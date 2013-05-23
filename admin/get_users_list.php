<?php
include_once("session.php");
include_once("include.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "select DISTINCT username from rrp_members where username LIKE '%$q%'";
$rows = $db->get_rsltset($sql);

foreach($rows as $row)
	echo $row['username']."\n";

?>