<?php session_start();
include_once("common/config.php");
include ("include.php");
$pid  = $_GET['pid'];

$today = date('Y-m-d');
$rand = md5(uniqid(rand(),1));

$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$os = $common->getOS($user_agent);
$browser =$common->getBrowser($user_agent);

// Check affiliate invite code Yes/No

$sql="select affiliate_invite_code from ".$prefix."site_settings";
$row_invite = $db->get_a_line($sql);

if($row_invite['affiliate_invite_code']==1)
	{
		$code=$_REQUEST['code'];
		$sql="select count(id) as total,status,published from ".$prefix."invitecode where code='$code'";
		$row_code = $db->get_a_line($sql);
	}


// Get affiliate page info from database
$sql="select j.*,p.pagename,p.pcontent from ".$prefix."affiliate j,".$prefix."pages p where p.pageid = j.agreement and   id = '1'";
$GetProd = $db->get_a_line($sql);


@extract($GetProd);
$index_page			= stripslashes($index_page);
$psponder			= $psponder;
$pagename			= stripslashes($pagename);
$content = stripslashes($pcontent);

$obj_responder = new autoresponders($psponder,0);

if (isset($_REQUEST['Submit']))
	{
	// Clean up form data
	$firstname 		=  $db->quote($_REQUEST["firstname"]);
   	$lastname 		=  $db->quote($_REQUEST["lastname"]);
  	$email 			=  $db->quote($_REQUEST["email"]);
  	$paypal_email 	=  $db->quote($_REQUEST['paypal_email']);
 	$username 		=  $db->quote($_REQUEST["username"]);
	$password 		=  $db->quote(md5($_REQUEST["password"]));
	$pass 			=  $db->quote($_REQUEST["password"]);
	$ip			= $_SERVER['REMOTE_ADDR'];
	$rand 			= $_POST["rand"];
	$code			= $_POST["code"];
       	
       
	// Check if username or email already in database
	$Check = $db->get_a_line("select count(*) as Cnt from ".$prefix."members where email = {$email} or username = {$username}");

	if($Check[Cnt] != 0)	
		{
		// Username or Email already exist so return to form with error message
		$Message = "<div class='error'>Username or Email already exits</div>";
		}
		
	elseif($Check[Cnt] == 0)	
		{
		// Username and email valid so signup member		
		$set = "firstname  = {$firstname},";
		$set .= "lastname  = {$lastname},";
		$set .= "email  = {$email}, ";
		$set .= "paypal_email  = {$paypal_email},";
		$set .= "username  = {$username},";
		$set .= "password  = {$password},";
		$set .= "date_joined = now(),";
		$set .= "ip = '$ip',";
		$set .= "status = '1',";
		$set .= "ref = '$ref',";
		$set .= "randomstring = '$rand'";		
		
                $mid = $db->insert_data_id("insert into ".$prefix."members set $set");
                $_SESSION['memberid']=$mid;
                setcookie("memberid", $mid, 0, "/");
               
		$db->insert("update ".$prefix."invitecode set status='1' where code ='$code'");
                
                foreach($_REQUEST as $key => $items)
                {
                    $$key = $items;
                }
                
		
		// Get admin email and site email details		
		$q = "select email_from_name,from_name,sitename from ".$prefix."site_settings";
		$a = $db->get_a_line($q);
                
		$email_from_name = $a['email_from_name'];
                $from_name = $a['from_name'];
                $sitename = $a['sitename'];
               
		$q = "select webmaster_email from ".$prefix."admin_settings";
		$b = $db->get_a_line($q);
                $webmaster_email = $b['webmaster_email'];
		
		
		// send new member signup email to member
		$q = "select subject, message from ".$prefix."emails where type='Email sent to affiliate member after signup'";
		$r = $db->get_a_line($q);
		@extract($r);	
                
		
                
                $subject = preg_replace("/{(.*?)}/e","$$1",$subject);
               	$message = preg_replace("/{(.*?)}/e","$$1",$message);
		
		$header	= "From: ".$from_name." <". $email_from_name .">";
                $message = str_replace("'", "", $message);
                
               
		$common->sendemail($email_from_name,$webmaster_email,$email,$subject,$message,$header);
               
                
		$q = "select subject, message from ".$prefix."emails where type='Email sent to admin after affiliate signup'";
		$r = $db->get_a_line($q);
		@extract($r);
				
		
		
		// send new affiliate signup email to admin
		
                    
		$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
		$message = preg_replace("/{(.*?)}/e","$$1",$message);
		$message = str_replace("'", "", $message);
		$common->sendemail($firstname,$email,$webmaster_email,$subject,$message,$header);
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
					<form name="affiliateform" id="affiliateform" action='<?php echo $http_path?>/member/index.php' method="post">
					<input type=hidden name="mrand" value='<?php echo $rand?>'>
					<input type=hidden name="pid" value='<?php echo $pid?>'>
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
                                              document.forms['affiliateform'].submit();
                                                              }
                                        </script>		
					</body>
		</html>
		<?php
        exit ();
		}
	}

	$smarty->assign('message',$Message);
	$smarty->assign('hidelink1',$hidelink1);
	$smarty->assign('hidelink2',$hidelink2);
	$smarty->assign('pid',$pid);
	$smarty->assign('rand',$rand);
	$smarty->assign('index_page',$index_page);
	
	
	$smarty->assign('content',$content);
	$smarty->assign('code',$code);
	
	if($row_invite['affiliate_invite_code']==1 && !empty($code))
	{
		if( $row_code['total'] > 0 && $row_code['status']==0)	
			{
			$outputsignup = $smarty->fetch('html/affiliate.tpl');
			$smarty->assign('code',$code);
			}
		else if($row_code['status']==1)
			$outputsignup = '<div class="error">Your invitation code is already used.Please contact to administrator for more details</div>';
		else
			$outputsignup = '<div class="error">Your invitation code incorrect. Please contact to your administrator</div>';
	}
	else if($row_invite['affiliate_invite_code']==0)
	{
		$outputsignup = $smarty->fetch('html/affiliate.tpl');
	}
	else 	
		$outputsignup = '
		<div class="error">
		We\'re sorry but this program is not available to everyone. 
		You may forward your request to enroll by providing a detailed description about 
		your business and how you will promote our programs, products and services by 
		<a href="/content.php?page=contact-us">clicking here</a>. If approved, you will be contacted by Admin within 48 hours.
		</div>';
	
	
	
	$smarty->assign('pagename','Affiliate Membership');
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
	$smarty->assign('content',$output);
	$smarty->assign('error',$warning);
	$smarty->display($FILEPATH.'/index.html');	
?>