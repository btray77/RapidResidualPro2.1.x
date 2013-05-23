<?php
include_once("../common/config.php");
include_once("../common/database.class.php");
$objdb = new database();
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
    $user->session_begin();
    $auth->acl($user->data);
    $username = 'deep';
	$newpass=phpbb_hash('123456');

$query= "UPDATE f_users SET user_password='$newpass' where username='$username'";
$result = $objdb->insert($query);
?>