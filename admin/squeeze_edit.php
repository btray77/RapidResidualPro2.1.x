<?php
include_once("session.php");
include_once("header.php");
$GetFile = file("../html/admin/squeeze_edit.html");
$Content = join("", $GetFile);
$Title = "Edit Squeeze Page";

if (isset($_POST['submit']))
{
	// Parse form data
	$page_name	= $db->quote($_POST["page_name"]);
	$squeezepage			= $db->quote($_POST["squeezepage"]);
	$comments				= $db->quote($_POST["comments"]);
	$width					= $db->quote($_POST["width"]);
	$access					= $db->quote($_POST["access"]);
	$keyword				= $db->quote($_POST["keyword"]);
	$seo_title				= $db->quote($_POST["seo_title"]);
	$page_title		= $db->quote($_POST["page_title"]);
	$meta_discription		= $db->quote($_POST["meta_discription"]);
	// Edit database
	$set = "comments		= {$comments}, ";
	$set .= "name			= {$page_name}, ";
	$set .= "width			= {$width}, ";
	$set .= "access			= {$access}, ";
	$set .= "meta_discription	= {$meta_discription},";
	$set .= "keyword			= {$keyword}, ";
	$set .= "seo_title			= {$seo_title}, ";
	$set .= "page_title			= {$page_title}, ";
	$set .= "squeezepage 	= {$squeezepage}";
	$sql =	"update ".$prefix."squeeze_pages set $set where id = '$pid'";
	
	$db->insert($sql);
	$msg = "e";
	header("Location:squeeze_view.php?msg=$msg");
	exit;
}

// read data from database
$mysql="select * from ".$prefix."squeeze_pages where id='$id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);
$commentscheck	= stripslashes($rslt["comments"]);
$accesscheck	= stripslashes($rslt["access"]);
$squeezepage	= stripslashes($rslt["squeezepage"]);
$keyword	= stripslashes($rslt["keyword"]);
$seo_title	= stripslashes($rslt["seo_title"]);
$meta_discription	= stripslashes($rslt["meta_discription"]);
$page_title  = stripslashes($rslt["page_title"]);
if ($commentscheck == 'yes')
	$comments1 = 'checked';
else 
	$comments2 = 'checked';

if ($accesscheck == 'Public')
	$access1 = 'checked';
else 
	$access2 = 'checked';



$Content = preg_replace("/{{add_hide_begin}}(.*?){{add_hide_end}}/s","",$Content);
$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include_once("footer.php");
?>