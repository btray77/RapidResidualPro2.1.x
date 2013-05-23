<?php
//$hidelink1="<!--";
//$hidelink2="-->";
include_once("../common/config.php");
include_once("include.php");
/*$GetFile = file("../html/member/forgot_password.html");
$pagecontent = join("",$GetFile);
*/
if (isset($_POST['submit']))
	{ 
	// Set variables from form	
	$uname 	= trim(stripslashes($_POST['user']));
	$email 	= trim(stripslashes($_POST['email']));

	// make sure both form fields were filled out
	if ((!$uname) || (!$email))
 		{ 
 		$error = "<div class='error'><img src='/images/crose.png' border='0'> One or more fields were not filled out.</div>";
		} 
	// check if member is in database
	$mysql="select count(*) as cnt from ".$prefix."members where username='$uname'";
	$r=$db->get_a_line($mysql);
        
	if($r[cnt]==0)
		{
		$error = "<div class='error'><img src='/images/crose.png' border='0'>Username not found in database.</div>";
		}
	elseif($r[cnt]>0)
		{ 
		$q = "select email as fEmail from ".$prefix."members where username='$uname'";
		$r = $db->get_a_line($q);
		@extract($r);
		if($fEmail != $email)
			{ 
			$error = "<div class='error'><img src='/images/crose.png' border='0'> Email address did not match.</div>";
			}		
		elseif($fEmail == $email)
			{
			$password=$common->createRandomPassword();			
			$newpass = md5("$password");
                        
			// reset password
			 $mysql="update ".$prefix."members set password='$newpass' where username='$uname'";
			$db->insert($mysql);
                       
			// get member details
			$q = "select email, firstname from ".$prefix."members where username='$uname'";
			$r = $db->get_a_line($q);
			@extract($r);
                        $username = $uname; 
			// get admin details
			$q = ("select from_name as from_name,email_from_name  from ".$prefix."site_settings");
			$r = $db->get_a_line($q);
			@extract($r);

			// send email to member with new password.
			$q = "select subject, message from ".$prefix."emails where type='Email sent to member for password reset'";
			$r = $db->get_a_line($q);
			@extract($r);
			$login_link = '<a href="'.$http_path."/member/index.php".'">Click here</a> to login into member section';
			$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
			$message = preg_replace("/{(.*?)}/e","$$1",$message);
			
			$common->sendemail($email_from_name, $email_from_name, $email, $subject, $message, $header);
							
			// end change password code
			$error = "<div class='success'><img src='/images/tick.png' border='0'> A temporary password was mailed to your email address on file. Please check your email now.</div>";
			}
		}
	}

$output_login = $smarty->fetch('../html/member/forgot_password.tpl');

$smarty->assign('errorx',$error);
$smarty->assign('pagename','Forget Password');
$smarty->assign('main_content',$output_login);

$output = $smarty->fetch('../html/member/content.tpl');
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
	$smarty->display($FILEPATH.'/index.html');	
	
/*$pagecontent = preg_replace("/{{(.*?)}}/e","$$1",$pagecontent) ;
include_once ("header.php");
$Content 		= preg_replace("/{{(.*?)}}/e","$$1",$Content);
$Content 		= preg_replace("/<{(.*?)}>/e","$$1",$Content);
echo $Content;	*/
?>