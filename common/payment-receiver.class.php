<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * Author Yasir Rehman
 * Email yasir.rehman@live.com
 * Version 1.0
 * Company Name: Next Bridge Pakistan.
 */

class payment_receiver_email {

    private $db;
    private $prefix;
    private $id;
    private $ref_email;
    private $ref;

    function __construct($pid, $ref='') {
        global $db, $prefix;
        $this->prefix = $prefix;
        $this->db = $db;
        $this->id = $pid;
        $this->ref = $ref;
    }

    function get_referel_email($ref) {
        $product_commision = $this->get_product_commision();
        $sql = "select alertpay_email, paypal_email, status, alertpay_ipn_code from " . $this->prefix . "members where username='$ref'";
        $row = $this->db->get_a_line($sql);
        if ($row[status] == 3) {
            $commission = $product_commision['jvcommission'];
        } else {
            $commission = $product_commision['commission'];
        }
        $data['affiliate_paypal_email'] = $row['paypal_email'];
        $data['affiliate_alertpay_email'] = $row['alertpay_email'];
        $data['alertpay_ipn_code'] = $row['alertpay_ipn_code'];
        $data['commision'] = $commission;
        return $data;
    }

    function get_site_partner() {
        $sql = "select * from " . $this->prefix . "site_settings where id='1'";
        $row = $this->db->get_a_line($sql);
        if ($row['paypal_sandbox'] == 1)
            $data['paypal_email'] = trim($row['sandbox_paypal_email']);
        else
            $data['paypal_email'] = trim($row['paypal_email']);
        $data['alertpay_merchant_email'] = trim($row['alertpay_merchant_email']);
        $data['alertpay_ipn_code'] = trim($row['alertpay_ipn_code']);
        $data['sitepartner'] = trim($row['sitepartner']);
        $data['partner_paypal_email'] = trim($row['partner_paypal_email']);
        $data['partner_alertpay_email'] = trim($row['partner_alertpay_email']);
        $data['partner_commission'] = trim($row['partner_commission']);
        $data['partner_alertpay_ipn'] = trim($row['partner1_alertpay_ipn_code']);
        $data['second_sitepartner'] = trim($row['second_sitepartner']);
        $data['second_partner_paypal_email'] = trim($row['second_partner_paypal_email']);
        $data['second_partner_alertpay_email'] = trim($row['second_alertpay_paypal_email']);
        $data['second_partner_commission'] = trim($row['second_partner_commission']);
        $data['second_partner_alertpay_ipn'] = trim($row['partner2_alertpay_ipn_code']);
        return $data;
    }

    function get_receiver_email() {
        $data = array();
        $site_partners = $this->get_site_partner();
        $product_partners = $this->get_product_partner();
        $commision = $this->get_product_commision();
        $data['owner']['commision'] = 0;
        $data['owner']['paypal_email'] = $site_partners['paypal_email'];
        $data['owner']['alertpay_email'] = $site_partners['alertpay_merchant_email'];
        $data['owner']['alertpay_ipn'] = $site_partners['alertpay_ipn_code'];
        if (!empty($this->ref)) {
            $refer_informtation = $this->get_referel_email($this->ref);
            $data['ref']['commision'] = $refer_informtation['commision'];
            $data['ref']['paypal_email'] = $refer_informtation['affiliate_paypal_email'];
            $data['ref']['alertpay_email'] = $refer_informtation['affiliate_alertpay_email'];
            $data['ref']['alertpay_ipn'] = $refer_informtation['alertpay_ipn_code'];
        }
        if ($product_partners['check_product_partner'] == 1) {
            $data['product']['commision'] = $commision['partner_commission'];
            $data['product']['paypal_email'] = $product_partners['porduct_partner_paypal_email'];
            $data['product']['alertpay_email'] = $product_partners['porduct_partner_alertpay_email'];
            $data['product']['alertpay_ipn'] = $product_partners['porduct_partner_alertpay_ipn'];
        }
        if ($site_partners['sitepartner'] == 'yes') {
            $data['sitepartner1']['commision'] = $site_partners['partner_commission'];
            $data['sitepartner1']['paypal_email'] = $site_partners['partner_paypal_email'];
            $data['sitepartner1']['alertpay_email'] = $site_partners['partner_alertpay_email'];
            $data['sitepartner1']['alertpay_ipn'] = $site_partners['partner_alertpay_ipn'];
        }
        if ($site_partners['second_sitepartner'] == 'yes') {
            $data['sitepartner2']['commision'] = $site_partners['second_partner_commission'];
            $data['sitepartner2']['paypal_email'] = $site_partners['second_partner_paypal_email'];
            $data['sitepartner2']['alertpay_email'] = $site_partners['second_partner_alertpay_email'];
            $data['sitepartner2']['alertpay_ipn'] = $site_partners['second_partner_alertpay_ipn'];
        }
        return $receiver = $this->process_data($data);
    }

    function process_data($data) {
        $data['owner']['commision'] = $this->get_owner_commission($data);
        $records = $this->process_commision($data);
        $receiver['paypal_email'] = $this->get_paypal_email($records);
        $alertpay = $this->get_alert_email($records);
        $receiver['alertpay_email'] = $alertpay['alertpay_email'];
        $receiver['alertpay_ipn'] = $alertpay['alertpay_ipn'];
        return $receiver;
    }

    function get_paypal_email($records) {
		/*echo '<pre>';
			print_r($records);
		echo '</pre>';*/
        foreach ($records as $key => $rows) {
            if ($rows['commision'] == 0)
                $commission = 0;
            else
               $commission = $rows['commision'];
	
          $sql = "SELECT count(id) as total FROM " . $this->prefix . "orders 
	   WHERE referrer='$this->ref' AND 
	   payee_email ='$rows[paypal_email]' AND 
	   payment_status ='Completed' AND
       payment_gateway =''  AND 
	   item_number='$this->id'";
         // echo  $sql;  
       $sq1_total_all = "SELECT count(id) as total FROM " . $this->prefix . "orders
	   WHERE referrer='$this->ref' AND
	   payee_email <> '$rows[paypal_email]' AND 
	   payment_status ='Completed' AND 
       payment_gateway =''   AND         
	   item_number='$this->id'";
            //  echo  "<br>".$sql;  
            $row_total = $this->db->get_a_line($sql);
            $total_sale_for_this_user = $row_total['total'];
            $row_total = $this->db->get_a_line($sq1_total_all);
            $total_sale_not_for_this_user = $row_total['total'];
            $all_paypal_sales_for_this_order = $total_sale_for_this_user + $total_sale_not_for_this_user;
            $total_paypal_percent = number_format(($total_sale_for_this_user * 100) / $all_paypal_sales_for_this_order, 0);
            /*             * ****************************************************************************** */
             /*  echo "
              <h3>Summary</h3>
              Actual Sales Commision = $commission <br />
              all paypal sales for this order < $all_paypal_sales_for_this_order<br />
              total_sale_not_for_this_user < $total_sale_not_for_this_user<br />
              Business Email [$key] = $rows[paypal_email]<br />
              Commission Value = $total_paypal_percent <= $commission <br />"; 
            /*             * ****************************************************************************** */
			
             if ($total_paypal_percent <=  $commission) {
			    return $rows['paypal_email'];
                break;
            	}
			
			
        }
		
    }

    function get_alert_email($records) {
        foreach ($records as $key => $rows) {
            if ($rows['commision'] == 0)
                $commission = 0;
            else
                $commission = $rows['commision'];
            $sql_alertpay_total = "
		  SELECT count(*) as total FROM " . $this->prefix . "orders 
		  WHERE referrer='$this->ref' AND
		  payee_email='$rows[alertpay_email]' AND
		  item_number='$this->id' AND
		  payment_status ='Completed' AND
		  payment_gateway ='AlertPay' ";
            $sql_alertpay_all = "
		  SELECT count(*) as total FROM " . $this->prefix . "orders 
		  WHERE referrer='$this->ref' AND 
		  payee_email <> '$rows[alertpay_email]' AND
		  item_number='$this->id' AND 
		  payment_status = 'Completed' AND 
		  payment_gateway ='AlertPay'";
            // echo "<br>".$sql_alertpay_total;
            $alertpay_row_total = $this->db->get_a_line($sql_alertpay_total);
            $alertpay_total_sale_for_this_user = $alertpay_row_total['total'];
            $alertpay_row_total = $this->db->get_a_line($sql_alertpay_all);
            $alertpay_total_sale_not_for_this_user = $alertpay_row_total['total'];
            $all_alertpay_sales_for_this_order = $alertpay_total_sale_for_this_user + $alertpay_total_sale_not_for_this_user;
            $total_alertpay_percent = number_format(($alertpay_total_sale_for_this_user * 100) / $all_alertpay_sales_for_this_order, 0);
            /*             * ****************************************************************************** */
            /* echo "
              <h3>Summary</h3>
              <p>Actual Sales Commision < $commission</p>
              <p>Total_sale_not_for_this_user < $alertpay_total_sale_not_for_this_user</p>
              <p>Total Sale for this user < $alertpay_total_sale_for_this_user</p>
              <p>Total Sale  < $all_alertpay_sales_for_this_order</p>
              <p>Business Email [$key] = $rows[alertpay_email]</p>
              <p>Commission Value = $total_alertpay_percent <  $commission</p>";
            /******************************************************************************** */
            if ($total_alertpay_percent <= $commission) {
                $receiver['alertpay_email'] = $rows['alertpay_email'];
                $receiver['alertpay_ipn'] = $rows['alertpay_ipn'];
                return $receiver;
            }
        }
    }

    function process_commision($data) {
        $commistion_for_all_partners = 100 - $data['ref']['commision'];
        foreach ($data as $key => $rows) {
            if ($key != 'ref')
                $data[$key]['commision'] = floor(($commistion_for_all_partners * $rows['commision']) / 100);
        }
        return $data;
    }

    function get_owner_commission($data) {
        $commision = 0;
        foreach ($data as $key => $rows) {
            if ($key != 'ref')
                $commision = $commision + $rows['commision'];
        }
        $commision = 100 - $commision;
        return $commision;
    }

    function get_product_partner() {
        $sql = " SELECT
            enable_product_partner,
            product_partner_paypal_email,
            product_partner_alertpay_email,
            ap_partner_ipn_security_code 
            FROM
            " . $this->prefix . "products 
            WHERE id='$this->id'";
        $row = $this->db->get_a_line($sql);
        $data['check_product_partner'] = $row['enable_product_partner'];
        $data['porduct_partner_paypal_email'] = $row['product_partner_paypal_email'];
        $data['porduct_partner_alertpay_email'] = $row['product_partner_alertpay_email'];
        $data['porduct_partner_alertpay_ipn'] = $row['ap_partner_ipn_security_code'];
        return $data;
    }

    function get_product_commision() {
        $sql = "SELECT 
         commission,
         jvcommission,
         partner_commission 
         FROM " . $this->prefix . "products WHERE id='$this->id'";
        $row = $this->db->get_a_line($sql);
        //---------------------- C O M M I S I O N -----------------------------
        $data['commission'] = $row['commission'];
        $data['jvcommission'] = $row['jvcommission'];
        $data['partner_commission'] = $row['partner_commission'];
        //---------------------- C O M M I S I O N -----------------------------
        return $data;
    }

}

?>