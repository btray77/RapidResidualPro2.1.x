<?php ob_start();
include_once '../../session.php';
require_once '../config/config.php';
require_once '../common.php';


       if(isset($_POST['create_btn'])){

                $bucket_name =   preg_replace('/[^a-z0-9]+/i','-',iconv('UTF-8','ASCII//TRANSLIT',$_POST['bucket_name']));
                $bucket_name =   strtolower(mysql_escape_string($bucket_name));

                // Create a bucket
                if (S3::putBucket(mysql_escape_string(strtolower($bucket_name)), S3::ACL_PRIVATE)) {
                    header("Location: ".$site_url."index.php?msg=bucket_success");
                }else{

                    header("Location: ".$site_url."create_bucket.php?msg=request_fail");
                }
          
        }elseif(isset($_POST['delete'])){

                foreach($_POST['delete'] as $id){
                    //Delete a bucket
                    if (S3::deleteBucket("$id")){

                        header("Location: ".$site_url."index.php?msg=delete_bucket");
                    }else{

                        header("Location: ".$site_url."index.php?msg=request_fail");
                    }
                }
        }else{

                    header("Location: ".$site_url."index.php?msg=access_denied");

        }
?>