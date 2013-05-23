<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/mindex.html");
$Content = join("", $GetFile);

function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}

if (isset($_POST['submit']))
{
	$member_main		= $db->quote($_POST["member_main"]);
	$jv_main		= $db->quote($_POST["jv_main"]);
	$affiliate_main		= $db->quote($_POST["affiliate_main"]);
	$memb_menu = $db->quote($_POST["member_menu"]);
	$jv_menu = $db->quote($_POST["jv_menu"]);
	$aff_menu = $db->quote($_POST["aff_menu"]);

	$set = "member_main  = {$member_main}, ";
	$set .= "jv_main = {$jv_main}, ";
	$set .= "affiliate_main	= {$affiliate_main},";
	$set .= "member_menu_id = {$memb_menu}, ";
	$set .= "affiliate_menu_id = {$aff_menu}, ";
	$set .= "jv_menu_id = {$jv_menu}";

	$q = "update ".$prefix."misc_pages set $set where id='1'";
	$db->insert($q);
	$msg = "<div class='success'><img src='/images/tick.png' align='absmiddle'>Index Pages Successfully Edited.</div>";
}

$mysql = "select * from ".$prefix."misc_pages where id='1'";
$rslt = $db->get_a_line($mysql);
$member_main = stripslashes($rslt["member_main"]);
$memb_menus = stripslashes($rslt["member_menu_id"]);
$jv_main = stripslashes($rslt["jv_main"]);
$jv_menus = stripslashes($rslt["jv_menu_id"]);
$affiliate_main = stripslashes($rslt["affiliate_main"]);
$aff_menus = stripslashes($rslt["affiliate_menu_id"]);

// Getting menu items
	$qry_menu = "select * from ".$prefix."menus where published = '1' ORDER BY id ASC";
	$res_menu = $db->get_rsltset($qry_menu);
// Member menu combo box
	$member_menu = "<select name='member_menu' id='member_menu'><option value='0'>Select menu</option>";
	for($i = 0; $i < count($res_menu); $i++){
		$member_menu_name = $res_menu[$i]['menu_name'];
		$member_menu_id = $res_menu[$i]['id'];
		if($member_menu_id == $memb_menus){
			$mem_selected = "selected = 'selected'";
		}else{
			$mem_selected = "";	
		}	
		
		$member_menu .= "<option value='".$member_menu_id."' ".$mem_selected.">".$member_menu_name."</option>";	
	}
	$member_menu .= "</select>";

// JV menu combo box
	$jv_menu = "<select name='jv_menu' id='jv_menu'><option value='0'>Select menu</option>";
	for($j = 0; $j < count($res_menu); $j++){
		$jv_menu_name = $res_menu[$j]['menu_name'];
		$jv_menu_id = $res_menu[$j]['id'];
		if($jv_menu_id == $jv_menus){
			$jv_selected = "selected = 'selected'";
		}else{
			$jv_selected = "";	
		}	
		$jv_menu .= "<option value='".$jv_menu_id."' ".$jv_selected.">".$jv_menu_name."</option>";	
	}
	$jv_menu .= "</select>";

// Affiliate menu combo box
	$aff_menu = "<select name='aff_menu' id='aff_menu'><option value='0'>Select menu</option>";
	for($k = 0; $k < count($res_menu); $k++){
		
		$aff_menu_name = $res_menu[$k]['menu_name'];
		$aff_menu_id = $res_menu[$k]['id'];
		if($aff_menu_id == $aff_menus){
			$aff_selected = "selected = 'selected'";
		}else{
			$aff_selected = "";	
		}	
		$aff_menu .= "<option value='".$aff_menu_id."' ".$aff_selected.">".$aff_menu_name."</option>";	
	
	}
	$aff_menu .= "</select>";
	

$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include "footer.php";
?>