<?php

/**
    Amazon Bucket Content Actions
**/
//ini_set('max_upload_filesize', 20000000);
//ini_set('post_max_size', 20000000);
//ini_set('memory_limit', 20000000);
ob_start();

include_once '../../session.php';
require_once '../config/config.php';
require_once '../common.php';
require_once $_SERVER['DOCUMENT_ROOT']."/admin/class-privileges.php";

error_reporting(E_ERROR);
                        

       if(isset($_POST['upload'])){ // Add Contents Actions
               
                // Upload contents to bucket
                    extract($_POST);
                    if(!empty($_FILES['s3file']['name'])){
                        $fileName = preg_replace('/[^a-zA-Z0-9-.]/i', '-', $_FILES['s3file']['name']);
                    }elseif(!empty($link_id)){
                        $fileName = preg_replace('/[^a-zA-Z0-9-.]/i', '-', $link_id);
                    }
                    $fileTempName = $_FILES['s3file']['tmp_name'];

               //Get File extension to check, its allowed or not
                    $filext = substr($fileName, strrpos($fileName, '.')+1);

                    if(in_array($filext, $allowed_extensions)){

                        if($_POST['upload-location'] == 's3'){ // If user choose to store contents on Amazon S3

                            if(!empty($_FILES['s3file']['name'])){
                                $newFileName = trim(time().$fileName);
                            }elseif(!empty($link_id)){
                                $newFileName = $link_id;
                            }

                         

                            // Put Object in S3 bucket.
                            $cdn = new AmazonS3($setting_array['aws_access_key'], $setting_array['aws_secret_key']);

                            if($_POST['content-type'] == 'video'){

                                $cont_type = "'contentType' => 'video/.$filext.'";
                            }elseif($_POST['content-type'] == 'video'){
                                $cont_type = "'contentType' => 'audio/.$filext.'";
                            }elseif($_POST['content-type'] == 'file'){
                                $cont_type = "'contentType' => 'binary/.$filext.'";
                            }

                            // Initiate a new multipart upload
                          /*  $response = $cdn->initiate_multipart_upload(trim($_POST['bucket_name']), $newFileName, array(
                                $cont_type,
                                'acl' => AmazonS3::ACL_PUBLIC,
                                'storage' => AmazonS3::STORAGE_STANDARD,
                                'meta' => array(
                                    'resolution' => '720p',
                                    'rating' => 'US PG-13',
                                    'runtime' => '00:30:00'
                                )
                            ));
                            
                            
                            // Get the Upload ID
                            $upload_id = (string) $response->body->UploadId;

                           
                            $parts = $cdn->get_multipart_counts(filesize('movie.mp4'), 5*MB);
                            
                            
                            // Queue batch requests
                            foreach ($parts as $i => $part)
                            {
                                $cdn->batch()->upload_part(trim($_POST['bucket_name']), $newFileName, $upload_id, array(
                                    'expect' => '100-continue',
                                    'fileUpload' => 'movie.mp4',
                                    'partNumber' => ($i + 1),
                                    'seekTo' => (integer) $part['seekTo'],
                                    'length' => (integer) $part['length'],
                                ));
                            }

                            // Send batch requests
                            $batch_responses = $cdn->batch()->send();

                            // Success?
                            //if($batch_responses->areOK()){
                            */


                            /** Want to Attach the file or Upload New one **/

                            if($_POST['sel_file'] == 'link'){ // Link Exisiting File

                                   $newFileNameArray = array();

                                    foreach ($_FILES as $btnFiles){
                                        $old_name = $btnFiles['name'];
                                        if(!empty($btnFiles['name'])){
                                            $upload = new Upload();
                                            $upload->SetFileName($btnFiles['name']);
                                            $upload->SetTempName($btnFiles['tmp_name']);
                                            $upload->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.
                                            $upload->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                            $upload->UploadFile();
                                            $newFileNameArray["$old_name"] = $upload->GetFileName();

                                       }
                                    }


                                    $new_shortname = strtolower($short_name);
                                    $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                    $token = preg_replace('/[^a-zA-Z0-9]/i', '', $new_shortname);

                                    $paypal = $_FILES['paypal_button']['name'];
                                    $alertpay = $_FILES['alertpay_button']['name'];
                                    $google = $_FILES['google_checkout_button']['name'];
                                    $clickbank = $_FILES['clickbank_button']['name'];
                                    $download = $_FILES['download_button']['name'];

                                    if(empty($player_height) && $_POST['content-type']=='video')
                                            $player_height='300';
                                    if(empty($player_width) && $_POST['content-type']=='video')
                                            $player_width='400';

                                    if(empty($player_height) && $_POST['content-type']=='audio')
                                            $player_height='24';
                                    if(empty($player_width) && $_POST['content-type']=='audio')
                                            $player_width='300';

                                    $c_size = $s3->getObjectInfo(mysql_escape_string(trim($_POST['bucket_name'])),$newFileName);


                                  $query = "INSERT INTO ".$prefix."amazon_s3 SET title='".$title.
                                    "',short_name = '".mysql_escape_string($new_shortname).
                                    "',creation_date = '".time()."',content_size = '".$c_size['size'].
                                    "',bucket_id = '".mysql_escape_string(trim(trim($_POST['bucket_name'])))."',content_id = '".$newFileName.
                                    "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                    "',content_access = '".mysql_escape_string($content_access).
                                    "',sold_page = '".$sold_letter."',keywords = '".$keywords.
                                    "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                    "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                    "',player_height = '".$player_height."',player_width = '".$player_width.
                                    "',download_graphic = '".$newFileNameArray["$download"]."',player_color = '".$player_color.
                                    "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                    "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view.
                                    "',paypal_button = '".$newFileNameArray["$paypal"]."',alert_pay_button = '".$newFileNameArray["$alertpay"].
                                    "',google_checkout_button = '".$newFileNameArray["$google"]."',clickbank_button = '".$newFileNameArray["$clickbank"].
                                    "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                    "',storage_location = '".'s3'.
                                    "'";
									
                                    $id = $db->insert_data_id($query);

                                    $token= $_POST['content-type'].'_' .$token. '_' . $id;

                                    $query ="UPDATE ".$prefix."amazon_s3 SET hidden_id='".md5($id)."' , custom_token = '".$token."' where id = $id";
                                    $db->insert($query);
                                   

                                   
                                    header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");


                            }else{// Upload New File

                                if (S3::putObject(S3::inputFile($fileTempName), mysql_escape_string(trim($_POST['bucket_name'])),$newFileName , S3::ACL_PUBLIC_READ)) {

                                    // Generate Token
                                    //$content_type = explode('/' , $_FILES['s3file']['type']);
                                   // $token = $content_type[0].'_'.$fileName;
                                   $newFileNameArray = array();
                                    //$counter = '0';
                                    foreach ($_FILES as $btnFiles){
                                        $old_name = $btnFiles['name'];
                                        if(!empty($btnFiles['name'])){
                                            $upload = new Upload();
                                            $upload->SetFileName($btnFiles['name']);
                                            $upload->SetTempName($btnFiles['tmp_name']);
                                            $upload->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.
                                            $upload->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                            $upload->UploadFile();
                                            $newFileNameArray["$old_name"] = $upload->GetFileName();

                                       }
                                        //$counter++;
                                    }


                                    $new_shortname = strtolower($short_name);
                                    $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                    $token = preg_replace('/[^a-zA-Z0-9]/i', '', $new_shortname);

                                    $paypal = $_FILES['paypal_button']['name'];
                                    $alertpay = $_FILES['alertpay_button']['name'];
                                    $google = $_FILES['google_checkout_button']['name'];
                                    $clickbank = $_FILES['clickbank_button']['name'];
                                    $download = $_FILES['download_button']['name'];

                                    if(empty($player_height) && $_POST['content-type']=='video')
                                            $player_height='300';
                                    if(empty($player_width) && $_POST['content-type']=='video')
                                            $player_width='400';

                                    if(empty($player_height) && $_POST['content-type']=='audio')
                                            $player_height='24';
                                    if(empty($player_width) && $_POST['content-type']=='audio')
                                            $player_width='300';


                                    $query = "INSERT INTO ".$prefix."amazon_s3 SET title='".$title.
                                    "',short_name = '".mysql_escape_string($new_shortname).
                                    "',creation_date = '".time()."',content_size = '".$_FILES['s3file']['size'].
                                    "',bucket_id = '".mysql_escape_string(trim($_POST['bucket_name']))."',content_id = '".$newFileName.
                                    "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                    "',content_access = '".mysql_escape_string($content_access).
                                    "',sold_page = '".$sold_letter."',keywords = '".$keywords.
                                    "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                    "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                    "',player_height = '".$player_height."',player_width = '".$player_width.
                                    "',download_graphic = '".$newFileNameArray["$download"]."',player_color = '".$player_color.
                                    "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                    "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view.
                                    "',paypal_button = '".$newFileNameArray["$paypal"]."',alert_pay_button = '".$newFileNameArray["$alertpay"].
                                    "',google_checkout_button = '".$newFileNameArray["$google"]."',clickbank_button = '".$newFileNameArray["$clickbank"].
                                    "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                    "',storage_location = '".'s3'.
                                    "'";
									
									
                                    $id = $db->insert_data_id($query);

                                    $token= $_POST['content-type'].'_' .$token. '_' . $id;

                                    $query ="UPDATE ".$prefix."amazon_s3 SET hidden_id='".md5($id)."' ,  custom_token = '".$token."' where id = $id";
                                    $db->insert($query);
                                   // ',


                                    header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");

                                }else{

                                    header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=request_fail");
                                }

                            }

                        }elseif($_POST['upload-location'] == 'local'){// If user choose to store contents locally

                                if($_POST['content-type'] == 'audio' || $_POST['content-type'] == 'video'){
                                    $my_upload_dir = $media_upload_dir;
                                    $linked_content_size = filesize($media_upload_dir.$link_id);
                                }elseif($_POST['content-type'] == 'file'){
                                    $my_upload_dir = $download_upload_dir;
                                    $linked_content_size = filesize($download_upload_dir.$link_id);
                                }

                                if(!empty($_FILES['s3file']['name'])){
                                    $newFileName = trim(time().$fileName);
                                }elseif(!empty($link_id)){
                                    $newFileName = $link_id;
                                }


                                /** Want to Attach the file or Upload New one **/

                                if($_POST['sel_file'] == 'link'){ // Link Exisiting File

                                    /*$upload = new Upload();
                                    $upload->SetFileName($newFileName);
                                    $upload->SetTempName($fileTempName);
                                    $upload->SetUploadDirectory($my_upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                    $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.
                                    $upload->SetMaximumFileSize(); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                    $upload->UploadFile();
                                    */
                                    $newFileNameArray = array("$fileName" => $newFileName);

                                    //chmod($my_upload_dir.$upload->GetFileName(), 0777);
                                    // Generate Token
                                    $content_type = explode('/' , $_FILES['s3file']['type']);

                                    $counter = '0';
                                    foreach ($_FILES as $btnFiles){
                                         $old_name = $btnFiles['name'];
                                        if(!empty($btnFiles['name'])){
                                            $upload = new Upload();
                                            $upload->SetFileName($btnFiles['name']);
                                            $upload->SetTempName($btnFiles['tmp_name']);
                                            $upload->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.
                                            $upload->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                            $upload->UploadFile();
                                            $newFileNameArray["$old_name"] = $upload->GetFileName();
                                       }
                                        //$counter++;
                                    }

                                    $new_shortname = strtolower($short_name);
                                    $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                    $token = preg_replace('/[^a-zA-Z0-9]/i', '', $new_shortname);

                                    $paypal = $_FILES['paypal_button']['name'];
                                    $alertpay = $_FILES['alertpay_button']['name'];
                                    $google = $_FILES['google_checkout_button']['name'];
                                    $clickbank = $_FILES['clickbank_button']['name'];
                                    $download = $_FILES['download_button']['name'];

                                    if(empty($player_height) && $_POST['content-type']=='video')
                                            $player_height='300';
                                    if(empty($player_width) && $_POST['content-type']=='video')
                                            $player_width='400';

                                    if(empty($player_height) && $_POST['content-type']=='audio')
                                            $player_height='24';
                                    if(empty($player_width) && $_POST['content-type']=='audio')
                                            $player_width='300';

                                   

                                    $query = "INSERT INTO ".$prefix."amazon_s3 SET title='".$title.
                                    "',short_name = '".mysql_escape_string($new_shortname).
                                    "',creation_date = '".time()."',content_size = '".$linked_content_size.
                                    "',bucket_id = '".mysql_escape_string(trim($_POST['bucket_name']))."
									',content_id = '".$link_id.
                                    "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                    "',content_access = '".mysql_escape_string($content_access).
                                    "',sold_page = '".$sold_letter."',keywords = '".$keywords.
                                    "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                    "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                    "',player_height = '".$player_height."',player_width = '".$player_width.
                                    "',download_graphic = '".$newFileNameArray["$download"]."',player_color = '".$player_color.
                                    "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                    "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view.
                                    "',paypal_button = '".$newFileNameArray["$paypal"]."',alert_pay_button = '".$newFileNameArray["$alertpay"].
                                    "',google_checkout_button = '".$newFileNameArray["$google"]."',clickbank_button = '".$newFileNameArray["$clickbank"].
                                    "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                    "',storage_location = '".'local'.
                                    "'";

                                    $id = $db->insert_data_id($query);

                                    $token= $_POST['content-type'].'_' . $token. '_' . $id;

                                    $query ="UPDATE ".$prefix."amazon_s3 SET hidden_id='".md5($id)."' , custom_token = '".$token."' where id = $id";
                                    $db->insert($query);

                                    header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");


                                }else{// Upload New File
                             
                                    $upload = new Upload();
                                    $upload->SetFileName($newFileName);
                                    $upload->SetTempName($fileTempName);
                                    $upload->SetUploadDirectory($my_upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                    $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.
                                    $upload->SetMaximumFileSize(); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                    $upload->UploadFile();

                                    $getContentid = $upload->GetFileName();
                                    $newFileNameArray = array("$fileName" => $upload->GetFileName());

                                    chmod($my_upload_dir.$upload->GetFileName(), 0777);
                                    // Generate Token
                                    $content_type = explode('/' , $_FILES['s3file']['type']);

                                    $counter = '0';
                                    foreach ($_FILES as $btnFiles){
                                         $old_name = $btnFiles['name'];
                                        if(!empty($btnFiles['name'])){
                                            $upload1 = new Upload();
                                            $upload1->SetFileName($btnFiles['name']);
                                            $upload1->SetTempName($btnFiles['tmp_name']);
                                            $upload1->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                            $upload1->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.
                                            $upload1->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                            $upload1->UploadFile();
                                            $newFileNameArray["$old_name"] = $upload1->GetFileName();
                                       }
                                      
                                    }

                                    $new_shortname = strtolower($short_name);
                                    $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                    $token = preg_replace('/[^a-zA-Z0-9]/i', '', $new_shortname);

                                    $paypal = $_FILES['paypal_button']['name'];
                                    $alertpay = $_FILES['alertpay_button']['name'];
                                    $google = $_FILES['google_checkout_button']['name'];
                                    $clickbank = $_FILES['clickbank_button']['name'];
                                    $download = $_FILES['download_button']['name'];

                                    if(empty($player_height) && $_POST['content-type']=='video')
                                            $player_height='300';
                                    if(empty($player_width) && $_POST['content-type']=='video')
                                            $player_width='400';

                                    if(empty($player_height) && $_POST['content-type']=='audio')
                                            $player_height='24';
                                    if(empty($player_width) && $_POST['content-type']=='audio')
                                            $player_width='300';

                                    $query = "INSERT INTO ".$prefix."amazon_s3 SET title='".$title.
                                    "',short_name = '".mysql_escape_string($new_shortname).
                                    "',creation_date = '".time()."',content_size = '".$_FILES['s3file']['size'].
                                    "',bucket_id = '".mysql_escape_string(trim($_POST['bucket_name']))."',content_id = '".$getContentid.
                                    "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                    "',content_access = '".mysql_escape_string($content_access).
                                    "',sold_page = '".$sold_letter."',keywords = '".$keywords.
                                    "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                    "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                    "',player_height = '".$player_height."',player_width = '".$player_width.
                                    "',download_graphic = '".$newFileNameArray["$download"]."',player_color = '".$player_color.
                                    "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                    "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view.
                                    "',paypal_button = '".$newFileNameArray["$paypal"]."',alert_pay_button = '".$newFileNameArray["$alertpay"].
                                    "',google_checkout_button = '".$newFileNameArray["$google"]."',clickbank_button = '".$newFileNameArray["$clickbank"].
                                    "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                    "',storage_location = '".'local'.
                                    "'";

                                    $id = $db->insert_data_id($query);

                                    $token= $_POST['content-type'].'_' . $token. '_' . $id;

                                    $query ="UPDATE ".$prefix."amazon_s3 SET hidden_id='".md5($id)."' , custom_token = '".$token."' where id = $id";
                                    $db->insert($query);
                                 

                                    header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");
                            }
                        }

                    }else{

                        header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=ext_not_allowed");

                    }

               
          
        }elseif(isset($_POST['delete'])){ // Delete Contents Actions

                    foreach($_POST['delete'] as $id){

                        $select_query = "SELECT COUNT(*)as total FROM ".$prefix."amazon_s3 WHERE content_id ='".$id."'";
                        $resultSet = $db->get_a_line($select_query);
                        $con_type = explode('_',$resultSet['custom_token']);
                        $total_contents = $resultSet['total'];
                        
                        ########################## Actions  ##########################
                        switch($act = 'd')
                        {
                        case 'd':                          
                                        $obj_pri= new Privileges($admin_id,$db);
                                        $obj_pri->getRole();
                                        if($obj_pri->canDelete('view_bucket_contents.php'))
                                        {   
                                                //Delete a bucket
                                                 if($_POST['storage_type'] == 'local'){ // Delete Contents Locally

                                                     if($con_type[0] == 'audio' || $con_type[0] == 'video'){
                                                            $my_upload_dir = $media_upload_dir;
                                                        }elseif($con_type[0] == 'file'){
                                                            $my_upload_dir = $download_upload_dir;
                                                        }

                                                        // Delete Record from DB
                                                        //$countRecord = "SELECT COUNT(*) FROM ".$prefix."amazon_s3 WHERE content_id ='".$id."'";
                                                        //$total_row = db->g


                                                       $delete_query = "DELETE FROM ".$prefix."amazon_s3 WHERE content_id ='".$id."'";
														
                                                        if($db->insert($delete_query)){

                                                        //if($total_contents <= 1)
                                                        //@unlink($my_upload_dir.$id);

                                                        if(file_exists($upload_dir.$resultSet['download_graphic'])){ @unlink($upload_dir.$resultSet['download_graphic']);}
                                                        if(file_exists($upload_dir.$resultSet['paypal_button'])){ @unlink($upload_dir.$resultSet['paypal_button']);}
                                                        if(file_exists($upload_dir.$resultSet['alert_pay_button'])){ @unlink($upload_dir.$resultSet['alert_pay_button']);}

                                                        }else{
                                                            header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=delete_content");
                                                        }

                                                }else{// Delete Contents from Amazon

                                                       $delete_query = "DELETE FROM ".$prefix."amazon_s3 WHERE content_id ='".$id."'";
													  
                                                        if($db->insert($delete_query)){

                                                        //if($total_contents <= 1)
                                                        S3::deleteObject(mysql_escape_string(trim($_POST['bucket_name'])), $id);
                                                        //Unlink Files
                                                         if(file_exists($upload_dir.$resultSet['download_graphic'])){ @unlink($upload_dir.$resultSet['download_graphic']);}
                                                         if(file_exists($upload_dir.$resultSet['paypal_button'])){ @unlink($upload_dir.$resultSet['paypal_button']);}
                                                         if(file_exists($upload_dir.$resultSet['alert_pay_button'])){ @unlink($upload_dir.$resultSet['alert_pay_button']);}
                                                         //if(file_exists($upload_dir.$resultSet['google_checkout_button'])){ @unlink($upload_dir.$resultSet['google_checkout_button']);}
                                                         // if(file_exists($upload_dir.$resultSet['clickbank_button'])){ @unlink($upload_dir.$resultSet['clickbank_button']);}

														}else{
	
															header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=delete_content");
														}
                                                }
                                        }
                                        else
                                        {
                                         $msg=archive_content($resultSet['id'],0,$db,$prefix);
										 header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=$msg");
                                       
                                        }
                                break;
                        case 'a':
                                        $msg=archive_content($resultSet['id'],$resultSet['published'],$db,$prefix);
                                        header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=$msg");
                        break;	
                        }


                        ################## Functions ##################
                        /*function delete_content($id,$db,$prefix)
                        {
                                $db->insert("delete from ".$prefix."products where id ='$id'");
                                return $msg='d'; 
                        }
						*/
                        


                    }

                   // header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=delete_content");


         }elseif(isset($_POST['edit'])){ //  Edit Contents Actions

                    // Upload contents to bucket
                    extract($_POST);
                    $fileName = preg_replace('/[^a-zA-Z0-9-.]/i', '-', $_FILES['s3file']['name']);//$_FILES['s3file']['name'];
                    $fileTempName = $_FILES['s3file']['tmp_name'];

                    //Database Fetch Records
                    $select_query = "SELECT * FROM ".$prefix."amazon_s3 WHERE id =".$id;
                    $resultSet = $db->get_a_line($select_query);

                    if($_POST['bucket_type'] != 'local' ){// Amazon Storage Used

                        if(!empty($bucket_name) && !empty($content_id)){

                            if(!empty($fileName)){// If we get a new file

                                if(S3::deleteObject($bucket_name, $content_id)){
                                    
                                    //Get File extension to check, its allowed or not
                                        $filext = substr($fileName, strrpos($fileName, '.')+1);

                                        if(in_array($filext, $allowed_extensions)){

                                            $newFileName = trim(time().$fileName);

                                            if (S3::putObject(S3::inputFile($fileTempName), mysql_escape_string(trim($_POST['bucket_name'])), $newFileName, S3::ACL_PUBLIC_READ)) {

                                                // Generate Token
                                                $content_type = explode('/' , $_FILES['s3file']['type']);
                                                $token = $content_type[0].'_'.$fileName;

                                                $newFileNameArray = array();
                                                $counter = '0';

                                                //Unlink Old Files before we Upload new ones.
                                                if(file_exists($upload_dir.$resultSet['download_graphic']) && !empty($_FILES['download_button']['name'])){ @unlink($upload_dir.$resultSet['download_graphic']);}
                                                if(file_exists($upload_dir.$resultSet['paypal_button']) && !empty($_FILES['paypal_button']['name'])){ @unlink($upload_dir.$resultSet['paypal_button']);}
                                                if(file_exists($upload_dir.$resultSet['alert_pay_button']) && !empty($_FILES['alertpay_button']['name'])){ @unlink($upload_dir.$resultSet['alert_pay_button']);}
                                                //if(file_exists($upload_dir.$resultSet['google_checkout_button'])){ @unlink($upload_dir.$resultSet['google_checkout_button']);}
                                               // if(file_exists($upload_dir.$resultSet['clickbank_button'])){ @unlink($upload_dir.$resultSet['clickbank_button']);}


                                                foreach ($_FILES as $btnFiles){                                                      

                                                        //if($counter < '4' ){
                                                        if(!empty($btnFiles['name'])){
                                                            $old_name = $btnFiles['name'];
                                                            $upload = new Upload();
                                                            $upload->SetFileName($btnFiles['name']);
                                                            $upload->SetTempName($btnFiles['tmp_name']);
                                                            $upload->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.

                                                            $upload->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                                            $upload->UploadFile();
                                                            $newFileNameArray["$old_name"] = $upload->GetFileName();
                                                        }
                                                        //$counter++;

                                                }

												
				                                	
                                                $new_shortname = strtolower($short_name);
                                                $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                                //$token = preg_replace('/[^a-zA-Z0-9]/i', '', $new_shortname);

                                                $paypal = $_FILES['paypal_button']['name'];
                                                $alertpay = $_FILES['alertpay_button']['name'];
                                                $google = $_FILES['google_checkout_button']['name'];
                                                $clickbank = $_FILES['clickbank_button']['name'];
                                                $download = $_FILES['download_button']['name'];
                                                $content_size = ($_FILES['s3file']['size'] !="")? $_FILES['s3file']['size']: $resultSet['content_size'];

                                                $query = "UPDATE ".$prefix."amazon_s3 SET title='".$title.
                                                "',short_name = '".mysql_escape_string($new_shortname).//"',custom_token = '".$token.
                                                "',content_size = '". $content_size .
                                                "',bucket_id = '".mysql_escape_string(trim($_POST['bucket_name']));
                                                if(!empty($newFileName)){
                                                   $query .= "',content_id = '".$newFileName;
                                                }
                                                "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                                "',sold_page = '".$sold_letter."',keywords = '".$keywords.
												"',`content_access` = '".mysql_escape_string($content_access).
                                                "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                                "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                                "',player_height = '".$player_height."',player_width = '".$player_width.
                                                "',player_color = '".$player_color.
                                                "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                                "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view;

                                                if(!empty($newFileNameArray["$download"])){
                                                   $query .= "',download_graphic = '".$newFileNameArray["$download"];
                                                }
                                                if(!empty($newFileNameArray["$paypal"])){
                                                   $query .= "',paypal_button = '".$newFileNameArray["$paypal"];
                                                }
                                                if(!empty($newFileNameArray["$alertpay"])){
                                                   $query .= "',alert_pay_button = '".$newFileNameArray["$alertpay"];
                                                }
                                                if(!empty($newFileNameArray["$google"])){
                                                   $query .= "',google_checkout_button = '".$newFileNameArray["$google"];
                                                }
                                                if(!empty($newFileNameArray["$clickbank"])){
                                                   $query .= "',clickbank_button = '".$newFileNameArray["$clickbank"];
                                                }

                                                $query .= "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                                "',storage_location = '".'s3'.
                                                "' WHERE id =".mysql_escape_string($_POST['id']);
                                                  $db->insert($query);
                                                  header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");



                                            }else{

                                                header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=request_fail");
                                            }

                                        }else{

                                            header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=ext_not_allowed");

                                        }

                                    }

                              }else{ //If we have old files


                                                $newFileNameArray = array();
                                                //$counter = '0';

                                                //Unlink Old Files before we Upload new ones.
                                                if(file_exists($upload_dir.$resultSet['download_graphic']) && !empty($_FILES['download_button']['name'])){ @unlink($upload_dir.$resultSet['download_graphic']);}
                                                if(file_exists($upload_dir.$resultSet['paypal_button']) && !empty($_FILES['paypal_button']['name'])){ @unlink($upload_dir.$resultSet['paypal_button']);}
                                                if(file_exists($upload_dir.$resultSet['alert_pay_button']) && !empty($_FILES['alertpay_button']['name'])){ @unlink($upload_dir.$resultSet['alert_pay_button']);}
                                                //if(file_exists($upload_dir.$resultSet['google_checkout_button'])){ @unlink($upload_dir.$resultSet['google_checkout_button']);}
                                               // if(file_exists($upload_dir.$resultSet['clickbank_button'])){ @unlink($upload_dir.$resultSet['clickbank_button']);}


                                                foreach ($_FILES as $btnFiles){
                                                        
                                                        if(!empty($btnFiles['name'])){
                                                            $old_name = $btnFiles['name'];
                                                            $upload = new Upload();
                                                            $upload->SetFileName($btnFiles['name']);
                                                            $upload->SetTempName($btnFiles['tmp_name']);
                                                            $upload->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.

                                                            $upload->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                                            $upload->UploadFile();
                                                            $newFileNameArray["$old_name"] = $upload->GetFileName();
                                                        }
                                                        

                                                }


                                                $new_shortname = strtolower($short_name);
                                                $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                                

                                                $paypal = $_FILES['paypal_button']['name'];
                                                $alertpay = $_FILES['alertpay_button']['name'];
                                                $google = $_FILES['google_checkout_button']['name'];
                                                $clickbank = $_FILES['clickbank_button']['name'];
                                                $download = $_FILES['download_button']['name'];
                                                $content_size = ($_FILES['s3file']['size'] !="")? $_FILES['s3file']['size']: $resultSet['content_size'];

                                                $query = "UPDATE ".$prefix."amazon_s3 SET title='".$title.
                                                "',short_name = '".mysql_escape_string($new_shortname).
                                                "',content_size = '".$content_size.
												"',`content_access` = '".mysql_escape_string($content_access).
                                                "',bucket_id = '".mysql_escape_string(trim($_POST['bucket_name']))."',content_id = '".$resultSet['content_id'].
                                                "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                                "',sold_page = '".$sold_letter."',keywords = '".$keywords.
                                                "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                                "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                                "',player_height = '".$player_height."',player_width = '".$player_width.
                                                "',player_color = '".$player_color.
                                                "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                                "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view;
                                                
                                                if(!empty($newFileNameArray["$download"])){
                                                   $query .= "',download_graphic = '".$newFileNameArray["$download"];
                                                }
                                                if(!empty($newFileNameArray["$paypal"])){
                                                   $query .= "',paypal_button = '".$newFileNameArray["$paypal"];
                                                }
                                                if(!empty($newFileNameArray["$alertpay"])){
                                                   $query .= "',alert_pay_button = '".$newFileNameArray["$alertpay"];
                                                }
                                                if(!empty($newFileNameArray["$google"])){
                                                   $query .= "',google_checkout_button = '".$newFileNameArray["$google"];
                                                }
                                                if(!empty($newFileNameArray["$clickbank"])){
                                                   $query .= "',clickbank_button = '".$newFileNameArray["$clickbank"];
                                                }

                                                $query .= "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                                "',storage_location = '".'s3'.
                                                "' WHERE id =".mysql_escape_string($_POST['id']);
												
                                                  $db->insert($query);
                                                  header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");

                              }


                                        

                        }

                    }elseif($_POST['bucket_type'] == 'local'){// Local Upload Storage Used
                       
                          if(!empty($bucket_name) && !empty($content_id)){

                              if(!empty($fileName)){// If we have a new file

                                  if($_POST['content-type'] == 'audio' || $_POST['content-type'] == 'video'){
                                        $my_upload_dir = $media_upload_dir;
                                    }elseif($_POST['content-type'] == 'file'){
                                        $my_upload_dir = $download_upload_dir;
                                    }

                                //if(@unlink($my_upload_dir.$content_id)){ //
                                    @unlink($my_upload_dir.$content_id);

                                        //Get File extension to check, its allowed or not
                                        $filext = substr($fileName, strrpos($fileName, '.')+1);

                                        if(in_array($filext, $allowed_extensions)){
                                            
                                            $upload = new Upload();
                                            $upload->SetFileName($fileName);
                                            $upload->SetTempName($fileTempName);
                                            $upload->SetUploadDirectory($my_upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.
                                            $upload->SetMaximumFileSize(); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                            
                                            
                                            if ($upload->UploadFile()) { 

                                                $newFileNameArray = array("$fileName" => $upload->GetFileName());

                                                // Generate Token
                                                $content_type = explode('/' , $_FILES['s3file']['type']);
                                                $token = $content_type[0].'_'.$fileName;

                                                //Unlink Old Files before we Upload new ones.
                                                if(file_exists($upload_dir.$resultSet['download_graphic']) && !empty($_FILES['download_button']['name'])){ @unlink($upload_dir.$resultSet['download_graphic']);}
                                                if(file_exists($upload_dir.$resultSet['paypal_button']) && !empty($_FILES['paypal_button']['name'])){ @unlink($upload_dir.$resultSet['paypal_button']);}
                                                if(file_exists($upload_dir.$resultSet['alert_pay_button']) && !empty($_FILES['alertpay_button']['name'])){ @unlink($upload_dir.$resultSet['alert_pay_button']);}
                                                //if(file_exists($upload_dir.$resultSet['google_checkout_button'])){ @unlink($upload_dir.$resultSet['google_checkout_button']);}
                                                // if(file_exists($upload_dir.$resultSet['clickbank_button'])){ @unlink($upload_dir.$resultSet['clickbank_button']);}

                                                $counter = '0';
                                                foreach ($_FILES as $btnFiles){

                                                        //if($counter < '4' ){
                                                        if(!empty($btnFiles['name'])){
                                                            $old_name = $btnFiles['name'];
                                                            $upload = new Upload();
                                                            $upload->SetFileName($btnFiles['name']);
                                                            $upload->SetTempName($btnFiles['tmp_name']);
                                                            $upload->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.

                                                            $upload->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                                            $upload->UploadFile();
                                                            $newFileNameArray["$old_name"] = $upload->GetFileName();

                                                        }
                                                        //$counter++;

                                                }


                                                $new_shortname = strtolower($short_name);
                                                $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                                //$token = preg_replace('/[^a-zA-Z0-9]/i', '', $new_shortname);

                                                $paypal = $_FILES['paypal_button']['name'];
                                                $alertpay = $_FILES['alertpay_button']['name'];
                                                $google = $_FILES['google_checkout_button']['name'];
                                                $clickbank = $_FILES['clickbank_button']['name'];
                                                $download = $_FILES['download_button']['name'];

                                                $query = "UPDATE ".$prefix."amazon_s3 SET title='".$title.
                                                "',short_name = '".mysql_escape_string($new_shortname).//"',custom_token = '".$token.
                                                "',content_size = '".$_FILES['s3file']['size'].
                                                "',content_access = '".mysql_escape_string($content_access).
                                                "',bucket_id = '".mysql_escape_string(trim($_POST['bucket_name']))."',content_id = '".$newFileNameArray["$fileName"].
                                                "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                                "',sold_page = '".$sold_letter."',keywords = '".$keywords.
                                                "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                                "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                                "',player_height = '".$player_height."',player_width = '".$player_width.
                                                "',player_color = '".$player_color.
                                                "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                                "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view;

                                                if(!empty($newFileNameArray["$download"])){
                                                   $query .= "',download_graphic = '".$newFileNameArray["$download"];
                                                }
                                                if(!empty($newFileNameArray["$paypal"])){
                                                   $query .= "',paypal_button = '".$newFileNameArray["$paypal"];
                                                }
                                                if(!empty($newFileNameArray["$alertpay"])){
                                                   $query .= "',alert_pay_button = '".$newFileNameArray["$alertpay"];
                                                }
                                                if(!empty($newFileNameArray["$google"])){
                                                   $query .= "',google_checkout_button = '".$newFileNameArray["$google"];
                                                }
                                                if(!empty($newFileNameArray["$clickbank"])){
                                                   $query .= "',clickbank_button = '".$newFileNameArray["$clickbank"];
                                                }

                                                $query .= "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                                "',storage_location = '".'local'.
                                                "' WHERE id =".mysql_escape_string($_POST['id']);
                                                
                                                  $db->insert($query);
                                                  header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");



                                            }else{

                                                header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=request_fail");
                                            }

                                        }else{

                                            header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=ext_not_allowed");

                                        }

                                //}

                            }else{// If we have the old file

                                                //Unlink Old Files before we Upload new ones.
                                                if(file_exists($upload_dir.$resultSet['download_graphic']) && !empty($_FILES['download_button']['name'])){ @unlink($upload_dir.$resultSet['download_graphic']);}
                                                if(file_exists($upload_dir.$resultSet['paypal_button']) && !empty($_FILES['paypal_button']['name'])){ @unlink($upload_dir.$resultSet['paypal_button']);}
                                                if(file_exists($upload_dir.$resultSet['alert_pay_button']) && !empty($_FILES['alertpay_button']['name'])){ @unlink($upload_dir.$resultSet['alert_pay_button']);}
                                                //if(file_exists($upload_dir.$resultSet['google_checkout_button'])){ @unlink($upload_dir.$resultSet['google_checkout_button']);}
                                                // if(file_exists($upload_dir.$resultSet['clickbank_button'])){ @unlink($upload_dir.$resultSet['clickbank_button']);}

                                                $counter = '0';
                                                foreach ($_FILES as $btnFiles){

                                                        //if($counter < '4' ){
                                                        if(!empty($btnFiles['name'])){
                                                            $old_name = $btnFiles['name'];
                                                            $upload = new Upload();
                                                            $upload->SetFileName($btnFiles['name']);
                                                            $upload->SetTempName($btnFiles['tmp_name']);
                                                            $upload->SetUploadDirectory($upload_dir); //Upload directory, this should be writable. Change Usign Config/Config.php
                                                            $upload->SetValidExtensions($allowed_extensions); //Extensions that are allowed if none are set all extensions will be allowed.

                                                            $upload->SetMaximumFileSize(30000000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value
                                                            $upload->UploadFile();
                                                            $newFileNameArray["$old_name"] = $upload->GetFileName();

                                                        }
                                                        //$counter++;

                                                }


                                                $new_shortname = strtolower($short_name);
                                                $new_shortname = preg_replace('/[^a-zA-Z0-9]/i', '-', $new_shortname);
                                                //$token = preg_replace('/[^a-zA-Z0-9]/i', '', $new_shortname);

                                                $paypal = $_FILES['paypal_button']['name'];
                                                $alertpay = $_FILES['alertpay_button']['name'];
                                                $google = $_FILES['google_checkout_button']['name'];
                                                $clickbank = $_FILES['clickbank_button']['name'];
                                                $download = $_FILES['download_button']['name'];

                                                $query = "UPDATE ".$prefix."amazon_s3 SET title='".$title.
                                                "',short_name = '".mysql_escape_string($new_shortname).//"',custom_token = '".$token.
                                                "',content_size = '".$resultSet['content_size'].
                                                "',content_access = '".mysql_escape_string($content_access).
                                                "',bucket_id = '".mysql_escape_string(trim($_POST['bucket_name']))."',content_id = '".$resultSet['content_id'].
                                                "',description_page = '".mysql_escape_string($description)."',sales_page = '".$sales_letter.
                                                "',sold_page = '".$sold_letter."',keywords = '".$keywords.
                                                "',auto_play = '".$auto_play."',player_controls = '".$player_controls.
                                                "',full_screen = '".$full_screen."',download_link = '".$download_link.
                                                "',player_height = '".$player_height."',player_width = '".$player_width.
                                                "',player_color = '".$player_color.
                                                "',buffer_time = '".$buffer_time."',allow_ripping_downloading = '".$allow_downloading.
                                                "',charge_to_view = '".$charge_to_view."',price_to_view = '".$price_to_view;

                                                if(!empty($newFileNameArray["$download"])){
                                                   $query .= "',download_graphic = '".$newFileNameArray["$download"];
                                                }
                                                if(!empty($newFileNameArray["$paypal"])){
                                                   $query .= "',paypal_button = '".$newFileNameArray["$paypal"];
                                                }
                                                if(!empty($newFileNameArray["$alertpay"])){
                                                   $query .= "',alert_pay_button = '".$newFileNameArray["$alertpay"];
                                                }
                                                if(!empty($newFileNameArray["$google"])){
                                                   $query .= "',google_checkout_button = '".$newFileNameArray["$google"];
                                                }
                                                if(!empty($newFileNameArray["$clickbank"])){
                                                   $query .= "',clickbank_button = '".$newFileNameArray["$clickbank"];
                                                }

                                                $query .= "',publish_date = '".time()."',unpublish_date = '".'NULL'.
                                                "',storage_location = '".'local'.
                                                "' WHERE id =".mysql_escape_string($_POST['id']);
                                              
                                                  $db->insert($query);
                                                  header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&msg=upload_success");
            
                            }

                        }


                    }




        }else{

                    header("Location: ".$site_url."view_bucket_contents.php?bucket=".mysql_escape_string(trim($_POST['bucket_name']))."&error=access_denied");

        }
		
function archive_content($id,$state,$db,$prefix)
		{
				$sql="update ".$prefix."amazon_s3 set published='$state' where id ='$id'";

				$db->insert($sql);
				if($state==1)
						return $msg='a';
				else 
						return $msg='un';
		}		
?>