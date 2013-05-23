<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/squeeze_add.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Parse form data
	$name 			= $db->quote($_POST["name"]);
	$squeezepage	= $db->quote($_POST["squeezepage"]);
	$comments		= $db->quote($_POST["comments"]);
	$width			= $db->quote($_POST["width"]);
	$access			= $db->quote($_POST["access"]);
	$keyword		= $db->quote($_POST["keyword"]);
	$page_title		= $db->quote($_POST["page_title"]);
	$seo_title		= $db->quote($_POST["seo_title"]);
	$meta_discription		= $db->quote($_POST["meta_discription"]);
	

	// Enter member data into database
	$set = "name  			= {$name}, ";
	$set .= "comments		= {$comments}, ";
	$set .= "width			= {$width}, ";
	$set .= "access			= {$access}, ";
	$set .= "asign_template	= 'default', ";
	$set .= "meta_discription	= {$meta_discription}, ";
	$set .= "keyword			= {$keyword}, ";
	$set .= "page_title			= {$page_title}, ";
	$set .= "seo_title			= {$seo_title}, ";
	$set .= "squeezepage	= {$squeezepage}";
	$mid = $db->insert_data_id("insert into ".$prefix."squeeze_pages set $set");
	header("Location: squeeze_view.php?msg=a");
}

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>