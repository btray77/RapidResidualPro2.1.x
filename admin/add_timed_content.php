<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/add_timed_content.html");
$returncontent=join("",$file);
$back = '<a href=list_timed_content.php?con='.$con.'>Go Back</a>';

if (isset($_POST['submit']))
{
	// Parse form data
	$pcontent		= addslashes($_POST["pcontent"]);
	$available		= $_POST["available"];
	$pagename		= $_POST["pagename"];
	$filename		= $_POST["filename"];
	$comments		= $db->quote($_POST["comments"]);
	$campaign		= $_POST["campaign"];

	// Update database
	$set = "pcontent  	= {$db->quote(trim($pcontent))},";
	$set .= "available	={$db->quote($available)},";
	$set .= "pagename  = {$db->quote($pagename)},";
	$set .= "campaign  	= {$db->quote($campaign)},";
	$set .= "comments	= {$comments},";
	$set .= "filename  = {$db->quote($filename)}";
	$pid = $db->insert_data_id("insert into ".$prefix."timed_content set $set") ;
	$msg = "a";
	header("Location: list_timed_content.php?con=$campaign&msg=$msg");
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>