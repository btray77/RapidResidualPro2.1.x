<?php
include_once("session.php");
//include_once("header.php");

$rs=$db->get_a_line("select * from ".$prefix."admin_settings where id='1'");
$ltime=date("F j, Y, g:i:s a", $rs[lastlogin]);
$time=date("F j, Y, g:i:s a", time());
$q="update ".$prefix."admin_settings set lastlogin='".time()."' where id='".$admin_id."'";

$db->insert($q);
if(empty($destination))
	$destination='admin_menu.php';
header("Location: $destination");
?>