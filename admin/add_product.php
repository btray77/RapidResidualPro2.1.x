<?php 
include ("session.php");
include ("header.php");
include_once('class-template.php');
function encodeHTML($sHTML) {
    $sHTML = ereg_replace("&", "&amp;", $sHTML);
    $sHTML = ereg_replace("<", "&lt;", $sHTML);
    $sHTML = ereg_replace(">", "&gt;", $sHTML);
    return $sHTML;
}
if (isset($_POST['submit'])) {
    $get_selected_oto = $otolist;
    $oto_dd = $db->get_oto($get_selected_oto);
    $product_name = $db->quote($_POST["product_name"]);
    $pshort = preg_replace('/([^a-z0-9])+/i', '_', $_POST["pshort"]);
    $pshort = preg_replace('/\_$/', '', $pshort);
    $pshort = $db->quote($_POST["pshort"]);
    $index_page = $db->quote($_POST["index_page"]);
    $download_form = $db->quote($_POST["download_form"]);
    $price = $db->quote($_POST["price"]);
    $image = $db->quote($_POST["image"]);
    $commission = $db->quote($_POST["commission"]);
    $jvcommission = $db->quote($_POST["jvcommission"]);
    $prodtype = $db->quote($_POST["prodtype"]);
    $prod_description = $db->quote($_POST["prod_description"]);
    $keywords = $db->quote ( $_POST ["keywords"] );
    $marketplace = $db->quote($_POST["marketplace"]);
    $affiliate_link = $db->quote($_POST["affiliate_link"]);
    $otocheck = $db->quote($_POST["otocheck"]);
    $one_time_offer = $db->quote($_POST['one_time_offer']);
    $otodowncheck = $db->quote($_POST['otodowncheck']);
    $down_one_time_offer = $db->quote($_POST['down_one_time_offer']);
    $otolist = $db->quote($_POST["otolist"]);
    $psponder = $db->quote($_POST["psponder"]);
    $no_text = $db->quote($_POST["no_text"]);
    $qlimit = $db->quote($_POST["qlimit"]);
    $quantity_cap = $db->quote($_POST["quantity_cap"]);
    $quantity_met_page = $db->quote($_POST["quantity_met_page"]);
    $subscription_active = $db->quote($_POST["subscription_active"]);
    $period1_active = $db->quote($_POST["period1_active"]);
    $period1_value = $db->quote($_POST["period1_value"]);
    $period1_interval = $db->quote($_POST["period1_interval"]);
    $srt = $db->quote($_POST["srt"]);
    $amount1 = $db->quote($_POST["amount1"]);
    $period2_active = $db->quote($_POST["period2_active"]);
    $period2_value = $db->quote($_POST["period2_value"]);
    $period2_interval = $db->quote($_POST["period2_interval"]);
    $amount2 = $db->quote($_POST["amount2"]);
    $period3_value = $db->quote($_POST["period3_value"]);
    $period3_interval = $db->quote($_POST["period3_interval"]);
    $amount3 = $db->quote($_POST["amount3"]);
    $squeezename = $db->quote($_POST["squeezename"]);
    $squeeze_check = $db->quote($_POST["squeeze_check"]);
    $pp_header = $db->quote($_POST["pp_header"]);
    $pp_return = $db->quote($_POST["pp_return"]);
    $tcontent = $db->quote($_POST["tcontent"]);
    $coaching = $db->quote($_POST["coaching"]);
    $template = $db->quote($_POST['selectdir']);
    $click_bank_url = $db->quote($_POST['click_bank_url']);
    $click_bank_security_code = $db->quote($_POST['click_bank_security_code']);
    $add_in_sidebar = $db->quote($_POST['add_in_sidebar']);
    $member_marketplace = $db->quote($_POST['member_marketplace']);
    $button_html = $db->quote($_POST['button_html']);
    $button_forum = $db->quote($_POST['button_forum']);
    $button_link = $db->quote($_POST['button_link']);
    $show_affiliate_link_paypal = $db->quote($_POST['paypal_affiliate_link']);
    $show_affiliate_link_alertpay = $db->quote($_POST['alertpay_affiliate_link']);
    $show_affiliate_link_clickbank = $db->quote($_POST['clickbank_affiliate_link']);
    $check_product_partner = $db->quote($_POST['check_product_partner']);
    $porduct_partner_paypal_email = $db->quote($_POST['porduct_partner_paypal_email']);
    $porduct_partner_alertpay_email = $db->quote($_POST['porduct_partner_alertpay_email']);
    $porduct_partner_alertpay_ipn = $db->quote($_POST['porduct_partner_alertpay_ipn']);
    $porduct_partner_commision = $db->quote($_POST['porduct_partner_commision']);
    // Make sure short name is uniquie
    $q = "select count(*) as cnt from " . $prefix . "products where pshort={$pshort} && id !='$prodid'";
    $r = $db->get_a_line($q);
    $count = $r[cnt];
    if ($count > 0) {
        // short name already exists
        header("Location: add_product.php?err=1");
        exit();
    }
    // Make sure OTO product is not set to add to marketplace
    if ($prodtype == "'OTO'" && $marketplace == "'yes'") {
        header("Location: add_product.php?err=3");
        exit;
    }
    if ($prodtype == "'Clickbank'" && $click_bank_url == "") {
        header("Location: add_product.php?err=6");
        exit;
    }
    if (is_dir('../images/payment_buttons')) {
        
    } else {
        mkdir("../images/payment_buttons", 777);
    }
    $imagepath = '../images/payment_buttons/';
    // Product File uploading section starts
    if ($_FILES["imageurl"]['name']) {
        if ((($_FILES["imageurl"]["type"] == "image/gif") ||
                ($_FILES["imageurl"]["type"] == "image/jpeg") ||
                ($_FILES["imageurl"]["type"] == "image/pjpeg") ||
                ($_FILES["imageurl"]["type"] == "image/png") ||
                ($_FILES["imageurl"]["type"] == "image/x-png"))
        ) {
            if ($_FILES["imageurl"]["error"] > 0) {
                $error_status = 'yes';
                header("Location: add_product.php?err=4");
                exit();
            } else {
                $imageurl = $imagepath . time() . $_FILES["imageurl"]["name"];
                move_uploaded_file($_FILES["imageurl"]["tmp_name"], $imageurl);
            }
        } else {
            header("Location: add_product.php?err=4");
            exit();
        }
    }
    // Product File uploading section ends
    // PayPal File uploading section starts
    if ($_FILES["paypal_image"]['name']) {
        if ((($_FILES["paypal_image"]["type"] == "image/gif") ||
                ($_FILES["paypal_image"]["type"] == "image/jpeg") ||
                ($_FILES["paypal_image"]["type"] == "image/pjpeg") ||
                ($_FILES["paypal_image"]["type"] == "image/png") ||
                ($_FILES["paypal_image"]["type"] == "image/x-png"))
        ) {
            if ($_FILES["paypal_image"]["error"] > 0) {
                $error_status = 'yes';
                header("Location: add_product.php?err=4");
                exit();
            } else {
                $file_path_paypal = $imagepath . time() . $_FILES["paypal_image"]["name"];
                move_uploaded_file($_FILES["paypal_image"]["tmp_name"], $file_path_paypal);
            }
        } else {
            header("Location: add_product.php?err=4");
            exit();
        }
    }
    // PayPal File uploading section ends
    // AlertPay File uploading section starts
    if ($_FILES["alertpay_image"]['name']) {
        if ((($_FILES["alertpay_image"]["type"] == "image/gif") ||
                ($_FILES["alertpay_image"]["type"] == "image/jpeg") ||
                ($_FILES["alertpay_image"]["type"] == "image/pjpeg") ||
                ($_FILES["alertpay_image"]["type"] == "image/png") ||
                ($_FILES["paypal_image"]["type"] == "image/x-png"))
        ) {
            if ($_FILES["alertpay_image"]["error"] > 0) {
                $error_status = 'yes';
                header("Location: add_product.php?err=5");
                exit();
            } else {
                $file_path_alertpay = $imagepath . time() . $_FILES["alertpay_image"]["name"];
                move_uploaded_file($_FILES["alertpay_image"]["tmp_name"], $file_path_alertpay);
            }
        } else {
            header("Location: add_product.php?err=5");
            exit();
        }
    }
    // AlertPay File uploading section ends
    // Click Bank File uploading section starts
    if ($_FILES["clickbank_image"]['name']) {
        if ((($_FILES["clickbank_image"]["type"] == "image/gif") ||
                ($_FILES["clickbank_image"]["type"] == "image/jpeg") ||
                ($_FILES["clickbank_image"]["type"] == "image/pjpeg") ||
                ($_FILES["clickbank_image"]["type"] == "image/png") ||
                ($_FILES["clickbank_image"]["type"] == "image/x-png"))
        ) {
            if ($_FILES["clickbank_image"]["error"] > 0) {
                $error_status = 'yes';
                header("Location: add_product.php?err=7");
                exit();
            } else {
                $file_path_clickbank = $imagepath . time() . $_FILES["clickbank_image"]["name"];
                move_uploaded_file($_FILES["clickbank_image"]["tmp_name"], $file_path_clickbank);
            }
        } else {
            header("Location: add_product.php?err=7");
            exit();
        }
    }
    // Click Bank File uploading section ends
    // Write to database
    $set = "product_name        = {$product_name},";
    $set .= "pshort            = {$pshort},";
    $set .= "index_page            = {$index_page},";
    $set .= "download_form        = {$download_form},";
    $set .= "image            = '" . mysql_real_escape_string(str_replace("..", "", $file_path_paypal)) . "',";
    $set .= "alertpay_image        = '" . mysql_real_escape_string(str_replace("..", "", $file_path_alertpay)) . "',";
    $set .= "clickbank_image            = '" . mysql_real_escape_string(str_replace("..", "", $file_path_clickbank)) . "',";
    $set .= "commission            = {$commission},";
    $set .= "jvcommission        = {$jvcommission},";
    $set .= "price              = {$price},";
    $set .= "imageurl                   = '" . mysql_real_escape_string(str_replace("..", "", $imageurl)) . "',";
    $set .= "prod_description           = {$prod_description},";
    
    $set .= "keywords  		= {$keywords},";
    $set .= "marketplace          = {$marketplace},";
    $set .= "affiliate_link             = {$affiliate_link},";
    $set .= "otocheck              = {$otocheck},";
    $set .= "one_time_offer             = {$one_time_offer},";
    $set .= "otodowncheck          = {$otodowncheck},";
    $set .= "down_one_time_offer        = {$down_one_time_offer},";
    $set .= "psponder              = {$psponder},";
    $set .= "no_text              = {$no_text},";
    $set .= "quantity_cap        = {$quantity_cap},";
    $set .= "qlimit                  = {$qlimit},";
    $set .= "quantity_met_page                  = {$quantity_met_page},";
    $set .= "subscription_active                = {$subscription_active},";
    $set .= "period1_active                     = {$period1_active},";
    $set .= "period1_value                      = {$period1_value},";
    $set .= "period1_interval                   = {$period1_interval},";
    $set .= "srt                  = {$srt},";
    $set .= "amount1                  = {$amount1},";
    $set .= "period2_active                     = {$period2_active},";
    $set .= "period2_value                      = {$period2_value},";
    $set .= "period2_interval                   = {$period2_interval},";
    $set .= "amount2                  = {$amount2},";
    $set .= "period3_value                      = {$period3_value},";
    $set .= "period3_interval                   = {$period3_interval},";
    $set .= "amount3                  = {$amount3},";
    $set .= "squeezename              = {$squeezename},";
    $set .= "squeeze_check                      = {$squeeze_check},";
    $set .= "pp_header                  = {$pp_header}, ";
    $set .= "pp_return                  = {$pp_return},";
    $set .= "tcontent                  = {$tcontent},";
    $set .= "coaching                  = {$coaching},";
    $set .= "template                           = {$template},";
    $set .= "prodtype                = {$prodtype},";
    $set .= "click_bank_security_code        = {$click_bank_security_code},";
    $set .= "click_bank_url            = {$click_bank_url},";
    $set .= "add_in_sidebar                     = {$add_in_sidebar},";
    $set .= "member_marketplace                 = {$member_marketplace},";
    $set .= "button_html                        = {$button_html},";
    $set .= "button_forum                       = {$button_forum},";
    $set .= "button_link                        = {$button_link},";
    $set .= "show_affiliate_link_paypal        = {$show_affiliate_link_paypal},";
    $set .= "show_affiliate_link_alertpay    = {$show_affiliate_link_alertpay},";
    $set .= "show_affiliate_link_clickbank    = {$show_affiliate_link_clickbank},";
    $set .= "enable_product_partner        = {$check_product_partner},";
    $set .= "product_partner_paypal_email    = {$porduct_partner_paypal_email},";
    $set .= "product_partner_alertpay_email    = {$porduct_partner_alertpay_email},";
    $set .= "ap_partner_ipn_security_code    = {$porduct_partner_alertpay_ipn},";
    $set .= "partner_commission                 = {$porduct_partner_commision}";
    $sql = "select count(id) as total from " . $prefix . "products where id = $prodid";
    $row_total = mysql_fetch_array(mysql_query($sql));
    if ($row_total['total'] > 0)
        mysql_query("UPDATE " . $prefix . "products set $set where id = $prodid") or die(mysql_error());
    else
        mysql_query("INSERT " . $prefix . "products set $set") or die(mysql_error());
    header("Location: paid_products.php?msg=paid");
}
else {
    $sql = "select max(id)+1 as id from " . $prefix . "products;";
    $rs_product = mysql_query($sql);
    $row_product = mysql_fetch_assoc($rs_product);
    $product_id = $row_product['id'];
}
$obj_template = new Template_information("../templates/");
$dir_name = $obj_template->ReadFolderDirectory();
function select_Dir($dir_name) {
    //$str.='<option value="0" >Select Template</option>';
    $str.='<option value="none">none</option>';
    $str.='<option value="default">default</option>';
    foreach ($dir_name as $name) {
        $str .= '<option value="' . $name . '" >' . $name . '</option>';
    }
    return $str;
}
$select_dir = select_Dir($dir_name);
// Template function ends here
$get_selected_oto = $one_time_offer;
$oto_dd = $db->get_oto($get_selected_oto);
$get_selected_down_oto = $down_one_time_offer;
$down_oto_dd = $db->get_oto($get_selected_down_oto);
$q = "select * from " . $prefix . "responders order by rspname2";
$r = $db->get_rsltset($q);
for ($i = 0; $i < count($r); $i++) {
    @extract($r[$i]);
    $pid = $rspname2;
    if ($pid == $showpaid) {
        $psponder.="<option value='$pid' Selected>$pid</option>";
    } elseif ($pid != $showpaid) {
        $psponder.="<option value='$pid'>$pid</option>";
    }
}
$q = "select * from " . $prefix . "squeeze_pages order by name";
$r = $db->get_rsltset($q);
for ($i = 0; $i < count($r); $i++) {
    @extract($r[$i]);
    $pid = $name;
    if ($pid == $showsqueeze) {
        $squeezename.="<option value='$pid' Selected>$pid</option>";
    } elseif ($pid != $showsqueeze) {
        $squeezename.="<option value='$pid'>$pid</option>";
    }
}
$q = "select * from " . $prefix . "tccampaign order by shortname";
$r = $db->get_rsltset($q);
for ($i = 0; $i < count($r); $i++) {
    @extract($r[$i]);
    $pid = $shortname;
    if ($pid == $showtimed) {
        $tcontent.="<option value='$pid' Selected>$pid</option>";
    } elseif ($pid != $showtimed) {
        $tcontent.="<option value='$pid'>$pid</option>";
    }
}
if ($err == '1') {
    $warning = "<div class='error'><img src='/images/crose.png' border='0'> Product short name already in use.</div>";
}
if ($err == '2') {
    $warning = "<div class='error'><img src='/images/crose.png' border='0'>OTO products can not be set as the home page product.</div>";
}
if ($err == '3') {
    $warning = "<div class='error'><img src='/images/crose.png' border='0'>OTO products can not be shown on the marketplace.</div>";
}
if ($err == '4') {
    $warning = "<div class='error'><img src='/images/crose.png' border='0'>PayPal image uploading failed.</div>";
}
if ($err == '5') {
    $warning = "<div class='error'><img src='/images/crose.png' border='0'>Alert Pay image uploading failed.</div>";
}
if ($err == '6') {
    $warning = "<div class='error'><img src='/images/crose.png' border='0'>Please enter Click Bank user id</div>";
}
if ($err == '7') {
    $warning = "<div class='error'><img src='/images/crose.png' border='0'>ClickBank image uploading failed.</div>";
}
?>
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
    
    /*********************************************/
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
        
/*********************************************/
$("#quantity_cap").click(function(){
    if($("#quantity_cap").val() > 0)
    $("#meetquantity").fadeIn(500);
else
$("#meetquantity").fadeOut(500);
});
/********************************************/
/*********************************************/
$("#quantity_cap").click(function(){
if($("#quantity_cap").val() > 0)
$("#meetquantity").fadeIn(500);
else
$("#meetquantity").fadeOut(500);
});
/********************************************/
$("#prodtype").change(function(){
$("#ototext").fadeOut(500);
$("#clickbanktext").fadeOut(500);
$("#paypaltext").fadeIn(500);
$("#alertpaytext").fadeIn(500);
$("#pricefield").fadeIn(500);
$("#priceaffiliate").fadeIn(500);
$("#pricejv").fadeIn(500);
$("#marketplace_settings").fadeIn(500);
$("#product_prices").fadeIn(500);
$("#affiliate_program").fadeIn(500);
$("#clickbank_affiliate_link").fadeOut(500);
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
}
else if($("#prodtype").val() =="Clickbank")
{
$("#paypaltext").fadeOut(500);
$("#alertpaytext").fadeOut(500);
$("#ototext").fadeOut(500);
$("#alertpay_affiliate_link").fadeOut(500);
$("#paypal_affiliate_link").fadeOut(500);
$("#clickbank_user_id").fadeIn(500);
$("#clickbank_security_code").fadeIn(500);
$("#clickbank_button").fadeIn(500);
$("#field_partners_settings").fadeOut(500);
                                
                                
                
                
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
/*********************************************************************************/
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
url: 'add-product.php',
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
<?php echo $warning ?>
<div class="success" id="loading" style="display:none"><img src='/images/tick.png' border='0' align="absmiddle"> Product is automatically saved. </div>
<div class="content-wrap">
    <div class="content-wrap-top"></div>
    <div class="content-wrap-inner">
        <strong>Edit Product</strong>
        <div class="buttons"><a    href="paid_products.php" >Go Back</a></div>
        <div class="formborder">
            <form name="editproduct" id="editproduct" action="add_product.php" method="post" enctype="multipart/form-data">
                <span id="hidden_field">
                    <input type="hidden" name="prodid" value="<?php echo $product_id ?>">
                    <input type="hidden" name="option" value="add">
                </span>
                <div class="leftpanel main">
                    <div class="fields">
                        <div class="field">
                            <label>Product Name: <span class="star">*</span></label>
                            <input type="text" name="product_name" size="40" value="" class="inputbox required" />
                            <div class="tool">    
                                <a href="" class="tooltip" title="Product Name is the name you want to give your product. 
                                   This can be used in the sales page and it will be used by PayPal during checkout. 
                                   This will also show in the members area when members view their purchased products list.">
                                    <img src="/images/toolTip.png" alt="help"/>
                                </a>
                            </div>
                        </div>
                        <div class="field">
                            <label>Product Short Name: <span class="star">*</span></label>
                            <input name="pshort" type="text" size="40" class="inputbox required" id="pshort" value="" />
                            <div class="tool">    
                                <a href="" class="tooltip" title="Product Short Name is the nickname for your product. 
                                   This name will be used in various places in the script to represent this product. 
                                   There should be no spaces in the product short name.">
                                    <img src="/images/toolTip.png" alt="help"/>
                                </a>
                            </div>
                        </div>
                        <div class="field">
                            <label>Product Description:<span class="star">*</span></label>
                            <input class="inputbox" readonly type="text" name="countdown" size="3" value="<?php echo 500 - strlen($prod_description); ?>"> characters left.<br />
                            <textarea name="prod_description" class="inputbox required" onKeyDown="limitText(this.form.prod_description,this.form.countdown,500);" 
                                      onKeyUp="limitText(this.form.prod_description,this.form.countdown,500);" cols="60" rows="8" id="prod_description"><?php echo $prod_description ?></textarea>
                            <div class="tool">
                                <a href="" class="tooltip" title="Product Description is a short paragraph to describe your product in the marketplace.">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                            
                           
                            
                        </div>    
						<div class="field">
						 <label>Keyword:<span class="star">*</span></label>
                            <input type="text" class="required" name="keywords" size="40" value="">
                            <div class="tool">
                                <a href="" class="tooltip" title="Products Meta Keywords for Search Engine">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
						</div>
                        <div class="field" >
                            <label>Product Image:</label>
                            <div class="inputfields">
                                <input name="imageurl" type="file" class="inputbox" id="imageurl" /><br />
                                <small>Recommended Resolution is 150px x 200px</small>
                            </div>
                            <div class="tool">
                                <a href="" class="tooltip" title="Product Image is the image that will display beside your product description in the marketplace.">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                        </div>     
                        <div class="field" id="ototext" style="display:none">
                            <label>No Thanks Text:</label>
                            <textarea rows="2" cols="70"  name="no_text" id="no_text"  >No Thanks!, I understand that this is the only chance I will have to take advantage of this offer.</textarea>
                            <div class="tool">
                                <a href="" class="tooltip" title="No Thanks Text is the text you want to display on the No Thanks link if this is an One Time Offer.">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                        </div>    
                        <div class="field" >
                            <label>Custom PayPal Header Image:</label>
                            <input name="pp_header" type="text" class="inputbox" id="pp_header" value="https://static.e-junkie.com/sslpic/25270.82c6f2f6f6ad566fec83e9b78dbd280c.gif" size="80" />
                            <div class="tool">
                                <a href="" class="tooltip" title="Custom PayPal Header Image is a header image you want to appear at the top of your PayPal checkout pages. This needs to be a secure (https:) page.">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                        </div>
                        <div class="field">
                            <label>Custom Return To Merchant Text:</label>
                            <input name="pp_return" type="text" class="inputbox" id="pp_return" value="Click Here To Complete Your Purchase." size="60" />
                            <div class="tool">
                                <a href="" class="tooltip" title="Custom Return To Merchant Text is where you can customize the Return To Merchant button that your customers will see after they finish their payment. ">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rightpanel">
                    <fieldset>
                        <legend class="legend">Product Payment Buttons</legend>
                        <small>Add mutiple payment buttons like PayPal, Alertpay and ClickBank </small>
                        <div class="fields">
                            <div class="field">
                                <label>Product Type:</label>
                                <select  name="prodtype" id="prodtype">
                                    <option value="free" >Free</option>
                                    <option value="paid" selected="selected" >Paid</option>
                                    <option value="OTO" >OTO</option>
                                    <option value="Clickbank">Clickbank</option>
                                </select>
                                <div class="tool">    
                                    <a href="" class="tooltip" title="Product Type is where you choose what type of product you want: free, paid, or OTO.">
                                        <img src="../images/toolTip.png" alt="help"/>
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="paypaltext"  >
                                <label>PayPal Button Image:</label>
                                <input name="paypal_image" type="file" class="inputbox" id="paypal_image" /> 
                                <div class="tool">
                                    <a href="" class="tooltip" title="Button Image is the image you want to use for your buy button on your sales page. Make sure the image is already in your images directory or 
                                       it will not display properly.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="alertpaytext" >
                                <label>Alert Pay Button Image:</label>
                                <input name="alertpay_image" type="file" class="inputbox" id="alertpay_image" /> 
                                <div class="tool">
                                    <a href="" class="tooltip" title="Button Image is the image you want to use for your buy button on your sales page. Make sure the image is already in your images directory or 
                                       it will not display properly.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="clickbank_user_id" style="display:none" >
                                <label>Click Bank User Id:</label>
                                <input class="inputbox" name="click_bank_url" id="click_bank_url" type="text" value="" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Enter ClickBank user id.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="clickbank_security_code" style="display:none">
                                <label>Click Bank Security Code:</label>
                                <input class="inputbox" name="click_bank_security_code" id="click_bank_security_code" type="text" value="" />     
                                <div class="tool">
                                    <a href="" class="tooltip" title="Please enter ClickBank security code.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="clickbank_button" style="display:none">
                                <label>Click Bank Buttom Image:</label>
                                <input name="clickbank_image" type="file" class="inputbox" id="clickbank_image" /> 
                                <div class="tool">
                                    <a href="" class="tooltip" title="Please enter ClickBank Button Image.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="affiliate_program">
                                <label >Affiliate program:</label>
                                <input class="inputbox" name="affiliate_link" id="affiliate_link" type="radio" value="yes"  />Yes
                                <input class="inputbox" name="affiliate_link" id="affiliate_link" type="radio" value="no" checked="checked" />No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Add To Affiliate Program will enable or disable the affiliate link of this product in the product marketplace.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="paypal_affiliate_link" style="display:none">
                                <label>Show PayPal Affiliate link:</label>
                                <input class="inputbox" name="paypal_affiliate_link" type="radio" value="yes"  />Yes
                                <input class="inputbox" name="paypal_affiliate_link" type="radio" value="no" checked="checked" />No 
                                <div class="tool">
                                    <a href="" class="tooltip" title="PayPal Affiliate link enable PayPal Affiliate Link in marketplace in member area.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="alertpay_affiliate_link" style="display:none">
                                <label>Show AlertPay Affiliate link:</label>
                                <input class="inputbox" name="alertpay_affiliate_link" type="radio" value="yes"  />Yes
                                <input class="inputbox" name="alertpay_affiliate_link" type="radio" value="no" checked="checked" />No 
                                <div class="tool">
                                    <a href="" class="tooltip" title="AlertPay Affiliate link enable AlertPay Affiliate Link in marketplace in member area.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="clickbank_affiliate_link" style="display:none">
                                <label>Show ClickBank Affiliate link:</label>
                                <input class="inputbox" name="clickbank_affiliate_link" type="radio" value="yes"  />Yes
                                <input class="inputbox" name="clickbank_affiliate_link" type="radio" value="no" checked="checked" />No 
                                <div class="tool">
                                    <a href="" class="tooltip" title="ClickBank Affiliate link enable ClickBank Affiliate Link in marketplace in member area.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset id="marketplace_settings">
                        <legend class="legend">MarketPlace Settings</legend>
                        <small>Product configuration in marketplace and sidebar </small>
                        <div class="fields">
                            <div class="field" >
                                <label >Add to marketplace:</label>
                                <input class="inputbox" name="marketplace" id="marketplace" type="radio" value="yes"  />Yes
                                <input class="inputbox" name="marketplace" id="marketplace" type="radio" value="no" checked="checked" />No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Add To Marketplace will enable or disable the display of this product in the product marketplace. Only free and paid products can have the marketplace enabled. OTO's will not display in the marketplace.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label >Add to marketplace in member area:</label>
                                <input class="inputbox" name="member_marketplace" id="member_marketplace" type="radio" value="yes"   />Yes
                                <input class="inputbox" name="member_marketplace" id="member_marketplace" type="radio" value="no"  checked="checked" />No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Add To Marketplace in Member Area will enable or disable the display of this product in the product marketplace. Only free and paid products can have the marketplace enabled. OTO's will not display in the marketplace.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label >Add in sidebar of member area:</label>
                                <input class="inputbox" name="add_in_sidebar" id="add_in_sidebar" type="radio" value="yes"   />Yes
                                <input class="inputbox" name="add_in_sidebar" id="add_in_sidebar" type="radio" value="no"  checked="checked" />No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Add Product in New Product Listing in Member area">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </fieldset>    
                    <fieldset>
                        <legend class="legend">OTO Settings</legend>
                        <small>Attach OTO Product with <?php echo $product_name; ?></small>
                        <div class="fields">
                            <div class="field" >
                                <label >Add OTO To Product:</label>
                                <input class="inputbox" name="otocheck" type="radio" value="yes" /> 
                                Yes
                                <input class="inputbox" name="otocheck" type="radio" value="no" checked="checked" /> 
                                No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Add OTO To Product will enable an OTO for this product.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label >One Time Offer Product:</label>
                                <select name="one_time_offer" class="inputbox" size="1">
<?php echo $oto_dd ?>
                                </select>
                                <div class="tool">
                                    <a href="" class="tooltip" title="One Time Offer Product is the OTO you want to assign to your product.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label >Add Downsell OTO:</label>                
                                <input class="inputbox" name="otodowncheck" type="radio" value="yes"  />Yes
                                <input class="inputbox" name="otodowncheck" type="radio" value="no" checked="checked" />No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Add Downsell OTO will enable a downsell if the OTO isn't purchased.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field">
                                <label >Downsell OTO Product:</label>
                                <select name="down_one_time_offer" class="inputbox" size="1">
<?php echo $down_oto_dd ?>
                                </select>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Downsell OTO Product is the OTO you want to assign as a downsell">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="leftpanel" style="width:98%;padding:0px">
                    <fieldset>
                        <legend class="legend">Additional Settings</legend>
                        <div class="fields">
                            <div class="field" >
                                <label>Custom PayPal Header Image:</label>
                                <input name="pp_header" type="text" class="inputbox" id="pp_header" value="https://static.e-junkie.com/sslpic/25270.82c6f2f6f6ad566fec83e9b78dbd280c.gif" size="80" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Custom PayPal Header Image is a header image you want to appear at the top of your PayPal checkout pages. This needs to be a secure (https:) page.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field">
                                <label>Custom Return To Merchant Text:</label>
                                <input name="pp_return" type="text" class="inputbox" id="pp_return" value="Click Here To Complete Your Purchase." size="60" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Custom Return To Merchant Text is where you can customize the Return To Merchant button that your customers will see after they finish their payment. ">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label style="width:200px">Select Template:</label>
                                <select name="selectdir" class="inputbox" >
<?php echo $select_dir; ?>
                                </select>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Price is how much your product will cost if it is a paid product.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>    
                            <div class="field" >
                                <label style="width:200px">Enable Coaching:</label>
                                <input class="inputbox" name="coaching" type="radio" value="yes" <?php echo $coaching1 ?> id="coaching" />Yes
                                <input class="inputbox" name="coaching" type="radio" value="no"  <?php echo $coaching2 ?> id="coaching" />No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Enable Coaching will allow members who have access to this product to gain access to a coaching module specific to the product.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label style="width:200px">Timed Release Content:<span class="star"> </span></label>
                                <select name="tcontent" class="inputbox" id="tcontent">
                                    <option value=0>None</option>
<?php echo $tcontent ?>
                                </select>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Timed Release Content is where you can choose to display one of the timed release content campaigns instead of the normal download page. If you choose one of the content campaigns from this dropdown then it will replace the standard download page when the member clicks into the dowload area.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field">
                                <label style="width:200px">Quantity Limit:</label>
                                <input class="inputbox" name="qlimit" type="radio" value="yes" <?php echo $qlimit1; ?> id="qlimit1"/>Yes
                                <input class="inputbox" name="qlimit" type="radio" value="no" <?php echo $qlimit2; ?>  id="qlimit2" />No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Quantity Limit On will enable you to place a restriction on how many copies of this product will be sold.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="quantitytext" <?
if ($qcheck == 'yes')
    echo 'style="display:block" '; else
    echo 'style="display:none" ';
?> >
                                <label style="width:200px">Available Quantity:</label>
                                <input name="quantity_cap" type="text" class="inputbox" id="quantity_cap" value="<?php echo $quantity_cap ?>" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Available Quantity is the number of copies of this product you want to sell.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label style="width:200px">Squeeze Page:</label>
                                <input class="inputbox" name="squeeze_check" type="radio" value="yes" <?php echo $squeeze_check1 ?>  id="squeeze_check1" />Yes
                                <input class="inputbox" name="squeeze_check" type="radio" value="no"  <?php echo $squeeze_check2 ?> id="squeeze_check2"/>No
                                <div class="tool">
                                    <a href="" class="tooltip" title="Squeeze Page On will show a squeeze page instead of the normal sales page for this product.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="squeezenametext" <? if ($squeeze_check == 'yes')
    echo 'style="display:block" '; else
    echo 'style="display:none" '; ?> >
                                <label style="width:200px">Squeeze Page:</label>
                                <select name="squeezename" class="inputbox" id="squeezename">
                                    <option value=0>Select</option>
<?php echo $squeezename ?>
                                </select>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Squeeze Page allows you to choose what squeeze page you wish to display">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" >
                                <label style="width:200px">Third Party Autoresponder</label>
                                <select name="psponder" class="inputbox" id="psponder">
                                    <option value=0>Select</option>
<?php echo $psponder ?>
                                </select>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Autoresponder Form allows you to choose an autoresponder to add your customer to when they purchase or gain access to this product. This will work both outside and inside the members area.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>  
                        </div>
                    </fieldset>
                    <fieldset id="field_partners_settings">
                        <legend class="legend">Product Partner Settings</legend>
                        <div class="fields">
                            <div class="field" >
                                <label style="width:250px">Enable Product Partner:</label>
                                <input class="inputbox" type="checkbox" name="check_product_partner" id="check_product_partner" value="1" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Enable Product partner Yes or No.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div id="partners_settings" style="display: none"> 
                                <div class="field"  >
                                    <label>Product Partner PayPal Email:</label>
                                    <input type="text" name="porduct_partner_paypal_email" id="porduct_partner_paypal_email" size="40" class="inputbox email">
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Product partner paypal email address.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label>Product Partner AlertPay Email:</label>
                                    <input type="text" name="porduct_partner_alertpay_email" id="porduct_partner_alertpay_email" size="40" class="inputbox email">
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Product partner AlertPay email address.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label>Product Partner AlertPay IPN:</label>
                                    <input type="text" name="porduct_partner_alertpay_ipn" id="porduct_partner_alertpay_ipn" size="40" class="inputbox">
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Product partner AlertPay IPN.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label>Product Partner Commision%:</label>
                                    <input type="text" name="porduct_partner_commision" id="porduct_partner_commision" size="10" maxlength="5" class="inputbox number commistion">
                                    <br />
                                    <small>Commision must be less then <?php echo get_total_commission($prefix, $db); ?> %</small>
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Product partner commision.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="legend">Subscription Payment Settings</legend>
                        <div class="fields">
                            <div class="field" >
                                <label style="width:200px">Subscription Active:</label>
                                <input class="inputbox" type="checkbox" name="subscription_active" id="subscription_active" value="1" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Subscription Active will change your product payment from a one time payment to a recurring billing subscription.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="pricefield" >
                                <label>Price:<span class="star">*</span></label>
                                <input type="text" name="price" id="product_price" value="<?php echo $price ?>" class="inputbox digits <? if ($prodcheck == 'free')
    echo 'required" '; ?> " />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Price is how much your product will cost if it is a paid product.">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div id="subscription_active_billing" style="display: none">
                                <div class="field" >
                                    <label style="background-color:#d4d4d4">Regular billing cycle:</label>
                                    <label>Billing Period:</label>
                                    <input class="inputbox" type="text" name="period3_value" value="1" size="3" maxlength="3" />
                                    <select class="inputbox" name="period3_interval">
                                        <option value='D'>Day(s)</option>
                                        <option value='W'>Weeks(s)</option>
                                        <option value='M'>Month(s)</option>
                                        <option value='Y'>Year(s)</option>
                                    </select>
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Billing Period is the number of days you want to allow in each billing period. This is fully customizable and can be set to any number of days, weeks, months, or years.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label >Billing Amount:<span class="star">*</span></label>
                                    <input class="inputbox digits" id="amount3" type="text" name="amount3" value="0.00" size="9" maxlength="9" />
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Billing Amount is the amount you want to charge your members each billing period.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label >Subscription Length:</label>
                                    <input class="inputbox" type="text" name="srt" value="1" size="9" maxlength="9" /><br />
                                    <small>(This is the number of times the member will be charged. If the subscription never ends then leave at 0.)</small>
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Subscription Length is the number of times you want the subscription to bill the customer. For open ended subscriptions that do not end leave set to 0.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label style="background-color:#d4d4d4">Trial Period 1</label>
                                    <small>Skip this section if you do not want to offer  trial periods with your subscriptions.</small><br />
                                    <label style="width:200px;">Trial Period 1 Active</label>
                                    <input class="inputbox" type="checkbox" name="period1_active" value="1"/>
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Subscription Active will change your product payment from a one time payment to a recurring billing subscription.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label>Trial 1 Subscription Period:</label>
                                    <input class="inputbox" type="text" name="period1_value" value="1" size="3" maxlength="3" />
                                    <select class="inputbox" name="period1_interval">
                                        <option value='D'>Day(s)</option>
                                        <option value='W'>Weeks(s)</option>
                                        <option value='M'>Month(s)</option>
                                        <option value='Y'>Year(s)</option>
                                    </select>
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Trial 1 Subscription Period is the number of days you want to allow in each billing period. This is fully customizable and can be set to any number of days, weeks, months, or years.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                                <div class="field" >
                                    <label>Trial 1 Subscription Amount:</label>
                                    <input class="inputbox" type="text" name="amount1" value="0.00" size="9" maxlength="9" />
                                    <div class="tool">
                                        <a href="" class="tooltip" title="Trial 1 Subscription Amount is the amount you want to charge your members for the trial period.">
                                            <img src="../images/toolTip.png" alt="help" align="top" />
                                        </a>
                                    </div>
                                </div>
                            </div>   
                            <div class="field" id="priceaffiliate" >
                                <label style="background-color:#d4d4d4">Commission</label>
                                <label>Affiliate Commission %:</label>
                                <input name="commission" type="text" class="inputbox number" id="commission" value="" maxlength="3" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="Affiliate Commission % is the percent of sales you wish affiliates to get when someone buys through their affiliate link.This is a percentage of sales, not the product price">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                            <div class="field" id="pricejv">
                                <label>JV Partner Commission %:</label>
                                <input name="jvcommission" type="text" class="inputbox number" id="jvcommission" value="" maxlength="3" />
                                <div class="tool">
                                    <a href="" class="tooltip" title="JV Partner Commission % is the percent of sales you wish affiliates to get when someone buys through their affiliate link.This is a percentage of sales, not the product price">
                                        <img src="../images/toolTip.png" alt="help" align="top" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </fieldset>    
                    <fieldset style="display:none" id="meetquantity">
                        <legend class="legend">Sold Out Page:</legend>
                        <div class="fields"  >
                            <label>Sold Out Page:</label>
                            <div class="tool">
                                <a href="" class="tooltip" title="Sold Out Page will replace the sales page for this product automatically once the quantity limit has been reached.">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                            <textarea name="quantity_met_page" cols="100" rows="8" class="inputbox" id="quantity_met_page"><?php echo $quantity_met_page ?></textarea>
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
							oEdit2.arrCustomButtons = [["Snippets", "modalDialog('/admin/Editor/scripts/bootstrap/snippets.htm',860,530,'Insert Snippets');", "Bootstrap", "btnContentBlock.gif"]];
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
							 oEdit2.css = ["/admin/Editor/scripts/bootstrap/css/bootstrap.min.css"];
							/*Render the editor*/
                             oEdit2.REPLACE("quantity_met_page");
                            </script>
                        </div>    
                    </fieldset>        
                    <fieldset id="salespage" >
                        <legend class="legend">Sales Page:</legend>
                        <div class="fields" id="salespagecontent" >
                            <label>Sales Page:</label>
                            <div class="tool">
                                <a href="" class="tooltip" title="Sales Page is where you put the html for your product sales page. This page uses the default header and footer. Do not include header and footer html when designing the sales page as they will be added automatically by the script. Enter only the text that goes between the body tags.">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                            <textarea name="index_page" cols="100" rows="8" class="inputbox" id="index_page"><?php echo $index_page ?></textarea>
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
							 oEdit1.css = "/admin/Editor/scripts/bootstrap/css/bootstrap.min.css";
							/*Render the editor*/
							 oEdit1.REPLACE("index_page");
                            </script>
                        </div>    
                    </fieldset>
                    <fieldset>
                        <legend class="legend">Download Page:</legend>    
                        <small>Note: This page uses your default member template. Add content or content html only.</small>    
                        <div class="fields" >
                            <label>Download Page:</label>
                            <div class="tool">
                                <a href="" class="tooltip" title="Price is how much your product will cost if it is a paid product.">
                                    <img src="../images/toolTip.png" alt="help" align="top" />
                                </a>
                            </div>
                            <textarea name="download_form" cols="100" rows="8" class="inputbox" id="download_form"><?php echo $download_form ?></textarea>
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
							 oEdit3.css = "/admin/Editor/scripts/bootstrap/css/bootstrap.min.css";
							/*Render the editor*/
                             oEdit3.REPLACE("download_form");
                            </script>
                        </div>    
                    </fieldset>
                </div>
                <div class="fields" style="padding:5px 7px;">
                    <input type="submit" name="submit" value="Save Product" class="inputbox">
                </div>
            </form>
        </div>
    </div>
    <div class="content-wrap-bottom"></div>
</div>
<?php
include_once("footer.php");
function get_total_commission($prefix, $db) {
    $sql = "select (partner_commission + second_partner_commission ) as total from " . $prefix . "site_settings where id='1'";
    $row = $db->get_a_line($sql);
    return (int) 100 - $row['total'];
}
?>