<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/addarp.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Parse form data
	$arp_list_id	= $db->quote($_POST["arp_list_id"]);
	$rspname2		= $db->quote($_POST["rspname2"]);
	$posturl		= $db->quote($_POST["posturl"]);
	$trackingtag1	= $db->quote($_POST["trackingtag1"]);
	$trackingtag2	= $db->quote($_POST["trackingtag2"]);
	$trackingtag3	= $db->quote($_POST["trackingtag3"]);
	$trackingtag4	= $db->quote($_POST["trackingtag4"]);
	$trackingtag5	= $db->quote($_POST["trackingtag5"]);

	// Update database
	$set	= " arp_list_id={$arp_list_id}";
	$set	.= ", rspname2={$rspname2}";
	$set	.= ", rspname='ARP'";
	$set	.= ", posturl={$posturl}";
	$set	.= ", trackingtag1={$trackingtag1}";
	$set	.= ", trackingtag2={$trackingtag2}";
	$set	.= ", trackingtag3={$trackingtag3}";
	$set	.= ", trackingtag4={$trackingtag4}";
	$set	.= ", trackingtag5={$trackingtag5}";

	$pid = $db->insert_data_id("insert into ".$prefix."responders set $set") ;
	$msg = "add";
	header("Location: arp.php?msg=$msg");
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>