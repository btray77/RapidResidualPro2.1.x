<?php

include ("include.php");

//$GetFile = file ("../html/member/login.html");
//$pagecontent = join("",$GetFile);
$destination=$_REQUEST['destination'];
if (isset($_POST['submit']))
$coupon = $_REQUEST['coupon'];
$product = $_REQUEST['product'];

if ((!$dUser) || (!$dPass))
	{
    $Message = "One or more fields were not filled out.";
	}
else 
	{
	include "session.php";
	}
ob_start();

$form_path	= "session.php";
$button = "Login";
 $err=$_GET['err'];
if($err=="inv")
	{
		
	$Message="<div class='error'><img src='/images/crose.png' border='0'> Invalid Username/Password!</div>";
	}
elseif($err=="ses")
	{
	$Message="<div class='error'><img src='/images/crose.png' border='0'> Session Time Out! Please Login again.</div>";
	}
elseif($err=="log")
	{
	$Message="<div class='error'><img src='/images/crose.png' border='0'> Successfully Logged out.</div>";
	}
elseif($err=="nolog")
	{
	$Message="<div class='error'><img src='/images/crose.png' border='0'> You must be logged in to view this resource.</div>";
	}
elseif($err=="blocked")
        {
                $Message='<div class="error">
                        <img src="../images/crose.png" align="absmiddle">
                        Sorry! This user account is unavailable at this time. Please contact to your administrator for further assistance.
                        </div>';
        }
elseif($err=="temp_block")
        {
            $Message="
                <div class='error'><img src='/images/crose.png' border='0'>
                    <p>Sorry! This account has been frozen. It appears that either you've forgotten your information or an unauthorized user is attempting to access your account.</p>
                    <p>You may attempt to use the \"Forgot Password\" feature or log in again in 30 minutes or you may need to contact your site administrator to have the account unsuspended.</p>
                    <p>We take your account security very seriously and apologize for any inconvenience this may cause. This is a security feature to protect you.</p>

                    Sincerely,<br/>
                    Site Security Admin
                </div>";
        }
elseif($err=="geo_conflict")
        {
            $Message="
                <div class='error'><img src='/images/crose.png' border='0'>
                    <p>You've attempted to login from a location not associated with your account which may indicate someone else is trying to log in using your information. This will freeze your account until you contact the site admin.</p>
                </div>";
        }
else
	{
	$Message="";
	}


	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	
 $os = getOS($user_agent);
	
 $browser = getBrowser($user_agent);	
		
	
$smarty->assign('os', $os);
$smarty->assign('browser', $browser);

$smarty->assign('coupon',$coupon);
$smarty->assign('product',$product);
$smarty->assign('dUser',$dUser);
$smarty->assign('dUser',$dPassword);
$smarty->assign('destination',$destination);
$smarty->assign('error',$Message);
$smarty->assign('button',$button);
$output_login = $smarty->fetch('../html/member/login.tpl');

	$smarty->assign('pagename','member area');
	$smarty->assign('main_content',$output_login);

	$output = $smarty->fetch('../html/member/content.tpl');
	
	$objTpl = new TPLManager($FILEPATH.'/index.html');
	$hotspots = $objTpl->getHotspotList();
	$placeHolders = $objTpl->getPlaceHolders($hotspots);
	$i=0;
		
	foreach($placeHolders as  $items)
	{
		if($hotspots[$i]!= 'menu_member')
		{
			$smarty->assign("$hotspots[$i]","$items");
		}
		$i++;
	}
	
	function getBrowser($user_agent){
		if(strstr($user_agent, 'ie')) return 'Internet Explorer';
		if(strstr($user_agent, 'firefox')) return 'Firefox';
}

function getOS($user_agent){
		if(strstr($user_agent, 'windows')) return 'Windows';
		if(strstr($user_agent, 'linux')) return 'Linux';
		if(strstr($user_agent, 'apple')) return 'Apple';
	
}	

	

	
	
	
	
	$smarty->assign('content',$output);
	
	
	
	$smarty->display($FILEPATH.'/index.html');		
//$pagecontent = preg_replace("/{{(.*?)}}/e", "$$1", $pagecontent);
//include_once ("header.php");
//$Content 		= preg_replace("/{{(.*?)}}/e","$$1",$Content);
//$Content 		= preg_replace("/<{(.*?)}>/e","$$1",$Content);
//echo $Content;
?>