<?php 

include_once '../session.php';

include_once '../header.php';

require_once 'config/config.php';

require_once 'common.php';

$query = "SELECT * FROM ".$prefix."amazon_s3 WHERE id = '".mysql_escape_string($_GET['C_id'])."'";

$result = $db->get_a_line($query);

?>

<link type="text/css" href="../managetemplate/css/farbtastic.css" rel="stylesheet">

<link href="/facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css"/>

<script src="/facebox/src/facebox.js" type="text/javascript"></script>

<!--<script type="text/javascript" src="../managetemplate/js/farbtastic.js"></script>-->

<script type="text/javascript" charset="utf-8">

  /*$(document).ready(function() {

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

	  

	/*$('a[rel*=facebox]').facebox({

        loadingImage : '/facebox/src/loading.gif',

        closeImage   : '/facebox/src/closelabel.png'

      })*/







	  

  /*});*/

</script>

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

    <p><strong><?php echo 'Edit Media ';?><?php echo ($bucket == 'local')?  'Local Uploads' : $bucket;?></strong></p>

    <div class="buttons"> <a href="view_bucket_contents.php?bucket=<?php echo $bucket;?>"> Go back </a> </div>

    <div class="formborder">

      <form id="edit_upload_form" method="post" action="actions/content_action.php" enctype="multipart/form-data">

        <table width="100%" border="0" cellpadding="5" cellspacing="2">

          <input type="hidden" name="bucket_type" value="<?php echo $_GET['bucket'];?>"/>

          <tr>

            <td><input type="hidden" name="bucket_name" id="bucket_name" value="<?php echo $result['bucket_id'];?>"/>

              <input type="hidden" name="content_id" id="content_id" value="<?php echo $result['content_id'];?>"/>

              <input type="hidden" name="id" id="id" value="<?php echo $result['id'];?>"/>

            </td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

          </tr>

          <tr>

            <td colspan="3"><fieldset>

              <legend>Content Setting</legend>

              <table width="100%" border="0">

                <tr>

                  <td width="33%">Title</td>

                  <td width="67%"><input type="text" name="title" id="title" value="<?php echo $result['title'];?>" /></td>

                </tr>

                <tr>

                  <td>Short Name</td>

                  <td><input name="short_name" type="text" id="short_name" value="<?php echo $result['short_name'];?>" /></td>

                </tr>

                <!--<tr>

                <td>Custom Token</td>

                <td><input name="custom_token" type="text" id="custom_token" value="<?php echo $result['custom_token'];?>" /></td>

              </tr>-->

                <tr>

                  <td>Content Access</td>

                  <td><input type="radio" name="content_access" id="content_access" value="Public" <?php echo ($result['content_access'] == 'Public')? 'checked="checked"' : ''; ?> />

                    Public

                    <input name="content_access" type="radio" id="content_access" value="Private" <?php echo ($result['content_access'] == 'Private')? 'checked="checked"' : ''; ?>/>

                    Private</td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

                <tr>

                  <td>Description</td>

                  <td><textarea name="description" id="description" cols="45" rows="5"><?php echo $result['description_page'];?></textarea>

                  </td>

                </tr>

                <tr>

                  <td>Keywords</td>

                  <td><input name="keywords" type="text" id="keywords" value="<?php echo $result['keywords'];?>" /></td>

                </tr>

              </table>

              </fieldset></td>

          </tr>

          <tr>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

          </tr>

          <?php 

	  $content_type = explode('_', $result['custom_token']);

	  if($content_type[0] == 'video'){?>

          <tr>

          

          <td colspan="3">

          <fieldset>

          <legend>Player Settings</legend>

          <table width="100%" border="0">

            <tr>

              <td>Player Size</td>

              <td>Height <span>

                <input name="player_height" type="text" id="player_height" size="7" value="<?php echo (!empty($result['player_height']))? $result['player_height'] :  '300'; ?>" />

                </span> Width <span>

                <input name="player_width" type="text" id="player_width" size="7" value="<?php echo (!empty($result['player_width']))? $result['player_width'] :  '500'; ?>" />

                </span> </td>

              <!-- Color Picker TD-->

              <!--<td width="67%" rowspan="9">

                <h3>Avaliable Colors</h3>

                <div class="tokens" style="width:auto">

                     <div id="picker" style="float: right;"></div>

                </div>-->

            </td>

            

            </tr>

            

            <tr>

              <td width="33%">Auto Play</td>

              <td width="67%"><input type="radio" name="auto_play" id="auto_play" value="Yes" <?php echo ($result['auto_play'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                Yes

                <input name="auto_play" type="radio" id="auto_play" value="No" <?php echo ($result['auto_play'] == 'No')? 'checked="checked"' : ''; ?> />

                No</td>

            </tr>

            <tr>

              <td>Show Player Controls</td>

              <td><input type="radio" name="player_controls" id="player_controls" value="Yes" <?php echo ($result['player_controls'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                Yes

                <input name="player_controls" type="radio" id="player_controls" value="No" <?php echo ($result['player_controls'] == 'No')? 'checked="checked"' : ''; ?> />

                No</td>

            </tr>

            <tr>

              <td>Allow Full Screen</td>

              <td><input type="radio" name="full_screen" id="full_screen" value="Yes" <?php echo ($result['full_screen'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                Yes

                <input name="full_screen" type="radio" id="full_screen" value="No" <?php echo ($result['full_screen'] == 'No')? 'checked="checked"' : ''; ?> />

                No</td>

            </tr>

            <tr>

              <td>Show Download Link</td>

              <td><input type="radio" name="download_link" id="download_link" value="Yes" <?php echo ($result['download_link'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                Yes

                <input name="download_link" type="radio" id="download_link" value="No" <?php echo ($result['download_link'] == 'No')? 'checked="checked"' : ''; ?> />

                No</td>

            </tr>

            <tr>

              <td>Select Download Graphic</td>

              <td><input type="file" name="download_button" id="download_button" />

                <?php //if($result['alert_pay_button'] != ""){

                   // echo $image_url.'/images/uploads/'.$result['download_graphic'];exit;

                    ?>

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['download_graphic']?>" height="32" align="absmiddle"/>

                <?php //}?>

              </td>

            </tr>

            <tr>

              <td>Custom Player Color</td>

              <td><select name="player_color">

                  <option value="#FFFFFF" <?php if($result['player_color'] == 'FFFFFF') echo 'selected'; ?>>White</option>

                  <option value="#FF0000" <?php if($result['player_color'] == '#FF0000') echo 'selected'; ?>>Red</option>

                  <option value="#800517" <?php if($result['player_color'] == '#800517') echo 'selected'; ?>>Fire Brick</option>

                  <option value="#0000FF" <?php if($result['player_color'] == '#0000FF') echo 'selected'; ?>>Light Blue</option>

                  <option value="#FFFF00" <?php if($result['player_color'] == '#FFFF00') echo 'selected'; ?>>Yellow</option>

                  <option value="#00FF00" <?php if($result['player_color'] == '#00FF00') echo 'selected'; ?>>Light Green</option>

                  <option value="#00FFFF" <?php if($result['player_color'] == '#00FFFF') echo 'selected'; ?>>Turquoise</option>

                  <option value="#FDD017" <?php if($result['player_color'] == '#FDD017') echo 'selected'; ?>>Golden</option>

                  <option value="#666666" <?php if($result['player_color'] == '#666666') echo 'selected'; ?>>Gray</option>

                </select>

                <!--<input type="text" id="player_color" name="player_color" class="colorwell" value="<?php if(!$result['player_color']) echo '#000000'; else  echo $result['player_color']?>" />-->

              </td>

            </tr>

            <!--<tr>

                <td>Buffer Time</td>

                <td>

                  <select name="buffer_time" id="buffer_time">

                    <option value="5" <?php echo ($result['buffer_time'] == '5')? 'selected="selected"' : ''; ?>>5 Sec</option>

                    <option value="10" <?php echo ($result['buffer_time'] == '10')? 'selected="selected"' : ''; ?>>10 Sec</option>

                    <option value="15" <?php echo ($result['buffer_time'] == '15')? 'selected="selected"' : ''; ?>>15 Sec</option>

                    <option value="20" <?php echo ($result['buffer_time'] == '20')? 'selected="selected"' : ''; ?>>20 Sec</option>

                    </select>

                </td>

                </tr>-->

            <!--<tr>

                <td>Allowing Ripping/Downloading </td>

                <td><input type="radio" name="allow_downloading" id="allow_downloading" value="Yes" <?php echo ($result['allow_ripping_downloading'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                  Yes

                  <input name="allow_downloading" type="radio" id="allow_downloading" value="No" <?php echo ($result['allow_ripping_downloading'] == 'No')? 'checked="checked"' : ''; ?> />

                  No</td>

                </tr>-->

          </table>

          </fieldset>

          </td>

          

          </tr>

          

          <tr>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

          </tr>

          <!--Payment Settings Commented-->

          <!--<tr>

        <td colspan="3"><fieldset>

          <legend>Payment Settings</legend>

          <table width="100%" border="0">

          <tr>

                <td width="33%">Charge to view this video</td>

                <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" <?php echo ($result['charge_to_view'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                    Yes

                    <input name="charge_to_view" type="radio" id="charge_to_view" value="No" <?php echo ($result['charge_to_view'] == 'No')? 'checked="checked"' : ''; ?> />

                    No</td>

              </tr>

              Price to View Video

              <tr>

                <td>Price to view this video</td>

                <td>$

                  <input name="price_to_view" type="text" id="price_to_view" value="<?php echo $result['price_to_view'];?>" /></td>

              </tr>

              <tr>

                <td>Custom PayPal Button </td>

                <td><input type="file" name="paypal_button" id="paypal_button" />

                 <?php if($result['paypal_button'] != ""){?>

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['paypal_button']?>"  height="32" align="absmiddle"/>

                <?php }?>

                </td>

              </tr>

              <tr>

                <td>Custom Alert Pay Button </td>

                <td><input type="file" name="alertpay_button" id="alertpay_button" />

                <?php if($result['alert_pay_button'] != ""){?>

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['alert_pay_button']?>"  height="32" align="absmiddle"/>

                <?php }?>

                </td>

              </tr>

              <tr>

                <td>Custom Google Checkout Button </td>

                <td><input type="file" name="google_checkout_button" id="google_checkout_button" />

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['google_checkout_button']?>"  height="32" align="absmiddle" alt="Google Checkout Button" />

                </td>

              </tr>

              <tr>

                <td>Custom Click Bank Button </td>

                <td><input type="file" name="clickbank_button" id="clickbank_button" />

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['clickbank_button']?>"  height="32" align="absmiddle" alt="Click Bank Button" />

                </td>

              </tr>

              <tr>

                <td>Any other custom fields that should be added  here</td>

                <td>&nbsp;</td> 

              </tr>

          </table>

        </fieldset></td>

      </tr>-->

          <?php }elseif($content_type[0] == 'audio'){?>

          <tr>

            <td colspan="3"><fieldset>

              <legend>Player Settings</legend>

              <table width="100%" border="0">

                <tr>

                  <td>Player Size</td>

                  <td><label> Height

                    <input name="player_height" type="text" id="player_height" size="7" value="<?php echo (!empty($result['player_height']))? $result['player_height'] :  '24'; ?>" />

                    Width

                    <input name="player_width" type="text" id="player_width" size="7" value="<?php echo (!empty($result['player_width']))? $result['player_width'] :  '500'; ?>" />

                    </label></td>

                  <!-- Color Picker-->

                  <!--<td width="67%" rowspan="8">

                 <h3>Avaliable Colors</h3>

                    <div class="tokens" style="width:auto">

                         <div id="picker" style="float: right;"></div>

                    </div>

                </td>-->

                </tr>

                <tr>

                  <td width="33%">Auto Play</td>

                  <td width="67%"><input type="radio" name="auto_play" id="auto_play" value="Yes" <?php echo ($result['auto_play'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                    Yes

                    <input name="auto_play" type="radio" id="auto_play" value="No" <?php echo ($result['auto_play'] == 'No')? 'checked="checked"' : ''; ?> />

                    No</td>

                </tr>

                <tr>

                  <td>Show Player Controls</td>

                  <td><input type="radio" name="player_controls" id="player_controls" value="Yes" <?php echo ($result['player_controls'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                    Yes

                    <input name="player_controls" type="radio" id="player_controls" value="No" <?php echo ($result['player_controls'] == 'No')? 'checked="checked"' : ''; ?> />

                    No</td>

                </tr>

                <tr>

                  <td>Show Download Link</td>

                  <td><input type="radio" name="download_link" id="download_link" value="Yes" <?php echo ($result['download_link'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                    Yes

                    <input name="download_link" type="radio" id="download_link" value="No" <?php echo ($result['download_link'] == 'No')? 'checked="checked"' : ''; ?> />

                    No</td>

                </tr>

                <tr>

                  <td>Select Download Graphic</td>

                  <td><input type="file" name="download_button" id="download_button" />

                    <?php if($result['download_graphic'] != ""){?>

                    <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['download_graphic']?>" height="32" align="absmiddle"/>

                    <?php }?>

                  </td>

                </tr>

                <tr>

                  <td>Custom Player Color</td>

                  <td><select name="player_color">

                      <option value="#FFFFFF" <?php if($result['player_color'] == 'FFFFFF') echo 'selected'; ?>>White</option>

                      <option value="#FF0000" <?php if($result['player_color'] == '#FF0000') echo 'selected'; ?>>Red</option>

                      <option value="#800517" <?php if($result['player_color'] == '#800517') echo 'selected'; ?>>Fire Brick</option>

                      <option value="#0000FF" <?php if($result['player_color'] == '#0000FF') echo 'selected'; ?>>Light Blue</option>

                      <option value="#FFFF00" <?php if($result['player_color'] == '#FFFF00') echo 'selected'; ?>>Yellow</option>

                      <option value="#00FF00" <?php if($result['player_color'] == '#00FF00') echo 'selected'; ?>>Light Green</option>

                      <option value="#00FFFF" <?php if($result['player_color'] == '#00FFFF') echo 'selected'; ?>>Turquoise</option>

                      <option value="#FDD017" <?php if($result['player_color'] == '#FDD017') echo 'selected'; ?>>Golden</option>

                      <option value="#666666" <?php if($result['player_color'] == '#666666') echo 'selected'; ?>>Gray</option>

                    </select>

                    <!--<input type="text" id="player_color" name="player_color" class="colorwell" value="<?php if(!$result['player_color']) echo '#000000'; else  echo $result['player_color']?>" />-->

                  </td>

                </tr>

                <!--<tr>

                <td>Buffer Time</td>

                <td>

                  <select name="buffer_time" id="buffer_time">

                    <option value="5" <?php echo ($result['buffer_time'] == '5')? 'selected="selected"' : ''; ?>>5 Sec</option>

                    <option value="10" <?php echo ($result['buffer_time'] == '10')? 'selected="selected"' : ''; ?>>10 Sec</option>

                    <option value="15" <?php echo ($result['buffer_time'] == '15')? 'selected="selected"' : ''; ?>>15 Sec</option>

                    <option value="20" <?php echo ($result['buffer_time'] == '20')? 'selected="selected"' : ''; ?>>20 Sec</option>

                    </select>

                </td>

                </tr>-->

                <!--<tr>

                <td>Allowing Ripping/Downloading </td>

                <td><input type="radio" name="allow_downloading" id="allow_downloading" value="Yes" <?php echo ($result['allow_ripping_downloading'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                  Yes

                  <input name="allow_downloading" type="radio" id="allow_downloading" value="No" <?php echo ($result['allow_ripping_downloading'] == 'No')? 'checked="checked"' : ''; ?> />

                  No</td>

                </tr>-->

              </table>

              </fieldset></td>

          </tr>

          <tr>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

          </tr>

          <!--Payment Settings Commented-->

          <!--<tr>

        <td colspan="3"><fieldset>

          <legend>Payment Settings</legend>

          <table width="100%" border="0">

          <tr>

                <td width="33%">Charge to listen this audio</td>

                <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" <?php echo ($result['charge_to_view'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                    Yes

                    <input name="charge_to_view" type="radio" id="charge_to_view" value="No" <?php echo ($result['charge_to_view'] == 'No')? 'checked="checked"' : ''; ?> />

                    No</td>

              </tr>

              Price to Listen Audio

              <tr>

                <td>Price to listen this audio</td>

                <td>$

                  <input name="price_to_view" type="text" id="price_to_view" value="<?php echo $result['price_to_view'];?>" /></td>

              </tr>

              <tr>

                <td>Custom PayPal Button </td>

                <td><input type="file" name="paypal_button" id="paypal_button" />

                 <?php if($result['paypal_button'] != ""){?>

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['paypal_button']?>"  height="32" align="absmiddle"/>

                <?php }?>

                </td>

              </tr>

              <tr>

                <td>Custom Alert Pay Button </td>

                <td><input type="file" name="alertpay_button" id="alertpay_button" />

                 <?php if($result['alert_pay_button'] != ""){?>

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['alert_pay_button']?>"  height="32" align="absmiddle"/>

                <?php }?>

                </td>

              </tr>

              <tr>

                <td>Custom Google Checkout Button </td>

                <td><input type="file" name="google_checkout_button" id="google_checkout_button" />

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['google_checkout_button']?>"  height="32" align="absmiddle" alt="Google Checkout Button" />

                </td>

              </tr>

              <tr>

                <td>Custom Click Bank Button </td>

                <td><input type="file" name="clickbank_button" id="clickbank_button" />

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['clickbank_button']?>"  height="32" align="absmiddle" alt="Click Bank Button" />

                </td>

              </tr>

              <tr>

                <td>Any other custom fields that should be added  here</td>

                <td>&nbsp;</td> 

              </tr>

          </table>

        </fieldset></td>

      </tr>-->

          <?php }elseif($content_type[0] == 'file'){?>

          <!--Payment Settings Commented-->

          <tr>

            <td colspan="3"><fieldset>

              <legend>Payment Settings</legend>

              <table width="100%" border="0">

                <!--<tr>

                <td width="33%">Charge to view this file</td>

                <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" <?php echo ($result['charge_to_view'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                    Yes

                    <input name="charge_to_view" type="radio" id="charge_to_view" value="No" <?php echo ($result['charge_to_view'] == 'No')? 'checked="checked"' : ''; ?> />

                    No</td>

              </tr>

              Price to View Video

              <tr>

                <td>Price to view this file</td>

                <td>$

                  <input name="price_to_view" type="text" id="price_to_view" value="<?php echo $result['price_to_view'];?>" /></td>

              </tr>-->

                <tr>

                  <td>Show Download Link</td>

                  <td><input type="radio" name="download_link" id="download_link" value="Yes" <?php echo ($result['download_link'] == 'Yes')? 'checked="checked"' : ''; ?>/>

                    Yes

                    <input name="download_link" type="radio" id="download_link" value="No" <?php echo ($result['download_link'] == 'No')? 'checked="checked"' : ''; ?> />

                    No</td>

                </tr>

                <tr id="graphic_download">

                  <td>Select Download Graphic</td>

                  <td><input type="file" name="download_button" id="download_button" />

                    <?php if($result['download_graphic'] != ""){?>

                    <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['download_graphic']?>"  height="32" align="absmiddle"/>

                    <?php } ?>

                  </td>

                </tr>

                <!--<tr>

                <td>Custom PayPal Button </td>

                <td><input type="file" name="paypal_button" id="paypal_button" />

                <?php if($result['paypal_button'] != ""){?>

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['paypal_button']?>" height="32" align="absmiddle"/>

                <?php }?>

                </td>

              </tr>

              <tr>

                <td>Custom Alert Pay Button </td>

                <td><input type="file" name="alertpay_button" id="alertpay_button" />

                <?php if($result['alert_pay_button'] != ""){?>

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['alert_pay_button']?>" height="32" align="absmiddle"/>

                <?php }?>

                </td>

              </tr>

              <tr>

                <td>Custom Google Checkout Button </td>

                <td><input type="file" name="google_checkout_button" id="google_checkout_button" />

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['google_checkout_button']?>" height="32" align="absmiddle" alt="Google Checkout Button" />

                </td>

              </tr>

              <tr>

                <td>Custom Click Bank Button </td>

                <td><input type="file" name="clickbank_button" id="clickbank_button" />

                <img name="" src="<?php echo $image_url.'/images/uploads/'.$result['clickbank_button']?>"  height="32" align="absmiddle" alt="Click Bank Button" />

                </td>

              </tr>

              <tr>

                <td>Any other custom fields that should be added  here</td>

                <td>&nbsp;</td> 

              </tr>-->

              </table>

              </fieldset></td>

          </tr>

          <?php

	  }

	  ?>

          <tr>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

          </tr>

          <!-- Removed preview form Documents -->

          <?php

      $type = explode('_',$result['custom_token']);

                if($type[0] == 'video'){



        ?>

          <tr>

            <td colspan="3"><fieldset>

              <legend>Upload File</legend>

              <table width="100%" border="0" align="center">

                <tr>

                  <td>&nbsp;</td>

                  <td>Bucket Name : <?php echo ($_GET['bucket'] == 'local')? 'Local Uploads': $result['bucket_id'];?></td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td>Content URL :

                    <script>

                $(document).ready(function(){

					   $('#preview_a').click(function(){



							$('#show_mediaspace'). fadeIn('slow');



							});

						$('#close').click(function(){



							$('#show_mediaspace'). fadeOut('slow');

							});

                });

				</script>

                    <?php

					if($_GET['bucket'] == 'local'){
						$file = $media_upload_url.$result['content_id'];
					}else{
						//$file = S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600);
                       $file = "https://s3.amazonaws.com/" . $result['bucket_id'] . '/' . $result['content_id'];
					}

					  $full_screen = ($result['full_screen'] == 'Yes') ? 'true' : 'false';

					if ($result['player_controls'] == 'Yes') {

					$player_controls = "

					controls: {

					backgroundColor: '" . $result['player_color'] . "',

					fullscreen: " . $full_screen . ",

					

					}

					";

					} else 

					{

					$player_controls = "controls: null";

					}



					

					?>

                    <a href="javascript:" id="preview_a">Preview</a>

					<p>

					<small>Note: Width and Height of the player is fixed, Width and Height of the player is only applicable on front-end of the application.</small>

					</p>

                    <div id="show_mediaspace" style="margin: 0pt auto; display: block; width: 550px; background-color: #f4f4f4; border: 1px solid #c4c4c4; padding: 12px; padding-bottom: 20px;"> <span id="close" style="float:right">

					<img src="/images/admin/delete.gif" style="cursor:pointer"></span>

                      <center>

                        <script src='http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js'></script>

                        <a class='rtmp' href='<?php echo $file;?>' style='display:block;width:<?php //echo $result['player_width']; ?>550px;height:<?php //echo $result['player_height']; ?>400px;'></a>

                        <script type='text/javascript'>

								flowplayer('a.rtmp', 'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', {

									key: '77cfdec6a8b2ac9cb19',

									clip: {autoPlay: <?php echo ($result['auto_play'] == 'Yes') ? 'true' : 'false'; ?>},

									plugins: {

										controls: {

											autoPlay: <?php echo ($result['auto_play'] == 'Yes') ? 'true' : 'false'; ?>,

											autoBuffering: false,

											bufferLength: '<?php echo $result['buffer_time']; ?>' ,

											backgroundColor: '<?php echo $result['player_color']; ?>',

											scaling: 'fit',

											autoHide: 'never',

											backgroundGradient: 'low',

											fullscreen: <?php echo ($result['full_screen'] == 'Yes') ? 'true' : 'false'; ?>,

											},

									}

							   });



                         </script>

                      </center>

                    </div>

                <tr>

                  <td></td>

                  <td>&nbsp;</td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

              </table>

              </fieldset></td>

          </tr>

          <?php

        }

                elseif($type[0] == 'audio'){



        ?>

          <tr>

            <td colspan="3"><fieldset>

              <legend>Upload File</legend>

              <table width="100%" border="0" align="center">

                <tr>

                  <td>&nbsp;</td>

                  <td>Bucket Name : <?php echo ($_GET['bucket'] == 'local')? 'Local Uploads': $result['bucket_id'];?></td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td>Content URL :

                    <script>

                $(document).ready(function(){

					   $('#preview_a').click(function(){
							$('#show_mediaspace'). fadeIn('slow');
							});
						$('#close').click(function(){



							$('#show_mediaspace'). fadeOut('slow');

							});

                });

				</script>

                    <?php

				if($_GET['bucket'] == 'local'){

								$file = $media_upload_url.$result['content_id'];

						}else{

							//$file = S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600);

                           	$file = "https://s3.amazonaws.com/" . $result['bucket_id'] . '/' . $result['content_id'];	                             

						}

						

						if ($result['player_controls'] == 'Yes') {

						$player_controls = "

						controls: {

						backgroundColor: '" . $result['player_color'] . "',

						fullscreen: false,

						

						}

						";

						} else 

						{

						$player_controls = "controls: null";

						}



						

						?>

                    <a href="javascript:" id="preview_a">Preview </a>

                    <div id="show_mediaspace" style="margin: 0pt auto; display: block; width: <?php echo $result['player_width']; ?>px; background-color: #f4f4f4; border: 1px solid #c4c4c4; padding: 12px; padding-bottom: 20px;"> <a class='rtmpaudio' href='<?php echo $file;?>' style='display:block;width:<?php echo $result['player_width']; ?>px;height:<?php echo $result['player_height']; ?>px;'></a>

                      <script src='http://www.rapidresidualpro.com/flowplayer/example/flowplayer-3.2.6.min.js'></script>

                      <script type='text/javascript'>

					  	

						  flowplayer('a.rtmpaudio', 'http://www.rapidresidualpro.com/flowplayer/flowplayer-3.2.7.swf', {

						  	key: '77cfdec6a8b2ac9cb19',

							clip: {

							    autoPlay: <?php echo ($result['auto_play'] == 'Yes') ? 'true' : 'false'; ?>,

								autoBuffering: false,

								bufferLength: '<?php echo $result['buffer_time']; ?>'

								},



									plugins: {

										audio: {

											url: '/flowplayer/flowplayer.audio-3.2.2.swf',

										},

										controls: {

											autoPlay: <?php echo ($result['auto_play'] == 'Yes') ? 'true' : 'false'; ?>,

											autoBuffering: false,

											bufferLength: '<?php echo $result['buffer_time']; ?>' ,

											backgroundColor: '<?php echo $result['player_color']; ?>',

											scaling: 'fit',

											autoHide: 'never',

											backgroundGradient: 'low',

											fullscreen: <?php echo ($result['full_screen'] == 'Yes') ? 'true' : 'false'; ?>,

											},

										}



							});



                                                  </script>

                    </div>

                    <!--<tr>

                                    <td>&nbsp;</td>

                                    <td><a href="<?php echo ($_GET['bucket'] == 'local')? $local_upload_url.$result['content_id'] : S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600);?>"><img name="" src="<?php echo ($_GET['bucket'] == 'local')? $local_upload_url.$result['content_id'] : S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600);?>"  height="32" alt="Preview" border="0" /></a></td>

                                  </tr>-->

                <tr>

                  <td></td>

                  <td>&nbsp;</td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

              </table>

              </fieldset></td>

          </tr>

          <?php

        }

                elseif($type[0] == 'file'){



        ?>

          <tr>

            <td colspan="3"><fieldset>

              <legend>Upload File</legend>

              <table width="100%" border="0" align="center">

                <tr>

                  <td>&nbsp;</td>

                  <td>Bucket Name : <?php echo ($_GET['bucket'] == 'local')? 'Local Uploads': $result['bucket_id'];?></td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td>Preview :

                    <?php

				if($_GET['bucket'] == 'local'){
						$file = $media_upload_url.$result['content_id'];
					}else{
						//$file = S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600);
                       $file = "https://s3.amazonaws.com/" . $result['bucket_id'] . '/' . $result['content_id'];
					}


				if(!empty($result['download_graphic'])){		

						?>

						

                    <a href="<?php echo $file;?>" id="preview_a"><img name="" src="<?php echo $image_url.'/images/uploads/'.$result['download_graphic']?>"  height="32" align="absmiddle" alt="download"/></a>

					<?php } else{?>

					<a href="<?php echo $file;?>" id="preview_a">Download Document File</a>

					<?php }?>

					

                    <!--<tr>

                                    <td>&nbsp;</td>

                                    <td><a href="<?php echo ($_GET['bucket'] == 'local')? $local_upload_url.$result['content_id'] : S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600);?>"><img name="" src="<?php echo ($_GET['bucket'] == 'local')? $local_upload_url.$result['content_id'] : S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600);?>"  height="32" alt="Preview" border="0" /></a></td>

                                  </tr>-->

                <tr>

                  <td></td>

                  <td>&nbsp;</td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

              </table>

              </fieldset></td>

          </tr>

          <?php

        }

	?>

          <!-- End Here-->

          <tr>

            <td colspan="3">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="3" align="center"><input type="submit" name="edit" id="edit" value="Submit" />

            </td>

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

