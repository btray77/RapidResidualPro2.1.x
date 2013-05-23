<?php
include ("common/config.php");
include ("common/database.class.php");
include ("common/common.class.php");
$db = new database();
$common = new common();
$path = $_SERVER['REQUEST_URI'];
$path = preg_replace("@.*?(yes/.*?)@","$1", $path);
$var_array = explode("/",$path);
switch(count($var_array)){
	case 5:
				$product_id=	$var_array[1];
				$nickname = $var_array[2];
				$afiliate_name= $var_array[3];
				$coupon_code=$var_array[4];
				$total=3;
	break;
	case 4:
				$product_id=	$var_array[1];
                $nickname = $var_array[2];
			    $afiliate_name= $var_array[3];
                $total=2;
	break;
	case 3:
				$product_id=	$var_array[1];
                $nickname = $var_array[2];
                $total=1;
	break;
	
}

$product_name = get_product_short($db,$prefix,$product_id );

if($total==3)  // Affiliate Name / product name / coupon code
{   
 
     $common->get_affiliate($db,$prefix,$afiliate_name);
	if($common->get_affiliate($db,$prefix,$afiliate_name))
	{
		if(!$common->is_affiliate_banned($db,$prefix,$afiliate_name)){
			if($common->get_product($db,$prefix,$product_name)){
				$common->is_coupon_valid($db,$prefix,$coupon_code);
                                
				switch($common->is_coupon_valid($db,$prefix,$coupon_code))
				{
					case -1:
						echo $error ="<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
                                            exit; 
						break;
					case -2:	
						echo $error ="<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
                                            exit; 
						break;
					case 1:
						
                                                $common->set_cookie_coupon($db,$prefix,$coupon_code);
												
                                                $common->set_cookie_affiliate($db,$prefix,$afiliate_name,$coupon_code);
                                                $call = process_link($db,$prefix,$nickname,$product_id);
                                                header("Location: ".$call);
                                                exit;
                                        break;	
				}
				
			}
			else
			{
				echo $error ="<div class='error'>Sorry invalid product name. Please enter correct product name</div>";
                                exit; 
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
		echo $error ="<div class='error'>Sorry invalid affilite name. Please enter correct affilite name</div>";
                exit ();
	}
}
//--------------------------------------------------------------------------------------------------------

if($total==2) //  product name / coupon code
{   


	if($common->get_affiliate($db,$prefix,$afiliate_name))
                {
                	
                        if(!$common->is_affiliate_banned($db,$prefix,$afiliate_name)){
                           
                                          $common->set_refere_cookies($db, $prefix, $afiliate_name, $product_name);
										
                                         $call = process_link($db,$prefix,$nickname,$product_id);
                                         header("Location: ".$call);
                                        exit;
                                        break;	

                        }
			else
			{ 
				header("Location:/content.php?page=affiliate-banned");
				exit;
			}
			
		}
	else if($common->is_coupon_valid($db, $prefix, $afiliate_name)==1)	  // Alias variable is of coupon_code as afiliate_name;	
	{
		
		$common->set_cookie_coupon($db,$prefix,$afiliate_name);
		$call = process_link($db,$prefix,$nickname,$product_id);
		header("Location: ".$call);
		exit;
	}
	else
	{
		echo $error ="<div class='error'>Sorry invalid Product name or Affiliate Name. Please enter correct Product name or Affiliate Name</div>";
                exit ();
	}	
}
//--------------------------------------------------------------------------------------------------------
else //   coupon code
{
        $call = process_link($db,$prefix,$nickname,$product_id);
	header("Location: ".$call);
        exit ();
}




function get_product_short($db,$prefix,$pid)
{
		$q = "select pshort from ".$prefix."products where id='$pid'";
		$r = $db->get_a_line($q);
		$pshort = $r['pshort'];
		return $pshort;
		
}

function process_link($db,$prefix,$nickname,$product_id)
{

if ($nickname == "")
	{
    echo "INVALID REDIRECT URL PASSED<br>";
    echo "You much supply the correct variable after /likes/.";
    exit;
	}
elseif ($nickname != "")
	{
	// does the nickname exist?
	
	$q = "select count(*) as cnt from ".$prefix."products_short where short_url='$nickname' and product_id= $product_id";
        
	$r = $db->get_a_line($q);
       
	if($r[cnt] == '0')
		{
		// not a valid nickname
		echo "Sorry, you supplied an incorrect url. The redirect short url does not exist.";
		exit;     
		}
	else
		{
		$q = "select redirect_url from ".$prefix."products_short where short_url='$nickname' and product_id= $product_id";
		$vv = $db->get_a_line($q);
		return $url = $vv['redirect_url'];
                
                
		
		exit;		
		}		
	}	
}
?>