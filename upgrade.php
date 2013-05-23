<?php error_reporting(E_ERROR);
if (!ini_get('display_errors')) { ini_set('display_errors', 1); }
include_once "common/config.php";
include_once "include.php";
include_once "common/placeholder.class.php";
/* * ******************************	 PRODUCTS 	*************************************** */
$q = mysql_query("select * from rrp_products order by id ");
$num_fields = mysql_num_fields($q);
$field = array();
for ($i = 0; $i < $num_fields; $i++) {
    $field[] = mysql_field_name($q, $i);
}
if (!in_array('add_in_sidebar', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `add_in_sidebar` VARCHAR( 5 ) NOT NULL DEFAULT 'yes'");
if (!in_array('member_marketplace', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `member_marketplace` VARCHAR( 5 ) NOT NULL DEFAULT 'yes'");
if (!in_array('button_html', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `button_html` VARCHAR( 255 ) NOT NULL'");
if (!in_array('button_forum', $field))
    mysql_query( "ALTER TABLE `rrp_products` ADD `button_forum` VARCHAR( 255 ) NOT NULL");
if (!in_array('button_link', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `button_link` VARCHAR( 255 ) NOT NULL");
if (!in_array('show_affiliate_link_paypal', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `show_affiliate_link_paypal` VARCHAR( 5 ) NOT NULL DEFAULT 'yes'");
if (!in_array('show_affiliate_link_alertpay', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `show_affiliate_link_alertpay` VARCHAR( 5 ) NOT NULL DEFAULT 'yes'");
if (!in_array('show_affiliate_link_clickbank', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `show_affiliate_link_clickbank` VARCHAR( 5 ) NOT NULL DEFAULT 'yes'");
if (!in_array('product_partner_paypal_email', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `product_partner_paypal_email` VARCHAR( 50 ) NOT NULL");
if (!in_array('product_partner_alertpay_email', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `product_partner_alertpay_email` VARCHAR( 50 ) NOT NULL");
if (!in_array('ap_partner_ipn_security_code', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `ap_partner_ipn_security_code` VARCHAR( 70 ) NOT NULL");
if (!in_array('partner_commission', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `partner_commission` INT(11) NOT  NULL");
if (!in_array('enable_product_partner', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `enable_product_partner` TINYINT  NOT  NULL  DEFAULT  '0'");
if (!in_array('trash', $field))
    mysql_query( "ALTER TABLE `rrp_products`  ADD `trash`  TINYINT NOT NULL DEFAULT '0'");
if (!in_array('keywords', $field))
    mysql_query( "ALTER TABLE  `rrp_products` ADD  `keywords` TEXT NOT NULL AFTER  `prod_description`");
/* * ******************************   PRODUCT_SHORT 	*************************************** */
$q = "CREATE TABLE IF NOT EXISTS `rrp_products_short` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_url` text NOT NULL,
  `redirect_url` text NOT NULL,
  `product_id` int(11) NOT NULL,
   PRIMARY KEY (`id`)
  )ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
mysql_query($q);
/* * ******************************************************************************************** */
$sql = mysql_query("select * from rrp_click_stats");
$num_fields_clicks = mysql_num_fields($sql);
$field_clicks = array();
for ($i = 0; $i < $num_fields_clicks; $i++) {
    $field_clicks[] = mysql_field_name($sql, $i);
}
if (!in_array('cookies_ref', $field_clicks))
    mysql_query("ALTER TABLE `rrp_click_stats`  ADD  `cookies_ref`  VARCHAR(  255 )  NOT  NULL;");
if (!in_array('ip_ref', $field_clicks))
    mysql_query("ALTER TABLE `rrp_click_stats`  ADD  `ip_ref`  VARCHAR(  255 )  NOT  NULL;");
/* * ******************************	 CLICKS STATUS 	*************************************** */
$sql = mysql_query("select * from rrp_click_stats");
$num_fields_clicks = mysql_num_fields($sql);
$field_clicks = array();
for ($i = 0; $i < $num_fields_clicks; $i++) {
    $field_clicks[] = mysql_field_name($sql, $i);
}

if (!in_array('cookies_ref', $field_clicks))

   mysql_query("ALTER TABLE `rrp_click_stats`  ADD  `cookies_ref`  VARCHAR(  255 )  NOT  NULL;");

if (!in_array('ip_ref', $field_clicks))

    mysql_query("ALTER TABLE `rrp_click_stats`  ADD  `ip_ref`  VARCHAR(  255 )  NOT  NULL;");

if (!in_array('item_type', $field_clicks))

   mysql_query("ALTER TABLE `rrp_click_stats`  ADD  `item_type`  VARCHAR(  255 )  NOT  NULL;");

if (!in_array('item_id', $field_clicks))

    mysql_query("ALTER TABLE `rrp_click_stats`  ADD  `item_id`  int(11)  NOT  NULL;");

/* * ***************************************************************************************** */
$sql = mysql_query("select * from rrp_squeeze_pages");

$num_fields_squeeze_pages = mysql_num_fields($sql);

$field_clicks = array();

for ($i = 0; $i < $num_fields_squeeze_pages; $i++) {

    $field_squeeze_pages[] = mysql_field_name($sql, $i);

}
if (!in_array('meta_discription', $field_squeeze_pages))

    mysql_query("ALTER TABLE `rrp_squeeze_pages` ADD `meta_discription` TEXT NOT NULL ");
	
if (!in_array('page_title', $field_squeeze_pages))

    mysql_query("ALTER TABLE `rrp_squeeze_pages` ADD `page_title` VARCHAR(  255 )  NOT  NULL ");
	
if (!in_array('seo_title', $field_squeeze_pages))

    mysql_query("ALTER TABLE `rrp_squeeze_pages` ADD `seo_title` VARCHAR(  255 )  NOT  NULL ");
	
/*if (!in_array('meta_discription', $field_squeeze_pages))

    mysql_query("ALTER TABLE `rrp_squeeze_pages` ADD `meta_discription` VARCHAR(  255 )  NOT  NULL ");	*/		
/* * ***************************************************************************************** */
$sql = mysql_query("select * from rrp_site_settings");

$num_fields_site_settings = mysql_num_fields($sql);

$field_site_settings = array();

for ($i = 0; $i < $num_fields_site_settings; $i++) {

    $field_site_settings[] = mysql_field_name($sql, $i);

}
if (!in_array('sidebar_my_download_text', $field_site_settings))
    mysql_query("ALTER TABLE `rrp_site_settings` ADD `sidebar_my_download_text` VARCHAR( 255 ) NOT NULL;");

if (!in_array('sidebar_instruction_text', $field_site_settings))
    mysql_query("ALTER TABLE `rrp_site_settings` ADD `sidebar_instruction_text` VARCHAR( 255 ) NOT NULL;");

if (!in_array('sidebar_new_products_text', $field_site_settings))
    mysql_query("ALTER TABLE `rrp_site_settings` ADD `sidebar_new_products_text` VARCHAR( 255 ) NOT NULL;");

if (!in_array('social_media_widgets', $field_site_settings))
    mysql_query("ALTER TABLE `rrp_site_settings` ADD `social_media_widgets` TEXT NOT NULL;");

    mysql_query("ALTER TABLE  `rrp_site_settings` CHANGE  `logo_url`  `logo` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    mysql_query("ALTER TABLE `rrp_site_settings` ADD `tagline` VARCHAR( 255 ) NOT NULL");

    
/*********************************************************************************************************/ 
mysql_query("ALTER TABLE  `rrp_orders` ADD  `subscriber_id` VARCHAR( 30 ) NOT NULL");
mysql_query("UPDATE `rrp_orders` SET payment_amount = '0.00' where payment_amount='$0 (allocate)'");
/***********************************************************************************************************/
$rs_sql = mysql_query("select * from rrp_amazon_s3");
$num_fields_amazon_s3 = mysql_num_fields($rs_sql);
$field_amazon_s3 = array();
for ($i = 0; $i < $num_fields_amazon_s3; $i++) {
    $field_amazon_s3[] = mysql_field_name($sql, $i);
}
if (!in_array('hidden_id', $field_amazon_s3))
    mysql_query("ALTER TABLE `rrp_amazon_s3` ADD `hidden_id` TEXT NOT NULL;");
	
$rs_amazon_s3 = mysql_query("select * from rrp_amazon_s3");
while($row_amazon_s3 = mysql_fetch_assoc($rs_amazon_s3)){
$hidden_id = md5($row_amazon_s3['id']);
$sql="update `rrp_amazon_s3` SET `hidden_id` ='$hidden_id' where id= $row_amazon_s3[id]";
	if(!mysql_query($sql)) die(mysql_error());
}
/* * ***************************************************************************************** */
$sql="CREATE TABLE IF NOT EXISTS `rrp_subscription_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subscribtion_id` varchar(30) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `create_date` date NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `oid` (`oid`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `subscribtion_id` (`subscribtion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
mysql_query($sql);
/* * ***************************************************************************************** */
$sql = "CREATE TABLE IF NOT EXISTS `rrp_products_short` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_url` text NOT NULL,
  `redirect_url` text NOT NULL,
  `product_id` int(11) NOT NULL,
   PRIMARY KEY (`id`)
  )ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
mysql_query($sql);

/* * ********************************  WRITE .HTTACCESS  ******************************************************* */
/*
$ourFileName = ".htaccess";
$arr = file($ourFileName);
foreach ($arr as $str) {
   list($RewriteRule, $buynow, $buynow_php) = explode(" ", $str);
    $data['rule'][] = $RewriteRule;
    $data['redirect'][] = $buynow;
    $data['filename'][] = strip_tags($buynow_php);
}



// && !in_array('buynow.php?pid=$1', $data['filename'])

if (!in_array('buynow.php?pid=$1&gateway=$2', $data['filename'])) {

    if ($fh = fopen($ourFileName, 'a')) {

        $stringData = " 
RewriteRule buynow/(.*)/(.*) buynow.php?pid=$1&gateway=$2 \n
RewriteRule buynow/(.*) buynow.php?pid=$1 \n 
RewriteRule yes/(.*) buy.php?var1=$1 \n";

        fwrite($fh, $stringData);

        fclose($fh);

    }

    else{    

        echo '
<div style="width:auto;margin:10% auto;padding:10px; font-family:Verdana, Arial, Helvetica, sans-serif;color:#FF6600;font-size:16px;border:1px solid #CC9900;background-color:#FDF2DF;text-align:center">
Sorry! Unable to top .htaccess file. Please find the file in root directory and add following lines. <br />
RewriteRule buynow/(.*)/(.*) buynow.php?pid=$1&gateway=$2\n <br />
RewriteRule buynow/(.*) buynow.php?pid=$1\n <br />
RewriteRule yes/(.*) buy.php?var1=$1\n <br />
</div>';

    }

 }
*/
    /*     * ******************************************   Constant Contact         ************************************* */

$sql = "CREATE TABLE IF NOT EXISTS `rrp_constant_contact`  (
`username` VARCHAR( 50 ) NOT NULL ,
`password` VARCHAR( 50 ) NOT NULL ,
`api_key` VARCHAR( 75 ) NOT NULL ,
`consumerSecret` VARCHAR( 75 ) NOT NULL
) ENGINE = MYISAM ;";
 mysql_query($sql);

$sql_order = "SELECT count(txnid) as total,txnid  FROM `rrp_orders` Where txnid <> 'FREE' AND txnid <>'ALLOCATED'  group by txnid order by  total  DESC";
$result_order = mysql_query($sql_order);
//$row_order= mysql_fetch_array($result_order);
while($row_order = mysql_fetch_assoc($result_order)){
			if($row_order['total'] > 1)
				{
				 $sql ="select * from `rrp_orders` where `txnid` ='$row_order[txnid]' ";
				 $rs=mysql_query($sql);
				 $i=0;
				 while($row = mysql_fetch_assoc($rs)){
				 	if($i==0){
						$sql_update_order ="UPDATE `rrp_orders` SET subscriber_id ='$row_order[txnid]' where `txnid` ='$row_order[txnid]'";
						mysql_query($sql_update_order);
						 $sql_order ="select o.id as oid,m.member_id   from `rrp_orders` o, rrp_member_products m where o.`txnid` = m.txn_id AND o.`txnid` ='$row[txnid]' ";
					 											
						//print_r($row);		
						}
					else {
						
						$rs_orders = mysql_query($sql_order);
						$rowdata = mysql_fetch_assoc($rs_orders);
						
						echo "<br><font color='red'> Add to subscribtion History</font> <br>";
						$sql_insert = $sql ='INSERT INTO rrp_subscription_payment_history  SET '.
											"oid='".$rowdata['oid']."',".
											"create_date='".$row['date']."',".
											"product_id='".$row['item_number']."',".
											"price='".$row['payment_amount']."',".
											"payment_status='".$row['payment_status']."',".
											"subscribtion_id='".$row['txnid']."',".
											"user_id='".$rowdata['member_id']."';";
																
						if(!mysql_query($sql_insert)) die( mysql_error());
						 $sql ="DELETE FROM `rrp_orders` where id =$row[id]";
						mysql_query($sql);
					}
					$i++;
					}
					
				}
}
?>

<div style="width:auto;margin:10% auto;padding:10px; font-family:Verdana, Arial, Helvetica, sans-serif;color:#666666;font-size:16px;border:1px solid #006633;background-color:#D7FFD7;text-align:center">
    Thank you! Database settings are successfully updated. Please delete this file for better security. <br />
	<b>Please delete upgrade.php file. This could be a security risk.</b>
</div>