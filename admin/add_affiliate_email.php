<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/add_affiliate_email.html");
$returncontent=join("",$file);




if (isset($_POST['submit']))
{
	// Parse form data
	$subject	= $db->quote($_POST["subject"]);
	$message	= $db->quote($_POST["message"]);
	$product	= $db->quote($_POST["product"]);

	// Get Product id
	$mysql="select * from ".$prefix."products where pshort={$product}";
	$rslt=$db->get_a_line($mysql);
	@extract($rslt);
	$product_id	= $id;

	// Update database
	$set	= " subject={$subject}";
	$set	.= ", message={$message}";
	 $set	.= ", product_id={$product_id}";
	
	$sql="insert into ".$prefix."marketing_emails set $set";
	
	$pid = $db->insert_data_id($sql);
	$msg = "a";
	header("Location: affiliate_emails.php?msg=$msg");
}

$q = "select * from ".$prefix."products where prodtype !='OTO' order by pshort";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid		= $pshort;
	$product.="<option value='$pid'>$pid</option>";
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>