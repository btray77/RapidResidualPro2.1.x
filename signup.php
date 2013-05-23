<?php
session_start();
include_once("common/config.php");
include ("include.php");
$title = "Membership Form";
$warning = "Fill out the form below to finish the payment process.";
$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$os = $common->getOS($user_agent);
$browser =$common->getBrowser($user_agent);
if($_GET['cbreceipt']!="" && $_GET['cbpop']!=""){
    $click_bank_message="<div class='success'>You Buy successfully </div>!";
}
$custom=$_COOKIE['custom'];
$str			= explode('|',$custom);
//$randomstring	= $str[0];
$pid			= $str[3];	
$ref			= $str[4];
$today = date('Y-m-d');
$randomstring  = $_REQUEST["randomstring"];
$obj_responder = new autoresponders('',$pid);
if (isset($_REQUEST['Submit']))
	{	
	// Clean up form data
	$firstname 		=  $_REQUEST["firstname"];
   	$lastname 		=  $_REQUEST["lastname"];
  	$email 			=  $_REQUEST["email"];
 	$username 		=  $_REQUEST["username"];
	$password 		=  $_REQUEST["password"];
	$pass 			=  $_REQUEST["password"];
	$password		=  md5($password);   // Encript password
	$ip				=  $_SERVER['REMOTE_ADDR'];
	$randomstring   =  $_REQUEST["randomstring"];
	$country 		= addslashes(trim($_POST["country"]));
	$city 			= addslashes(trim($_POST["city"]));
	$latitude 		= addslashes(trim($_POST["latitude"]));
	$longitude 		= addslashes(trim($_POST["longitude"]));
	$operating_system = addslashes(trim($_POST["operating_system"]));
	$browser 		= addslashes(trim($_POST["browser"]));
	$destination	=trim($_REQUEST["destination"]);
	
	// Check if username or email already in database
	 $sql_count="select count(*) as Cnt from ".$prefix."members where username ='". $username."'";
	
	$Check = $db->get_a_line($sql_count);
	if($Check[Cnt] != 0)	
		{
                $_SESSION["fname"]=$_POST["firstname"];
                $_SESSION["lname"]=$_POST["lastname"];
                $_SESSION["uname"]=$_POST["username"];
                $_SESSION["email"]=$_POST["email"];
		// Username or Email already exist so return to form with error message
		$Message = "Username already exits";
                 if($_POST["page"]=="clickbank"){
                    $call = $http_path."/clickbank.php?randomstring=".$_POST["randomstring"]."&pid=".$_REQUEST['pid']."&msg=u";
                    header("Location: ".$call);
                    exit;
                } else {
                    $call = $http_path."/signup.php?randomstring=".$_POST["randomstring"]."&pid=".$_REQUEST['pid']."&msg=u";
                    header("Location: ".$call);
                    exit;
                }
		}
		
	elseif($Check[Cnt] == 0)	
		{
		// Username and email valid so signup member
				
		$time=time();
		$last_login=time();
		$set = "firstname  = {$db->quote($firstname)},";
		$set .= "lastname  = {$db->quote($lastname)},";
		$set .= "email  = {$db->quote($email)},";
		$set .= "username  = {$db->quote($username)},";
		$set .= "password  = {$db->quote($password)},";
		$set .= "ref  = '$ref',";
                $set .= "last_login = $time";
		$sql="update ".$prefix."members set $set where randomstring = '$randomstring'";
		
		$mid = $db->insert_data_id($sql);
                $_SESSION['memberid']=$mid;
                setcookie("memberid", $mid, 0, "/");
		
             /* $mysql="insert ".$prefix."member_session set hash='$rand', time='$time', `country` = '{$country}', 
                `city` = '{$city}', `latitude` = '{$latitude}', `longitude` = '{$longitude}', 
                `operating_system` = '{$operating_system}', `browser` = '{$browser}',member_id='$mid' ";
                $db->insert($mysql);
              
              */  
		// Get Product name
		$q = "select * from ".$prefix."orders o, ".$prefix."products p where p.id = o.item_number and o.randomstring='$randomstring'";
		$aa = $db->get_a_line($q);
		$product_name = $aa['item_name'];
		$pshort = $aa['pshort'];
		
		// Get admin email and site email details		
		$q = "select sitename, email_from_name, mailer_details from ".$prefix."site_settings";
		$a = $db->get_a_line($q);
		@extract($a);		
		$q = "select webmaster_email from ".$prefix."admin_settings";
		$b = $db->get_a_line($q);
		@extract($b);		
		
		// send new member signup email to member
		$q = "select subject, message from ".$prefix."emails where type='Email sent to paid member after signup'";
		$r = $db->get_a_line($q);
		@extract($r);		
		$login_link = $http_path."/member/index.php";		
				
		$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
		$message = preg_replace("/{(.*?)}/e","$$1",$message);
		$message = $message."\r\n\r\n".$mailer_details;
		$header	= "From: ".$email_from_name." <".$webmaster_email.">";
		@mail($email,$subject,$message,$header);			
			
		// send new member signup email to admin
		$q = "select subject, message from ".$prefix."emails where type='Email sent to admin after product payment'";
		$r = $db->get_a_line($q);
		@extract($r);
	
		$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
		
		$message = preg_replace("/{(.*?)}/e","$$1",$message);
		$header	= "From: ".$email_from_name." <".$webmaster_email.">";
		@mail($webmaster_email,$subject,$message,$header);
		
		/******************** Sending mail to merchant email ************************/
		$rand_string = $_REQUEST['randomstring'];
		// Getting payee email through products
		$query_product = "select * from ".$prefix."orders where randomstring ='$rand_string'";
		$row_product = $db->get_a_line($query_product);
		$payee_email = trim($row_product['payee_email']);
				
		$msg_body = str_replace("Admin", '', $message);
		$msg_body1 = str_replace("Dear", 'Dear '.$payee_email, $msg_body);
		$msg_body2 = str_replace("Thankyou.", 'Thankyou. \n\n Admin', $msg_body1);
						
		@mail($payee_email,$subject,$msg_body2,$header);
		/******************** Sending mail to merchant email ************************/
		
		 /******************  ADD TO AUTO RESPONDERS   ************************/
             $autoresponder = $obj_responder -> process_Autoresponders();
          /******************  END TO AUTO RESPONDERS   ************************/	
		
		
		?>
		
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <script src="/common/newLayout/jquery/jquery.js" type="text/javascript" charset="utf-8"></script>
                <link rel="stylesheet" href="/common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8"/>
                <script src="/common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"/></script>
				<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>
                <style>
                body{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:15px;text-align:center}
                p { font-size: 1.2em; }
                div.facebook #pp_full_res .pp_inline{text-align:center}
                .pp_details{display:none;}
                </style> 
                <body>
					<form id="memberform" name="memberform" action='<?php echo $http_path?>/member/index.php' method="post">
					<input type=hidden name="mrand" value='<?php echo $randomstring?>'>
					<input type=hidden name="pshort" value='<?php echo $pshort?>'>	
					<input name="country" id="country" value=""  type="hidden"  />
					<input name="city" value="" id="city"  type="hidden"  />
					<input name="latitude" value="" id="latitude" type="hidden"  />
					<input name="longitude" value="" id="longitude"  type="hidden"  />
					<input name="operating_system" value="<?php echo $os ?>"  type="hidden"  />
					<input name="browser" value="<?php echo $browser ?>"  type="hidden"  />
					</form>
                                    <div id="main">
                                    <a href="#inline_demo" rel="prettyPhoto[inline]"></a>
                                        <div id="inline_demo" style="display:none;">
                                            <p>Please Wait....</p>
                                            <p style="display: none;"><?php echo $autoresponder?></p>
                                            <p id="message"></p>
                                        </div>
                                    <script type="text/javascript" charset="utf-8">
                                    $(document).ready(function(){
                                    $("a[rel^='prettyPhoto']").prettyPhoto().trigger('click');
                                    $('.pp_content').css("height","114px");
                                    document.getElementById('country').value=geoip_country_name();
                                    document.getElementById('city').value=geoip_city();
                                    document.getElementById('latitude').value=geoip_latitude();
                                    document.getElementById('longitude').value=geoip_longitude();
                                    $('#message').html('<img src="/images/wait.gif" border="" alt="loading...." />');  
                                    var data = 'username=<?php echo $username?>&email=<?php echo $email?>&password=<?php echo $org_password?>';  	
                                    $.ajax({  
                                      type: "POST",  
                                      url: "forum.php",  
                                      data: data,  
                                      success: function(data) {  
                                            $('#message').html("<p>Forum Registration!</p>") 
                                            .append('<p>You are successfully become a member of our forum</p><img src="/images/wait.gif" border="" alt="loading...." />');  
                                            
                                            }  
                                    });
										setTimeout("submitform()",1500);
                                    });
                                    </script>
                                    </div>
                    <script type="text/javascript">
					function submitform(){
						 document.forms['memberform'].submit();
					  }
                    </script>		
					</body>
		</html>
		<?php	
		 exit ();		
		}
	}
	 if($_GET["msg"]=="s"){
            $msg="<div class='error'>Invalid Security Code</div>";
        } else if($_GET["msg"]=="u") {
            $msg="<div class='error'>This Username Already exist !</div>";
        }
	$smarty->assign('msg',$msg);
        
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $os = getOS($user_agent);
        $browser = getBrowser($user_agent);	
	
        $smarty->assign('os', $os);
        $smarty->assign('browser', $browser);
		$smarty->assign('message',$Message);
        $smarty->assign('fname',$_SESSION["fname"]);
        $smarty->assign('lname',$_SESSION["lname"]);
        $smarty->assign('email',$_SESSION["email"]);
        $smarty->assign('uname',$_SESSION["uname"]);
       
        $smarty->assign('click_bank_message',$click_bank_message);
	$smarty->assign('hidelink1',$hidelink1);
	$smarty->assign('page','signup');
	$smarty->assign('pid',$pid);
	$smarty->assign('hidelink2',$hidelink2);
	$smarty->assign('randomstring',$randomstring);
	if(!empty($randomstring))
	{
		$outputsignup = $smarty->fetch('html/signup.tpl');	
	}
	else 
		$outputsignup = $common->show_error('5');
	//$warning=
	
	
	$smarty->assign('pagename',$title);
	$smarty->assign('main_content',$outputsignup);
	$output = $smarty->fetch('html/content.tpl');
	
	$objTpl = new TPLManager($FILEPATH.'/index.html');
	$hotspots = $objTpl->getHotspotList();
	$placeHolders = $objTpl->getPlaceHolders($hotspots);
	$i=0;
	foreach($placeHolders as  $items)
	{
		$smarty->assign("$hotspots[$i]","$items");
		$i++;
	}
       
	$smarty->assign('error',$error);
	$smarty->assign('content',$output);
	
	$smarty->display($FILEPATH.'/index.html');
        unset($_SESSION["fname"],$_SESSION["lname"],$_SESSION["uname"],$_SESSION["email"]);
        
function getBrowser($user_agent){
    if(strstr($user_agent, 'ie')) return 'Internet Explorer';
    if(strstr($user_agent, 'firefox')) return 'Firefox';
}
function getOS($user_agent){
    if(strstr($user_agent, 'windows')) return 'Windows';
    if(strstr($user_agent, 'linux')) return 'Linux';
    if(strstr($user_agent, 'apple')) return 'Apple';
}	
        
        
?>