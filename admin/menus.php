<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/menus.html");
$returncontent=join("",$file);

function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}

if (isset($_POST['submit']))
{
	// Set variables from form
	$member_menu		=  $db->quote($_POST["member_menu"]);
	$affiliate_menu		=  $db->quote($_POST["affiliate_menu"]);
	$jv_menu			=  $db->quote($_POST["jv_menu"]);

	// Upadate Dababase Information
	$set	= "member_menu={$member_menu}";
	$set	.= ", affiliate_menu={$affiliate_menu}";
	$set	.= ", jv_menu={$jv_menu}";
	$q = "update ".$prefix."misc_pages set $set where id='1'";
	$db->insert($q);

	$msg = "Menus Successfully Edited.";
}

$mysql="select * from ".$prefix."misc_pages where id='1'";
$rslt=$db->get_a_line($mysql);
$member_menu=$rslt["member_menu"];
$affiliate_menu=$rslt["affiliate_menu"];
$jv_menu=$rslt["jv_menu"];

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>