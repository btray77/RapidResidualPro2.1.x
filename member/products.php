<?php

include ("include.php");
include_once("session.php");

$short = $_GET['short'];
$ip	= $_SERVER['REMOTE_ADDR'];
/* * ********************************************************************** */
$q = "select * from " . $prefix . "members where id='$memberid'";
$r = $db->get_a_line($q);
@extract($r);
$firstname = $r[firstname];
$username = $r[username];
$status = $r[status];

if ($status == '1') {
    $sql_page = "select affiliate_main as member_main, affiliate_menu_id from " . $prefix . "misc_pages";
    $row_page = $db->get_a_line($sql_page);
    // Getting menu alias
    $qry_menu_alias = "select * from " . $prefix . "menus where id = '" . $row_page['affiliate_menu_id'] . "'";
    $row_menu_alias = $db->get_a_line($qry_menu_alias);
    $menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
} elseif ($status == '2') {
    $sql_page = "select member_main as member_main, member_menu_id from " . $prefix . "misc_pages";
    $row_page = $db->get_a_line($sql_page);
    // Getting menu alias
    $qry_menu_alias = "select * from " . $prefix . "menus where id = '" . $row_page['member_menu_id'] . "'";
    $row_menu_alias = $db->get_a_line($qry_menu_alias);
    $menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
} elseif ($status == '3') {
    $sql_page = "select jv_main as member_main, jv_menu_id from " . $prefix . "misc_pages";
    $row_page = $db->get_a_line($sql_page);
    // Getting menu alias
    $qry_menu_alias = "select * from " . $prefix . "menus where id = '" . $row_page['jv_menu_id'] . "'";
    $row_menu_alias = $db->get_a_line($qry_menu_alias);
    $menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}

/* * ********************************************************************* */
//$FILEPATH = $_SERVER['DOCUMENT_ROOT'] . "/templates/" . memberarea . "";


/***************************************************************************************/
/*                          Create Veiwes
/**************************************************************************************/

$counter->setcounter('',$short,$ip,$ref);
/***************************************************************************************/
// get product page info from database
$q = "select * from " . $prefix . "products where pshort='$short'";
$v = $db->get_a_line($q);

$product_name = $v['product_name'];
$pid = $v['id'];
$short = $v['pshort'];
$prod_description = stripslashes(strip_tags($v['prod_description']));
$image = $v['imageurl'];
$template = $v['template'];
$tcontent1 = $v['tcontent'];

if ($template == "default")
    $target = "";
else
    $target='target="_blank"';


if ($image == '') {
    $product_image = '';
} else {
    $product_image = '<img src="' . $v['imageurl'] . '" border="0"  >';
}
$link = '<a href="sales.php?short=' . $short . '#top" ' . $target . '>Click Here For Details...</a>';


if (!empty($tcontent1)) {
    $difference = $common->time_release_difference($prefix, $db, $pid, $memberid);
    $time_release_content = $common->getTimeRelaseContent($prefix, $db, $tcontent1, $difference);
}
$mydownloads = $common->myDownloads($prefix, $db, $memberid);
$new_products = $common->newProducts($prefix, $db, $memberid);

/* * ******************************************************************************** */
$smarty->assign('description', $prod_description);
$smarty->assign('image', $product_image);
$smarty->assign('link', $link);
$product = $smarty->fetch('../html/member/product.tpl');


$smarty->assign('pagename', $product_name);
$smarty->assign('main_content', $product);


$output = $smarty->fetch('../html/member/content.tpl');
/* * ********************************************************************************* */
$smarty->assign('time_release_content', $time_release_content);
$smarty->assign('my_downloads', $mydownloads);
$smarty->assign('new_products', $new_products);
$right_panel = $smarty->fetch('../html/member/right_panel.tpl');

/* * ********************************************************************************* */
$objTpl = new TPLManager($FILEPATH . '/index.html');
$hotspots = $objTpl->getHotspotList();
$placeHolders = $objTpl->getPlaceHolders($hotspots);
$i = 0;
foreach ($placeHolders as $items) {
    $smarty->assign("$hotspots[$i]", "$items");
    $i++;
}

$smarty->assign("menus", $menus);
$smarty->assign('current_date', $today);
$smarty->assign('right_panel', $right_panel);
$smarty->assign('error', $Message);
$smarty->assign('content', $output);
$smarty->display($FILEPATH . '/index.html');
?>