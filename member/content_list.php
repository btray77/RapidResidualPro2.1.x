<?php
include ("include.php");
//include_once("session.php");
$GetFile = file("../html/member/content_list.html");
$tcontent = join("",$GetFile);
$Pat = "/{{loop_begin}}(.*?){{loop_end}}/s";
preg_match($Pat,$tcontent,$Output);
$SelectedContent = $Output[1];

// Get default campaign name
$q = "select * from ".$prefix."tccampaign where id='1'";
$r4 = $db->get_a_line($q);
@extract($r4);
$cshortname	= $r4['shortname'];

$q = "select count(*) as cnt from ".$prefix."timed_content where campaign='$cshortname'";
$r = $db->get_a_line($q);
$count = $r[cnt];

$q = "select * from ".$prefix."member_products where member_id='$memberid' && product_id='1'";
$r2 = $db->get_a_line($q);
@extract($r);
$date_added	= $r2['date_added'];
$today = date('l F jS, Y');
$difference = (strtotime($today) - strtotime($date_added)) / (60 * 60 * 24);		
$ToReplace = "";

$GetMembers = $db->get_rsltset("select * from ".$prefix."timed_content where campaign='$cshortname' ORDER BY available, pageid");
for($i = 0; $i < count($GetMembers); $i++)
	{
	@extract($GetMembers[$i]);	
	if($difference >= $available)
		{		
		$list = '<a href=tcontent.php?content='.$filename.'>'.$pagename.'</a><br>';
		}
	else
		{
		$list = $pagename."<br>";
		}	
	$ToReplace .= preg_replace($Ptn,"$$1",$SelectedContent);
	}
$tcontent = preg_replace($Pat,$ToReplace,$tcontent);
$tcontent = preg_replace("/{{(.*?)}}/e","$$1",$tcontent);
$q = "select count(*) as cnt from ".$prefix."member_products where member_id='$memberid' && product_id='1' && refunded ='0'";
$r = $db->get_a_line($q);
$count = $r[cnt];

if($count == '0')
	{
	$tcontent = '';	
	}
?>