<?php
include_once("common/config.php");
include ("include.php");
include_once("common/placeholder.class.php");

//echo $randomstring  = $_REQUEST["randomstring"];exit;

// get terms page info from database
$w = $db->get_a_line("select * from ".$prefix."pages where filename = 'clickbank' and published=1");
@extract($w);
$main_content		= stripslashes($w["pcontent"]);
$pagename	= $w["pagename"];
$content_page_name = " - ".$pagename;
$linkproduct	= $w["linkproduct"];
$width	= $w["width"];
$commentcheck = $w["comments"];
$showurl	= $w["showurl"];
$nofollow	= $w["nofollow"];
$keyword = $w["keywords"];

$product_is=$db->get_a_line("select * from ".$prefix."products where id =".$_GET['pid']);
$product_price=$product_is['price'];
$product_name=$product_is['product_name'];

if($product_is['subscription_active']==1){
    
        if($product_is['period3_interval']=='D')
            $interval=" days";
         else if($product_is['period3_interval']=='W')
            $interval=" Week";    
         else if($product_is['period3_interval']=='M')
            $interval=" Months";
            
         else if($product_is['period3_interval']=='Y')
            $interval=" Years";

         $product_price=$product_is['amount3'];
         
         $subscription_msg="
         <b>Simply fill the below registration form and click the \"Signup\" button to get started.</b><br/>
(Every ".$product_is['period3_value']." ".$interval." you will receive an email to this address with download instructions for the next lesson in your training.)";
    
}

if($w['asign_template']=="default"){}
else if($w['asign_template']=='none'){$FILEPATH="";}
else {	$FILEPATH=$_SERVER['DOCUMENT_ROOT']."/templates/". $w['asign_template'] ."";}

if($_GET["msg"]=="s"){
    $msg="<div class='error'>Invalid Security Code</div>";
} else if($_GET["msg"]=="u") {
    $msg="<div class='error'>This Username Already exist !</div>";
}
$pid_is=$_GET["pid"];
$smarty->assign('product_name',$product_name);
$smarty->assign('fname',$_SESSION["fname"]);
$smarty->assign('lname',$_SESSION["lname"]);
$smarty->assign('email',$_SESSION["email"]);
$smarty->assign('uname',$_SESSION["uname"]);
$smarty->assign('pid',$pid);
$smarty->assign('subscription_msg',$subscription_msg);
$smarty->assign('page','thankyou');
$smarty->assign('product_price',$product_price);
$smarty->assign('msg',$msg);
$smarty->assign('randomstring',$randomstring);
$signup_form= $smarty->fetch('html/signup.tpl');





$main_content =   preg_replace ("/\{\{(.*?)\}\}/e", "$$1", $main_content);




$smarty->assign('pagename',$pagename);
$smarty->assign('main_content',$main_content);

$output = $smarty->fetch('html/content.tpl');


$objTpl = new TPLManager($FILEPATH.'/index.html');
$hotspots = $objTpl->getHotspotList();

$placeHolders = $objTpl->getPlaceHolders($hotspots);
$i=0;
foreach($placeHolders as  $items)
{
	if($hotspots[$i] == 'settings_keywords')
	{
		$items=$keyword;
	}
	$smarty->assign("$hotspots[$i]","$items");
	$i++;
}


$smarty->assign('content',$output);
$smarty->assign('error',$errors);
$smarty->display($FILEPATH.'/index.html');
unset($_SESSION["fname"],$_SESSION["lname"],$_SESSION["uname"],$_SESSION["email"]);
?>