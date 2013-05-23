<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/editarp.html");
$Content = join("", $GetFile);

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
	$set	.= ", posturl={$posturl}";
	$set	.= ", trackingtag1={$trackingtag1}";
	$set	.= ", trackingtag2={$trackingtag2}";
	$set	.= ", trackingtag3={$trackingtag3}";
	$set	.= ", trackingtag4={$trackingtag4}";
	$set	.= ", trackingtag5={$trackingtag5}";

	$db->insert("update ".$prefix."responders set $set where id ='$id'");
	$msg = "edit";
	header("Location: arp.php?msg=$msg");
}

// read data from database
$mysql="select * from ".$prefix."responders where id='$id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);
$arp_list_id	= $arp_list_id;
$rspname2		= $rspname2;
$posturl		= $posturl;
$trackingtag1	= $trackingtag1;
$trackingtag2	= $trackingtag2;
$trackingtag3	= $trackingtag3;
$trackingtag4	= $trackingtag4;
$trackingtag5	= $trackingtag5;

$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include "footer.php";
?>