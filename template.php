<?php
include ("include.php");
include ("common/config.php");
$GetFile = file("html/template.html");
$Content = join("",$GetFile);
$today	= date("Y-m-d");
$ip	= $_SERVER['REMOTE_ADDR'];
ob_start();
	
$q = "select sitename, description, keywords, tracking from ".$prefix."site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
$tracking = stripslashes($r['tracking']);

if($page)
	{
	$q = "select * from ".$prefix."pages where filename='$page'" ;
	$w = $db->get_a_line($q);
	@extract($r);
	$description	= stripslashes($w["description"]);
	$keywords		= stripslashes($w["keywords"]);	
	}

	
// Lets get referrer
$ref   = $_GET['ref'];
if ($ref == "")
	{
	$ref=$_COOKIE[rapidresidualpro];
	}
elseif ($ref != "")
{
		// Apply site settings checks on cookies
		$qry = "select cookie_mode, cookie_expiry from ".$prefix."site_settings where id='1'";
		$row = $db->get_a_line($qry);
		$cookie_mode = $row['cookie_mode'];
		$cookie_expiry = $row['cookie_expiry'];
		// Apply site settings checks on cookies
		if($cookie_mode == 'last'){
			setcookie("rapidresidualpro", $ref, time()+3600*24*$cookie_expiry, "/", $_SERVER['HTTP_HOST'], 0);
		}else{
			if(isset($_COOKIE['rapidresidualpro'])){
			}else{
				setcookie("rapidresidualpro", $ref, time()+3600*24*$cookie_expiry, "/", $_SERVER['HTTP_HOST'], 0);
			}
		}

	
		//setcookie("rapidresidualpro", $ref, time() + 365*24*60*60, "/", $_SERVER['HTTP_HOST'], 0);
	}
$link="<a href=http://rapidresidualpro.com target=_blank>Powered by Rapid Residual Pro</a>";
?>