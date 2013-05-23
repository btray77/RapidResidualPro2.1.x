<?php
include ("include.php");


// Get Product info from database
$GetProd = $db->get_a_line("select * from ".$prefix."products where id = '1'");
@extract($GetProd);
$product_name			= $product_name;


echo $product_name;

?>