<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/editcoupon.html");
$Content = join("", $GetFile);

if (isset($_POST['submit']))
{
	
	// Parse form data
	$couponcode		= $db->quote($_POST["couponcode"]);
	$discount		= $db->quote($_POST["discount"]);
	$publish	= $_POST["publish"];
	if($publish){
		$publish = $publish;
	}else{
		$publish = '0';
	}
	$expire_date = $db->quote(date("Y-m-d H:i:s", strtotime($_POST["expire_date"])));

	// Update database
	$set	= " couponcode={$couponcode}";
	$set	.= ", discount={$discount}";
	$set	.= ", publish='$publish'";
	$set	.= ", expire_date={$expire_date}";
	$set	.= ", prod='{$pproduct}'";
	$db->insert("update ".$prefix."coupon_codes set $set where id ='$prodid'");
	$msg = "edit";
	header("Location: coupon.php?msg=$msg");
}

// read data from database
$mysql="select * from ".$prefix."coupon_codes where id='$prodid'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);

$expire_date 	= date("d-M-Y h:i:s A", strtotime($rslt["expire_date"]));
$couponcode		= $rslt['couponcode'];
$discount		= $rslt['discount'];
$showprod		= $rslt['prod'];
$publish        = $rslt['publish'];
if($publish == '1'){
	$publish = '<input type="checkbox" name="publish" id="publish" value="1" checked="checked">';
}else{
	$publish = '<input type="checkbox" name="publish" id="publish" value="1">';
}

$q = "select * from ".$prefix."products where prodtype='paid' order by pshort";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid		= $pshort;

	if($pid ==$showprod)
	{
		$pproduct.="<option value='$pid' Selected>$product_name</option>";
	}
	elseif($pid !=$showprod)
	{
		$pproduct.="<option value='$pid'>$product_name</option>";
	}
}
$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include "footer.php";
?>