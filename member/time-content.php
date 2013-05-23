<?php
session_start();
include ("include.php");
include_once("session.php");
$errors=$common->show_error($error);


function show_trcomments($prefix,$db,$page,$type, $filename)
{
	/* Question to check whether comments status is published or not*/	
	$sql_comment = "SELECT id, display_name, display_url, date, comment
				       FROM ".$prefix."comments
				       where type='$type'
				       and page='$page'
				       and filename = '$filename'
				       and published=1
				       ORDER BY id";   
	$row_comment = $db->get_rsltset($sql_comment);
	$out_comments = '';
	if(count($row_comment) > 0){
		// start of reply section
		foreach($row_comment as $cvalue){
				$out_comments .= '<div class="author"><a href="'.$cvalue['display_url'].'" target="_blank">'.$cvalue['display_name'].'</a></div><div class="date">'.$cvalue['date'].'</div><div class="comment">'.$cvalue['comment'].'</div><div class="url"><a href="'.$cvalue['display_url'].'" rel="nofollow">'.$cvalue['display_url'].'</a></div>';

				$sql_reply = "select * from " . $prefix . "comments_reply where comment_id = '" . $cvalue['id']."'";
				$row_reply = $db->get_rsltset($sql_reply);
				if(count($row_reply) > 0)
				{
					$comments[] = array('comment' => $cvalue, 'reply' => $row_reply);
					 
					foreach($row_reply as $reply)
					{
						$dateofcomment = date("F d, Y H:i a", strtotime($reply['postedon']));
						$out_comments .= '<div class="url" style="padding-left:20px;"><strong>Posted on: '.$dateofcomment . "</strong><br>".$reply['title'].'</div>';
					}
				}else{
					$comments[] = array('comment' => $cvalue);
					$dateofcomment = '';
					$out_comments .= '';
				}
				 
				 
			}
			// end of reply section
		}else{
			$out_comments .= '<div class="comment">No comments found</div>';
			$comments = 0;
		}

		return $comments;
	}
/********** IF comments form submitted then this section starts *******************************/
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
		$call = $http_path."/member/time-content.php?content=$content&tcontent1=".$_POST['campaign']."&error=8#comm_pos";
	   	header("Location: ".$call);
		exit;
   	}

	// Clean up form data
	$display_name =  $db->quote($_REQUEST["display_name"]);
	$display_url =  $db->quote(str_replace("http://", "", $_REQUEST["display_url"]));	
	$comment	=  $_REQUEST["comment"];	// Do quoting after all the stripping below
	$page		=  $_REQUEST["content"];
	
	preg_match( '@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s+charset=([^\s"]+))?@i',    $comment, $matches );$encoding = $matches[3];
	$comment = iconv( $encoding, "utf-8", $comment );
	$comment = $common->strip_html_tags( $comment );
	$comment = html_entity_decode( $comment, ENT_QUOTES, "UTF-8" ); 	
	
	if($display_url == 'Enter your website address')
		{
		$display_url = "";
		}	
	// Enter into database	
	
	$cmt = $db->quote($comment);	
	$cmt = str_replace('\r\n', '<br>', $cmt);
			
	$set = "display_name  = {$display_name},";
	$set .= "display_url  = {$display_url},";
	$set .= "comment  = {$cmt},";
	$set .= "date  = '$today',";
	$set .= "type  = 'trcontent',";	
	$set .= "filename = ".$db->quote($page).",";
	$set .= "page  = {$db->quote($_POST['campaign'])}";
	$sql = "insert into ".$prefix."comments set $set";
	
	$mid = $db->insert_data_id("insert into ".$prefix."comments set $set");
}	
/********** IF comments form submitted then this section ends *******************************/

/********************* To show comments with replys in template file *****************************/
if($pageno==""){$pageno=0;}
if($pageno)
$start = ($pageno - 1) * $limit; 			//first item to display on this page
else
$start = 0;	
$page = $content;
$trcampaign = $_GET['tcontent1'];
if($page)
{
	$sql_blogs = "SELECT * FROM ".$prefix."timed_content where filename='$page' and campaign = {$db->quote($trcampaign)} and published=1 ";
	$row_blogs = $db->get_a_line($sql_blogs);
	if(count($row_blogs))
	{
		// Calling function that contains comments/replys
		$comments=show_trcomments($prefix,$db,$trcampaign,'trcontent', $page);
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
			
		}// end of count if	
	
}


// Get the member user id
 $q = "select * from ".$prefix."members where id='$memberid'";
$r = $db->get_a_line($q);
@extract($r);
$firstname 		= $r[firstname];
$status 		= $r[status];

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

// get paid download page info from database

//$q = "select * from ".$prefix."timed_content where filename = '$content'";
$q = "select * from ".$prefix."timed_content where filename = '$content' and campaign = {$db->quote($trcampaign)}";
$v = $db->get_a_line($q);
@extract($v);
$pagename = $v['pagename'];	
$pcontent = $v['pcontent'];
$pcontent = stripslashes($pcontent);
$available = $v['available'];
$filename = $v['filename'];
$tr_comments = $v['comments'];
$campaign = trim(strtolower($_GET['tcontent1']));
if(is_numeric($_GET['pid']) && !empty($_GET['pid']))
{
	$pid = mysql_escape_string(trim($_GET['pid']));
}
else
{
$q = "select id from ".$prefix."products where tcontent = '$tcontent1'";
$v = $db->get_a_line($q);
$pid= $v['id'];
}



$difference=$common->time_release_difference($prefix,$db,$pid,$memberid);
// Does member has access to this page?
/*$q = "select * from ".$prefix."member_products where product_id='1' && member_id = '$memberid'";
$v = $db->get_a_line($q);
$date_added = $v['date_added'];	
$today = date('Y-m-d');
$difference = dateDiff($date_added, $today);
*/
//echo "$difference < $available";

if($difference < $available)
	{
	header("Location: error.php?error=4");
	exit;
	}

// display paid products page page
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
 			}
 		}
 }	
$pagecontent = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $pcontent);

// To check commenting allowed or not
/*$q_campn = "select * from ".$prefix."tccampaign where shortname = {$db->quote($trcampaign)}";
$v_campn = $db->get_a_line($q_campn);
@extract($v_campn);
$commentcheck = $v_campn["comments"];*/
if($tr_comments == 'yes'){
/************ zzz ********************/
// Getting max id starts
	$qry_max_comment = "SELECT MAX(id) as id FROM ".$prefix."comments where published=1 ORDER BY id";
	$row_max_comment = $db->get_a_line($qry_max_comment);	
	$max_comment_id = $row_max_comment['id'] + 1;	
	$smarty->assign('max_id', $max_comment_id);
// Getting max id ends	
	
	$smarty->assign('ptype','time-content');
	$smarty->assign('page', $filename);
	$smarty->assign('max_id', $max_comment_id);
	$smarty->assign('campaign', $campaign);
	$smarty->assign('display_name',$_SESSION['display_name']);	
	$smarty->assign('display_url',$_SESSION['display_url']);	
	$smarty->assign('post_comments',$_SESSION['comment']);	
	$smarty->assign('out_comments', $out_comments);
	$smarty->assign('error',$errors);
	
$output_comment = $smarty->fetch('../html/member/trcomments.tpl');
$smarty->assign('comments',$output_comment);
/************ zzz ********************/
}else{
	$smarty->assign('comments','');
}

$pagecontent = preg_replace("/[$]/","&#36;",$pagecontent);
$pagecontent = str_replace('�', '&trade;', $pagecontent);
$pagecontent = str_replace('�', '&#169;', $pagecontent);

$smarty->assign('pagename',	$pagename);
$smarty->assign('main_content',$pagecontent);
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
	$smarty->assign('content',$output);
	$smarty->display($FILEPATH.'/index.html');	


?>