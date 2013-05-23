<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/edit_helpdesk.html");
$Content = join("", $GetFile);

if (isset($_POST['submit']))
{
	// Parse form data
	$name		= $db->quote($_POST["name"]);
	$url		= $db->quote($_POST["url"]);

	// Update database
	$set	= " name={$name}";
	$set	.= ", url={$url}";

	$db->insert("update ".$prefix."help_desks set $set where id ='$id'");
	$msg = "edit";
	header("Location: help_desks.php?msg=$msg");
}

// read data from database
$mysql="select * from ".$prefix."help_desks where id='$id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);
$name		= $name;
$url		= $url;

$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include "footer.php";
?>