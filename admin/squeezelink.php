<?php
include_once("session.php");
$GetFile = file("../html/admin/squeezelink.html");
$Content = join("",$GetFile);

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
?>