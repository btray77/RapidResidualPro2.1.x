<?php
session_start();
class common {
    public $min_logout_time;
    public $root_path;
    public $http_path;
    //====================================================================================
    // constructor
    function common() {
        if (!include("config.php"))
            die('Sorry! unable to get config.php');
        $this->min_logout_time = 90;
        $this->path = $path;
        $this->root_path = $root_path;
        $this->http_path = $http_path;
        $this->prefix = $prefix;
    }
    //=====================================================================================
    //=====================================================================================
    //to generate a secure unique sessionkey
    function hashgen() {
        $hash = md5(uniqid(rand(), 1));
        return $hash;
    }
    //=====================================================================================
    //=====================================================================================
    function return_file_content($db_domain, $xpath) {
        $fp = fopen("$xpath", "r");
        $fullcontent = fread($fp, filesize("$xpath"));
        fclose($fp);
        return $fullcontent;
    }
    //=====================================================================================
    //=====================================================================================
    // Admin time out check. Edit the min_logout_time variable in the common function to change the timeout period.
    function check_admin_session($hash, $db_domain) {
        if (!include("config.php"))
            die('Sorry! unable to get config.php');
        $qry = "Select admin_id,timestamp from " . $prefix . "admin_session where hash='$hash'";
        $line = $db_domain->get_a_line($qry);
        $adminid = $line[0];
        $min_logout_time = $this->min_logout_time;
        $min = $min_logout_time * 90;
        if ((time() - $line[1] ) > $min) {
            return 0;
        } else {
            $timestmp = time();
            $qry = "Update " . $prefix . "admin_session set timestamp='$timestmp' where hash='$hash'";
            if (!($result = mysql_query("$qry"))) {
                $men = mysql_errno();
                $mem = mysql_error();
                echo ("<h4>$qry  $men $mem</h4>");
                exit;
            } else {
                return $adminid;
            }
        }
    }
    //=====================================================================================
    //=====================================================================================
    // Member time out check. Edit the min_logout_time variable in the common function to change the timeout period.
    function check_session($hash, $db_domain) {
        if (!include("config.php"))
            die('Sorry! unable to get config.php');
        $session = $prefix . "member_session";
        $qry = "Select member_id, time from $session  where hash='$hash'";
        $line = $db_domain->get_a_line($qry);
        $usrid = $line[0];
        $min_logout_time = $this->min_logout_time;
        $min = $min_logout_time * 90;
        if ((time() - $line[1] ) > $min) {
            return 0;
        } else {
            $timestmp = time();
            $qry = "Update $session set time=$timestmp where hash='$hash'";
            if (!($result = mysql_query("$qry"))) {
                $men = mysql_errno();
                $mem = mysql_error();
                echo ("<h4>$qry  $men $mem</h4>");
                exit;
            } else {
                return $usrid;
            }
        }
    }
    //=====================================================================================
    //=====================================================================================
    // Member session check for 3rd party scripts
    function check_3rdsession($hash, $db_domain) {
        if (!include("config.php"))
            die('Sorry! unable to get config.php');
        $session = $prefix . "member_session";
        $qry = "Select member_id, time from $session where hash='$hash'";
        $line = $db_domain->get_a_line($qry);
        $usrid = $line[0];
        $min_logout_time = $this->min_logout_time;
        $min = $min_logout_time * 90;
        if ((time() - $line[1] ) > $min) {
            return 0;
        } else {
            $timestmp = time();
            $qry = "Update $session set time=$timestmp where hash='$hash'";
            if (!($result = mysql_query("$qry"))) {
                $men = mysql_errno();
                $mem = mysql_error();
                echo ("<h4>$qry  $men $mem</h4>");
                exit;
            } else {
                return $usrid;
            }
        }
    }
    //=====================================================================================
    //=====================================================================================
    //Session check for downloads
    function check_sessiond($hash, $db_domain) {
        if (!include("config.php"))
            die('Sorry! unable to get config.php');
        $session = $prefix . "member_session";
        $qry = "Select member_id,time from $session  where hash='$hash'";
        $line = $db_domain->get_a_line($qry);
        $usrid = $line[0];
        $min_logout_time = $this->min_logout_time;
        $min = $min_logout_time * 60;
        if ((time() - $line[1] ) > $min) {
            return 0;
        }//end if
        else {
            $timestmp = time();
            $qry = "Update $session set time=$timestmp where hash='$hash'";
            if (!($result = mysql_query("$qry"))) {
                $men = mysql_errno();
                $mem = mysql_error();
                echo ("<h4>$qry  $men $mem</h4>");
                exit;
            }//end inner if
            else {
                return $usrid;
            }//end inner else
        }//end else
    }
//function check_session()
    //=====================================================================================
    //=====================================================================================
    // Page break pagination for multiple pages
    function print_page_break3($db_object, $return_content, $count, $records, $links, $fPage) {
        $pages = ceil($count / $records);
        //$pattern="/<{page_loopstart}>(.*?)<{page_loopend}>/s";
        //preg_match($pattern,$return_content,$out);
        $myvar = $out[1];
        $str = "";
        $temp_link = $links;
        if ($pages != 1) {
            $tPage = $fPage . "/" . $pages . " Page(s) ";
        } else {
            $tPage = "";
        }
        for ($i = 1; $i <= $pages; $i++) {
            $link = $links . "page=$i";
            $page1 = $i;
            if ($page1 == $fPage) {
                //$page1 = $i;
                $p = $i - 1;
                $n = $i + 1;
                //$str.=preg_replace("/<{(.*?)}>/e","$$1",$myvar);
            } else {
                if ($fPage == 1) {
                    $l_n = $fPage + 4;
                    $l_p = 0;
                } elseif ($fPage == 2) {
                    $l_n = $fPage + 3;
                    $l_p = 0;
                } elseif ($fPage == $pages) {
                    $l_n = $pages;
                    $l_p = $fPage - 4;
                } elseif ($fPage == ($pages - 1)) {
                    $l_n = $pages;
                    $l_p = $fPage - 3;
                } else {
                    $l_n = $fPage + 2;
                    $l_p = $fPage - 2;
                }
                if (($page1 <= $l_n) and ($page1 >= $l_p)) {
                    $page1 = $pagefrom + $page1;
                    $page1 = "<a href=\"$link\">$page1</a>";
                }
            }
        }
        //$return_content=preg_replace($pattern,$str,$return_content);
        $prev = $temp_link . "page=" . $p;
        $next = $temp_link . "page=" . $n;
        if ($pages > 1) {
            if ($n > $pages || $pages == 0) {
                $next = " Next";
            } else {
                $next = "<a href='$next'>Next</a> ";
            }
            if ($p == 0) {
                $previous = "Previous ";
            } else {
                $previous = "<a href='$prev'>Previous</a> ";
            }
        } else {
            $next = "";
            $previous = "";
        }
        if ($pages > 1) {
            if ($fPage == 1) {
                $first = "&laquo;First ";
            } else {
                $first = "<a href='" . $temp_link . "page=1'>&laquo;First</a> ";
            }
            if ($fPage == $pages) {
                $last = "Last&raquo; ";
            } else {
                $last = "<a href='" . $temp_link . "page=" . $pages . "'>Last&raquo;</a> ";
            }
        } elseif ($pages < 1) {
            $first = "";
            $last = "";
            $single = "1";
        }
        $content = "<div class='totalpage'>Total:$count</div>";
        $content.="<div class='page'>$single $first $previous  $next $last</div>";
        return $content;
    }
    //=====================================================================================
    //=====================================================================================
    function pagiation_simple($targetpage, $limit, $total_pages, $page, $start, $content='') {
        $adjacents = 3;
        if (strstr($targetpage, '?')) {
            $targetpage = "$targetpage&pageno=";
        }
        else
            $targetpage = "$targetpage?pageno=";
        if (!empty($content))
            $targetpage.="$targetpage&content=$content";
        if ($page == 0)
            $page = 1;     //if no page var is given, default to 1.
        $prev = $page - 1;       //previous page is page - 1
        $next = $page + 1;       //next page is page + 1
        $lastpage = ceil($total_pages / $limit);  //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<div class=\"pagination\">";
            //previous button
            if ($page > 1)
                $pagination.= "<a href=\"$targetpage$prev&amp;limit=$limit#pagination\">&lt;&lt; previous</a>";
            else
                $pagination.= "<span class=\"disabled\">&lt;&lt; previous</span>";
            //pages
            if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage$counter&amp;limit=$limit#pagination\">$counter</a>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage$counter&amp;limit=$limit#pagination\">$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"$targetpage$lpm1&amp;limit=$limit#pagination\">$lpm1</a>";
                    $pagination.= "<a href=\"$targetpage$lastpage&amp;limit=$limit#pagination\">$lastpage</a>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination.= "<a href=\"$targetpage1&amp;limit=$limit#pagination\">1</a>";
                    $pagination.= "<a href=\"$targetpage2&amp;limit=$limit#pagination\">2</a>";
                    $pagination.= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage$counter&amp;limit=$limit#pagination\">$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"$targetpage$lpm1&amp;limit=$limit#pagination\">$lpm1</a>";
                    $pagination.= "<a href=\"$targetpage$lastpage&amp;limit=$limit#pagination\">$lastpage</a>";
                }
                //close to end; only hide early pages
                else {
                    $pagination.= "<a href=\"$targetpage1&amp;limit=$limit#pagination\">1</a>";
                    $pagination.= "<a href=\"$targetpage2&amp;limit=$limit#pagination\">2</a>";
                    $pagination.= "...";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage$counter&amp;limit=$limit#pagination\">$counter</a>";
                    }
                }
            }
            //next button
            if ($page < $counter - 1)
                $pagination.= "<a href=\"$targetpage$next&amp;limit=$limit#pagination\">Next &gt;&gt;</a>";
            else
                $pagination.= "<span class=\"disabled\">Next &gt;&gt;</span>";
            $pagination.= "</div>\n";
        }
        if ($start == 0) {
            $totalrec = $limit;
            $startrec = $start + 1;
        } else {
            $startrec = $start;
            $totalrec = $startrec + $limit;
        }
        $content = '';
        $content .= '</table><div class="pages">';
        $content .= '<div class="totalpages">Total: ' . $total_pages . '</div>';
        $content .= '<div class="pager">' . $pagination . '&nbsp;</div></div>';
        return $content;
    }
    //to generate a random password
    function createRandomPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
    //=====================================================================================
    //=====================================================================================
    // send emails out
    function sendemail($sender_name, $webmaster_email, $email, $subject, $body, $header) {
        require_once('phpmailer/class.phpmailer.php');
        require_once('database.class.php');
        $db = new database();
        $q = "select mailer,email_from_name,from_name,mailer,smtpsecure,smtphost,smtpport,smtpusername,smtppassword,mailer_details from " . $this->prefix . "site_settings where id='1'";
        $v = $db->get_a_line($q);
        extract($v);
        global $error;
        $mail = new PHPMailer();  // create a new object
        //$mail->IsSMTP(); 
        if (empty($email))
            $email = $email_from_name;
			
		if ($mailer == 'smtp') {
            $mail->Mailer = $mailer; // enable SMTP
            $mail->IsHTML();
            $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true;  // authentication enabled
            $mail->SMTPSecure = $smtpsecure; // secure transfer enabled REQUIRED for Gmail
            $mail->Host = $smtphost; // SMTP HOST
            $mail->Port = "$smtpport"; //465 
            $mail->Username = "$smtpusername";
            $mail->Password = "$smtppassword";
            $mail->SetFrom($email_from_name, $from_name);
            //$mail->AddReplyTo($webmaster_email, $sender_name);
            $mail->Subject = $subject;
            //$body = $body . "<p>$mailer_details</p>";
			$mail->Body = nl2br($body);
            $mail->AddAddress($email);
            if (!$mail->Send()) {
                $error = '<div class="error">Mail error: ' . $mail->ErrorInfo . "</div>";
                return false;
            } else {
                return true;
            }
        } else {
            $header = 'MIME-Version: 1.0' . "\r\n";
            $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $header .= "From: " . $sender_name . " <" . $webmaster_email . ">";
            $body = nl2br($body . "<p>$mailer_details</p>");
            if (!mail($email, $subject, $body, $header)) {
                $error = '<div class="error">Mail error: Sorry your server can not send email using PHP mail. Please configure your SMTP in site settings.</div>';
                return false;
            } else {
                return true;
            }
        }
    }
    //=====================================================================================
    //=====================================================================================
    //to generate a one off sale paypal button
    function paypalbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return) {
        // If price is negative then make it zero
        if ($price < 0) {
            $price = 0;
        } else {
            $price = $price;
        }
        if (empty($image))
            $image = '/images/payment_buttons/paypal_button.png';
        $button = "<form action='$paypath' method='post' name='paymentfrm'>
		<input type='hidden' name='cmd' value='_xclick'>		
		<input type='hidden' name='rm' value='2'>
		<input type='hidden' name='business' value='$receiver'>
		<input type='hidden' name='item_name' value='$itemname'>
		<input type='hidden' name='item_number' value='$pid'>
		<input type='hidden' name='amount' value='$price'>
		<input type='hidden' name='custom' value='$rands'>
		<input type='hidden' name='return' value='$return_url'>
		<input type='hidden' name='notify_url' value='$notify_url'>
		<input type='hidden' name='cancel_return' value='$http_path'>
		<input type='hidden' name='no_note' value='1'>
		<input type='hidden' name='no_shipping' value='1'>
		<input type='hidden' name='currency_code' value='USD'>		
		<input type='hidden' name='cpp_header_image' value='$pp_header'>
		<input type='hidden' name='cbt' value='$pp_return'>
		<input type='image' style='border: 0pt none ;' src='" . $http_path . "" . str_replace("..", "", $image) . "'>
		</form>
		";
        return $button;
    }
    //=====================================================================================
    //=====================================================================================
    //=====================================================================================
    //=====================================================================================
    //to generate a one off sale paypal button
    function paypalbutton_hidden($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return) {
        // If price is negative then make it zero
        if ($price < 0) {
            $price = 0;
        } else {
            $price = $price;
        }
        $button = "<form action='$paypath' method='post' name='paymentfrm' >
		<input type='hidden' name='cmd' value='_xclick'>		
		<input type='hidden' name='rm' value='2'>
		<input type='hidden' name='business' value='$receiver'>
		<input type='hidden' name='item_name' value='$itemname'>
		<input type='hidden' name='item_number' value='$pid'>
		<input type='hidden' name='amount' value='$price'>
		<input type='hidden' name='custom' value='$rands'>
		<input type='hidden' name='return' value='$return_url'>
		<input type='hidden' name='notify_url' value='$notify_url'>
		<input type='hidden' name='cancel_return' value='$http_path'>
		<input type='hidden' name='no_note' value='1'>
		<input type='hidden' name='no_shipping' value='1'>
		<input type='hidden' name='currency_code' value='USD'>		
		<input type='hidden' name='cpp_header_image' value='$pp_header'>
		<input type='hidden' name='cbt' value='$pp_return'>
		
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
    //=====================================================================================
    //=====================================================================================
    //to generate a subscription paypal button
    function paypalsubbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return) {
        // If price is negative then make it zero
        if ($price < 0) {
            $price = 0;
        } else {
            $price = $price;
        }
        // If price is negative then make it zero
        if ($amount3 < 0) {
            $amount3 = 0;
        } else {
            $amount3 = $amount3;
        }
        if (empty($image))
            $image = '/images/payment_buttons/paypal_button.png';
        $button = "<form action='$paypath' method='post' name='paymentfrm'>
		<input type='hidden' name='cmd' value='_xclick-subscriptions'>
		<input type='hidden' name='business' value='$receiver'>
		<input type='hidden' name='item_name' value='$itemname'>
		<input type='hidden' name='item_number' value='$pid'>
		<input type='hidden' name='rm' value='2'>
		<input type='hidden' name='return' value='$return_url'>
		<input type='hidden' name='cancel_return' value='$http_path'>
		<input type='hidden' name='notify_url' value='$notify_url'>	
		<input type='hidden' name='currency_code' value='USD'>	
		<input type='hidden' name='custom' value='$rands'>
		<input type='hidden' name='no_note' value='1'>
		<input type='hidden' name='no_shipping' value='1'>
		<input type='hidden' name='usr_manage' value='0'>	
		<input type='hidden' name='sra' value='1'>
		" . $recurr . "	
		" . $trial1 . "
		" . $trial2 . "
		<input type='hidden' name='a3' value='$amount3'>
		<input type='hidden' name='p3' value='$period3_value'>
		<input type='hidden' name='t3' value='$period3_interval'>
		<input type='hidden' name='cpp_header_image' value='$pp_header'>
		<input type='hidden' name='cbt' value='$pp_return'>
		<input type='image' style='border: 0pt none ;' src='" . $http_path . "" . str_replace("..", "", $image) . "'>
		</form>
		";
        return $button;
    }
    function paypalsubbutton_hidden($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return) {
        // If price is negative then make it zero
        if ($price < 0) {
            $price = 0;
        } else {
            $price = $price;
        }
        // If price is negative then make it zero
        if ($amount3 < 0) {
            $amount3 = 0;
        } else {
            $amount3 = $amount3;
        }
        $button = "<form action='$paypath' method='post' name='paymentfrm'>
		<input type='hidden' name='cmd' value='_xclick-subscriptions'>
		<input type='hidden' name='business' value='$receiver'>
		<input type='hidden' name='item_name' value='$itemname'>
		<input type='hidden' name='item_number' value='$pid'>
		<input type='hidden' name='rm' value='2'>
		<input type='hidden' name='return' value='$return_url'>
		<input type='hidden' name='cancel_return' value='$http_path'>
		<input type='hidden' name='notify_url' value='$notify_url'>	
		<input type='hidden' name='currency_code' value='USD'>	
		<input type='hidden' name='custom' value='$rands'>
		<input type='hidden' name='no_note' value='1'>
		<input type='hidden' name='no_shipping' value='1'>
		<input type='hidden' name='usr_manage' value='0'>	
		<input type='hidden' name='sra' value='1'>
		" . $recurr . "	
		" . $trial1 . "
		" . $trial2 . "
		<input type='hidden' name='a3' value='$amount3'>
		<input type='hidden' name='p3' value='$period3_value'>
		<input type='hidden' name='t3' value='$period3_interval'>
		<input type='hidden' name='cpp_header_image' value='$pp_header'>
		<input type='hidden' name='cbt' value='$pp_return'>
		
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
    //=====================================================================================
    //=====================================================================================
    function getdash($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
    //=====================================================================================
    //=====================================================================================
    function mywordwrap($string, $width) {
        $length = strlen($string);
        for ($i = 0; $i <= $length; $i = $i + 1) {
            $char = substr($string, $i, 1);
            if ($char == "<")
                $skip = 1;
            elseif ($char == ">")
                $skip = 0;
            elseif ($char == " ")
                $wrap = 0;
            if ($skip == 0)
                $wrap = $wrap + 1;
            $returnvar = $returnvar . $char;
            if ($wrap > $width) { // alter this number to set the maximum word length
                $returnvar = $returnvar . "<br>";
                $wrap = 0;
            }
        }
        return $returnvar;
    }
    function show_error($error) {
        if ($error == '1') {
            $msgerror .= "INVALID URL PASSED<br>You must supply the correct variable after /go/.";
        } elseif ($error == '2') {
            $msgerror .= "Sorry, you supplied an incorrect member value.";
        } elseif ($error == '3') {
            $msgerror .= "Sorry, you supplied an incorrect member, product or coupon value.";
        } elseif ($error == '4') {
            $msgerror .= "We could not confirm your PayPal payment. Please contact the site admin for instructions.";
        } elseif ($error == '5') {
            $msgerror .= "You can only access this page after a valid payment has been made.";
        } elseif ($error == '6') {
            $msgerror .= "INVALID URL PASSED<br>You must supply the correct page name variable.";
        } elseif ($error == '7') {
            $msgerror .= "Sorry, but the coupon code you supplied has expired.";
        } elseif ($error == '8') {
            $msgerror .= 'Invalid captcha entered, please try again.';
        }
        if (!empty($msgerror))
            return "<div class='error'>" . $msgerror . "</div>";
        else
            return '';
    }
    public function strip_html_tags($text) {
        $text = preg_replace(
                array(
            // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
                ), array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
                ), $text);
        return strip_tags($text);
    }
    //=====================================================================================
    function show_comments($prefix, $db, $page, $type) {
        //$sql="SELECT * FROM ".$prefix."comments WHERE page='$page' AND type='$type' AND published=1 order by id";
        $sql_comment = "SELECT id, display_name, display_url, date, comment
       FROM " . $prefix . "comments
       where type='$type'
       and page='$page'
       and published=1
       ORDER BY id";
        $row_comment = $db->get_rsltset($sql_comment);
        $out_comments = '';
        if (count($row_comment) > 0) {
            $j = 0;
            // start of reply section <a href="' . $cvalue['display_url'] . '" rel="nofollow">' . $cvalue['display_url'] . '</a>
            foreach ($row_comment as $cvalue) {
                $comments[$j]['display_url'] = stripslashes(str_replace("http://", "", $cvalue[display_url]));
                $comments[$j]['display_name'] = stripslashes($cvalue[display_name]);
                $comments[$j]['date'] = $cvalue[date];
                $comments[$j]['comment'] = stripslashes($cvalue[comment]);
                $sql_reply = "select * from " . $prefix . "comments_reply where comment_id = '" . $cvalue['id'] . "'";
                $row_reply = $db->get_rsltset($sql_reply);
                if (count($row_reply) > 0) {
                    $i = 0;
                    foreach ($row_reply as $reply) {
                        $row_replys[$i]['postedon'] = date("F d, Y H:i a", strtotime($reply['postedon']));
                        $row_replys[$i]['title'] = stripslashes($reply['title']);
                        $row_replys[$i]['description'] = stripslashes($reply['description']);
                        $i++;
                    }
                    $comments[$j]['reply'] = $row_replys;
                }
                $j++;
            }
            // end of reply section
        }
        return $comments;
    }
    //=====================================================================================
    function getTimeRelaseContent($prefix, $db, $tcontent1, $difference) {
      $title = (string) $this->get_instruction_text($prefix, $db);
        $pid = $_GET['pid'];
        $sql = "select * from " . $prefix . "timed_content where campaign='$tcontent1' ORDER BY trorder ASC";
        $GetMembers = $db->get_rsltset($sql);
        if (count($GetMembers) > 0) {
            $list = '<h2>'. $title.'</h2>
				<p class="spacer">&nbsp;</p>
				<ul>';
            for ($i = 0; $i < count($GetMembers); $i++) {
                @extract($GetMembers[$i]);
                if ($_GET['content'] == $filename) {
                    $cssStyle = "class = 'rightActive'";
                } else {
                    $cssStyle = "";
                }
                if ($difference >= $available) {
                    $list.= '<li>
					<a ' . $cssStyle . ' href=time-content.php?content=' . $filename . '&tcontent1=' . $tcontent1 . '&pid=' . $pid . '#top>' . $pagename . '</a></li>';
                } else {
                    $list.= '<li>
					<a  style="color:#000;cursor:arrow" title="' . $pagename . ' is not available yet">' . $pagename . '</a>
					</li>';
                }
            }
            $list.='</ul>';
        }
        return $list;
    }
    //=====================================================================================
    function time_release_difference($prefix, $db, $product_id, $memberid) {
        //$q = "select (DATEDIFF(NOW(),`date_added`)) as diff from rrp_member_products where product_id='$product_id' AND member_id = '$memberid'";
      $q = "SELECT (DATEDIFF(NOW(), mprod.date_added)) as diff, mprod.txn_id as txn_id, ord.txnid as txnid, mprod.product_id as product_id, mprod.member_id as member_id, ord.payment_status as payment_status
		    from rrp_member_products mprod, rrp_orders ord
		    WHERE mprod.product_id = '$product_id'
		    AND mprod.member_id = '$memberid'
		    AND ord.payment_status = 'Completed'";
        $v = $db->get_a_line($q);
        $difference = $v['diff'];
        return $difference;
    }
    /*     * *********************************************************************************** */
//and mp.txn_id = o.txnid
    function myDownloads($prefix, $db, $memberid) {
		$title = $this->get_my_download_text($prefix, $db);
        $sql = "select id, product_name, coaching, pshort from " . $prefix . "products
     		    where id in (select mp.product_id from " . $prefix . "member_products mp, " . $prefix . "orders o where  
                     mp.member_id='$memberid' && mp.refunded='0' and mp.txn_id=o.txnid and o.payment_status ='Completed') ORDER BY product_name ASC";
        $products = $db->get_rsltset($sql);
        if (count($products) > 0) {
            $str_product = '<h2>'. $title .'</h2><ul>';
            foreach ($products as $pro) {
                // Coaching link section starts here
                if ($pro['coaching'] == 'yes') {
                    $sql_total = "select count(*) as total from " . $prefix . "member_messages
         where product = '$pro[pshort]' && mid = '$memberid' && mchecked='0'";
                    $row_total_checked = $db->get_a_line($sql_total);
                    $sql_total = "select count(*) as total from " . $prefix . "member_messages
         where product='$pro[pshort]' && mid='$memberid'";
                    $row_total = $db->get_a_line($sql_total);
                    if ($row_total_checked['total'] == '0') {
                        $getcoachlink = "<a style='font-size:11px;' href='index.php?page=messages&pid=" . $pro[pshort] . "'>Coaching</a> (" . $row_total['total'] . ")";
                    } else {
                        $getcoachlink = "<a style='font-size:11px;' href='index.php?page=messages&pid=" . $pro[pshort] . "'>Coaching (" . $row_total['total'] . ") <span class='new'>" . $row_total_checked['total'] . " New</span>  </a>";
                    } // end count1
                } else {
                    $getcoachlink = '';
                }
                // Making link acitve
                if ($_GET['pid'] == $pro['id']) {
                    $cssStyle = "class = 'rightActive'";
                } else {
                    $cssStyle = "";
                }
                // Coaching link section ends here
                $str_product .= '<li>
				<a ' . $cssStyle . ' href=paid.php?pid=' . $pro['id'] . '#top>' . $pro['product_name'] . '</a>&nbsp;<br /><span style="font-size:11px;">' . $getcoachlink . '</span></li>';
            }
        }
        $str_product.='</ul>';
        return $str_product;
    }
    //=====================================================================================
    function newProducts($prefix, $db, $memberid) {
	 $title = $this->get_new_products_text($prefix, $db);
        $sql = "select id, pshort,product_name from " . $prefix . "products where published=1 and 
             prodtype <> 'OTO' and
            add_in_sidebar = 'yes' and
            id not in (select product_id from " . $prefix . "member_products where member_id='$memberid' && refunded='0')
	ORDER BY product_name ASC limit 0,10";
        $products = $db->get_rsltset($sql);
        if (count($products) > 0) {
            $new_products = '<h2>'. $title.'</h2><ul>';
            foreach ($products as $pro) {
                // Making link acitve
                if ($_GET['short'] == $pro['pshort']) {
                    $cssStyle = "class = 'rightActive'";
                } else {
                    $cssStyle = "";
                }
                $new_products .= '<li>
                    <a  ' . $cssStyle . 'href=products.php?short=' . $pro['pshort'] . '#top>' . $pro['product_name'] . '</a>
                        </li>';
            }
             $new_products.='</ul>';
        }
       
        return $new_products;
    }
    //**********************************************************************************************/
    function getmedia($type, $itemid, $db, $prefix) {
        switch ($type) {
            case 'video':
                $content = $this->loadVideo($itemid, $db, $prefix);
                break;
            case 'audio':
                $content = $this->loadAudio($itemid, $db, $prefix);
                break;
            case 'file':
                $content = $this->loadImage($itemid, $db, $prefix);
                break;
        }
        return $content;
    }
    /*     * ************************************************************************************************** */
    /*     * ************************************************************************************************** */
    function loadVideo($itemid, $db, $prefix) {
        include("{$this->root_path}/admin/media-storage/common.php");
        $sql_rs_setting = "select * from " . $prefix . "site_settings";
        $rs_settings = $db->get_a_line($sql_rs_setting);
	$media_path=  $rs_settings['swf_down'];
        $sql = "select * from " . $prefix . "amazon_s3 where id =$itemid";
        $results = $db->get_a_line($sql);
        if (empty($_SESSION['memberid']))
            $memberid = $_COOKIE['memberid'];
        else
            $memberid = $_SESSION['memberid'];
        if ($results['content_access'] == 'Private' && $memberid < 1) {
            $str = '<div class="error">
				This is private content for members only. Please login to view or, if you are not a member yet go ahead and enroll today to get immediate access!</div>';
        } else {
            $sql = "select count(*) as total from " . $prefix . "amazon_s3 where id = $itemid";
            $rs_total = $db->get_a_line($sql);
           
            $full_screen = ($results['full_screen'] == 'Yes') ? 'true' : 'false';
            $autostart = ($results['auto_play'] == 'Yes') ? 'true' : 'false';
            if ($results['player_controls'] == 'Yes') {
                $player_controls = " controls: {
                                    backgroundColor: '" . $results['player_color'] . "',
                                    scaling: 'fit',
                                    autoHide: 'never',
                                    autoBuffering: true,
                                    bufferLength: 5,
                                    backgroundGradient: 'low',
                                    fullscreen: " . $full_screen . ",
                                    }";
            } else {
                $player_controls = "controls: null";
            }
            if ($rs_total['total'] > 0) {
                if (!empty($results['download_graphic']))
                    $button = '<img src="' . $this->http_path . '/images/uploads/' . $results['download_graphic'] . '" border=0 alt="Download">';
                else
                    $button = '<span class="download_button">Click here to download</span>';
                $bucket = trim($results['bucket_id']);
                if ($bucket == 'local') {
                    if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"]))
                    $file=$this->http_path."/$media_path/".$results['content_id'];
                    else
                    $file = "" . $this->http_path . "/videos/" . $results['hidden_id'];
                    $download = "" . $this->http_path . "/download/" . $results[hidden_id];
                    $download_link = ($results['download_link'] == 'Yes') ? '<a href="' . $download . '">'. $button .'</a>' : '';
                } else {
                    if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"]))
                            $file = "https://s3.amazonaws.com/".$results['bucket_id'].'/'.$results['content_id'];
                   else
                            $file = "" . $this->http_path . "/videos/" . $results['hidden_id'];
                   $download = "" . $this->http_path . "/download/" . $results['content_id'];	
                   $download_link = ($results['download_link'] == 'Yes') ? '<a href="'. $download .'">'. $button .'</a>' : '';
                }
                if (!empty($file)) {
                    $mysql = "SELECT domain_name FROM " .$prefix . "amazon_cloud_front WHERE buket_id='" .$results['bucket_id'] . "'";
		    $rslt_cf = $db->get_a_line($mysql);
                    $mediaName = explode('.', $results['content_id']);
                    if ($rs_settings['cloud_fornt'] != 1) { 
                        if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"])){
                        $str  = "<video id= '$results[id]' height=\"270\" src=\"$file\" width=\"480\"></video>";
                        $str .= '<div class="download">'.$download_link.'</div>';
                        }
                        else {
                            $str = '<a 
                            id="player'.$results[id].'" 
                            class="player'.$results[id].'" 
                            href="' . $file . '" 
                            style="display:block;width:'.$results['player_width']."px;height:".$results['player_height'].'px;">
                            </a>
                            <script src="http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js"></script>
                            
                            ';		
                            $str .= "<script type=\"text/javascript\">
                            flowplayer('a.player$results[id]',{src:'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', wmode:'opaque'}, {
                            key: '77cfdec6a8b2ac9cb19',
                            clip: {autoPlay: " . $autostart . "},
                            plugins: {" . $player_controls . "}
                            });</script>";
                            $str .= '<div class="download">'.$download_link.'</div>';		
                        }
                    }
                    elseif (!empty($rslt_cf['domain_name'])) {
                        if ($mediaName[1] == 'mp4') {
                        $extCode = 'mp4:';
                        $href = 'href="mp4:' . $mediaName[0] . '"';
                        } else {
                        $extCode = '';
                        $href = 'href="' . $mediaName[0] . '"';
                        }
                        if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"])){
                        $str  = "<video  id= '$results[id]' height=\"270\" src=\"$file\" width=\"480\"></video>";
                        $str .='<div class="download">'.$download_link.'</div>';
                        }
                        else
                        {
                        $str = '
                        <a 
                        id="player'.$results['id'].'" 
                        class="player'.$results['id'].'" 
                        '. $href .' style="display:block;width:'.$results['player_width']."px;height:".$results['player_height'].'px;">
                        </a>
                        <script src="http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js"></script>
                        
                        <script type="text/javascript">';
                        $str .= " flowplayer('a.player$results[id]',{src:'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', wmode:'opaque'}, {
                        key: '77cfdec6a8b2ac9cb19',
                        clip: {                                        
                        autoPlay: " . $autostart . ",
                        provider: 'rtmp',
                        },
                        plugins: {
                            rtmp: {
                            url: 'http://www.rapidresidualpro.com/flowplayer/flowplayer.rtmp-3.2.3.swf',
                            netConnectionUrl: 'rtmp://" . $rslt_cf['domain_name'] . "/cfx/st'
                            },
                        " . $player_controls . "
                            }
                        });";
                        $str .= '</script>';
                        $str .= '<div class="download">'.$download_link.'</div>';
                        }
                    } 
                    else {// IF Cloud Front Stream is not available then load the Normal S3 Contents
                    if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"])){
                        $str = "<video id= '$results[id]' height=\"270\" src=\"$file\" width=\"480\"></video>";
                        $str .= '<div class="download">'.$download_link.'</div>';		
                    } else {
                    $str = '<a 
                    id="player'.$results[id].'" class="player'.$results[id].'" href="' . $file . '" style="display:block;width:' . $results['player_width'] . "px;height:" . $results['player_height'] . 'px;">
                    </a>
                    <script src="http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js"></script>
                   
                    ';	
                    $str .= "<script type=\"text/javascript\">
                        flowplayer('a.player$results[id]',{src:'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', wmode:'opaque'}, {
                        key: '77cfdec6a8b2ac9cb19',
                        clip: {autoPlay: " . $autostart . "},
                        plugins: {
                        " . $player_controls . "
                        }
                        });
                    </script>";
                    $str .= '<div class="download">'.$download_link.'</div>';		
                    }	
                }
              } else  $str = '';
                
            } else {  $str = '';  }
        }
        return $str;
    }
    /*     * *************************************************************************************************** */
    function loadImage($itemid, $db, $prefix) {
        include("{$this->root_path}/admin/media-storage/common.php");
        $sql = "select * from " . $prefix . "amazon_s3 where id =$itemid";
        $results = $db->get_a_line($sql);
		
        $token = explode('_', $results['custom_token']);
        $content_type = $token[0];
        $sql = "select count(*) as total from " . $prefix . "amazon_s3 where id = $itemid";
        $rs_total = $db->get_a_line($sql);
        if (empty($_SESSION['memberid']))
            $memberid = $_COOKIE['memberid'];
        else
            $memberid = $_SESSION['memberid'];
        if ($results['content_access'] == 'Private' && $memberid < 1) {
            $str = '<div class="error">
					This is private content for members only. Please login to view or, if you are not a member yet go ahead and enroll today to get immediate access!
				   </div>';
        } else {
            if ($rs_total['total'] > 0) {
                if (!empty($results['download_graphic']))
                    $button = '<img src="' . $this->http_path . '/images/uploads/' . $results['download_graphic'] . '" border=0 alt="Download">';
                else
                    $button = '<span class="download_button">Click here to download</span>';
                $bucket = trim($results['bucket_id']);
                if ($bucket == 'local') {
                    $sql_setting = "select * from " . $prefix . "site_settings";
                    $settings = $db->get_a_line($sql_setting);
                    if ($content_type == 'video')
                        $download = $this->http_path . "/download/" . $results['hidden_id'];
                    else if ($content_type == 'audio')
                        $download = $this->http_path . "/download/" . $results['hidden_id'];
                    else
                    $download = $this->http_path . "/download/" . $results['hidden_id'];
                    $download_link = ($results['download_link'] == 'Yes') ? '<a href="'.$download.'">'.$button.'</a>' : '';
                }
                else {
                    $download = $this->http_path . "/download/" . $results['hidden_id'];
                    $download_link = ($results['download_link'] == 'Yes') ? '<a href="'.$download.'">'.$button.'</a>' : '';
                }
            }
            else
                $download_link = '';
        }
        return $download_link;
    }
    /*     * ************************************************************************************************** */
    function loadAudio($itemid, $db, $prefix) {
        include("$this->root_path/admin/media-storage/common.php");
		
        $sql_rs_setting = "select * from " . $prefix . "site_settings";
        $rs_settings = $db->get_a_line($sql_rs_setting);
		$media_path=  $rs_settings['swf_down'];
        $sql = "select * from " . $prefix . "amazon_s3 where id = $itemid";
        $results = $db->get_a_line($sql);
        if (empty($_SESSION['memberid']))
            $memberid = $_COOKIE['memberid'];
        else
            $memberid = $_SESSION['memberid'];
        if ($results['content_access'] == 'Private' && $memberid < 1) {
            $str = '<div class="error">
					This is private content for members only. Please login to view or, if you are not a member yet go ahead and enroll today to get immediate access!
				   </div>';
        } else {
            $sql = "select count(*) as total from " . $prefix . "amazon_s3 where id = $itemid";
            $rs_total = $db->get_a_line($sql);
            $autostart = ($results['auto_play'] == 'Yes') ? 'true' : 'false';
            if ($results['player_controls'] == 'Yes') {
            $player_controls = " controls: {
                                    backgroundColor: '" . $results['player_color'] . "',
                                    fullscreen: false,
                                    autoHide: 'never',
                                    backgroundGradient: 'low',
                                }";
            } else {
          $player_controls = " controls: {
                                    backgroundColor: '" . $results['player_color'] . "',
                                    fullscreen: false,
                                    autoHide: 'never',
                                    backgroundGradient: 'low',
                                                }
                  ";
            }
            if ($rs_total['total'] > 0) {
                if (!empty($results['download_graphic']))
                    $button = '<img src="' . $this->http_path . '/images/uploads/' . $results['download_graphic'] . '" border=0 alt="Download">';
                else
                    $button = '<span class="download_button">Click here to download</span>';
                $bucket_id = trim($results['bucket_id']);
                if ($bucket_id == 'local') {
                    $sql_setting = "select * from " . $prefix . "site_settings";
                    $settings = $db->get_a_line($sql_setting);
                    if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"]))
                    {
                     $file=$this->http_path."/images/media/".$results['content_id'];
                     $download = $this->http_path."/$media_path/".$results['content_id'];
                    }
                    else		
                    { 	
                    $file = $this->http_path . "/audios/" . $results['hidden_id'].'.mp3';
                     $download = $this->http_path . "/download/" . $results['hidden_id'];
                    }
                    $download_link = ($results['download_link'] == 'Yes') ? '<a href="'.$download.'">'.$button.'</a>' : '';
                } 
                else {
                    if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"]))
                     {
                        $file = "https://s3.amazonaws.com/".$results['bucket_id'].'/'.$results['content_id'];
                        $download = "https://s3.amazonaws.com/".$results['bucket_id'].'/'.$results['content_id'];
                     }
                    else
                     {
                       $file = $this->http_path . "/audios/" . $results['hidden_id'].'.mp3';
                       $download = "" . $this->http_path . "/download/" . $results['hidden_id'];
                     }
		    $download_link = ($results['download_link'] == 'Yes') ? '<a href="'.$download.'">'.$button.'</a>' : '';
                }
                if (!empty($file)) {
                    //echo $file = 'https://s3.amazonaws.com/momsugaraudios/WohBeeteDinYaadHain-Tanya.mp3';
                    $mysql = "SELECT domain_name FROM " .
					$prefix . "amazon_cloud_front WHERE buket_id='" .
					$results['bucket_id'] . "'";
                    $rslt_cf = $db->get_a_line($mysql);
                    $mediaName = explode('.', $results['content_id']);
                    if ($rs_settings['cloud_fornt'] != 1) {
                        if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"])){
                            $str=" <audio id= 'player$results[id]'><source src=\"$file\" type=\"audio/mpeg\" /></audio>";
                            $str.='<div class="download">'.$download_link.'</div>';
                        } 
                        else{
                            $str ='<a 
                            id="player'.$results[id].'" 
                            class="player'.$results[id].'" 
                            href="' . $file . '"
                            style="display:block;width:'.$results['player_width']."px;height:".$results['player_height'].'px;">
                            </a>
                            <script src="http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js"></script>
                            '
                                    ;	
                            $str.="<script type=\"text/javascript\">	
                            flowplayer('a.player$results[id]', 'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', {
                                        key:'77cfdec6a8b2ac9cb19',
                                        clip: {autoPlay: " . $autostart . ",},                                   
                                        plugins: {
                                            audio: {
                                             url: 'http://www.rapidresidualpro.com/flowplayer/flowplayer.audio-3.2.2.swf',
                                            },
                                            " . $player_controls . "
                                               }
                                   });</script>";
                           $str.='<div class="download">'.$download_link.'</div>';
                        }	
	                     
                          
                    } elseif (!empty($rslt_cf['domain_name'])) {
        		if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"])){
                         $str=" <audio id= 'player$results[id]'><source src=\"$file\" type=\"audio/mpeg\" /></audio>";
			 $str.='<div class="download">'.$download_link.'</div>';
			}
                        else{
                            $str = '<a 
                            id="player'.$results[id].'" 
                            class="player'.$results[id].'" 
                            href="mp3:' . $mediaName[0] . '" 
                            style="display:block;width:'.$results['player_width']."px;height:".$results['player_height'].'px;">
                            </a>
                            <script src="http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js"></script>
                         
                            <script type="text/javascript">';	
                            $str.="
                            flowplayer('a.player$results[id]', 'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', {
                            key:'77cfdec6a8b2ac9cb19',
                            clip: {
                                provider: 'rtmp',
                                autoPlay: " . $autostart . ",
                                autoBuffering: false,
                            },
                            plugins: {
                                rtmp: {
                                url: 'http://www.rapidresidualpro.com/flowplayer/flowplayer.rtmp-3.2.3.swf',
                                netConnectionUrl: 'rtmp://" . $rslt_cf['domain_name'] . "/cfx/st',
                                durationFunc: 'getStreamLength'
                            },
                            audio: {
                            url: 'http://www.rapidresidualpro.com/flowplayer/flowplayer.audio-3.2.2.swf',
                            },
                           " . $player_controls . "
                            }
                             });";
                            $str.='</script>';
                            $str.='<div class="download">'.$download_link.'</div>';
                            }
                    } 
                    else {// IF Cloud Front Stream is not available then load the Normal S3 Contents
                        if (preg_match("/iP(od|hone|ad)/i", $_SERVER["HTTP_USER_AGENT"])){
                           
                            $str=" <audio id= 'player$results[id]'><source src=\"$file\" type=\"audio/mpeg\" /></audio>";
                            $str.='<div class="download">'.$download_link.'</div>'; 
                        } 
                        else{
                            $str = '<a 
                            id="player'.$results['id'].'" 
                            class="player'.$results['id'].'" 
                            href="' . $file . '" style="display:block;width:' . $results['player_width'] . "px;height:" . $results['player_height'] . 'px;">
                            </a>
                            <script src="http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js"></script>
                            
                            ';		
                            $str.="<script type=\"text/javascript\">
                            flowplayer('a.player$results[id]', 'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', {
                            key:'77cfdec6a8b2ac9cb19',
                                clip: {
                                autoPlay: " . $autostart . ",
                                },                                   
                                plugins: {
                                audio: {
                                url: 'http://www.rapidresidualpro.com/flowplayer/flowplayer.audio-3.2.2.swf',
                                },
                                " . $player_controls . "
                                }
                            });
                            </script>";
                            $str.='<div class="download">'.$download_link.'</div>';
                            }	
                          }
                       }
                    }
                  else   $str = '';
                 }
            return $str;
        }
    /*     * ****************************************************************** */
    function getTextBetweenTags($string) {
        $pattern = "/{{(.*?)}}/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }
    //=====================================================================================
    /*     * **************************************************************************************************** */
    function set_cookie_coupon($db, $prefix, $coupon_code) {
        $sql = "SELECT p.id,p.pshort,c.expire_date FROM " . $prefix . "coupon_codes c," . $prefix . "products p where c.prod=p.pshort and c.couponcode ='$coupon_code'; ";
        $row = $db->get_a_line($sql);
        $value = $coupon_code;
        $expiry_date = strtotime($row[expire_date]);
		
        if(!setcookie('coupon-' . $row['pshort'], $value, $expiry_date, '/'))
		   return -4;
		else
			return 1;   
    }
//-------------------------------------------------------------------------------------------------------
    function set_cookie_affiliate($db, $prefix, $affiliate_name, $coupon_code) {
         $sql = "SELECT p.id,p.pshort,p.otocheck,p.one_time_offer,c.expire_date FROM " . $prefix . "coupon_codes c," . $prefix . "products p where c.prod=p.pshort and c.couponcode ='$coupon_code'; ";
        $row = $db->get_a_line($sql);
        $expiry_date = $this->get_Affiliate_Cookie_Expiry($db, $prefix);
	
        if ($this->Check_Cookies_mode($db, $prefix) == 'first') {
			
            if (!isset($_COOKIE['referer-' . $row['pshort']])) {
				if($row['otocheck']=='yes') 
				setcookie('referer-' . $row['one_time_offer'], $affiliate_name, time() + (3600 * 24 * $expiry_date), "/");
                setcookie('referer-' . $row['pshort'], $affiliate_name, time() + (3600 * 24 * $expiry_date), "/");
                setcookie("offsite-ref", $affiliate_name, time() + (3600 * 24 * 90), "/");
            }
        } else {
			if($row['otocheck']=='yes') 
				setcookie('referer-' . $row['one_time_offer'], $affiliate_name, time() + (3600 * 24 * $expiry_date), "/");
            setcookie('referer-' . $row['pshort'], $affiliate_name, time() + (3600 * 24 * $expiry_date), "/");
            setcookie("offsite-ref", $affiliate_name, time() + (3600 * 24 * 90), "/");
        }
        return 1;
    }
//---------------------------------------------------------------------------------------------------
    function set_refere_cookies($db, $prefix, $affiliate_name, $product_name) {
		$sql = "SELECT p.id,p.pshort,p.otocheck,p.one_time_offer FROM " . $prefix . "products p WHERE  pshort= '$product_name'; ";
        $row = $db->get_a_line($sql);
		$expiry_date = $this->get_Affiliate_Cookie_Expiry($db, $prefix);
		if($row['otocheck']=='yes') 
				setcookie('referer-' . $row['one_time_offer'], $affiliate_name, time() + (3600 * 24 * $expiry_date), "/");
        if (!setcookie('referer-' . $product_name, $affiliate_name, time() + (3600 * 24 * $expiry_date), "/"))
            die("Error to create referer cookie");
        if (!setcookie("offsite-ref", $affiliate_name, time() + (3600 * 24 * 90), "/"))
            die("Error to create referer cookie");;
    }
    /*     * ************************************************************************************************** */
    function is_coupon_valid($db, $prefix, $coupon_code) {
        $q2 = "select * from " . $prefix . "coupon_codes where couponcode='$coupon_code'";
        $v = $db->get_a_line($q2);
        $product_name = $v[prod];
        if (!empty($product_name)) {
            $current_date = date("Y-m-d H:i:s");
            $coupon_expiry = $v[expire_date];
            if (strtotime($coupon_expiry) < strtotime($current_date)) {
                return -2;
            } else {
                //setcookie("coupon_code",$coupon_code, strtotime($coupon_expiry),"/");
                return 1;
            }
        }
        else
            return -1;
    }
    function get_product($db, $prefix, $product_name) {
        $q = "select count(*) as total from " . $prefix . "products where pshort='$product_name'";
        $r = $db->get_a_line($q);
        $product_count = $r['total'];
        if ($product_count > 0)
            return 1;
        else
            return 0;
    }
    function get_Coupon_Cookie_Expire($db, $prefix, $coupon_code) {
        $q2 = "select expire_date from " . $prefix . "coupon_codes where couponcode='$coupon_code'";
        $v = $db->get_a_line($q2);
        return $expire_date = $v['expire_date'];
    }
    function get_Affiliate_Cookie_Expiry($db, $prefix) {
        $sql = "select cookie_expiry from " . $prefix . "site_settings where id='1'";
        $row = $db->get_a_line($sql);
        return $row['cookie_expiry'];
    }
    function Check_Cookies_mode($db, $prefix) {
        $qry = "select cookie_mode, cookie_expiry from " . $prefix . "site_settings where id='1'";
        $row = $db->get_a_line($qry);
        return $cookie_mode = $row['cookie_mode'];
    }
//
    function get_affiliate($db, $prefix, $afiliate_name) {
        $q = "select count(id) as total from " . $prefix . "members where username='$afiliate_name'";
        $v = $db->get_a_line($q);
        $member_value = $v[total];
        if ($member_value > 0)
            return 1;
        else
            return 0;
    }
    function is_affiliate_banned($db, $prefix, $afiliate_name) {
        $q = "select count(id) as total from " . $prefix . "members where username='$afiliate_name' and is_block=0";
        $v = $db->get_a_line($q);
        $member_value = $v[total];
        if ($member_value > 0) {
            return 0;
        } else {
            return 1;
        }
    }
    function getBrowser($user_agent) {
        if (strstr($user_agent, 'ie'))
            return 'Internet Explorer';
        if (strstr($user_agent, 'firefox'))
            return 'Firefox';
    }
    function getOS($user_agent) {
        if (strstr($user_agent, 'windows'))
            return 'Windows';
        if (strstr($user_agent, 'linux'))
            return 'Linux';
        if (strstr($user_agent, 'apple'))
            return 'Apple';
    }
    function get_real_IP_address() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    function get_site_name($db,$prefix)
    {
    	$sql = "SELECT sitename from  " . $prefix . "site_settings";
    	$rows = $db->get_a_line($sql);
    	return $rows['sitename'];
    }
    function get_social_media($db,$prefix)
    {
    	$sql = "SELECT social_media_widgets from  " . $prefix . "site_settings";
    	$rows = $db->get_a_line($sql);
    	return $rows['social_media_widgets'];
    }
	function get_my_download_text($prefix, $db)
    {
        $sql = "SELECT sidebar_my_download_text from  " . $prefix . "site_settings";
		$rows = $db->get_a_line($sql);
    	return  $rows['sidebar_my_download_text'];
    }
	function get_new_products_text($prefix, $db)
    {
    	$sql = "SELECT sidebar_new_products_text from  " . $prefix . "site_settings";
		$rows = $db->get_a_line($sql);
    	return $rows['sidebar_new_products_text'];
    }
	function get_instruction_text($prefix, $db)
    {
    	$sql = "SELECT sidebar_instruction_text  from  " . $prefix . "site_settings";
    	$rows = $db->get_a_line($sql);
    	return  $rows['sidebar_instruction_text'];
    }
}
//end class
/* * ******************************************************************************************** */
// 				E N D	 O F	C L A S S
// 			G L O B A L   F U N C T I O N  S T A R T   F O R M   H E R E 
/* * ******************************************************************************************** */
function valid_email($address) {
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}
function isValidURL($url) {
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
function checkDomain($db, $prefix, $url) {
    $sql = "select count(id) as total from " . $prefix . "license_domains where domain='$url'";
    $v = $db->get_a_line($sql);
    if ($v['total'] > 0)
        return 1;
    else
        return 0;
}
while (list($key, $value) = @each($_POST)) {
    $$key = $value;
}
while (list($key, $value) = @each($_GET)) {
    $$key = $value;
}
/* * ***************************************************************************************************************** */
function get_payment_buttons($gateway) {
    include_once ("alertpay.class.php");
    $alertpay = new AlertPay();
    $common = new common();
    global $paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url,
    $http_path, $shipping, $image, $pp_header, $pp_return, $rand, $ip, $price, $prodtype, $ref, $ttype,
    $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn;
    if (!empty($gateway)) {
        switch ($gateway) {
            case "paypal":
                $out['paypal_button'] = $common->paypalbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return, $alpertpay_return_url);
                break;
            case "alertpay":
                $out['alertpay_button'] = $alertpay->button($rand, $ip, $price, $pid, $itemname, $prodtype, $ref, $ttype, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
                break;
            default:
                $out['paypal_button'] = $common->paypalbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return, $alpertpay_return_url);
                $out['alertpay_button'] = $alertpay->button($rand, $ip, $price, $pid, $itemname, $prodtype, $ref, $ttype, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
                break;
        }
    } else {
        $out['paypal_button'] = $common->paypalbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return, $alpertpay_return_url);
        $out['alertpay_button'] = $alertpay->button($rand, $ip, $price, $pid, $itemname, $prodtype, $ref, $ttype, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
    }
    return $out;
}
function get_subscription_payment_buttons($gateway) {
    include_once ("alertpay.class.php");
    $alertpay = new AlertPay();
    $common = new common();
    global $paypath, $receiver, $itemname, $pid, $price, $rands,
    $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3,
    $period3_value, $period3_interval, $pp_header, $pp_return, $rand, $ip, $amt_owed, $pid, $ref,
    $ttype, $pcheck, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn;
    if (!empty($gateway)) {
        switch ($gateway) {
            case "paypal":
                $out['paypal_button'] = $common->paypalsubbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return);
                break;
            case "alertpay":
                $out['alertpay_button'] = $alertpay->subscription_button($rand, $ip, $pid, $itemname, $product_type, $ref, $ttype, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
                break;
            default:
                $out['paypal_button'] = $common->paypalsubbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return);
                $out['alertpay_button'] = $alertpay->subscription_button($rand, $ip, $pid, $itemname, $product_type, $ref, $ttype, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
                break;
        }
    } else {
        $out['paypal_button'] = $common->paypalsubbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return);
        $out['alertpay_button'] = $alertpay->subscription_button($rand, $ip, $pid, $itemname, $product_type, $ref, $ttype, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
    }
    return $out;
}
/* * ************************************************************************************************************************ */
//                          T H I R D   P A R T Y  B U T T O N     H A N D E L I N G
/* * ************************************************************************************************************************* */
function get_payment_hidden_buttons($gateway) {
    include_once ("alertpay.class.php");
    include_once ("common/clickbank.class.php");
    $alertpay = new AlertPay();
    $clickBank = new ClickBank();
    $common = new common();
    global $paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url,
    $http_path, $shipping, $image, $pp_header, $pp_return, $rand, $ip, $price, $prodtype, $ref, $ttype,
    $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn;
    if (!empty($gateway)) {
        switch ($gateway) {
            case "paypal":
                $buttons = $common->paypalbutton_hidden($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return, $alpertpay_return_url);
                break;
            case "alertpay":
                $buttons = $alertpay->button_hidden($rand, $ip, $price, $pid, $itemname, $prodtype, $ref, $ttype, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
                break;
            /* default:
              $out['paypal_button'] = $common->paypalbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return, $alpertpay_return_url);
              $out['alertpay_button'] = $alertpay->button($rand, $ip, $price, $pid, $itemname, $prodtype, $ref, $ttype, $alpertpay_return_url, $receiver_alertpay , $receiver_alertpay_ipn);
              break; */
        }
    } else {
        $buttons = $common->paypalbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $pp_header, $pp_return, $alpertpay_return_url);
        // $out['alertpay_button'] = $alertpay->button($rand, $ip, $price, $pid, $itemname, $prodtype, $ref, $ttype, $alpertpay_return_url, $receiver_alertpay , $receiver_alertpay_ipn);
    }
    return $buttons;
}
function get_subscription_payment_hidden_buttons($gateway) {
    include_once ("alertpay.class.php");
    $alertpay = new AlertPay();
    $common = new common();
    global $paypath, $receiver, $itemname, $pid, $price, $rands,
    $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3,
    $period3_value, $period3_interval, $pp_header, $pp_return, $rand, $ip, $amt_owed, $pid, $ref,
    $ttype, $pcheck, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn;
    if (!empty($gateway)) {
        switch ($gateway) {
            case "paypal":
                $buttons = $common->paypalsubbutton_hidden($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return);
                break;
            case "alertpay":
                $buttons = $alertpay->subscription_button_hidden($rand, $ip, $pid, $itemname, $product_type, $ref, $ttype, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay, $receiver_alertpay_ipn);
                break;
            /* default:
              $out['paypal_button'] = $common->paypalsubbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return);
              $out['alertpay_button'] = $alertpay->subscription_button($rand, $ip, $pid, $itemname, $product_type, $ref, $ttype, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay , $receiver_alertpay_ipn);
              break; */
        }
    } else {
        //$out['paypal_button'] = $common->paypalsubbutton($paypath, $receiver, $itemname, $pid, $price, $rands, $return_url, $notify_url, $http_path, $shipping, $image, $recurr, $trial1, $trial2, $amount3, $period3_value, $period3_interval, $pp_header, $pp_return);
        // $out['alertpay_button'] = $alertpay->subscription_button($rand, $ip, $pid, $itemname, $product_type, $ref, $ttype, $_billing_cycle, $_trial, $alpertpay_return_url, $receiver_alertpay , $receiver_alertpay_ipn);
    }
    return $buttons;
}
?>