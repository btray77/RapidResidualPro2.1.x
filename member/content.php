<?php
session_start();
include ("include.php");
include_once("session.php");
$errors=$common->show_error($error);
date_default_timezone_set('CDT');

// Get the member user id
$q = "select * from ".$prefix."members where id='$memberid'";
$r = $db->get_a_line($q);
@extract($r);
$firstname 		= $r[firstname];
$username		= $r[username];
$status			= $r[status];

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





/********************************************************************************************/
// Save Comments Opertaions
/********************************************************************************************/
if (isset($_REQUEST['Submit']))
{

	if( $_SESSION['security_code'] == $_POST['captchastring'] && !empty($_SESSION['security_code'] ) ) {
		unset($_SESSION['security_code']);
		unset($_SESSION['display_name']);
		unset($_SESSION['display_url']);
		unset($_SESSION['comment']);
	}else{
		$_SESSION['display_name']= $_REQUEST["display_name"];
		$_SESSION['display_url']= $_REQUEST["display_url"];
		$_SESSION['comment']= stripslashes($_REQUEST["comment"]);
		$call = $http_path."/member/content.php?page=$page&error=8#comm_pos";
		header("Location: ".$call);
		exit;
	}

	$display_name 		=  $db->quote($_REQUEST["display_name"]);
	$display_url = $_REQUEST['display_url'];
	$comment	=  $_REQUEST["comment"];
	$page		=  $_REQUEST["page"];

	preg_match( '@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s+charset=([^\s"]+))?@i',    $comment, $matches );$encoding = $matches[3];
	$comment = iconv( $encoding, "utf-8", $comment );
	$comment = $common->strip_html_tags( $comment );
	$comment = html_entity_decode( $comment, ENT_QUOTES, "UTF-8" );

	if($display_url == 'Enter your website address')
	{
		$display_url = "";
	}

	// Enter into database
	$set = "display_name  = {$display_name},";
	$set .= "display_url  = {$db->quote($display_url)},";
	$set .= "comment  = {$db->quote($comment)},";
	$set .= "date  = '$today',";
	$set .= "type  = 'content',";
	$set .= "page  = '$page'";
	$mid = $db->insert_data_id("insert into ".$prefix."comments set $set");
}

/***************************************************************************************************/
// Get page content
/***************************************************************************************************/

/***************************************************************************************************/
// POST CONTACT FORM VALUE
/***************************************************************************************************/
if(isset($_POST['submit_contact'])){
	if( $_SESSION['security_code'] == $_POST['captchastring'] && !empty($_SESSION['security_code'] ) ) {
            $SQL="SELECT webmaster_email FROM ".$prefix."admin_settings WHERE role = 1;";
            $admin_settings = $db->get_a_line($SQL);
            @extract($admin_settings);
		$to=$webmaster_email;
		$headers = 'From: '. $_REQUEST['name']. "< ". $_REQUEST['email'] .">\r\n" .
                'Reply-To: '.$_REQUEST['email']."\r\n" .
                'X-Mailer: PHP/' . phpversion();
		$subject= stripslashes($_REQUEST["subject"]);
        $message ="From Contact Us form on $http_path:\n";
		$message .= stripslashes($_REQUEST["message"]);
                
		if(mail($to,$subject,$message,$headers))
			$error='<div class="success" style="width:90%;float:left;clear:both">Thankyou your request is successfully posted. We will contact you soon.</div>';
		else 
			$error='<div class="error" style="width:90%;float:left;clear:both">Sorry we unble to send an email. Please try again.</div>';
		$name=''; $subject=''; $message=''; $email='';

	}
	else{
	  
		$name =  $_REQUEST["name"];
		$email = $_REQUEST["email"];
		$subject =  $_REQUEST["subject"];
		$message =  $_REQUEST["message"];
	 	$error='<div class="error" style="width:90%;float:left;clear:both">Invalid security code please try again.</div>';
		 
	}
}
/***************************************************************************************************/
// Get page content
/***************************************************************************************************/


$q = "select * from ".$prefix."pages where filename='$page'" ;
$w = $db->get_a_line($q);
@extract($w);
$main_content		= stripslashes($w["pcontent"]);
$pagename	= $w["pagename"];
$content_page_name = " - ".$pagename;
$linkproduct	= $w["linkproduct"];
$width	= $w["width"];
$commentcheck = $w["comments"];
$showurl	= $w["showurl"];
$nofollow	= $w["nofollow"];
$keyword = $w["keywords"];
$settings_description = $w["description"];
$assign_template=$w['asign_template'];

if($assign_template=="default"){}
else if($w['asign_template']=='none'){$FILEPATH="";}
else {	$FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/". $w['asign_template'] ."";}

/****************************************************************************************/
$flag=0;
	if($linkproduct == "All Members In Members Area")
	{
	// Access Allowed for All Members	
	$flag=1;
	}
	elseif($linkproduct == "Site Root Page")
	{
	// Access Allowed for All Members	
	$flag=1;
	}
	elseif($linkproduct == "All Paid Product Members")
	{
	// Access restricted to Paid Members	
	// Check to see if member has at least one paid product that hasn't been refunded.	
	$q="select count(*) as cnt from ".$prefix."member_products where refunded='0' AND type='paid' AND member_id = '$memberid'";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count < 1)
		{
		$main_content = "<div class='error'>This content available to members who have purchased paid products only.</div>";	
		$flag=1;
		}	
	}

	elseif($linkproduct == "All Free Product Members")
	{
	// Access restricted to Free Members	
	// Check to see if member has at least one free product.	
	$q="select count(*) as cnt from ".$prefix."member_products where type='free' AND member_id = '$memberid'";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count < 1)
		{
		$main_content = "<div class='error'>This content available to members who have free products only.</div>";	
		$flag=1;
		}	
	}	
		
	elseif($linkproduct == "All OTO Product Members")
	{
	// Access restricted to OTO Members	
	// Check to see if member has at least one oto product that hasn't been refunded.	
	$q="select count(*) as cnt from ".$prefix."member_products where refunded='0' AND type='oto' AND member_id = '$memberid'";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count < 1)
		{
		$main_content = "<div class='error'>This content available to members who have One Time Offer products only.</div>";	
		$flag=1;
		}	
	}
		
	elseif($linkproduct != "Legal")
	{
	
	$q = "select * from ".$prefix."products where pshort='$linkproduct'" ;
	$aa = $db->get_a_line($q);
	@extract($aa);
	$pid = $aa['id'];
	
	$q="select count(*) as cnt from ".$prefix."member_products where refunded='0' AND product_id='$pid' AND member_id = '$memberid'";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count < 1)
		{
		$main_content = "<div class='error'>This content available to members who have purchased specific products only.</div>";
		$flag=1;	
		}	
	}	
/*********************************************************************************************/
$smarty->assign('action',$_SERVER[REQUEST_URI]);
$smarty->assign('error',$error);
$smarty->assign('name',$name);
$smarty->assign('email',$email);
$smarty->assign('subject',$subject);
$smarty->assign('message',$message);
$contact_form= $smarty->fetch('../html/contact-us.tpl');
/**************************************************************************************************/

$tokens =$common->getTextBetweenTags($main_content);
foreach($tokens as $token)
{

	$temp =	explode('_',$token);
	if(count($temp)==3)
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
$flag;
/**********************************************************************************/
$main_content = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $main_content);
// $commentcheck == 'yes' && $flag == 0  commented
if($commentcheck == 'yes')
{
	$comments=$common->show_comments($prefix,$db,$page,'content');
	if(count($comments)){
		$smarty->assign('comments',$comments);
	}else{
		$out_comments .= '<div class="comment">No comments found</div>';
		$smarty->assign('comments',0);
	}
	// Getting max id starts
	$qry_max_comment = "SELECT MAX(id) as id FROM ".$prefix."comments where published=1 ORDER BY id";
	$row_max_comment = $db->get_a_line($qry_max_comment);
	$max_comment_id = $row_max_comment['id'] + 1;
	$smarty->assign('max_id', $max_comment_id);
	// Getting max id ends
	$smarty->assign('ptype','content');
	$smarty->assign('page',$page);
	$smarty->assign('display_name',$_SESSION['display_name']);
	$smarty->assign('display_url',$_SESSION['display_url']);
	$smarty->assign('post_comments',$_SESSION['comment']);
	$smarty->assign('content',$output);
	$smarty->assign('error',$errors);
	$output_comment = $smarty->fetch('../html/member/comments.tpl');

	$smarty->assign('pagename',$pagename);
	$smarty->assign('main_content',$main_content);
	$smarty->assign('comments',$output_comment);
	$output = $smarty->fetch('../html/member/content.tpl');
} // end of comment check
else {
	$smarty->assign('pagename',$pagename);
	$smarty->assign('main_content',$main_content);

	$output = $smarty->fetch('../html/member/content.tpl');
}



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
	if($hotspots[$i] == 'settings_keywords')
	{ 
		$items=$keyword;
	}
	if($hotspots[$i] == 'settings_description')
	{ 
		$items=$settings_description;
	}
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