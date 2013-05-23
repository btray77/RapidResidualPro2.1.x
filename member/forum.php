<?php
session_start();
if(empty($_SESSION['memberid']))
 {	header("location:/member/"); exit();}
define('IN_PHPBB', true);
include_once("../common/config.php");
include_once("../common/database.class.php");
$db = new database();
$mysql = "select * from " . $prefix . "members where id={$_SESSION[memberid]}";
$rslt = $db->get_a_line($mysql);
if ($rslt['is_block'] == 1) {
header("Location: login.php?err=blocked");
exit;
}
 $username = $rslt["username"];

$password = $_SESSION['password'];
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
$navtarget = "index.php";
$user->session_begin();
$auth->acl($user->data);
$user->setup();
$auth = new auth();
$values = $auth->login($username, $password);
if(empty($values['error_msg']))
	header("location:../forum/");
else
	echo $values['error_msg'];
exit();
	
?>
