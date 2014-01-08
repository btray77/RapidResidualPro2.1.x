<?php

$output = '';
include "include.php";
$path_to_curl = 'PHP';
// Get admin email and site email details		
$q = "select sitename, email_from_name, mailer_details from " . $prefix . "site_settings";
$a = $db->get_a_line($q);
@extract($a);
$q = "select webmaster_email from " . $prefix . "admin_settings where role='1'";
$b = $db->get_a_line($q);
@extract($b);
// Get sandbox setting from site settings
$q = "select paypal_sandbox from " . $prefix . "site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
if ($paypal_sandbox == "1")
    $pp_debug = 1;
else if ($paypal_sandbox == "0")
    $pp_debug = 0;

$post_string = '';
$output = '';
$valid_post = '';
$workString = 'cmd=_notify-validate';
/* Get PayPal Payment Notification variables including the encrypted code */
reset($_POST);
while (list($key, $val) = each($_POST)) {
    $post_string .= $key . '=' . $val . '&';
    $val = stripslashes($val);
    $val = urlencode($val);
    $workString .= '&' . $key . '=' . $val;
    $datalog.="\n" . $key . '=' . $val;
}

// assign posted variables to local variables
$item_name = trim(stripslashes($_POST['item_name']));
$item_number = trim(stripslashes($_POST['item_number']));
$payment_status = trim(stripslashes($_POST['payment_status']));
$payment_type = trim(stripslashes($_POST['payment_type']));
$payment_gross = trim(stripslashes($_POST['payment_gross']));
$txn_id = trim(stripslashes($_POST['txn_id']));
$payee_email = trim(stripslashes($_POST['receiver_email']));
$payer_email = trim(stripslashes($_POST['payer_email']));
$payment_date = trim(stripslashes($_POST['payment_date']));
$invoice = trim(stripslashes($_POST['invoice']));
$quantity = trim(stripslashes($_POST['quantity']));
$pending_reason = trim(stripslashes($_POST['pending_reason']));
$payment_method = trim(stripslashes($_POST['payment_method']));
$first_name = trim(stripslashes($_POST['first_name']));
$last_name = trim(stripslashes($_POST['last_name']));
$address_street = trim(stripslashes($_POST['address_street']));
$address_city = trim(stripslashes($_POST['address_city']));
$address_state = trim(stripslashes($_POST['address_state']));
$address_zipcode = trim(stripslashes($_POST['address_zip']));
$address_country = trim(stripslashes($_POST['address_country']));
$address_status = trim(stripslashes($_POST['address_status']));
$payer_status = trim(stripslashes($_POST['payer_status']));
$notify_version = trim(stripslashes($_POST['notify_version']));
$verify_sign = trim(stripslashes($_POST['verify_sign']));
$business = trim(stripslashes($_POST['business']));
$custom = trim(stripslashes($_POST['custom']));
$txn_type = trim(stripslashes($_POST['txn_type']));

$settle_amount = trim(stripslashes($_POST['settle_amount']));
$settle_currency = trim(stripslashes($_POST['settle_currency']));
$exchange_rate = trim(stripslashes($_POST['exchange_rate']));
$payment_fee = trim(stripslashes($_POST['payment_fee']));
$payment_amount = trim(stripslashes($_POST['mc_gross']));
$mc_fee = trim(stripslashes($_POST['mc_fee']));
$mc_currency = trim(stripslashes($_POST['mc_currency']));
$tax = trim(stripslashes($_POST['tax']));
$for_auction = trim(stripslashes($_POST['for_auction']));
$memo = trim(stripslashes($_POST['memo']));
$option_name1 = trim(stripslashes($_POST['option_name1']));
$option_selection1 = trim(stripslashes($_POST['option_selection1']));
$option_name2 = trim(stripslashes($_POST['option_name2']));
$option_selection2 = trim(stripslashes($_POST['option_selection2']));
$num_cart_items = trim(stripslashes($_POST['num_cart_items']));

// subscription variables
$username = trim(stripslashes($_POST['username']));
$password = trim(stripslashes($_POST['password']));
$subscr_id = trim(stripslashes($_POST['subscr_id']));
$subscr_date = trim(stripslashes($_POST['subscr_date']));
$subscr_effective = trim(stripslashes($_POST['subscr_effective']));
$period1 = trim(stripslashes($_POST['period1']));
$period2 = trim(stripslashes($_POST['period2']));
$period3 = trim(stripslashes($_POST['period3']));
$amount1 = trim(stripslashes($_POST['amount1']));
$amount2 = trim(stripslashes($_POST['amount2']));
$amount3 = trim(stripslashes($_POST['amount3']));
$mc_amount1 = trim(stripslashes($_POST['mc_amount1']));
$mc_amount2 = trim(stripslashes($_POST['mc_amount2']));
$mc_amount3 = trim(stripslashes($_POST['mc_amount3']));
$recurring = trim(stripslashes($_POST['recurring']));
$recur_times = trim(stripslashes($_POST['recur_times']));
$subscr_eot = trim(stripslashes($_POST['subscr_eot']));
$subscr_cancel = trim(stripslashes($_POST['subscr_cancel']));
$str = $_POST['custom'];

// post back to PayPal using cURL with https url
$url = ($pp_debug) ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr";

if ($path_to_curl == 'PHP') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $workString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);


    curl_close($ch);
} else {
    $arg3 = exec("$path_to_curl -d \"$workString\" $url", $arg1, $arg2);
    $return_string = "";
    $num_in_array = count($arg1);
    for ($i = 0; $i <= $num_in_array; $i++) {
        $return_string .= $arg1[$i] . "\n";
    }
    $output = $return_string;
}

$str = explode('|', $str);
$rand = $str[0];
$ip = $str[1];
$amt_owed = $str[2];
$pid = $str[3];
$ref = $str[4];
$ttype = $str[5];
$today = date('Y-m-d');

$q = "select * from " . $prefix . "products where id='$pid'";
$r = $db->get_a_line($q);
@extract($r);
$prod = $r['product_name'];
$price = $r['price'];
$prodtype = $r['prodtype'];
// open text log file
$log = fopen("ipn.log", "a+");
//fwrite($log, "\n\nipn - " . gmstrftime("%b %d %Y %H:%M:%S", time()) . "\n");
$values = "
You've received a new order at $sitename [$http_path]<br />
Here are your order details:<br />
Item Number: $item_number <br />
Product Name: $item_name <br />
Product Price: $mc_gross\n
Payment Status: $payment_status <br />
Transaction Id: $txn_id <br />
Payer Email:$payer_email <br />
To review this order in detail, log into your site Admin below: <br />
$http_path/admin <br />
";
$to = "$webmaster_email";
$to = "yasir509@gmail.com";
$header = 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$header .= "From: System Generated Email [$sitename] <$webmaster_email>";

//$post = print_r($_POST);
//fwrite($log, "Vals: " . $item_number . " " . $item_name . " " . $mc_gross . " " . $payment_status . " " . $pending_reason . " " . $txn_id . " " . $payer_email . " " . $rand . " " . $pp_debug . "\n ");


if ($pp_debug) {
    fputs($outputData, "RETURN STRING:$output\n\n");
}
//process payment
if (ereg('VERIFIED', $output)) {
    $valid_post = 'VERIFIED POST';
    // Process subscriptions before payment is made
    switch ($txn_type) {
        case 'subscr_signup':
          
            break; // end subscr_signup	

        case 'subscr_payment':

            $sql = "select count(*) as total from " . $prefix . "orders where subscriber_id='$subscr_id';";
            $rows = $db->get_a_line($sql);
            $count = $rows['total'];
			$string .= " \n SELECT ORDER SQL:  " . $sql . ' TOTAL RECORD: ' . $count;
			
			if ($count > 0) {
                $set = " payment_status='$payment_status', payment_type='$payment_type' where subscriber_id='$subscr_id'";
                $sql = "update " . $prefix . "orders set $set";
                $db->insert($sql);
			    $string .= " \n UPDATE ORDER SQL:  " . $sql;
				
				$sql = "select id from " . $prefix . "orders where subscriber_id='$subscr_id'";
				$row = $db->get_a_line($sql);
				$orderid = $row['id'];
				$string .= " \n SELECT ORDER SQL:  " . $sql . ' TOTAL RECORD: ' . $count;
				
				$sql = "select count(*) as total from " . $prefix . "member_products where txn_id='$subscr_id'";
				$row = $db->get_a_line($sql);
				$count = $row['total'];
				$string .= " \n SELECT MEMBER PRODUCT SQL:  " . $sql . ' TOTAL RECORD: ' . $count;	
				
				
				if ($count > 0) {
                    $sql = "select member_id,product_id from " . $prefix . "member_products where txn_id='$subscr_id'";
                    $row = $db->get_a_line($sql);
                    $member_id = $row['member_id'];
                    $product_id = $row['product_id'];

                    $set = "oid='$orderid'";
                    $set .= ", user_id='$member_id'";
                    $set .= ", product_id='$product_id'";
                    $set .= ", price='$mc_gross'";
                    $set .= ", subscribtion_id='$subscr_id'";
                    $set .= ", payment_status='$payment_status'";
                    $set .= ", reason='$reason'";
                    $set .= ", create_date='$today'";
                    $set .= ", payment_type='$payment_type'";
                    $q = "insert into " . $prefix . "subscription_payment_history set $set";
                   	$string .= " \n INSERT SUBSCRIBTION PAYMENT HISTORY SQL:  " . $q;
                    $db->insert($q);
					@mail($to, "New Order is completed", $values, $header);
                }
			 }
               
                /*                 * ********************************************************************* */
				$message = "
				Dear $first_name $last_name:
				<p>Thank you for purchasing $item_name!</p>
				
				<p>Please follow the link below to complete the signup process and
				log into your private member area at $sitename where access to 
				your purchase is now available to you.</p>
				<p>	
				" . $http_path . "/paypal_return.php?randomstring=" . $rand . "
				</p>
				Warmest Regards, 
				Site Admin";
				$string .= " \n ***** 	PROCESSING COMPLETED	******* \n "; //$payer_email
                @mail($payer_email, "Complete your account signup for $sitename", $message, $header);	
                /*                 * ************************************************************************** */
            
            break;
        case 'subscr_cancel':
            $set = "refunded='1' where txn_id='$subscr_id'";
            $q = "update " . $prefix . "member_products set $set";
            $db->insert($q);
            // Set order to refunded	
            $set = "payment_status='Cancelled' where subscriber_id='$subscr_id'";
            $q = "update " . $prefix . "orders set $set";
            $db->insert($q);
            break;
        case 'recurring_payment_suspended_due_to_max_failed_payment':
            $set = "refunded='1' where txn_id='$recurring_payment_id'";
            $q = "update " . $prefix . "member_products set $set";
            $db->insert($q);
            break;
    } // End switch txn_type
    if (eregi('Pending', $payment_status)) {
        // Process pending payments
        switch ($txn_type) {
            case 'web_accept':
                $q = "select count(*) as cnt from " . $prefix . "orders where txnid='$txn_id'";
                $r = $db->get_a_line($q);
                $count = $r[cnt];
                if ($count > '0') {
                    // insert into orders table
                    $set = " payment_status='$payment_status' where txnid='$subscr_id'";
                    $sql = "update " . $prefix . "orders set $set";
                    $db->insert($sql);
                } // end insert into orders table	
                break; // end web_accept

            case 'subscr_payment':
                $q = "select count(*) as cnt from " . $prefix . "orders where subscriber_id='$subscr_id'";
                $r = $db->get_a_line($q);
                $count = $r[cnt];
                if ($count > '0') {
                    // insert into orders table
                    $set = " payment_status='$payment_status' where subscriber_id='$subscr_id'";
                    $sql = "update " . $prefix . "orders set $set";
                    $db->insert($sql);
                    $set = "refunded='1' where txn_id='$subscr_id'";
                    $q = "update " . $prefix . "member_products set $set";
                    $db->insert($q);
					
                } // end insert into orders table	
                break;
        }
    } // end pending
    if (eregi('Refunded', $payment_status)) {
        // Set member product to refunded
        switch ($txn_type) {
            case 'web_accept':
                $set = "refunded='1' where txn_id='$txn_id'";
                $q = "update " . $prefix . "member_products set $set";
                $db->insert($q);

                $set = "payment_status='$payment_status' where txnid='$txn_id'";
                $q = "update " . $prefix . "orders set $set";
                $db->insert($q);
                break;

            case 'subscr_payment':
                $set = "refunded='1' where txn_id='$subscr_id'";
                $q = "update " . $prefix . "member_products set $set";
                $db->insert($q);

                $set = "payment_status='$payment_status' where subscriber_id='$subscr_id'";
                $q = "update " . $prefix . "orders set $set";
                $db->insert($q);
                break;
        }
        // Set order to refunded	
    } // end refunded
    if (eregi('Reversed', $payment_status)) {
        paypal_ipn_failed($payment_status, $item_number, $txn_id, $subscr_id, $pending_reason, $mc_gross);
    } // end reversed
    if (eregi('Completed', $payment_status)) {
        switch ($txn_type) {
            // The payment was sent by your customer via Buy Now Buttons, Donations, or Auction Smart Logos
            case 'web_accept':
                case 'web_accept':
                $q = "select count(*) as cnt from " . $prefix . "orders where txnid='$txn_id'"; // && payment_type='echeck'
                $r = $db->get_a_line($q);
                $count = $r[cnt];
                if ($count == '0') {
                    // insert into orders table
                    $set = "item_number='$item_number'";
                    $set .= ", item_name='$prod'";
                    $set .= ", date='$today'";
                    $set .= ", payment_amount='$mc_gross'";
                    $set .= ", payment_status='$payment_status'";
                    $set .= ", pending_reason='$pending_reason'";
                    $set .= ", txnid='$txn_id'";
                    $set .= ", payer_email='$payer_email'";
                    $set .= ", payee_email='$business'";
                    $set .= ", referrer='$ref'";
                    $set .= ", randomstring='$rand'";
                    $set .= ", payment_type='$payment_type'";
                    $q = "insert into " . $prefix . "orders set $set";
                    $db->insert($q);
                }
                // end insert into orders table
                elseif ($count > '0') {
                    // Pending order cleared
                    // Clear pending from order
                    $set = "pending_reason='', payment_status='Completed' where txnid='$txn_id'";
                    $q = "update " . $prefix . "orders set $set";
                    $db->insert($q);
                } // end clear pending order

                switch ($ttype) {
                    case 'outside':
                        // Normal payment or echeck?
                        $q = "select payment_type from " . $prefix . "orders where txnid='$txn_id'";
                        $c = $db->get_a_line($q);
                        @extract($c);

                        if ($payment_type == 'echeck') {
                            // Set member product to give access
                            $set = "refunded='0' where txn_id='$txn_id'";
                            $q = "update " . $prefix . "member_products set $set";
                            $db->insert($q);

                            // Send signup email to member
                            // Get product name
                            $q = "select product_name from " . $prefix . "products where id='$item_number'";
                            $c = $db->get_a_line($q);
                            @extract($c);
                            $signup_link = $http_path . "/signup.php?randomstring=" . $rand;

                            // send new member signup email to member
                            $q = "select subject, message from " . $prefix . "emails where type='Email sent to new member after echeck clears'";
                            $r = $db->get_a_line($q);
                            @extract($r);
                            $subject = preg_replace("/{(.*?)}/e", "$$1", $subject);
                            $message = preg_replace("/{(.*?)}/e", "$$1", $message);
                            $message = $message . "\r\n\r\n" . $mailer_details;
                            $header = "From: " . $email_from_name . " <" . $webmaster_email . ">";
                            @mail($payer_email, $subject, $message, $header);
                        } else {
                            // not an echeck					
                            // Enter member into database
                            $now = time();
                            $set = "date_joined = now(),";
                            $set .= "ip='$ip',";
                            $set .= "firstname='Account',";
                            $set .= "lastname='Pending',";
                            $set .= "email='$payer_email',";
                            $set .= "last_login = $now,";
                            $set .= "ref='$ref',";
                            $set .= "randomstring = '$rand'";
                            $mid = $db->insert_data_id("insert into " . $prefix . "members set $set");

                            // Get member id
                            $q = "select id as mid from " . $prefix . "members where randomstring='$rand'";
                            $r = $db->get_a_line($q);
                            @extract($r);

                            // insert into member products table			
                            $set = "member_id='$mid'";
                            $set .= ", product_id='$pid'";
                            $set .= ", date_added='$today'";
                            $set .= ", txn_id='$txn_id'";
                            $set .= ", type='$prodtype'";
                            $mid = $db->insert_data_id("insert into " . $prefix . "member_products set $set");
							
							
                        }
                        break; // end outside			

                    case 'inside':
                        $q = "select count(*) as cnt from " . $prefix . "member_products where txn_id='$txn_id'";
                        $r = $db->get_a_line($q);
                        $count = $r[cnt];
                        if ($count == '0') {
                            // get member id
                            $q = "select id as mid from " . $prefix . "members where randomstring='$rand'";
                            $r = $db->get_a_line($q);
                            @extract($r);

                            // enter into member products
                            $set = "member_id='$mid'";
                            $set .= ", product_id='$pid'";
                            $set .= ", date_added='$today'";
                            $set .= ", txn_id='$txn_id'";
                            $set .= ", type='$prodtype'";
                            $q = "insert into " . $prefix . "member_products set $set";
                            $db->insert($q);
                        } elseif ($count != '0') {
                            $set = "refunded='0', date_added='$today' where txn_id='$txn_id'";
                            $q = "update " . $prefix . "member_products set $set";
                            $db->insert($q);
                        }
                        break; // end inside			
                }
                        @mail($to, "New Order is completed", $values, $header);
                        /************************************************************************/
                        $message="
                        Dear $first_name $last_name:
                        <p>Thank you for purchasing $item_name!</p>

                        <p>Please follow the link below to complete the signup process and
                        log into your private member area at $sitename where access to 
                        your purchase is now available to you.</p>
                        <p>	
                        ".$http_path."/paypal_return.php?randomstring=".$rand."
                        </p>
                        Warmest Regards, 
                        Site Admin";

                        @mail($payer_email, "Complete your account signup for $sitename", $message, $header);	
                        /*****************************************************************************/
                break; // end web_accept
                /*                 * ************************************************************************** */
                break; // end web_accept
            // This payment was sent by your customer via the PayPal Shopping Cart feature
            case 'cart':
                break;

            // This payment was sent by your customer from the PayPal website, using the "Send Money" tab
            case 'send_money':
                break;


            // This IPN is for a subscription sign-up
            case 'subscr_signup':
                break;

            // This IPN is for a subscription cancellation
            // remove client Info to subscription table
            case 'subscr_cancel':
                break;

            // This IPN is for a subscription modification
            // update client info in subscription table
            case 'subscr_modify':
                break;

            // This IPN is for a subscription payment failure
            // Subscription Failure
            case 'subscr_failed':
                break;


            // This IPN is for a subscription's end of term
            // update/cancel client info in subscription table.. End of term request.
            case 'subscr_eot':
                break;
        } // end switch (txn_type)
    }   // end payment status complete
}   // end VERIFIED response from paypal
if ($pp_debug) {
    fclose($outputData);
}
fwrite($log, "\n " . $datalog);
fclose($log);
exit;
?>
