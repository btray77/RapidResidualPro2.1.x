<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/addaweber.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Parse form data
	$aweber_unit		= $db->quote($_POST["aweber_unit"]);
	$aweber_meta		= $db->quote($_POST["aweber_meta"]);
	$rspname2			= $db->quote($_POST["rspname2"]);

	// Update database
	$set	= " aweber_unit={$aweber_unit}";
	$set	.= ", aweber_meta={$aweber_meta}";
	$set	.= ", rspname2={$rspname2}";
	$set	.= ", rspname='Aweber'";

	$pid = $db->insert_data_id("insert into ".$prefix."responders set $set") ;
	$msg = "add";
	header("Location: aweber.php?msg=$msg");
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>