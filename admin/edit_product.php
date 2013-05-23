<?php
include ("session.php");
include ("header.php");
include_once ('class-template.php');
// $GetFile = file("../html/admin/edit_product.html");
// $Content = join("", $GetFile);
$Title = "Product Management";
function encodeHTML($sHTML) {
	$sHTML = ereg_replace ( "&", "&amp;", $sHTML );
	$sHTML = ereg_replace ( "<", "&lt;", $sHTML );
	$sHTML = ereg_replace ( ">", "&gt;", $sHTML );
	return $sHTML;
}
if (isset ( $_POST ['submit'] )) {
	
	$get_selected_oto = $otolist;
	$oto_dd = $db->get_oto ( $get_selected_oto );
	
	$product_name = $db->quote ( $_POST ["product_name"] );
	$pshort = preg_replace ( '/([^a-z0-9])+/i', '_', $pshort );
	$pshort = preg_replace ( '/\_$/', '', $pshort );
	$pshort = $db->quote ( $_POST ["pshort"] );
	$index_page = $db->quote ( $_POST ["index_page"] );
	$download_form = $db->quote ( $_POST ["download_form"] );
	$price = $db->quote ( $_POST ["price"] );
	$image = $db->quote ( $_POST ["image"] );
	$commission = $db->quote ( $_POST ["commission"] );
	$jvcommission = $db->quote ( $_POST ["jvcommission"] );
	$prodtype = $db->quote ( $_POST ["prodtype"] );
	$prod_description = $db->quote ( $_POST ["prod_description"] );
	$keywords = $db->quote ( $_POST ["keywords"] );
	$marketplace = $db->quote ( $_POST ["marketplace"] );
	$affiliate_link = $db->quote ( $_POST ["affiliate_link"] );
	$otocheck = $db->quote ( $_POST ["otocheck"] );
	$one_time_offer = $db->quote ( $_POST ['one_time_offer'] );
	$otodowncheck = $db->quote ( $_POST ['otodowncheck'] );
	$down_one_time_offer = $db->quote ( $_POST ['down_one_time_offer'] );
	$otolist = $db->quote ( $_POST ["otolist"] );
	$psponder = $db->quote ( $_POST ["psponder"] );
	$no_text = $db->quote ( $_POST ["no_text"] );
	$qlimit = $db->quote ( $_POST ["qlimit"] );
	$quantity_cap = $db->quote ( $_POST ["quantity_cap"] );
	$quantity_met_page = $db->quote ( $_POST ["quantity_met_page"] );
	
	$subscription_active = $db->quote ( $subscription_active );
	$period1_active = $db->quote ( $_POST ["period1_active"] );
	$period1_value = $db->quote ( $_POST ["period1_value"] );
	$period1_interval = $db->quote ( $_POST ["period1_interval"] );
	$srt = $db->quote ( $_POST ["srt"] );
	$amount1 = $db->quote ( $_POST ["amount1"] );
	$period2_active = $db->quote ( $_POST ["period2_active"] );
	$period2_value = $db->quote ( $_POST ["period2_value"] );
	$period2_interval = $db->quote ( $_POST ["period2_interval"] );
	$amount2 = $db->quote ( $_POST ["amount2"] );
	$period3_value = $db->quote ( $_POST ["period3_value"] );
	$period3_interval = $db->quote ( $_POST ["period3_interval"] );
	$amount3 = $db->quote ( $_POST ["amount3"] );
	$squeezename = $db->quote ( $_POST ["squeezename"] );
	$squeeze_check = $db->quote ( $_POST ["squeeze_check"] );
	$pp_header = $db->quote ( $_POST ["pp_header"] );
	$pp_return = $db->quote ( $_POST ["pp_return"] );
	$tcontent = $db->quote ( $_POST ["tcontent"] );
	$coaching = $db->quote ( $_POST ["coaching"] );
	$template = $db->quote ( $_POST ['selectdir'] );
	$click_bank_url = $db->quote ( $_POST ['click_bank_url'] );
	
	$add_in_sidebar = $db->quote ( $_POST ['add_in_sidebar'] );
	$member_marketplace = $db->quote ( $_POST ['member_marketplace'] );
	$button_html = $db->quote ( $_POST ['button_html'] );
	$button_forum = $db->quote ( $_POST ['button_forum'] );
	$button_link = $db->quote ( $_POST ['button_link'] );
	
	$click_bank_security_code = $db->quote ( $_POST ['click_bank_security_code'] );
	$show_affiliate_link_paypal = $db->quote ( $_POST ['paypal_affiliate_link'] );
	$show_affiliate_link_alertpay = $db->quote ( $_POST ['alertpay_affiliate_link'] );
	$show_affiliate_link_clickbank = $db->quote ( $_POST ['clickbank_affiliate_link'] );
	
	$check_product_partner = $db->quote ( $_POST ['check_product_partner'] );
	$porduct_partner_paypal_email = $db->quote ( $_POST ['porduct_partner_paypal_email'] );
	$porduct_partner_alertpay_email = $db->quote ( $_POST ['porduct_partner_alertpay_email'] );
	$porduct_partner_alertpay_ipn = $db->quote ( $_POST ['porduct_partner_alertpay_ipn'] );
	$porduct_partner_commision = $db->quote ( $_POST ['porduct_partner_commision'] );
	
	// Make sure short name is uniquie
	$q = "select count(*) as cnt from " . $prefix . "products where pshort={$pshort} && id !='$prodid'";
	$r = $db->get_a_line ( $q );
	$count = $r [cnt];
	if ($count > 0) {
		// short name already exists
		header ( "Location: edit_product.php?prodid=$prodid&err=1" );
		exit ();
	}
	
	// Make sure OTO product is not set to add to marketplace
	if ($prodtype == "'OTO'" && $marketplace == "'yes'") {
		header ( "Location: edit_product.php?prodid=$prodid&err=3" );
		exit ();
	}
	
	if (is_dir ( '/images/payment_buttons' )) {
		chmod('/images/payment_buttons',0777);
	} else {
		mkdir ( "/images/payment_buttons", 0777 );
	}
	$imagepath = '../images/payment_buttons/';
	// ---------------------------------------------------------------------------------------------
	// Product File uploading section starts
	// ----------------------------------------------------------------------------------------------
	if ($_FILES ["imageurl"] ['name']) {
		if ((($_FILES ["imageurl"] ["type"] == "image/gif") || ($_FILES ["imageurl"] ["type"] == "image/jpeg") || ($_FILES ["imageurl"] ["type"] == "image/pjpeg") || ($_FILES ["imageurl"] ["type"] == "image/png") || ($_FILES ["imageurl"] ["type"] == "image/x-png"))) {
			if ($_FILES ["imageurl"] ["error"] > 0) {
				$error_status = 'yes';
				header ( "Location: edit_product.php?prodid=$prodid&err=4" );
				exit ();
			} else {
				
				$imageurl = $imagepath . time () . $_FILES ["imageurl"] ["name"];
				move_uploaded_file ( $_FILES ["imageurl"] ["tmp_name"], $imageurl );
			}
		} else {
			header ( "Location: edit_product.php?prodid=$prodid&err=4" );
			exit ();
		}
	} else {
		$imageurl = $_POST ['imageurl_hid'];
	}
	// ----------------------------------------------------------------------------------------------
	// PayPal File uploading section starts
	// -----------------------------------------------------------------------------------------------
	if ($_FILES ["paypal_image"] ['name']) {
		if ((($_FILES ["paypal_image"] ["type"] == "image/gif") || ($_FILES ["paypal_image"] ["type"] == "image/jpeg") || ($_FILES ["paypal_image"] ["type"] == "image/pjpeg") || ($_FILES ["paypal_image"] ["type"] == "image/png") || ($_FILES ["paypal_image"] ["type"] == "image/x-png"))) {
			if ($_FILES ["paypal_image"] ["error"] > 0) {
				$error_status = 'yes';
				header ( "Location: edit_product.php?prodid=$prodid&err=4" );
				exit ();
			} else {
				
				$file_path_paypal = $imagepath . time () . $_FILES ["paypal_image"] ["name"];
				move_uploaded_file ( $_FILES ["paypal_image"] ["tmp_name"], $file_path_paypal );
			}
		} else {
			header ( "Location: edit_product.php?prodid=$prodid&err=4" );
			exit ();
		}
	} else {
		$file_path_paypal = $_POST ['paypal_hid_image'];
	}
	// ----------------------------------------------------------------------------------------------
	// AlertPay File uploading section starts
	// ----------------------------------------------------------------------------------------------
	
	if ($_FILES ["alertpay_image"] ['name']) {
		
		if ((($_FILES ["alertpay_image"] ["type"] == "image/gif") || ($_FILES ["alertpay_image"] ["type"] == "image/jpeg") || ($_FILES ["alertpay_image"] ["type"] == "image/pjpeg") || ($_FILES ["alertpay_image"] ["type"] == "image/png") || ($_FILES ["alertpay_image"] ["type"] == "image/x-png"))) {
			if ($_FILES ["alertpay_image"] ["error"] > 0) {
				$error_status = 'yes';
				header ( "Location: edit_product.php?prodid=$prodid&err=5" );
				exit ();
			} else {
				
				$file_path_alertpay = $imagepath . time () . $_FILES ["alertpay_image"] ["name"];
				move_uploaded_file ( $_FILES ["alertpay_image"] ["tmp_name"], $file_path_alertpay );
			}
		} else {
			header ( "Location: edit_product.php?prodid=$prodid&err=5" );
			exit ();
		}
	} else {
		$file_path_alertpay = $_POST ['alertpay_hid_image'];
	}
	
	// ----------------------------------------------------------------------------------------------
	// ClickBank File uploading section starts
	// ----------------------------------------------------------------------------------------------
	
	if ($_FILES ["clickbank_image_upload"] ['name']) {
		
		if ((($_FILES ["clickbank_image_upload"] ["type"] == "image/gif") || ($_FILES ["clickbank_image_upload"] ["type"] == "image/jpeg") || ($_FILES ["clickbank_image_upload"] ["type"] == "image/pjpeg") || ($_FILES ["clickbank_image_upload"] ["type"] == "image/png") || ($_FILES ["clickbank_image_upload"] ["type"] == "image/x-png"))) {
			if ($_FILES ["clickbank_image_upload"] ["error"] > 0) {
				$error_status = 'yes';
				header ( "Location: edit_product.php?prodid=$prodid&err=6" );
				exit ();
			} else {
				$file_path_clickbank = $imagepath . time () . $_FILES ["clickbank_image_upload"] ["name"];
				move_uploaded_file ( $_FILES ["clickbank_image_upload"] ["tmp_name"], $file_path_clickbank );
			}
		} else {
			header ( "Location: edit_product.php?prodid=$prodid&err=6" );
			exit ();
		}
	} else {
		$file_path_clickbank = $_POST ['clickbank_hid_image'];
	}
	
	// ----------------------------------------------------------------------------------------------
	// Write to database
	// ----------------------------------------------------------------------------------------------
	
	$set = "product_name		= {$product_name},";
	$set .= "pshort			= {$pshort},";
	$set .= "index_page			= {$index_page},";
	$set .= "download_form		= {$download_form},";
	$set .= "image			= '" . mysql_real_escape_string ( $file_path_paypal ) . "',";
	$set .= "alertpay_image		= '" . mysql_real_escape_string ( $file_path_alertpay ) . "',";
	$set .= "clickbank_image		= '" . mysql_real_escape_string ( $file_path_clickbank ) . "',";
	$set .= "commission			= {$commission},";
	$set .= "jvcommission		= {$jvcommission},";
	$set .= "price  			= {$price},";
	$set .= "imageurl			= '" . mysql_real_escape_string ( $imageurl ) . "',";
	$set .= "prod_description  		= {$prod_description},";
	$set .= "keywords  		= {$keywords},";
	
	$set .= "marketplace  		= {$marketplace},";
	$set .= "affiliate_link   		= {$affiliate_link},";
	$set .= "otocheck  			= {$otocheck},";
	$set .= "one_time_offer  		= {$one_time_offer},";
	$set .= "otodowncheck  		= {$otodowncheck},";
	$set .= "down_one_time_offer	= {$down_one_time_offer},";
	$set .= "psponder  			= {$psponder},";
	$set .= "no_text  			= {$no_text},";
	$set .= "quantity_cap		= {$quantity_cap},";
	$set .= "qlimit		  	= {$qlimit},";
	$set .= "quantity_met_page	  	= {$quantity_met_page},";
	$set .= "subscription_active  	= {$subscription_active},";
	$set .= "period1_active  		= {$period1_active},";
	$set .= "period1_value  		= {$period1_value},";
	$set .= "period1_interval  		= {$period1_interval},";
	$set .= "srt			= {$srt},";
	$set .= "amount1  			= {$amount1},";
	$set .= "period2_active  		= {$period2_active},";
	$set .= "period2_value  		= {$period2_value},";
	$set .= "period2_interval  		= {$period2_interval},";
	$set .= "amount2  			= {$amount2},";
	$set .= "period3_value  		= {$period3_value},";
	$set .= "period3_interval  		= {$period3_interval},";
	$set .= "amount3  			= {$amount3},";
	$set .= "squeezename  		= {$squeezename},";
	$set .= "squeeze_check  		= {$squeeze_check},";
	$set .= "pp_header 	 		= {$pp_header}, ";
	$set .= "pp_return	 	 	= {$pp_return},";
	$set .= "tcontent  			= {$tcontent},";
	$set .= "coaching	 	 	= {$coaching},";
	$set .= "template                   = {$template},";
	$set .= "prodtype			= {$prodtype},";
	$set .= "click_bank_security_code	= {$click_bank_security_code},";
	$set .= "click_bank_url		= {$click_bank_url},";
	
	$set .= "add_in_sidebar		= {$add_in_sidebar},";
	$set .= "member_marketplace 	= {$member_marketplace},";
	$set .= "button_html		= {$button_html},";
	$set .= "button_forum		= {$button_forum},";
	$set .= "button_link		= {$button_link},";
	$set .= "show_affiliate_link_paypal		= {$show_affiliate_link_paypal},";
	$set .= "show_affiliate_link_alertpay	= {$show_affiliate_link_alertpay},";
	$set .= "show_affiliate_link_clickbank	= {$show_affiliate_link_clickbank},";
	
	$set .= "enable_product_partner		= {$check_product_partner},";
	$set .= "product_partner_paypal_email	= {$porduct_partner_paypal_email},";
	$set .= "product_partner_alertpay_email	= {$porduct_partner_alertpay_email},";
	$set .= "ap_partner_ipn_security_code	= {$porduct_partner_alertpay_ipn},";
	$set .= "partner_commission                 = {$porduct_partner_commision}";
	
	$sql = "update " . $prefix . "products set $set where id = '$prodid'";
	
	mysql_query ( $sql ) or die ( mysql_error () );
	
	header ( "Location: paid_products.php?msg=paid" );
}
// ----------------------------------------------------------------------------------------------
// Get Product info from database
// ----------------------------------------------------------------------------------------------
$GetProd = $db->get_a_line ( "select * from " . $prefix . "products where id = '$prodid'" );
@extract ( $GetProd );
$product_name = stripslashes ( $product_name );
$pshort = stripslashes ( $pshort );
$price = $price;
$index_page = stripslashes ( $index_page );
$download_form = stripslashes ( $download_form );
$p_image = stripslashes ( $image );
$a_image = stripslashes ( $alertpay_image );
$c_image = stripslashes ( $clickbank_image );
$commission = stripslashes ( $commission );
$jvcommission = stripslashes ( $jvcommission );
$imageurl = stripslashes ( $imageurl );
$prod_description = stripslashes ( $prod_description );
$keywords = stripslashes ( $keywords );
$marketplacecheck = stripslashes ( $marketplace );
$showpaid = $psponder;
$no_text = stripslashes ( $no_text );
$prodcheck = $prodtype;
$quantity_cap = $quantity_cap;
$qcheck = $qlimit;
$quantity_met_page = $quantity_met_page;
$showsqueeze = $squeezename;
$pp_header = $pp_header;
$pp_return = $pp_return;
$showtimed = $tcontent;
$sowcoaching = $showcoaching;
$click_bank_url = $click_bank_url;
$click_bank_security_code = $click_bank_security_code;
$t_name = $template;
if (! empty ( $p_image )) {
	$paypal_image = "<a href='" . stripslashes ( $p_image ) . "' target='_blank' rel='prettyPhoto'><img  height='30' border='0' align='absmiddle' alt='View File' title='View File' src='" . stripslashes ( $p_image ) . "'></a>";
} else {
	$paypal_image = "";
	$p_image = "/images/payment_buttons/paypal_button.png";
}
if (! empty ( $a_image )) {
	$alertpay_image = "<a href='" . stripslashes ( $a_image ) . "' target='_blank' rel='prettyPhoto'><img  height='30' border='0' align='absmiddle' alt='View File' title='View File' src='" . stripslashes ( $a_image ) . "'></a>";
} else {
	$alertpay_image = "";
	$a_image = "/images/payment_buttons/alertpay-button.gif";
}
if (! empty ( $c_image )) {
	$clickbank_image = "<a href='" . stripslashes ( $c_image ) . "' target='_blank' rel='prettyPhoto'><img  height='30' border='0' align='absmiddle' alt='View File' title='View File' src='" . stripslashes ( $c_image ) . "'></a>";
} else {
	$clickbank_image = "";
	$c_image = "/images/payment_buttons/clickbank-button.png";
}
if (! empty ( $imageurl )) {
	
	list ( $width, $height ) = @getimagesize ( $imageurl );
	if ($width > $height)
		$shight = 60;
	else if ($width < 100 or $height < 100)
		$shight = 60;
	else
		$shight = 150;
	$product_image = "<a href='" . stripslashes ( $imageurl ) . "' target='_blank' rel='prettyPhoto'>
	<img  height='$shight' border='0' align='absmiddle' alt='View File' title='View File' src='" . stripslashes ( $imageurl ) . "'></a>";
} else {
	$product_image = "";
}
// Template function starts here
$obj_template = new Template_information ( "../templates/" );
$dir_name = $obj_template->ReadFolderDirectory ();
function select_Dir($dir_name, $pageid, $t_name) {
	if ($t_name == 'default')
		$default = 'selected="selected"';
	else
		$default = '';
	if ($t_name == 'none')
		$none = 'selected="selected"';
	else
		$none = '';
		
		/* $str.='<option value="0" >Select Template</option>'; */
	$str .= '<option value="none" ' . $none . ' >none</option>';
	$str .= '<option value="default"' . $default . '>default</option>';
	foreach ( $dir_name as $name ) {
		
		if ($t_name == $name)
			$select = 'selected="selected"';
		else
			$select = "";
			// echo "$name----$selected -----$select <br>";
		$str .= '<option value="' . $name . '" ' . $select . '>' . $name . '</option>';
	}
	
	return $str;
}
$select_dir = select_Dir ( $dir_name, $row ['id'], $t_name );
// Template function ends here
$q = "select * from " . $prefix . "squeeze_pages order by name";
$r = $db->get_rsltset ( $q );
for($i = 0; $i < count ( $r ); $i ++) {
	@extract ( $r [$i] );
	$pid = $name;
	
	if ($pid == $showsqueeze) {
		$squeezename .= "<option value='$pid' Selected>$pid</option>";
	} elseif ($pid != $showsqueeze) {
		$squeezename .= "<option value='$pid'>$pid</option>";
	}
}
$squeeze_check = $squeeze_check;
if ($squeeze_check == 'yes') {
	$squeeze_check1 = 'checked';
} else if ($squeeze_check == 'no') {
	$squeeze_check2 = 'checked';
}
// subscription active
$subscription_active = $subscription_active;
if ($subscription_active == 1) {
	$sub_active = "checked";
}
// trial period 1
$period1_active = $period1_active;
$period1_value = $period1_value;
$period1_interval = $period1_interval;
$srt = $srt;
$amount1 = $amount1;
if ($period1_active == 1) {
	$p1_active = "checked";
}
switch ($period1_interval) {
	case 'D' :
		$p1_interval_d = "selected";
		break;
	
	case 'W' :
		$p1_interval_w = "selected";
		break;
	
	case 'M' :
		$p1_interval_m = "selected";
		break;
	
	case 'Y' :
		$p1_interval_y = "selected";
		break;
}
// trial period 2
$period2_active = $period2_active;
$period2_value = $period2_value;
$period2_interval = $period2_interval;
$amount2 = $amount2;
if ($period2_active == 1) {
	$p2_active = "checked";
}
switch ($period2_interval) {
	case 'D' :
		$p2_interval_d = "selected";
		break;
	
	case 'W' :
		$p2_interval_w = "selected";
		break;
	
	case 'M' :
		$p2_interval_m = "selected";
		break;
	
	case 'Y' :
		$p2_interval_y = "selected";
		break;
}
// regular subscription
$period3_value = $period3_value;
$period3_interval = $period3_interval;
$amount3 = $amount3;
switch ($period3_interval) {
	case 'D' :
		$p3_interval_d = "selected";
		break;
	
	case 'W' :
		$p3_interval_w = "selected";
		break;
	
	case 'M' :
		$p3_interval_m = "selected";
		break;
	
	case 'Y' :
		$p3_interval_y = "selected";
		break;
}
if ($qcheck == 'yes') {
	$qlimit1 = 'checked';
} else if ($qcheck == 'no') {
	$qlimit2 = 'checked';
}
if ($marketplacecheck == 'yes') {
	$mark1 = 'checked';
} else if ($marketplacecheck == 'no') {
	$mark2 = 'checked';
}
if ($affiliate_link == 'yes') {
	$affilate1 = 'checked';
} else if ($affiliate_link == 'no') {
	$affilate2 = 'checked';
}
if ($coaching == 'yes') {
	$coaching1 = 'checked';
} else if ($coaching == 'no') {
	$coaching2 = 'checked';
}
$q = "select * from " . $prefix . "responders order by rspname2";
$r = $db->get_rsltset ( $q );
for($i = 0; $i < count ( $r ); $i ++) {
	@extract ( $r [$i] );
	$pid = $rspname2;
	
	if ($pid == $showpaid) {
		$psponder .= "<option value='$pid' Selected>$pid</option>";
	} elseif ($pid != $showpaid) {
		$psponder .= "<option value='$pid'>$pid</option>";
	}
}
if ($add_in_sidebar == 'yes') {
	$add_in_sidebar_checked1 = 'checked';
} else if ($add_in_sidebar == 'no') {
	$add_in_sidebar_checked2 = 'checked';
}
if ($member_marketplace == 'yes') {
	$member_marketplace_checked1 = 'checked';
} else if ($member_marketplace == 'no') {
	$member_marketplace_checked2 = 'checked';
}
$getoto = stripslashes ( $otocheck );
if ($getoto == 'yes') {
	$otocheck1 = 'checked';
} else if ($getoto == 'no') {
	$otocheck2 = 'checked';
}
$get_selected_oto = $one_time_offer;
$oto_dd = $db->get_oto ( $get_selected_oto );
$getdownoto = stripslashes ( $otodowncheck );
if ($getdownoto == 'yes') {
	$otodowncheck1 = 'checked';
} else if ($getdownoto == 'no') {
	$otodowncheck2 = 'checked';
}
$get_selected_down_oto = $down_one_time_offer;
$down_oto_dd = $db->get_oto ( $get_selected_down_oto );
$q = "select * from " . $prefix . "tccampaign order by shortname";
$r = $db->get_rsltset ( $q );
for($i = 0; $i < count ( $r ); $i ++) {
	@extract ( $r [$i] );
	$pid = $shortname;
	
	if ($pid == $showtimed) {
		$tcontent .= "<option value='$pid' Selected>$pid</option>";
	} elseif ($pid != $showtimed) {
		$tcontent .= "<option value='$pid'>$pid</option>";
	}
}
if ($err == '1') {
	$warning = "<div class='error'><img src='/images/crose.png' border='0'> Product short name already in use.</div>";
}
if ($err == '2') {
	$warning = "<div class='error'><img src='/images/crose.png' border='0'> OTO products can not be set as the home page product.</div>";
}
if ($err == '3') {
	$warning = "<div class='error'><img src='/images/crose.png' border='0'> OTO products can not be shown on the marketplace.</div>";
}
if ($err == '4') {
	$warning = "<div class='error'><img src='/images/crose.png' border='0'> PayPal image uploading failed.</div>";
}
if ($err == '5') {
	$warning = "<div class='error'><img src='/images/crose.png' border='0'> Alert Pay image uploading failed.</div>";
}
if ($err == '6') {
	$warning = "<div class='error'><img src='/images/crose.png' border='0'> ClickBank image uploading failed.</div>";
}
if ($err == '7') {
	$warning = "<div class='error'><img src='/images/crose.png' border='0'> ClickBank image uploading failed.</div>";
}
?>
<script language="javascript1.5"
	src="/common/newLayout/jquery/products.js"></script>
<script>
    $(document).ready(function() {
        $("legend").click(function() {
            if($(this).hasClass('collapsible'))
                $(this).removeClass('collapsed').addClass('collapsible');
			
            $(this).toggleClass("collapsible");
            $(this).nextAll("div").slideToggle(500);
            $(this).nextAll("ul").slideToggle(500);
        });
      
        $("fieldset legend").nextAll("div").hide(); 
        $("fieldset legend").nextAll("ul").hide(); 
        /*************************************************************************************************************************/
        $("#subscription_active").click(function(){
            value = $('input:checked[name=subscription_active]:checked').val();
            if(value==1)
            {
             
                $("#subscription_active_billing").fadeIn(500); 
                $("#pricefield").fadeOut(500);
                $("#pricefield").removeClass("required");
                $("#amount3").addClass("required");
            }
            else
            {
               
                $("#subscription_active_billing").fadeOut(500);
                $("#pricefield").fadeIn(500);
                 
              
            }
        });
        /*******************************************************************************/
        $("#check_product_partner").click(function(){
            value = $('input:checked[name=check_product_partner]:checked').val();
            if(value==1)
            {
                $("#partners_settings").fadeIn(500);
            }
            else
            {
                $("#partners_settings").fadeOut(500);
            }
        });   
        /********************************************************************************/ 
    
        $("input:radio[name=affiliate_link]").click(function() {
    
            value = $('input:radio[name=affiliate_link]:checked').val();
      
        
            if(value=="yes")
            {
                if($("#prodtype").val() =="Clickbank") {
                    $("#clickbank_affiliate_link").fadeIn(500);
                }
                else
                {
                    $("#paypal_affiliate_link").fadeIn(500);
                    $("#alertpay_affiliate_link").fadeIn(500);
                }
            }
            else
            {   $("#clickbank_affiliate_link").fadeOut(500);
                $("#paypal_affiliate_link").fadeOut(500);
                $("#alertpay_affiliate_link").fadeOut(500);
            }
                
        });
    
        /*************************************************************************************************************************/
    
        $("input:radio[name=gateway_select]").click(function() {
            value = $('input:radio[name=gateway_select]:checked').val();
            if(value==1)
            { 
                botton_html='<a href="<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/paypal"><img src="<?php echo $http_path ?>/<?php echo stripslashes(str_replace('../', '', $p_image)); ?>" alt="Buy" /></a>';
                button_forum='[url=<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/paypal][img] <?php echo $http_path ?>/<?php echo stripslashes(str_replace('../', '', $p_image)); ?>[/img][/url]';
                button_link='<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/paypal';
            }
            else if(value==2)
            {
                botton_html='<a href="<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/alertpay"><img src="<?php echo $http_path ?>/<?php echo stripslashes(str_replace('../', '', $a_image)); ?>" alt="Buy" /></a>';
                button_forum='[url=<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/alertpay][img] <?php echo $http_path ?>/<?php echo stripslashes(str_replace('../', '', $a_image)); ?>[/img][/url]';
                button_link='<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/alertpay';
            }
            else if(value==3){
                botton_html='<a href="<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/clickbank"><img src="<?php echo $http_path ?>/<?php echo stripslashes(str_replace('../', '', $c_image)); ?>" alt="Buy" /></a>';
                button_forum='[url=<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/clickbank][img] <?php echo $http_path ?>/<?php echo stripslashes(str_replace('../', '', $c_image)); ?> [/img][/url]';
                button_link='<?php echo $http_path ?>/buynow/<?php echo $prodid ?>/clickbank';
            }
            $("#button_html").val(botton_html);
            $("#button_forum").val(button_forum);
            $("#button_link").val(button_link);
        });
        /*************************************************************************************************************************/
        $("#quantity_cap").keyup(function(){
            if($("#quantity_cap").val() > 0)
                $("#meetquantity").fadeIn(500);
            else
                $("#meetquantity").fadeOut(500);
        });
        /********************************************/
		
        $("#qlimit1").click(function(){$("#quantitytext").fadeIn(500);$("#meetquantity").fadeIn(500);});
        $("#qlimit2").click(function(){$("#quantitytext").fadeOut(500);$("#meetquantity").fadeOut(500);});
		
        $("#squeeze_check1").click(function(){
            $("#squeezenametext").fadeIn(500);
			
			
			
        });
        $("#squeeze_check2").click(function(){
            $("#squeezenametext").fadeOut(500);
			
			
        });
		
		
        /********************************************/
        $("#prodtype").change(function(){
            $("#ototext").fadeOut(500);
            $("#clickbanktext").fadeOut(500);
            $("#paypaltext").fadeIn(500);
            $("#alertpaytext").fadeIn(500);
            $("#product_prices").fadeIn(500);
            $("#priceaffiliate").fadeIn(500);
            $("#radio_others").fadeIn(500);
            $("#pricejv").fadeIn(500);
            $("#marketplace_settings").fadeIn(500);
            $("#formsbuttons").fadeIn(500);
            $("#affiliate_program").fadeIn(500);
            $("#clickbank_affiliate_link").fadeOut(500);
            $("#radio_clickbank").fadeOut(500);
            $("#field_partners_settings").fadeIn(500);
                        
            $('input:radio[name=affiliate_link]:eq(no)').attr('checked', 'checked');
                        
                        
            if($("#prodtype").val() =="OTO")
            {
                $("#ototext").fadeIn(500);
                $("#clickbank_user_id").fadeOut(500);
                $("#clickbank_security_code").fadeOut(500);
                $("#clickbank_button").fadeOut(500);
                $("#affiliate_program").fadeOut(500);
                $("#alertpay_affiliate_link").fadeOut(500);
                $("#paypal_affiliate_link").fadeOut(500);
                $("#marketplace_settings").fadeOut(500);
                $("#formsbuttons").fadeOut(500);
            }
            else if($("#prodtype").val() =="Clickbank")
            {
                $("#paypaltext").fadeOut(500);
                $("#alertpaytext").fadeOut(500);
                $("#ototext").fadeOut(500);
                $("#alertpay_affiliate_link").fadeOut(500);
                $("#field_partners_settings").fadeOut(500);
                $("#paypal_affiliate_link").fadeOut(500);
                $("#clickbank_user_id").fadeIn(500);
                $("#clickbank_security_code").fadeIn(500);
                $("#clickbank_button").fadeIn(500);
                $("#radio_others").fadeOut(500);
                $("#radio_clickbank").fadeIn(500);
                
				
				
            }
            else if($("#prodtype").val() =="free")
            {
                $("#product_prices").fadeOut(500);
                $("#priceaffiliate").fadeOut(500);
                $("#pricejv").fadeOut(500);
                $("#clickbank_user_id").fadeOut(500);
                $("#clickbank_security_code").fadeOut(500);
                $("#clickbank_button").fadeOut(500);
                $("#pricefield").removeClass("required");
                $("#field_partners_settings").fadeOut(500);
				
            }	
            else
            {
				
                $("#ototext").fadeOut(500);
                $("#clickbank_user_id").fadeOut(500);
                $("#clickbank_security_code").fadeOut(500);
                $("#clickbank_button").fadeOut(500);
                $("#pricefield").fadeIn(500);
                $("#priceaffiliate").fadeIn(500);
                $("#pricejv").fadeIn(500);
            }	
        });
        /******************************************************************/
        $(".tab_content").hide(); //Hide all content
        $("ul.tabs li:first").addClass("active").show(); //Activate first tab
        $(".tab_content:first").show(); //Show first tab content
	
        //On Click Event
        $("ul.tabs li").click(function() {
            $("ul.tabs li").removeClass("active"); //Remove any "active" class
            $(this).addClass("active"); //Add "active" class to selected tab
            $(".tab_content").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            $(activeTab).fadeIn(); //Fade in the active content
            return false;
        });	
	
        /***********************************************************************************/
 	
        $.validator.addMethod( "commistion", function(value, element) {
            var commission = <?php echo get_total_commission($prefix, $db); ?>;
            return this.optional(element) || value < commission;
        }, "Commision must be less then <?php echo get_total_commission($prefix, $db); ?> %" 
    );
        $("#editproduct").validate();	
    });
</script>
<script>
    var ajaxcontent = function(){
	
        var currentIFrame = $('#idContentoEdit1'); 
        quantity_data = currentIFrame.contents().find("body").html();
        $("#index_page").html(quantity_data); 
	
        var currentIFrame = $('#idContentoEdit2'); 
        sales_data = currentIFrame.contents().find("body").html();
        $("#quantity_met_page").html(sales_data); 
	
        var currentIFrame = $('#idContentoEdit3'); 
        download_data = currentIFrame.contents().find("body").html();
        $("#download_form").html(download_data); 
	
        var formdata = $("form").serialize();
        $('#loading').css('display','block');
	 
	
        $.ajax({
            type: 'POST',
            url: 'edit_product.php', //autosave-product
            data : formdata ,
            dataType: 'html',
            cacheBoolean:false,
            success: function(data) {
				 
                //$('#loading').css('display','none');
                $('#hidden_field').html(data);
				   
            }
        }); 
    };
    $(document).ready(function(){
	
        setInterval(ajaxcontent, 300000);
	
	 
    });
</script>
<?php echo $warning?>
<div class="success" id="loading" style="display: none">
	<img src='/images/tick.png' border='0' align="absmiddle"> Product is
	automatically saved.
</div>
<?php echo $warning?>
<div class="content-wrap">
	<div class="content-wrap-top"></div>
	<div class="content-wrap-inner">
		<strong>Edit Product</strong>
		<div class="buttons">
			<a href="paid_products.php">Go Back</a>
		</div>
		<div class="formborder">
			<form name="editproduct" id="editproduct" action="edit_product.php"
				method="post" enctype="multipart/form-data">
				<input type="hidden" name="prodid" value="<?php echo $prodid ?>"> <input
					type="hidden" name="option" value="edit">
				<div class="leftpanel main">
					<div class="fields">
						<div class="field">
							<label>Product Name: <span class="star">*</span></label> <input
								type="text" name="product_name" size="40"
								value="<?php echo $product_name ?>" class="inputbox required" />
							<div class="tool">
								<a href="" class="tooltip"
									title="Product Name is the name you want to give your product. 
				This can be used in the sales page and it will be used by PayPal during checkout. 
				This will also show in the members area when members view their purchased products list.">
									<img src="/images/toolTip.png" alt="help" />
								</a>
							</div>
						</div>
						<div class="field">
							<label>Product Short Name: <span class="star">*</span></label> <input
								name="pshort" type="text" size="40" class="inputbox required"
								id="pshort" value="<?php echo $pshort ?>" />
							<div class="tool">
								<a href="" class="tooltip"
									title="Product Short Name is the nickname for your product. 
				This name will be used in various places in the script to represent this product. 
				There should be no spaces in the product short name."> <img
									src="/images/toolTip.png" alt="help" />
								</a>
							</div>
						</div>
						<div class="field">
						 <label>Keyword:<span class="star">*</span></label>
                            <input type="text" class="required" name="keywords" size="40" value="<?php echo $keywords ?>">
                            <div class="tool">
                                <a href="" class="tooltip" title="Products Meta Keywords for Search Engine">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
						</div>	
						<div class="field">
							<label>Product Description:<span class="star">*</span></label> <input
								class="inputbox" readonly type="text" name="countdown" size="3"
								value="<?php echo 500 - strlen($prod_description); ?>">
							characters left.<br />
							<textarea name="prod_description" class="inputbox required"
								onKeyDown="limitText(this.form.prod_description,this.form.countdown,500);"
								onKeyUp="limitText(this.form.prod_description,this.form.countdown,500);"
								cols="60" rows="8" id="prod_description"><?php echo $prod_description ?></textarea>
							<div class="tool">
								<a href="" class="tooltip"
									title="Product Description is a short paragraph to describe your product in the marketplace.">
									<img src="../images/toolTip.png" alt="help" align="top" />
								</a>
							</div>
						</div>
						<div class="field">
							<label>Product Image:</label>
							<div class="inputfields">
								<input name="imageurl" type="file" class="inputbox"
									id="imageurl" /><br /> <small>Recommended Resolution is 150px x
									200px</small>
							</div>
							<input type="hidden" name="imageurl_hid" id="imageurl_hid"
								value="<?php echo $imageurl ?>" />
							<div id="productimg">
                                <?php if (!empty($product_image)) { ?>
                                    <div class="productimage">
                                        <?php echo $product_image?>
                                    </div>
                                <?php } ?>
                            </div>
							<div class="tool" style="position: relative; right: -11em;">
								<a href="" class="tooltip"
									title="Product Image is the image that will display beside your product description in the marketplace.">
									<img src="../images/toolTip.png" alt="help" align="top" />
								</a>
							</div>
						</div>
						<div class="field" id="ototext"
							<?
							if ($prodcheck == 'OTO')
								echo 'style="display:block" ';
							else
								echo 'style="display:none" ';
							?>>
							<label>No Thanks Text:</label>
							<textarea rows="2" cols="70" name="no_text" id="no_text"><?php echo $no_text?></textarea>
							<div class="tool">
								<a href="" class="tooltip"
									title="No Thanks Text is the text you want to display on the No Thanks link if this is an One Time Offer.">
									<img src="../images/toolTip.png" alt="help" align="top" />
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="rightpanel">
					<fieldset>
						<legend class="legend">Product Payment Buttons</legend>
						<small>Add mutiple payment buttons like PayPal, Alertpay and
							ClickBank </small>
						<div class="fields">
							<div class="field">
								<label>Product Type:</label> <select name="prodtype"
									id="prodtype">
									<option value="free"
										<?php
										
if ($prodcheck == 'free')
											echo 'selected';
										?>>Free</option>
									<option value="paid"
										<?php
										
if ($prodcheck == 'paid')
											echo 'selected';
										?>>Paid</option>
									<option value="OTO"
										<?php
										
if ($prodcheck == 'OTO')
											echo 'selected';
										?>>OTO</option>
									<option value="Clickbank"
										<?php
										
if ($prodcheck == 'Clickbank')
											echo 'selected';
										?>>Clickbank</option>
								</select>
								<div class="tool">
									<a href="" class="tooltip"
										title="Product Type is where you choose what type of product you want: free, paid, or OTO.">
										<img src="../images/toolTip.png" alt="help" />
									</a>
								</div>
							</div>
							<div class="field" id="paypaltext"
								<?php
								if ($prodcheck == 'Clickbank')
									echo 'style="display:none"';
								else
									echo 'style="display:block"';
								?>>
								<label>PayPal Button Image:</label> <input name="paypal_image"
									type="file" class="inputbox" id="paypal_image" /> <?php echo $paypal_image?>
                                <input type="hidden"
									name="paypal_hid_image" id="paypal_hid_image"
									value="<?php echo $p_image ?>" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Button Image is the image you want to use for your buy button on your sales page. Make sure the image is already in your images directory or 
				it will not display properly."> <img src="../images/toolTip.png"
										alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="alertpaytext"
								<?php
								if ($prodcheck == 'Clickbank')
									echo 'style="display:none"';
								else
									echo 'style="display:block"';
								?>>
								<label>Alert Pay Button Image:</label> <input
									name="alertpay_image" type="file" class="inputbox"
									id="alertpay_image" /> <?php echo $alertpay_image; ?>
                                <input type="hidden"
									name="alertpay_hid_image" id="alertpay_hid_image"
									value="<?php echo $a_image ?>" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Button Image is the image you want to use for your buy button on your sales page. Make sure the image is already in your images directory or 
				it will not display properly."> <img src="../images/toolTip.png"
										alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="clickbank_user_id"
								<?php
								if ($prodcheck == 'Clickbank')
									echo 'style="display:block"';
								else
									echo 'style="display:none"';
								?>>
								<label>Click Bank User Id:</label> <input class="inputbox"
									name="click_bank_url" id="click_bank_url" type="text"
									value="<?php echo $click_bank_url; ?>" />
								<div class="tool">
									<a href="" class="tooltip" title="Enter ClickBank user id."> <img
										src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="clickbank_security_code"
								<?php
								if ($prodcheck == 'Clickbank')
									echo 'style="display:block"';
								else
									echo 'style="display:none"';
								?>>
								<label>Click Bank Security Code:</label> <input class="inputbox"
									name="click_bank_security_code" id="click_bank_security_code"
									type="text" value="<?php echo $click_bank_security_code; ?>" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Please enter ClickBank security code."> <img
										src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="clickbank_button"
								<?php
								if ($prodcheck == 'Clickbank')
									echo 'style="display:block"';
								else
									echo 'style="display:none"';
								?>>
								<label>Click Bank Button Image:</label> <input
									name="clickbank_image_upload" type="file" class="inputbox"
									id="clickbank_image" />  <?php echo $clickbank_image; ?>
                                <input type="hidden"
									name="clickbank_hid_image" id="clickbank_hid_image"
									value=" <?php echo $c_image; ?>" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Please enter ClickBank Button Image."> <img
										src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="clickbank_button" style="display: none">
								<label>Click Bank Button Image:</label> <input
									name="clickbank_image" type="file" class="inputbox"
									id="clickbank_image" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Please enter ClickBank Button Image."> <img
										src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="affiliate_link"
								<?php
								if ($prodcheck == 'OTO')
									echo 'style="display:none"';
								else
									echo 'style="display:block"';
								?>>
								<label>Affiliate program:</label> <input class="inputbox"
									name="affiliate_link" type="radio" value="yes"
									<?php echo $affilate1 ?> />Yes <input class="inputbox"
									name="affiliate_link" type="radio" value="no"
									<?php echo $affilate2 ?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Add To Affiliate Program will enable or disable the affiliate link of this product in the product marketplace.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="paypal_affiliate_link"
								<?php
								if ($prodcheck == 'Clickbank' or $prodcheck == 'OTO')
									echo 'style="display:none"';
								else
									echo 'style="display:block"';
								?>>
								<label>Show PayPal Affiliate link:</label> <input
									class="inputbox" name="paypal_affiliate_link" type="radio"
									value="yes"
									<?php
									
if ($show_affiliate_link_paypal == 'yes')
										echo "checked";
									?> />Yes <input
									class="inputbox" name="paypal_affiliate_link" type="radio"
									value="no"
									<?php
									
if ($show_affiliate_link_paypal == 'no')
										echo "checked";
									?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="PayPal Affiliate link enable PayPal Affiliate Link in marketplace in member area.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="alertpay_affiliate_link"
								<?php
								if ($prodcheck == 'Clickbank' or $prodcheck == 'OTO')
									echo 'style="display:none"';
								else
									echo 'style="display:block"';
								?>>
								<label>Show AlertPay Affiliate link:</label> <input
									class="inputbox" name="alertpay_affiliate_link" type="radio"
									value="yes"
									<?php
									
if ($show_affiliate_link_alertpay == 'yes')
										echo "checked";
									?> />Yes <input
									class="inputbox" name="alertpay_affiliate_link" type="radio"
									value="no"
									<?php
									
if ($show_affiliate_link_alertpay == 'no')
										echo "checked";
									?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="AlertPay Affiliate link enable AlertPay Affiliate Link in marketplace in member area.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="clickbank_affiliate_link"
								<?php
								if ($prodcheck == 'Clickbank')
									echo 'style="display:block"';
								else
									echo 'style="display:none"';
								?>>
								<label>Show ClickBank Affiliate link:</label> <input
									class="inputbox" name="clickbank_affiliate_link" type="radio"
									value="yes"
									<?php
									
if ($show_affiliate_link_clickbank == 'yes')
										echo "checked";
									?> />Yes <input
									class="inputbox" name="clickbank_affiliate_link" type="radio"
									value="no"
									<?php
									
if ($show_affiliate_link_clickbank == 'no')
										echo "checked";
									?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="ClickBank Affiliate link enable ClickBank Affiliate Link in marketplace in member area.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset
						<?php
						if ($prodcheck == 'OTO')
							echo 'style="display:none"';
						else
							echo 'style="display:block"';
						?>
						id="marketplace_settings">
						<legend class="legend">MarketPlace Settings</legend>
						<small>Product configuration in marketplace and sidebar </small>
						<div class="fields">
							<div class="field">
								<label>Add to marketplace:</label> <input class="inputbox"
									name="marketplace" type="radio" value="yes"
									<?php echo $mark1 ?> />Yes <input class="inputbox"
									name="marketplace" type="radio" value="no" <?php echo $mark2 ?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Add To Marketplace will enable or disable the display of this product in the product marketplace. Only free and paid products can have the marketplace enabled. OTO's will not display in the marketplace.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label>Add to marketplace in member area:</label> <input
									class="inputbox" name="member_marketplace" type="radio"
									value="yes" <?php echo $member_marketplace_checked1 ?> />Yes <input
									class="inputbox" name="member_marketplace" type="radio"
									value="no" <?php echo $member_marketplace_checked2 ?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Add To Marketplace in Member Area will enable or disable the display of this product in the product marketplace. Only free and paid products can have the marketplace enabled. OTO's will not display in the marketplace.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label>Add in sidebar of member area:</label> <input
									class="inputbox" name="add_in_sidebar" type="radio" value="yes"
									<?php echo $add_in_sidebar_checked1 ?> />Yes <input
									class="inputbox" name="add_in_sidebar" type="radio" value="no"
									<?php echo $add_in_sidebar_checked2 ?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Add Product in New Product Listing in Member area"> <img
										src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend class="legend">OTO Settings</legend>
						<small>Attach OTO Product with <?php echo $product_name; ?></small>
						<div class="fields">
							<div class="field">
								<label>Add OTO To Product:</label> <input class="inputbox"
									name="otocheck" type="radio" value="yes"
									<?php echo $otocheck1 ?> />Yes <input class="inputbox"
									name="otocheck" type="radio" value="no"
									<?php echo $otocheck2 ?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Add OTO To Product will enable an OTO for this product.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label>One Time Offer Product:</label> <select
									name="one_time_offer" class="inputbox" size="1">
                                    <?php echo $oto_dd?>
                                </select>
								<div class="tool">
									<a href="" class="tooltip"
										title="One Time Offer Product is the OTO you want to assign to your product.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label>Add Downsell OTO:</label> <input class="inputbox"
									name="otodowncheck" type="radio" value="yes"
									<?php echo $otodowncheck1 ?> />Yes <input class="inputbox"
									name="otodowncheck" type="radio" value="no"
									<?php echo $otodowncheck2 ?> />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Add Downsell OTO will enable a downsell if the OTO isn't purchased.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label>Downsell OTO Product:</label> <select
									name="down_one_time_offer" class="inputbox" size="1">
                        <?php echo $down_oto_dd?>
                                </select>
								<div class="tool">
									<a href="" class="tooltip"
										title="Downsell OTO Product is the OTO you want to assign as a downsell">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset id="formsbuttons"
						<?php
						if ($prodcheck == 'OTO')
							echo 'style="display:none"';
						else
							echo 'style="display:block"';
						?>>
						<legend class="legend">Generate Button</legend>
						<small>Code for off-site buttons</small> <span class="tool"> <a
							href="" class="tooltip"
							title="These codes are for off-site buttons and links or for adding multiple   buttons on one page of your website. Use the drop-down tokens for your   main product buy button when creating or editing your products.">
								<img src="../images/toolTip.png" alt="help" align="top" />
						</a>
						</span>
						<div
							<?php
							if ($prodcheck == 'OTO')
								echo 'style="width:99%;padding: 5px;display:none"';
							else
								echo 'style="width:99%;padding: 5px;display:block"';
							?>>
							<span id="radio_clickbank"
								<?php
								if ($prodcheck == 'Clickbank')
									echo 'style="display:block"';
								else
									echo 'style="display:none"';
								?>> <input value="3" type="radio"
								name="gateway_select" id="gateway_select">ClickBank
							</span> <span id="radio_others"
								<?php
								if ($prodcheck != 'Clickbank')
									echo 'style="display:block"';
								else
									echo 'style="display:none"';
								?>> <input value="1" type="radio"
								name="gateway_select" id="gateway_select">PayPal <input
								value="2" type="radio" name="gateway_select" id="gateway_select">Alertpay
							</span>
						</div>
						<ul class="tabs" style="width: 100%;">
							<li><a href="#tab1">HTML</a></li>
							<li><a href="#tab2">Forum</a></li>
							<li><a href="#tab3">Link</a></li>
						</ul>
						<div class="tab_container" style="width: 92%; height: 120px">
							<div id="tab1" class="tab_content">
								<textarea cols="35" rows="5" name="button_html" id="button_html"><?php echo stripslashes( $button_html) ?></textarea>
							</div>
							<div id="tab2" class="tab_content">
								<textarea cols="35" rows="5" name="button_forum"
									id="button_forum"><?php echo stripslashes($button_forum) ?></textarea>
							</div>
							<div id="tab3" class="tab_content">
								<textarea cols="35" rows="5" name="button_link" id="button_link"><?php echo stripslashes($button_link) ?></textarea>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="leftpanel" style="width: 98%; padding: 0px">
					<fieldset>
						<legend class="legend">Additional Settings</legend>
						<div class="fields">
							<div class="field">
								<label>Custom PayPal Header Image:</label> <input
									name="pp_header" type="text" class="inputbox" id="pp_header"
									value="<?php echo $pp_header ?>"
									size="80" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Custom PayPal Header Image is a header image you want to appear at the top of your PayPal checkout pages. This needs to be a secure (https:) page.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label>Custom Return To Merchant Text:</label> <input
									name="pp_return" type="text" class="inputbox" id="pp_return"
									value="Click Here To Complete Your Purchase." size="60" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Custom Return To Merchant Text is where you can customize the Return To Merchant button that your customers will see after they finish their payment. ">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label style="width: 200px">Select Template:</label> <select
									name="selectdir" class="inputbox">
<?php echo $select_dir; ?>
                                </select>
								<div class="tool">
									<a href="" class="tooltip"
										title="Price is how much your product will cost if it is a paid product.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label style="width: 200px">Enable Coaching:</label> <input
									class="inputbox" name="coaching" type="radio" value="yes"
									<?php echo $coaching1 ?> id="coaching" />Yes <input
									class="inputbox" name="coaching" type="radio" value="no"
									<?php echo $coaching2 ?> id="coaching" />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Enable Coaching will allow members who have access to this product to gain access to a coaching module specific to the product.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label style="width: 200px">Timed Release Content:<span
									class="star"> </span></label> <select name="tcontent"
									class="inputbox" id="tcontent">
									<option value=0>None</option>
<?php echo $tcontent?>
                                </select>
								<div class="tool">
									<a href="" class="tooltip"
										title="Timed Release Content is where you can choose to display one of the timed release content campaigns instead of the normal download page. If you choose one of the content campaigns from this dropdown then it will replace the standard download page when the member clicks into the dowload area.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label style="width: 200px">Quantity Limit:</label> <input
									class="inputbox" name="qlimit" type="radio" value="yes"
									<?php echo $qlimit1; ?> id="qlimit1" />Yes <input
									class="inputbox" name="qlimit" type="radio" value="no"
									<?php echo $qlimit2; ?> id="qlimit2" />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Quantity Limit On will enable you to place a restriction on how many copies of this product will be sold.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="quantitytext"
								<?
								if ($qcheck == 'yes')
									echo 'style="display:block" ';
								else
									echo 'style="display:none" ';
								?>>
								<label style="width: 200px">Available Quantity:</label> <input
									name="quantity_cap" type="text" class="inputbox"
									id="quantity_cap" value="<?php echo $quantity_cap ?>" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Available Quantity is the number of copies of this product you want to sell.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label style="width: 200px">Squeeze Page:</label> <input
									class="inputbox" name="squeeze_check" type="radio" value="yes"
									<?php echo $squeeze_check1 ?> id="squeeze_check1" />Yes <input
									class="inputbox" name="squeeze_check" type="radio" value="no"
									<?php echo $squeeze_check2 ?> id="squeeze_check2" />No
								<div class="tool">
									<a href="" class="tooltip"
										title="Squeeze Page On will show a squeeze page instead of the normal sales page for this product.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="squeezenametext"
								<?
								if ($squeeze_check == 'yes')
									echo 'style="display:block" ';
								else
									echo 'style="display:none" ';
								?>>
								<label style="width: 200px">Squeeze Page:</label> <select
									name="squeezename" class="inputbox" id="squeezename">
									<option value=0>Select</option>
<?php echo $squeezename?>
                                </select>
								<div class="tool">
									<a href="" class="tooltip"
										title="Squeeze Page allows you to choose what squeeze page you wish to display">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field">
								<label style="width: 200px">Third Party Autoresponder</label> <select
									name="psponder" class="inputbox" id="psponder">
									<option value=0>Select</option>
<?php echo $psponder?>
                                </select>
								<div class="tool">
									<a href="" class="tooltip"
										title="Autoresponder Form allows you to choose an autoresponder to add your customer to when they purchase or gain access to this product. This will work both outside and inside the members area.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset id="field_partners_settings">
						<legend class="legend">Product Partner Settings</legend>
						<div class="fields">
							<div class="field">
								<label style="width: 250px">Enable Product Partner:</label> <input
									class="inputbox" type="checkbox" name="check_product_partner"
									id="check_product_partner" value="1"
									<?php
									
if ($enable_product_partner == 1)
										echo "checked";
									?> />
								<div class="tool">
									<a href="" class="tooltip"
										title="Enable Product partner Yes or No."> <img
										src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div id="partners_settings"
								<?php
								
if ($enable_product_partner == 1)
									echo 'style="display: block"';
								else
									echo 'style="display: none"';
								?>>
								<div class="field">
									<label>Product Partner PayPal Email:</label> <input type="text"
										name="porduct_partner_paypal_email"
										id="porduct_partner_paypal_email" size="40"
										class="inputbox email"
										value="<?php echo $product_partner_paypal_email ?>">
									<div class="tool">
										<a href="" class="tooltip"
											title="Product partner paypal email address."> <img
											src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label>Product Partner AlertPay Email:</label> <input
										type="text" name="porduct_partner_alertpay_email"
										id="porduct_partner_alertpay_email" size="40"
										class="inputbox email"
										value="<?php echo $product_partner_alertpay_email; ?>">
									<div class="tool">
										<a href="" class="tooltip"
											title="Product partner AlertPay email address."> <img
											src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label>Product Partner AlertPay IPN:</label> <input type="text"
										name="porduct_partner_alertpay_ipn"
										id="porduct_partner_alertpay_ipn" size="40" class="inputbox"
										value="<?php echo $ap_partner_ipn_security_code ?>">
									<div class="tool">
										<a href="" class="tooltip"
											title="Product partner AlertPay IPN."> <img
											src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label>Product Partner Commision%:</label> <input type="text"
										name="porduct_partner_commision"
										id="porduct_partner_commision" size="10" maxlength="5"
										class="inputbox number commistion"
										value="<?php echo $partner_commission ?>"> <br /> <small>Commision must be less then <?php echo get_total_commission($prefix, $db); ?> %</small>
									<div class="tool">
										<a href="" class="tooltip" title="Product partner commision.">
											<img src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset id="product_prices"
						<?php
						if ($prodcheck == 'free')
							echo 'style="display:none" ';
						else
							echo 'style="display:block" ';
						?>>
						<legend class="legend">Product Price Settings</legend>
						<div class="fields">
							<div class="field">
								<label style="width: 200px">Subscription Active:</label> <input
									class="inputbox" type="checkbox" name="subscription_active"
									id="subscription_active" value="1" <?php echo $sub_active ?> />
								<div class="tool">
									<a href="" class="tooltip"
										title="Subscription Active will change your product payment from a one time payment to a recurring billing subscription.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="pricefield"
								<?php
								if ($prodcheck == 'free' || $subscription_active == '1')
									echo 'style="display:none" ';
								else
									echo 'style="display:block" ';
								?>>
								<label>Price:<span class="star">*</span></label> <input
									type="text" name="price" id="product_price"
									value="<?php echo $price ?>"
									class="inputbox <?
									
if ($prodcheck == 'free')
										echo 'required" ';
									?> " />
								<div class="tool">
									<a href="" class="tooltip"
										title="Price is how much your product will cost if it is a paid product.">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div id="subscription_active_billing"
								<?php
								if ($subscription_active == '1')
									echo 'style="display:block" ';
								else
									echo 'style="display:none" ';
								?>>
								<div class="field">
									<label style="background-color: #d4d4d4">Regular billing cycle:</label>
									<label>Billing Period:</label> <input class="inputbox"
										type="text" name="period3_value"
										value="<?php echo $period3_value ?>" size="3" maxlength="3" />
									<select class="inputbox" name="period3_interval">
										<option value='D' <?php echo $p3_interval_d ?>>Day(s)</option>
										<option value='W' <?php echo $p3_interval_w ?>>Weeks(s)</option>
										<option value='M' <?php echo $p3_interval_m ?>>Month(s)</option>
										<option value='Y' <?php echo $p3_interval_y ?>>Year(s)</option>
									</select>
									<div class="tool">
										<a href="" class="tooltip"
											title="Billing Period is the number of days you want to allow in each billing period. This is fully customizable and can be set to any number of days, weeks, months, or years.">
											<img src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label>Billing Amount: <span class="star">*</span></label> <input
										class="inputbox" type="text" id="amount3" name="amount3"
										value="<?php echo $amount3 ?>" size="9" maxlength="9" />
									<div class="tool">
										<a href="" class="tooltip"
											title="Billing Amount is the amount you want to charge your members each billing period.">
											<img src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label>Subscription Length:</label> <input class="inputbox"
										type="text" name="srt" value="<?php echo $srt ?>" size="9"
										maxlength="9" /><br /> <small>(This is the number of times the
										member will be charged. If the subscription never ends then
										leave at 0.)</small>
									<div class="tool">
										<a href="" class="tooltip"
											title="Subscription Length is the number of times you want the subscription to bill the customer. For open ended subscriptions that do not end leave set to 0.">
											<img src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label style="background-color: #d4d4d4">Trial Period 1</label>
									<small>Skip this section if you do not want to offer trial
										periods with your subscriptions.</small><br /> <label
										style="width: 200px;">Trial Period 1 Active</label> <input
										class="inputbox" type="checkbox" name="period1_active"
										value="1" <?php echo $p1_active ?> />
									<div class="tool">
										<a href="" class="tooltip"
											title="Subscription Active will change your product payment from a one time payment to a recurring billing subscription.">
											<img src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label>Trial 1 Subscription Period:</label> <input
										class="inputbox" type="text" name="period1_value"
										value="<?php echo $period1_value ?>" size="3" maxlength="3" />
									<select class="inputbox" name="period1_interval">
										<option value='D' <?php echo $p1_interval_d; ?>>Day(s)</option>
										<option value='W' <?php echo $p1_interval_w; ?>>Weeks(s)</option>
										<option value='M' <?php echo $p1_interval_m; ?>>Month(s)</option>
										<option value='Y' <?php echo $p1_interval_y; ?>>Year(s)</option>
									</select>
									<div class="tool">
										<a href="" class="tooltip"
											title="Trial 1 Subscription Period is the number of days you want to allow in each billing period. This is fully customizable and can be set to any number of days, weeks, months, or years.">
											<img src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
								<div class="field">
									<label>Trial 1 Subscription Amount:</label> <input
										class="inputbox" type="text" name="amount1"
										value="<?php echo $amount1 ?> " size="9" maxlength="9" />
									<div class="tool">
										<a href="" class="tooltip"
											title="Trial 1 Subscription Amount is the amount you want to charge your members for the trial period.">
											<img src="../images/toolTip.png" alt="help" align="top" />
										</a>
									</div>
								</div>
							</div>
							<div class="field" id="priceaffiliate"
								<?
								if ($prodcheck == 'free')
									echo 'style="display:none" ';
								else
									echo 'style="display:block" ';
								?>>
								<label style="background-color: #d4d4d4">Commission</label> <label>Affiliate
									Commission %:</label> <input name="commission" type="text"
									class="inputbox number" id="commission"
									value="<?php echo $commission; ?>" maxlength="3" />
								<div class="tool">
									<a href="" class="tooltip"
										title="Affiliate Commission % is the percent of sales you wish affiliates to get when someone buys through their affiliate link.This is a percentage of sales, not the product price">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
							<div class="field" id="pricejv"
								<?
								if ($prodcheck == 'free')
									echo 'style="display:none" ';
								else
									echo 'style="display:block" ';
								?>>
								<label>JV Partner Commission %:</label> <input
									name="jvcommission" type="text" class="inputbox number"
									id="jvcommission" value="<?php echo $jvcommission; ?>"
									maxlength="3" />
								<div class="tool">
									<a href="" class="tooltip"
										title="JV Partner Commission % is the percent of sales you wish affiliates to get when someone buys through their affiliate link.This is a percentage of sales, not the product price">
										<img src="../images/toolTip.png" alt="help" align="top" />
									</a>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset
						<?
						if ($qcheck == 'yes')
							echo 'style="display:block" ';
						else
							echo 'style="display:none" ';
						?>
						id="meetquantity">
						<legend class="legend">Sold Out Page:</legend>
						<div class="fields">
							<label>Sold Out Page:</label>
							<div class="tool">
								<a href="" class="tooltip"
									title="Sold Out Page will replace the sales page for this product automatically once the quantity limit has been reached.">
									<img src="../images/toolTip.png" alt="help" align="top" />
								</a>
							</div>
							<textarea name="quantity_met_page" cols="100" rows="8"
								class="inputbox" id="quantity_met_page"><?php echo $quantity_met_page ?></textarea>
							<script>
                               var oEdit2 = new InnovaEditor("oEdit2");
							oEdit2.width = 700;
							oEdit2.height = 450;
							
							
							oEdit2.groups = [
							["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
							["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
							["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
							["grpEdit4", "", ["CharsDialog", "Line", "BRK","CustomTag","HTML5Video"]],
							["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
							];
							
							if (oEdit2.fileBrowser != "") {
        oEdit2.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,650,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    } else { /* If file browser not used, reduce the HTML5 Video dialog height */
        oEdit2.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,330,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    }
							/*Enable Custom File Browser */
							
							oEdit2.fileBrowser = "/admin/Editor/assetmanager/asset.php";
							/*Define "CustomTag" dropdown */
							oEdit2.arrCustomTag=    [["Product Name","{\{product_name\}}"],
							["PayPal Button","{\{paypal_button}\}"],
							["AlertPay Button","{\{alertpay_button\}}"],
							["ClickBank Button","{\{clickbank_button_id\}}"],
							["Price","{\{actual_price\}}"],
							["Referred By","{\{referred_by\}}"],
							["Quantity","{\{quantity_cap\}}"],
							["Final Price","{\{discount_price\}}"],
							["Current Members Click Here to Purchase","{\{member_check\}}"],
							["No Thanks Message for OTO only","{\{no_thanks\}}"]
							];//Define custom tag selection//Define custom tag selection
							/*Apply stylesheet for the editing content*/
							oEdit2.css = "/admin/Editor/styles/default.css";
							/*Render the editor*/
                                oEdit2.REPLACE("quantity_met_page");
                            </script>
						</div>
					</fieldset>
					<fieldset>
						<legend class="legend">Sales Page:</legend>
						<div class="fields" id="salespagecontent">
							<label>Sales Page:</label>
							<div class="tool">
								<a href="" class="tooltip"
									title="Sales Page is where you put the html for your product sales page. This page uses the default header and footer. Do not include header and footer html when designing the sales page as they will be added automatically by the script. Enter only the text that goes between the body tags.">
									<img src="../images/toolTip.png" alt="help" align="top" />
								</a>
							</div>
							<textarea name="index_page" cols="100" rows="8" class="inputbox"
								id="index_page"><?php echo $index_page ?></textarea>
							<script>
							var oEdit1 = new InnovaEditor("oEdit1");
							oEdit1.width = 700;
							oEdit1.height = 450;
							
							
							oEdit1.groups = [
							["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
							["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
							["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
							["grpEdit4", "", ["CharsDialog", "Line", "BRK","CustomTag","HTML5Video"]],
							["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
							];
							
							if (oEdit1.fileBrowser != "") {
        oEdit1.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,650,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    } else { /* If file browser not used, reduce the HTML5 Video dialog height */
        oEdit1.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,330,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    }
							/*Enable Custom File Browser */
							
							oEdit1.fileBrowser = "/admin/Editor/assetmanager/asset.php";
						 	/*Define "CustomTag" dropdown */
							oEdit1.arrCustomTag=    [["Product Name","{\{product_name\}}"],
							["PayPal Button","{\{paypal_button}\}"],
							["AlertPay Button","{\{alertpay_button\}}"],
							["ClickBank Button","{\{clickbank_button_id\}}"],
							["Price","{\{actual_price\}}"],
							["Referred By","{\{referred_by\}}"],
							["Quantity","{\{quantity_cap\}}"],
							["Final Price","{\{discount_price\}}"],
							["Current Members Click Here to Purchase","{\{member_check\}}"],
							["No Thanks Message for OTO only","{\{no_thanks\}}"]
							];//Define custom tag selection//Define custom tag selection
							/*Apply stylesheet for the editing content*/
							oEdit1.css = "/admin/Editor/styles/default.css";
							/*Render the editor*/
							oEdit1.REPLACE("index_page");
                            </script>
						</div>
					</fieldset>
					<fieldset>
						<legend class="legend">Download Page:</legend>
						<small>Note: This page uses your default member template. Add
							content or content html only.</small>
						<div class="fields">
							<label>Download Page:</label>
							<div class="tool">
								<a href="" class="tooltip"
									title="Price is how much your product will cost if it is a paid product.">
									<img src="../images/toolTip.png" alt="help" align="top" />
								</a>
							</div>
							<textarea name="download_form" cols="100" rows="8"
								class="inputbox" id="download_form"><?php echo $download_form ?></textarea>
							<script>
                                var oEdit3 = new InnovaEditor("oEdit3");
							oEdit3.width = 700;
							oEdit3.height = 450;
							
							
							oEdit3.groups = [
							["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
							["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
							["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
							["grpEdit4", "", ["CharsDialog", "Line", "BRK","CustomTag","HTML5Video"]], 
							["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
							];
							
							if (oEdit3.fileBrowser != "") {
        oEdit3.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,650,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    } else { /* If file browser not used, reduce the HTML5 Video dialog height */
        oEdit3.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,330,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    }
							/*Enable Custom File Browser */
							
							oEdit3.fileBrowser = "/admin/Editor/assetmanager/asset.php";
							/*Define "CustomTag" dropdown */
							oEdit3.arrCustomTag=    [["Product Name","{\{product_name\}}"],
							["PayPal Button","{\{paypal_button}\}"],
							["AlertPay Button","{\{alertpay_button\}}"],
							["ClickBank Button","{\{clickbank_button_id\}}"],
							["Price","{\{actual_price\}}"],
							["Referred By","{\{referred_by\}}"],
							["Quantity","{\{quantity_cap\}}"],
							["Final Price","{\{discount_price\}}"],
							["Current Members Click Here to Purchase","{\{member_check\}}"],
							["No Thanks Message for OTO only","{\{no_thanks\}}"]
							];//Define custom tag selection//Define custom tag selection
							/*Apply stylesheet for the editing content*/
							oEdit3.css = "/admin/Editor/styles/default.css";
							/*Render the editor*/
                                oEdit3.REPLACE("download_form");
                            </script>
						</div>
					</fieldset>
				</div>
				<div class="fields" style="padding: 5px 7px;">
					<input type="submit" name="submit" value="Save Product"
						class="inputbox">
				</div>
			</form>
		</div>
	</div>
	<div class="content-wrap-bottom"></div>
</div>
<?php
include_once ("footer.php");
function get_total_commission($prefix, $db) {
	$sql = "select (partner_commission + second_partner_commission ) as total from " . $prefix . "site_settings where id='1'";
	$row = $db->get_a_line ( $sql );
	return ( int ) 100 - $row ['total'];
}
?>