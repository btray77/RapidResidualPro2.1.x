<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/edit_affiliate_email.html");
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
	$product_id	= $rslt[id];


	// Update database
	$set	= " subject={$subject}";
	$set	.= ", message={$message}";
	$set	.= ", product_id={$product_id}";


	$db->insert("update ".$prefix."marketing_emails set $set where id = '$eid'");
	$msg = "e";
	header("Location: affiliate_emails.php?msg=$msg");
}

// Get Product info from database
$GetProd = $db->get_a_line("select * from ".$prefix."marketing_emails where id = '$eid'");
@extract($GetProd);
$subject			= stripslashes($subject);
$message			= stripslashes($message);
$prod = $product_id;


$q = "select * from ".$prefix."products where prodtype !='OTO' order by id";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid		= $id;
	$short 		= $pshort;
	if($pid == $prod)
	{
		$product.="<option value='$short' Selected>$short</option>";
	}
	elseif($pid !=$prod)
	{
		$product.="<option value='$short'>$short</option>";
	}
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>