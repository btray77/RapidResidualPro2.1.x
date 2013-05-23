<?php

$pshort   = $_REQUEST['pshort'];
include_once("include.php");
include_once("session.php");

// Get OTO short name
$q = "select id as pid, one_time_offer from ".$prefix."products where pshort='$pshort'";
$v = $db->get_a_line($q);
@extract($v);

// Get OTO id
$q = "select down_one_time_offer, otodowncheck, id as oto_id from ".$prefix."products where pshort='$one_time_offer'";
$v = $db->get_a_line($q);
@extract($v);

// Has member seen this oto before?
$q="select count(*) as cnt from ".$prefix."oto_check where member_id='$memberid' && product_id='$pid' && oto_id='$oto_id'";
$r=$db->get_a_line($q);
$count=$r[cnt];

if($count > 0)
{
	$red_msg = base64_encode('no');
	header("Location: index.php");
	exit;	
}
	
// Insert into member oto table
 
$q = "insert into ".$prefix."oto_check set member_id='$memberid', product_id='$pid', oto_id='$oto_id'";
$db->insert($q);

// get product page info from database
$q = "select * from ".$prefix."products where id='$oto_id'";
$v = $db->get_a_line($q);

$home_page 				= $v['index_page'];
$home_page 				= str_replace('™', '&trade;', $home_page);
$home_page 				= str_replace('©', '&#169;', $home_page);
$home_page				= stripslashes($home_page);
$product_name 			= $v['product_name'];
$short 					= $v['pshort'];
$pid 					= $v['id'];
$price					= $v['price'];
$image					= $v['image'];
$commission				= $v['commission'];
$prodtype		 		= $v['prodtype'];
$no_text 				= $v['no_text'];
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
$assign_template        = $v['template'];
$pp_header			    = $v['pp_header'];
$pp_return			    = $v['pp_return'];
/***********************************************************************************************/
$ref=$_COOKIE['referer-'.$short];
$coupon  = $_COOKIE['coupon-'.$short];
/***************************************************************************************************/



/***************************************************************************************************/
$_billing_cycle = array('period'=>$period3_interval, 'interval' => $period3_value, 'amount'=> $amount3);

$_billing_cycle['limit'] = $srt;

if($period1_active) $_trial = array('period'=>$period1_interval, 'interval' => $period1_value, 'amount'=> $amount1);


if($period2_active){
	
	if(!$period1_active)  $_trial = array('period'=>$period2_interval, 'interval' => $period2_value, 'amount'=> $amount2);
	else $_trial2 = array('period'=>$period2_interval, 'interval' => $period2_value, 'amount'=> $amount2);
	 
}
/***************************************************************************************************/

if($period1_active=='0')
	{
	$amount1 = '0';	
	$period1_value = '0';	
	$period1_interval = '0';	
	}

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
/*******************************************************************************/
$q1 = "select * from ".$prefix."members where id=$memberid";
$v2 = $db->get_a_line($q1);
@extract($v2);
$rand = $v2['randomstring'];
/*****************************************************************************************************/
$return_url = $http_path."/member/paypal_return.php";
$notify_url = $http_path."/ipn/paypal/payment_ipn.php";
$alpertpay_return_url=$http_path."/member/alertpay_return.php?randomstring=$rand";
$itemname = $product_name;

$amt_owed = $price;
$discount_price=$price;
$actual_price = $price;
$ip = $_SERVER['REMOTE_ADDR'];
$ttype = "inside";
$array = array($rand, $ip, $amt_owed, $pid, $ref, $ttype, $pcheck);
 $rands = implode('|', $array);
$_SESSION['rands'] = $rands;
setcookie("custom", $rands, time() + 365*24*60*60, "/", $_SERVER['HTTP_HOST'], 0);
/***************************************************************************************************************/
//		PAYMENT PROCESSOR SYSTEM
/****************************************************************************************************************/
if ($period1_active =='1')
		{
		// Trial period 1 active
		$trial1="<input type='hidden' name='a1' value='$amount1'>
	<input type='hidden' name='p1' value='$period1_value'>
	<input type='hidden' name='t1' value='$period1_interval'>";
		if ($period2_active=='1')
			{
			// Trial period 2 active
			$trial2="<input type='hidden' name='a2' value='$amount2'>
	<input type='hidden' name='p2' value='$period2_value'>
	<input type='hidden' name='t2' value='$period2_interval'>";
			}			
		}

if($billing_cycle == '1')
	{	
	$recurr = '';
	}
if($billing_cycle != '1')
	{	
	$recurr = '<!-- Recurring Payment -->
	<input type="hidden" name="src" value="1">
	<input type="hidden" name="srt" value="'.$billing_cycle.'">	';
	}	

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

if($otodowncheck == "yes")
    {	
    $no_thanks = "<br><br><center><A href=otodownsell.php?pshort=$one_time_offer>".$no_text."</A></center><br><br>";
    }
else
    {
    $no_thanks = "<br><br><center><a href=index.php>".$no_text."</center><br><br>";
    }
	
// Do we have a referrer?
if($ref != '')
	{
	$q = "select count(*) as cnt from ".$prefix."members where username='$ref'";
	$r = $db->get_a_line($q);

	if($r[cnt] != 0)
		{
		$q = "select * from ".$prefix."members where username='$ref'";
		$r = $db->get_a_line($q);
		$firstname = $r['firstname'];
		$lastname = $r['lastname'];	
		$ref2 = $firstname." ".$lastname;
		$referred_by ="<p align=center class=tbtext><font color=gray>Referrer: ".$ref2."</font><br><br></p>";
		}
	}
		
// Look up to see if a click from that IP has already been registered  
/***************************************************************************************/
/*                          Create Veiwes
/**************************************************************************************/
$counter->setcounter('',$short,$ip,$ref);
/***************************************************************************************/		



/************************************************************************************************/	
 $tokens =$common->getTextBetweenTags($home_page);
 foreach($tokens as $token)
 {
 	 	
 		$temp =	explode('_',$token);
 		if(count($temp)==3)
 		{
 			
 			switch($temp[0]) 
 			{
 				case 'video':
 					$$token = 	$common->getmedia('video',$temp[2],$db,$prefix);
 				break;
 				case 'audio':
 					$$token =	$common->getmedia('audio',$temp[2],$db,$prefix);
 				break;
 				case 'file':
 					$$token =	$common->getmedia('file',$temp[2],$db,$prefix);
 				break;
 				case 'clickbank':
                        $$token = $clickBank->button($temp[2], $pid, $ref, $rand);
                break;	
 			}
 		}
 }	
	$home_page_product = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $home_page);	

	
	$smarty->assign('main_content',$home_page_product);
	$output = $smarty->fetch('../html/member/content.tpl');
	
	
	
/*************************************************************************************************************/
if ($assign_template == "default") {
 
} else if ($assign_template == 'none') {
    $FILEPATH = "";
} else {
    $FILEPATH = $_SERVER['DOCUMENT_ROOT'] . "/templates/" . $assign_template . "";
}
if(!empty($FILEPATH)){
	$objTpl = new TPLManager($FILEPATH.'/index.html');
	$hotspots = $objTpl->getHotspotList();
	
	$placeHolders = $objTpl->getPlaceHolders($hotspots);
	$i=0;
	foreach($placeHolders as  $items)
	{
		$smarty->assign("$hotspots[$i]","$items");
		$i++;
	}
	
	$smarty->assign('current_date',$today);
	$smarty->assign('rightpanel',$rightpanel);
	$smarty->assign('error',$Message);
	$smarty->assign('content',$output);
	$smarty->display($FILEPATH.'/index.html');
}
else
{
	$smarty->display('../html/member/content.tpl');	
}
?>