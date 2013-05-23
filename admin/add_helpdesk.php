<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/add_helpdesk.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Parse form data
	$name		= $db->quote($_POST["name"]);
	$url		= $db->quote($_POST["url"]);

	// Update database
	$set	= " name={$name}";
	$set	.= ", url={$url}";
	
	$pid = $db->insert_data_id("insert into ".$prefix."help_desks set $set") ;
	$msg = "add";
	header("Location: help_desks.php?msg=$msg");
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>