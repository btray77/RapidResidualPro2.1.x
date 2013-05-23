<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/edit_email.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Post Data
	$subject	= $db->quote($_POST["subject"]);
	$message	= $db->quote($_POST["message"]);



	// Update database
	$q = "update ".$prefix."emails set subject={$subject}, message={$message} where id='$mailId' ";
	$db->insert($q);
	$msg=2;

	header("Location: emails.php?msg=$msg");
	exit;
}

else
{
	// read data from database
	$sql = "select * from ".$prefix."emails where id='$mid' ";
	$rs = $db->get_a_line($sql);

	$mailId = $rs['id'];
	$subject = stripslashes($rs['subject']);
	$message = stripslashes($rs['message']);
	$from_name=$rs['from_name'];
	$from_email=$rs['from_email'];
	$email_type=str_replace("_", " ", $rs['type']);
}

// show page
$returncontent=preg_replace("/<{(.*?)}>/e","$$1",$returncontent);
$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>