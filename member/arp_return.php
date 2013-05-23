<?php
include ("include.php");
include_once("session.php");
$str			= $_COOKIE["custom"];
$str			= explode('|',$str);
$item_number	= $str[3];

$q = "select * from ".$prefix."products where id='$item_number'";
$r = $db->get_a_line($q);
$pshort 		= $r[pshort];

header("Location: index.php?pshort=$pshort");
exit;
?>