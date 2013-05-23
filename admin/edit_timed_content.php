<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/edit_timed_content.html");
$returncontent=join("",$file);
$back = '<a href=list_timed_content.php?con='.$con.'>Go Back</a>';

function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}

if (isset($_POST['submit']))
{
	// Parse form data
	$pcontent		= addslashes($_POST["pcontent"]);
	//$pcontent 		= encodeHTML($pcontent);
	$available		= $_POST["available"];
	$pagename		= $_POST["pagename"];
	$comments		= $db->quote($_POST["comments"]);
	$filename		= $_POST["filename"];

	// Update database
	$set = "pcontent  	= {$db->quote($pcontent)},";
	$set .= "available	={$db->quote($available)},";
	$set .= "pagename  = {$db->quote($pagename)},";
	$set .= "comments	= {$comments},";
	$set .= "filename  = {$db->quote($filename)}";


	$db->insert("update ".$prefix."timed_content set $set where pageid = '$pageid'");
	$msg = "e";
	header("Location: list_timed_content.php?con=$campaign&msg=$msg");
}
$sql="select * from ".$prefix."timed_content where pageid = '$id'";
$GetProd = $db->get_a_line("$sql");
@extract($GetProd) ;

$pcontent		= stripslashes($GetProd['pcontent']);
$available		= $available;
$campaign		= $campaign;
$pagename		= $pagename;
$filename		= $filename;
$commentscheck	= stripslashes($comments);

if ($commentscheck == 'yes')
{
	$comments1 = 'checked';
}
else if ($commentscheck == 'no')
{
	$comments2 = 'checked';
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>