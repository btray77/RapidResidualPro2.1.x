<?php
session_start();
include_once("common/config.php");
include_once("include.php");

$limit = 10;
$page_title ='Blog';
$settings_meta='';
/********************************************************************/
$sql="select name from ". $prefix ."template where default_blog=1;";
$row = $db->get_a_line($sql);
$template = $row['name'];
/*******************************************************************/
if(!empty($template)){
$FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/$template";
}
/********************************************************************/
$social_media = $common->get_social_media($db, $prefix); // SOCIAL MEDIA
/*********************************************************************/
$errors=$common->show_error($error);
date_default_timezone_set('CDT');
$today = date('Y-m-d h:i:s');

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
		$call = $http_path."/blog.php?page=$page&error=8#comm_pos";
	   	header("Location: ".$call);
		exit;
   	}

	// Clean up form data
	$display_name 		=  $db->quote($_REQUEST["display_name"]);
	$display_url		=  $db->quote($_REQUEST["display_url"]);	
	$comment	=  $_REQUEST["comment"];	// Do quoting after all the stripping below
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
	
	$cmt = $db->quote($comment);	
	$cmt = str_replace('\r\n', '<br>', $cmt);
			
	$set = "display_name  = {$display_name},";
	$set .= "display_url  = {$display_url},";
	$set .= "comment  = {$cmt},";
	$set .= "date  = '$today',";
	$set .= "type  = 'blog',";	
	$set .= "page  = {$db->quote($page)}";
	$sql = "insert into ".$prefix."comments set $set";
	
	$mid = $db->insert_data_id("insert into ".$prefix."comments set $set");
	}		
/**************************************************************************************/

if($pageno==""){$pageno=0;}
if($pageno)
$start = ($pageno - 1) * $limit; 			//first item to display on this page
else
$start = 0;	
	
if($archive)
{
$start_date 	= $archive.'-1';
$end_date 	= $archive.'-31';
$sql_count ="select count(pageid) as total from ".$prefix."pages where date_added between '$start_date' and '$end_date' and type = 'blog' 
and published=1";
$row_total = $db->get_a_line($sql_count);

$sql_blogs = "select pageid,filename,pagename,date_added,description from ".$prefix."pages where 
date_added between '$start_date' and '$end_date' and type = 'blog' and published=1 order by pageid DESC limit $start,$limit";

$pager=$common->pagiation_simple('blog.php?archive',$limit,$row_total['total'],$pageno,$start,$archive);
}
else if($page)
{
	$sql_blogs = "SELECT pageid,filename,pagename,date_added,pcontent,comments,keywords,description FROM ".$prefix."pages where filename='$page' and published=1 ";
	$smarty->assign('blogs_detail','1');
}
else 
{	
	$sql_count ="select count(pageid) as total from ".$prefix."pages where type = 'blog' and published=1";
	$row_total = $db->get_a_line($sql_count);	
	
	$sql_blogs = "SELECT pageid,filename,pagename,date_added,comments,description,keywords FROM ".$prefix."pages where `type`='blog' and published=1
	  ORDER BY date_added DESC LIMIT $start,$limit";

	$pager=$common->pagiation_simple('blog.php',$limit,$row_total['total'],$pageno,$start,'');
}
	if($page){   
		$row_blogs = $db->get_a_line($sql_blogs);
		$page_title = $row_blogs['pagename'];
	}else
	{
		$row_blogs = $db->get_rsltset($sql_blogs);
		//$page_title = $row_blogs['pagename'];
	}
if($page){

	if(count($row_blogs))
		{
		
		$comments = $common->show_comments($prefix,$db,$page,'blog');
		
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
			
/*******************************************************************************/


 $tokens =$common->getTextBetweenTags($row_blogs['pcontent']);

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
                        $$token = $clickBank->button($temp[2], $pid, $ref, $rand);
                break;
 				
 			}
 		}
 }
 
/********************************************************************************/
				
				$page_content = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $row_blogs['pcontent']);
				$smarty->assign('ptype','blog');	
			 	$smarty->assign('page',$row_blogs['filename']);
				if($row_blogs['comments']!='no'){
					$smarty->assign('display_name',$_SESSION['display_name']);	
					$smarty->assign('display_url',$_SESSION['display_url']);	
					$smarty->assign('post_comments',$_SESSION['comment']);	
					$smarty->assign('error',$errors);
					
					$smarty->assign('out_comments', $out_comments);
					$output_comment = $smarty->fetch('html/comments.tpl');
			 	}
				else
				$output_comment='';
			 	$smarty->assign('pagename', stripslashes(strip_tags($row_blogs['pagename'])));
				$smarty->assign('pcontent', stripslashes($page_content));	
				$smarty->assign('date_added', $row_blogs[date_added]);
				$output_blog = $smarty->fetch('html/blog.tpl');
				unset($_SESSION['display_name']);unset($_SESSION['display_url']);unset($_SESSION['comment']);
		}// end of count if
}
else{
	if(count($row_blogs))
		{
		$i=0;
		foreach($row_blogs as $row_blog){
			$sql_comment = "SELECT count(id) as total   FROM ".$prefix."comments where `type`='blog' and page='$row_blog[filename]' and published=1 ";   
			$row_comment = $db->get_a_line($sql_comment);
			$data[$i]['pagename'] = (stripslashes(strip_tags($row_blog['pagename'])));
			$data[$i]['date_added'] = stripslashes(strip_tags($row_blog['date_added']));
			$data[$i]['filename'] = $row_blog['filename'];
			$data[$i]['comments'] = "Comments(".$row_comment[total].")";
			$data[$i]['description'] = substr(preg_replace('/[^A-Za-z0-9 \'\"]/i','',$row_blog[description]),0,400).'...';
		$i++;	
		 }		
		}// end of count if
		
	$smarty->assign('blogs',$data);
	$smarty->assign('comments',$output_comment);
	$smarty->assign('pager',$pager);
	$output_blog = $smarty->fetch('html/blog.tpl');
}	

/******************************************************************************************/
	$sql="SELECT DISTINCT DATE_FORMAT(date_added ,'%M %Y')as title, DATE_FORMAT(date_added ,'%Y-%m')as  link FROM ".$prefix."pages 
		where type = 'blog' and published=1 
		ORDER BY date_added DESC,DAYOFMONTH(date_added)";

	$row_archive = $db->get_rsltset($sql);

	$sql_recent = "SELECT pageid, filename,pagename,date_added,description   FROM ".$prefix."pages where
	 `type`='blog' and published=1 ORDER BY date_added DESC LIMIT 5";
	$row_recent = $db->get_rsltset($sql_recent);
	
	
 	$smarty->assign('recent',$row_recent);		
	$smarty->assign('archives',$row_archive);
	
	$output_blog_right = $smarty->fetch('html/blog-right.tpl');
	
/******************************************************************************************/

$smarty->assign('pagename',$page_title);
$smarty->assign('social_media',$social_media);
$smarty->assign('main_content',$output_blog);
$smarty->assign('comments',$output_comment);

$output = $smarty->fetch('html/content.tpl');

$smarty->assign('blogposts',$output_blog_right);
$output_right = $smarty->fetch('html/right-panel.tpl');

$objTpl = new TPLManager($FILEPATH.'/index.html');
$hotspots = $objTpl->getHotspotList();

$placeHolders = $objTpl->getPlaceHolders($hotspots,$page_title);


$i=0;
	foreach($placeHolders as $items)
	{
		if($hotspots[$i] == 'settings_keywords' && !empty($row_blogs['keywords']))
		{ 
			$items=$row_blogs['keywords'];
		}
		if($hotspots[$i] == 'settings_description' && !empty($row_blogs['description']))
		{ 
			$items=$row_blogs['description'];
		}
		$smarty->assign("$hotspots[$i]","$items");
		$i++;
	}
$smarty->assign('error',$errors);	
$smarty->assign('content',$output);
$smarty->assign('right_panel',$output_right);
$smarty->assign('sidebar',$output_right);
$smarty->display($FILEPATH.'/index.html');

/****************************************************************************************************************/

?>