<?php
include_once("common/config.php");
include_once ("include.php");
include_once("common/placeholder.class.php");
include_once("common/common.class.php");

$error = $_GET['error'];
$errors = $common->show_error($error);
/***************************************************************************************************/
// Get page content
/***************************************************************************************************/
$pagename	= "404 Page Not Found";
$main_content = "The Page you are requesting is not available right now.";

$smarty->assign('pagename',$pagename);
$smarty->assign('main_content',$main_content);
$smarty->assign('error',$errors);
$output = $smarty->fetch('html/error.tpl');
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
?>