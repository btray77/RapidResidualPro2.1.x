<?php session_start();
class Session {
	
    function verifylogin($db, $common, $dUser, $dPass) {
	
        include("../common/config.php");
        $dUser = stripslashes(trim($dUser));
        $dPass = stripslashes(trim($dPass));
		$_SESSION['password']=$dPass;
        $dPass = md5("$dPass");
        $mysql = "select count(*) as cnt from " . $prefix . "members where username='$dUser' and password='$dPass'";
        $r = $db->get_a_line($mysql);
        $country = addslashes(trim($_POST["country"]));
        $city = addslashes(trim($_POST["city"]));
        $latitude = addslashes(trim($_POST["latitude"]));
        $longitude = addslashes(trim($_POST["longitude"]));
        $operating_system = addslashes(trim($_POST["operating_system"]));
        $browser = addslashes(trim($_POST["browser"]));
        $destination = trim($_POST["destination"]);
        //Temporarily Block User Account
        $temp_block = $this->confirmIPAddress(get_real_IP_address(), $dUser, $db);
        if ($temp_block == '1') {
            header("location: login.php?err=temp_block&destination=$destination");
            exit;
        }
        if ($r[cnt] > 0) {// valid entry
            $product = $_REQUEST['product'];
            $coupon = $_REQUEST['coupon'];
            $mysql = "select * from " . $prefix . "members where username={$db->quote($dUser)} and password={$db->quote($dPass)}";
            $rslt = $db->get_a_line($mysql);
            if ($rslt['is_block'] == 1) {
                header("Location: login.php?err=blocked");
                exit;
            }
            $username = $rslt["username"];
            $password = $rslt["password"];
            $member_id = $rslt["id"];
            $hash = $common->hashgen();
            setcookie("memcookie", $hash, 0, "/");
            setcookie("memberid", $member_id, 0, "/");
            $time = time();
            $q = "select count(*) as cnt from " . $prefix . "member_session where member_id='$member_id'";
            $r = $db->get_a_line($q);
            if ($r[cnt] > 0) {
                $mysql = "update " . $prefix . "member_session set hash='$hash', time='$time', `country` = '{$country}', 
        					`city` = '{$city}', `latitude` = '{$latitude}', `longitude` = '{$longitude}', 
						`operating_system` = '{$operating_system}', `browser` = '{$browser}'
						where member_id='$member_id'
                				";

                $db->insert($mysql);
            } else {
                $mysql = "insert into " . $prefix . "member_session set member_id='$member_id', hash='$hash', time='$time', 
								`country` = '{$country}', `city` = '{$city}', `latitude` = '{$latitude}', `longitude` = '{$longitude}', 
								`operating_system` = '{$operating_system}', `browser` = '{$browser}'  ";
                $db->insert($mysql);
            }
            //Check for geo Location Change
            $geoTrack = $this->checkGeoLocation($member_id, $city, $country, $db);
            $ip = get_real_IP_address();
            $mysql = "insert into " . $prefix . "member_session_archive set member_id='$member_id', hash='$hash', time='$time', 
                            `country` = '{$country}', `city` = '{$city}', `latitude` = '{$latitude}', `longitude` = '{$longitude}', ip = '{$ip}',
                            `operating_system` = '{$operating_system}', `browser` = '{$browser}'  ";
            $db->insert($mysql);
            if (empty($destination))
                $destination = 'index.php';
            header("location: $destination");
            exit;
        }
        else {
            //Add Invalid Login Attempt ot DB
            $myin = "select count(*) as cnt from " . $prefix . "members where username='$dUser'";
            $rin = $db->get_a_line($myin);
            if ($rin['cnt'] > 0) {
                $this->addLoginAttempt(get_real_IP_address(), $dUser, $db);
            }
            header("location: login.php?err=inv&destination=$destination");
            exit;
        }
    }
    
    function confirmIPAddress($ip, $username, $db) {
        //After 5 Min account will activiated again
        include("../common/config.php");
        $q = "SELECT attempts, (CASE when time is not NULL and DATE_ADD(time, INTERVAL 5 MINUTE)>NOW() then 1 else 0 end) as Denied FROM " . $prefix . "login_attempts WHERE ip = '$ip' and username ='$username'";
        $data = $db->get_a_line($q);
        //Verify that at least one login attempt is in database
        if (!$data) {
            return 0;
        }
        if ($data["attempts"] >= 3) {
            if ($data["Denied"] == 1) {
                return 1;
            } else {
                $this->clearLoginAttempts($ip, $username, $db);
                return 0;
            }
        }
        return 0;
    }

    function addLoginAttempt($ip, $username, $db) {
        //Increase number of attempts. Set last login attempt if required.
        include("../common/config.php");
        $q = "SELECT * FROM " . $prefix . "login_attempts WHERE username='$username' AND ip='$ip'";
        $data = $db->get_a_line($q);
        if ($data) {
            $attempts = $data['attempts'] + 1;
            if ($attempts == 3) {
                $q = "UPDATE " . $prefix . "login_attempts SET  attempts='$attempts', time=NOW() WHERE username='$username' AND ip='$ip'";
                $db->insert($q);
            } else {
                $q = "UPDATE " . $prefix . "login_attempts SET  attempts='$attempts' WHERE username='$username' AND ip='$ip'";
                $db->insert($q);
            }
        } else {
            $q = "INSERT INTO " . $prefix . "login_attempts SET username='$username', ip='$ip', attempts='1',time=NOW()";
            $db->insert($q);
        }
    }

    function clearLoginAttempts($ip, $username, $db) {
        include("../common/config.php");
        $q = "UPDATE " . $prefix . "login_attempts SET attempts = 0 WHERE ip = '$ip' AND username='$username'";
        $db->insert($q);
    }

    function checkGeoLocation($member_id, $city, $country, $db) {
        include_once("include.php");
        include("../common/config.php");
        //include("../common/common.class.php");
        $common = new common();
        $q = "SELECT ip,country,city,time FROM " . $prefix . "member_session_archive WHERE member_id='$member_id' ORDER BY time DESC LIMIT 1";
        $r = $db->get_a_line($q);
        $mytime = $r['time'] + 120;  //APPOX 2 Seconds
        $changeTime = $r['time'];
        if ($r['country'] != $country || $r['city'] != $city) {
            if ($mytime > time()) {// && $r['country'] != $country && $r['city'] != $city
                $rs = $db->get_single_column("select time from " . $prefix . "member_session where member_id='$member_id'");
                $db->insert("update " . $prefix . "members set last_login='" . $rs['time'] . "' where id='$member_id'");
                $mysql = "delete from " . $prefix . "member_session where member_id='$member_id'";
                $db->insert($mysql);
                //Block the user
                $query = "UPDATE `{$prefix}members` SET is_block='1', report_abuse='1' WHERE id=" . (int) $member_id;
                mysql_query($query) or die(mysql_error());
                //Send Email to Admin regarding Abuse
                $rsMember = $db->get_a_line("SELECT firstname,lastname,email,username FROM " . $prefix . "members WHERE id='$member_id'");
                //Get Latest 10 Activities
                $rsActivities = $db->get_rsltset("SELECT country,city,ip,time FROM " . $prefix . "member_session_archive WHERE member_id='$member_id' ORDER BY time DESC LIMIT 50");
                $activityOutput = "";
                foreach ($rsActivities as $activity) {
                    $activityOutput .= '<tr>
										<td>' . $activity['country'] . '</td>
										<td>' . $activity['city'] . '</td>
										<td>' . $activity['ip'] . '</td>
										<td>' . date('Y:m:d H:i:s', $activity['time']) . '</td>		
										</tr>';
                }
                //Get Admin Email List
                $rsAdminEmail = $db->get_rsltset("SELECT webmaster_email,username FROM " . $prefix . "admin_settings WHERE role='1'");
                foreach ($rsAdminEmail as $fromEmail) {
                    $subject = "ALERT: You may want to check this users login information...";
                    $email = $fromEmail['webmaster_email'];
                    $message = 'Hello ' . ucfirst($fromEmail['username']) . ':<br /><br />
                                        This is a site security alert notice to let you know that it appears one of your members may be sharing his/her account login with others or perhaps an attempt to hack into their account has happened.<br/>
                                        Log into your site\'s admin area here: ' . $http_path . '/admin/, review this members login information and take the appropriate action (suspend the account, contact the member, etc.).
                                        <b>First Name:</b> &nbsp;&nbsp;&nbsp;&nbsp;' . $rsMember['firstname'] . '&nbsp;&nbsp;&nbsp;&nbsp;<b>Last Name:</b> &nbsp;&nbsp;&nbsp;&nbsp;' . $rsMember['lastname'] . '<br/>
                                        <b>UserName:</b> &nbsp;&nbsp;&nbsp;&nbsp;' . $rsMember['username'] . '&nbsp;&nbsp;&nbsp;&nbsp;<b>Email:</b> &nbsp;&nbsp;&nbsp;&nbsp;' . $rsMember['email'] . '<br/>
                                        <b>User\'s Latest Activities</b><br/>
                                        <table width=\"500\" style="text-align:center;" cellspacing="0">
                                         <tr style="font-weight:bold;background-color:#FC0;color:#FFF;height:30px">
                                             <td>Country</td>
                                             <td>City</td>
                                             <td>IP Address</td>
                                             <td>Time</td>
                                         </tr>
                                         ' . $activityOutput . '
                                       </table>
                                        <p>Thank you for using Rapid Residual Pro...<br />
                                          Automated Site System Security
                                            </p>';
                    $common->sendemail('', '', $email, $subject, $message, '');
                }
                setcookie($member_id, "", time() - 3600);
                header("location: login.php?err=geo_conflict&destination=$destination");
                exit;
            }
        }
    }

}

$obj = new Session;
$ip = get_real_IP_address();
if(isset($_SESSION['mrand']))
	$mrand = $_SESSION['mrand'];

if ($mrand) {
    $country = addslashes(trim($_POST["country"]));
    $city = addslashes(trim($_POST["city"]));
    $latitude = addslashes(trim($_POST["latitude"]));
    $longitude = addslashes(trim($_POST["longitude"]));
    $operating_system = addslashes(trim($_POST["operating_system"]));
    $browser = addslashes(trim($_POST["browser"]));
    $mysql = "select * from " . $prefix . "members where randomstring='$mrand'";
    $rslt = $db->get_a_line($mysql);
    $username = $rslt["username"];
    $password = $rslt["password"];
    $memberid = $rslt["id"];
    $hash = $common->hashgen();
    setcookie("memcookie", $hash, 0, "/");
    setcookie("memberid", $member_id, 0, "/");
    $time = time();
    $q = "select count(*) as cnt from " . $prefix . "member_session where member_id='$memberid'";
    $r = $db->get_a_line($q);
    if ($r[cnt] > 0) {
        $mysql = "update " . $prefix . "member_session set hash='$hash', time='$time' where member_id='$memberid'";
        $db->insert($mysql);
    } else {
        $mysql = "insert into " . $prefix . "member_session set member_id='$memberid', hash='$hash', time='$time'";
        $db->insert($mysql);
    }

    $mysql = "insert into " . $prefix . "member_session_archive set hash='$hash', time='$time', `country` = '{$country}', 
        `city` = '{$city}', `latitude` = '{$latitude}', `longitude` = '{$longitude}', 
        `operating_system` = '{$operating_system}', `browser` = '{$browser}',ip = '$ip',member_id='$memberid' ";
    $db->insert($mysql);
    ?>
    <html>
        <body>
            <form name="logform" action='index.php' method="post">
                <input type="hidden" name="pshort" value='<?php echo $_POST['pshort']; ?>'>
                <input name="country" id="country" value="<?php echo $_POST['country']; ?>"  type="hidden"  />
                <input name="city" value="<?php echo $_POST['city']; ?>" id="city"  type="hidden"  />
                <input name="latitude" value="<?php echo $_POST['latitude']; ?>" id="latitude" type="hidden"  />
                <input name="longitude" value="<?php echo $_POST['longitude']; ?>" id="longitude"  type="hidden"  />
                <input name="operating_system" value="<?php echo $_POST['os']; ?>"  type="hidden"  />
                <input name="browser" value="<?php echo $_POST['browser']; ?>"  type="hidden"  />
            </form>
            <script type="text/javascript">
                document.forms["logform"].submit();
            </script>		
        </body>
    </html>	
    <?php
} elseif ($submit == "Login") {
    $admin_id = $obj->verifylogin($db, $common, $dUser, $dPass, $destination);
} else {
    $hash = $_COOKIE["memcookie"];
    $memberid = $common->check_session($hash, $db);
    $_SESSION['memberid'] = $memberid;
    if ($memberid == "") {
        if (empty($_SERVER['HTTP_REFERER']))
            $destination = str_replace("/member/", "", $_SERVER['REQUEST_URI']);
        else
            $destination = str_replace("$http_path/member/", "", $_SERVER['HTTP_REFERER']);
        header("Location: login.php?destination=$destination");
        exit;
    }
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

$url = "http://{$_SERVER[SERVER_NAME]}{$_SERVER[REQUEST_URI]}";
$url .= ( $_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
$query = "INSERT INTO `{$prefix}member_navigation` SET member_id = '{$memberid}', url = '{$url}', hash = '{$_COOKIE[memcookie]}'";
mysql_query($query) or die(mysql_error());
?>
