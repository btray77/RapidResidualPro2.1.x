<?php
// Include Files
	include_once("include.php");
	include_once("session.php");
	
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
$q = "select count(*) as cnt from  ".$prefix."members where ref = '".$username."'";
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

$pager=$common->pagiation_simple('affiliate_users.php',$limit,$count,$pageno,$start);
/***********************************************************************************/


$ChangeColor = 1;
$ToReplace = "";
/********************************* Main Query **************************************************/
	
$sql = "select *,CONCAT(firstname,' ',lastname) as name, DATEDIFF( NOW( ) , FROM_UNIXTIME( last_login ) ) AS DAYS
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


/************************************ Assigning inner content to template file ***********************************************/
$smarty->assign('data',$data);
$smarty->assign('i', '0');
$smarty->assign('targetpage', $targetpage);
$smarty->assign('Title', $Title);
$smarty->assign('pager',$pager);
$output_market = $smarty->fetch('../html/affiliate_users.tpl');
$smarty->assign('pagename','Referers');
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