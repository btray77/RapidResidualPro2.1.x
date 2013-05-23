<?php
include ("include.php");
include_once("session.php");
$id = $memberid;

$q = "select * from ".$prefix."members where id='$memberid'";
$r = $db->get_a_line($q);
@extract($r);
$firstname 	= $r[firstname];
$username	= $r[username];
$status		= $r[status];
 // Get index page content
/*	if($status == '1')
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

if (isset($_POST['submit']))
{
	// Parse form data
	$firstname 			= $_POST["firstname"];
	$lastname 			= $_POST["lastname"];
	$email 				= $_POST["email"];
	$paypal_email		= $_POST["paypal_email"];
	$alertpay_email		= $_POST["alertpay_email"];
	$alertpay_ipn_code	= $_POST["alertpay_ipn_code"];
	$clickbank_email	= $_POST["clickbank_email"];
	$address_street		= $_POST["address_street"];	
	$address_city		= $_POST["address_city"];		
	$address_state		= $_POST["address_state"];	
	$address_zipcode	= $_POST["address_zipcode"];	
	$address_country	= $_POST["address_country"];
	$telephone			= $_POST["telephone"];	
	$skypeid			= $_POST["skypeid"];		
	
	// Save to databaase
	$set	.= " firstname={$db->quote($firstname)}";
	$set	.= ", lastname={$db->quote($lastname)}";
	$set	.= ", email={$db->quote($email)}";	
	$set	.= ", address_street={$db->quote($address_street)}";	
	$set	.= ", address_city={$db->quote($address_city)}";	
	$set	.= ", address_state={$db->quote($address_state)}";	
	$set	.= ", address_zipcode={$db->quote($address_zipcode)}";	
	$set	.= ", address_country={$db->quote($address_country)}";
	$set	.= ", skypeid={$db->quote($skypeid)}";	
	$set	.= ", telephone={$db->quote($telephone)}";
	$set	.= ", alertpay_email={$db->quote($alertpay_email)}";
	$set	.= ", alertpay_ipn_code={$db->quote($alertpay_ipn_code)}";
	$set	.= ", clickbank_email={$db->quote($clickbank_email)}";		
	$set	.= ", paypal_email={$db->quote($paypal_email)}";
	$sql = "update ".$prefix."members set $set where id = '$memberid'";
	$db->insert($sql);
	
	$Message = "<div class='success'><img src='/images/tick.png' border='0' align='absmiddle'>
		 $firstname, Your profile is successfully updated.</div>";
	}

// Get site settings
	$query_settings = "select * from ".$prefix."site_settings where id='1'";
	$row_settings = $db->get_a_line($query_settings);
	$paypal_enable = $row_settings['paypal_enable'];
	$alertpay_enable = $row_settings['alertpay_enable'];
	
// Get the member profile information

$smarty->assign('act',$act);
$smarty->assign('id',$memberid);

$smarty->assign('firstname',$firstname);
$smarty->assign('lastname',$lastname);
$smarty->assign('email',$email);
$smarty->assign('paypal_email',$paypal_email);
$smarty->assign('paypal_enable',$paypal_enable);
$smarty->assign('alertpay_email',$alertpay_email);
$smarty->assign('alertpay_ipn_code',$alertpay_ipn_code);
$smarty->assign('alertpay_enable',$alertpay_enable);
$smarty->assign('clickbank_email',$clickbank_email);
$smarty->assign('address_street',$address_street);
$smarty->assign('address_city',$address_city);
$smarty->assign('address_state',$address_state);
$smarty->assign('address_zipcode',$address_zipcode);
$smarty->assign('address_country',$address_country);
$smarty->assign('telephone',$telephone);
$smarty->assign('skypeid',$skypeid);
$smarty->assign('message', $Message);
$output_login = $smarty->fetch('../html/member/profile.tpl');

$smarty->assign('pagename',$firstname.'\'s Profile');
$smarty->assign('main_content',$output_login);
$output = $smarty->fetch('../html/member/content.tpl');
	/************************************************************************************/
	if(!empty($tcontent1)){
		
		$time_release_content = $common->getTimeRelaseContent($prefix,$db,$tcontent1,$difference);
	}
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