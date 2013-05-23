<?php
include_once "include.php";
include_once("session.php");
$pshort = $_GET['pshort'];

// does member already have product?
$q = "select * from " . $prefix . "products where pshort='$pshort'";
$v = $db->get_a_line($q);
$pid = $v['id'];


$q = "select count(*) as cnt from " . $prefix . "member_products where product_id='$pid' && member_id='$memberid'";
$r = $db->get_a_line($q);
$count = $r[cnt];
if ($count != 0) {
    include_once("header.php");
    echo "<br><br><br>";
    echo "<center>You have already puchased this product.<br><a href=paidlist.php>Please click here to go to the product list page.</a></center>";
    echo "<br><br><br>";
    include_once("footer.php");
    exit();
}

$mysql = "select * from " . $prefix . "members where id='$memberid'";
$rslt = $db->get_a_line($mysql);
$randomstring = $rslt["randomstring"];
$ref = $rslt["ref"];


// get index page info from database

$q = "select * from " . $prefix . "products where pshort='$pshort'";
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

/***********************************************************************************************/
$ref=$_COOKIE['referer-'.$short];
$coupon  = $_COOKIE['coupon-'.$short];
/***************************************************************************************************/
//$coupon  = $_GET['coupon'];
if ($coupon != '') {
    $q = "select count(*) as cnt from " . $prefix . "coupon_codes where couponcode='$coupon' && prod ='$short'";
    $r = $db->get_a_line($q);
    $count = $r[cnt];
    if ($count != '0') {
        $q = "select * from " . $prefix . "coupon_codes where couponcode='$coupon'";
        $h = $db->get_a_line($q);
        $discount = $h['discount'];
        $expire_date = strtotime($h["expire_date"]);
        $today_date = date('F jS Y h:i:s A');
        $current = strtotime($today_date);

        if ($current > $expire_date) {
            header("Location: error.php?error=7");
            exit();
        }

        if ($subscription_active == '1') {
            $amount3 = $amount3 - $discount;
            $price = $price - $discount;
            if ($amount3 == '0') {
                $fullcoupon = '1';
            }
        } elseif ($subscription_active == '') {
            $price = $price - $discount;
            $fprice = $fprice - $discount;
            if ($price == '0') {
                $fullcoupon = '1';
            }
        }
    }
}

if ($period1_active == '0') {
    $amount1 = '0';
    $period1_value = '0';
    $period1_interval = '0';
}

/* * ************************************************************************************************************ */
//		PAYMENT PROCESSOR SYSTEM
/* * ************************************************************************************************************* */
$q = "select alertpay_merchant_email, paypal_sandbox, paypal_email, sandbox_paypal_email, sitepartner, partner_paypal_email, partner_commission from " . $prefix . "site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
if ($paypal_sandbox == "1") {
    $receiver = $sandbox_paypal_email;
    $receiver_alertpay = $alertpay_merchant_email;
    $paypath = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else if ($paypal_sandbox == "0") {
    $receiver = $paypal_email;
    $receiver_alertpay = $alertpay_merchant_email;
    $paypath = "https://www.paypal.com/cgi-bin/webscr";
}

// Is there a partner?
$q1 = "select * from " . $prefix . "site_settings where id='1'";
$v1 = $db->get_a_line($q1);
@extract($v1);
$sitepartner = trim($v1['sitepartner']);
$partner_paypal_email = trim($v1['partner_paypal_email']);
$partner_alertpay_email = trim($v1['partner_alertpay_email']);
$partner_commission = trim($v1['partner_commission']);
$second_sitepartner = trim($v1['second_sitepartner']);
$second_partner_paypal_email = trim($v1['second_partner_paypal_email']);
$second_partner_alertpay_email = trim($v1['second_alertpay_paypal_email']);
$second_partner_commission = trim($v1['second_partner_commission']);

if ($sitepartner == 'yes') {
    // Get number of partner sales for this product
    $q = "select count(*) as cnt from " . $prefix . "orders where payee_email='$partner_paypal_email' && item_number='$pid' && payment_status ='Completed' and payment_gateway =''";
    $r = $db->get_a_line($q);
    $total_partner_sales = $r[cnt];
    // Get total admin sales for thsis product
    $q = "select count(*) as cnt from " . $prefix . "orders where (payee_email='$receiver' OR payee_email='$second_partner_paypal_email') && item_number='$pid' && payment_status = 'Completed' and payment_gateway =''";
    $t = $db->get_a_line($q);
    $total_owner_sales = $t[cnt];
    $total_site_sales = $total_partner_sales + $total_owner_sales;

    if ($total_site_sales > '0') {
        $count1 = $total_partner_sales / $total_site_sales;
        $count2 = $count1 * 100;
        $partner_paid_percentage = number_format($count2, 0);
        if ($partner_paid_percentage < $partner_commission) {
            $receiver = $partner_paypal_email;
        }
    }
    /*     * ************************************************************************* */
    // 			Check of Alertpay receiver account infortmation.	
    /*     * **************************************************************************** */
    if (!empty($partner_alertpay_email)) {
        $sql_alert = "select count(*) as cnt from " . $prefix . "orders where payee_email='$partner_alertpay_email' && item_number='$pid' && payment_status ='Completed' and payment_gateway ='AlertPay' ";
        $rs_alert = $db->get_a_line($sql_alert);
        $total_partner_sales_alert = $rs_alert[cnt];
        // Get total admin sales for thsis product
        $sql_alert = "select count(*) as cnt from " . $prefix . "orders where (payee_email='$receiver_alertpay' OR payee_email='$second_partner_alertpay_email') && item_number='$pid' && payment_status = 'Completed' and payment_gateway ='AlertPay'";
        $t_alert = $db->get_a_line($sql_alert);
        $total_owner_sales_alert = $t_alert[cnt];
        $total_site_sales_alert = $total_partner_sales_alert + $total_owner_sales_alert;

        if ($total_site_sales_alert > '0') {
            $count1 = $total_partner_sales_alert / $total_site_sales_alert;
            $count2 = $count1 * 100;
            $partner_paid_percentage_alert = number_format($count2, 0);
            if ($partner_paid_percentage_alert < $partner_commission) {
                $receiver_alertpay = $partner_alertpay_email;
            }
        }
    }
    /*     * ************************************************************************* */
    // 			Check of Alertpay receiver account infortmation.	
    /*     * **************************************************************************** */
}
// Check for second site partner			
if ($second_sitepartner == 'yes') {
    // Get number of partner sales for this product
    $q = "select count(*) as cnt from " . $prefix . "orders where payee_email='$second_partner_paypal_email' && item_number='$pid' && payment_status ='Completed' and payment_gateway ='' ";
    $r = $db->get_a_line($q);
    $total_second_partner_sales = $r[cnt];

    // Get total admin sales for thsis product
    $q = "select count(*) as cnt from " . $prefix . "orders where (payee_email='$receiver' OR payee_email='$partner_paypal_email') && item_number='$pid' && payment_status = 'Completed' and payment_gateway =''";
    $t = $db->get_a_line($q);
    $total_owner_sales = $t[cnt];
    $total_site_sales = $total_second_partner_sales + $total_owner_sales;

    if ($total_site_sales > '0') {
        $count1 = $total_second_partner_sales / $total_site_sales;
        $count2 = $count1 * 100;
        $partner_paid_percentage = number_format($count2, 0);
        if ($partner_paid_percentage < $second_partner_commission) {
            $receiver = $second_partner_paypal_email;
        }
    }
    /*     * ************************************************************************* */
    // 			Check of Alertpay receiver account infortmation.	
    /*     * **************************************************************************** */
    if (!empty($partner_alertpay_email)) {
        $sql_alert = "select count(*) as cnt from " . $prefix . "orders where payee_email='$second_partner_alertpay_email' && item_number='$pid' && payment_status ='Completed' and payment_gateway ='AlertPay'";
        $rs_alert = $db->get_a_line($sql_alert);
        $total_partner_sales_alert = $rs_alert[cnt];
        // Get total admin sales for thsis product
        $sql_alert = "select count(*) as cnt from " . $prefix . "orders where (payee_email='$receiver_alertpay' OR payee_email='$partner_alertpay_email') && item_number='$pid' && payment_status = 'Completed' and payment_gateway ='AlertPay'";
        $t_alert = $db->get_a_line($sql_alert);
        $total_owner_sales_alert = $t_alert[cnt];
        $total_site_sales_alert = $total_partner_sales_alert + $total_owner_sales_alert;

        if ($total_site_sales_alert > '0') {
            $count1 = $total_partner_sales_alert / $total_site_sales_alert;
            $count2 = $count1 * 100;
            $partner_paid_percentage_alert = number_format($count2, 0);
            if ($partner_paid_percentage_alert < $partner_commission) {
                $receiver_alertpay = $second_partner_alertpay_email;
            }
        }
    }
    /*     * ************************************************************************* */
    // 			Check of Alertpay receiver account infortmation.	
    /*     * **************************************************************************** */
}

// If there is a referrer see who gets paid
if ($ref != '') {
    // Get affilates paypal email
    $q = "select paypal_email, status from " . $prefix . "members where username='$ref'";
    $pp = $db->get_a_line($q);
    $affiliate_pp = $pp[paypal_email];
    $affiliate_alertpay = $pp[alertpay_email];
    $status = $pp[status];

    if ($status == '3') {
        $commission = $jvcommission;
    }


    if ($commission == "100") {
        $receiver = $affiliate_pp;
        $receiver_alertpay = $affiliate_alertpay;
    } else {
        // Get number of sales affiliate has made
        $q = "select count(*) as cnt from " . $prefix . "orders where referrer='$ref' && item_number='$pid' && payment_status ='Completed' and payment_gateway =''";
        $r = $db->get_a_line($q);
        $total_affilate_sales = $r[cnt];

        if ($total_affilate_sales > '0') {
            // Get number of sales affiliate has been paid for
            $q = "select count(*) as cnt from " . $prefix . "orders where referrer='$ref' && payee_email='$affiliate_pp' && item_number='$pid' && payment_status ='Completed' and payment_gateway =''";
            $rr = $db->get_a_line($q);
            $paid_affilate_sales = $rr[cnt];

            // Find who should get payment
            $count1 = $paid_affilate_sales / $total_affilate_sales;
            $count2 = $count1 * 100;
            $affiliate_paid_percentage = number_format($count2, 0);

            if ($affiliate_paid_percentage < $commission) {
                // Affiliate gets paid
                $receiver = $affiliate_pp;
            }
        }
        /*         * ************************************************************************* */
        // 			Check of Alertpay receiver account infortmation.	
        /*         * **************************************************************************** */
        if (!empty($affiliate_alertpay)) {
            $sql_alert = "select count(*) as cnt from " . $prefix . "orders where referrer='$ref' && item_number='$pid' && payment_status ='Completed' and payment_gateway ='AlertPay'";
            $rs_alert = $db->get_a_line($sql_alert);
            $total_affilate_sales_alert = $rs_alert[cnt];

            if ($total_affilate_sales_alert > '0') {
                // Get number of sales affiliate has been paid for
                $q = "select count(*) as cnt from " . $prefix . "orders where referrer='$ref' && payee_email='$affiliate_alertpay' && item_number='$pid' && payment_status ='Completed' and payment_gateway ='AlertPay'";
                $rr_alert = $db->get_a_line($q);
                $paid_affilate_sales_alert = $rr_alert[cnt];

                // Find who should get payment
                $count1 = $paid_affilate_sales_alert / $total_affilate_sales_alert;
                $count2 = $count1 * 100;
                $affiliate_paid_percentage_alert = number_format($count2, 0);

                if ($affiliate_paid_percentage_alert < $commission) {
                    // Affiliate gets paid
                    $receiver_alertpay = $affiliate_alertpay;
                    $receiver = $affiliate_pp;
                }
            }
        }

        /*         * ************************************************************************* */
        // 			Check of Alertpay receiver account infortmation.	
        /*         * **************************************************************************** */
    }
}

/* * ************************************************************************************************************ */
//		PAYMENT PROCESSOR SYSTEM
/* * ************************************************************************************************************* */

$array = array($randomstring, $mrand, $ref_id, $ref, $ref_comm, $ip, $amt_owed, $mid, $pass, $pid, $point_amount, $pidoto);
$rands = implode('|', $array);
$_SESSION['rands'] = $rands;

$return_url = $http_path . "member/paidlist.php";
$notify_url = $http_path . "ipn/paypal/payment_ipn.php";
$cancel_url = $http_path . "member/index.php";
$itemname = "Payment for  <? echo $product_name; ?>";
$item_number = "member";
$paypal_image = $http_path . "images/paypal_buynow.gif";

if ($ppbutton == "") {
    $ppimage = $http_path . "images/paypal_buynow.gif";
} elseif ($ppbutton != "") {
    $ppimage = $http_path . "images/" . $ppbutton;
}


if ($subscription_active == '1') {
    $item_number = "member-membership";
    if ($period1_active == '1') {
        // Trial period 1 active
        $trial1 = "<input type='hidden' name='a1' value='$amount1'>
	<input type='hidden' name='p1' value='$period1_value'>
	<input type='hidden' name='t1' value='$period1_interval'>";
        if ($period2_active == '1') {
            // Trial period 2 active
            $trial2 = "<input type='hidden' name='a2' value='$amount2'>
	<input type='hidden' name='p2' value='$period2_value'>
	<input type='hidden' name='t2' value='$period2_interval'>";
        }
    }

    if ($billing_cycle == '1') {
        $recurr = '';
    }
    if ($billing_cycle != '1') {
        $recurr = '<!-- Recurring Payment -->
	<input type="hidden" name="src" value="1">
	<input type="hidden" name="srt" value="' . $billing_cycle . '">	';
    }
    ?>	
    <body onLoad="javascript:document.paypal.submit();">
        <form name="paypal" action="<? echo $paypath; ?>" method="post" name="paymentfrm">
            <input type='hidden' name='cmd' value='_xclick-subscriptions'>
            <input type='hidden' name='business' value="<? echo $receiver; ?>">
            <input type='hidden' name='item_name' value="<? echo $product_name; ?>">
            <input type='hidden' name='item_number' value="<? echo $item_number; ?>">
            <input type='hidden' name='no_shipping' value='1'>
            <input type='hidden' name='return' value="<? echo $return_url; ?>">	
            <input type='hidden' name='cancel_return' value="<? echo $cancel_url; ?>">
            <input type='hidden' name='no_note' value='1'>
            <input type='hidden' name='currency_code' value='USD'>	
            <input type='hidden' name='bn' value='PP-SubscriptionsBF'>
    <? echo $trial1; ?>
    <? echo $trial2; ?>
            <input type='hidden' name='a3' value="<? echo $amount3; ?>">
            <input type='hidden' name='p3' value="<? echo $period3_value; ?>">
            <input type='hidden' name='t3' value="<? echo $period3_interval; ?>'">
            <input type='hidden' name='sra' value='1'>
            <input type='hidden' name='rm' value='2'>
    <? echo $recurr; ?>	
            <input type='hidden' name='notify_url' value="<? echo $notify_url; ?>">
            <input type='hidden' name='custom' value="<? echo $rands; ?>">
            <input type='hidden' name='usr_manage' value='0'>
            <input type='hidden' name='cpp_header_image' value="<? echo $pp_header; ?>">
            <input type='hidden' name='cbt' value="<? echo $pp_return; ?>">
        </form>	
    <?php
} elseif ($subscription_active != '1') {
    ?>	
    <body onLoad="javascript:document.paypal.submit();">	
        <form name="paypal" action="<? echo $paypath; ?>" method="post" name="paymentfrm">
            <input type='hidden' name='cmd' value='_xclick'>
            <input type='hidden' name='rm' value='2'>
            <input type='hidden' name='business' value="<? echo $receiver; ?>">
            <input type='hidden' name='item_name' value="<? echo $product_name; ?>">
            <input type='hidden' name='item_number' value="<? echo $item_number; ?>">
            <input type='hidden' name='amount' value="<? echo $price; ?>">
            <input type='hidden' name='custom' value="<? echo $rands; ?>">
            <input type='hidden' name='return' value="<? echo $return_url; ?>">	
            <input type='hidden' name='notify_url' value="<? echo $notify_url; ?>">
            <input type='hidden' name='cancel_return' value="<? echo $cancel_url; ?>">
            <input type='hidden' name='no_note' value='1'>
            <input type='hidden' name='no_shipping' value='1'>
            <input type='hidden' name='currency_code' value='USD'>
            <input type='hidden' name='cpp_header_image' value="<? echo $pp_header; ?>">
            <input type='hidden' name='cbt' value="<? echo $pp_return; ?>">
        </form>
    <?php
}
?>