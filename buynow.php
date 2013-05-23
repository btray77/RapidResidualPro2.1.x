<?php
include_once "common/config.php";
include_once "include.php";
include_once "common/placeholder.class.php";

$q = "select paypal_sandbox, paypal_email, sandbox_paypal_email, sitepartner, partner_paypal_email, partner_commission from " . $prefix . "site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
if ($paypal_sandbox == "1") {
    $receiver = $sandbox_paypal_email;
    $paypath = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else if ($paypal_sandbox == "0") {
    $receiver = $paypal_email;
    $paypath = "https://www.paypal.com/cgi-bin/webscr";
}


// get product page info from database
$q = "select * from " . $prefix . "products where id='$pid'";

$v = $db->get_a_line($q);
$home_page = $v['index_page'];
$home_page = str_replace('�', '&trade;', $home_page);
$home_page = str_replace('�', '&#169;', $home_page);
$home_page_product = stripslashes($home_page);
$itemname=$product_name = $v['product_name'];
$short = $v['pshort'];
$pid = $v['id'];
$price = $v['price'];
$image = $v['image'];
$commission = $v['commission'];
$jvcommission = $v['jvcommission'];
$prodtype = $v['prodtype'];
$qlimit = $v['qlimit'];
$quantity_cap = $v['quantity_cap'];
$quantity_met_page = $v['quantity_met_page'];
$subscription_active = $v['subscription_active'];
$period1_active = $v['period1_active'];
$period2_active = $v['period2_active'];
$amount1 = $v['amount1'];
$period1_value = $v['period1_value'];
$period1_interval = $v['period1_interval'];
$amount2 = $v['amount2'];
$period2_value = $v['period2_value'];
$period2_interval = $v['period2_interval'];
$amount3 = $v['amount3'];
$period3_value = $v['period3_value'];
$period3_interval = $v['period3_interval'];
$prodtype = $v['prodtype'];
$srt = $v['srt'];
$billing_cycle = $v['srt'];
$squeeze_check = $v['squeeze_check'];
$squeezename = $v['squeezename'];
$kunakicheck = $v['kunakicheck'];
$pp_header = $v['pp_header'];
$pp_return = $v['pp_return'];

/* * ************************************************************************************************ */
$_billing_cycle = array('period' => $period3_interval, 'interval' => $period3_value, 'amount' => $amount3);

$_billing_cycle['limit'] = $srt;

if ($period1_active)
    $_trial = array('period' => $period1_interval, 'interval' => $period1_value, 'amount' => $amount1);


if ($period2_active) {

    if (!$period1_active)
        $_trial = array('period' => $period2_interval, 'interval' => $period2_value, 'amount' => $amount2);
    else
        $_trial2 = array('period' => $period2_interval, 'interval' => $period2_value, 'amount' => $amount2);
}
/* * ************************************************************************************************ */


if ($subscription_active == '1') {
    $fprice = $amount3;
    $price = $amount3;
} else {
    $fprice = $price;
}

/* * ************************************* Applying coupon discounts code ************************************ */
$cookies = 'coupon-' . $short;
$coupon = $_COOKIE[$cookies];
if ($coupon == "")
    $coupon = $_GET['coupon'];

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
            $errors = $common->show_error('7');
            $discount = 0;
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
$ref = $_COOKIE['referer-'.$short];
/* * ************************************************************************************ */
/*                          Create Veiwes
  /************************************************************************************* */
$counter->setcounter('', $short, $ip, $ref);
/* * ************************************************************************************ */
/* * ************************************* Applying coupon discounts code ************************************ */


/* * ************************************************************************************************************ */
//		PAYMENT PROCESSOR SYSTEM
/* * ************************************************************************************************************* */
$payment = new payment_receiver_email($pid, $ref);   /// Get Receiver email address
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

/* * ************************************************************************************************************ */
//		PAYMENT PROCESSOR SYSTEM
/* * ************************************************************************************************************* */
if(!empty($_SESSION['memberid']))
	$memberid = $_SESSION['memberid'];
else 	
	$memberid = $_COOKIE['memberid'];
if (!empty($memberid)) {
    $q1 = "select randomstring from " . $prefix . "members where id=$memberid";
    $v2 = $db->get_a_line($q1);
    $rand = $v2['randomstring'];
    $ttype = "inside";
    $return_url = $http_path . "/member/paypal_return.php";
    $alpertpay_return_url = $http_path . "/member/alertpay_return.php?randomstring=$rand";
} else {
    $rand = md5(uniqid(rand(), 1));
    $ttype = "outside";
    $return_url = $http_path . "/paypal_return.php";
    $alpertpay_return_url = $http_path . "/alertpay_return.php?randomstring=$rand";
}

$notify_url = $http_path . "/ipn/paypal/payment_ipn.php";
$amt_owed = $price;
$ip = $_SERVER['REMOTE_ADDR'];

$array = array($rand, $ip, $amt_owed, $pid, $ref, $ttype, $pcheck);
$rands = implode('|', $array);

$_SESSION['rands'] = $rands;
setcookie("custom", $rands, time() + 365 * 24 * 60 * 60, "/", $_SERVER['HTTP_HOST'], 0);
/* * ******************************************************************************************** */

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


if ($prodtype == 'paid') {
    if ($subscription_active == '1') {
        $buttons = get_subscription_payment_hidden_buttons($_GET['gateway']);
    } else {
        $buttons = get_payment_hidden_buttons($_GET['gateway']);
    }
    if ($fullcoupon == '1') {
        if (!empty($ref)) {
            header("location:/free.php?pshort=$short&c=$coupon&ref=$ref");
            exit();
        } else {
            header("location:/free.php?pshort=$short&c=$coupon");
            exit();
        }
    }
} else if ($prodtype == 'Clickbank') {
    if ($squeeze_check == 'yes') {
        $q = "select * from " . $prefix . "squeeze_pages where name='$squeezename'";
        $v = $db->get_a_line($q);
        $click_bank_id = get_clickbank_product_id($v['squeezepage']);
        $call = $clickBank->button_hidden($click_bank_id, $pid, $ref, $rand);
    } else {
        $click_bank_id = get_clickbank_product_id($home_page_product);
        $call = $clickBank->button_hidden($click_bank_id, $pid, $ref, $rand);
    }
    header("location:$call");
    exit();
} elseif ($prodtype == 'free') {
    if (!empty($ref)) {
        header("location:/free.php?pshort=$short&c=$coupon&ref=$ref");
        exit();
    } else {
        header("location:/free.php?pshort=$short&c=$coupon");
        exit();
    }
}

function get_clickbank_product_id($content) {
    $common = new common();
    $tokens = $common->getTextBetweenTags($content);
    if (count($tokens) > 0) {
        foreach ($tokens as $token) {
            $temp = explode('_', $token);
            if ($temp[0] = 'clickbank')
                $click_bank_id = $temp[2];
            break;
        } // end foreach
    }
    return $click_bank_id;
    // return Submit()
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Payment Process</title>
        <link type="text/css" rel="stylesheet" href="/common/newLayout/core.css" />
        <style>
            .wait{
                border: 1px solid #005B93;
                font-family: verdana;
                font-size: 12px;
                margin: 25% auto;
                padding: 10px;
                text-align: center;
                width: 98%;
                background: #cedce7; /* Old browsers */
                background: -moz-linear-gradient(top, #cedce7 0%, #596a72 100%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#cedce7), color-stop(100%,#596a72)); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top, #cedce7 0%,#596a72 100%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top, #cedce7 0%,#596a72 100%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top, #cedce7 0%,#596a72 100%); /* IE10+ */
                background: linear-gradient(top, #cedce7 0%,#596a72 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#cedce7', endColorstr='#596a72',GradientType=0 ); /* IE6-9*/
                }
            </style>

        </head>

        <body onload="return Submit()">
            <div class="wait">
                <p>Please wait.....</p>
                <img src="/images/wait.gif" alt="Wait" />
            </div>
            <?php echo $buttons; ?>
        </body>
    </html>