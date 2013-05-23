<?php

class AlertPay {

    public $actionUrl;
    public $isTestMode = 1;
    public $ipnCode;
    public $id;
    public $prefix;
    public $db;
    public $memberId;

    function __construct($id=1) {
        $this->id = $id;
        global $db, $prefix;
        $this->prefix = $prefix;
        $this->db = $db;
        $query = "SELECT `alertpay_merchant_email`, `alertpay_action_url`, `alertpay_test_mode`, `alertpay_ipn_code` 
                    FROM {$prefix}site_settings WHERE id = {$this->id}";
        $rec = $db->get_a_line($query);
        $this->actionUrl = $rec['alertpay_action_url'];
        $this->isTestMode = $rec['alertpay_test_mode'];
        //$this->ipnCode = $rec['alertpay_ipn_code'];
        if ($this->isTestMode == '1')
            $this->actionUrl = 'https://sandbox.alertpay.com/sandbox/checkout';
        else
            $this->actionUrl = 'https://www.alertpay.com/PayProcess.aspx';
    }

    function get_member($random, $ip, $referer, $fName, $lName, $address, $city, $state, $zip, $country, $email) {
        $query = "SELECT id FROM {$this->prefix}members WHERE randomstring = '{$random}'";
        $rec_member = $this->db->get_a_line($query);
        $log .= "Member Area Query\n" . $query . "\n";
        $fp = fopen('ipn.log', 'a+');
        fwrite($fp, $log);
        $log = "";
        if (is_numeric($rec_member['id']) && $rec_member['id'] > 0) {
            return $rec_member['id'];
        } else {
            $query = "INSERT INTO {$this->prefix}members SET firstname = '{$fName}', lastname = '{$lName}', ip = '{$ip}', email = '{$email}', 
                        address_street = '{$address}', address_city = '{$city}', address_state = '{$state}', address_zipcode = '{$zip}',
                        address_country = '{$country}', randomstring = '{$random}', ref = '{$referer}', date_joined = NOW()";
            return $this->db->insert_data_id($query);
        }
    }

    function set_order($product_id, $product_name, $price, $payment_status, $buyer_email, $seller_email, $referer, $random_string, $payment_type, $txn_id) {
        $payment_status = str_replace('Subscription-', '', $payment_status);
        $payment_status = str_replace('Payment-', '', $payment_status);
        if ($payment_status == 'Success')
            $payment_status = 'Completed';
        $set .= " payment_status='$payment_status'";
        $query = "SELECT id FROM {$this->prefix}orders WHERE randomstring='$random_string' 

            AND payee_email='$seller_email' AND item_number = $product_id AND payment_status='$payment_status'";
        $exist = $this->db->get_a_line($query);
        $log .= "\n" . $query . "\n";
        $order_id = $exist['id'];
        if (is_numeric($order_id) && $order_id > 0) {
            //	$query = "UPDATE {$this->prefix}orders SET {$set} WHERE id = {$order_id}";				
            //	$this->db->insert($query);		
        } else {
            $set .= ", item_number='$product_id'";
            $set .= ", item_name='$product_name'";
            $set .= ", date=NOW()";
            $set .= ", payment_amount='$price'";
            $set .= ", pending_reason='$pending_reason'";
            $set .= ", txnid='$txn_id'";
            $set .= ", payer_email='$buyer_email'";
            $set .= ", payee_email='$seller_email'";
            $set .= ", referrer='$referer'";
            $set .= ", randomstring='$random_string'";
            $set .= ", payment_type='$payment_type'";
            $set .= ", payment_gateway='AlertPay'";
            $query = "INSERT INTO {$this->prefix}orders SET {$set} ";
            $order_id = $this->db->insert_data_id($query);
        }
        $log .= "\n" . $query . "\n";
        $fp = fopen('ipn.log', 'a+');
        fwrite($fp, $log);
        $log = "";
        return $order_id;
    }

    function add_product_to_member($member_id, $product_id, $transaction_id, $product_type) {
        $query = "SELECT COUNT(*) as count FROM {$this->prefix}member_products 
            					WHERE member_id = {$member_id} AND product_id = {$product_id}";
        $products = $this->db->get_a_line($query);
        if ($products['count'] > 0)
            return false;
        $set = "member_id='$member_id'";
        $set .= ", product_id='$product_id'";
        $set .= ", date_added=NOW()";
        $set .= ", txn_id='$transaction_id'";
        $set .= ", type='$product_type'";
        $q = "insert into " . $this->prefix . "member_products set $set";
        $this->db->insert($q);
    }

    function process() {
        
    }

    function button($random_code, $ip, $amount, $pid, $product_name, $product_type, $referer, $register_status, $alpertpay_return_url, $receiver_alertpay , $receiver_alertpay_ipn) {
        global $http_path;
        $this->account = $receiver_alertpay;
		$this->ipnCode = $receiver_alertpay_ipn;
		
        if (empty($http_path))
            $http_path = "http://www.rapidresidualpro.com/";
        $query = "SELECT COUNT(*) as total FROM {$this->prefix}members WHERE randomstring = '{$random_code}' ";
        $user = $this->db->get_a_line($query);
        $query = "SELECT alertpay_image FROM {$this->prefix}products WHERE id = '{$pid}' ";
        $product_image = $this->db->get_a_line($query);
        if (empty($product_image['alertpay_image']))
            $image = '/images/payment_buttons/alertpay-button.gif';
        else
            $image = $http_path . "" . str_replace("..", "", $product_image['alertpay_image']);
        if (empty($alpertpay_return_url)) {
            //$return_url = ($user['total']>0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" :	"{$http_path}/signup.php?randomstring=$random_code" ;
            $return_url = ($user['total'] > 0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" : "{$http_path}/alertpay_return.php?randomstring=$random_code";
        }
        else
            $return_url=$alpertpay_return_url;
        if ($this->isTestMode)
            $test_mode = "<input type=\"hidden\" name=\"ap_test\" value=\"1\" />";

        $button = "<form method=\"post\" action=\"{$this->actionUrl}\" >
                                    <input type=\"hidden\" name=\"ap_merchant\" value=\"{$receiver_alertpay}\"/>
                                    <input type=\"hidden\" name=\"ap_purchasetype\" value=\"item-goods\"/>
                                    <input type=\"hidden\" name=\"ap_itemcode\" value=\"{$pid}\"/>
                                    <input type=\"hidden\" name=\"ap_itemname\" value=\"{$product_name}\"/>
                                    <input type=\"hidden\" name=\"ap_amount\" value=\"{$amount}\"/>
                                    <input type=\"hidden\" name=\"ap_currency\" value=\"USD\"/>
                                    <input type=\"hidden\" name=\"ap_cancelurl\" value=\"{$http_path}/signup.php\" />
                                    <input type=\"hidden\" name=\"ap_returnurl\" value=\"{$return_url}\" />
                                    <input type=\"hidden\" name=\"apc_1\" value=\"{$random_code}\" />
                                    <input type=\"hidden\" name=\"apc_2\" value=\"{$ip}\" />
                                    <input type=\"hidden\" name=\"apc_3\" value=\"{$referer}\" />
                                    <input type=\"hidden\" name=\"apc_4\" value=\"{$register_status}\" />
                                    <input type=\"hidden\" name=\"apc_5\" value=\"{$product_type}\" />
                                    {$test_mode}
                                    <input type=\"image\" name=\"ap_image\" src=\"$image\"/>    

                            </form>";

        return $button;
    }

    function button_hidden($random_code, $ip, $amount, $pid, $product_name, $product_type, $referer, $register_status, $alpertpay_return_url, $receiver_alertpay , $receiver_alertpay_ipn) {
        global $http_path;
      $this->account = $receiver_alertpay;
	  $this->ipnCode = $receiver_alertpay_ipn;
        if (empty($http_path))
            $http_path = "http://www.rapidresidualpro.com/";
        $query = "SELECT COUNT(*) as total FROM {$this->prefix}members WHERE randomstring = '{$random_code}' ";
        $user = $this->db->get_a_line($query);
        $query = "SELECT alertpay_image FROM {$this->prefix}products WHERE id = '{$pid}' ";
        $product_image = $this->db->get_a_line($query);
        if (empty($product_image['alertpay_image']))
            $image = '/images/payment_buttons/alertpay-button.gif';
        else
            $image = $http_path . "" . str_replace("..", "", $product_image['alertpay_image']);
        if (empty($alpertpay_return_url)) {
            //$return_url = ($user['total']>0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" :	"{$http_path}/signup.php?randomstring=$random_code" ;
            $return_url = ($user['total'] > 0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" : "{$http_path}/alertpay_return.php?randomstring=$random_code";
        }
        else
            $return_url=$alpertpay_return_url;
        if ($this->isTestMode)
            $test_mode = "<input type=\"hidden\" name=\"ap_test\" value=\"1\" />";

        $button = "<form method=\"post\" action=\"{$this->actionUrl}\" name=\"paymentfrm\">
                                    <input type=\"hidden\" name=\"ap_merchant\" value=\"{$this->account}\"/>
                                    <input type=\"hidden\" name=\"ap_purchasetype\" value=\"item-goods\"/>
                                    <input type=\"hidden\" name=\"ap_itemcode\" value=\"{$pid}\"/>
                                    <input type=\"hidden\" name=\"ap_itemname\" value=\"{$product_name}\"/>
                                    <input type=\"hidden\" name=\"ap_amount\" value=\"{$amount}\"/>
                                    <input type=\"hidden\" name=\"ap_currency\" value=\"USD\"/>
                                    <input type=\"hidden\" name=\"ap_cancelurl\" value=\"{$http_path}/signup.php\" />
                                    <input type=\"hidden\" name=\"ap_returnurl\" value=\"{$return_url}\" />
                                    <input type=\"hidden\" name=\"apc_1\" value=\"{$random_code}\" />
                                    <input type=\"hidden\" name=\"apc_2\" value=\"{$ip}\" />
                                    <input type=\"hidden\" name=\"apc_3\" value=\"{$referer}\" />
                                    <input type=\"hidden\" name=\"apc_4\" value=\"{$register_status}\" />
                                    <input type=\"hidden\" name=\"apc_5\" value=\"{$product_type}\" />
                                   {$test_mode}
                                </form>
                                <SCRIPT LANGUAGE='JavaScript'>
                                    function Submit() {
                                    window.document.paymentfrm.submit();
                                    return;
                                    }
                                </SCRIPT>
                                   ";

        return $button;
    }

    function __toString() {

        echo '<pre>';
        print_r($this);
        echo '</pre>';
    }

    function get_period($period) {

        switch ($period) {

            case 'D':
                return 'Day';
                break;
            case 'W':
                return 'Week';
                break;
            case 'M':
               return 'Month';
                break;
            case 'Y':
                return 'Year';
                break;
            default:
                return 'Month';
                break;
        }
    }

    function subscription_button($random_code, $ip, $pid, $product_name, $product_type, $referer, $register_status, $billing_cycle, $trial, $alpertpay_return_url, $receiver_alertpay,$receiver_alertpay_ipn) 
	{
	
	
			$this->account = $receiver_alertpay;
			$this->ipnCode = $receiver_alertpay_ipn;
			
			$query = "SELECT COUNT(*) as total FROM {$this->prefix}members WHERE randomstring = '{$random_code}' ";
			$user = $this->db->get_a_line($query);
			global $http_path;
			$query = "SELECT period1_active,alertpay_image FROM {$this->prefix}products WHERE id = '{$pid}' ";
			$product_image = $this->db->get_a_line($query);
			if (empty($product_image['alertpay_image']))
				$image = 'https://www.alertpay.com/PayNow/4F59239578EA46C1AD168BA6E9BD2067g.gif';
			else
				$image = $http_path . "" . str_replace("..", "", $product_image['alertpay_image']);
			if (empty($http_path))
				$http_path = "http://www.rapidresidualpro.com/";
			if (empty($alpertpay_return_url)) {
				//$return_url = ($user['total']>0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" :	"{$http_path}/signup.php?randomstring=$random_code" ;
				$return_url = ($user['total'] > 0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" : "{$http_path}/alertpay_return.php?randomstring=$random_code";
			}
			else
				$return_url=$alpertpay_return_url;
			if ($this->isTestMode)
				$test_mode = "<input type=\"hidden\" name=\"ap_test\" value=\"1\" />";
			if ($product_image['period1_active'] == 1) {
				$trial_params = "<input type=\"hidden\" name=\"ap_trialtimeunit\" value=\"" . $this->get_period($trial['period']) . "\" />
									<input type=\"hidden\" name=\"ap_trialperiodlength\" value=\"{$trial[interval]}\" />    
									<input type=\"hidden\" name=\"ap_trialamount\" value=\"{$trial[amount]}\" />";
			}
			if ($billing_cycle['limit'] > 0)
				$num_of_transactions = "<input type=\"hidden\" name=\"ap_periodcount\" value=\"{$billing_cycle[limit]}\" />";
			$button = "<form method=\"post\" action=\"{$this->actionUrl}\" >
					<input type=\"hidden\" name=\"ap_merchant\" value=\"{$this->account}\"/>
					<input type=\"hidden\" name=\"ap_purchasetype\" value=\"subscription\"/>
					<input type=\"hidden\" name=\"ap_itemcode\" value=\"{$pid}\"/>
					<input type=\"hidden\" name=\"ap_itemname\" value=\"{$product_name}\"/>
					<input type=\"hidden\" name=\"ap_amount\" value=\"{$billing_cycle[amount]}\"/>
					<input type=\"hidden\" name=\"ap_currency\" value=\"USD\"/>
					<input type=\"hidden\" name=\"ap_cancelurl\" value=\"{$http_path}/member/payment_cancel.php\" />
					<input type=\"hidden\" name=\"ap_returnurl\" value=\"{$return_url}\" />
					<input type=\"hidden\" name=\"ap_timeunit\" value=\"" . $this->get_period($billing_cycle['period']) . "\" />
					<input type=\"hidden\" name=\"ap_periodlength\" value=\"{$billing_cycle[interval]}\" />
					{$num_of_transactions}
					{$trial_params}	
					<input type=\"hidden\" name=\"apc_1\" value=\"{$random_code}\" />
					<input type=\"hidden\" name=\"apc_2\" value=\"{$ip}\" />
					<input type=\"hidden\" name=\"apc_3\" value=\"{$referer}\" />
					<input type=\"hidden\" name=\"apc_4\" value=\"{$register_status}\" />
					<input type=\"hidden\" name=\"apc_5\" value=\"{$product_type}\" />
					<input type=\"image\" name=\"ap_image\" src=\"$image\"/>    
					$test_mode
					</form>
					 
					";
			return $button;
		
	
	}

    function subscription_button_hidden($random_code, $ip, $pid, $product_name, $product_type, $referer, $register_status, $billing_cycle, $trial, $alpertpay_return_url, $receiver_alertpay,$receiver_alertpay_ipn) 
	{
	

        $this->account = $receiver_alertpay;
		$this->ipnCode = $receiver_alertpay_ipn;
        $query = "SELECT COUNT(*) as total FROM {$this->prefix}members WHERE randomstring = '{$random_code}' ";
        $user = $this->db->get_a_line($query);
        global $http_path;
        $query = "SELECT period1_active,alertpay_image FROM {$this->prefix}products WHERE id = '{$pid}' ";
        $product_image = $this->db->get_a_line($query);
        if (empty($product_image['alertpay_image']))
            $image = 'https://www.alertpay.com/PayNow/4F59239578EA46C1AD168BA6E9BD2067g.gif';
        else
            $image = $http_path . "" . str_replace("..", "", $product_image['alertpay_image']);
        if (empty($http_path))
            $http_path = "http://www.rapidresidualpro.com/";
        if (empty($alpertpay_return_url)) {
            //$return_url = ($user['total']>0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" :	"{$http_path}/signup.php?randomstring=$random_code" ;
            $return_url = ($user['total'] > 0) ? "{$http_path}/member/downloads.php?randomstring=$random_code" : "{$http_path}/alertpay_return.php?randomstring=$random_code";
        }
        else
            $return_url=$alpertpay_return_url;
        if ($this->isTestMode)
            $test_mode = "<input type=\"hidden\" name=\"ap_test\" value=\"1\" />";
        if ($product_image['period1_active'] == 1) {
            $trial_params = "<input type=\"hidden\" name=\"ap_trialtimeunit\" value=\"" . $this->get_period($trial['period']) . "\" />
                                <input type=\"hidden\" name=\"ap_trialperiodlength\" value=\"{$trial[interval]}\" />    
                            	<input type=\"hidden\" name=\"ap_trialamount\" value=\"{$trial[amount]}\" />";
        }
        if ($billing_cycle['limit'] > 0)
            $num_of_transactions = "<input type=\"hidden\" name=\"ap_periodcount\" value=\"{$billing_cycle[limit]}\" />";
        $button = "<form method=\"post\" action=\"{$this->actionUrl}\" name=\"paymentfrm\">
                <input type=\"hidden\" name=\"ap_merchant\" value=\"{$receiver_alertpay}\"/>
                <input type=\"hidden\" name=\"ap_purchasetype\" value=\"subscription\"/>
                <input type=\"hidden\" name=\"ap_itemcode\" value=\"{$pid}\"/>
                <input type=\"hidden\" name=\"ap_itemname\" value=\"{$product_name}\"/>
                <input type=\"hidden\" name=\"ap_amount\" value=\"{$billing_cycle[amount]}\"/>
                <input type=\"hidden\" name=\"ap_currency\" value=\"USD\"/>
                <input type=\"hidden\" name=\"ap_cancelurl\" value=\"{$http_path}/member/payment_cancel.php\" />
                <input type=\"hidden\" name=\"ap_returnurl\" value=\"{$return_url}\" />
                <input type=\"hidden\" name=\"ap_timeunit\" value=\"" . $this->get_period($billing_cycle['period']) . "\" />
                <input type=\"hidden\" name=\"ap_periodlength\" value=\"{$billing_cycle[interval]}\" />
                {$num_of_transactions}
                {$trial_params}	
                <input type=\"hidden\" name=\"apc_1\" value=\"{$random_code}\" />
                <input type=\"hidden\" name=\"apc_2\" value=\"{$ip}\" />
                <input type=\"hidden\" name=\"apc_3\" value=\"{$referer}\" />
                <input type=\"hidden\" name=\"apc_4\" value=\"{$register_status}\" />
                <input type=\"hidden\" name=\"apc_5\" value=\"{$product_type}\" />
                 
                $test_mode
				<SCRIPT LANGUAGE='JavaScript'>
                                    function Submit() {
                                    window.document.paymentfrm.submit();
                                    return;
                                    }
                                </SCRIPT>
                </form>
                 
                ";
        return $button;
    
	}

    function subscription_cancel($transaction_referrence_number) {

        $query = "UPDATE {$this->prefix}member_products SET refunded = 1 WHERE txn_id = '{$transaction_referrence_number}'";

        $this->db->insert($query);
    }

    function get_ipn_code($email, $pid , $ref) {

        $query = "SELECT alertpay_ipn_code, `partner_alertpay_email`, `second_partner_paypal_email`, `partner1_alertpay_ipn_code`, `partner2_alertpay_ipn_code` 
				FROM {$this->prefix}site_settings;";

        $partners = $this->db->get_a_line($query);
        $partners1 = $partners['partner_alertpay_email'];
        $partners_ipn1 = $partners['partner1_alertpay_ipn_code'];
        $partners2 = $partners['second_partner_paypal_email'];
        $partners_ipn2 = $partners['partner2_alertpay_ipn_code'];
        $alert_pay_ipn = $partners['alertpay_ipn_code'];

		$sql = " SELECT enable_product_partner, product_partner_alertpay_email, ap_partner_ipn_security_code FROM " . $this->prefix . "products WHERE id='$pid'";
    	$row = $this->db->get_a_line($sql); 
   
    	if($row['enable_product_partner']==1){
			$porduct_partner_alertpay_ipn   = $row['ap_partner_ipn_security_code'];
			$porduct_partner_alertpay_email   = $row['product_partner_alertpay_email'];		
		}
    
			
	
        if (!empty($ref)) {

            $query = "SELECT `alertpay_email`, `alertpay_ipn_code` 	FROM {$prefix}site_settings WHERE username = {$ref}";
            $members = $this->db->get_a_line($query);
            $member = $members['alertpay_email'];
            $member_ipn = $members['alertpay_ipn_code'];
        }

        if ($email == $partners1 && !empty($partners_ipn1)) {
            return $partners_ipn1;
        }
		else if ($email == $partners2 && !empty($partners_ipn2)) {
            return $partners_ipn2;
        } else if ($email == $member && !empty($member_ipn)) {
            return $member_ipn;
        }
		else if ($email == $porduct_partner_alertpay_email && !empty($porduct_partner_alertpay_ipn)) {
            return $porduct_partner_alertpay_ipn;
        }
	  else
           return $alert_pay_ipn;
    }

    function ipn() 
	{



        //Setting information about the transaction from the IPN post variables

        $mySecurityCode = urldecode($_POST['ap_securitycode']);

        $receivedMerchantEmailAddress = urldecode($_POST['ap_merchant']);

        $transactionStatus = urldecode($_POST['ap_status']);

        $testModeStatus = urldecode($_POST['ap_test']);

        $purchaseType = urldecode($_POST['ap_purchasetype']);

        $currency = urldecode($_POST['ap_currency']);

        $totalAmountReceived = urldecode($_POST['ap_totalamount']);

        $feeAmount = urldecode($_POST['ap_feeamount']);

        $netAmount = urldecode($_POST['ap_netamount']);

        $transactionReferenceNumber = urldecode($_POST['ap_referencenumber']);

        $transactionDate = urldecode($_POST['ap_transactiondate']);

        $transactionType = urldecode($_POST['ap_transactiontype']);



        //Setting the subscription's information from the IPN post variables

        $subscriptionReferenceNumber = urldecode($_POST['ap_subscriptionreferencenumber']);

        $subscriptionSetupCost = urldecode($_POST['ap_setupamount']);

        $subscriptionTimeUnit = urldecode($_POST['ap_timeunit']);

        $subscriptionPeriodLength = urldecode($_POST['ap_periodlength']);

        $subscriptionPeriodCount = urldecode($_POST['ap_periodcount']);

        $subscriptionTrialAmount = urldecode($_POST['ap_trialamount']);

        $subscriptionTrialTimeUnit = urldecode($_POST['ap_trialtimeunit']);

        $subscriptionTrialPeriodLength = urldecode($_POST['ap_trialperiodlength']);

        $subscriptionNextRunDate = urldecode($_POST['ap_nextrundate']);

        $subscriptionPaymentNumber = urldecode($_POST['ap_subscriptionpaymentnumber']);

        $subscriptionCancelledBy = urldecode($_POST['ap_cancelledby']);

        $subscriptionCancelNotes = urldecode($_POST['ap_cancelnotes']);



        //Setting the customer's information from the IPN post variables

        $customerFirstName = urldecode($_POST['ap_custfirstname']);

        $customerLastName = urldecode($_POST['ap_custlastname']);

        $customerAddress = urldecode($_POST['ap_custaddress']);

        $customerCity = urldecode($_POST['ap_custcity']);

        $customerState = urldecode($_POST['ap_custstate']);

        $customerCountry = urldecode($_POST['ap_custcountry']);

        $customerZipCode = urldecode($_POST['ap_custzip']);

        $customerEmailAddress = urldecode($_POST['ap_custemailaddress']);



        //Setting information about the purchased service from the IPN post variables

        $itemName = urldecode($_POST['ap_itemname']);

        $itemCode = urldecode($_POST['ap_itemcode']);

        $itemDescription = urldecode($_POST['ap_description']);

        $itemQuantity = urldecode($_POST['ap_quantity']);

        $itemAmount = urldecode($_POST['ap_amount']);



        //Setting extra information about the purchased item from the IPN post variables

        $additionalCharges = urldecode($_POST['ap_additionalcharges']);

        $shippingCharges = urldecode($_POST['ap_shippingcharges']);

        $taxAmount = urldecode($_POST['ap_taxamount']);

        $discountAmount = urldecode($_POST['ap_discountamount']);



        //Setting your customs fields received from the IPN post variables

        $random = urldecode($_POST['apc_1']);

        $ip = urldecode($_POST['apc_2']);

        $referer = urldecode($_POST['apc_3']);

        $register_status = urldecode($_POST['apc_4']);

        $product_type = urldecode($_POST['apc_5']);
		if(empty($IPN_SECURITY_CODE)){
        	$IPN_SECURITY_CODE = $this->get_ipn_code($receivedMerchantEmailAddress,$itemCode, $referer);
		}
		$MY_MERCHANT_EMAIL = $receivedMerchantEmailAddress;

        $log .= "START AT: " . date("Y-m-d H:i:s") . "\n";
        $log .= "\n---------------IPN STARTS -------------------\n";

        define("IPN_SECURITY_CODE", $IPN_SECURITY_CODE);
        define("MY_MERCHANT_EMAIL", $MY_MERCHANT_EMAIL);
		
		$log .= "\nIPN CODE: " . IPN_SECURITY_CODE . "\n";
		$log .= "\nBUSINESS EMAIL ADDRESS: " . MY_MERCHANT_EMAIL . "\n";
		$log .= "\n-------------------------------DATA REVEIVED ------------------------- \n";
        $log .= print_r($_POST, true);
		$log .= "\n-------------------------------DATA REVEIVED ------------------------- \n";

        $myCustomField_6 = urldecode($_POST['apc_6']);

        if ($receivedMerchantEmailAddress != MY_MERCHANT_EMAIL) {

                $log .= "Sorry ! MERCHANT EMAIL Address is incorrect\n";
        } 
		else {

            if ($mySecurityCode != IPN_SECURITY_CODE) {
		          $log .= "Sorry ! IPN SECURITY CODE is incorrect $mySecurityCode ==" . IPN_SECURITY_CODE . '\n';
            } else {

				 $log .= "IPN comming from alert Pay OK.\n";
				 
   				$member_id = $this->get_member($random, $ip, $referer, $customerFirstName, $customerLastName, $customerAddress, $customerCity, $customerState, $customerZipCode, $customerCountry, $customerEmailAddress);

                $order_id = $this->set_order($itemCode, $itemName, $itemAmount, $transactionStatus, $customerEmailAddress, MY_MERCHANT_EMAIL, $referer, $random, $transactionType, $transactionReferenceNumber);

                //$this->add_product_to_member($member_id, $itemCode, $transactionReferenceNumber, $product_type);



                $log .= "getting member: {$random} {$member_id}.\n";

                $log .= "setting order:  {$order_id}.\n";

                $log .= "Random :  {$this->member_random}.\n";

                $fp = fopen('ipn.log', 'a+');
                fwrite($fp, $log);

                if ($receivedMerchantEmailAddress != MY_MERCHANT_EMAIL) {

                    // The data was not meant for the business profile under this email address.
                    // Take appropriate action.
                } else {

                    // Check if the security code matches

                    if ($mySecurityCode != IPN_SECURITY_CODE) {

                        // The data is NOT sent by AlertPay.
                        // Take appropriate action.
                    } else {

                        // Check if it is an initial payment for a subscription

                        $log .= "Purchase Type:  {$purchaseType}.\n";

                        if ($transactionStatus == "Success") {

                            //	if ($purchaseType == "subscription"  && $transactionStatus == "Success" ) {
                            // Check if there was a trial period

                            $this->add_product_to_member($member_id, $itemCode, $transactionReferenceNumber, $product_type);

                            $log .= "Add product into Member table:  {$transactionReferenceNumber}.\n";

                            if (isset($subscriptionTrialAmount) && isset($subscriptionTrialTimeUnit) && isset($subscriptionTrialPeriodLength)) {

                                if ($subscriptionTrialAmount == 0) {

                                    // It is a FREE trial and no transaction reference number is returned.
                                    // Check if TEST MODE is on/off and apply the proper logic.
                                    // A subscription reference number will be returned.
                                    // Process the order here by cross referencing the received data with your database.
                                    // After verification, update your database accordingly.
                                } elseif ($subscriptionTrialAmount > 0) {

                                    // Is is a PAID trial and transaction reference number will be returned.
                                    // Check if TEST MODE is on/off. and apply the proper logic. 
                                    // If Test Mode is ON then no transaction reference number will be returned.
                                    // A subscription reference number will be returned.
                                    // Process the order here by cross referencing the received data with your database. 														
                                    // Check that the total amount paid was the expected amount.
                                    // Check that the amount paid was for the correct service.
                                    // Check that the currency is correct.
                                    // ie: if ($totalAmountReceived == 50) ... etc ...
                                    // After verification, update your database accordingly.						
                                } else {

                                    // The trial amount is invalid.
                                    // Take appropriate action.
                                }
                            }



                            // There is no trial and the payment is the first installment of the subscription.
                            // Check if TEST MODE is on/off and apply the proper logic. 
                            // If Test Mode is ON then no transaction reference number will be returned.
                            // A subscription reference number will be returned.
                            // Process the order here by cross referencing the received data with your database. 														
                            // Check that the total amount paid was the expected amount.
                            // Check that the amount paid was for the correct service.
                            // Check that the currency is correct.
                            // ie: if ($totalAmountReceived == 50) ... etc ...
                            // After verification, update your database accordingly.														
                        } elseif ($purchaseType == "subscription" && $transactionStatus == "Subscription-Payment-Success" && $subscriptionPaymentNumber > 1) {







                            // The payment is for a recurring subscription.
                            // Check if TEST MODE is on/off and apply the proper logic. 
                            // If Test Mode is ON then no transaction reference number will be returned.
                            // A subscription reference number will be returned.
                            // Process the order here by cross referencing the received data with your database. 														
                            // Check that the total amount paid was the expected amount.
                            // Check that the amount paid was for the correct service.
                            // Check that the currency is correct.
                            // ie: if ($totalAmountReceived == 50) ... etc ...
                            // After verification, update your database accordingly.	
                        } else {

                            switch ($transactionStatus) {

                                case "Subscription-Expired":

                                    // Take appropriate when the subscription has reached its terms.

                                    $set = "refunded='1' where txn_id=' $transactionReferenceNumber'";

                                    $q = "update {$this->prefix}member_products set $set";

                                    $this->db->insert($q);



                                    // Set order to refunded	

                                    $set = "payment_status='Subscription Expired' where txnid='$transactionReferenceNumber'";

                                    $q = "update {$this->prefix}orders set $set";

                                    $this->db->insert($q);



                                    $this->subscription_cancel($transactionReferenceNumber);

                                    $log .= "\n===============================\n

												Subscription-Canceled

										\n===============================\n Set order to refunded	\n

										$q\n";







                                    break;

                                case "Subscription-Payment-Failed":

                                    // Take appropriate actions when a payment attempt has failed.

                                    $set = "refunded='1' where txn_id=' $transactionReferenceNumber'";

                                    $q = "update {$this->prefix}member_products set $set";

                                    $this->db->insert($q);



                                    // Set order to refunded	

                                    $set = "payment_status='Subscription Payment Failed' where txnid='$transactionReferenceNumber'";

                                    $q = "update {$this->prefix}orders set $set";

                                    $this->db->insert($q);



                                    $this->subscription_cancel($transactionReferenceNumber);

                                    $log .= "\n===============================\n

												Subscription-Canceled

										\n===============================\n Set order to refunded	\n

										$q\n";







                                    break;

                                case "Subscription-Payment-Rescheduled":

                                    // Take appropriate actions when a payment is rescheduled.









                                    break;



                                case "Subscription-Canceled":



                                    $set = "refunded='1' where txn_id=' $transactionReferenceNumber'";

                                    $q = "update {$this->prefix}member_products set $set";

                                    $this->db->insert($q);



                                    // Set order to refunded	

                                    $set = "payment_status='Cancelled' where txnid='$transactionReferenceNumber'";

                                    $q = "update {$this->prefix}orders set $set";

                                    $this->db->insert($q);



                                    $this->subscription_cancel($transactionReferenceNumber);

                                    $log .= "\n===============================\n

												Subscription-Canceled

										\n===============================\n Set order to refunded	\n

										$q\n";

                                    break;

                                default:

                                    // Take a default action in the case that none of the above were handled.

                                    break;
                            }
                        }
                    }
                }



                $log .= "closing All.\n";

				

                
            }
        }
        return $log;
        }
	
	

}

?>