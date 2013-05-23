<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/blog-edit.html");
$Content = join("", $GetFile);

function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}

if (isset($_POST['submit']))
{
	// Parse form input through Post
	$pcontent		= $db->quote(trim($_POST["pcontent"]));
	$pagename		= $db->quote($_POST["pagename"]);
	$description	= $db->quote($_POST["description"]);
	$keywords		= $db->quote($_POST["keywords"]);
	$rss			= $db->quote($_POST["rss"]);
	$comments		= $db->quote($_POST["comments"]);
	$width			= $db->quote($_POST["width"]);
	$showurls		= $db->quote($_POST["showurls"]);
	$nofollow		= $db->quote($_POST["nofollow"]);
		
	// Set Data to be inserted into database
	$set = "pagename  		= {$pagename}, ";
	$set .= "pcontent		= {$pcontent}, ";
	$set .= "description	= {$description}, ";
	$set .= "rss  			= {$rss}, ";
	$set .= "showurls  		= {$showurls}, ";
	$set .= "nofollow  		= {$nofollow}, ";
	$set .= "comments		= {$comments}, ";
	$set .= "width			= {$width}, ";
	$set .= "keywords  		= {$keywords}";

	// Write to database
	$db->insert("update ".$prefix."pages set $set where pageid = '$pageid'");
	$msg = "e";
	header("Location: blog.php?msg=$msg");
}

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
$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include_once("footer.php");
?>