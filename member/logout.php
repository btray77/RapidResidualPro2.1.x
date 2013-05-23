<?php
include_once("include.php");
include_once("session.php");

$rs=$db->get_single_column("select time from ".$prefix."member_session where member_id='$memberid'");
$db->insert("update ".$prefix."members set last_login='".$rs[0]."' where id='$memberid'");
$mysql="delete from ".$prefix."member_session where member_id='$memberid'";
$db->insert($mysql);
unset($_SESSION['memberid']);
setcookie("memberid",0,0,"/");
setcookie("memcookie",0,0,"/");
header("Location: login.php?err=log");
exit;
?>