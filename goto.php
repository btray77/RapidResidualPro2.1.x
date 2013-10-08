<?php
include_once("common/config.php");
include ("include.php");
$path = $_SERVER['REQUEST_URI'];
$ip	= $_SERVER['REMOTE_ADDR'];
$path = preg_replace("@.*?(goto/.*?)@","$1", $path);
$var_array = explode("/",$path);
switch(count($var_array)){
	case 4:
		 $afiliate_name=	$var_array[1];
		 $product_name = $var_array[2];
		 $coupon_code=$var_array[3];
		 $total=3;
	break;
	case 3:
		 $product_name = $var_array[1];
		 $afiliate_name= $var_array[1];
		 $coupon_code=$var_array[2];
		$total=2;
	break;
	case 2:
		$coupon_code=$var_array[1];
		$total=1;
	break;
	
}
/***********************************************************************************************/
if($total==3)  // Affiliate Name / product name / coupon code
{
	if($common->get_affiliate($db,$prefix,$afiliate_name))
	{
		if(!$common->is_affiliate_banned($db,$prefix,$afiliate_name)){
			if($common->get_product($db,$prefix,$product_name)){
				
				switch($common->is_coupon_valid($db,$prefix,$coupon_code))
				{
					case -1:
						$error ="<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
						break;
					case -2:	
						$error ="<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
						break;
					case 1:
						$common->set_cookie_coupon($db,$prefix,$coupon_code);
						$common->set_cookie_affiliate($db,$prefix,$afiliate_name,$coupon_code);
						//---------------------------------------------------------------
						$counter->setCounterByType('', $product_name, $ip, $afiliate_name,'COUPON_VIEW','');                        //---------------------------------------------------------------
						$call = $http_path."/products.php?ref=$afiliate_name&short=$product_name&coupon=$coupon_code";
	   					header("Location: ".$call);
						exit;
						break;	
				}
				
			}
			else
			{
				$error ="<div class='error'>Sorry invalid product name. Please enter correct product name</div>";
			}	
		}
		else
		{ 
			header("Location:/content.php?page=affiliate-banned");
			exit;
		}
	}
	else
	{
		$error ="<div class='error'>Sorry invalid affilite name. Please enter correct affilite name</div>";
	}
}
//--------------------------------------------------------------------------------------------------------
else if($total==2) //  product name / coupon code
{
	if($common->get_product($db,$prefix,$product_name)){
			
			switch($common->is_coupon_valid($db,$prefix,$coupon_code))
			{
				case -1:
					$error ="<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
					break;
				case -2:	
					$error ="<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
					break;
				case 1:
					$common->set_cookie_coupon($db,$prefix,$coupon_code);
					$call = $http_path."/products.php?short=$product_name&coupon=$coupon_code";
					//---------------------------------------------------------------
					$counter->setCounterByType('', $product_name, $ip, $afiliate_name,'COUPON_VIEW','');
					//---------------------------------------------------------------
   					header("Location: ".$call);
					exit;
					break;	
			}
			
		}
	else if($common->get_affiliate($db,$prefix,$afiliate_name))
			{
				if(!$common->is_affiliate_banned($db,$prefix,$afiliate_name)){
					switch($common->is_coupon_valid($db,$prefix,$coupon_code))
					{
						case -1:
							$error ="<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
							break;
						case -2:	
							$error ="<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
							break;
						case 1:
							$common->set_cookie_coupon($db,$prefix,$coupon_code);
							$product_name = get_product_name_by_coupon($db,$prefix,$coupon_code);
							if(!empty ($product_name)){
							//---------------------------------------------------------------
							$counter->setCounterByType('', $product_name, $ip, $afiliate_name,'COUPON_VIEW','');
							//---------------------------------------------------------------
							}
							$common->set_cookie_affiliate($db,$prefix,$afiliate_name,$coupon_code);
							$call = $http_path."/sales.php?ref=$afiliate_name&coupon=$coupon_code";
		   					header("Location: ".$call);
							exit;
							break;	
					}
				}
			else
			{ 
				header("Location:/content.php?page=affiliate-banned");
				exit;
			}
			
		}	
	else
	{
		$error ="<div class='error'>Sorry invalid Product name or Affiliate Name. Please enter correct Product name or Affiliate Name</div>";
	}	
}
//--------------------------------------------------------------------------------------------------------
else //   coupon code
{
			switch($common->is_coupon_valid($db,$prefix,$coupon_code))
			{
				case -1:
					$error ="<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
					break;
				case -2:	
					$error ="<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
					break;
				case -4:	
					$error ="<div class='error'>Unable to set cookies</div>";
				break;	
				case 1:
					$common->set_cookie_coupon($db,$prefix,$coupon_code);
					$call = $http_path."/sales.php?coupon=$coupon_code";
                    $product_name = get_product_name_by_coupon($db,$prefix,$coupon_code);
					if(!empty ($product_name)){
					//---------------------------------------------------------------
					$counter->setCounterByType('', $product_name, $ip, '','COUPON_VIEW','');
					//---------------------------------------------------------------
                                        }
   					header("Location: ".$call);
					exit;
					break;	
			}
}
/******************************************************************************************/
function set_cookie_coupon($db,$prefix,$coupon_code)
{
	$sql="SELECT p.id,p.pshort,c.expire_date FROM ".$prefix."coupon_codes c,".$prefix."products p where c.prod=p.pshort and c.couponcode ='$coupon_code'; ";
	$row = $db->get_a_line($sql);
	$value=$coupon_code;
	$expiry_date=strtotime($row[expire_date]);
	setcookie ('coupon-'.$row['pshort'],$value,$expiry_date,'/');
	
	return 1;
}
//-------------------------------------------------------------------------------------------------------
function set_cookie_affiliate($db,$prefix,$affiliate_name,$coupon_code)
{
	$sql="SELECT p.id,p.pshort,c.expire_date FROM ".$prefix."coupon_codes c,".$prefix."products p where c.prod=p.pshort and c.couponcode ='$coupon_code'; ";
	$row = $db->get_a_line($sql);
	$expiry_date=get_Affiliate_Cookie_Expiry($db,$prefix);
	
	if(Check_Cookies_mode($db,$prefix)=='first')
	{
		
		if(!isset($_COOKIE['referer-'.$row['pshort']])){
			setcookie ('referer-'.$row['pshort'], $affiliate_name,time()+(3600*24*$expiry_date),"/");
				
		}	
	}
	else {
		setcookie('referer-'.$row['pshort'], $affiliate_name, time()+(3600*24*$expiry_date),"/");
	}
	
	return 1;
}
/*****************************************************************************************************/
function is_coupon_valid($db,$prefix,$coupon_code)
{
		$q2 = "select * from ".$prefix."coupon_codes where couponcode='$coupon_code'";
		$v = $db->get_a_line($q2);
		$product_name = $v[prod];
		
		if(!empty($product_name))
		{
		$current_date = date("Y-m-d H:i:s");
		$coupon_expiry = $v[expire_date];
			if(strtotime($coupon_expiry) < strtotime($current_date)){
				return -2;
			}
			else
			{ 
			//setcookie("coupon_code",$coupon_code, strtotime($coupon_expiry),"/");
			return 1;
			}
		}
		else
			return -1;
}
function get_product($db,$prefix,$product_name)
{
		$q = "select count(*) as total from ".$prefix."products where pshort='$product_name'";
		$r = $db->get_a_line($q);
		$product_count = $r['total'];
		if($product_count > 0)
			return 1;
		else
			return 0;
}
function get_Coupon_Cookie_Expire($db,$prefix,$coupon_code){
		$q2 = "select expire_date from ".$prefix."coupon_codes where couponcode='$coupon_code'";
		$v = $db->get_a_line($q2);
		return $expire_date = $v['expire_date'];
}
function get_Affiliate_Cookie_Expiry($db,$prefix){
	$sql = "select cookie_expiry from ".$prefix."site_settings where id='1'";
	$row = $db->get_a_line($sql);
	return $row['cookie_expiry'];
	
}
function Check_Cookies_mode($db,$prefix)
{
    $qry = "select cookie_mode, cookie_expiry from ".$prefix."site_settings where id='1'";
	$row = $db->get_a_line($qry);
	return $cookie_mode = $row['cookie_mode'];
}
//
function get_affiliate($db,$prefix,$afiliate_name){
		$q = "select count(id) as total from ".$prefix."members where username='$afiliate_name'";
		$v = $db->get_a_line($q);
		$member_value = $v[total];
		
		if($member_value > 0)
			return 1;
		else
			return 0;
}
function is_affiliate_banned($db,$prefix,$afiliate_name){
		$q = "select count(id) as total from ".$prefix."members where username='$afiliate_name' and is_block=0";
		$v = $db->get_a_line($q);
		$member_value = $v[total];
		if($member_value > 0)
		{	
			return 0;
		}
		else
		{
			return 1;
		}
}
function get_product_name_by_coupon($db,$prefix,$coupon)
{
$qry_prod_cpn = "select * from " . $prefix . "coupon_codes where couponcode = '" . $coupon . "'";
$row_prod_cpn = $db->get_a_line($qry_prod_cpn);
return $short = $row_prod_cpn['prod'];
}
/*********************************************************************************************************/
	$objTpl = new TPLManager($FILEPATH.'/index.html');
	$hotspots = $objTpl->getHotspotList();
	$placeHolders = $objTpl->getPlaceHolders($hotspots);
	$i=0;
	foreach($placeHolders as  $items)
	{
		$smarty->assign("$hotspots[$i]","$items");
		$i++;
	}
	$smarty->assign('content',$error);
	$smarty->assign('error',$error);
	$smarty->display($FILEPATH.'/index.html');	
?>