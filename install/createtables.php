<?php session_start();
        include_once("../common/config.php");
        include_once("../common/database.class.php");
        $db = new database;
        $today = date("Y-m-d");
        $ip = $_SERVER['REMOTE_ADDR'];


        //////////////////////////////////////////////////////////////////////////////////////////////
        // Fetch the content of SQL file and Dump in Database


        // Temporary variable, used to store current query
        $filename = 'sql_dump.sql';
		if(is_file( $filename)){
		
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line_num => $line) {
        // Only continue if it's not a comment
            if (substr($line, 0, 2) != '--' && $line != '') {
        // Add this line to the current segment
                $templine .= $line;
        // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';') {
        // Perform the query
                     $db->insert($templine);
        // Reset temp variable to empty
                    $templine = '';
                }
            }
        }
		}
		else
		{
			die('Sorry Unable to get SQL file. Please check you install directory');
		}

        // Insert into admin_settings table
		$prot_down			= $_SESSION['prot_down'];
		$swf_down			= $_SESSION['swf_down']; 
		
        $q = "Update ".$prefix."admin_settings set id='1', username='$admin_login',password='$admin_pass', webmaster_email='$admin_email',status='1',role ='1'";
        $db->insert($q);
	
		// Insert into site_settings table
		 $q = "	update ".$prefix."site_settings set email_from_name='$admin_email',license_key='$license_key',paypal_email='$paypal_email' ,prot_down='$prot_down',swf_down ='$swf_down' ;";
		$db->insert($q);
		
		$time = time();	
		$date_joined =date('Y-m-d');
		$randomstring = md5(uniqid(rand(),1));
		$q = "	update ".$prefix."members set email ='$admin_email',username='$admin_login',password='$admin_pass' ,date_joined ='$date_joined',last_login='$time',randomstring ='$randomstring' ;";
		$db->insert($q);


?>