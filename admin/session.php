<?php
// admin session
include($_SERVER['DOCUMENT_ROOT'] . "/common/config.php");
include($_SERVER['DOCUMENT_ROOT'] . "/common/database.class.php");
include($_SERVER['DOCUMENT_ROOT'] . "/common/common.class.php");
$db = new database;
$common = new common;
$path = $common->path;

class Session {

    function verifylogin($db, $common, $fUser, $fPass, $destination) {
        // verify key for pirated version
    
       
        //end of verification
        include($_SERVER['DOCUMENT_ROOT'] . "/common/config.php");
        $fPass = md5("$fPass");
        $mysql = "select count(*) as cnt,`status` from " . $prefix . "admin_settings where username='$fUser' and password='$fPass'";

        $r = $db->get_a_line($mysql);

        if ($r[cnt] > 0 && $r['status'] == 1) {
            // valid entry
            $mysql = "select * from " . $prefix . "admin_settings where username='$fUser' and password='$fPass'";
            $rslt = $db->get_a_line($mysql);
            $username = $rslt["username"];
            $admin_id = $rslt["id"];

            $q = "select * from " . $prefix . "admin_session where admin_id='$admin_id'";
            $r = $db->get_a_line($q);
            if ($r[cnt] > 0) {
                $last_export = $r[last_export];
            } else {
                $last_export = 0;
            }

            $hash = $common->hashgen();
            if(!setcookie("admin", $hash, 0, "/")) 
			die('Sorry Unable to create admin cookies. Please contact server administrator.');
            $time = time();

            $q = "select count(*) as cnt from " . $prefix . "admin_session where admin_id='$admin_id'";
            $r = $db->get_a_line($q);
            if ($r[cnt] > 0) {
                // Update existing session data
                $mysql = "update " . $prefix . "admin_session set hash='$hash', timestamp='$time', last_export='$last_export' where admin_id='$admin_id'";
                $db->insert($mysql);
            } else {
                // Create new session data
                $mysql = "insert into " . $prefix . "admin_session values ('$admin_id','$hash','$time','$last_export')";
                $db->insert($mysql);
            }
            return $admin_id;
        } else if ($r[cnt] > 0 && $r['status'] == 0) {
            header("Location:/admin/index.php?err=blocked&destination=$destination");
            exit;
        } else {
            // Invalid login attempt
            header("Location:/admin/index.php?err=inv&destination=$destination");
            exit;
        }
    }

}



$obj = new Session;
    if ($submit == "Login") {
        $admin_id = $obj->verifylogin($db, $common, $fUser, $fPass, $destination);
    } else {
        $hash = $_COOKIE["admin"];
        $admin_id = $common->check_admin_session($hash, $db);
        if ($admin_id == "") {

            if (empty($_SERVER['HTTP_REFERER']))
                $ref = str_replace("/admin/", "", $_SERVER['REQUEST_URI']);
            else
                $ref = str_replace("$http_path/admin/", "", $_SERVER['HTTP_REFERER']);

            header("Location:/admin/index.php?err=ses&destination=$ref");
            exit;
        }
    }

?>