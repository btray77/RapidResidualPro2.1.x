<?php
include_once 'include.php';
include_once 'session.php';

$pshort = trim(mysql_escape_string($pshort));
$sql = "select * from " . $prefix . "products where pshort='" . $pshort . "'";
$row_product = $db->get_a_line($sql);
extract($row_product);
$psponder = $row_product["psponder"];

$obj_responder = new autoresponders('', $id);
$autoresponder = $obj_responder->process_Autoresponders();

$coupon  = $_COOKIE['coupon-'.$pshort];
$short  = $pshort;
if ($prodtype == "free") {
    $price = '0';
} 
else if($coupon != '')
{  
	$q="select count(*) as cnt from ".$prefix."coupon_codes where couponcode='$coupon' && prod ='$short'";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count != '0')
	{
		$q = "select * from ".$prefix."coupon_codes where couponcode='$coupon'";
		$h = $db->get_a_line($q);
		$discount = $h['discount'];
		$expire_date	= strtotime($h["expire_date"]);
		$today_date = date('F jS Y h:i:s A');
		$current 	= strtotime($today_date);
		if($current > $expire_date)
		{
			$errors=$common->show_error('7');
			$discount = 0;
		}
		if ($subscription_active =='1')
		{
			$amount3 = $amount3 - $discount;
			$price = $price - $discount;
                        if($price < 0 || $fprice < 0)
                        {
                            $price=0;$fprice=0;
                        }
                         $discount_price=number_format($price,2);
			if($amount3 == '0')
			{
				$fullcoupon = '1';
			}
		}
		else
		{
			$fprice = $fprice - $discount;
			$price = $price - $discount;
			if($price < 0 || $fprice < 0)
				{
					$price=0;$fprice=0;
				}
				$discount_price=number_format($price,2);
		if($price == '0')
			{
				$fullcoupon = '1';
			}
		}
	}
}
else {
    header("location:$_SERVER[HTTP_REFERER]&error=true");
   exit();
}
// insert into member products table
$mid = $_SESSION['memberid'];
$q = "select randomstring from " . $prefix . "members where id='$mid'";
$row_mem = $db->get_a_line($q);
$rand = $row_mem['randomstring'];
$set = "member_id='$mid'";
$set .= ", product_id='" . $id . "'";
$set .= ", date_added='" . $today . "'";
$set .= ", txn_id='FREE'";
$set .= ", type='$prodtype'";
$q = "insert into " . $prefix . "member_products set $set";
$db->insert($q);
// get Admin payee account
$sql_settings = "select paypal_email,sandbox_paypal_email,paypal_sandbox,alertpay_merchant_email from " . $prefix . "site_settings";
$row_settings = $db->get_a_line($sql_settings);
if ($row_settings['paypal_sandbox'] == 1 && empty($row_settings['paypal_email']))
    $payee = $row_settings['sandbox_paypal_email'];
else
    $payee= $row_settings['paypal_email'];
if (empty($payee)) {
    $payee = $row_settings['alertpay_merchant_email'];
}
// insert into order table                  
$set = "item_number='$id'";
$set .= ", item_name='$product_name'";
$set .= ", date='$today'";
$set .= ", payment_amount='$price'";
$set .= ", payment_status='Completed'";
$set .= ", pending_reason=''";
$set .= ", txnid='FREE'";
$set .= ", payer_email={$db->quote($email)}";
$set .= ", payee_email='$payee   '";
$set .= ", referrer='$ref'";
$set .= ", payment_gateway='Free'";
$set .= ", randomstring='$rand'";
$set .= ", payment_type='instant'";
$q = "insert into " . $prefix . "orders set $set";
$db->insert($q);
/* * ****************  ADD TO AUTO RESPONDERS   *********************** */
$autoresponder = $obj_responder->process_Autoresponders();
/* * ****************  END TO AUTO RESPONDERS   *********************** */
header("location:index.php?pshort=$pshort");
exit();
?>
