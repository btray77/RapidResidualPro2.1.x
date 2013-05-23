<?php

    if (!class_exists('S3')) require_once 's3class/S3.php';
   // Instantiate the class
    $s3 = new S3(awsAccessKey, awsSecretKey);

    if (!class_exists('upload-class')) require_once 's3class/upload-class.php';
if (!class_exists('S3')) require_once 's3class/S3.php';
   // Instantiate the class
    $s3 = new S3(awsAccessKey, awsSecretKey);

if (!class_exists('upload-class')) 
	require_once 's3class/upload-class.php';
     
require_once 'amazon-sdk/sdk-1.3.3/sdk.class.php';

// Disable SSL But its Not Recommended
/*$sdb = new AmazonSDB($setting_array['aws_access_key'],$setting_array['aws_secret_key']);
$sdb->disable_ssl(true);*/



?>