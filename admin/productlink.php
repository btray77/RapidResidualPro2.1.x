<?php
include_once("session.php");
$GetFile = file("../html/admin/productlink.html");
$Content = join("",$GetFile);

// Product query for coupons
$qry_prod_cpn = "select * from ".$prefix."coupon_codes where prod = '".$_REQUEST['product_name']."'";
$row_prod_cpn = $db->get_rsltset($qry_prod_cpn);
@extract($row_prod_cpn);

// Getting product info
$qry_prod = "select * from ".$prefix."products where pshort = '".$_REQUEST['product_name']."'";
$row_prod = $db->get_a_line($qry_prod);
if($row_prod['prodtype'] == 'Clickbank'){
	$direct_product_link = '<tr valign="top">
			<td nowrap="nowrap"  align="left" valign="middle" class="black">Hop Link:</td>
			<td align="left"><p>http://<span class="style1">affiliate_username</span>.';
			if(!empty($row_prod['click_bank_url'])){
				$direct_product_link .= $row_prod['click_bank_url'].'.hop.clickbank.net</p></td></tr>';
			}else{
				$direct_product_link .= 'hop.clickbank.net</p></td></tr>';
			}

}else{
	$direct_product_link = '<tr valign="top">
			<td nowrap="nowrap"  align="left" valign="middle" class="black">Default Affiliate Link:</td>
			<td align="left"><p>'.$http_path.'/to/<span class="style1">affiliate_username</span>/'.$product_name.'/products</p></td>
		  </tr><tr valign="top">
        <td nowrap="nowrap"  align="left" valign="middle" class="black">Direct Affiliate Link To Salesletter :</td>
        <td align="left"><p>'.$http_path.'/to/<span class="style1">affiliate_username</span>/'.$product_name.'/sales</p></td>
      </tr>';
}



$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
?>