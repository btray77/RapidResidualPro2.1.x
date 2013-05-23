<?php
include_once("session.php");
$GetFile = file("../html/admin/bloglink.html");
$Content = join("",$GetFile);

// Get data to populate fields on page
$GetProd = $db->get_a_line("select * from ".$prefix."pages where pageid = '$pageid'");
@extract($GetProd);
$filename	= $filename;
$pagelink="content.php?page=".$filename;

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
?>