<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/addcoupon.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	
	// Parse form data
	$couponcode		= $_POST["couponcode"];
	$discount		= $_POST["discount"];
	$pproduct		= $_POST["pproduct"];
	$publish		= $_POST["publish"];
	if($publish){
		$publish = $publish;
	}else{
		$publish = '0';
	}
	$today	= date("Y-m-d");
	$expire_date = date("Y-m-d H:i:s", strtotime($_POST["expire_date"]));



	// Update database
	$set	= " couponcode='$couponcode'";
	$set	.= ", discount='$discount'";
	$set	.= ", prod='$pproduct'";
	$set	.= ", publish='$publish'";
	$set	.= ", expire_date='$expire_date'";
	$set	.= ", date_added='$today'";

	$pid = $db->insert_data_id("insert into ".$prefix."coupon_codes set $set") ;
	$msg = "add";
	header("Location: coupon.php?msg=$msg");
}


// Get details
$q = "select * from ".$prefix."products where prodtype='paid' order by pshort";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid =  $pshort;
        
	$pproduct.="<option value='$pid'>$product_name</option>";
}



$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>