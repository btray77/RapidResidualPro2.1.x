<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/editshorturl.html");
$Content = join("", $GetFile);

if (isset($_POST['submit']))
{
	// Parse form data
	$url		= $db->quote($_POST["url"]);
	$nickname	= $db->quote($_POST["nickname"]);

	// Update database
	$set	= " url={$url}";
	$set	.= ", nickname={$nickname}";

	$db->insert("update ".$prefix."recommends set $set where id ='$id'");
	$msg = "edit";
	header("Location: shorturl.php?msg=$msg");
}

// read data from database
$mysql="select * from ".$prefix."recommends where id='$id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);
$url		= $url;
$nickname	= $nickname;

$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include "footer.php";
?>