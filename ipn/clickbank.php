<?php
require_once ("../common/config.php");
require_once ("../common/database.class.php");
require_once ("../common/common.class.php");
require_once("../common/clickbank.class.php");
$string = print_r($_REQUEST, true);
$fp = fopen('clickbank.log', 'a');
$string = "Database Loading...\n";
$db = new database();
$string = "Database Loading...\n";
$clickBank = new ClickBank();
$string .= "Loading Click Bank Object...\r\n";
$clickBank->ipn();
$string .= "IPN Successfully Executed...\r\n";
fwrite($fp, $string);
fclose($fp);


?>