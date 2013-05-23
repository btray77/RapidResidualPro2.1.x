<?php
// Include Files
	include_once("include.php");
	include_once("session.php");

// Checking payment emails	
$sql_aj = "select count(*) as emailcount from ".$prefix."members
				where id = '".$_SESSION['memberid']."'
				and (paypal_email != '' || alertpay_email != '' || clickbank_email != '')";
$row_aj = $db->get_a_line($sql_aj);
$cntPay = $row_aj['emailcount'];
$smarty->assign('cntPay', $cntPay);

/**************************** Getting member info and setting menu things ***********************************/
	$sql_men = "select * from ".$prefix."members where id='$_SESSION[memberid]'";

	$row_mem = $db->get_a_line($sql_men);
	@extract($row_mem);
	$status	= $row_mem['status'];
	$firstname 		= $row_mem['firstname'];
	$username		= $row_mem['username'];
	$status			= $row_mem['status'];

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

/********************************** Getting right panel content **************************************************/
$mydownloads = $common->myDownloads($prefix,$db,$_SESSION[memberid]);
$new_products = $common->newProducts($prefix,$db,$_SESSION[memberid]);
/************************************************************************************/
$smarty->assign('time_release_content',$time_release_content);
$smarty->assign('my_downloads',$mydownloads);
$smarty->assign('new_products',$new_products);

$right_panel = $smarty->fetch('../html/member/right_panel.tpl');
/***********************************************************************************/

/********************************** Pagination content here **************************************************/

$limit = 10;
$q = "select count(*) as cnt
		from  ".$prefix."members
		where ref = '".$username."'";
$r = $db->get_a_line($q);
$count = $r[cnt];
$total_pages = $count;
$smarty->assign('total_pages', $total_pages);
if($count == "0")
{
	$warning = "No Results Found";
}

if($pageno==""){$pageno=0;}
if($pageno)
$start = ($pageno - 1) * $limit; 			//first item to display on this page
else
$start = 0;

$pager=$common->pagiation_simple('affiliate_home.php',$limit,$count,$pageno,$start);
/***********************************************************************************/


$ChangeColor = 1;
$ToReplace = "";
/********************************* Main Query **************************************************/
	$sql = "select *,CONCAT(firstname,' ',lastname) as name, DATEDIFF( NOW( ) , FROM_UNIXTIME( last_login ) ) AS DAYS,
			(SELECT COUNT(id) FROM ".$prefix."click_stats WHERE referrer = ".$prefix."members.username) AS hits
			from  ".$prefix."members
			where ref = '".$username."'
			limit $start,$limit";

$reports = $db->get_rsltset($sql);
$row_username = $db->get_a_line($sql);
if(count($reports) > 0)
{
	$i=0;
	foreach($reports as $report){
		
		/***********************************************************************/
		$data[$i]['id'] = $report['id'];
		$data[$i]['name'] = stripslashes($report['name']);
		$data[$i]['email'] = $report['email'];
		$data[$i]['username'] = $report['username'];
		$data[$i]['hits'] = $report['hits'];
		if($report['DAYS'] == 0)
		{
			$data[$i]['DAYS'] = "Today";
		}else
		{
			$data[$i]['DAYS'] = $report['DAYS'].' day(s) ago';
		}
		
		$i++; 
	}
}

function days_in_month($month, $year)
{
	return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}
$month=date('m');
$year=date('Y');
$days=days_in_month($month,$year);

	$start_date=$year.'-'.$month . '-01';
	$end_date=$year.'-'.$month . '-' .$days;
	
	// Total Affiliates this month
	$sql_aff_mon = "SELECT count(id) as total FROM ".$prefix."members WHERE ref = '".$username."' and date_joined between '$start_date' and '$end_date'";
	$row_total= $db->get_a_line($sql_aff_mon);
	$smarty->assign('totalAffiliatemon', $row_total['total']);
	
	// All Affilaites
	$sql_aff_all = "select count(id) as total from ".$prefix."members where ref = '".$username."'";
	$row_total_aff_all = $db->get_a_line($sql_aff_all);
	$smarty->assign('allAffiliate', $row_total_aff_all['total']);
	
	// ALL CLICKS
	$row_total_clicks_today = $db->get_a_line("SELECT count(id) as total from rrp_click_stats where `referrer` ='$username' AND visited_date between '$start_date' and '$end_date'  AND item_type <> ''");
	$row_total_clicks_today = $row_total_clicks_today['total'];
	$smarty->assign('row_total_clicks_today', $row_total_clicks_today);
	
	$row_total_clicks_all = $db->get_a_line("SELECT count(id) as total from rrp_click_stats where `referrer` ='$username' AND item_type <> ''");
	 $row_total_clicks_all = $row_total_clicks_all['total'];
	 $smarty->assign('row_total_clicks_all', $row_total_clicks_all);
	// ALL VIEWS
	
	$row_total_views_today = $db->get_a_line("SELECT count(id) as total from rrp_click_stats where `referrer` ='$username' AND visited_date between '$start_date' and '$end_date'");
	 $row_total_views_today = $row_total_views_today['total'];
	 $smarty->assign('row_total_views_today', $row_total_views_today);
	 
	$row_total_views_all = $db->get_a_line("SELECT count(id) as total from rrp_click_stats where `referrer` ='$username'");
	 $row_total_views_all = $row_total_views_all['total'];
	 $smarty->assign('row_total_views_all', $row_total_views_all);
	 // This month product
	 $sql_thismonthprod = "select count(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m
							where m.paypal_email = o.payee_email
							AND referrer = '".$username."'
							AND o.date between '$start_date' and '$end_date'
							AND o.payment_status='Completed';";
	$row_thismonth = $db->get_a_line($sql_thismonthprod);
	if($row_thismonth['total']){
		$thismonthprod = $row_thismonth['total'];
	}else{
		$thismonthprod = '0';
	}
	$smarty->assign('thismonthprod', $thismonthprod);
	 
	// Accumulated product
	$sql_accumulatedprod = "select count(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m
							where m.paypal_email = o.payee_email
							AND referrer = '".$username."'
							AND o.payment_status='Completed';";
	$row_accumulated = $db->get_a_line($sql_accumulatedprod);
	if($row_accumulated['total']){
		$thismonthaccprod = $row_accumulated['total'];
	}else{
		$thismonthaccprod = '0';
	}
	$smarty->assign('thismonthaccprod', $thismonthaccprod);
	
	
	// Commission this month
	$sql_com_mon = "select SUM(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m 
			where m.paypal_email = o.payee_email
			AND referrer = '$username' 
			AND o.date between '$start_date' and '$end_date'
			AND o.payment_status='Completed';";
	$row_com_mon = $db->get_a_line($sql_com_mon);
	$com_mon = '$ '.number_format($row_com_mon['total'],2);
	$smarty->assign('com_mon', $com_mon);
		
	// All Commission
	$sql_com_all = "select SUM(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m 
					where m.paypal_email = o.payee_email
					AND referrer = '$username' 
					AND o.payment_status='Completed';";
	$row_com_all = $db->get_a_line($sql_com_all);
	$com_all = '$ '.number_format($row_com_all['total'],2);
	$smarty->assign('com_all', $com_all);	


/************************************ Assigning inner content to template file ***********************************************/
$smarty->assign('month', $month);
$smarty->assign('year', $year);
$smarty->assign('data',$data);
$smarty->assign('i', '0');
$smarty->assign('targetpage', $targetpage);
$smarty->assign('pager',$pager);
$output_market = $smarty->fetch('../html/affiliate_home.tpl');
$smarty->assign('pagename','My Reports');
$smarty->assign('main_content',$output_market);
$output = $smarty->fetch('../html/member/content.tpl');
/***********************************************************************************/

/************************************** Template code here *********************************************/
$objTpl = new TPLManager($FILEPATH.'/index.html');
$hotspots = $objTpl->getHotspotList();
$placeHolders = $objTpl->getPlaceHolders($hotspots);
$i=0;
foreach($placeHolders as  $items)
{
	$smarty->assign("$hotspots[$i]","$items");
	$i++;
}
/***********************************************************************************/

/************************************** Assigning everything to main template *********************************************/
$smarty->assign("menus",$menus);
$smarty->assign('current_date',$today);
$smarty->assign('right_panel',$right_panel);
$smarty->assign('sidebar',$right_panel);
$smarty->assign('error',$Message);
$smarty->assign('content',$output);
$smarty->display($FILEPATH.'/index.html');
/***********************************************************************************/
?>