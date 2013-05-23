<?php 
include_once("../../session.php");
include_once("../../include.php");
if(!empty($_GET['pid'])){

$mid =get_user_id($prefix,$db,$_GET['mid']);
$pid =(int) $_GET['pid'];
$action = (int) $_GET['action'];

if($action==0)
{
$payment_status ='Completed';
}
else
{
$payment_status ='Refunded';
}
$sql="UPDATE ".$prefix."member_products set refunded=$action where product_id='$pid' and member_id='$mid' ";

$db->insert($sql);
$sql = "UPDATE ".$prefix."orders set payment_status = '$payment_status' where randomstring='$_GET[mid]' and item_number = '$pid'";	
$db->insert($sql);
	echo "<script>window.location.href='product.php?mid=$_GET[mid]'</script>";
	exit();
}	

	
	  	
	function get_user_id($prefix,$db,$mid)
	{
		$sql = "SELECT id from ".$prefix."members where  randomstring='$mid' ";
	 	$row_member= $db->get_a_line($sql);
		return $row_member['id'];
	}

?>
 