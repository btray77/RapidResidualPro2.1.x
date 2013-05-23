<?php

include_once("common/config.php");
include ("include.php");


$path = $_SERVER['REQUEST_URI'];
$ip	= $_SERVER['REMOTE_ADDR'];

$path = preg_replace("@.*?(go/.*?)@", "$1", $path);
$var_array = explode("/", $path);

switch (count($var_array)) {
    case 4:
        $afiliate_name = $var_array[1];
        $product_name = $var_array[2];
        $coupon_code = $var_array[3];
        $total = 3;
        break;
    case 3:
        $product_name = $var_array[1];
        $afiliate_name = $var_array[1];
        $coupon_code = $var_array[2];
        $total = 2;
        break;
    case 2:
        $coupon_code = $var_array[1];
        $total = 1;
        break;
}
/* * ******************************************************************************************** */
if ($total == 3) {  // Affiliate Name / product name / coupon code
    if ($common->get_affiliate($db, $prefix, $afiliate_name)) {
        if (!$common->is_affiliate_banned($db, $prefix, $afiliate_name)) {
            if ($common->get_product($db, $prefix, $product_name)) {

                switch ($common->is_coupon_valid($db, $prefix, $coupon_code)) {
                    case -1:
                        $error = "<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
                        break;
                    case -2:
                        $error = "<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
                        break;
                    case 1:
                        $common->set_cookie_coupon($db, $prefix, $coupon_code);
                        $common->set_cookie_affiliate($db, $prefix, $afiliate_name, $coupon_code);
						//---------------------------------------------------------------
						$counter->setCounterByType('', $product_name, $ip, $afiliate_name,'COUPON_VIEW','');
						//---------------------------------------------------------------
                        $call = $http_path . "/products.php?ref=$afiliate_name&short=$product_name&coupon=$coupon_code";
                        header("Location: " . $call);
                        exit;
                        break;
                }
            } else {
                $error = "<div class='error'>Sorry invalid product name. Please enter correct product name</div>";
            }
        } else {
            header("Location:/content.php?page=affiliate-banned");
            exit;
        }
    } else {
        $error = "<div class='error'>Sorry invalid affilite name. Please enter correct affilite name</div>";
    }
}
//--------------------------------------------------------------------------------------------------------
else if ($total == 2) { //  product name / coupon code
    if ($common->get_product($db, $prefix, $product_name)) {

        switch ($common->is_coupon_valid($db, $prefix, $coupon_code)) {
            case -1:
                $error = "<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
                break;
            case -2:
                $error = "<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
                break;
            case 1:
                $common->set_cookie_coupon($db, $prefix, $coupon_code);
				//---------------------------------------------------------------
				$counter->setCounterByType('', $product_name, $ip, '','COUPON_VIEW','');
				//---------------------------------------------------------------
                $call = $http_path . "/products.php?short=$product_name&coupon=$coupon_code";
                header("Location: " . $call);
                exit;
                break;
        }
    } else if ($common->get_affiliate($db, $prefix, $afiliate_name)) {
        if (!$common->is_affiliate_banned($db, $prefix, $afiliate_name)) {
            switch ($common->is_coupon_valid($db, $prefix, $coupon_code)) {
                case -1:
                    $error = "<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
                    break;
                case -2:
                    $error = "<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
                    break;
                case 1:
                    $common->set_cookie_coupon($db, $prefix, $coupon_code);
                    $common->set_cookie_affiliate($db, $prefix, $afiliate_name, $coupon_code);
                    $product_name = get_product_name_by_coupon($db,$prefix,$coupon_code);
                    if(!empty ($product_name)){
                    //---------------------------------------------------------------
                    $counter->setCounterByType('', $product_name, $ip, $afiliate_name,'COUPON_VIEW','');
                    //---------------------------------------------------------------
                    }
                    $call = $http_path . "/index.php?ref=$afiliate_name&coupon=$coupon_code";
                    header("Location: " . $call);
                    exit;
                    break;
            }
        } else {
            header("Location:/content.php?page=affiliate-banned");
            exit;
        }
    } else {
        $error = "<div class='error'>Sorry invalid Product name or Affiliate Name. Please enter correct Product name or Affiliate Name</div>";
    }
}
//--------------------------------------------------------------------------------------------------------
else { //   coupon code
    switch ($common->is_coupon_valid($db, $prefix, $coupon_code)) {
        case -1:
            $error = "<div class='error'>Sorry invalid coupon code. Please enter correct coupon code</div>";
            break;
        case -2:
            $error = "<div class='error'>Sorry coupon code is expired. Please enter correct coupon code</div>";
            break;
        case 1:
            if ($common->set_cookie_coupon($db, $prefix, $coupon_code)) {
                 $product_name = get_product_name_by_coupon($db,$prefix,$coupon_code);
                    if(!empty ($product_name)){
                    //---------------------------------------------------------------
                    $counter->setCounterByType('', $product_name, $ip, '','COUPON_VIEW','');
                    //---------------------------------------------------------------
                    }
                $call = $http_path . "/index.php?coupon=$coupon_code";
                header("Location: " . $call);
                exit;
            } else {
                $error = "<div class='error'>Sorry! Unable to write cookies. Please check your browser settings.</div>";
            }
            break;
    }
}

function get_product_name_by_coupon($db,$prefix,$coupon)
{
$qry_prod_cpn = "select * from " . $prefix . "coupon_codes where couponcode = '" . $coupon . "'";
$row_prod_cpn = $db->get_a_line($qry_prod_cpn);
return $short = $row_prod_cpn['prod'];

}


/* * ****************************************************************************************************** */

$objTpl = new TPLManager($FILEPATH . '/index.html');
$hotspots = $objTpl->getHotspotList();
$placeHolders = $objTpl->getPlaceHolders($hotspots);
$i = 0;
foreach ($placeHolders as $items) {
    $smarty->assign("$hotspots[$i]", "$items");
    $i++;
}
$smarty->assign('content', $error);
$smarty->assign('error', $error);
$smarty->display($FILEPATH . '/index.html');
?>