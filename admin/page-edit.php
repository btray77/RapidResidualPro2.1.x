<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/page-edit.html");
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
	$linkproduct	= $db->quote($_POST["linkproduct"]);
	$description	= $db->quote($_POST["description"]);
	$keywords		= $db->quote($_POST["keywords"]);
	$rss			= $db->quote($_POST["rss"]);
	$comments		= $db->quote($_POST["comments"]);
	$showurls		= $db->quote($_POST["showurls"]);
	$nofollow		= $db->quote($_POST["nofollow"]);
		
	// Set Data to be inserted into database
	$set = "pagename  		= {$pagename}, ";
	$set .= "pcontent		= {$pcontent}, ";
	$set .= "linkproduct	= {$linkproduct}, ";
	$set .= "description	= {$description}, ";
	$set .= "showurls  		= {$showurls}, ";
	$set .= "nofollow  		= {$nofollow}, ";
	$set .= "rss  			= {$rss}, ";
	$set .= "comments		= {$comments}, ";
	$set .= "keywords  		= {$keywords}";

	// Write to database
	$db->insert("update ".$prefix."pages set $set where pageid = '$pageid'");
	$msg = "e";
	header("Location: pages.php?msg=$msg");
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

$plink		= $linkproduct;
$q = "select * from ".$prefix."products order by pshort";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid		= $pshort;

	if($pid ==$plink)
	{
		$linkproduct.="<option value='$pid' Selected>$pid</option>";
	}
	elseif($pid !=$plink)
	{
		$linkproduct.="<option value='$pid'>$pid</option>";
	}
}

if($plink =='Site Root Page')
{
	$linkproduct.="<option value='$plink' Selected>Site Root Page</option>";
}
if($plink =='Legal')
{
	$linkproduct.="<option value='$plink' Selected>Legal</option>";
}
if($plink =='All Members In Members Area')
{
	$linkproduct.="<option value='$plink' Selected>All Members In Members Area</option>";
}
if($plink =='All Free Product Members')
{
	$linkproduct.="<option value='$plink' Selected>All Free Product Members</option>";
}
if($plink =='All Paid Produt Members')
{
	$linkproduct.="<option value='$plink' Selected>All Paid Product Members</option>";
}

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