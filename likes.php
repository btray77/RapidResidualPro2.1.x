<?php
include ("common/config.php");
include ("common/database.class.php");
include ("common/common.class.php");
$db = new database();
$common = new common();
$path = $_SERVER['REQUEST_URI'];
$path = preg_replace("@.*?(/recommends/.*?)@","$1", $path);
$var_array = explode("/",$path);

$var1 = $var_array[2];  // redirect nickname

if ($var1 == "")
	{
    echo "INVALID REDIRECT URL PASSED<br>";
    echo "You much supply the correct variable after /likes/.";
    exit;
	}
elseif ($var1 != "")
	{
	// does the nickname exist?
	
	$q = "select count(*) as cnt from ".$prefix."recommends where nickname='$var1'";
	$r = $db->get_a_line($q);
	if($r[cnt] == '0')
		{
		// not a valid nickname
		echo "Sorry, you supplied an incorrect url. The redirect nickname does not exist.";
		exit;     
		}
	else
		{
		$q = "select * from ".$prefix."recommends where nickname='$var1'";
		$vv = $db->get_a_line($q);
		$url = $vv['url'];		
		header("Location: ".$url);
		exit;		
		}		
	}	
	
?>