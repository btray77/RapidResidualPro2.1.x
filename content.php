<?php
include_once("common/config.php");
include_once ("include.php");
include_once("common/placeholder.class.php");
date_default_timezone_set('CDT');
$today = date('Y-m-d h:i:s');
/********************************************************************/
$social_media = $common->get_social_media($db, $prefix); // SOCIAL MEDIA
/*********************************************************************/
/********************************************************************************************
// Save Comments Opertaions
/********************************************************************************************/
$errors=$common->show_error($error);
if (isset($_REQUEST['Submit']))
{
	if( $_SESSION['security_code'] == $_POST['captchastring'] && !empty($_SESSION['security_code'] ) ) {
		unset($_SESSION['security_code']);
		unset($_SESSION['display_name']);
		unset($_SESSION['display_url']);
		unset($_SESSION['comment']);
	}
	else{
		$_SESSION['display_name']= $_REQUEST["display_name"];
		$_SESSION['display_url']= $_REQUEST["display_url"];
		$_SESSION['comment']= stripslashes($_REQUEST["comment"]);
		$call = $http_path."content.php?page=$page&error=8#comm_pos";
		header("Location: ".$call);
		exit;
	}
	$display_name 	=  $db->quote($_REQUEST["display_name"]);
	$display_url 	= $_REQUEST['display_url'];
	$comment		=  $_REQUEST["comment"];
	$page			=  $_REQUEST["page"];
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
// POST CONTACT FORM VALUE
/***************************************************************************************************/
if(isset($_POST['submit_contact'])){
	if( $_SESSION['security_code'] == $_POST['captchastring'] && !empty($_SESSION['security_code'] ) ) {
            $SQL="select sitename, email_from_name from " . $prefix . "site_settings";
			// $SQL="SELECT webmaster_email FROM ".$prefix."admin_settings WHERE role = 1;";
			$admin_settings = $db->get_a_line($SQL);
            @extract($admin_settings);
			$to=$email_from_name;
			
			$headers = 'From: '. $_REQUEST['name']. "< ". $_REQUEST['email'] .">\r\n" .
        	 	       'Reply-To: '.$_REQUEST['email']."\r\n" .
            		   'X-Mailer: PHP/' . phpversion();
			$headers  .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";	   
			$subject= stripslashes($_REQUEST["subject"]);
    	    $message ="<p>From Contact Us form on $sitename:</p>";
			$message .= nl2br(stripslashes($_REQUEST["message"]));
                
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
if($w['asign_template']=="default"){}
else if($w['asign_template']=='none'){$FILEPATH="";}
else {	$FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/". $w['asign_template'] ."";}
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
/*********************************************************************************************/
$smarty->assign('action',$_SERVER[REQUEST_URI]);
$smarty->assign('error',$error);
$smarty->assign('name',$name);
$smarty->assign('email',$email);
$smarty->assign('subject',$subject);
$smarty->assign('message',$message);
$contact_form= $smarty->fetch('html/contact-us.tpl');
/********************************************************************************************/
$main_content =   preg_replace ("/\{\{(.*?)\}\}/e", "$$1", $main_content);
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
	$smarty->assign('error',$errors);
	$output_comment = $smarty->fetch('html/comments.tpl');
	
	$smarty->assign('social_media',$social_media);
	$smarty->assign('pagename',$pagename);
	$smarty->assign('main_content',$main_content);
	$smarty->assign('comments',$output_comment);
	$output = $smarty->fetch('html/content.tpl');
} // end of comment check
else {
	$smarty->assign('social_media',$social_media);
	$smarty->assign('pagename',$pagename);
	
	$smarty->assign('main_content',$main_content);
	$output = $smarty->fetch('html/content.tpl');
}
$objTpl = new TPLManager($FILEPATH.'/index.html');
$hotspots = $objTpl->getHotspotList();
$placeHolders = $objTpl->getPlaceHolders($hotspots,$pagename);
$i=0;
foreach($placeHolders as  $items)
{
	if($hotspots[$i] == 'settings_keywords' && !empty($keyword))
	{ 
		$items=$keyword;
	}
	if($hotspots[$i] == 'settings_description' && !empty($settings_description))
	{ 
		$items=$settings_description;
	}
	$smarty->assign("$hotspots[$i]","$items");
	$i++;
}
$signup_form = $smarty->fetch('html/signup.tpl');
$smarty->assign('content',$output);
$smarty->assign('error',$errors);
$smarty->display($FILEPATH.'/index.html');
?>