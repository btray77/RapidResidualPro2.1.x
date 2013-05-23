<?php
include "session.php" ;
include "header.php" ;

$GetFile = file("../html/admin/edit_campaign.html") ;
$Content = join("",$GetFile) ;

$Title = "Edit Timed Release Content Campaign" ;

if (isset($_POST['submit']))
{
	// Set variables from form
	$longname		=  $db->quote($_POST["longname"]);
	$description	=  $db->quote($_POST["description"]);
	$shortname=str_replace(' ','',$shortname);
	
	// Upadate Dababase Information
	$set	= "longname={$longname}";
	$set	.= ", description={$description}";

	// Write to database
	$db->insert("update ".$prefix."tccampaign set $set where id = '$id'");
	$msg = "e";
	header("Location: tcampaigns.php?msg=$msg");
}

// Get data to populate fields on page
$GetProd = $db->get_a_line("select * from ".$prefix."tccampaign where id = '$id'");
@extract($GetProd) ;

$longname		= $longname;
$shortname		= $shortname;
$description	= $description;



$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
include "footer.php" ;
?>