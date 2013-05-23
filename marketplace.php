<?php
include_once("common/config.php");
include ("include.php");

$limit=10;

########## pagination ###########
$q = "select count(*) as cnt from ".$prefix."products where marketplace = 'yes' and published=1";
$r = $db->get_a_line($q);
$count = $r[cnt];
if($count == "0")
	{
	$warning = "No Results Found";
	}



	if($pageno==""){$pageno=0;}
	if($$pageno)
	$start = ($pageno - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;		

$pager=$common->pagiation_simple('marketplace.php',$limit,$count,$pageno,$start);
########## pagination ###########
$ChangeColor = 1;
$ToReplace = "";
$sql="select * from ".$prefix."products where marketplace = 'yes' and published=1 order by id asc limit $start, $limit";
$products = $db->get_rsltset($sql);


if(count($products) > 0)
{
	
	$i=0;
	foreach($products as $product){
	/********************************************/
	if($product['period3_interval'] == "D"){$interval = "Day(s)";}
	if($product['period3_interval'] == "W"){$interval = "Week(s)";}
	if($product['period3_interval'] == "M"){$interval = "Month(s)";}
	if($product['period3_interval'] == "Y"){$interval = "Year(s)";}	
	/*******************************************************/	
		if($product['prodtype'] == "free")
		{
		$salesprice = "Free";
		}
		else
		{
		if($product['subscription_active'] == "1")
			{
			$salesprice = $product['amount3'] ." every ".$period3_value." ".$interval;
			}
			else
			{
			$salesprice = $product['price'];
			}		
		}
	/***********************************************************************/
		if($product['imageurl'] == '')
		{
		$product_image ='';
		}
	else
		{
		$product_image ='<img src="'.$product['imageurl'].'" border="0">';
		}					
	
	$salespage_link='<a href="products.php?short='.$product['pshort'].'">Click Here For More Information</a>';
	/***********************************************************************************************/	
		$productContent[$i]['name'] = $product['product_name'];
		$productContent[$i]['discription'] = stripslashes($product['prod_description']);
		//$productContent[$i]['price'] = $salesprice;
		$productContent[$i]['image'] = $product_image;
		$productContent[$i]['saleslink'] = $salespage_link;
	$i++; }
	}

	$smarty->assign('products',$productContent);
	$smarty->assign('pager',$pager);
	
	$output_market = $smarty->fetch('html/marketplace.tpl');
	
	$smarty->assign('pagename','Product Marketplace');
	$smarty->assign('main_content',$output_market);
	$output = $smarty->fetch('html/content.tpl');
	
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
	
	$smarty->display($FILEPATH.'/index.html');


?>