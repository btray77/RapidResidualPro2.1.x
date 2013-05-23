<?php
include_once("../common/config.php");
include_once("../common/database.class.php");
include_once("../common/common.class.php");
include_once '../smarty/libs/Smarty.class.php';
include_once("../common/placeholder.class.php");
require_once('../common/alertpay.class.php');
include_once ("../common/clickbank.class.php");
include_once("../common/counter.class.php");
include_once("../common/autoresponders.php");
include_once("../common/payment-receiver.class.php");
$limit=10;
$today	= date("Y-m-d H:i:s");
$db = new database();
$common = new common();
$counter = new counter();
$alertpay = new AlertPay();
$clickBank = new ClickBank();
$memberid = $_SESSION['memberid'];
ob_start();
// Get html header information
$q = "select sitename, description, keywords, meta from ".$prefix."site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
$meta = stripslashes($meta);

/* ********** To check whether cookie of coupon is set start ********* */
if(isset($_COOKIE['coupon_code'])){
	$coupon  = $_COOKIE['coupon_code'];
}else{
	$coupon  = $_GET['coupon'];
}
/* ********** To check whether cookie of coupon is set end ********* */
/********************************************************************/
$sql="select name from ". $prefix ."template where default_member=1;";
$row = $db->get_a_line($sql);
$template = $row['name'];

/*******************************************************************/
if(!empty($template)){
$FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/$template";
}
else 
$FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/memberarea/";
/********************************************************************/

$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 0;
$smarty->template_dir = "/templates";
$objTpl = new TPLManager($FILEPATH.'/index.html');
/*************************************************************************************/
//require_once($root_path . 'common/clickbank.class.php');
//$clickBank = new ClickBank();	
$alertpay = new AlertPay();
/*********************************************************************************************/
// Get index page content
?>