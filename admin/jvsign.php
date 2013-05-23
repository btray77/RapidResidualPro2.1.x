<?php
include ("session.php");
include ("header.php");
$GetFile = file("../html/admin/jvsign.html");
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
	// Parse form data
	$index_page 			= $db->quote($_POST["index_page"]);
	$psponder				= $db->quote($_POST["psponder"]);

	// Write to database
	$set = "index_page				= {$index_page}, ";
	$set .= "psponder  				= {$psponder} ,";
	$set .= "agreement  			= {$agreement}";

	$db->insert("update ".$prefix."jvsign set $set where id = '1'");
	$msg = "ed";
}

// Get Product info from database
$GetProd = $db->get_a_line("select * from ".$prefix."jvsign where id = '1'");
@extract($GetProd);
$index_page			= stripslashes($index_page);
$showpaid			= $psponder;
$agreementid			= $agreement;

$q = "select * from ".$prefix."responders order by rspname2";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid = $rspname2;

	if($pid ==$showpaid)
	{
		$psponder.="<option value='$pid' Selected>$pid</option>";
	}
	elseif($pid !=$showpaid)
	{
		$psponder.="<option value='$pid'>$pid</option>";
	}
}

$sql="select pageid,pagename from ".$prefix."pages where published = '1' and linkproduct='Legal'";
$GetProd = $db->get_rsltset($sql);
 foreach($GetProd as $pages)
 {
	$pagename			= stripslashes($pages['pagename']);
	$pageid			= $pages['pageid'];
	
 if($pageid ==$agreementid)
	{
		$agreement.="<option value='$pageid' selected>$pagename</option>";
	}
	elseif($pid !=$showpaid)
	{
		$agreement.="<option value='$pageid'>$pagename</option>";
	}
	
	
 }


$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include_once("footer.php");
?>