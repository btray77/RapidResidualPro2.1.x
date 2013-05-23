<?php
include_once("session.php");
include_once("header.php");

if($admin_id !='1')
{
	echo "<br><br><center><h2>Only the site owner can access this page.</h2></center><br><br>";
	include_once("footer.php");
	exit;
}

$GetFile = file("../html/admin/admin_view.html");
$Content = join("",$GetFile);
$Title = "Extra Admin Management";

if($act == "d")
{
	if($id =='1')
	{
		$msg = "no1";
	}
	elseif($id == $admin_id)
	{
		$msg = "no2";
	}
	else
	{
		// Delete member
		$db->insert("delete from ".$prefix."admin_settings where id ='$id'");
		$msg = "d";
	}
}

$Pat = "/<{Begin}>(.*?)<{End}>/s";
preg_match($Pat,$Content,$Output);
$SelectedContent = $Output[1];


########## pagination ###########

$q = "select count(*) as cnt from ".$prefix."admin_settings where id !='1' && id !='$admin_id'";
$r = $db->get_a_line($q);
$count = $r[cnt];
$records=10;
$links = "admin_view.php?";

if($page=="")
{
	$page=1;
}

$start=($page-1)*$records;
$Content=$common->print_page_break3($db,$Content,$count,$records,$links,$page);

########## pagination ###########

$ChangeColor = 1;
$ToReplace = "";

$GetMembers = $db->get_rsltset("select * from ".$prefix."admin_settings where id !='1' && id !='$admin_id' order by id asc limit $start, $records");

for($i = 0; $i < count($GetMembers); $i++)
{
	if($ChangeColor == 0)
	{
		$bgcolor = "#eaeaea";
		$ChangeColor = 1;
	}
	else
	{
		$bgcolor = "#FFFFFF";
		$ChangeColor = 0;
	}

	@extract($GetMembers[$i]);
	$ToReplace .= preg_replace($Ptn,"$$1",$SelectedContent);
}
$Content = preg_replace($Pat,$ToReplace,$Content);

if($msg == "a")
{
	$Message = "Admin is Successfully Added";
}
else if($msg == "e")
{
	$Message = "Admin is Successfully Edited";
}
else if($msg == "d")
{
	$Message = "Admin is Successfully Deleted";
}
else if($msg == "no1")
{
	$Message = "Can not delete the main admin account";
}
else if($msg == "no2")
{
	$Message = "Can not delete your own account";
}

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
include_once("footer.php");
?>