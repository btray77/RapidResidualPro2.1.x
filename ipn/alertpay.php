<?php
//header("Cache-Control: no-cache, must-revalidate");
include ("../common/config.php");
include ("../common/database.class.php");
include ("../common/common.class.php");
include ("../common/alertpay.class.php");
$fp = fopen('ipn.log', 'w');

$string .= "\n-------------------------------DATA RECEIVED ------------------------- \n";
$string .= print_r($_REQUEST, true);
$string .= "\n-------------------------------DATA RECEIVED ------------------------- \n";

$string .= "\nGoing Further to Database...\n";


$db = new database();

$string .= "\nDatabase Included Sucessfully!...\n";


$common = new common();

$string .= "\nCommon Included Sucessfully!...\n";


$alertPay = new AlertPay();

$string .= "\nAlertpay  Included Sucessfully!...\n";


$string .= "\n-------------------------------IPN DATA RECEIVED ------------------------- \n";
$string .=  $alertPay->ipn();
$string .= "\n------------------------------- IPN DATA RECEIVED ------------------------- \n";

$string .= "All Process Done\n";
fwrite($fp, $string);



?>

