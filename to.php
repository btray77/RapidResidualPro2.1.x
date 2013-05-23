<?php

include_once("common/config.php");
include ("include.php");


$path = $_SERVER['REQUEST_URI'];
$path = preg_replace("@.*?(to/.*?)@","$1", $path);
$var_array = explode("/",$path);

switch(count($var_array)){
	case 4:
             $afiliate_name=$var_array[1];
             $product_name = $var_array[2];
             $prod_type=$var_array[3];
             $total=3;
	break;
	case 3:
            $product_name = $var_array[1];
            $afiliate_name= $var_array[1];
            $prod_type=$var_array[2];
             $total=2;
	break;
}

/***********************************************************************************************/
if($total==3)  // Affiliate Name / product name / product type
{
    	if($common->get_affiliate($db,$prefix,$afiliate_name))
	{
		if(!$common->is_affiliate_banned($db,$prefix,$afiliate_name)){
			if($common->get_product($db,$prefix,$product_name)){
                            $common->set_refere_cookies($db,$prefix,$afiliate_name,$product_name);
                            //---------------------------------------------------------------
                            $counter->setCounterByType('', $product_name, $ip, $afiliate_name,'PRODUCT_VIEW','');
                            //---------------------------------------------------------------
                            if($prod_type == 'sales'){
                                $call = $http_path."/sales.php?ref=$afiliate_name&short=$product_name";
                            }else{
                                $call = $http_path."/products.php?ref=$afiliate_name&short=$product_name";
                            }

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
		$error ="<div class='error'>Sorry invalid affilite name. Please enter correct affilite name</div>";
	}
}
//--------------------------------------------------------------------------------------------------------

else if($total==2) //  product name / coupon code
{
	if($common->get_product($db,$prefix,$product_name))
        {
            //---------------------------------------------------------------
               $counter->setCounterByType('', $product_name, $ip, '','PRODUCT_VIEW','');
            //---------------------------------------------------------------
            if($prod_type == 'sales'){
                $call = $http_path."/sales.php?short=$product_name";
            }else{
                $call = $http_path."/products.php?short=$product_name";
            }
            header("Location: ".$call);
            exit;
	}
	else
	{
		$error ="<div class='error'>Sorry invalid Product name. Please enter correct Product name.</div>";
	}	
}

//-------------------------------------------------------------------------------------------------------

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