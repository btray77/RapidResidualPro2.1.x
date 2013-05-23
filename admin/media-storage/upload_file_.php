<?php 
include_once '../session.php';
include_once '../header.php';
require_once 'config/config.php';
require_once 'common.php';
?>

<?php
    $warnMsg = "";
    if(!is_dir($root_path.$prot_down)){
        $warnMsg .= '<div class="warning"><img src="../images/warning.png" align="absmiddle"> Please create '.$prot_down.' directory via FTP.</div>';
        $configmod = substr(sprintf('%o', fileperms($root_path.$prot_down)), -4);
          if($configmod != '0777')
          {
               if(!chmod($root_path.$prot_down,777))
                                $warnMsg .='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
              $warnMsg.= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> '. $prot_down .'  has  '.$configmod .' permissions. But required Permission is 0777 to access.</div>';
          }
    }

    if(!is_dir($root_path.$swf_down)){
        $warnMsg .= '<div class="warning"><img src="../images/warning.png" align="absmiddle"> Please create '.$swf_down.' directory via FTP.</div>';
        $configmod = substr(sprintf('%o', fileperms($root_path.$swf_down)), -4);
          if($configmod != '0777')
          {
               if(!chmod($root_path.$swf_down,777))
                                $warnMsg .='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
              $warnMsg.= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> '. $swf_down .'  has  '.$configmod .' permissions. But required Permission is 0777 to access.</div>';
          }
    }

    echo $warnMsg;
?>

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo 'Add Media ';?><?php echo ($bucket == 'local')? 'Local Uploads' : $bucket;?></strong></p>

<link type="text/css" href="../managetemplate/css/farbtastic.css" rel="stylesheet">
<script type="text/javascript" src="../managetemplate/js/farbtastic.js"></script>
<script>
$(document).ready(function(){

	$('#content-type').change(function(){
		content_type = $("#content-type").val();

	        $.ajax({
	        type: "POST",
	        url: "upload_ajax_form.php",
	        data: 'content_type='+content_type,
	        dataType: "html",
	        success: function(data) {
	 			
	            if(data != ""){
	                $("#form_div").html(data);
	            } else {
					alert("Unable to Load Form.");	
				} 
	 
	        }
	 
	        });
	 
	        return false;            
	
	});

        $('#link-sec').css('display','none');

        $('#upload-location').change(function(){
		upload_location = $("#upload-location").val();

	        $.ajax({
	        type: "POST",
	        url: "actions/ajaxResponse.php",
	        data: 'upload_location='+upload_location+'&bucket=<?php echo mysql_escape_string($_GET['bucket']); ?>',
	        dataType: "html",
	        success: function(data) {
	 			
	            if(data != ""){
	                $("#link_id").html(data);
	            } else {
					alert("Unable to Load Form.");
				}

	        }

	        });

	        return false;

	});

        // Disable Upload Sec
        //$('#upload-sec').css('display','none');

       /* $var = $('#upload-location').val();
        if($var == 's3'){
            $('#link-upload').css('display','block');
            $('#link-sec').css('display','none');
        }else{
            $('#link-upload').css('display','none');
            $('#link-sec').css('display','block');
        }

        $('#upload-location').change(function(){
        
            if($('#upload-location').val() == 's3'){
                $('#link-upload').css('display','block');
                $('#link-sec').css('display','block');
                
                
            }else{

                $('#link-upload').css('display','block');
                //if($('#link-sec').css('display','block');)
                $('#link-sec').css('display','block');
            }
        });*/
});

$('#graphic_download').css('display', 'none');

function showField(field){
		download = $("input[@name="+field+"]:checked").val();
		alert(download);
		if(download == 'Yes'){
			$('#graphic_download').css('display', 'table-row');
		}
	}
	
function colorPicker(){
	
	$('#demo').hide();
    var f = $.farbtastic('#picker');
    var p = $('#picker').css('opacity', 1);
    var selected;
    $('.colorwell')
      .each(function () { f.linkTo(this); $(this).css('opacity', 1); })
      .focus(function() {
        if (selected) {
          $(selected).css('opacity',1).removeClass('colorwell-selected');
        }
        f.linkTo(this);
        p.css('opacity', 1);
        $(selected = this).css('opacity', 1).addClass('colorwell-selected');
      });

}

function getUploadLink(val){

    if(val =='upload'){
        document.getElementById('upload-sec').style.display='block';
        document.getElementById('link-sec').style.display='none';
    }else if(val =='link'){
        document.getElementById('upload-sec').style.display='none';
        document.getElementById('link-sec').style.display='block';
    }

}
	
</script>


<div class="buttons">
<a href="view_bucket_contents.php?bucket=<?php echo $bucket;?>"> Go back </a>
</div>
<div class="formborder">
<form id="upload_form" method="post" action="actions/content_action.php" enctype="multipart/form-data">
    <input type="hidden" name="bucket_name" id="bucket_name" value="<?php echo mysql_escape_string($_GET['bucket']);?>"/>
        <fieldset>
          <legend>Content Setting</legend>
          <table width="100%" border="0">
               <tr>
                <td width="33%">Title</td>
                <td width="67%"><input type="text" name="title" id="title" /></td>
              </tr>
              <tr>
                <td>Short Name</td>
                <td><input type="text" name="short_name" id="short_name" /></td>
              </tr>
              <tr>
                <td>Upload Location</td>
                <td><select name="upload-location" id="upload-location">
                        <?php
                        if(mysql_escape_string($_GET['bucket'] == 'local')){
                            echo '<option value="local">Local</option>';
                        }else{
                            echo '<option value="s3">Amazon S3</option>';
                        }
                        ?>
                  <!--<option value="local" <?php if(mysql_escape_string($_GET['bucket'] == 'local')){echo 'selected="selected"';}?>>Local</option>-->
                  <!--<option value="s3" <?php if(mysql_escape_string($_GET['bucket'] != 'local')){echo 'selected="selected"';}?>>Amazon S3</option>-->
                </select></td>
              </tr>
              <tr>
                <td>Content Type</td>
                <td><select name="content-type" id="content-type">
                  <option value="" selected="selected" onchange="return colorPicker();">Select the Media Type</option>
                  <option value="video">Video</option>
                  <option value="audio">Audio</option>
                  <option value="file">File</option>
                </select></td>
              </tr>
              <tr>
                <td>Content Access</td>
                <td><input type="radio" name="content_access" id="content_access" value="Public" />
                  Public 
                  <input name="content_access" type="radio" id="content_access" value="Private" checked="checked" />
                  Private</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Description</td>
                <td>
                  <textarea name="description" id="description" cols="45" rows="5"></textarea>
                </td>
              </tr>
              <!--<tr>
                <td>Sales Letter</td>
                <td><textarea name="sales_letter" id="sales_letter" cols="45" rows="5"></textarea></td>
              </tr>
              <tr>
                <td>Sold Letter</td>
                <td><textarea name="sold_letter" id="sold_letter" cols="45" rows="5"></textarea></td>
              </tr>-->
              <tr>
                <td>Keywords</td>
                <td><input type="text" name="keywords" id="keywords" /></td>
              </tr>
          </table>
        </fieldset>
      
	  <div id="form_div" style="display:block;width:100%;float:left"></div>
      <fieldset >
          <legend>Link/Upload File</legend>
          <table width="100%" border="0" align="center">
            <tr>
                <td>&nbsp;</td>
                <td>Please select whether you want to upload new file or link to existing one. </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>
                    Upload
					<input type="radio" name="sel_file" id="sel_file" checked="" value="upload" onclick="javascript:getUploadLink('upload');"/>
                    Link
					<input type="radio" name="sel_file" id="sel_file" value="link" onclick="javascript:getUploadLink('link');"/>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
          </table>
        </fieldset>
     
      <!-- Link Section id="link-sec" -->
     <fieldset > 
          <legend>Link File</legend>
          <table width="100%" border="0" align="center">
            <tr>
                <td>&nbsp;</td>
                <td>Please select the uploaded content from the drop down below, which you want to link.</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>
                    <select name="link_id" id="link_id">
						<option value="0">Select Files</option>
                        <?php

                        if($_GET['bucket']!='local'){
                            $res = $s3->getBucket(mysql_escape_string($_GET['bucket']));
                            foreach($res as $resId){
                                $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $resId['name']);
                                if($ext=='php' || $ext=='js' || $ext!='html'){

                                }else{

                                ?>
                                <option value="<?php echo $resId['name']?>"><?php echo $resId['name'];?></option>
                                <?php
                                }
                                }
                        }else{

                                $mediaArray = scandir($media_upload_dir);
                                foreach($mediaArray as $file){
                                   if(is_file($media_upload_dir.$file)){
                                       $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $file);
                                       if($ext!='php' || $ext!='js' || $ext!='html'){
									   } else{
                                    ?>
                                       <option value="<?php echo $file;?>"><?php echo $ext;?></option>
                                   <?php }}
                               }

                               $docArray = scandir($download_upload_dir);
                                foreach($docArray as $file){
                                   if(is_file($download_upload_dir.$file)){
                                       $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $file);
                                        if($ext!='php' || $ext!='js' || $ext!='html'){ } else{
                                    ?>
                                       <option value="<?php echo $file;?>"><?php echo $file;?></option>
                                   
                                   <?php }}

                               }

                        }
                        ?>
                    </select>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
          </table>
        </fieldset>
      <!-- Link End Here -->
      <!-- Upload Start Here -->
    <fieldset id="upload-sec">
          <legend>Upload File</legend>
          <table width="100%" border="0" align="center">
            <tr>
                <td>&nbsp;</td>
                <td>Please click on browse button to select the file and click on Upload to start upload.</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>
               <br>
               <span class="warning">Only FLV,MP4,MP3 file format is allowed for Audio and Video Contents.</span>
               <br><br>
               
                <input type="file" name="s3file" id="s3file" />
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
          </table>
        </fieldset>
	<input type="submit" name="upload" id="upload" value="Submit" />
          <input type="reset" name="Reset" id="button" value="Reset" />
</form>
</div>

</div>

<div class="content-wrap-bottom"></div>
</div>
<?php include_once '../footer.php';?>

