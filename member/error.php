<?php
include ("include.php");
include_once("session.php");
$error=$_GET['error'];

if($error == '1')
	{
	$pagecontent .= "INVALID URL PASSED<br>No filename specified.";
	}
elseif($error == '2')
	{
	$pagecontent .= "We are sorry, but the requested file could not be found.<br>Either the file does not exist or the file name is incorrect.<br>Please contact site admin to correct this problem.";
	}
elseif($error == '3')
	{
	$pagecontent .= "We could not confirm your PayPal payment. Please contact the site admin for instructions.";
	}
elseif($error == '4')
	{
	$pagecontent .= "You do not currently have access to this page.";
	}
elseif($error == '5')
	{
	$pagecontent .= "We could not confirm your AlertPay payment. Please contact the site admin for instructions.";
	}	
	
	$smarty->assign('pagename',	$pagename);
	$smarty->assign('main_content',$pagecontent);
	$output = $smarty->fetch('../html/member/content.tpl');
	
	$objTpl = new TPLManager($FILEPATH.'/index.html');
	$hotspots = $objTpl->getHotspotList();
	
	$placeHolders = $objTpl->getPlaceHolders($hotspots);
	$i=0;
	foreach($placeHolders as  $items)
	{
		$smarty->assign("$hotspots[$i]","$items");
		$i++;
	}
	
	$smarty->assign('current_date',$today);
	$smarty->assign('right_panel',$right_panel);
        $smarty->assign('sidebar',$right_panel);
	$smarty->assign('error',$Message);
	$smarty->assign('content',$output);
	$smarty->display($FILEPATH.'/index.html');		
?>