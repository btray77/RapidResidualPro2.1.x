<?php

include_once("session.php");
include_once("header.php");

$file=file("../html/admin/tdp.html");
$returncontent=join("",$file);

function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}


if (isset($_POST['submit']))
{
	// Parse form data
	$terms  		= $db->quote($_POST["terms"]);
	$privacy  		= $db->quote($_POST["privacy"]);
	$disclaimer  	= $db->quote($_POST["disclaimer"]);
	$contact	  	= $db->quote($_POST["contact"]);
	$antispam	  	= $db->quote($_POST["antispam"]);
	$links		  	= $db->quote($_POST["links"]);
	$dmca		  	= $db->quote($_POST["dmca"]);
	$affiliate	  	= $db->quote($_POST["affiliate"]);
	$membership	  	= $db->quote($_POST["membership"]);
	$refund		  	= $db->quote($_POST["refund"]);
	$health		  	= $db->quote($_POST["health"]);
	$compensation  	= $db->quote($_POST["compensation"]);

	// Update database
	$set	.= " terms={$terms}";
	$set	.= ", privacy={$privacy}";
	$set	.= ", disclaimer={$disclaimer}";
	$set	.= ", contact={$contact}";
	$set	.= ", antispam={$antispam}";
	$set	.= ", links={$links}";
	$set	.= ", dmca={$dmca}";
	$set	.= ", affiliate={$affiliate}";
	$set	.= ", membership={$membership}";
	$set	.= ", refund={$refund}";
	$set	.= ", health={$health}";
	$set	.= ", compensation={$compensation}";

	$mysql="update ".$prefix."site_settings set $set where id='1'";
	$db->insert($mysql);

	$msg = "Legal Pages Successfully Edited.";
}

// read data from database
$mysql="select * from ".$prefix."site_settings where id='1'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);

$terms			= stripslashes($terms);
$disclaimer		= stripslashes($disclaimer);
$privacy		= stripslashes($privacy);
$contact		= stripslashes($contact);
$antispam		= stripslashes($antispam);
$links			= stripslashes($links);
$dmca			= stripslashes($dmca);
$affiliate		= stripslashes($affiliate);
$membership		= stripslashes($membership);
$refund			= stripslashes($refund);
$health			= stripslashes($health);
$compensation	= stripslashes($compensation);

// show page
$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>