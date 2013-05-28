<?php
include ("include.php");
include_once("session.php");
/***************************************************************/
$sql_men = "select * from ".$prefix."members where id='$memberid'";
$row_mem = $db->get_a_line($sql_men);
@extract($row_mem);
$status			= $row_mem['status'];	

// Menu cases here
if($status == '1')
{
	$sql_page = "select affiliate_main as member_main, affiliate_menu_id from ".$prefix."misc_pages";
	$row_page = $db->get_a_line($sql_page);
	 // Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['affiliate_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
		$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
elseif($status == '2')
{
	$sql_page = "select member_main as member_main, member_menu_id from ".$prefix."misc_pages"; 
	$row_page = $db->get_a_line($sql_page);
	 // Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['member_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
	$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
elseif($status == '3')
{
	$sql_page = "select jv_main as member_main, jv_menu_id from ".$prefix."misc_pages";
	$row_page = $db->get_a_line($sql_page);
	// Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['jv_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
	$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
/******************************************************************/
$sql_men = "select * from ".$prefix."members where id='$_SESSION[memberid]'";
$row_mem = $db->get_a_line($sql_men);
@extract($row_mem);
$firstname 		= $row_mem['firstname'];
$username		= $row_mem['username'];
$status			= $row_mem['status'];
$paypal_email   = $row_mem['paypal_email'];	
########## pagination ###########
/************************************************************************************/
	$mydownloads = $common->myDownloads($prefix,$db,$memberid); 
	$new_products = $common->newProducts($prefix,$db,$memberid); 
/************************************************************************************/
	$smarty->assign('time_release_content',$time_release_content);
	$smarty->assign('my_downloads',$mydownloads);
	$smarty->assign('new_products',$new_products);
	
	$right_panel = $smarty->fetch('../html/member/right_panel.tpl');
/***********************************************************************************/
########## pagination ###########
$sql = "select count(id) as total from ".$prefix."member_products where member_id='$memberid' && refunded='0'";
$row_total = $db->get_a_line($sql);
if($pageno)
$start = ($pageno - 1) * $limit; 			//first item to display on this page
else
{
$start = 0;
$pageno = 0;
}	
$pager=$common->pagiation_simple('downloads.php',$limit,$row_total['total'],$pageno,$start,'');	
########## pagination ###########
$ChangeColor = 1;
$ToReplace = "";
$sql="select * from ".$prefix."member_products where member_id='$memberid' && refunded='0' order by id asc limit $start,$limit ";
$products = $db->get_rsltset($sql);
if(count($products) > 0)
{
	$i=0;
	foreach($products as $product){
	$sql = "select * from ".$prefix."products where id='$product[product_id]'" ;
	$row_product = $db->get_a_line($sql);
	 
        $sql = "select * from ".$prefix."orders where id='$product[product_id]'" ;
	$row_product_orders = $db->get_a_line($sql);
        $id = str_replace('=', '', base64_encode (base64_encode ($row_product_orders['txn_id'])));
   
	$productContent[$i]['name'] = $row_product['product_name'];
	$productContent[$i]['pshort'] = $row_product['pshort'];
	$productContent[$i]['description'] = $row_product['prod_description'];
	$productContent[$i]['date'] = $product['date_added'];
	$productContent[$i]['txn_id'] = $product['txn_id'];
        $productContent[$i]['orderdetails'] = $orderdetails;
	
	if($row_product['coaching'] == 'yes')
		{		
		$sql_total = "select count(*) as total from ".$prefix."member_messages where product='$row_product[pshort]' 
		&& mid='$memberid' && mchecked='0'";
		$row_total_checked = $db->get_a_line($sql_total);
		$sql_total = "select count(*) as total from ".$prefix."member_messages where product='$row_product[pshort]' 
		&& mid='$memberid'";
		$row_total = $db->get_a_line($sql_total);
		
		if($row_total_checked['total'] == '0')
			{
				$getprod	= "<a href='paid.php?pid=".$product[product_id]."'>Click Here For Download Page</a><br>
				<a href='index.php?page=messages&pid=".$row_product[pshort]."'>Click Here To Access Your Coaching Module</a>
				(". $row_total['total'] .")";
				/*$getprod	= "<a href='paid.php?pid=".$product[product_id]."'>Click Here For Download Page</a>";*/			
			}
		else
			{
				$getprod	= "<a href='paid.php?pid==".$product[product_id]."'>Click Here For Download Page</a><br>
				<a href='index.php?page=messages&pid=".$row_product[pshort]."'>
				Click Here To Access Your Coaching Module (". $row_total['total'] .")  ". $row_total_checked['total'] ." New</a>";
				/*$getprod = "<a href='paid.php?pid==".$product[product_id]."'>Click Here For Download Page</a>";*/
			} // end count1		
		} // end coaching on
	else
		$getprod = "<a href='paid.php?pid=".$product[product_id]."'>Click Here For Download Page</a>";
		$productContent[$i]['getprod'] = $getprod;		
	
	$i++;	}
	}
		
                
        $smarty->assign('products',$productContent);
		
		$smarty->assign('pager',$pager);
		$output_download = $smarty->fetch('../html/member/downloads.tpl');
		$output_download= stripslashes($output_download);
		$smarty->assign('pagename','My Downloads');
		$smarty->assign('main_content',$output_download);
		$output = $smarty->fetch('../html/member/content.tpl');
		
		$hotspots = $objTpl->getHotspotList();
		
		$placeHolders = $objTpl->getPlaceHolders($hotspots);
		$i=0;
		foreach($placeHolders as  $items)
		{
			
			$smarty->assign("$hotspots[$i]","$items");
			$i++;
		}
		
		$smarty->assign("menus",$menus);
		$smarty->assign('current_date',$today);
		$smarty->assign('right_panel',$right_panel);
                $smarty->assign('sidebar',$right_panel);
		$smarty->assign('error',$Message);
		$smarty->assign('content',$output);
		$smarty->display($FILEPATH.'/index.html');
?>
