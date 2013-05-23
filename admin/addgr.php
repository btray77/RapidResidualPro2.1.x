<?php

include_once("session.php");
include_once("header.php");

$file=file("../html/admin/addgr.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Parse form data
	$gr_campaign		= $db->quote($_POST["gr_campaign"]);
	$rspname2			= $db->quote($_POST["rspname2"]);

	// Update database
	$set	= " gr_campaign={$gr_campaign}";
	$set	.= ", rspname2={$rspname2}";
	$set	.= ", rspname='GetResponse'";

	$pid = $db->insert_data_id("insert into ".$prefix."responders set $set") ;
	$msg = "add";
	header("Location: gr.php?msg=$msg");


}




$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>