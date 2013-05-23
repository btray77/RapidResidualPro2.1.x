<?php session_start();
$error='';
include_once("include.php");
include_once("session.php");
$pid = $_GET['pid'];

/* * ********************************************************************** */
$q = "select * from " . $prefix . "members where id='$memberid'";
$r = $db->get_a_line($q);
@extract($r);
$firstname = $r['firstname'];
$username = $r['username'];
$status = $r['status'];
$telephone = $r['telephone'];

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


$q = "select count(*) as cnt from " . $prefix . "member_products where member_id='$memberid' && product_id='$pid'";
$r = $db->get_a_line($q);
if ($r[cnt] == 0) {
    $pagecontent = "<table><tr><td><br> You are not allowed to access this product without purchasing.<br><br><br></td></tr></table>";
} else {

    // get paid download page info from database
    $q = "select * from " . $prefix . "products where id='$pid'";
    $v = $db->get_a_line($q);
	
// end

	
	$short = $v['pshort'];
     $pagecontent = stripslashes($v['download_form']);
    $tcontent1 = $v['tcontent'];

    $q = "select * from " . $prefix . "tccampaign where shortname='$tcontent1'";
    $r22 = $db->get_a_line($q);
    @extract($r22);
    $longname = $r22['longname'];

    if ($tcontent1 != '0' && $pid != '1') {


        $q = "select count(*) as cnt from " . $prefix . "timed_content where campaign='$tcontent1'";
        $r = $db->get_a_line($q);
        $count = $r[cnt];

        $q = "select * from " . $prefix . "member_products where member_id='$memberid' && product_id='$pid'";
        $r2 = $db->get_a_line($q);
        @extract($r2);
        $date_added = $r2['date_added'];
        $refunded = $r2['refunded'];
        $today = date('l F jS, Y');
        $difference = (strtotime($today) - strtotime($date_added)) / (60 * 60 * 24);
        $ToReplace = "";



        if ($refunded != '0') {
            header("Location: error.php?error=4");
            exit;
        }
    }
}
/* * ********************************************************************** */

$tokens = $common->getTextBetweenTags($pagecontent);
foreach ($tokens as $token) {
    $temp = explode('_', $token);
    if (count($temp) == 3) {

        switch ($temp[0]) {
            case 'video':
                $$token = $common->getmedia('video', $temp[2], $db, $prefix);
                break;
            case 'audio':
                $$token = $common->getmedia('audio', $temp[2], $db, $prefix);
                break;
            case 'file':
                $$token = $common->getmedia('file', $temp[2], $db, $prefix);
                break;
        }
    }
}
/* * ************************************************************************** */
/*if (!empty($firstname))   $firstname = '"$firstname"'; else $firstname = $_SESSION['license']['firstname'];
if (empty($lastname))   $lastname = '""$lastname""'; else $lastname = $_SESSION['license']['lastname'];
if (empty($email))   $email = '""$email""'; else $email = $_SESSION['license']['email'];
if (empty($phone))   $phone = '""$phone""'; else $phone = $_SESSION['license']['phone'];
*/


if (!empty($_SESSION['error'])) {   $message = $_SESSION['error'];}
if (!empty($_SESSION['success'])) {   unset($_SESSION['license']); unset($_SESSION['domains']); $message = $_SESSION['success'];
}

if (!empty($tcontent1)) {
    $difference = $common->time_release_difference($prefix, $db, $pid, $memberid);
    $time_release_content = $common->getTimeRelaseContent($prefix, $db, $tcontent1, $difference);
}
$mydownloads = $common->myDownloads($prefix, $db, $memberid);
$new_products = $common->newProducts($prefix, $db, $memberid);

$_SESSION['error'] = ''; $_SESSION['success'] = '';

$pagecontent = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $pagecontent);

$smarty->assign('pagename', $v['product_name']);
$smarty->assign('errorx', $message);
$smarty->assign('main_content', $pagecontent);

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
$smarty->assign('sidebar',$right_panel);
$smarty->assign('error', $Message);
$smarty->assign('content', $output);
$smarty->display($FILEPATH . '/index.html');


?>