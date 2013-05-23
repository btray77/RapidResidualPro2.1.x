<?php
include ("include.php");
include_once("session.php");
$today = date('Y-m-d');
$pshort = $_REQUEST['pshort'];

/*echo '<pre>';
print_r($_SESSION);
echo "<br>COOKIES<br>";
print_r($_COOKIE);
echo '<pre>';*/

$q = "select * from ".$prefix."products where pshort='$pshort'";
$r = $db->get_a_line($q);
@extract($r);
$otocheck 		= $r[otocheck];
$one_time_offer	= $r[one_time_offer];
if($otocheck == "yes")

{

	header("Location: oto.php?pshort=$pshort");

	exit;	

}

//}



// Get the member user id



$sql_men = "select * from ".$prefix."members where id='$memberid'";

$row_mem = $db->get_a_line($sql_men);

@extract($row_mem);

$firstname 		= $row_mem['firstname'];

$username		= $row_mem['username'];

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

	$member_main = $row_page[member_main];

	 

// Get the member user id

 $tokens =$common->getTextBetweenTags($member_main);

 foreach($tokens as $token)

 {

 	 	

 		$temp =	explode('_',$token);

 		if(count($temp) == 3)

 		{

 			

 			switch($temp[0]) 

 			{

 				case 'video':

 					$$token = 	$common->getmedia('video',$temp[2],$db,$prefix);

 				break;

 				case 'audio':

 					$$token =	$common->getmedia('audio',$temp[2],$db,$prefix);

 				break;

 				case 'file':

 					$$token =	$common->getmedia('file',$temp[2],$db,$prefix);

 				break;	

 			}

 		}

 }

	



$member_main = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $member_main);

$member_main	= stripslashes($member_main);

$member_main 		= str_replace('�', '&trade;', $member_main);

$member_main 		= str_replace('�', '&#169;', $member_main);



//$aff_link = $http_path."go/".$username;

if(empty($page))

{

	$smarty->assign('pagename','Member Section');

	$smarty->assign('main_content',$member_main);

}

else

{ 	

	include $page.'.php';

}

/************************************************************************************/



	if(!empty($tcontent1)){

		

		$difference=$common->time_release_difference($prefix,$db,$pid,$memberid);

		$time_release_content = $common->getTimeRelaseContent($prefix,$db,$tcontent1,$difference);

	}

	$mydownloads = $common->myDownloads($prefix,$db,$memberid); 

	$new_products = $common->newProducts($prefix,$db,$memberid); 

/************************************************************************************/

	$smarty->assign('time_release_content',$time_release_content);

	$smarty->assign('my_downloads',$mydownloads);

	$smarty->assign('new_products',$new_products);

	

	$right_panel = $smarty->fetch('../html/member/right_panel.tpl');

			

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

	$smarty->assign('username', $username);

	$smarty->assign('content',$output);

	$smarty->display($FILEPATH.'/index.html');		

?>