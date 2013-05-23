<?php
session_start();
include_once("common/config.php");
include ("include.php");
$pshort = mysql_escape_string(trim($_REQUEST['pshort']));
$ccode = mysql_escape_string(trim($_REQUEST['c']));

$today = date('Y-m-d');
$rand = md5(uniqid(rand(), 1));
$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$os = $common->getOS($user_agent);
$browser =$common->getBrowser($user_agent);	

// get product page info from database
$q = "select * from " . $prefix . "products where pshort='$pshort'";
$v = $db->get_a_line($q);

$product_name = $v['product_name'];
$qlimit = $v['qlimit'];
$quantity_cap = $v['quantity_cap'];
$quantity_met_page = $v['quantity_met_page'];
$prodtype = $v['prodtype'];
$price = $v['price'];
$id = $v['id'];
$amount3 = $v['amount3'];
$subscription_active = $v['subscription_active'];

$obj_responder = new autoresponders('',$id);


// Is there a quantity limit?
if ($qlimit == "yes") {
    // Quantity limited so get total members with product
    $q = "select count(*) as cnt from " . $prefix . "member_products where product_id='$id'";
    $r = $db->get_a_line($q);
    $count = $r[cnt];
    if ($count >= $quantity_cap) {
        $pagecontent = preg_replace("/\[\[(.*?)\]\]/e", "$$1", $quantity_met_page);
        $pagecontent = preg_replace("/{{(.*?)}}/e", "$$1", $pagecontent);
        $pagecontent = preg_replace("/[$]/", "&#36;", $pagecontent);

        $returncontent = preg_replace('/\{\{([a-zA-Z0-9_]*)\}\}/e', "$$1", $pagecontent);
        $smarty->assign('main_content', $returncontent);
        $output = $smarty->fetch('html/content.tpl');

        $objTpl = new TPLManager($FILEPATH . '/index.html');
        $hotspots = $objTpl->getHotspotList();
        $placeHolders = $objTpl->getPlaceHolders($hotspots);
        $i = 0;
        foreach ($placeHolders as $items) {
            $smarty->assign("$hotspots[$i]", "$items");
            $i++;
        }
        $smarty->assign('content', $output);
        $smarty->assign('error', $errors);
        $smarty->display($FILEPATH . '/index.html');
        exit;
    }
}




if (isset($_POST['Submit'])) {
	if( $_SESSION['security_code'] != $_POST['captchastring'] && !empty($_SESSION['security_code'] ) ) {
		echo $warning = '<div class="error">security code did not match please enter again.</div>';
	}
		else{
		
    // Clean up form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $org_password = $password;
    $pass = $_POST["password"];
    $_SESSION['password']=$org_password;
    $password = md5($password);   // Encript password
    
    $pshort = $_POST["pshort"];
    $rand = $_POST["rand"];
    $ref = $_POST["ref"];
	// Was product id submited
    // get product page info from database
    $q = "select * from " . $prefix . "products where pshort='$pshort'";
    $v = $db->get_a_line($q);
    $product_name = $v['product_name'];
    $qlimit = $v['qlimit'];
    $quantity_cap = $v['quantity_cap'];
    $quantity_met_page = $v['quantity_met_page'];
    $prodtype = $v['prodtype'];
    $price = $v['price'];
    $short = $v['pshort'];
    $amount3 = $v['amount3'];
    $subscription_active = $v['subscription_active'];
    $id = $v['id'];


    if ($prodtype != "free") {
        if ($c == '') {
            $Message = '<div class="error">You must pay before accessing this product.</div>';
        } elseif ($c != '') {
            $q = "select count(*) as cnt from " . $prefix . "coupon_codes where couponcode='$c' && prod ='$short'";
            $r = $db->get_a_line($q);
            $count = $r[cnt];
            if ($count != '0') {
                $q = "select * from " . $prefix . "coupon_codes where couponcode='$c'";
                $h = $db->get_a_line($q);
                $discount = $h['discount'];
                $amount3 = $amount3 - $discount;
                $price = $price - $discount;

                if ($subscription_active == '1') {
                    // subscriptions on
                    if ($amount3 != '0') {
                        $Message = '<div class="error">You must pay before accessing this product.</div>';
                    }
                } elseif ($subscription_active == '') {
                    if ($price != '0') {
                        $Message = '<div class="error">You must pay before accessing this product.</div>';
                    }
                }
            }
        }
    } else if ($prodtype == "free") {
        $price = '0';
    }


    // Check if username or email already in database
    $sql = "select count(*) as Cnt from " . $prefix . "members where email = '$email' or username = '$username'";
    $Check = $db->get_a_line("$sql");


    if ($Check[Cnt] != 0) {
        // Username or Email already exist so return to form with error message
        $Message = '<div class="error">Username or Email already exits</div>';
    } elseif ($Check[Cnt] == 0) {
        $time=time();
        $ip = $common->get_real_IP_address();
        // Username and email valid so signup member		
        $set = "firstname  = {$db->quote($firstname)},";
        $set .= "lastname  = {$db->quote($lastname)},";
        $set .= "email  = {$db->quote($email)},";
        $set .= "paypal_email  = {$db->quote($paypal_email)},";
        $set .= "username  = {$db->quote($username)},";
        $set .= "password  = {$db->quote($password)},";
        $set .= "date_joined = now(),";
        $set .= "last_login = $time,";
        $set .= "ip = '$ip',";
        $set .= "ref = '$ref',";
        $set .= "randomstring = '$rand'";
        $mid = $db->insert_data_id("insert into " . $prefix . "members set $set");
        
        $_SESSION['memberid']=$mid;
        setcookie("memberid", $mid, 0, "/");
        // Get Member id
        $q = "select id as mid from " . $prefix . "members where randomstring='$rand'";
        $r = $db->get_a_line($q);
        @extract($r);

        // insert into member products table
        $set = "member_id='$mid'";
        $set .= ", product_id='" . $id . "'";
        $set .= ", date_added='" . $today . "'";
        $set .= ", txn_id='FREE'";
        $set .= ", type='$prodtype'";
        $q = "insert into " . $prefix . "member_products set $set";
        $db->insert($q);

        // get Admin payee account
        $sql_settings = "select paypal_email,sandbox_paypal_email,paypal_sandbox,alertpay_merchant_email from " . $prefix . "site_settings";
        $row_settings = $db->get_a_line($sql_settings);
        if ($row_settings['paypal_sandbox'] == 1 && empty($row_settings['paypal_email']))
            $payee = $row_settings['sandbox_paypal_email'];
        else
            $payee= $row_settings['paypal_email'];
        if (empty($payee)) {
            $payee = $row_settings['alertpay_merchant_email'];
        }

        // insert into order table                  

        $set = "item_number='$id'";
        $set .= ", item_name='$product_name'";
        $set .= ", date='$today'";
        $set .= ", payment_amount='$price'";
        $set .= ", payment_status='Completed'";
        $set .= ", pending_reason=''";
        $set .= ", txnid='FREE'";
        $set .= ", payer_email={$db->quote($email)}";
        $set .= ", payee_email='$payee   '";
        $set .= ", referrer='$ref'";
        $set .= ", payment_gateway='Free'";
        $set .= ", randomstring='$rand'";
        $set .= ", payment_type='instant'";
        $q = "insert into " . $prefix . "orders set $set";
        
        $oid= $id = $db->insert_data_id($q);
        
        // Get admin email and site email details		
        $q = "select sitename, email_from_name, mailer_details from " . $prefix . "site_settings";
        $a = $db->get_a_line($q);
        @extract($a);
        $q = "select webmaster_email from " . $prefix . "admin_settings";
        $b = $db->get_a_line($q);
        @extract($b);

        // send new member signup email to member
        $q = "select subject, message from " . $prefix . "emails where type='Email sent to free member after signup'";
        $r = $db->get_a_line($q);
        @extract($r);
        $login_link = $http_path . "/member/index.php";

        /******************  ADD TO AUTO RESPONDERS   ************************/
            $autoresponder = $obj_responder -> process_Autoresponders();
        /******************  END TO AUTO RESPONDERS   ************************/
        
        $subject = preg_replace("/{(.*?)}/e", "$$1", $subject);
        $message = preg_replace("/{(.*?)}/e", "$$1", $message);
        $message = $message . "\r\n\r\n" . $mailer_details;
        $header = "From: " . $email_from_name . " <" . $webmaster_email . ">";
        @mail($email, $subject, $message, $header);

        // send new member signup email to admin
        $q = "select subject, message from " . $prefix . "emails where type='Email sent to admin on new user sign up'";
        $r = $db->get_a_line($q);
        @extract($r);

        $subject = preg_replace("/{(.*?)}/e", "$$1", $subject);
        $message = preg_replace("/{(.*?)}/e", "$$1", $message);
        $header = "From: " . $email_from_name . " <" . $webmaster_email . ">";
        @mail($webmaster_email, $subject, $message, $header);
        ?>
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <script src="/common/newLayout/jquery/jquery.js" type="text/javascript" charset="utf-8"></script>
                <link rel="stylesheet" href="/common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8"/>
                <script src="/common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"/></script>
		<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>
                <style>
                body{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:15px;text-align:center}
                p { font-size: 1.2em; }
                div.facebook #pp_full_res .pp_inline{text-align:center}
                .pp_details{display:none;}

                </style> 
                <body>
                   
                    <form name="memlog" id="memlog" action='<?php echo $http_path ?>/member/index.php' method="post">
                        <input type=hidden name="mrand" value='<?php echo $rand ?>'>
                        <input type=hidden name="pshort" value='<?php echo $pshort ?>'>
                        <input name="country" id="country" value=""  type="hidden"  />
                        <input name="city" value="" id="city"  type="hidden"  />
                        <input name="latitude" value="" id="latitude" type="hidden"  />
                        <input name="longitude" value="" id="longitude"  type="hidden"  />
                        <input name="operating_system" value="<?php echo $os ?>"  type="hidden"  />
                        <input name="browser" value="<?php echo $browser ?>"  type="hidden"  />
                    </form>
					
                            <div id="main">
                            <a href="#inline_demo" rel="prettyPhoto[inline]"></a>
                                <div id="inline_demo" style="display:none;">
                                    <p>Please Wait....</p>
                                    <p id="message"></p>

                                </div>
                            <script type="text/javascript" charset="utf-8">
                            $(document).ready(function(){
                            $("a[rel^='prettyPhoto']").prettyPhoto().trigger('click');

                            $('.pp_content').css("height","114px");
                            document.getElementById('country').value=geoip_country_name();
                            document.getElementById('city').value=geoip_city();
                            document.getElementById('latitude').value=geoip_latitude();
                            document.getElementById('longitude').value=geoip_longitude();
                            $('#message').html('<img src="/images/wait.gif" border="" alt="loading...." />');  
                            var data = 'username=<?php echo $username?>&email=<?php echo $email?>&password=<?php echo $org_password?>';  	
                                    $.ajax({  
                                      type: "POST",  
                                      url: "forum.php",  
                                      data: data,  
                                      success: function(data) {  
                                            $('#message').html("<p>Forum Registration!</p>") 
                                            .append('<p>You are successfully become a member of our forum</p><img src="/images/wait.gif" border="" alt="loading...." />');  
                                            
                                            }  
                                    });
								setTimeout("submitform()",1500);
                                   });
                            </script>
                            </div>
                    <script type="text/javascript">
					function submitform(){
						 document.forms['memlog'].submit();
					  }
                    </script>
                </body>
            </html>
            <?php exit();
        }
    
		}
	}
else	
    $warning = '<div style="font-style:italic;color:#999">Please fill in this form below (All fields are required)</div>';
    $smarty->assign('os', $os);
    $smarty->assign('browser', $browser);
        
    $smarty->assign('warning', $warning);
    $smarty->assign('ref', $ref);
    $smarty->assign('message', $Message);
    $smarty->assign('pshort', $_REQUEST['pshort']);
    $smarty->assign('hidelink1', $hidelink1);
    $smarty->assign('hidelink2', $hidelink2);
    $smarty->assign('firstname', $_REQUEST['firstname']);
    $smarty->assign('lastname', $_REQUEST['lastname']);
    $smarty->assign('email', $_REQUEST['email']);
    $smarty->assign('username', $_REQUEST['username']);
    $smarty->assign('paypal_email', $_REQUEST['paypal_email']);

    $smarty->assign('c', $c);
    $smarty->assign('rand', $rand);

    if (!empty($pshort))
        $outputsignup = $smarty->fetch('html/free.tpl');
    else
        $outputsignup = $common->show_error('5');
    $smarty->assign('pagename', 'Free Membership');
    $smarty->assign('main_content', $outputsignup);
    $output = $smarty->fetch('html/content.tpl');

    $objTpl = new TPLManager($FILEPATH . '/index.html');
    $hotspots = $objTpl->getHotspotList();
    $placeHolders = $objTpl->getPlaceHolders($hotspots);
    $i = 0;
    foreach ($placeHolders as $items) {
        $smarty->assign("$hotspots[$i]", "$items");
        $i++;
    }

    $smarty->assign('content', $output);
    $smarty->assign('error', $warning);
    $smarty->display($FILEPATH . '/index.html');


    
    ?>