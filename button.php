<?php

$q = "select paypal_sandbox, paypal_email, sandbox_paypal_email, sitepartner, partner_paypal_email, partner_commission from ".$prefix."site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
if ($paypal_sandbox=="1")
	{
	$receiver = $sandbox_paypal_email;
	$paypath	= "https://www.sandbox.paypal.com/cgi-bin/webscr";	
	}
else if ($paypal_sandbox=="0")
	{
	$receiver = $paypal_email;
	$paypath	= "https://www.paypal.com/cgi-bin/webscr";
	}


// get product page info from database
$q = "select * from ".$prefix."products where id='1'";
$v = $db->get_a_line($q);
$home_page 				= $v['index_page'];
$home_page 				= str_replace('™', '&trade;', $home_page);
$home_page 				= str_replace('©', '&#169;', $home_page);
$home_page_product 		= stripslashes($home_page);
$product_name 			= $v['product_name'];
$short 					= $v['pshort'];
$pid 					= $v['id'];
$price					= $v['price'];
$image					= $v['image'];
$commission				= $v['commission'];
$jvcommission			= $v['jvcommission'];
$prodtype		 		= $v['prodtype'];
$qlimit			 		= $v['qlimit'];
$quantity_cap		 	= $v['quantity_cap'];
$quantity_met_page		= $v['quantity_met_page'];
$subscription_active	= $v['subscription_active'];
$period1_active 		= $v['period1_active'];
$period2_active 		= $v['period2_active'];	
$amount1 				= $v['amount1'];	
$period1_value 			= $v['period1_value'];	
$period1_interval 		= $v['period1_interval'];	
$amount2 				= $v['amount2'];	
$period2_value 			= $v['period2_value'];	
$period2_interval 		= $v['period2_interval'];		
$amount3 				= $v['amount3'];	
$period3_value 			= $v['period3_value'];	
$period3_interval 		= $v['period3_interval'];
$prodtype		 		= $v['prodtype'];
$srt 					= $v['srt'];
$billing_cycle 			= $v['srt'];
$squeeze_check 			= $v['squeeze_check'];
$squeezename 			= $v['squeezename'];
$kunakicheck			= $v['kunakicheck'];
$pp_header				= $v['pp_header'];
$pp_return				= $v['pp_return'];


/***************************************************************************************************/
$_billing_cycle = array('period'=>$period3_interval, 'interval' => $period3_value, 'amount'=> $amount3);

$_billing_cycle['limit'] = $srt;

if($period1_active) $_trial = array('period'=>$period1_interval, 'interval' => $period1_value, 'amount'=> $amount1);


if($period2_active){
	
	if(!$period1_active)  $_trial = array('period'=>$period2_interval, 'interval' => $period2_value, 'amount'=> $amount2);
	else $_trial2 = array('period'=>$period2_interval, 'interval' => $period2_value, 'amount'=> $amount2);
	 
}
/***************************************************************************************************/


if ($subscription_active == '1')
	{
	$fprice=$amount3;
	$price=$amount3;
	}
else
	{
	$fprice=$price;
	}
	
/*************************************** Applying coupon discounts code *************************************/	

$coupon  = $_COOKIE['coupon_code'];
if($coupon=="")
$coupon = $_GET['coupon'];

if($coupon != '')
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
			if($amount3 == '0')
			{
				$fullcoupon = '1';
			}
		}
		elseif ($subscription_active =='')
		{
			$price = $price - $discount;
			$fprice = $fprice - $discount;
			if($price == '0')
			{
				$fullcoupon = '1';
			}
		}
	}
}	
/*************************************** Applying coupon discounts code *************************************/

/***************************************************************************************************************/
//		PAYMENT PROCESSOR SYSTEM
/****************************************************************************************************************/
$payment = new payment_receiver_email($pid,$ref);   /// Get Receiver email address
$receiver_emails = $payment->get_receiver_email();

$paypal_settings = "select click_user_id,paypal_sandbox from " . $prefix . "site_settings where id='1'";
$row_paypal_settings = $db->get_a_line($paypal_settings);

if ($row_paypal_settings['paypal_sandbox'] == "1") {
    $paypath = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
    $paypath = "https://www.paypal.com/cgi-bin/webscr";
}

$receiver = $receiver_emails['paypal_email'];
$receiver_alertpay = $receiver_emails['alertpay_email'];
$receiver_alertpay_ipn = $receiver_emails['alertpay_ipn'];
$receiver_clickbank = $row_paypal_settings['click_user_id'];

/***************************************************************************************************************/
//		PAYMENT PROCESSOR SYSTEM
/****************************************************************************************************************/	
$return_url = $http_path."/paypal_return.php";
$notify_url = $http_path."/ipn/paypal/payment_ipn.php";
$itemname = $product_name;
$rand = md5(uniqid(rand(),1));
$amt_owed	= $price;
$ip	= $_SERVER['REMOTE_ADDR'];
$ttype = "outside";

$array = array($rand, $ip, $amt_owed, $pid, $ref, $ttype, $pcheck);
$rands = implode('|', $array);
$_SESSION['rands'] = $rands;


if ($subscription_active == '1')
		{
			$buttons = get_subscription_payment_buttons($_GET['gateway']);
			@extract($buttons);
		}
	elseif ($subscription_active != '1')
		{
	    	$buttons = get_payment_buttons($_GET['gateway']);
			@extract($buttons);
		
		}

?>