<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/editgr.html");
$Content = join("", $GetFile);

if (isset($_POST['submit']))
{
	// Parse form data
	$gr_campaign		= $db->quote($_POST["gr_campaign"]);
	$rspname2		= $db->quote($_POST["rspname2"]);

	// Update database
	$set	= " gr_campaign={$gr_campaign}";
	$set	.= ", rspname2={$rspname2}";

	$db->insert("update ".$prefix."responders set $set where id ='$id'");
	$msg = "edit";
	header("Location: gr.php?msg=$msg");
}

// read data from database
$mysql="select * from ".$prefix."responders where id='$id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);
$gr_campaign		= $gr_campaign;
$rspname2			= $rspname2;

$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include "footer.php";
?>