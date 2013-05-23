<?php

if($_POST['content_type'] == 'video'){
echo '<td colspan="3"><fieldset>
          <legend>Player Settings</legend>
          <table width="100%" border="0">
		  	<tr>
               <td>Player Size</td>
                <td>
                  Height 
                  <span><input name="player_height" type="text" id="player_height" size="7" value="300" /></span>
                Width 
                <span><input name="player_width" type="text" id="player_width" size="7" value="400"/></span>
                </td>
                <!-- Color Picker -->
		<!--<td width="67%" rowspan="9">

                <h3>Avaliable Colors</h3>

                <div class="tokens" style="width:auto">

                     <div id="picker" style="float: right;"></div>

                </div>

                </td>

                -->

              </tr> 

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

                <td><input type="radio" name="download_link" id="download_link" value="Yes"  />

                    Yes

                      <input name="download_link" type="radio" id="download_link" value="No" checked="checked" />

                    No</td>

              </tr>

              <tr id="graphic_download">

                <td>Select Download Graphic</td>

                <td><input type="file" name="download_button" id="download_button" /></td>

              </tr>

              <tr>

                <td>Custom Player Color</td>

                <td>

                    <select name="player_color">

                        <option value="#FFFFF">White</option>                       

                        <option value="#FF0000">Red</option>

                        <option value="#800517">Fire Brick</option>

                        <option value="#0000FF">Light Blue</option>

                        <option value="#FFFF00">Yellow</option>

                        <option value="#00FF00">Light Green</option>

                        <option value="#00FFFF">Turquoise</option>

                        <option value="#FDD017">Golden</option>

                        <option value="#666666">Gray</option>

                    </select>



                    <!-- Choose Color Picker -->

                    <!--<a href="javascript:" onclick="return colorPicker();">Choose Color</a>-->

                </td>

              </tr>

              <!--<tr>

                <td>Buffer Time</td>

                <td>

                    <select name="buffer_time" id="buffer_time">

                            <option value="5">5 Sec</option>

                            <option value="10">10 Sec</option>

                            <option value="15">15 Sec</option>

                            <option value="20">20 Sec</option>

                    </select>

                </td>

              </tr>-->

              <!--<tr>

                <td>Allowing Ripping/Downloading </td>

                <td><input type="radio" name="allow_downloading" id="allow_downloading" value="Yes" />

                    Yes

                      <input name="allow_downloading" type="radio" id="allow_downloading" value="No" checked="checked" />

                    No</td>

              </tr>-->

          </table>

        </fieldset>

      <!--Payment Settings Commented-->

      <!--<fieldset>

          <legend>Payment Settings</legend>

          <table width="100%" border="0">

            <tr>

                <td width="33%">Charge to view this video</td>

                <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" />

                    Yes

                    <input name="charge_to_view" type="radio" id="charge_to_view" value="No" checked="checked" />

                    No</td>

              </tr>

              Price to View Video

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

              <tr>

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

              </tr>

         </table>
	</td>
        </fieldset>-->';



}elseif($_POST['content_type'] == 'audio'){



echo '<td colspan="3"><fieldset>

          <legend>Player Settings</legend>

          <table width="100%" border="0">

		  <tr>

                <td>Player Size</td>

                <td>

                  Height 

                  <span><input name="player_height" type="text" id="player_height" size="7" value="24"/></span>

                Width 

                <span><input name="player_width" type="text" id="player_width" size="7" value="300"/></span>

                </td>

                <!-- Color Picker -->

		<!--<td width="67%" rowspan="9">

                <h3>Avaliable Colors</h3>

                <div class="tokens" style="width:auto">

                     <div id="picker" style="float: right;"></div>

                </div>

                </td>-->

              </tr> 

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

			  <tr id="graphic_download">

                <td>Select Download Graphic</td>

                <td><input type="file" name="download_button" id="download_button" /></td>

              </tr>

              <tr>

                <td>Custom Player Color</td>

                <td>

                    <select name="player_color">

                        <option value="#FFFFF">White</option>

                        <option value="#FF0000">Red</option>

                        <option value="#800517">Fire Brick</option>

                        <option value="#0000FF">Light Blue</option>

                        <option value="#FFFF00">Yellow</option>

                        <option value="#00FF00">Light Green</option>

                        <option value="#00FFFF">Turquoise</option>

                        <option value="#FDD017">Golden</option>

                        <option value="#666666">Gray</option>

                    </select>



                    <!-- Choose Color Picker -->

                    <!--<a href="javascript:" onclick="return colorPicker();">Choose Color</a>-->

</td>

</tr>

<!--<tr>

    <td>Buffer Time</td>

    <td>

        <select name="buffer_time" id="buffer_time">

            <option value="5">5 Sec</option>

            <option value="10">10 Sec</option>

            <option value="15">15 Sec</option>

            <option value="20">20 Sec</option>

        </select>

    </td>

</tr>-->

<!--<tr>

    <td>Allowing Ripping/Downloading </td>

    <td><input type="radio" name="allow_downloading" id="allow_downloading" value="Yes" />

        Yes

        <input name="allow_downloading" type="radio" id="allow_downloading" value="No" checked="checked" />

        No</td>

</tr>-->

</table>

</fieldset>

<!--Payment Settings Commented-->

<!--

   <fieldset>

            <legend>Payment Settings</legend>

            <table width="100%" border="0">

                <tr>

                    <td width="33%">Charge to listen this audio</td>

                    <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" />

                        Yes

                        <input name="charge_to_view" type="radio" id="charge_to_view" value="No" checked="checked" />

                        No</td>

                </tr>

                Price to Listen Audio

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

                <tr>

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

                </tr>

            </table>

        </fieldset>
		
		-->
		</td>
		';



		}elseif($_POST['content_type'] == 'file'){



			echo '<td colspan="3"><fieldset>

            <legend>File Settings</legend>

            <table width="100%" border="0">

                <!--<tr>

                    <td width="33%">Charge to view this File</td>

                    <td width="67%"><input type="radio" name="charge_to_view" id="charge_to_view" value="Yes" />

                        Yes

                        <input name="charge_to_view" type="radio" id="charge_to_view" value="No" checked="checked" />

                        No</td>

                </tr>

                Price to View File

                <tr>

                  <td>Price to view this File</td>

                  <td>$

                    <input type="text" name="price_to_view" id="price_to_view" /></td>

                </tr>-->

                <tr>

                <td>Show Download Link</td>

                <td><input type="radio" name="download_link" id="download_link" value="Yes" />

                  Yes

                  <input name="download_link" type="radio" id="download_link" value="No" checked="checked" />

                  No</td>

                </tr>

                <tr id="graphic_download">

                    <td>Select Download Graphic</td>

                    <td><input type="file" name="download_button" id="download_button" /></td>

                </tr>

                <!--<tr>

                    <td>Custom PayPal Button </td>

                    <td><input type="file" name="paypal_button" id="paypal_button" /></td>

                </tr>

                <tr>

                    <td>Custom Alert Pay Button </td>

                    <td><input type="file" name="alertpay_button" id="alertpay_button" /></td>

                </tr>

                <tr>

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

        </fieldset></td>';



			}



//echo '<PRE>';print_r($_POST);



?>