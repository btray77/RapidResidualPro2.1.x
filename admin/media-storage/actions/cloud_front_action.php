<?php

ob_start();
include_once '../../session.php';
require_once '../config/config.php';
require_once '../common.php';


if (isset($_POST['create_dis'])) {
    $bucket_new = preg_replace('/[^a-z0-9]+/i','-',iconv('UTF-8','ASCII//TRANSLIT',$_POST['bucket_new']));
    $bucket_new = strtolower(mysql_escape_string($bucket_new));
    // Create a Distribution
    // Check wehter create a distribution from existing buckets or to create new bucket.
    //CNAMES
    $cnames = array();
    $cnames = explode(',', mysql_escape_string($_POST['cnames']));
    if (empty($cnames)) {

        $cnames = '';
    } else {

        $cnames = "'CNAME' => $cnames";
    }

    // Type of Contents
    /*if($_POST['type'] == "true"){
            $chk_stream = "'Streaming' => true";
    }else{
            $chk_stream = "";
    }*/


    if (mysql_escape_string($_POST['bucket_name']) != "") {

        // Create a new CloudFront distribution from an S3 bucket.
        $cdn = new AmazonCloudFront($setting_array['aws_access_key'], $setting_array['aws_secret_key']);


        // Type of Contents
        if($_POST['type'] == "true"){
                $response = $cdn->create_distribution(mysql_escape_string(strtolower($_POST['bucket_name'])), 'rrp_Stream_' . time(), array(
                    'Enabled' => true,
                    'Comment' => 'RapidResidualPro Contents',
                    $cnames,
                    'Logging' => array(
                        'Bucket' => mysql_escape_string(strtolower($_POST['bucket_name'])) . '-log',
                        'Prefix' => 'log_'
                    ),
                    'Streaming' => true,
                ));
        }else{
                $response = $cdn->create_distribution(mysql_escape_string(strtolower($_POST['bucket_name'])), 'rrp_Stream_' . time(), array(
                    'Enabled' => true,
                    'Comment' => 'RapidResidualPro Contents',
                    $cnames,
                    'Logging' => array(
                        'Bucket' => mysql_escape_string(strtolower($_POST['bucket_name'])) . '-log',
                        'Prefix' => 'log_'
                    ),

                ));
        }
        
        

        if ($response->isOK()) {
            $mysql = "insert into " . $prefix . "amazon_cloud_front SET distribution_id='" . $response->body->Id . "',
                        buket_id='" . mysql_escape_string($_POST['bucket_name']) . "',domain_name='" . $response->body->DomainName . "'";

            $db->insert($mysql);

            header("Location: ../view_distributions.php?msg=distrb_success");
        } else {
            header("Location: ../cloud_front.php?error=request_fail");
        }

        //Create New Bucket and Add to Cloud Front
    } elseif ($bucket_new != "") {

        if (S3::getBucket($bucket_new)!== false){

        if (S3::putBucket($bucket_new, S3::ACL_PRIVATE)) {

            // Create a new CloudFront distribution from an S3 bucket.
            $cdn = new AmazonCloudFront($setting_array['aws_access_key'], $setting_array['aws_secret_key']);

            $response = $cdn->create_distribution($bucket_new, 'rrp_Stream_' . time(), array(
                        'Enabled' => true,
                        'Comment' => 'RapidResidualPro Contents',
                        $cnames,
                        'Logging' => array(
                            'Bucket' => $bucket_new . '-log',
                            'Prefix' => 'log_'
                        ),
                        $chk_stream,
                    ));
            if ($response->isOK()) {

                $mysql = "insert into " . $prefix . "amazon_cloud_front SET distribution_id='" . $response->body->Id . "',
                            buket_id='" . $bucket_new . "',domain_name='" . $response->body->DomainName . "'";

                $db->insert($mysql);


                header("Location: ../view_distributions.php?msg=distrb_success");
            } else {
                header("Location: ../cloud_front.php?error=request_fail");
                //echo 'Error'.$response->body->Error->Message;
            }
            }else {

                header("Location: ../cloud_front.php?error=request_fail");
            }
        } else {

            header("Location: ../cloud_front.php?error=request_fail");
        }
    } else {
        header("Location: ../cloud_front.php?error=request_fail");
    }
} elseif (isset($_POST['delete'])) {

    foreach ($_POST['delete'] as $id) {

        //Set Bucket Configuration
        $cdn = new AmazonCloudFront($setting_array['aws_access_key'], $setting_array['aws_secret_key']);

        // Pull streaming distributions
        $streaming_distributions = $cdn->get_streaming_distribution_list();

        // Pull existing config XML...
        if (in_array($id, $streaming_distributions)) {

            $existing_xml = $cdn->get_distribution_config($id, array(
                        'Streaming' => true,
                    ));
            $streaming = true;//die('Stream');
        } else {

            $existing_xml = $cdn->get_distribution_config($id);
        }



        // Was the request successful?
        if ($existing_xml->isOK()) {
            
            
                $deleteDis = $cdn->delete_distribution((string)trim($id), (string)trim($existing_xml->header['etag']),
                        array('Streaming' => true,)
                        );
                if ($deleteDis->isOK()) {

                    $mysql = "DELETE FROM ".$prefix."amazon_cloud_front WHERE distribution_id='".$id."'";
                    $db->insert($mysql);

                    header("Location: ../view_distributions.php?msg=delete_distrb");
                } else {
                   
                    header("Location: ../view_distributions.php?error=request_fail");
                }
            }
        }
    //}
} elseif (mysql_escape_string($_GET['do'] != '')) {

    if (mysql_escape_string($_GET['do'] != 'disable')) {
        $status = "Enabled' => false";
    } elseif (mysql_escape_string($_GET['do'] != 'enable')) {
        $status = "Enabled' => true";
    } else {
        header("Location: ../view_distributions.php?error=access_denied");
    }

    //Set Bucket Configuration
    $cdn = new AmazonCloudFront($setting_array['aws_access_key'], $setting_array['aws_secret_key']);
    $id = mysql_escape_string($_GET['id']);

    // Pull streaming distributions
    $streaming_distributions = $cdn->get_streaming_distribution_list();

    // Pull existing config XML...
    if (in_array($id, $streaming_distributions)) {

        $existing_xml = $cdn->get_distribution_config($id, array(
                    'Streaming' => true
                ));
        $streaming = true;
    } else {

        $existing_xml = $cdn->get_distribution_config($id);
    }


    // Was the request successful?
    if ($existing_xml->isOK()) {
        // Generate an updated XML config...
        if (in_array($id, $streaming_distributions)) {

            $updated_xml = $cdn->update_config_xml($existing_xml, array(
                        $status,
                        'Streaming' => true
                    ));
            $streaming = true;
        } else {

            $updated_xml = $cdn->update_config_xml($existing_xml, array(
                        $status,
                    ));
        }


        // Fetch an updated ETag value
        $etag = $existing_xml->header['etag'];

        // Set the updated config XML to the distribution.
        if (in_array($id, $streaming_distributions)) {

            $response = $cdn->set_distribution_config($id, $updated_xml, $etag, array(
                        'Streaming' => true
                    ));
            $streaming = true;
        } else {

            $response = $cdn->set_distribution_config($id, $updated_xml, $etag);
        }

        if ($response->isOK()) {           

            if ($status == 'enable') {
                header("Location: ../view_distributions.php?msg=distrb_disable");
            } else {
                header("Location: ../view_distributions.php?msg=distrb_enable");
            }
        } else {
            header("Location: ../view_distributions.php?error=request_fail");
        }
    }
} else {

    header("Location: ../view_distributions.php?error=access_denied");
}
?>