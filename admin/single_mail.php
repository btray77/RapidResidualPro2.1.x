<?php

include_once("session.php");
include_once("header.php");

$file=file("../html/admin/single_mail.html");
$returncontent=join("",$file);

if (isset($_POST['send']))
{

	//$email_from_name	= $_POST["email_from_name"];
	$subject 			= $_POST["subject"];
	$message 			= $_POST["message"];
	//$footer		 		= $_POST["mailer_details"];
	//$webmaster_email	= $_POST["from_address"];

	$q = "select email_from_name, mailer_details from ".$prefix."site_settings where id=1";
	$r = $db->get_a_line($q);
	@extract($r);
	$footer	= $mailer_details;

	$q = "select webmaster_email from ".$prefix."admin_settings where id=1";
	$r = $db->get_a_line($q);
	@extract($r);

	$q = "select email, firstname, lastname, username from ".$prefix."members where id='".$mid."'";
	$r = $db->get_a_line($q);
	extract($r);
	//$subject=addslashes($subject);
	//$message=addslashes($message);
	$insql = "insert  ".$prefix."email_log set  subject ='$subject',message='$message',member_id='$mid'";
	$db->insert($insql);
	
	// send email to member
	$subject=stripslashes($subject);
	$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
	$message1 = $message."\n\n".$footer."\r\n" ;
	$message1=stripslashes($message1);
	$message1 = preg_replace("/{(.*?)}/e","$$1",$message1);
	$header	= "From: ".$email_from_name." <".$webmaster_email.">";
	@mail($email,$subject,$message1,$header) ;

	$msg = '<div class="success"><img align="absmiddle" src="../images/tick.png"> Email has been successfully sent to '.$email . '</div>';
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>