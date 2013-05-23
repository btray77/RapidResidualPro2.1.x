<?php

	include_once("common/config.php");
	include ("include.php");
	$ip	= $_SERVER['REMOTE_ADDR'];
	$parts = explode('/', $_GET['vars']);
	/************************ New implemented code here ************************************/
	
	$path = $_SERVER['REQUEST_URI'];
	$path = preg_replace("@.*?(referrer/.*?)@","$1", $path);
	$var_array = explode("/",$path);
	
	switch(count($var_array)){
		case 3:
			 $afiliate_name = $var_array[1];
			 $product_name  = $var_array[2];
			 
			 $total = 2;
		break;
		case 4:
			 $afiliate_name = $var_array[1];
			 $product_name  = $var_array[2];
			 $pgateway       = $var_array[3];
			 $total = 3;
		break;
	}
	/***********************************************************************************************/
	if($total==3)  // Affiliate Name / product name / gateway
	{
		if($common->get_affiliate($db,$prefix,$afiliate_name,$pgateway))
		{
			if(!$common->is_affiliate_banned($db,$prefix,$afiliate_name)){
				if($common->get_product($db,$prefix,$product_name)){
                                   
				   $common->set_refere_cookies($db,$prefix,$afiliate_name,$product_name);
				   $counter->setCounterByType('', $product_name, $ip, $afiliate_name,'LINK','');
					$call = $http_path."/products.php?ref=$afiliate_name&short=$product_name&gateway=$pgateway";
		   			header("Location: ".$call);
					exit;
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
			$error ="<div class='error'>Sorry invalid affiliate name. Please enter correct affiliate name</div>";
		}
	}
	if($total==2)  // Affiliate Name / product name / gateway
	{
		if($common->get_affiliate($db,$prefix,$afiliate_name,$pgateway))
		{
			if(!$common->is_affiliate_banned($db,$prefix,$afiliate_name)){
				if($common->get_product($db,$prefix,$product_name)){
                                   
				   $common->set_refere_cookies($db,$prefix,$afiliate_name,$product_name);
				   //---------------------------------------------------------------
				   $counter->setCounterByType('', $product_name, $ip, $afiliate_name,'LINK','');
				   //---------------------------------------------------------------
					$call = $http_path."/products.php?ref=$afiliate_name&short=$product_name";
		   			header("Location: ".$call);
					exit;
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
			$error ="<div class='error'>Sorry invalid affiliate name. Please enter correct affiliate name</div>";
		}
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