<?php 
include_once '../session.php';
include_once '../header.php';
require_once 'config/config.php';
require_once 'common.php';
?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo 'Add Media';?></strong></p>

<script>
$(document).ready(function(){
	/*$('#video-form-player').fadeOut();
	$('#video-form-payment').fadeOut();
	$('#audio-form-player').fadeOut();
	$('#audio-form-payment').fadeOut();
	$('#file-form').fadeOut();*/
	$('#video-form-player').css('display', 'none');
	$('#video-form-payment').css('display', 'none');
	$('#audio-form-player').css('display', 'none');
	$('#audio-form-payment').css('display', 'none');
	$('#file-form').css('display', 'none');
	
	$('#content-type').change(function(){
		content_type = $("#content-type").val();
		if(content_type == 'video'){
			$('#video-form-player').css('display', 'table-row');
			$('#video-form-payment').css('display', 'table-row');
			$('#audio-form-player').css('display', 'none');
			$('#audio-form-payment').css('display', 'none');
			$('#file-form').css('display', 'none');
		}
		else if(content_type == 'audio'){
			$('#audio-form-player').css('display', 'table-row');
			$('#audio-form-payment').css('display', 'table-row');
			$('#video-form-player').css('display', 'none');
			$('#video-form-payment').css('display', 'none');
			$('#file-form').css('display', 'none');
		}
		else if(content_type == 'file'){
			$('#file-form').css('display', 'table-row');
			$('#video-form-player').css('display', 'none');
			$('#video-form-payment').css('display', 'none');
			$('#audio-form-player').css('display', 'none');
			$('#audio-form-payment').css('display', 'none');
			
		}
	
	});
});
</script>

<div class="buttons">
<a href="view_bucket_contents.php?bucket=<?php echo $bucket;?>"> Go back </a>
</div>
<div class="formborder">
<form id="upload_form" method="post" action="actions/content_action.php" enctype="multipart/form-data">
    <table width="100%" border="0">
      <tr>
        <td><input type="hidden" name="bucket_name" id="bucket_name" value="<?php echo mysql_escape_string($_GET['bucket']);?>"/></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3">
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
                <td>Content Type</td>
                <td><select name="content-type" id="content-type">
                  <option value="" selected="selected">Select Media Type</option>
                  <option value="video">Video</option>
                  <option value="audio">Audio</option>
                  <option value="file">File</option>
                </select></td>
              </tr>
              <!--<tr>
                <td>Content Access</td>
                <td><input type="radio" name="content_access" id="content_access" value="Public" />
                  Public 
                  <input name="content_access" type="radio" id="content_access" value="Private" checked="checked" />
                  Private</td>
              </tr>-->
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><strong>This file is available to</strong></td>
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
        </fieldset></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
      <tr id="video-form-player">
        <td colspan="3" width="100%"><fieldset>
          <legend>Player Settings</legend>
          <table width="100%" border="0">
            <tr>
                <td width="33%">Auto Play</td>
                <td width="67%"><input type="radio" name="auto_play" id="auto_play" value="Yes" />
                  Yes 
                  <input name="auto_play" type="radio" id="auto_play" value="No" checked="checked" />
                  No</td>
              </tr>
              <tr>
                <td>Show Player Controls</td>
                <td><input type="radio" name="player_controls" id="player_controls" value="Yes" />
                    Yes
                      <input name="player_controls" type="radio" id="player_controls" value="No" checked="checked" />
                    No</td>
              </tr>
              <tr>
                <td>Allow Full Screen</td>
                <td><input type="radio" name="full_screen" id="full_screen" value="Yes" />
                    Yes
                      <input name="full_screen" type="radio" id="full_screen" value="No" checked="checked" />
                    No</td>
              </tr>
              <tr>
                <td>Show Download Link</td>
                <td><input type="radio" name="download_link" id="download_link" value="Yes" />
                    Yes
                      <input name="download_link" type="radio" id="download_link" value="No" checked="checked" />
                    No</td>
              </tr>
              <tr>
                <td>Player Size</td>
                <td><label>
                  Height 
                  <input name="player_height" type="text" id="player_height" size="7" />
                Width 
                <input name="player_width" type="text" id="player_width" size="7" />
                </label></td>
              </tr>
              <tr>
                <td>Select Download Graphic</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Custom Player Color</td>
                <td>
                    <select name="player_color" id="player_color">
                        <option value="Red">Red</option>
                        <option value="Yellow">Yellow</option>
                        <option value="Green">Green</option>
                        <option value="Black">Black</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td>Buffer Time</td>
                <td>
                    <select name="buffer_time" id="buffer_time">
                            <option value="5">5 Sec</option>
                            <option value="10">10 Sec</option>
                            <option value="15">15 Sec</option>
                            <option value="20">20 Sec</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td>Allowing Ripping/Downloading </td>
                <td><input type="radio" name="allow_downloading" id="allow_downloading" value="Yes" />
                    Yes
                      <input name="allow_downloading" type="radio" id="allow_downloading" value="No" checked="checked" />
                    No</td>
              </tr>
          </table>
        </fieldset></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr id="video-form-payment">
        <td colspan="3"><fieldset>
          <legend>Payment Settings</legend>
          <table width="100%" border="0">
            <tr>
                <td width="33%">Charge to view this video</td>
                <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" />
                    Yes
                    <input name="charge_to_view" type="radio" id="charge_to_view" value="No" checked="checked" />
                    No</td>
              </tr>
              <tr>
                <td>Price to view this video</td>
                <td>$
                  <input type="text" name="price_to_view" id="price_to_view" /></td>
              </tr>
              <tr>
                <td>Custom PayPal Button </td>
                <td><input type="file" name="paypal_button" id="paypal_button" /></td>
              </tr>
              <tr>
                <td>Custom Alert Pay Button </td>
                <td><input type="file" name="alertpay_button" id="alertpay_button" /></td>
              </tr>
              <!--<tr>
                <td>Custom Google Checkout Button </td>
                <td><input type="file" name="google_checkout_button" id="google_checkout_button" /></td>
              </tr>
              <tr>
                <td>Custom Click Bank Button </td>
                <td><input type="file" name="clickbank_button" id="clickbank_button" /></td>
              </tr>
              <tr>
                <td>Any other custom fields that should be added  here</td>
                <td>&nbsp;</td> 
              </tr>-->
          </table>
        </fieldset></td>
      </tr>

     
      <tr id="audio-form-player">
        <td colspan="3"><fieldset>
          <legend>Player Settings</legend>
          <table width="100%" border="0">
            <tr>
                <td width="33%">Auto Play</td>
                <td width="67%"><input type="radio" name="auto_play" id="auto_play" value="Yes" />
                  Yes 
                  <input name="auto_play" type="radio" id="auto_play" value="No" checked="checked" />
                  No</td>
              </tr>
              <tr>
                <td>Show Player Controls</td>
                <td><input type="radio" name="player_controls" id="player_controls" value="Yes" />
                    Yes
                      <input name="player_controls" type="radio" id="player_controls" value="No" checked="checked" />
                    No</td>
              </tr>
              <tr>
                <td>Show Download Link</td>
                <td><input type="radio" name="download_link" id="download_link" value="Yes" />
                  Yes
                  <input name="download_link" type="radio" id="download_link" value="No" checked="checked" />
                  No</td>
              </tr>
              <tr>
                <td>Player Size</td>
                <td><label>
                  Height 
                  <input name="player_height" type="text" id="player_height" size="7" />
                Width 
                <input name="player_width" type="text" id="player_width" size="7" />
                </label></td>
              </tr>
              <tr>
                <td>Select Download Graphic</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Custom Player Color</td>
                <td>
                    <select name="player_color" id="player_color">
                        <option value="Red">Red</option>
                        <option value="Yellow">Yellow</option>
                        <option value="Green">Green</option>
                        <option value="Black">Black</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td>Buffer Time</td>
                <td>
                    <select name="buffer_time" id="buffer_time">
                            <option value="5">5 Sec</option>
                            <option value="10">10 Sec</option>
                            <option value="15">15 Sec</option>
                            <option value="20">20 Sec</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td>Allowing Ripping/Downloading </td>
                <td><input type="radio" name="allow_downloading" id="allow_downloading" value="Yes" />
                    Yes
                      <input name="allow_downloading" type="radio" id="allow_downloading" value="No" checked="checked" />
                    No</td>
              </tr>
          </table>
        </fieldset></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr id="audio-form-payment">
        <td colspan="3"><fieldset>
          <legend>Payment Settings</legend>
          <table width="100%" border="0">
            <tr>
                <td width="33%">Charge to listen this audio</td>
                <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" />
                    Yes
                    <input name="charge_to_view" type="radio" id="charge_to_view" value="No" checked="checked" />
                    No</td>
              </tr>
              <tr>
                <td>Price to listen this audio</td>
                <td>$
                  <input type="text" name="price_to_view" id="price_to_view" /></td>
              </tr>
              <tr>
                <td>Custom PayPal Button </td>
                <td><input type="file" name="paypal_button" id="paypal_button" /></td>
              </tr>
              <tr>
                <td>Custom Alert Pay Button </td>
                <td><input type="file" name="alertpay_button" id="alertpay_button" /></td>
              </tr>
              <!--<tr>
                <td>Custom Google Checkout Button </td>
                <td><input type="file" name="google_checkout_button" id="google_checkout_button" /></td>
              </tr>
              <tr>
                <td>Custom Click Bank Button </td>
                <td><input type="file" name="clickbank_button" id="clickbank_button" /></td>
              </tr>
              <tr>
                <td>Any other custom fields that should be added  here</td>
                <td>&nbsp;</td> 
              </tr>-->
          </table>
        </fieldset></td>
      </tr>
      
      <tr id="file-form">
        <td colspan="3"><fieldset>
          <legend>Payment Settings</legend>
          <table width="100%" border="0">
            <tr>
                <td width="33%">Charge to view this File</td>
                <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" />
                    Yes
                    <input name="charge_to_view" type="radio" id="charge_to_view" value="No" checked="checked" />
                    No</td>
              </tr>
              <tr>
                <td>Price to view this File</td>
                <td>$
                  <input type="text" name="price_to_view" id="price_to_view" /></td>
              </tr>
              <tr>
                <td>Custom PayPal Button </td>
                <td><input type="file" name="paypal_button" id="paypal_button" /></td>
              </tr>
              <tr>
                <td>Custom Alert Pay Button </td>
                <td><input type="file" name="alertpay_button" id="alertpay_button" /></td>
              </tr>
              <!--<tr>
                <td>Custom Google Checkout Button </td>
                <td><input type="file" name="google_checkout_button" id="google_checkout_button" /></td>
              </tr>
              <tr>
                <td>Custom Click Bank Button </td>
                <td><input type="file" name="clickbank_button" id="clickbank_button" /></td>
              </tr>
              <tr>
                <td>Any other custom fields that should be added  here</td>
                <td>&nbsp;</td> 
              </tr>-->
          </table>
        </fieldset></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><fieldset>
          <legend>Upload File</legend>
          <table width="100%" border="0" align="center">
            <tr>
                <td>&nbsp;</td>
                <td>Please click on browse button to select the file and click on Upload to start upload.</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="file" name="s3file" id="s3file" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
          </table>
        </fieldset></td>
      </tr>    
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="center"><input type="submit" name="upload" id="upload" value="Submit" />
          <input type="reset" name="Reset" id="button" value="Reset" />
        <strong>Click  Submit to Get Token Now!</strong></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
</form>
</div>

</div>

<div class="content-wrap-bottom"></div>
</div>
<?php include_once '../footer.php';?>

