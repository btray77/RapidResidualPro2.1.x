<?php
include "include.php";
//include "class-general-functions.php";
$file=file("../html/admin/ano-header.html");
$returncontent=join("",$file);
ob_start();

// Code to check if we should use the WYSIWYG editor

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
?>