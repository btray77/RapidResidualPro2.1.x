<?php
include "session.php";
include "header.php";
//$GetFile = file("../html/admin/view-content-page.html");
//$Content = join("", $GetFile);
if(isset($_GET['pageid']) )
$pageid= (int) $_GET['pageid'];
$type=$_GET['type'];

if($type=='blog')
{$backURL='blog.php'; $view="View Blog";}
else	
{ $backURL='pages.php';$view="View Content";}
// Get data to populate fields on page
$GetProd = $db->get_a_line("select * from ".$prefix."pages where pageid = '$pageid'");
@extract($GetProd) ;

$pagename		= $pagename;
$pcontent		= stripslashes($pcontent);
$filename		= $filename;
$description	= $description;
$keywords		= $keywords;
$width			= $width;
$rsscheck		= stripslashes($rss);
$commentscheck	= stripslashes($comments);
$showurlscheck	= stripslashes($showurls);
$nofollowcheck	= stripslashes($nofollow);

if ($rsscheck == 'yes')
{
	$rss1 = 'checked';
}
else if ($rsscheck == 'no')
{
	$rss2 = 'checked';
}

if ($commentscheck == 'yes')
{
	$comments1 = 'checked';
}
else if ($commentscheck == 'no')
{
	$comments2 = 'checked';
}

if ($showurlscheck == 'yes')
{
	$showurls1 = 'checked';
}
else if ($showurlscheck == 'no')
{
	$showurls2 = 'checked';
}

if ($nofollowcheck == 'yes')
{
	$nofollow1 = 'checked';
}
else if ($nofollowcheck == 'no')
{
	$nofollow2 = 'checked';
}



// Display page to browser
//$Content = preg_replace($Ptn,"$$1",$Content);
//echo $Content;
include_once("../html/admin/view-content-page.html");
include_once("footer.php");
?>