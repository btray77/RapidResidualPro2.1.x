<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/addshorturl.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Parse form data
	$url		= $db->quote($_POST["url"]);
	$nickname	= $db->quote($_POST["nickname"]);

	// Update database
	$set	= " url={$url}";
	$set	.= ", nickname={$nickname}";

	$pid = $db->insert_data_id("insert into ".$prefix."recommends set $set") ;
	$msg = "add";
	header("Location: shorturl.php?msg=$msg");
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>