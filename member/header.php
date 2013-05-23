<?php
include_once "include.php";
$GetFile = file("../html/member/template.html");
$Content = join("",$GetFile);
ob_start();	

// Get html header information
$q = "select sitename, description, keywords, meta from ".$prefix."site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
$meta = stripslashes($meta);

// Get member status
$q = "select * from ".$prefix."members where id='$memberid'";
$m = $db->get_a_line($q);
@extract($m);
$status 	= $m[status];
	
// Get menu
$q = "select * from ".$prefix."misc_pages";
$r = $db->get_a_line($q);
@extract($r);
$member_menu	= stripslashes($r[member_menu]);
$affiliate_menu	= stripslashes($r[affiliate_menu]);

if($status =='1')
	{
	$menu = $affiliate_menu;
	}
elseif($status =='2')
	{
	$menu = $member_menu;
	}
elseif($status =='3')
	{
	$menu = $jv_menu;
	}	

include_once "content_list.php";
$content=str_replace("{tcontent}",$tcontent,$content);
$link="<a href=http://rapidresidualpro.com target=_blank>Powered by Rapid Residual Pro</a>";
?>