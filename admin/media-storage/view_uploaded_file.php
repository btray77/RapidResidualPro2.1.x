<?php
include_once '../session.php';
include_once '../header.php';
require_once 'config/config.php';
require_once 'common.php';

$query = "SELECT * FROM " . $prefix . "amazon_s3 WHERE id = '" . mysql_escape_string($_GET['C_id']) . "'";
$result = $db->get_a_line($query);
$content_type = explode('_', $result['custom_token']);
?>
<style>
    .pp_inline clearfix{text-align:center}
</style>
<div class="content-wrap">
  <div class="content-wrap-top"></div>
  <div class="content-wrap-inner">
    <p><strong><?php echo 'View Media '; ?><?php echo ($result['bucket_id'] == 'local') ? 'Local Uploads' : $result['bucket_id']; ?></strong></p>
    <div class="buttons"> <a href="view_bucket_contents.php?bucket=<?php echo $result['bucket_id']; ?>">Go Back</a> </div>
    <div class="formborder">
      <form id="edit_upload_form" method="post" action="actions/content_action.php" enctype="multipart/form-data">
        <fieldset>
        <legend>Content Setting</legend>
        <table width="100%" border="0">
          <tr>
            <td width="33%">Title</td>
            <td width="67%"><?php echo $result['title']; ?></td>
          </tr>
          <tr>
            <td>Short Name</td>
            <td><?php echo $result['short_name']; ?></td>
          </tr>
          <tr>
            <td>Custom Token</td>
            <td><?php echo $result['custom_token']; ?></td>
          </tr>
          <!--<tr>
                                      <td>Content Access</td>
                                      <td><?php echo $result['content_access']; ?></td>
                                    </tr>-->
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Description</td>
            <td><?php echo $result['description_page']; ?> </td>
          </tr>
          <!--<tr>
                                      <td>Sales Letter</td>
                                      <td><?php echo $result['sales_page']; ?></td>
                                    </tr>
                                    <tr>
                                      <td>Sold Letter</td>
                                      <td><?php echo $result['sold_page']; ?></td>
                                    </tr>-->
          <tr>
            <td>Keywords</td>
            <td><?php echo $result['keywords']; ?></td>
          </tr>
        </table>
        </fieldset>
        <?php if ($content_type[0] == 'video') {
                    ?>
        <fieldset>
        <legend>Player Settings</legend>
        <table width="100%" border="0" cellpadding="2" cellspacing="1">
          <tr>
            <td>Player Size</td>
            <td><label> Height
              <input name="player_height" type="text" id="player_height" size="7" value="<?php echo $result['player_height']; ?>" disabled="disabled"/>
              Width
              <input name="player_width" type="text" id="player_width" size="7" value="<?php echo $result['player_width']; ?>" disabled="disabled"/>
              </label></td>
          </tr>
          <tr>
            <td width="33%">Auto Play</td>
            <td width="67%"><?php echo $result['auto_play']; ?></td>
          </tr>
          <tr>
            <td>Show Player Controls</td>
            <td><?php echo $result['player_controls']; ?></td>
          </tr>
          <tr>
            <td>Allow Full Screen</td>
            <td><?php echo $result['full_screen']; ?></td>
          </tr>
          <tr>
            <td>Show Download Link</td>
            <td><?php echo $result['download_link']; ?></td>
          </tr>
          <?php if ($result['download_graphic'] != "") {
                                    ?>
          <tr>
            <td>Download Graphic</td>
            <td><img name="" src="<?php echo $image_url . '/images/uploads/' . $result['download_graphic'] ?>" width="" height="32" alt="Download Button" /></td>
          </tr>
          <?php } ?>
          <tr>
            <td>Custom Player Color</td>
            <td><?php echo $result['player_color']; ?> </td>
          </tr>
          <!--<tr>
                                          <td>Allowing Ripping/Downloading </td>
                                          <td><?php echo $result['allow_ripping_downloading']; ?></td>
                                        </tr>-->
        </table>
        </fieldset>
        <!--Payment Settings Commented-->
        <!--</tr>
                            <tr>
                            <td colspan="3"><fieldset>
                                <legend>Payment Settings</legend>
                                <table width="100%" border="0" cellpadding="2" cellspacing="1">
                                <tr>
                                      <td width="33%">Charge to view this video</td>
                                      <td width="67%"><?php echo $result['charge_to_view']; ?></td>
                                    </tr>
                                    <tr>
                                      <td>Price to view this video</td>
                                      <td>$<?php echo $result['price_to_view']; ?></td>
                                    </tr>
                        <?php if ($result['paypal_button'] != "") {
                        ?>
                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                              <td>Custom PayPal Button </td>
                                                                                                                                                                                                                                                              <td>

                                                                                                                                                                                                                                                              <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['paypal_button'] ?>"  height="32" alt="PayPal Button" />

                                                                                                                                                                                                                                                              </td>
                                                                                                                                                                                                                                                            </tr>
                        <?php } ?>
                        <?php if ($result['alert_pay_button'] != "") {
                        ?>
                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                              <td>Custom Alert Pay Button </td>
                                                                                                                                                                                                                                                              <td>
                                                                                                                                                                                                                                                              <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['alert_pay_button'] ?>"  height="32" alt="AlertPay Button" />
                                                                                                                                                                                                                                                              </td>
                                                                                                                                                                                                                                                            </tr>
                        <?php } ?>
                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                          <td>Custom Google Checkout Button </td>
                                                                                                                                                                                                                          <td>
                                                                                                                                                                                                                          <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['google_checkout_button'] ?>"  height="32" alt="Google Checkout Button" />
                                                                                                                                                                                                                          </td>
                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                          <td>Custom Click Bank Button </td>
                                                                                                                                                                                                                          <td>
                                                                                                                                                                                                                          <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['clickbank_button'] ?>"  height="32" alt="Click Bank Button" />
                                                                                                                                                                                                                          </td>
                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                          <td>Any other custom fields that should be added  here</td>
                                                                                                                                                                                                                          <td>&nbsp;</td>
                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                    </table>
                                                                                                                                                                                                                  </fieldset></td>
                                                                                                                                                                                                                </tr>-->
        <?php } elseif ($content_type[0] == 'audio') {
                        ?>
        <fieldset>
        <legend>Player Settings</legend>
        <table width="100%" border="0" cellpadding="2" cellspacing="1">
          <tr>
            <td>Player Size</td>
            <td><label> Height
              <input name="player_height" type="text" id="player_height" size="7" value="<?php echo $result['player_height']; ?>" disabled="disabled"/>
              Width
              <input name="player_width" type="text" id="player_width" size="7" value="<?php echo $result['player_width']; ?>" disabled="disabled"/>
              </label></td>
          </tr>
          <tr>
            <td width="33%">Auto Play</td>
            <td width="67%"><?php echo $result['auto_play']; ?></td>
          </tr>
          <tr>
            <td>Show Player Controls</td>
            <td><?php echo $result['player_controls']; ?></td>
          </tr>
          <tr>
            <td>Show Download Link</td>
            <td><?php echo $result['download_link']; ?></td>
          </tr>
          <?php if ($result['download_graphic'] != "") {
                                    ?>
          <tr>
            <td>Download Graphic</td>
            <td><img name="" src="<?php echo $image_url . '/images/uploads/' . $result['download_graphic'] ?>"  height="32" alt="Download Button" /></td>
          </tr>
          <?php } ?>
          <tr>
            <td>Custom Player Color</td>
            <td><?php echo $result['player_color']; ?> </td>
          </tr>
          <!--<tr>
                                          <td>Allowing Ripping/Downloading </td>
                                          <td><?php echo $result['allow_ripping_downloading']; ?></td>
                                        </tr>-->
        </table>
        </fieldset>
        <!--Payment Settings Commented-->
        <!--<tr>
                       <td colspan="3"><fieldset>
                           <legend>Payment Settings</legend>
                           <table width="100%" border="0" cellpadding="2" cellspacing="1">
                           <tr>
                                 <td width="33%">Charge to listen this audio</td>
                                 <td width="67%"><?php echo $result['charge_to_view']; ?></td>
                               </tr>
                               <tr>
                                 <td>Price to listen this audio</td>
                                 <td>$<?php echo $result['price_to_view']; ?></td>
                               </tr>
                    <?php if ($result['paypal_button'] != "") {
                    ?>
                                                                                                                                                                                                                                                                                           <tr>
                                                                                                                                                                                                                                                                                             <td>Custom PayPal Button </td>
                                                                                                                                                                                                                                                                                             <td>
                                                                                                                                                                                                                                                                                             <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['paypal_button'] ?>"  height="32" alt="PayPal Button" />
                                                                                                                                                                                                                                                                                             </td>
                                                                                                                                                                                                                                                                                           </tr>
                    <?php } ?>
                    <?php if ($result['alert_pay_button'] != "") {
                    ?>
                                                                                                                                                                                                                                                                                           <tr>
                                                                                                                                                                                                                                                                                             <td>Custom Alert Pay Button </td>
                                                                                                                                                                                                                                                                                             <td>
                                                                                                                                                                                                                                                                                             <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['alert_pay_button'] ?>"  height="32" alt="AlertPay Button" />
                                                                                                                                                                                                                                                                                             </td>
                                                                                                                                                                                                                                                                                           </tr>
                    <?php } ?>
                                                                                                                                                                                                                                                       <tr>
                                                                                                                                                                                                                                                         <td>Custom Google Checkout Button </td>
                                                                                                                                                                                                                                                         <td>
                                                                                                                                                                                                                                                         <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['google_checkout_button'] ?>"  height="32" alt="Google Checkout Button" />
                                                                                                                                                                                                                                                         </td>
                                                                                                                                                                                                                                                       </tr>
                                                                                                                                                                                                                                                       <tr>
                                                                                                                                                                                                                                                         <td>Custom Click Bank Button </td>
                                                                                                                                                                                                                                                         <td>
                                                                                                                                                                                                                                                         <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['clickbank_button'] ?>"  height="32" alt="Click Bank Button" />
                                                                                                                                                                                                                                                         </td>
                                                                                                                                                                                                                                                       </tr>
                                                                                                                                                                                                                                                       <tr>
                                                                                                                                                                                                                                                         <td>Any other custom fields that should be added  here</td>
                                                                                                                                                                                                                                                         <td>&nbsp;</td>
                                                                                                                                                                                                                                                       </tr>
                                                                                                                                                                                                                                                   </table>
                                                                                                                                                                                                                                                 </fieldset></td>
                                                                                                                                                                                                                                               </tr>-->
        <?php } elseif ($content_type[0] == 'file') {
 ?>
        <!--Payment Settings Commented-->
        <fieldset>
        <legend>File Settings</legend>
        <table width="327" border="0" cellpadding="2" cellspacing="1">
          <!--<tr>
                                                                  <td width="33%">Charge to view this file</td>
                                                                  <td width="67%"><?php echo $result['charge_to_view']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Price to view this file</td>
                                                                  <td>$<?php echo $result['price_to_view']; ?></td>
                                                                </tr>-->
          <?php if ($result['download_graphic'] != "") { ?>
          <tr>
            <td width="145">Download Graphic</td>
            <td width="292"><img name="" src="<?php echo $image_url . '/images/uploads/' . $result['download_graphic'] ?>"  height="32" alt="Download Button" /></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td>Show Download Link</td>
            <td><?php echo ($result['download_link'] == 'Yes') ? 'Yes' : 'No'; ?></td>
          </tr>
          <?php } ?>
          <?php //if($result['paypal_button'] != ""){         ?>
          <!--<tr>
                  <td>Custom PayPal Button </td>
                  <td>
                  <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['paypal_button'] ?>"  height="32" alt="PayPal Button" />
                  </td>
                </tr>
                                    <?php //} ?>
<?php //if($result['alert_pay_button'] != ""){         ?>
                <tr>
                  <td>Custom Alert Pay Button </td>
                  <td>
                  <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['alert_pay_button'] ?>"  height="32" alt="AlertPay Button" />
                  </td>
                </tr>
<?php //}         ?>
                <tr>
                  <td>Custom Google Checkout Button </td>
                  <td>
                  <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['google_checkout_button'] ?>"  height="32" alt="Google Checkout Button" />
                  </td>
                </tr>
                <tr>
                  <td>Custom Click Bank Button </td>
                  <td>
                  <img name="" src="<?php echo $image_url . '/images/uploads/' . $result['clickbank_button'] ?>"  height="32" alt="Click Bank Button" />
                  </td>
                </tr>
                <tr>
                  <td>Any other custom fields that should be added  here</td>
                  <td>&nbsp;</td>
                </tr>-->
        </table>
        </fieldset>
        <?php }
                                            $type = explode('_', $result['custom_token']);
                                            if ($type[0] == 'video') {
                    ?>
        <fieldset>
        <legend>Upload File</legend>
        <table width="100%" border="0" align="center">
          <tr>
            <td>Bucket Name : <?php echo ($_GET['bucket'] == 'local') ? 'Local Uploads' : $result['bucket_id']; ?></td>
          </tr>
          <tr>
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
									
									$file = "https://s3.amazonaws.com/" . $result['bucket_id'] . '/' . $result['content_id'];
									}

                                            ?>
              <a href="javascript:" id="preview_a">Preview </a>
              <div id="show_mediaspace" style="margin: 0pt auto; display: block; width: 550px; background-color: #f4f4f4; border: 1px solid #c4c4c4; padding: 12px; padding-bottom: 20px;"> <span id="close" style="float:right"><img src="/images/admin/delete.gif" style="cursor:pointer"></span>
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
              </div></td>
        </table>
        </fieldset>
        <?php
                                            } elseif ($type[0] == 'audio') {
                    ?>
        <fieldset>
        <legend>Upload File</legend>
        <table width="100%" border="0" align="center">
          <tr>
            <td>Bucket Name : <?php echo ($_GET['bucket'] == 'local') ? 'Local Uploads' : $result['bucket_id']; ?></td>
          </tr>
          <tr>
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
                                            ?>
              <a href="javascript:" id="preview_a">Preview </a>
              <div id="show_mediaspace" style="margin: 0pt auto; display: block; width: <?php echo $result['player_width']; ?>px; background-color: #f4f4f4; border: 1px solid #c4c4c4; padding: 12px; padding-bottom: 20px;"> <a class='rtmpaudio' href='<?php echo $file;?>' style='display:block;width:<?php echo $result['player_width']; ?>px;height:<?php echo $result['player_height']; ?>px;'></a>
                <script src='/flowplayer/example/flowplayer-3.2.6.min.js'></script>
                <script type='text/javascript'>

                                                        flowplayer('a.rtmpaudio', '/flowplayer/flowplayer-3.2.7.swf', {

                                                            clip: {


                                                                autoPlay: <?php echo ($result['auto_play'] == 'Yes') ? 'true' : 'false'; ?>,
                                                                autoBuffering: false,
                                                                bufferLength: '<?php echo $result['buffer_time']; ?>',
																
                                                            },

                                                                plugins: {



                                                                    audio: {

                                                                            url: '/flowplayer/flowplayer.audio-3.2.2.swf',

                                                                    },



                                                                    controls: {

                                                                        backgroundColor: '<?php echo $result['player_color']; ?>',

                                                                        fullscreen: false,

                                                                        autoHide: false

                                                                }



                                                                }

                                                        });

                                                  </script>
              </div>
              <!--<tr>
                                        <td>&nbsp;</td>
                                        <td><a href="<?php echo ($_GET['bucket'] == 'local') ? $local_upload_url . $result['content_id'] : S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600); ?>">
                                            <img name="" src="<?php echo ($_GET['bucket'] == 'local') ? $local_upload_url . $result['content_id'] : S3::getAuthenticatedURL($result['bucket_id'], $result['content_id'], 3600); ?>"  height="32" alt="Preview" border="0" />
                                     </a></td>
                                      </tr>-->
            </td>
        </table>
        </fieldset>
        <?php
                                            } elseif ($type[0] == 'file') {
                    ?>
        <fieldset>
        <legend>Upload File</legend>
        <table width="100%" border="0" align="center">
          <tr>
            <td>Bucket Name : <?php echo ($_GET['bucket'] == 'local') ? 'Local Uploads' : $result['bucket_id']; ?></td>
          </tr>
          <tr>
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
              <a href="<?php echo $file;?>" id="preview_a"><img name="" src="<?php echo $image_url.'/images/uploads/'.$result['download_graphic']?>"  height="32" align="absmiddle"/></a>
              <?php } else{?>
              <a href="<?php echo $file;?>" id="preview_a">Download Document File</a>
              <?php }?>
            </td>
        </table>
        </fieldset>
        <?php
                                            }
                    ?>
      </form>
    </div>
  </div>
  <div class="content-wrap-bottom"></div>
</div>
<?php include_once '../footer.php'; ?>
