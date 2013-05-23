<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 session_start();
include_once("../common/config.php");
include_once("../common/autoresponders.php");
include_once("include.php");
include_once("session.php");
$randomstring = mysql_real_escape_string(trim($_POST["random"]));
$total_attempts = trim($_POST["try"]);
$item_number = trim($_POST["item_number"]);
$q = "select sitename, email_from_name, mailer_details from ".$prefix."site_settings";
$a = $db->get_a_line($q);
@extract($a);
$q = "select webmaster_email from ".$prefix."admin_settings where role='1' and status='1'";
$b = $db->get_a_line($q);
@extract($b);
$q = "select id as member_id from ".$prefix."members where randomstring='$randomstring'";
$c = $db->get_a_line($q);
@extract($c);
$product_sql = "select item_name,payer_email,payment_type,item_number,payment_status from ".$prefix."orders where randomstring='$randomstring' 
AND item_number = $item_number";

$row_product = $db->get_a_line($product_sql);
$product_id=$row_product['item_number'];
$product_name=$row_product['item_name'];
$payer_email=$row_product['payer_email'];
$payment_type=$row_product['payment_type'];
$payment_status = $row_product['payment_status'];
$q="select count(*) as cnt from ".$prefix."member_products where member_id='$memberid' && product_id='$product_id'";
$r=$db->get_a_line($q); 
$count=$r[cnt];
$obj_responder = new autoresponders('',$product_id);
if($count == 0 && $total_attempts == 119)
{
echo "Our system is unable to accept PayPal payment. Please wait our administrator will contact you soon. Sorry for inconveniences.";
 
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
$headers .= "From: System Generated <$email_from_name> " . "\r\n";	
$subject= 'Your '.$sitename.' purchase... complete your transaction now.';	
$message= '
    <p>Thank you for your purchase of '.$product_name.'.</p>
<p>To complete your transaction and access your purchase,</p>
<p>please click the link below now:</p>
<p><a href="'.$http_path.'/paypal_return.php?randomstring='.$randomstring.'">Click Here</a></p>
<p>Sincerly,
Administrator '.$sitename.'</p>
';
$common->sendemail('System Generated', $payer_email , $subject, $message, $headers);
$subject= 'Account Pending Transaction at '.$sitename;
$message= '
Admin:<br>
<p>'. $payer_email.' was sent an "Account Pending" email.</p>
<p>This could simply be because of failure to return to
your website to complete their transaction. </p>
<p>However, if you receive a number of these notices,
you may want to verify that your payment processor
is working correctly.</p>
<p>No action is required on your part.</p>
<p>The customer has been sent the "Account Pending"
email already.</p>
<p>If the account remains in the "Account Pending"
status, you can resend the email manually from
member management in your site admin area.</p>
<p>Regards,</p>
<p>System Admin '.$sitename.'<p>';
$common->sendemail('System Generated', '', $webmaster_email, $subject, $message, $headers);
echo '0';
}
else if($count==1)
{
  /******************  ADD TO AUTO RESPONDERS   ************************/
    $autoresponder = $obj_responder -> process_Autoresponders();
  /******************  END TO AUTO RESPONDERS   ************************/	
	if($payment_type=='echeck' && $payment_status != 'Completed')
	{
		$q = "select item_number, payer_email as email from ".$prefix."orders where randomstring='$randomstring'";
		$a = $db->get_a_line($q);
		@extract($a);
		
		// Get product name
		$q = "select product_name from ".$prefix."products where id='$item_number'";
		$c = $db->get_a_line($q);
		@extract($c);
				
		// send new member signup email to member.
		$q = "select subject, message from ".$prefix."emails where type='Email sent to new member after echeck payment'";
		$r = $db->get_a_line($q);
		@extract($r);					
		$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
		$message = preg_replace("/{(.*?)}/e","$$1",$message);
		$message = $message."\r\n\r\n".$mailer_details;
		$header	= "From: ".$email_from_name." <".$webmaster_email.">";
		@mail($email,$subject,$message,$header);
		echo $payment_type;	
		$autoresponder = $obj_responder -> process_Autoresponders();	
	}
	else if($payment_type=='echeck' && $payment_status == 'Completed')
	{
		echo '1';
	}
	else if($payment_status == 'Completed')
		echo '1';
	
	
}
	
?>