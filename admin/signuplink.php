<?php
include_once("session.php");
// Get admin email and site email details
$common = new common();
$q = "select sitename, email_from_name, mailer_details from ".$prefix."site_settings";
$a = $db->get_a_line($q);
@extract($a);
$q = "select webmaster_email from ".$prefix."admin_settings where id='1'";
$b = $db->get_a_line($q);
@extract($b);

// Get user email
$q = "select email as email from ".$prefix."members where randomstring='$randomstring'";
$a = $db->get_a_line($q);
@extract($a);

// send new member signup email to member
$q = "select subject, message from ".$prefix."emails where type='Email sent to new member for signup process'";
$r = $db->get_a_line($q);
@extract($r);
$signup_link = $http_path."/paypal_return.php?randomstring=".$randomstring;
$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
$message = preg_replace("/{(.*?)}/e","$$1",$message);
$message = $message."\r\n\r\n".$mailer_details;
$header	= "From: ".$email_from_name." <".$webmaster_email.">";
$common->sendemail($email_from_name, $webmaster_email, $email, $subject, $message, $header);
//@mail($email,$subject,$message,$header);
//echo "<center><br><br>Email Sent<br><br></center>";
header("Location: member_view.php?msg=msent");exit();
?>