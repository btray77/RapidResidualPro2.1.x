<?php
$setting_query = "SELECT * FROM ".$prefix."site_settings WHERE id='1'";
$setting_array = $db->get_a_line($setting_query);

$site_url = $http_path.'/admin/media-storage/';
$image_url = $http_path;

$upload_dir="$root_path/images/uploads/";

$local_upload_dir="$root_path/document/";
$local_upload_url="$http_path/document/";

$media_upload_dir = $root_path.$setting_array['swf_down'];
$media_upload_url = $http_path.$setting_array['swf_down'];
$download_upload_dir = $root_path.$setting_array['prot_down'];

$download_upload_url = $root_path.$setting_array['prot_down'];



if(!is_dir($media_upload_dir))

{

    @mkdir($media_upload_dir);

    @chmod($media_upload_dir,777);

}

if(!is_dir($download_upload_dir))

{

    @mkdir($download_upload_dir);

    @chmod($download_upload_dir,777);

}







// SITE Messages

$site_messages = array(

                'bucket_success' => 'Bucket has been created successfully.',

                'request_fail' => 'Sorry, its not a valid request. Try again',

                'access_denied' => 'Access Denied',

                'delete_content' => 'Contents deleted successfully',

				'un' => 'Contents is successfully archived',

                'upload_success' => 'Contents uploaded successfully',

                'delete_bucket' => 'Bucket deleted successfully',

                'ext_not_allowed' => 'This File Format is not supported.',

                'cnames_required' => 'Please enter valid CNAMEs',

                'distrb_success' => 'Distribution has been created successfully.',

                'delete_distrb' => 'Distribution deleted successfully',

                'distrb_enable' => 'Distribution Enabled successfully',

                'distrb_disable' => 'Distribution Disabled successfully',

);







// Extension Allowed

$allowed_extensions = explode(',',$setting_array['allowed_file_types']);





// AWS access info

//'AKIAJAKF4XFRP72A2U4A'

//'flaO4rQT7EVhjaIzO4R4sZhPHDGJphjEt3/ETmO2'

if (!defined('awsAccessKey')) define('awsAccessKey', $setting_array['aws_access_key']);

if (!defined('awsSecretKey')) define('awsSecretKey', $setting_array['aws_secret_key']);



//$uploadFile = dirname(__FILE__).'/actions/upload_file_action.php'; // File to upload, we'll use the S3 class since it exists



// If you want to use PECL Fileinfo for MIME types:

//if (!extension_loaded('fileinfo') && @dl('fileinfo.so')) $_ENV['MAGIC'] = '/usr/share/file/magic';





// Check for CURL

if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))

	exit("\nERROR: CURL extension not loaded\n\n");



// Pointless without your keys!

if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')

	exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".

	"define('awsAccessKey', 'change-me');\n

	 define('awsSecretKey', 'change-me');\n\n");
?>