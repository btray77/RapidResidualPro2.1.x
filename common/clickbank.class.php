<?php
class ClickBank{
	private $developerKey;
	private $apiKey;
	//private $secretKey = "12882575W05588EU";
	private $db;
	private $prefix;
	private $account;
	private $productCode; 
	private $query;
	private $id;
	
	
	function __construct(){
		$this->developerKey = "DEV-BF9925DA357383D2578B49FA51F6F1AE9FF2";
		$this->apiKey = "API-304CB24D2FBD999B13C6934370AA4A16C365";
		global $db, $prefix;
		$this->db = $db;
		$this->prefix = $prefix;
	
	}
	
	function button($item, $pid, $referer, $rand){
		
		$hop=$_GET['hop'];
		if(!empty($_GET['hopset']))
			$hopset = $_GET['hopset'];
		else	
			$hopset ='notset';
		$sql='select click_bank_url,clickbank_image from '.$this->prefix.'products where id='.$pid.';';
		$click_product = $this->db->get_a_line($sql);
        $username=$click_product['click_bank_url'];
                
		if(empty($click_product['clickbank_image']))
			$image="/images/payment_buttons/clickbank-button.png";
		else
			$image = $click_product['clickbank_image'];    
					 	
		
		if($hopset=='notset' && !empty($hop)){
			$sql='select clickbank_email from '.$this->prefix."members where username='$referer ';";
			$click_bank = $this->db->get_a_line($sql); 
			$click_bank_ref =  $click_bank['clickbank_email'];
			if(empty($click_bank_ref))
                            $hoplink = $hop;
			else
                            $hoplink = $click_bank_ref;

		$button = "<a href=\"http://$hoplink.$username.hop.clickbank.net?randomstring=$rand&hopset=set\">
		<img border=\"0\" alt=\"Click Bank\" src=\"$image\"></a>";
		
		}
		else
		{
		
		$button = "<a href=\"http://$item.$username.pay.clickbank.net?pid=$pid&randomstring=$rand\">
		<img border=\"0\" alt=\"Click Bank\" src=\"$image\"></a>";
		}
		return $button;
	
	}
	
	function button_hidden($item, $pid, $referer, $rand){
		$hop=$_GET['hop'];
		$sql='select click_bank_url,clickbank_image from '.$this->prefix.'products where id='.$pid.';';
		if(empty($click_bank_username[clickbank_image]))
		$image="/images/payment_buttons/clickbank-button.png";
		else
		$image = $click_bank_username[clickbank_image];    
		
		$click_bank_username = $this->db->get_a_line($sql);
		$username=$click_bank_username['click_bank_url'];
		if((!empty($referer) and $referer!='None')  || !empty($hop)){
		$sql='select clickbank_email from '.$this->prefix."members where username='$referer ';";
		$click_bank = $this->db->get_a_line($sql); 
		$click_bank_ref =  $click_bank['clickbank_email'];
		if(empty($click_bank_ref))
		$hoplink = $hop;
		else
		$hoplink = $click_bank_ref;
		$button = "http://$hoplink.$username.hop.clickbank.net?pid=$pid&referer=$referer&randomstring=$rand";
		
		}
		else
		{
		$button = "http://$item.$username.pay.clickbank.net?pid=$pid&randomstring=$rand";
		}
		return $button;
	
	}
	
	function ipnVerification() {
		$ipnFields = array();
		foreach ($_POST as $key => $value) {
		if ($key == "cverify") {
		continue;
		}
		$ipnFields[] = $key;
		}
		sort($ipnFields);
		//		echo '<pre>'; print_r($ipnFields); echo '</pre>'; exit;
		foreach ($ipnFields as $field) {
			// if Magic Quotes are enabled $_POST[$field] will need to be 
        // un-escaped before being appended to $pop 
			//$pop = $pop . $_POST[$field] . "|";
		}
		$pop = $pop . $this->secretKey;
		$calcedVerify = sha1(mb_convert_encoding($pop, "UTF-8"));
		$calcedVerify = strtoupper(substr($calcedVerify,0,8));
		return $calcedVerify == $_POST["cverify"];
	}
	
	
	function get_member($first_name, $last_name, $address, $city, $state, $zip, $country, $email, $randomstring){
	$fp = fopen('clickbank.log', 'a');
	$data='\r\n----------- Create A Member----------------\r\n';
	$first_name = strtolower($first_name);
	$last_name = strtolower($last_name);
		$query = "SELECT id FROM {$this->prefix}members WHERE randomstring = '{$randomstring}'";
		$rec_member = $this->db->get_a_line($query);
		if(is_numeric($rec_member['id']) && $rec_member['id'] > 0) 
			$member_id = $rec_member['id'];
		else {
		$query = "INSERT INTO {$this->prefix}members SET firstname = '{$first_name}', lastname = '{$last_name}', email = '{$email}', 
				  address_street = '{$address}', address_city = '{$city}', address_state = '{$state}', address_zipcode = '{$zip}',
				  address_country = '{$country}', date_joined = NOW(), randomstring = '$randomstring'";
		$member_id = $this->db->insert_data_id($query);	
		}
		
		$data .= "\n{$query}\n";
		$data .= "\n Member Id:" . $member_id . "\n";
		
		fwrite($fp, $data);
		fclose($fp);
		return $member_id;
	}
	
	function set_order($product_id, $product_name, $price, $payment_status, $email, $payment_type, $transaction_id, $rand, $referrer ,$affiliateid){
		$payee_email = $affiliateid;
		$fp = fopen('clickbank.log', 'a');
		$data='\r\n----------- Set Orders----------------\r\n';
		$set	= "item_number='$product_id'";
		$set	.= ", item_name='$product_name'";
		$set	.= ", date=NOW()";
		$set	.= ", payment_amount='$price'";
		$set	.= ", payment_status='$payment_status'";
		$set	.= ", randomstring='$rand'";
		$set	.= ", txnid='$transaction_id'";
		$set	.= ", payer_email='$email'";
		$set	.= ", payee_email='{$payee_email}'";
		$set	.= ", referrer='$referrer'";
		$set	.= ", payment_gateway='ClickBank'";
		$set	.= ", payment_type='$payment_type'";
		$query = "INSERT INTO {$this->prefix}orders SET {$set} ";
		$order_id = $this->db->insert_data_id($query);
		
		$data .= "\n{$query}\n";
		$data .= "\n Order Id: " . $order_id . "\n";
		
		fwrite($fp, $data);	
		fclose($fp);	
		return $order_id;	
	
	}
	
	function add_product_to_member($member_id, $product_id, $transaction_id, $product_type){
		$fp = fopen('clickbank.log', 'a');
		$data='\r\n----------- Add Product to Member----------------\r\n';
		$set	= "member_id='$member_id'";
		$set	.= ", product_id='$product_id'";
		$set	.= ", date_added=NOW()";
		$set	.= ", txn_id='$transaction_id'";
		$set	.= ", type='$product_type'";
		
		$q = "insert into ".$this->prefix."member_products set $set";
		$this->db->insert($q);
		$fp = fopen('clickbank.log', 'a');
		$data .= "\r\n Save Infomration in Product Order\n";
		$data .= "\r\n{$q}\n";
		$data .= "\r\n" . mysql_error() . "\n";
		
		fwrite($fp, $data);	
		fclose($fp);
		return true;		
	}
	
function get_product_id($product_name){
	$query = "SELECT id FROM {$this->prefix}products WHERE pshort = '{$product_name}'";
	$rec_product = $this->db->get_a_line($query);
	return $rec_product['id'];
}
	
	//cprodtitle	ctid
function ipn(){
	$fp = fopen('clickbank.log', 'a');
	$data='\r\n----------- Loading IPN----------------\r\n';
	$string .= 'Script Started: ' . date('Ymd His') . "\r\n";
	
	$product_name           = addslashes($this->filter($_POST['cprodtitle']));
	$product_code           = $this->filter($_POST['cproditem']);
	$price 		  	= (float) $this->filter($_POST['corderamount'])/100;
	$product_type           = $this->filter($_POST['cprodtype']);
	$payment_status         = $this->filter($_POST['ctransaction']);
	
	if($payment_status=='TEST_SALE' || $payment_status=='Sale')
	$payment_status         ='Completed';
	
	$payment_method         = $this->filter($_POST['ctranspaymentmethod']);
	$transaction_id         = $this->filter($_POST['ctransreceipt']);
	$referrer		= $this->filter($_POST['ctransaffiliate']);
	$ctranspublisher        = $this->filter($_POST['ctranspublisher']);
	
	$first_name             = $this->filter($_POST['ccustfirstname']);
	$params			= $this->filter($_POST['cvendthru']);
	$last_name 		= $this->filter($_POST['ccustlastname']);
	$address	 	= $this->filter($_POST['ccustaddr1']) . ' ' . $this->filter($_POST['ccustaddr1']);
	$city 			= $this->filter($_POST['ccustcity']);
	$state 			= $this->filter($_POST['ccuststate']);
	$zipcode		= $this->filter($_POST['ccustzip']); 
	$country		= $this->filter($_POST['ccustcc']); 
	$email			= $this->filter($_POST['ccustemail']);
		
	foreach($_REQUEST as $key=>$items){
		$string .= "$key = $items \r\n";
	}
	
	$product_id = $this->get_product_id($product_name);
	//$zipcode		= $this->filter($_POST['ctid']); 
	$string .= "\nproduct_id = " . $product_id;
	
	$params = urldecode(trim($params));
	$string .= "\nparams = " . $params;
	$temp = explode('&', $params);
	for($i=0, $count = count($temp); $i < $count; $i++){
            $arr = explode('=', $temp[$i]);
            $index = $arr[0];
            $p[$index] = $arr[1];							 
	}
	@extract($p);
	
	$string .= "\n" . print_r($p, true) . "\n";
	$string .="\n";
	$randomstring = str_replace("randomstring=",'',$randomstring);
	list($randomstring,$ip,$price,$product_id,$referrer,$member) = explode('|',$randomstring);
	$string .="\nRandomString:".$randomstring;
	$string .="\nIp:".$ip;
	$string .="\nProduct_id:".$product_id;
    $string .="\nReferrer:".$referrer;
	$string .="\n pid:".$pid;
	$string .="\n product_name:".$product_name;
	$string .="\n payment_status:".$payment_status;
	$string .="\n email:".$email;
	$string .="\n payment_method:".$payment_method;
	$string .="\n randomstring:".$randomstring;
	$string .="\n referrer:".$referrer;
	$string .="\n ctranspublisher:".$ctranspublisher;
	fwrite($fp, $string);
	fclose($fp);
	switch($payment_status){
	case 'Completed':
            $member_id = $this->get_member($first_name, $last_name, $address, $city, $state, $zip, $country, $email, $randomstring);
			$string .="\n member_id:".$member_id;
			$order_id = $this->set_order($pid, $product_name, $price, $payment_status, $email, $payment_method, $transaction_id, $randomstring, $referrer,$ctranspublisher);
	       $string .="\n order_id:".$order_id;
			  $this->add_product_to_member($member_id, $pid, $transaction_id, $product_type);
				$string .="\n transaction_id:".$transaction_id;
				$string .="\n product_type:".$product_type;
				
					
	break; 
	case 'Sale-RB':
	break;
	
	case 'Refund':
	break;
	
	case 'Bounced':
	break;
	
	case 'Chargeback':
	break;
	
	
        }
}
function filter($var){
	return stripslashes(trim($var));
	}
}
?>