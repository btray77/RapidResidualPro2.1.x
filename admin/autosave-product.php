<?php
include ("session.php");
if(count($_POST) > 0)
{
foreach($_POST as $key =>$items)
	{
		$$key = addslashes(trim($items));
	}
 $pshort = preg_replace('/([^a-z0-9])+/i', '-', $pshort);

 $set = "product_name				= '{$product_name}',";
    $set .= "pshort					= '{$pshort}',";
    $set .= "index_page				= '{$index_page}',";
    $set .= "download_form			= '{$download_form}',";
    $set .= "image					= '" . mysql_real_escape_string(str_replace("..", "", $file_path_paypal)) . "',";
    $set .= "alertpay_image			= '" . mysql_real_escape_string(str_replace("..", "", $file_path_alertpay)) . "',";
    $set .= "clickbank_image        = '" . mysql_real_escape_string(str_replace("..", "", $file_path_clickbank)) . "',";
    $set .= "commission				= '{$commission}',";
    $set .= "jvcommission			= '{$jvcommission}',";
    $set .= "price      			= '{$price}',";
    $set .= "imageurl               = '" . mysql_real_escape_string(str_replace("..", "", $imageurl)) . "',";
    $set .= "prod_description       = '{$prod_description}',";
    $set .= "marketplace  			= '{$marketplace}',";
    $set .= "affiliate_link         = '{$affiliate_link}',";
    $set .= "otocheck  				= '{$otocheck}',";
    $set .= "one_time_offer         = '{$one_time_offer}',";
    $set .= "otodowncheck  			= '{$otodowncheck}',";
    $set .= "down_one_time_offer    = '{$down_one_time_offer}',";
    $set .= "psponder  				= '{$psponder}',";
    $set .= "no_text  				= '{$no_text}',";
    $set .= "quantity_cap			= '{$quantity_cap}',";
    $set .= "qlimit		  			= '{$qlimit}',";
    $set .= "quantity_met_page      = '{$quantity_met_page}',";
    $set .= "subscription_active    = '{$subscription_active}',";
    $set .= "period1_active         = '{$period1_active}',";
    $set .= "period1_value          = '{$period1_value}',";
    $set .= "period1_interval       = '{$period1_interval}',";
    $set .= "srt			  		= '{$srt}',";
    $set .= "amount1  				= '{$amount1}',";
    $set .= "period2_active         = '{$period2_active}',";
    $set .= "period2_value          = '{$period2_value}',";
    $set .= "period2_interval       = '{$period2_interval}',";
    $set .= "amount2  				= '{$amount2}',";
    $set .= "period3_value          = '{$period3_value}',";
    $set .= "period3_interval       = '{$period3_interval}',";
    $set .= "amount3  				= '{$amount3}',";
    $set .= "squeezename  			= '{$squeezename}',";
    $set .= "squeeze_check          = '{$squeeze_check}',";
    $set .= "pp_header 	 			= '{$pp_header}', ";
    $set .= "pp_return	 	 		= '{$pp_return}',";
    $set .= "tcontent  				= '{$tcontent}',";
    $set .= "coaching	 	 		= '{$coaching}',";
    $set .= "template               = '{$template}',";
    $set .= "prodtype				= '{$prodtype}',";
    
    $set .= "click_bank_security_code		= '{$click_bank_security_code}',";
    $set .= "click_bank_url					= '{$click_bank_url}',";
    $set .= "add_in_sidebar                 = '{$add_in_sidebar}',";
    $set .= "member_marketplace             = '{$member_marketplace}',";
    $set .= "button_html                    = '{$button_html}',";
    $set .= "button_forum                   = '{$button_forum}',";
    $set .= "button_link                    = '{$button_link}',";
    
    $set .= "show_affiliate_link_paypal		= '{$show_affiliate_link_paypal}',";
    $set .= "show_affiliate_link_alertpay	= '{$show_affiliate_link_alertpay}',";
    $set .= "show_affiliate_link_clickbank	= '{$show_affiliate_link_clickbank}',";
    
    $set .= "enable_product_partner			= '{$check_product_partner}',";
    $set .= "product_partner_paypal_email	= '{$porduct_partner_paypal_email}',";
    $set .= "product_partner_alertpay_email	= '{$porduct_partner_alertpay_email}',";
    $set .= "ap_partner_ipn_security_code	= '{$porduct_partner_alertpay_ipn}',";
    $set .= "partner_commission             = '{$porduct_partner_commision}'";

if($option=='add')	
{	
	$sql= "insert into " . $prefix . "products set $set";
 	mysql_query($sql) or die(mysql_error());
	$pid = mysql_insert_id();
	
}
else if($option=='edit')
{
	$sql = "update " . $prefix . "products set $set where id = '$prodid'";
	mysql_query($sql) or die(mysql_error());
	 
}
echo $str = '<input type="hidden" name="prodid" value="'.$prodid.'">
			 <input type="hidden" name="option" value="edit">';
}
?>
