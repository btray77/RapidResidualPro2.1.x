<?php
include_once("../session.php");
include_once("../header.php");
include_once('class-template.php');

$PATH = $_SERVER[DOCUMENT_ROOT] . "/templates/";
$obj_template = new Template_information($PATH);
$dir = $obj_template->ReadFolderDirectory($PATH);


#################### ACTION ##########################
$file_path=$root_path."templates/";


if($_FILES["zip_file"]["name"]){

	$folder = pathinfo($_FILES["zip_file"]["name"], PATHINFO_FILENAME);
	$theme=$file_path.$folder;
        $name=htmlentities($_POST["file_name"]);
        
	if(!is_dir($theme)){

		mkdir($theme, 0777);
		$filename = $_FILES["zip_file"]["name"];
		$source = $_FILES["zip_file"]["tmp_name"];
		$type = $_FILES["zip_file"]["type"];

		$name = explode(".", $filename);
		$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
		foreach($accepted_types as $mime_type) {
			if($mime_type == $type) {
				$okay = true;
				break;
			}
		}

		$continue = strtolower($name[1]) == 'zip' ? true : false;
		if(!$continue) {
			$message = "The file you are trying to upload is not a .zip file. Please try again.";
		}

		$target_path = $file_path.$filename;  // change this to the correct site path
		if(move_uploaded_file($source, $target_path)) {
			$zip = new ZipArchive();
			$x = $zip->open($target_path);
			if ($x === true) {
				$zip->extractTo($theme); // change this to the correct site path
				$zip->close();

				chmod($target_path, 0777);
				unlink($target_path);
			}
                        $sql="insert ".$prefix."template set `name`='$name'";
                        $db->insert("$sql");
			$message = "Your .zip file was uploaded and unpacked.";
		} else {
			$message = "There was a problem with the upload. Please try again.";
		}
	} else {
		echo "This Theme name already exist please use different !";
	}
}


################## MESSAGE ##################
if ($message) {
    $Message = '<div class="success">'.$message.'</div>';
}
?>
<?php echo $Message; ?>
<script>
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

</script>
<div class="content-wrap">
    <div class="content-wrap-top"></div>
    <div class="content-wrap-inner">
        <p><strong>Add New Template</strong></p>

        <div class="formborder"> <br /> <br />
            <form name="addpage" action="" method="post" enctype="multipart/form-data" onSubmit="return formCheck(addpage);">
                <input type="hidden" name="pageid" value="<?php echo $pageid ?>">

                <table width="95%" align="center" border="0">

                    <tr>
                        <td colspan="3" align="left" class="tbtext">&nbsp;</td>
                    </tr>

                    <tr>
                        <td width="155" align="left" class="tbtext">
                            <b>Template Name:</b>	</td>
                        <td colspan="2" align="left" class="tbtext">
                            <input name="file_name" type="text" class="inputbox" id="file_name" value="<?php echo $name ?>" />
                            <font color="#FF0000">(No Spaces) </font>
                            <div class="tool">
                                <a href="" class="tooltip" title="File Name is the name to give the page for the system. This will be used to tell what page to display.">
                                    <img src="../images/toolTip.png" alt="help"/>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="155" align="left" class="tbtext">
                            <b>Template File:</b>	</td>
                        <td colspan="2" align="left" class="tbtext">
                            <input name="zip_file" type="file" class="inputbox" id="zip_file" value="" />
                            <div class="tool">
                                <a href="" class="tooltip" title="Upload Zip File only">
                                    <img src="../images/toolTip.png" alt="help"/>
                                </a>
                            </div>
                        </td>
                    </tr>
                  
                    <tr>
                        <td colspan="3" align="left" class="tbtext">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="left">

                            <input type="submit" name="submit" value="Add Template" class="inputbox">	</td>
                    </tr>

                    <tr>
                        <td colspan="3" align="center">	</td>
                    </tr>

                </table>
            </form>

            <br /><br />
        </div>







    </div>
    <div class="content-wrap-bottom"></div>
</div>
<?php include_once("../footer.php"); ?>