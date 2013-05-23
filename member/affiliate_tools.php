<?php

include ("include.php");
include_once("session.php");

$q = "select * from ".$prefix."members where id='$memberid'";
$r = $db->get_a_line($q);
@extract($r);
$firstname 		 = $r[firstname];
$lastname		 = $r["lastname"];
$username		 = $r[username];
$status			 = $r[status];
$paypal_email    = $r['paypal_email'];
$alertpay_email  = $r['alertpay_email'];
$clickbank_email = $r['clickbank_email'];

// Get index page content
/*if($status == '1')
{
	$menus = $objTpl->getPlaceHolders('menu_member');
}
elseif($status == '2')
{
	$menus = $objTpl->getPlaceHolders('menu_jvpartner');
}
elseif($status == '3')
{
	$menus = $objTpl->getPlaceHolders('menu_affiliate');
}



*/
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


$short = $_GET['short'];

$q = "select * from ".$prefix."products where pshort='$short'";
$r1 = $db->get_a_line($q);
$pid = $r1[id];
$click_bank_url = $r1[click_bank_url];
// Payment link
if($paypal_email == "")
{	
}else
{
	$affiliate_label = '<strong>PayPal Affiliate Link: </strong>';
	//$affiliate_link  = "<a href='".$http_path."/referrer/".$username."/". $r1['pshort']."/paypal' target='_blank'>Click Here</a>";
	$affiliate_link  = $http_path."/referrer/".$username."/". $r1['pshort']."/paypal";
}
if($alertpay_email == "")
{
}
else
{
	$alertpay_label = '<strong>Alert Pay Affiliate Link:</strong> ';
	//$affiliate_link_alertpay = "<a href='".$http_path."/referrer/".$username."/". $r1['pshort']."/alertpay' target=' _blank'>Click Here</a>";
	$affiliate_link_alertpay = $http_path."/referrer/".$username."/". $r1['pshort']."/alertpay";
}

if($r1['prodtype'] == 'Clickbank'){	
	if($clickbank_email == "")
	{
	}
	else
	{
		$clickbank_label = '<strong>Click Bank HopLink:</strong> ';
		//$clickbank_link = "<a href='http://".$clickbank_email.'.'.$click_bank_url.".hop.clickbank.net' target= '_blank'>Click Here</a>";
		$clickbank_link = $clickbank_email.'.'.$click_bank_url.".hop.clickbank.net";
	}
}
else $clickbank_link="";


$Selectedpagecontent = $Output[1];
$ToReplace = "" ;

$q="select * from ".$prefix."marketing_banners where product_id=$pid order by product_id";
$r=$db->get_rsltset($q);

for($i=0;$i<count($r);$i++)
	{
	@extract($r[$i]);
	$pshort= $r[i][pshort];
	
	// get product shortname 
	$q = "select * from ".$prefix."products where pshort='$short'";
	$r1 = $db->get_a_line($q);
	$pshort= $r1[pshort];
	$product_name = $r1[product_name];
	$Title = "Affiliate Tools for ".$product_name;
	
	if( $banner_url != '')
		{
		$bimage	= "<img src=".$banner_url." border=0>";
		if($pid == '1')
			{
			$bcode   = "<a href='".$http_prod_path."/referrer/".$username."/$short'><img src='".$banner_url."'></a>";
			}
		else
			{
			$bcode   = "<a href='".$http_prod_path."/referrer/".$username."/$short".$pshort."'><img src='".$banner_url."'></a>";
			}		
		}
	elseif( $banner_image != '')
		{
		$bimage	= "<img src=".$bannerimg_display_path.$banner_image." border=0>";
		
		if($pid == '1')
			{
			$bcode   = "<a href='".$http_prod_path."/referrer/".$username."/$short'><img src='".$bannerimg_display_path.$banner_image."'></a>";
			}
		else
			{
			$bcode   = "<a href='".$http_prod_path."/referrer/".$username."/".$pshort."'><img src='".$bannerimg_display_path.$banner_image."'></a>";
			}		
		}
		$BannerContent[$i]['bimage'] = $bimage;
		$BannerContent[$i]['bcode'] = stripslashes($bcode);
	
	}

	$smarty->assign('banners',$BannerContent);


	
$GetMembers = $db->get_rsltset("select * from ".$prefix."marketing_emails where product_id=$pid order by id asc");
for($i = 0 ; $i < count($GetMembers); $i++)
	{
	@extract($GetMembers[$i]);
	$sno		= $i+1;
	$bid		= $id;	
	$subject = stripslashes($subject);
	$message = stripslashes($message);
	
	// get product shortname 
	$q = "select * from ".$prefix."products where pshort='$short'";
	$r1 = $db->get_a_line($q);
	$pshort= $r1[pshort];
	$product_name = $r1[product_name];

	if($pid == '1')
		{
		$affiliatelink   = $http_prod_path."/referrer/".$username;
		}
	else
		{
		$affiliatelink   = $http_prod_path."/referrer/".$username."/".$pshort;
		}		
		
		$message = preg_replace("/{{(.*?)}}/e","$$1",$message);
	
		$messageContent[$i]['subject'] = $subject;
		$messageContent[$i]['message'] = stripslashes($message);
		$messageContent[$i]['pshort'] = $pshort;
		$messageContent[$i]['discription'] = stripslashes($product_name);
		$messageContent[$i]['affiliate_link'] = $affiliatelink;
		
	
	}	
	$smarty->assign('messages',$messageContent);
	
	// Payment links assigning
	
	$links = array($affiliate_label => $affiliate_link, $alertpay_label => $affiliate_link_alertpay, $clickbank_label => $clickbank_link);
	$smarty->assign('links',$links);	
		
	$output_affiliate_tools = $smarty->fetch('../html/member/affiliate_tools.html');
	

	$smarty->assign('pagename',$Title);
	$smarty->assign('main_content',$output_affiliate_tools);

	$output = $smarty->fetch('../html/member/content.tpl');


$mydownloads = $common->myDownloads($prefix,$db,$memberid);
$new_products = $common->newProducts($prefix,$db,$memberid);
/************************************************************************************/
$smarty->assign('time_release_content',$time_release_content);
$smarty->assign('my_downloads',$mydownloads);
$smarty->assign('new_products',$new_products);
$right_panel = $smarty->fetch('../html/member/right_panel.tpl');

/************************************************************************************/
$objTpl = new TPLManager($FILEPATH.'/index.html');
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