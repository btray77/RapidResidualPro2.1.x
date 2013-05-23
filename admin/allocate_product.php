<?php
include_once("session.php");
include_once("header.php");
include_once("../common/autoresponders.php");
$GetFile = file("../html/admin/allocate_product.html");
$Content = join("", $GetFile);
$Title = "Allocate Products";
$today	= date("Y-m-d");
if (isset($_POST['allocate']))
{
	// Get product type
	$q = "select * from ".$prefix."products where id='$product'" ;
	$aa = $db->get_a_line($q);
	@extract($aa);
	$prodtype	= $aa['prodtype'];
	$obj_responder = new autoresponders('',$product);
	
	for ($i=0; $i < count($members); $i++)
	{
		// Cycle through products to see if member already has selected products
		$mid	= $members[$i];
		if($mid!=0)
		{
			echo $q = "select count(*) as cnt from ".$prefix."member_products where member_id='$mid' and product_id='$product'";
			$r = $db->get_a_line($q);
			
			if($r['cnt'] < 1)
			{
				// Member doesn't have slected product yet so add it to the member product table
				$q = "insert into ".$prefix."member_products set 
                                        product_id='$product', 
                                        type='$prodtype', 
										txn_id='ALLOCATED',
                                        date_added='$today', 
                                        member_id='$mid'";
				 $db->insert($q);
                                    
				$q = "select * from ".$prefix."members where id='$mid'";
				$r = $db->get_a_line($q);
				@extract($r);
				$ip = $r['ip'];
				$q = "select * from ".$prefix."products where id='$product'";
				$r = $db->get_a_line($q);
				@extract($r);
				$short = $r['pshort'];
                                
                                $sql_settings = "select paypal_email,sandbox_paypal_email,paypal_sandbox,alertpay_merchant_email from ".$prefix."site_settings";
                                $row_settings = $db->get_a_line($sql_settings);
                                if($row_settings['paypal_sandbox']==1 && empty ($row_settings['paypal_email']) )
                                $payee= $row_settings['sandbox_paypal_email'];
                                else
                                $payee= $row_settings['paypal_email'];
                                if(empty($payee))
                                {
                                $payee =$row_settings['alertpay_merchant_email'];
                                }
                                
                                $set	= "item_number='$id'";
                                $set	.= ", item_name='$product_name'";
                                $set	.= ", date='$today'";
                                $set	.= ", payment_amount='0.00'";
                                $set	.= ", payment_status='Completed'";
                                $set	.= ", pending_reason=''";
                                $set	.= ", txnid='ALLOCATED'";
                                $set	.= ", randomstring='$randomstring'";
                                $set	.= ", payer_email='$email'";
                                $set	.= ", payee_email='$payee'";
                                $set	.= ", referrer=''";
                                $set	.= ", payment_gateway='Allocate'";
                                $set	.= ", payment_type='instant'";
                                  $q = "insert into ".$prefix."orders set $set";
                                 $db->insert($q);
                               
         /******************  ADD TO AUTO RESPONDERS   ************************/
			 $_SESSION['memberid'] = $mid;
             $autoresponder = $obj_responder -> process_Autoresponders();
          /******************  END TO AUTO RESPONDERS   ************************/	              
                                
				// Add to click stats so conversion tracking is correct
				$q = "insert into ".$prefix."click_stats set visited_date='$today', referrer='$ref', ip='$ip', product='$short'";
				$db->insert($q);
				header("location: paid_products.php?msg=all");
			}
			else{
			header("location: paid_products.php?msg=all");
			}
		}
	}
	header("location: paid_products.php?msg=all");
}
// List Products
$q = "select product_name, id from ".$prefix."products order by product_name";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$sno		= $sno+1;
	$pid		= $id;
	$pname		= stripslashes($product_name);
	$prod.="<option value='$pid'>$pname</option>";
}
$qq = "select firstname, lastname, id, username  from ".$prefix."members order by firstname, lastname, username";
$rr = $db->get_rsltset($qq);
for($j=0; $j<count($rr); $j++)
{
	$mid=$rr[$j]['id'];
	$uname=$rr[$j]['username'];
	$mname=$rr[$j][firstname].' '.$rr[$j][lastname].' ( '.$uname.' )';
	$mem	.= "<option value='$mid'>$mname</option>";
}
$Content = preg_replace("/{{add_hide_begin}}(.*?){{add_hide_end}}/s","",$Content);
$Content = preg_replace($Ptn,"$$1",$Content) ;
echo $Content ;
include_once("footer.php");
?>