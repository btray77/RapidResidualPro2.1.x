<?php
/* 
 * This file is used for the Ajax Call Response.
 * 
 */

include_once '../../session.php';
require_once '../config/config.php';
require_once '../common.php';


/** Link or Upload DropDownlist Population **/

if(isset($_POST['upload_location']) && $_POST['upload_location'] == 's3'){

        $options = "";
        $sql = "SELECT * FROM ".$prefix."amazon_s3 WHERE bucket_id = '".mysql_escape_string($_POST['bucket'])."' order by publish_date DESC";
        $result = mysql_query($sql);
        while($myResult = mysql_fetch_array($result)){

               $options.='<option value="'.$myResult["content_id"].'">'.$myResult["content_id"].'</option>';

        }
        echo ($options !="")? $options : '<option value="">No Record Found</option>';


}else if(isset($_POST['upload_location']) && $_POST['upload_location'] == 'local'){

        $options = "";
        $sql = "SELECT * FROM ".$prefix."amazon_s3 WHERE bucket_id = 'local' order by publish_date DESC";
        $result = mysql_query($sql);
        while($myResult = mysql_fetch_array($result)){

               $options.='<option value="'.$myResult["content_id"].'">'.$myResult["content_id"].'</option>';

        }
        echo ($options !="")? $options : '<option value="">No Record Found</option>';
}

/** Link or Upload DropDownlist Population Ends Here **/

?>
