<?php

include "session.php" ;
include "header.php" ;
$GetFile = file("../html/admin/blog-add.html");
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
	$filename		= $db->quote($_POST["filename"]);
	$keywords		= $db->quote($_POST["keywords"]);
	$rss			= $db->quote($_POST["rss"]);
	$comments		= $db->quote($_POST["comments"]);
	$width			= $db->quote($_POST["width"]);
	$description	= $db->quote($_POST["description"]);
	$showurls		= $db->quote($_POST["showurls"]);
	$nofollow		= $db->quote($_POST["nofollow"]);

	// Make sure file name is unique
	$q="select count(*) as cnt from ".$prefix."pages where filename={$filename}";
	$r=$db->get_a_line($q);
	$count=$r[cnt];

	if($count > 0)
	{
		// file name already exists
		header("Location: blog-add.php?err=1&pagename=$pagename&filename=$filename&pcontent=$pcontent");
		exit();
	}
	$date_added	= $db->quote(date("Y-m-d H:i:s"));
	//$date_added = date(" Y m d G:i:s");
	// Set Data to be inserted into database
	$set = "pagename  		= {$pagename}, ";
	$set .= "pcontent		= {$pcontent}, ";
	$set .= "linkproduct	= '', ";
	$set .= "date_added		= {$date_added}, ";
	$set .= "filename  		= {$filename}, ";
	$set .= "showurls  		= {$showurls}, ";
	$set .= "nofollow  		= {$nofollow}, ";
	$set .= "rss  			= {$rss}, ";
	$set .= "comments		= {$comments}, ";
	$set .= "width			= {$width}, ";
	$set .= "description	= {$description}, ";
	$set .= "type			= 'blog', ";
	$set .= "keywords  		= {$keywords}";

	// Write to database
	$pid = $db->insert_data_id("insert into ".$prefix."pages set $set");
	$msg = "a";
	header("Location: blog.php?msg=$msg");
}

// Get data to populate fields on page
$q = "select * from ".$prefix."products order by pshort";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid		= $pshort;
	$linkproduct.="<option value='$pid'>$pid</option>";
}



if($err == '1')
{
	$warning = "Filename already in use.";
}

// Display page to browser
$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;

include_once("footer.php");
?>