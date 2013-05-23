<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/add_campaign.html");
$Content = join("",$GetFile);
$Title = "Add Timed Release Content Campaign";

if (isset($_POST['submit']))
{
	// Set variables from form
	$longname		=  $db->quote($_POST["longname"]);
	$shortname		=  $db->quote($_POST["shortname"]);
	$description	=  $db->quote($_POST["description"]);
	$shortname=str_replace(' ','',$shortname);

	// Make sure short name is uniquie
	$q="select count(*) as cnt from ".$prefix."tccampaign where shortname={$shortname} && id !='$id'";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count > 0)
	{
		// short name already exists
		header("Location: add_campaign.php?id=$id&err=1");
		exit();
	}


	// Upadate Dababase Information
	$set	= "longname={$longname}";
	$set	.= ", shortname={$shortname}";
	$set	.= ", description={$description}";

	// Write to database
	$pid = $db->insert_data_id("insert into ".$prefix."tccampaign set $set");
	$msg = "a";
	header("Location: tcampaigns.php?msg=$msg");
}

if($err == '1')
{
	$msg = "Campaign short name already in use.";
}

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
include "footer.php";
?>