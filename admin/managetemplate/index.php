<?php
error_reporting(E_ALL);
include_once("../session.php");
include_once("../header.php");
include_once('class-template.php');
$file_path = $root_path ."/templates/";
chmodDirectory("../../templates",0);
if(!class_exists('ZipArchive')) 
	$errormessage="sorry unable to extract your zipfile. Your server did not support this feature. 
			Please contact server administrator.";
else
	$errormessage="";			
#################### upload theme ##########################

if ($_FILES["zip_file"]["name"]) {
    $folder = pathinfo($_FILES["zip_file"]["name"], PATHINFO_FILENAME);
    $theme = $file_path . $folder;
    $name = htmlentities($_POST["file_name"]);
    $filename = $_FILES["zip_file"]["name"];
    $source = $_FILES["zip_file"]["tmp_name"];
    $type = $_FILES["zip_file"]["type"];
    $name = explode(".", $filename);
    $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    foreach ($accepted_types as $mime_type) {
        if ($mime_type == $type) {
            $okay = true;
            break;
        }
    }
   $continue = strtolower($name[1]) == 'zip' ? true : false;
    if (!$continue) {
        $errmessage = "The file you are trying to upload is not a .zip formate. Please try again.";
    } else {
        if (!is_dir($theme)) {

            mkdir($theme, 0777);

            $target_path = $file_path . $filename;  // change this to the correct site path

            if (move_uploaded_file($source, $target_path)) {
			 $zip = new ZipArchive();
				if ($zip->open($target_path)=== TRUE) {
					  $zip->extractTo($theme); // change this to the correct site path
	                  $zip->close();
    	              unlink($target_path);

                }
				else
				{
			echo "sorry unable to extract your zipfile. Your server did not support this feature. 
			Please contact server administrator.";
					exit();
				}
				chmodDirectory("../../templates/$folder",0);
               $sql = "insert " . $prefix . "template set `name`='$folder'";

                $db->insert("$sql");
			
                $message = "Template Uploaded Successfully!";

            } else {

                $errmessage = "Templates directory is missing.";

            }

        } else {

            $errmessage = "This Theme name already exist please use different !";

        }
    }
}
#################### ACTION ##########################
switch ($_GET['action']) {

    case 'default':

        $name = $_GET['name'];

        $for = $_GET['for'];

        $msg = default_template($name, $for, $prefix, $db);

        header("location:?msg=$msg");

        exit();



        break;

}

############# OPERATION ####################



function default_template($name, $for, $prefix, $db) {

    switch ($for) {

        case 'outside': $dafault = 'default';

            break;

        case 'blog': $dafault = 'default_blog';

            break;

        case 'member': $dafault = 'default_member';

            break;

    }



    $sql = "select count(id) as total from " . $prefix . "template where `name`='$name';";

    $row_total = $db->get_a_line("$sql");

    $sql = "UPDATE " . $prefix . "template set `$dafault`=0";

    $db->insert("$sql");

    if ($row_total['total'] > 0) {

        $sql = "UPDATE " . $prefix . "template set `$dafault`=1 where `name`='$name';";

    } else {

        $sql = "insert " . $prefix . "template set `$dafault`=1,`name`='$name'";

    }

    $db->insert("$sql");

    $msg = 'd';



    return $msg;

}



switch ($_GET['msgflag']) {

    case 1:

        $message = "Theme Deleted Successfully !";

        break;

    case 2:

        $errmessage = "There was a problem with the Theme Deleted. Please try again !";

        break;

    case 3:

        $errmessage = "Theme not exist with this name !";

        break;

}



################## MESSAGE ##################

if ($msg == "d") {

    $Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Template is set as default</div>';

}

if ($message) {

    $Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> ' . $message . '</div>';

}

if ($errmessage) {

    $Message = '<div class="error"><img src="/images/crose.png" align="absmiddle"> ' . $errmessage . '</div>';

}
if(!empty($errormessage))
	$Message = '<div class="error"><img src="/images/crose.png" align="absmiddle"> ' . $errormessage . '</div>';

?>

<?php echo $Message; ?>
<style>
#screenshot{
	position:absolute;
	border:1px solid #ccc;
	background:#000;
	padding:5px;
	display:none;
	color:#fff;
	}
</style>
<script src="js/main.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $("#token").hide();
       $("#labeltoken").click(function () {
           $("#token").addClass("slider");
            $("#token").slideToggle('slow',function(){
               
            });
        });
    });
    function confirmdelete()
    {
       document.getElementById('action').value = 'del';
       if(confirm('Are you sure you want to delete this comment?'))
        {
            document.form.submit();
            return true;
        }
        else return false;
    }
    checked=false;
   function checkedAll () {
        var aa= document.getElementById('form');
       if (checked == false)
        {
            checked = true
        }
        else
        {
            checked = false
        }
        for (var i =0; i < aa.elements.length; i++)
        {
            aa.elements[i].checked = checked;
        }
    }
    function uploadform(){
        if(document.getElementById("themeupload").style.display=="none" || document.getElementById("themeupload").style.display==""){
            document.getElementById("themeupload").style.display="block"
        } else {
            document.getElementById("themeupload").style.display="none"
        }
    }
</script>

<div class="content-wrap">
    <div class="labeltoken" id="labeltoken">
        <div class="labelinner">
            <div class="rotate">Placeholders</div>
        </div>
     </div>
    <div class="content-wrap-top"></div>

    <div class="content-wrap-inner">

        <p><strong>Template Management</strong></p>
<?php if(empty($errormessage)){?>
        <div class="buttons" onclick="uploadform();">
            <a href="#.html">Add New Template</a>
        </div>

        <div id="themeupload" style="background-color: #F4F4F4;border: 1px solid #C4C4C4;clear: both;display: none;float: left;margin: 9px 0;padding: 5px 0 5px 8px;width: 99%;">
            <form action="index.php" method="post" enctype="multipart/form-data" >
                <b>Template File : </b> <input name="zip_file" type="file" class="inputbox" id="zip_file" value="" />
                <input type="submit" value="Upload">
                    <a href="" class="tooltip" title="file should be in zip format">
                        <img src="../../images/toolTip.png" alt="help" align="absmiddle"/>
                    </a>
            </form>
        </div>
<?php }?>		
        <div id="grid">

            <table cellpadding="0" cellspacing="0">

                <tr>

                    <th>Name</th>

                    <th>Size</th>

                    <th>Created On</th>

                    <th>Default Outside Page</th>

                    <th>Default Blog Page</th>

                    <th>Default Member area</th>

                    <th>Options</th>

                </tr>

                <?php

               $PATH = $_SERVER[DOCUMENT_ROOT] . "/templates/";

                $obj_template = new Template_information($PATH);

                $dir = $obj_template->ReadFolderDirectory($PATH);

				

                foreach ($dir as $name) {
					
                    $size = $obj_template->getDirectorySize($PATH.'/'.$name);

                ?>

                    <tr>



                        <td style="text-align:left" >
						
						<a href="#" class="screenshot" rel="<?php echo $http_path."/templates/$name/".$name.'.png'; ?>" title="<?php echo $name; ?>"><?php echo $name; ?></a></td>

                        <td nowrap="nowrap"><?php echo $size; ?></td>

                        <td nowrap="nowrap"><?php echo date("d F Y", filemtime("$PATH/$name")); ?></td>

                        <td>

                        <?php

                        if ($obj_template->getDefault($prefix, $db, $name, 'outside')) {

                            $image_src = "/images/admin/default.png";

                            $url = '';

                        } else {

                            $image_src = "/images/admin/default-grey.png";

                            $url = '?action=default&name=' . $name . '&for=outside';

                        }

                        ?>

                        <a href="<?php echo $url; ?>">

                            <img src="<?php echo $image_src; ?>" border="" alt="default">

                        </a>

                    </td>



                    <td>

                        <?php

                        if ($obj_template->getDefault($prefix, $db, $name, 'blog')) {

                            $image_src = "/images/admin/default.png";

                            $url = '';

                        } else {

                            $image_src = "/images/admin/default-grey.png";

                            $url = '?action=default&name=' . $name . '&for=blog';

                        }

                        ?>

                        <a href="<?php echo $url; ?>">

                            <img src="<?php echo $image_src; ?>" border="" alt="default">

                        </a>

                    </td>



                    <td>

                        <?php

                        if ($obj_template->getDefault($prefix, $db, $name, 'member')) {

                            $image_src = "/images/admin/default.png";

                            $url = '';

                        } else {

                            $image_src = "/images/admin/default-grey.png";

                            $url = '?action=default&name=' . $name . '&for=member';

                        }

                        ?>

                        <a href="<?php echo $url; ?>">

                            <img src="<?php echo $image_src; ?>" border="" alt="default">

                        </a>

                    </td>





                    <td nowrap="nowrap">

                      <img alt="Manange Template" src="/images/admin/manage.png" border="0" align="absmiddle">

                        <a href="manage.php?theme=<?php echo $name; ?>">Manage</a> &nbsp; 

                        <a href="deltheme.php?action=del&theme=<?php echo $name; ?>"><img alt="Delete Template" src="/images/crose.png" border="0" align="absmiddle"></a>                    </td>



                </tr>

                <?php } ?>



                </table>



            </div>
        <div class="tokens" id="token">
       <span style="color:red">Important Token</span>
       <ul>
        <li>$settings_keywords}<br /><small>Placeholder show Keyword in your templates</small></li>
        <li>{$settings_description} <br /><small>Placeholder show Website Meta Description in your templates</small></li>
        <li>{$settings_sitename}<br /><small>Placeholder show Website Name in your templates</small></li>
        <li>{$settings_meta}<br /><small>To show additional meta in template</small></li>
        <li>{$template_xxx_css}<br /><small>Placeholder allow you to add your custom CSS using Template Name in your templates</small></li>
        <li>{$menu_main}<br /><small>Placeholder allow you to add your Menus in your templates, You get this placeholder from Menu manager</small></li>
        <li>{$content}<br /><small>Placeholder allow you to add all the content in your template like Text,Images, Videos, Audio etc</small></li>
        <li>{$right_panel}/{$siderbar}<br /><small>Placeholder allow you to add Blog and Member area right section. </small></li>
        <li>{$settings_tracking}<br /><small>Placeholder allow you to add Google Analytics and other tracking JavaScripts. </small></li>
       </ul>
   </div>
     </div>
    
        <div class="content-wrap-bottom"></div>

    </div>

<?php 
function chmodDirectory( $path = ".", $level = 0 ){  

$ignore = array( 'images', '.', '..' );

$dh = @opendir( $path );

while( false !== ( $file = readdir( $dh ) ) ){ // Loop through the directory

if( !in_array($file, $ignore) ){
if( is_dir( "$path/$file" ) ){
chmod("$path/$file",0777);
chmodDirectory("$path/$file", ($level+1));
} else {
chmod("$path/$file",0777); // desired permission settings
}//elseif

}//if in array

}//while

closedir( $dh );

}//function

include_once("../footer.php"); ?>