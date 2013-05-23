<?php
include_once("session.php");
include_once("../common/config.php");
include_once("../common/common.class.php");
include_once("../common/database.class.php");

$db= new database;
$common= new common;

// Log Admin out of system.
$time=time();
$db->insert("update ".$prefix."admin_settings set lastlogin='$time' where id='$admin_id'");
$mysql="delete from ".$prefix."admin_session where admin_id='$admin_id'";
$db->insert($mysql);
setcookie("admin","",0,"/");
header("Location:index.php");
exit;
?>