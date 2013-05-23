<?php

include "session.php" ;
include "header.php" ;

$GetFile = file("../html/admin/editaweber.html");
$Content = join("", $GetFile);

if (isset($_POST['submit']))
{
	// Parse form data
	$aweber_unit		= $db->quote($_POST["aweber_unit"]);
	$aweber_meta		= $db->quote($_POST["aweber_meta"]);
	$rspname2		= $db->quote($_POST["rspname2"]);

	// Update database

	$set	= " aweber_unit={$aweber_unit}";
	$set	.= ", aweber_meta={$aweber_meta}";
	$set	.= ", rspname2={$rspname2}";

	$db->insert("update ".$prefix."responders set $set where id ='$id'");
	$msg = "edit";
	header("Location: aweber.php?msg=$msg");
}

// read data from database
$mysql="select * from ".$prefix."responders where id='$id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);

$aweber_unit		= $aweber_unit;
$aweber_meta		= $aweber_meta;
$rspname2			= $rspname2;

$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;

include "footer.php";
?>