<?php
include_once("session.php");
$GetFile = file("../html/admin/couponlink.html");
$Content = join("",$GetFile);

// Product query for coupons
$qry_prod_cpn = "select * from ".$prefix."products where pshort = '".$product_name."'";
$row_prod_cpn = $db->get_a_line($qry_prod_cpn);

$prod_id = $row_prod_cpn['id'];

if($prod_id == '1'){
	$fileName = 'go';
}else{
	$fileName = 'goto';
}

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
?>