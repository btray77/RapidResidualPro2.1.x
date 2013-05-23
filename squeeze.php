<?php
include_once("common/config.php");
include ("include.php");
include_once("common/placeholder.class.php");
error_reporting(E_ERROR);
	
/********************************************************************/
$site_name = $common->get_site_name($db, $prefix);  // SITE NAME FOR SEO
/********************************************************************/
$social_media = $common->get_social_media($db, $prefix); // SOCIAL MEDIA
/*********************************************************************/
$ref   = $_GET['ref'];
if ($ref == "")
	{
	$ref=$_COOKIE[rapidresidualpro];
	}
elseif ($ref != "")
	{
		
		// Apply site settings checks on cookies
		$qry = "select cookie_mode, cookie_expiry from ".$prefix."site_settings where id='1'";
		$row = $db->get_a_line($qry);
		$cookie_mode = $row['cookie_mode'];
		$cookie_expiry = $row['cookie_expiry'];
		// Apply site settings checks on cookies
		if($cookie_mode == 'last'){
			setcookie("rapidresidualpro", $ref, time()+3600*24*$cookie_expiry, "", $_SERVER['HTTP_HOST'], 0);
		}else{
			if(isset($_COOKIE['rapidresidualpro'])){
			}else{
				setcookie("rapidresidualpro", $ref, time()+3600*24*$cookie_expiry, "", $_SERVER['HTTP_HOST'], 0);
			}
		}
		
		//setcookie("rapidresidualpro", $ref, time() + 365*24*60*60, "", $_SERVER['HTTP_HOST'], 0);
	}

date_default_timezone_set('CDT');
$today = date('Y-m-d h:i:s');
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
	  $call = $http_path."squeeze.php?page=$page&error=8#comm_pos";
	  header("Location: ".$call);
	  exit;
	 }
	 
		
			
	// Clean up form data
	$display_name 		=  addslashes($_REQUEST["display_name"]);	
	$comment	=  addslashes($_REQUEST["comment"]);
	$page		=  $_REQUEST["page"];

	
	// Enter into database	
	$set = "display_name  =' $display_name',";
	$set .= "display_url  ='$display_url',";
	$set .= "comment  =' $comment',";
	$set .= "date  = '$today',";
	$set .= "type  = 'squeeze',";	
	$set .= "page  = '$page';";	
	$mid = $db->insert_data_id("insert into ".$prefix."comments set $set");	
	}

// Get page content
$q = "select * from ".$prefix."squeeze_pages where name='$page'";
$w = $db->get_a_line($q);
@extract($r);
$name =  stripslashes($w["name"]);
$pcontent		=  stripslashes($w["squeezepage"]);
$keyword		=  stripslashes($w["keyword"]);
$meta_discription = stripslashes($w["meta_discription"]);

/***********************	CHECK EITHER PAGE IS PRIVATE OR PUBLIC	****************************************/ 
if($w['access']=='Private'){
	$hash=$_COOKIE["memcookie"];
	$memberid=$common->check_session($hash,$db);
	if($memberid=="")
		{
		if(empty($_SERVER['HTTP_REFERER']))
			$destionation = str_replace("$http_path//member/","",$_SERVER['REQUEST_URI']);
		else	
			$destionation = str_replace("$http_path//member/","",$_SERVER['HTTP_REFERER']);	
		header("Location: /member/login.php?destionation=$destionation");
		exit;
		}
	
}
$HTML_HEAD ='';
$HTML_END ='';
/***********************	LOAD TEMPLATE	****************************************/ 
if($w['asign_template']=="default"){

}

else if($w['asign_template']=='none' or empty($w['asign_template'])){$FILEPATH="";
$HTML_HEAD ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>'.$site_name.' - '.stripslashes($w["seo_title"]).'</title>
<script src="/common/newLayout/jquery/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="/admin/Editor/scripts/common/mediaelement/mediaelement-and-player.min.js" type="text/javascript"></script>
        <link href="/admin/Editor/scripts/common/mediaelement/mediaelementplayer.min.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript">
        $(document).ready(function () {
	 		$("audio,video").mediaelementplayer();
        }); 
	</script>

</head><body>';
$HTML_END='</body></html>';

}
else {	$FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/". $w['asign_template'] ."";}

/*************************************************************************************/

$commentcheck = $w["comments"];
$width= $w["width"];
// Do we have a referrer?
$ref   = $_GET['ref'];
if ($ref == '')
	{
	$ref=$_COOKIE[rapidresidualpro];
	}
elseif($ref != '')
	{
	
		// Apply site settings checks on cookies
		$qry = "select cookie_mode, cookie_expiry from ".$prefix."site_settings where id='1'";
		$row = $db->get_a_line($qry);
		$cookie_mode = $row['cookie_mode'];
		$cookie_expiry = $row['cookie_expiry'];
		// Apply site settings checks on cookies
		if($cookie_mode == 'last'){
			setcookie("rapidresidualpro", $ref, time()+3600*24*$cookie_expiry, "", $_SERVER['HTTP_HOST'], 0);
		}else{
			if(isset($_COOKIE['rapidresidualpro'])){
			}else{
				setcookie("rapidresidualpro", $ref, time()+3600*24*$cookie_expiry, "", $_SERVER['HTTP_HOST'], 0);
			}
		}
	
		//setcookie("rapidresidualpro", $ref, time() + 365*24*60*60, "", $_SERVER['HTTP_HOST'], 0);
	}

if($ref != '')
	{
	$q = "select count(*) as cnt from ".$prefix."members where username='$ref'";
	$r = $db->get_a_line($q);

	if($r[cnt] != 0)
		{
		$q = "select * from ".$prefix."members where username='$ref'";
		$r = $db->get_a_line($q);
		$firstname = $r['firstname'];
		$lastname = $r['lastname'];	
		$ref2 = $firstname." ".$lastname;
		$referred_by ="<p align=center class=tbtext><font color=gray>Referrer: ".$ref2."</font><br><br></p>";
		}
	}
if($commentcheck == 'yes')
	{
		
		$comments=$common->show_comments($prefix,$db,$page,'squeeze');
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
		$smarty->assign('ptype','squeeze');	
		$smarty->assign('page',$page);	
		$smarty->assign('display_name',$_SESSION['display_name']);	
		$smarty->assign('display_url',$_SESSION['display_url']);	
		$smarty->assign('post_comments',$_SESSION['comment']);	
		$smarty->assign('error',$errors);
		$output_comment = $smarty->fetch('html/comments.tpl');
	
		$smarty->assign('pagename',$pagename);
		$smarty->assign('main_content',$main_content);
		$smarty->assign('comments',$output_comment);
		$output = $smarty->fetch('html/content.tpl');
	}
	
// Do we have a referrer?
if($ref != '')
	{
	$q = "select count(*) as cnt from ".$prefix."members where username='$ref'";
	$r = $db->get_a_line($q);

	if($r[cnt] != 0)
		{
		$q = "select * from ".$prefix."members where username='$ref'";
		$r = $db->get_a_line($q);
		$firstname = $r['firstname'];
		$lastname = $r['lastname'];	
		$ref2 = $firstname." ".$lastname;
		$referred_by ="<p align=center class=tbtext><font color=gray>Referrer: ".$ref2."</font><br><br></p>";
		}
	}

include ('button.php');

 $tokens =$common->getTextBetweenTags($pcontent);
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
 				case 'clickbank':
                        $$token = $clickBank->button($temp[2], $pid, $ref, $rands);
                break;
 			}
 		}
 }

$pcontent =   preg_replace ("/\{\{(.*?)\}\}/e", "$$1", $pcontent);
$smarty->assign('pagename',$pagename);


$smarty->assign('HTMLhead',$HTML_HEAD);
$smarty->assign('HTMLfooter',$HTML_END);
$smarty->assign('main_content',$pcontent);
$smarty->assign('social_media',$social_media);
if(!empty($FILEPATH)){
$output = $smarty->fetch('html/content.tpl');	
$objTpl = new TPLManager($FILEPATH.'/index.html');
$hotspots = $objTpl->getHotspotList();
/*echo "<pre>";
print_r($hotspots);
echo "<pre>";*/

$placeHolders = $objTpl->getPlaceHolders($hotspots,stripslashes($w["seo_title"]));
$i=0;
/*echo "<pre>";
print_r($placeHolders);
echo "<pre>";*/
foreach($placeHolders as  $items)
{
	
	if($hotspots[$i] == 'settings_keywords' && !empty($keyword))
	{ 
		$items=$keyword;
	}
	if($hotspots[$i] == 'settings_description' && !empty($meta_discription))
	{
		$items=$meta_discription;
	}	
	$smarty->assign("$hotspots[$i]","$items");
	$i++;
}

$smarty->assign('content',$output);
$smarty->display($FILEPATH.'/index.html');
}
else {
$smarty->display('html/content.tpl');	
}

/*$Content = preg_replace("/{{(.*?)}}/e", "$$1", $pcontent);
$Content = preg_replace("/<{(.*?)}>/e","$$1",$Content);
$Content =   preg_replace ("/\[\[(.*?)\]\]/e", "$$1", $Content);*/

?>