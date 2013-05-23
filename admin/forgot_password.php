<?php
include_once("ano-header.php");
include_once("../common/config.php");
include_once("../include.php");
$GetFile = file("../html/admin/forgot_password.html");
$Content = join("",$GetFile);

if (isset($_POST['submit']))
{
	// Set variables from form
	$uname =  $_POST["user"];
	$email = $_POST["email"];

	// Get admin info from database
	$q = ("select id , username,webmaster_email from ".$prefix."admin_settings where username = '$uname' and webmaster_email ='$email'");
	$r = $db->get_a_line($q);
	$fUser=$r["username"];
        $username=$r["username"];
	$fEmail=$r["webmaster_email"];
        $login_link="<a href=".$http_path.'/admin/index.php'."> Click here</a> to login into admin panel";
        $id=$r["id"];
        
	if ((!$uname) || (!$email))
	{
		// One or both fields not filled out.
		 $error ='<div class="top-message"><img src="../images/crose.png" align="absmiddle">One or more fields were not filled out.</div>';
		
	}
	elseif($uname == $fUser)
	{
		// Both fields filled out so process.
		if($fEmail != $email)
		{
			// Email not correct.
			$error = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Email address did not match.</div>';
		
		}
		elseif($fEmail == $email)
		{
			// Generate a random password
			$password=$common->createRandomPassword();
			$newpass=md5("$password");

			// Reset password
                        $sql="update ".$prefix."admin_settings set password='$newpass' where id=$id ";
                
			$db->insert($sql);

			// Get admin details
			$q = ("select webmaster_email as email from ".$prefix."admin_settings where id=$id");
			$r = $db->get_a_line($q);
			@extract($r);

			// Send email to admin with new password.
			$q = ("select subject, message from ".$prefix."emails where type='Email sent to admin for password reset'");
			$r = $db->get_a_line($q);
			@extract($r);
				
			$q = ("select from_name as from_name,email_from_name  from ".$prefix."site_settings");
			$rr = $db->get_a_line($q);
			@extract($rr);
				
			$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
			$message = preg_replace("/{(.*?)}/e","$$1",$message);
			$header   = "To: ".$webmaster_email."\r\n";
			$header	.= "From: ".$from_name." <".$email_from_name.">";
                        
			$common->sendemail($from_name,$email_from_name,$email,$subject,$message,$header) ;
                       
			// End change password code
			$error='<div class="success"><img src="../images/tick.png" align="absmiddle">A temporary password was mailed to your email address on file. Please check your email now.</div>';
		}
	}
	elseif($uname != $fUser)
	{
		// Username doesn't match.
		$error = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Username did not match.</div>';
		
	}
}

// Display page
$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;

?>