<?php
include ("include.php");
include_once("session.php");
$short = $_GET['short'];
$ip	= $_SERVER['REMOTE_ADDR'];
$fullcoupon =0;

/*************************************************************************/
$q = "select * from ".$prefix."members where id='$memberid'";
$r = $db->get_a_line($q);
@extract($r);
$firstname 		= $r[firstname];
$username		= $r[username];
$status			= $r[status];
if($status == '1')
{
	$sql_page = "select affiliate_main as member_main, affiliate_menu_id from ".$prefix."misc_pages";
	$row_page = $db->get_a_line($sql_page);
	 // Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['affiliate_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
		$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
elseif($status == '2')
{
	$sql_page = "select member_main as member_main, member_menu_id from ".$prefix."misc_pages"; 
	$row_page = $db->get_a_line($sql_page);
	 // Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['member_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
	$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
elseif($status == '3')
{
	$sql_page = "select jv_main as member_main, jv_menu_id from ".$prefix."misc_pages";
	$row_page = $db->get_a_line($sql_page);
	// Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['jv_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
	$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
 
/************************************************************************/
// get product page info from database
$q = "select * from ".$prefix."products where pshort='$short'";
$v = $db->get_a_line($q);
$home_page 			= $v['index_page'];
$home_page 			= str_replace('�', '&trade;', $home_page);
$home_page 			= str_replace('�', '&#169;', $home_page);
$home_page_product 	= stripslashes($home_page);
$product_name 		= $v['product_name'];
$short 				= $v['pshort'];
$pid 				= $v['id'];
$price				= $v['price'];
$image				= $v['image'];
$commission			= $v['commission'];
$image_alertpay		= $v['alertpay_image'];
$jvcommission		= $v['jvcommission'];
$prodtype		 	= $v['prodtype'];
$qlimit			 	= $v['qlimit'];
$quantity_cap		= $v['quantity_cap'];
$quantity_met_page	= $v['quantity_met_page'];
$subscription_active= $v['subscription_active'];
$period1_active 	= $v['period1_active'];
$period2_active 	= $v['period2_active'];	
$amount1 			= $v['amount1'];	
$period1_value 		= $v['period1_value'];	
$period1_interval 	= $v['period1_interval'];	
$amount2 			= $v['amount2'];	
$period2_value 		= $v['period2_value'];	
$period2_interval 	= $v['period2_interval'];		
$amount3 			= $v['amount3'];	
$period3_value 		= $v['period3_value'];	
$period3_interval 	= $v['period3_interval'];
$prodtype		 	= $v['prodtype'];
$srt 				= $v['srt'];
$billing_cycle 		= $v['srt'];
$squeeze_check 		= $v['squeeze_check'];
$squeezepage 		= $v['squeezepage'];
$squeezepage		= stripslashes($squeezepage);
$kunakicheck		= $v['kunakicheck'];
$tcontent1 			= $v['tcontent'];
$t_name             = $v['template'];
$pp_header		= $v['pp_header'];
$pp_return		= $v['pp_return'];
// Template code here
if($t_name == "default")
{
}
elseif($t_name == 'none' or empty($t_name))
{
     $FILEPATH ="";   
}
else 
{
    $FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/". $t_name ."";
}
// Template code here
$coupon  = $_COOKIE['coupon-'.$short];
/***************************************************************************************************/
if ($cur_product['subscription_active'] == '1')
{
	$fprice=$amount3;
	$price=$amount3;
        $actual_price=$amount3;
}
else
{
	$fprice=$price;
        $actual_price=$price;
}
$_billing_cycle = array('period'=>$period3_interval, 'interval' => $period3_value, 'amount'=> $amount3);
$_billing_cycle['limit'] = $srt;
if($period1_active) $_trial = array('period'=>$period1_interval, 'interval' => $period1_value, 'amount'=> $amount1);
if($period2_active){
	
	if(!$period1_active)  $_trial = array('period'=>$period2_interval, 'interval' => $period2_value, 'amount'=> $amount2);
	else $_trial2 = array('period'=>$period2_interval, 'interval' => $period2_value, 'amount'=> $amount2);
	 
}
/***************************************************************************************************/
if($kunakicheck =='yes')
	{
	$shipping = '0';
	}
if($kunakicheck =='no')
	{
	$shipping = '1';
	}
	
if($period1_active=='0')
	{
	$amount1 = '0';	
	$period1_value = '0';	
	$period1_interval = '0';	
	}	
// Coupon Discount section starts here
//$coupon  = $_GET['coupon'];
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
else
 $discount_price=  $price;
// Coupon Discount section ends here
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
$discount_price = $price;
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
if($period1_active=='0')
{
	$amount1 = '0';
	$period1_value = '0';
	$period1_interval = '0';
}
// Is there a quantity limit?
if($qlimit == "yes")
	{
	// Quantity limited so get total members with product
	$q="select count(*) as cnt from ".$prefix."member_products where product_id='$pid'";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count >= $quantity_cap)
		{
		$tokens =$common->getTextBetweenTags($quantity_met_page);
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
			$returncontent = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $quantity_met_page);	
			$smarty->assign('main_content',$returncontent);
			$output = $smarty->fetch('../html/member/content.tpl');
			$objTpl = new TPLManager($FILEPATH.'/index.html');
			$hotspots = $objTpl->getHotspotList();
			$placeHolders = $objTpl->getPlaceHolders($hotspots);
			$i=0;
			foreach($placeHolders as  $items)
			{
				$smarty->assign("$hotspots[$i]","$items");
				$i++;
			}
			$smarty->assign('content',$output);
			$smarty->assign('error',$errors);
			$smarty->display($FILEPATH.'/index.html');	
		exit;
		}
	}
/***************************************************************************************/
/*                          Create Veiwes
/**************************************************************************************/
$counter->setcounter('',$short,$ip,$ref);
/***************************************************************************************/
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
if($prodtype == "paid")
{
    $image=str_replace("..",'',$image);
    if ($subscription_active == '1' && $fullcoupon == 0)
    {
    $buttons = get_subscription_payment_buttons($_GET['gateway']);
    @extract($buttons);
    }
    elseif ($subscription_active != '1' && $fullcoupon == 0)
    {
    $buttons = get_payment_buttons($_GET['gateway']);
    @extract($buttons);
    }
	
	
if($fullcoupon == '1')
    {
        if(!empty($ref))
        {
            $paypal_button = "<a href='buyfree.php?pshort=$short&c=$coupon&ref=$ref'><img style='border: 0pt none ;' src='".$image."'></a>";
            $alertpay_button = "<a href='buyfree.php?pshort=$short&c=$coupon&ref=$ref'><img style='border: 0pt none ;' src='".$image_alertpay."'></a>";
        }
        else
        {
            $paypal_button = "<a href='buyfree.php?pshort=$short&c=$coupon'><img style='border: 0pt none ;' src='".$image."'></a>";
            $alertpay_button = "<a href='buyfree.php?pshort=$short&c=$coupon'><img style='border: 0pt none ;' src='".$image_alertpay."'></a>";		
        }
      }	
		
}
elseif($prodtype == "free")
{
    if(!empty($ref))
    {
        $paypal_button = "<a href='buyfree.php?pshort=$short&c=$coupon&ref=$ref'><img style='border: 0pt none ;' src='".$image."'></a>";
        $alertpay_button = "<a href='buyfree.php?pshort=$short&c=$coupon&ref=$ref'><img style='border: 0pt none ;' src='".$image_alertpay."'></a>";
    }
    else
    {
        $paypal_button = "<a href='buyfree.php?pshort=$short&c=$coupon'><img style='border: 0pt none ;' src='".$image."'></a>";
        $alertpay_button = "<a href='buyfree.php?pshort=$short&c=$coupon'><img style='border: 0pt none ;' src='".$image_alertpay."'></a>";		
    }
}
/************************************************************************************************/
//'buyfree.php?pshort=$short&ref=$ref
//$member_check='<a href="member/login.php?destination=products.php?short='.$short.'&coupon='.$coupon.'">Existing Members Login Here To Purchase</a>';
/*******************************************************************************/
	
if(!empty($tcontent1)){
$difference=$common->time_release_difference($prefix,$db,$pid,$memberid);
$time_release_content = $common->getTimeRelaseContent($prefix,$db,$tcontent1,$difference);
}
$mydownloads = $common->myDownloads($prefix,$db,$memberid);
$new_products = $common->newProducts($prefix,$db,$memberid);
/***********************************************************************************/
$tokens =$common->getTextBetweenTags($home_page_product);
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
                        $$token = $clickBank->button($temp[2], $pid, $ref, $rands);
                break;	
 			}
 		}
 }	
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
        $pagecontent = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $home_page_product);	
    $smarty->assign('pagename',$product_name);
	$smarty->assign('main_content',$pagecontent);
	$output = $smarty->fetch('../html/member/content.tpl');
/************************************************************************************/
$smarty->assign('time_release_content',$time_release_content);
$smarty->assign('my_downloads',$mydownloads);
$smarty->assign('new_products',$new_products);
$right_panel = $smarty->fetch('../html/member/right_panel.tpl');
/************************************************************************************/
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
    $smarty->assign("menus",$menus);
    $smarty->assign('current_date',$today);
    $smarty->assign('right_panel',$right_panel);
    $smarty->assign('sidebar',$right_panel);
    $smarty->assign('error',$Message);
    $smarty->assign('content',$output);
    $smarty->display($FILEPATH.'/index.html');	
}
 else 
{
	$smarty->display('../html/member/content.tpl');
}
?>