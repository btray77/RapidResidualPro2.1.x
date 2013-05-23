<?php
include_once ("session.php");
include_once ("header.php");
if ($msg == 'a') {
    $msg = '<div class="success"><img src="../images/tick.png" align="absmiddle">Site settings are successfully edited!</div>';
}
if ($msg == 'e') {
    $msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Invalid Email!  Please Check Enter Your Correct Email And Try Again.</div>';
}
if ($msg == 'file') {
    $msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Files must be either JPEG, GIF, or PNG and less than 100 kb</div>';
}
/**
 * Save Site Settings
 */
for ($i = 0; $i < $index; $i++) {
    echo '<pre>';
}
if (isset($_POST ['submit'])) {
    /**
     * Are emails Valid?
     */
    $valid_email = array();

    $valid_email [] = (valid_email($_POST ["email_from"])) ? 'valid' : 'invalid';
    $valid_email [] = (valid_email($_POST ["paypal_email"])) ? 'valid' : 'invalid';
    if (!empty($_POST ['sandbox_paypal_email'])) {
        $valid_email [] = (valid_email($_POST ["sandbox_paypal_email"])) ? 'valid' : 'invalid';
    }
    if (!empty($_POST ['partner_paypal_email'])) {
        $valid_email [] = (valid_email($_POST ["partner_paypal_email"])) ? 'valid' : 'invalid';
    }
    if (!empty($_POST ['alertpay_merchant_email'])) {
        $valid_email [] = (valid_email($_POST ["alertpay_merchant_email"])) ? 'valid' : 'invalid';
    }

    if (in_array('invalid', $valid_email, TRUE)) {
        header("Location: site_settings.php?msg=e");
    } else {
        // Parse form data
      if(!empty($_FILES["logo"]["name"])){  
        if (($_FILES["logo"]["type"] == "image/gif")
          || ($_FILES["logo"]["type"] == "image/jpeg")
          || ($_FILES["logo"]["type"] == "image/png" )
          && ($_FILES["logo"]["size"] < 104857600))
          {
          if(!move_uploaded_file($_FILES["logo"]["tmp_name"],
             "../images/" . $_FILES["logo"]["name"])) die('Unable to upload image');
          }
        else
          {
          header("Location: site_settings.php?msg=file");  
          }
          $filename =",logo='".$_FILES["logo"]["name"]."'";
      }else   $filename ='';
     
        $sitename = $db->quote($_POST ["sitename"]);
        $keywords = $db->quote($_POST ["keywords"]);
        $description = $db->quote($_POST ["description"]);
        $sidebar_my_download_text = $db->quote($_POST ["sidebar_my_download_text"]);
        $sidebar_instruction_text = $db->quote($_POST ["sidebar_instruction_text"]);
        $sidebar_new_products_text = $db->quote($_POST ["sidebar_new_products_text"]);
        $social_media_widgets = $db->quote($_POST ["social_media_widgets"]);
        
        $tagline = $db->quote($_POST ["tagline"]);
        // Email Settings
        $email_from = $db->quote($_POST ["email_from"]);
        $from_name = $db->quote($_POST ["from_name"]);
        $mailer = $db->quote($_POST ["mailer"]);
        $smtpsecure = $db->quote($_POST ["smtpsecure"]);
        $smtphost = $db->quote($_POST ["smtphost"]);
        $smtpport = $db->quote($_POST ["smtpport"]);
        $smtpusername = $db->quote($_POST ["smtpusername"]);
        $smtppassword = $db->quote($_POST ["smtppassword"]);
        $mail_footer = $db->quote($_POST ["mail_footer"]);

        // Global

        $useeditor = $db->quote($_POST ["useeditor"]);
        $meta = $db->quote($_POST ["meta"]);
        $paypal_email = $db->quote($_POST ["paypal_email"]);
        $sandbox_paypal_email = $db->quote($_POST ["sandbox_paypal_email"]);
        $prot_down = $db->quote(trim($_POST ["prot_down"]));
        $swf_down = $db->quote(trim($_POST ["swf_down"]));
        
        
        
        $prod = $db->quote($_POST ["prod"]);
        $tracking = $db->quote($_POST ["tracking"]);
        $sitepartner = $db->quote($_POST ["sitepartner"]);
        $second_sitepartner = $db->quote($_POST ["second_sitepartner"]);
        $partner_paypal_email = $db->quote($_POST ["partner_paypal_email"]);
        $partner_alertpay_email = $db->quote($_POST ["partner_alertpay_email"]);
        $partner_commission = $db->quote($_POST ["partner_commission"]);
        if ($partner_commission == '') {
            $partner_commission = NULL;
        }
        $second_partner_paypal_email = $db->quote($_POST ["second_partner_paypal_email"]);
        $second_partner_alertpay_email = $db->quote($_POST ["second_partner_alertpay_email"]);
        $second_partner_commission = $db->quote($_POST ["second_partner_commission"]);
        $paypal_enable = $db->quote($_POST ["paypal_enable"]);
        $alertpay_enable = $db->quote($_POST ["alertpay_enable"]);
        // Amazon Settings

        $aws_access_key = $db->quote($_POST ["aws_access_key"]);
        $aws_secret_key = $db->quote($_POST ["aws_secret_key"]);
        $allowed_file_types = $db->quote($_POST ["allowed_file_types"]);
        $cloud_fornt = $db->quote($_POST ["cloud_fornt"]);

        // AlertPay Setting

        $alertpay_merchant_email = $db->quote($_POST ["alertpay_merchant_email"]);
        $alertpay_test_mode = $db->quote($_POST ["alertpay_test_mode"]);
        $alertpay_ipn_code = $db->quote($_POST ["alertpay_ipn_code"]);

        // $alertpay_refund_code = $db->quote($_POST["alertpay_refund_code"]);
        // Affilie Cookie

        $cookie_mode = $db->quote($_POST ["cookie_mode"]);
        $cookie_expiry = $db->quote($_POST ["cookie_expiry"]);

        // Click Bank Setting

        $click_api_key = $db->quote($_POST ["click_api_key"]);
        $click_user_id = $db->quote($_POST ["click_user_id"]);
        // Partners AlertPay security code
        $partner1_alertpay_ipn_code = $db->quote($_POST ["partner1_alertpay_ipn_code"]);
        $partner2_alertpay_ipn_code = $db->quote($_POST ["partner2_alertpay_ipn_code"]);
        if ($second_partner_commission == '') {
            $second_partner_commission = NULL;
        }
        // Update database
        $set = " sitename={$sitename}";
        $set .= ", sidebar_my_download_text={$sidebar_my_download_text}";
        $set .= ", sidebar_instruction_text={$sidebar_instruction_text}";
        $set .= ", sidebar_new_products_text={$sidebar_new_products_text}";
        $set .= ", social_media_widgets={$social_media_widgets}";
        $set .= ", keywords={$keywords}";
        $set .= ", tagline={$tagline}";
        $set .= " $filename";
        
        $set .= ", description={$description}";
        $set .= ", email_from_name={$email_from}";
        $set .= ", from_name={$from_name}";
        $set .= ", mailer={$mailer}";
        $set .= ", smtpsecure={$smtpsecure}";
        $set .= ", smtphost={$smtphost}";
        $set .= ", smtpport={$smtpport}";
        $set .= ", smtpusername={$smtpusername}";
        $set .= ", smtppassword={$smtppassword}";
        $set .= ", mailer_details={$mail_footer}";
        $set .= ", meta={$meta}";
        $set .= ", useeditor=1";
        $set .= ", paypal_email={$paypal_email}";
        $set .= ", sandbox_paypal_email={$sandbox_paypal_email}";
        $set .= ", paypal_sandbox={$paypal_sandbox}";
        $set .= ", prot_down={$prot_down}";
        $set .= ", swf_down={$swf_down}";
        $set .= ", prod={$prod}";
        $set .= ", partner_paypal_email={$partner_paypal_email}";
        $set .= ", partner_alertpay_email={$partner_alertpay_email}";
        $set .= ", partner_commission={$partner_commission}";
        $set .= ", sitepartner={$sitepartner}";
        $set .= ", second_partner_paypal_email={$second_partner_paypal_email}";
        $set .= ", second_partner_alertpay_email={$second_partner_alertpay_email}";
        $set .= ", second_partner_commission={$second_partner_commission}";
        $set .= ", second_sitepartner={$second_sitepartner}";
        $set .= ", paypal_enable={$paypal_enable}";
        $set .= ", alertpay_enable={$alertpay_enable}";
        $set .= ", tracking={$tracking}";

        // Amazon S3 Settings
        $set .= ", aws_access_key={$aws_access_key}";
        $set .= ", aws_secret_key={$aws_secret_key}";
        $set .= ", allowed_file_types={$allowed_file_types}";
        $set .= ", cloud_fornt={$cloud_fornt}";
        // Alert Pay Setting
        $set .= ", alertpay_merchant_email={$alertpay_merchant_email}";
        $set .= ", alertpay_test_mode={$alertpay_test_mode}";
        $set .= ", alertpay_ipn_code={$alertpay_ipn_code}";
        // $set .= ", alertpay_refund_code={$alertpay_refund_code}";
        // Click Back Setting
        $set .= ", click_api_key={$click_api_key}";
        $set .= ", click_user_id={$click_user_id}";
        // Partners AlertPay security codes
        $set .= ", partner1_alertpay_ipn_code ={$partner1_alertpay_ipn_code}";
        $set .= ", partner2_alertpay_ipn_code ={$partner2_alertpay_ipn_code}";

        // Affiliate Cookie Setting
        $set .= ", cookie_mode={$cookie_mode}";
        $set .= ", cookie_expiry={$cookie_expiry}";

        $mysql = "update " . $prefix . "site_settings set $set where id='1'";
	
        $db->insert($mysql);

        // Create Protected Download and Media Directories

        if (!empty($_POST ['prot_down'])) {

            $download_upload_dir = $root_path . $_POST ['prot_down'];
            $download_upload_url = $root_path . $_POST ['prot_down'];

            if (!is_dir($download_upload_dir)) {
                @mkdir($download_upload_dir);
                @chmod($download_upload_dir, 777);
            }
        }

        if (!empty($_POST ['swf_down'])) {

            $media_upload_dir = $root_path . $_POST ['swf_down'];
            $media_upload_url = $http_path . $_POST ['swf_down'];

            if (!is_dir($media_upload_dir)) {
                @mkdir($media_upload_dir);
                @chmod($media_upload_dir, 777);
            }
        }

        header("Location: site_settings.php?msg=a");
    }
}

// read data from database

$mysql = "select * from " . $prefix . "site_settings where id='1'";
$rslt = $db->get_a_line($mysql);
@extract($rslt);
$meta = stripslashes($meta);

/*
 * $sitename 						= $sitename; $keywords 						= $keywords; $description 					=
 * $description; $email_from_name 				= $email_from_name; $mailer_details 				=
 * $mailer_details; $useeditor						= $useeditor; $paypal_email 					=
 * $paypal_email; $sandbox_paypal_email 			= $sandbox_paypal_email; $prot_down
 * 						= $prot_down; $swf_down 						= $swf_down;
 */

$tracking = stripslashes($tracking);

$prodcheck = $prod;

/*
 * $partner_paypal_email			= $partner_paypal_email; $partner_commission				=
 * $partner_commission; $second_partner_paypal_email	=
 * $second_partner_paypal_email; $second_partner_commission		=
 * $second_partner_commission;
 */

$paypal_enable = $paypal_enable;

if ($paypal_enable == 'yes') {

    $paypal_enable_chk1 = 'checked';
} elseif ($paypal_enable == 'no') {

    $paypal_enable_chk2 = 'checked';
}

$alertpay_enable = $alertpay_enable;

if ($alertpay_enable == 'yes') {

    $alertpay_enable_chk1 = 'checked';
} elseif ($alertpay_enable == 'no') {

    $alertpay_enable_chk2 = 'checked';
}

$sitepartner = $sitepartner;

if ($sitepartner == 'yes') {

    $partner1 = 'checked';
} elseif ($sitepartner == 'no') {

    $partner2 = 'checked';
}

$second_sitepartner = $second_sitepartner;

if ($second_sitepartner == 'yes') {

    $second_partner1 = 'checked';
} elseif ($second_sitepartner == 'no') {

    $second_partner2 = 'checked';
}

if ($prodcheck == 'onsite') {

    $onstatus1 = 'checked';
} else if ($prodcheck == 'offsite') {

    $offstatus1 = 'checked';
}

if ($useeditor == 0) {

    $option10a = "selected";
} else if ($useeditor == 1) {

    $option11a = "selected";
}

if ($paypal_sandbox == 1) {

    $true1 = "selected";
} else if ($paypal_sandbox == 0) {

    $false1 = "selected";
}

// Affiliate cookie

if ($cookie_mode == 'first') {

    $first_mode = 'checked="checked"';
} elseif ($cookie_mode == 'last') {

    $last_mode = 'checked="checked"';
} else {

    $first_mode = $last_mode = '';
}
?>



<!-- ###################### Error Message Start ###################### -->

<?php
$warnMsg = "";
if (!is_dir($root_path . $prot_down)) {
    $warnMsg .= '<div class="warning"><img src="../images/warning.png" align="absmiddle"> Please create ' . $prot_down . ' directory via FTP.</div>';
    $configmod = substr(sprintf('%o', fileperms($root_path . $prot_down)), - 4);
    if ($configmod != '0777') {
        if (!chmod($root_path . $prot_down, 777))
            $warnMsg .= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
        $warnMsg .= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> ' . $prot_down . '  has  ' . $configmod . ' permissions. But required Permission is 0777 to access.</div>';
    }
}

if (!is_dir($root_path . $swf_down)) {
    $warnMsg .= '<div class="warning"><img src="../images/warning.png" align="absmiddle"> Please create ' . $swf_down . ' directory via FTP.</div>';
    $configmod = substr(sprintf('%o', fileperms($root_path . $swf_down)), - 4);
    if ($configmod != '0777') {
        if (!chmod($root_path . $swf_down, 777))
            $warnMsg .= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
        $warnMsg .= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> ' . $swf_down . '  has  ' . $configmod . ' permissions. But required Permission is 0777 to access.</div>';
    }
}

echo $warnMsg;
?>



<?php echo $msg; ?>

<div class="content-wrap">
    <div class="content-wrap-top"></div>
    <div class="content-wrap-inner">
        <p>
            <strong>WebSite System Settings</strong>
        </p>
        <script>
            var error = 0;
            $(document).ready(function(){
                $(".tab_content").hide(); //Hide all content

                $("ul.tabs li:first").addClass("active").show(); //Activate first tab

                $(".tab_content:first").show(); //Show first tab content

            	    

                //On Click Event

                $("ul.tabs li").click(function() {

                    $("ul.tabs li").removeClass("active"); //Remove any "active" class

                    $(this).addClass("active"); //Add "active" class to selected tab

                    $(".tab_content").hide(); //Hide all tab content

                    var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content

                    $(activeTab).fadeIn(); //Fade in the active content

                    return false;

                });    
                             
                        
                        

                $.validator.addMethod( "alphanum", function(value, element) {
                    return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
                }, "This field must contain only letters, numbers."
            );
	
                $.validator.addMethod( "number", function(value, element) {
                    return this.optional(element) || /^[0-9\-]+$/i.test(value);
                }, "This field must contain only numbers."
            );
	
                /**********************************************************************/
                $('#settings').bind('click focus', function() {
                    if($(this).validate().checkForm()) {
                        $('#testsmtp').removeClass('button_disabled').attr('disabled', false);
                    } else {
                        $('#testsmtp').addClass('button_disabled').attr('disabled', true);
                    }
                });

                /************************************************************/
                $("#testsmtp").click(function() {
                    var mail_type = $("#mailer").val();
                    var email_from = $("#email_from").val();
                    var from_name = $("#from_name").val();
                    var smtpsecure = $("#smtpsecure").val();
                    var smtphost = $("#smtphost").val();
                    var smtpport = $("#smtpport").val();
                    var smtpusername = $("#smtpusername").val();
                    var smtppassword = $("#smtppassword").val();
                    var mail_footer = $("#mail_footer").val();
		
                    var dataString = 'mail_type='+ mail_type + '&email_from=' + email_from + '&from_name=' + from_name + '&smtpsecure=' + smtpsecure
                        + '&smtphost=' + smtphost + '&smtpport=' + smtpport + '&smtpusername=' + smtpusername + '&smtppassword=' + smtppassword + '&mail_footer=' + '';
	  
                    $("#loading").css('display','block');
                    $("#page-content").css('display','none');
                    $.ajax({
                        type: "POST",
                        url: "checksmtp.php",
                        data: dataString,
                        dataType: "html",
                        success: function(data) {
                            $('#page-content').html('');
                            $('#page-content').append(data);
                            $("#page-content").css('display','block');
                            $("#loading").css('display','none');		
                        },
                        error: function(){
                            $('#page-content').append("Smtp is not configured properly. Please check your settings");
                        }
                    });
                    return false;
                });
		$("#submitform").click(function() {
			$("form").submit();
			});
                ////////////////////////////////////////////////////	
            });


        
        </script>
        <?php if ($obj_pri->getRole() == 1) { ?>

            <form action="site_settings.php" method="post" enctype="multipart/form-data" name="settings" id="form_settings">

                <?php
                echo $disable = '';
            } else
                $disable = 'disabled="disabled"';
            ?>
            <ul class="tabs" style="width: 100%;">
                <li><a href="#tab1">Website</a></li>
                <li><a href="#tab2">Payment</a></li>
                <li><a href="#tab3">Site Partners</a></li>
                <li><a href="#tab4">Amazon API </a></li>
                <li><a href="#tab5">Affiliate Cookies</a></li>
                <li><a href="#tab6">SMTP</a></li>


            </ul>
            <div class="tab_container"
                 style="width: 97.6%; height: auto; background-color: #f4f4f4">
                <div id="tab1" class="tab_content">
                    <table border=0 style="float: left; width: 100%">
                        <tr>

                            <td width="61" align="left" >Website Name:</td>

                            <td width="214" align="left"><input name="sitename" type="text"
                                                                class="inputbox" id="sitename" value='<?php echo $sitename ?>'
                                                                size="60" <?php echo $disable ?>></td>



                            <td width="43">
                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Site Name: The name people see in the web page name area of their browser.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>
                        <tr>

                            <td width="61" align="left" >Website Tagline:</td>

                            <td width="214" align="left"><input name="tagline" type="text"
                                                                class="inputbox" id="tagline" value='<?php echo $tagline ?>'
                                                                size="60" <?php echo $disable ?>></td>



                            <td width="43">
                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Site Tagline: This is an optional field and will display on template">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>
                        <tr>

                            <td align="left" valign="top" >Meta Keywords:</td>

                            <td align="left"><textarea name="keywords" rows="5" cols="60"
                                                       class="inputbox" <?php echo $disable ?>><?php echo $keywords ?></textarea>							</td>

                            <td valign="top">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Keywords: The keywords which will be embedded in your header html. Use these for SEO purposes.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                        <tr>

                            <td align="left" valign="top" >Meta Description:</td>

                            <td align="left"><textarea name="description" rows="5" cols="60"
                                                       class="inputbox" <?php echo $disable ?>><?php echo $description ?></textarea>							</td>

                            <td valign="top">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Site Description: This will be embedded in your header html. Use for SEO purposes.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>
                         <tr>

                            <td align="left" valign="top" >Website Logo:</td>

                            <td align="left">
                                <input type="file" name="logo" class="inputbox" /><br />
                                <small>Allow file type is .png,.jpg and .gif and file dimension should be 300 x 150</small>
                                <br/>
                               <?php if(!empty($logo)){ ?> 
                                <img src="/images/<?php echo $logo;?>" title="logo" alt="logo"  style="border: 1px solid #c4c4c4" /><br />
                               <small> Copy and Paste this URL in you HTML theme<br/> 
                                &lt;img src=&quot;/images/{$settings_logo}&quot; alt=&quot;Logo&quot; title=&quot;{$settings_tagline}&quot; height=&quot;120&quot;  /&gt;
                                </small> 
                                
                               <?php }?> 
                            </td>

                            <td valign="top">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Site Logo: Allow file type is .png,.jpg and .gif and file dimension should be 300 x 150">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>
                        <tr>

                            <td align="left" valign="top" >Extra
                                Javascripts,CSS or IFrame etc </td>

                            <td align="left"><textarea name="meta" cols="60" rows="5"
                                                       class="inputbox" id="meta" <?php echo $disable ?>><?php echo $meta ?></textarea>							</td>

                            <td valign="top">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="This can be used to add Javascript code,Javascript File, CSS and IFrame that needs to go inside a pages head section. Please never enter any plain text in it.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                        <tr>

                            <td align="left" valign="top" >Member Area
                                Sidebars Settings</td>

                            <td align="left"><span > My Download: <input
                                        name="sidebar_my_download_text" type="text" class="inputbox"
                                        id="sidebar_my_download_text"
                                        value="<?php echo $sidebar_my_download_text ?>" size="40"
                                        <?php echo $disable ?> />
                                </span></td>

                            <td valign="top"><div class="tool">
                                    <a href="" class="tooltip"
                                       title="This can be used to change the sidebar text in member area.">
                                        <img src="../images/toolTip.png" alt="help" />									</a>

                                </div></td>
                        </tr>

                        <tr>
                            <td align="left" >&nbsp;</td>
                            <td align="left"><span >Instructions: &nbsp;&nbsp;<input
                                        name="sidebar_instruction_text" type="text" class="inputbox"
                                        id="sidebar_instruction_text"
                                        value="<?php echo $sidebar_instruction_text ?>" size="40"
                                        <?php echo $disable ?> />
                                </span></td>
                            <td valign="top"><div class="tool">
                                    <a href="" class="tooltip"
                                       title="This can be used to change the sidebar text in member area">
                                        <img src="../images/toolTip.png" alt="help" />									</a>
                                </div></td>
                        </tr>
                        <tr>
                            <td align="left" >&nbsp;</td>
                            <td align="left"><span >New Products:<input
                                        name="sidebar_new_products_text" type="text" class="inputbox"
                                        id="sidebar_new_products_text"
                                        value="<?php echo $sidebar_new_products_text ?>" size="40"
                                        <?php echo $disable ?> />
                                </span></td>
                            <td valign="top"><div class="tool">
                                    <a href="" class="tooltip"
                                       title="This can be used to change the sidebar text in member area.">
                                        <img src="../images/toolTip.png" alt="help" />									</a>
                                </div></td>
                        </tr>
                        <tr>
                            <td align="left" >Social Media Widget </td>
                            <td align="left"><textarea name="social_media_widgets" cols="60"
                                                       rows="5" class="inputbox" id="social_media_widgets"
                                                       <?php echo $disable ?>><?php echo $social_media_widgets ?></textarea></td>
                            <td valign="top"><div class="tool">
                                    <a href="" class="tooltip"
                                       title="This can be used to add social media wigdets like facebook,twiter and google plus etc.">
                                        <img src="../images/toolTip.png" alt="help" />									</a>
                                </div></td>
                        </tr>
                        <tr>
                            <td align="left" >Analytics/Tracking Code:</td>
                            <td align="left"><textarea name="tracking" cols="60" rows="5"
                                                       class="inputbox" id="tracking" <?php echo $disable ?>><?php echo $tracking ?></textarea></td>
                            <td valign="top"><div class="tool">
                                    <a href="" class="tooltip"
                                       title="Analytics/Tracking Code: Place any analytics code here!">
                                        <img src="../images/toolTip.png" alt="help" />									</a>
                                </div></td>
                        </tr>
                    </table>
                </div>
                <div id="tab2" class="tab_content">
                    <table width="100%" border="0" align="center" cellpadding="5"
                           cellspacing="">







                        <tr>

                            <td align="left" >Live PayPal Email:</td>

                            <td width="1105" align="left" ><input
                                    name="paypal_email" type="text" class="inputbox"
                                    id="paypal_email" value="<?php echo $paypal_email ?>" size="60"
                                    <?php echo $disable ?> /></td>

                            <td width="36">



                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Live PayPal Email: is used to accept payments."> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                        <tr>

                            <td align="left" >Sandbox PayPal Email:</td>

                            <td  align="left"><input
                                    name="sandbox_paypal_email" type="text" class="inputbox"
                                    id="sandbox_paypal_email"
                                    value="<?php echo $sandbox_paypal_email ?>" size="60"
                                    <?php echo $disable ?> /></td>

                            <td>

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Sandbox PayPal Email: is used to test the system settings without 

                                       going through the live PayPal payment system."> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>



                        <tr>

                            <td  align="left" width="361">PayPal Live/Test
                                Mode:</td>

                            <td align="left" valign="top" ><select
                                    name="paypal_sandbox" class="inputbox" id="paypal_sandbox"
                                    <?php echo $disable ?>>

                                    <option value="0" <?php echo $false1 ?>>Live PayPal</option>

                                    <option value="1" <?php echo $true1 ?>>Test Mode</option>

                                </select></td>

                            <td>

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="PayPal Live/Test Mode: Toggle between live and test mode of paypal.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                        <tr>

                            <td width="361" align="left" >Enable Paypal:							</td>

                            <td  align="left"><input class="inputbox"
                                                     name="paypal_enable" type="radio" value="yes"
                                                     <?php echo $paypal_enable_chk1; ?> />Yes <input class="inputbox"
                                                     name="paypal_enable" type="radio" value="no"
                                                     <?php echo $paypal_enable_chk2; ?> />No</td>

                            <td></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" align="center" cellpadding="5"
                           cellspacing="">



                        <tr>
                            <th colspan="2" align="left" bgcolor="#CCCCCC" >Alertpay Settings</th>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>

                            <td align="left" >Merchant Email:</td>

                            <td width="1105" align="left" ><input
                                    name="alertpay_merchant_email" type="text" class="inputbox"
                                    id="alertpay_merchant_email"
                                    value="<?php echo $alertpay_merchant_email ?>" size="60"
                                    <?php echo $disable ?> /></td>

                            <td width="36">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Merchant Email: is used to identify your account at alertpay.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                        <tr>

                            <td align="left" >IPN Security Code:</td>

                            <td  align="left"><input name="alertpay_ipn_code"
                                                     type="text" class="inputbox" id="alertpay_ipn_code"
                                                     value="<?php echo $alertpay_ipn_code ?>" size="60"
                                                     <?php echo $disable ?> /></td>

                            <td><div class="tool">
                                    <a href="" class="tooltip"
                                       title="Merchant Email: is used to identify your account at alertpay.">
                                        <img src="../images/toolTip.png" alt="help" />									</a>
                                </div></td>
                        </tr>

                        <tr>

                            <td  align="left" width="361">AlertPay Mode:</td>

                            <td align="left" valign="top" ><select
                                    name="alertpay_test_mode" class="inputbox"
                                    id="alertpay_test_mode" <?php echo $disable ?>>

                                    <option value="0"
                                    <?php
                                    if ($alertpay_test_mode == 0)
                                        echo "selected"
                                        ?>>Live Account</option>

                                    <option value="1"
                                    <?php
                                    if ($alertpay_test_mode == 1)
                                        echo "selected"
                                        ?>>Test Mode</option>
                                </select></td>

                            <td>

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="AlertPay Mode: Toggle between live and test mode of alertpay.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                                                <!-- <tr>
        <td align="left" ><strong>AlertPay Refund Security Code</strong></td>
        <td  align="left" >
            <input name="alertpay_refund_code" type="text" class="inputbox" id="alertpay_refund_code" value="<?php echo $alertpay_refund_code ?>" size="60" <?php echo $disable ?> /
            ></td>
        <td>&nbsp;</td>
      </tr>-->
                        <tr>

                            <td width="361" align="left" >Enable AlertPay:							</td>

                            <td  align="left"><input class="inputbox"
                                                     name="alertpay_enable" type="radio" value="yes"
                                                     <?php echo $alertpay_enable_chk1; ?> />Yes <input
                                                     class="inputbox" name="alertpay_enable" type="radio" value="no"
                                                     <?php echo $alertpay_enable_chk2; ?> />No</td>

                            <td>&nbsp;</td>
                        </tr>
                    </table>

                </div>
                <div id="tab3" class="tab_content">
                    <table width="100%" border="0" align="center" cellpadding="5"
                           cellspacing="">
                        <tr>
                            <td width="361" align="left" >Enable Site Partner:
                            </td>
                            <td width="1105" align="left" ><input
                                    class="inputbox" name="sitepartner" type="radio" value="yes"
                                    <?php echo $partner1 ?> <?php echo $disable ?> /> Yes <input
                                    class="inputbox" name="sitepartner" type="radio" value="no"
                                    <?php echo $partner2 ?> <?php echo $disable ?> /> No</td>
                            <td width="36" align="left" ><a href=""
                                                            class="tooltip" title="Enable Site Partner."><img
                                        src="../images/toolTip.png" alt="help" border="0" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" >Site Partner PayPal Email:</td>
                            <td  align="left"><input
                                    name="partner_paypal_email" type="text" class="inputbox"
                                    id="partner_paypal_email"
                                    value="<?php echo $partner_paypal_email ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="Site Partner PayPal Email."><img
                                        src="../images/toolTip.png" alt="help" border="0" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" >Site Partner AlertPay Email:</td>
                            <td  align="left"><input
                                    name="partner_alertpay_email" type="text" class="inputbox"
                                    id="partner_alertpay_email"
                                    value="<?php echo $partner_alertpay_email ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="Site Partner AlertPay Email:"><img
                                        src="../images/toolTip.png" alt="help" border="0" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" >First Partner IPN Security Code:</td>
                            <td  align="left"><input
                                    name="partner1_alertpay_ipn_code" type="text" class="inputbox"
                                    id="partner1_alertpay_ipn_code"
                                    value="<?php echo $partner1_alertpay_ipn_code ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="AlertPay IPN Security Code: is used for successful payments.">
                                    <img src="../images/toolTip.png" alt="help" border="0" />
                                </a></td>
                        </tr>
                        <tr>
                            <td align="left" >Site Partner Commission %:</td>
                            <td  align="left"><input name="partner_commission"
                                                     type="text" class="inputbox" id="partner_commission"
                                                     value="<?php echo $partner_commission ?>" size="60"
                                                     <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="Site Partner Commission %"><img
                                        src="../images/toolTip.png" alt="help" border="0" /></a></td>
                        </tr>
                        <tr>
                            <td width="361" align="left" >Enable Second
                                Partner:</td>
                            <td  align="left"><input class="inputbox"
                                                     name="second_sitepartner" type="radio" value="yes"
                                                     <?php echo $second_partner1 ?> <?php echo $disable ?> /> Yes <input
                                                     class="inputbox" name="second_sitepartner" type="radio"
                                                     value="no" <?php echo $second_partner2 ?> <?php echo $disable ?> />
                                No</td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="Enable Second Partner"><img src="../images/toolTip.png"
                                                                   alt="help" border="0" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" >Second Partner PayPal Email:</td>
                            <td  align="left"><input
                                    name="second_partner_paypal_email" type="text" class="inputbox"
                                    id="second_partner_paypal_email"
                                    value="<?php echo $second_partner_paypal_email ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="Second Partner PayPal Email."><img
                                        src="../images/toolTip.png" alt="help" border="0" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" >Second Partner AlertPay Email:</td>
                            <td  align="left"><input
                                    name="second_partner_alertpay_email" type="text"
                                    class="inputbox" id="second_partner_alertpay_email"
                                    value="<?php echo $second_partner_alertpay_email ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="Second Partner AlertPay Email."><img
                                        src="../images/toolTip.png" alt="help" border="0" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" >Second Partner IPN Security Code:</td>
                            <td  align="left"><input
                                    name="partner2_alertpay_ipn_code" type="text" class="inputbox"
                                    id="partner2_alertpay_ipn_code"
                                    value="<?php echo $partner2_alertpay_ipn_code ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="AlertPay IPN Security Code: is used for successful payments.">
                                    <img src="../images/toolTip.png" alt="help" border="0" />
                                </a></td>
                        </tr>
                        <tr>
                            <td align="left" >Second Partner Commission %:</td>
                            <td  align="left"><input
                                    name="second_partner_commission" type="text" class="inputbox"
                                    id="second_partner_commission"
                                    value="<?php echo $second_partner_commission ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td  align="left"><a href="" class="tooltip"
                                                 title="Second Partner Commission."><img
                                        src="../images/toolTip.png" alt="help" border="0" /></a></td>
                        </tr>
                    </table>
                </div>

                <div id="tab4" class="tab_content">
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="">
                        <tr>
                            <td align="left"  width="21%">Protected Download Folder:</td>
                            <td width="74%" align="left" ><input
                                    name="prot_down" type="text" class="inputbox" id="prot_down"
                                    value="<?php echo $prot_down ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td width="2%">
                                <div class="tool">
                                    <a href="" class="tooltip"
                                       title="Protected Download Folder: Only save on local directory of the server. (/images/documents/)">
                                        <img src="../images/toolTip.png" alt="help" />                </a>            </div>        </td>
                        </tr>
                        <tr>
                            <td align="left"  width="21%">Protected
                                Video/Audio Folder:</td>
                            <td width="74%" align="left" ><input
                                    name="swf_down" type="text" class="inputbox" id="swf_down"
                                    value="<?php echo $swf_down ?>" size="60" <?php echo $disable ?> />        </td>
                            <td width="2%">
                                <div class="tool">
                                    <a href="" class="tooltip"
                                       title="Protected Video/Audio Folder: Only save on local directory of the server. (/images/media/)">
                                        <img src="../images/toolTip.png" alt="help" />                </a>            </div>        </td>
                        </tr>
                        <?php
                        if ($obj_pri->getRole() != 1)
                            $type = "password";
                        else
                            $type = "text";
                        ?>
                        <tr>
                            <td align="left"  width="21%">Amazon Access Key:</td>
                            <td width="74%" align="left" ><input
                                    name="aws_access_key" type="<?php echo $type ?>"
                                    class="inputbox" id="aws_access_key"
                                    value="<?php echo $aws_access_key ?>" size="60"
                                    <?php echo $disable ?> /></td>

                            <td width="2%">
                                <div class="tool">
                                    <a href="" class="tooltip"
                                       title="Amazon Access Key is generated for a unique user to access the amazon webservices.">
                                        <img src="../images/toolTip.png" alt="help" />                </a>            </div>        </td>
                        </tr>
                        <tr>
                            <td align="left"  width="21%">Amazon Secret Key:</td>
                            <td width="74%" align="left" ><input
                                    name="aws_secret_key" type="<?php echo $type ?>"
                                    class="inputbox" id="aws_secret_key"
                                    value="<?php echo $aws_secret_key ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td width="2%">
                                <div class="tool">
                                    <a href="" class="tooltip"
                                       title="Amazon Secret Key is generated for a unique user to access the amazon webservices.">
                                        <img src="../images/toolTip.png" alt="help" />                </a>            </div>        </td>
                        </tr>
                        <tr>
                            <td align="left"  width="21%">Allowed File
                                Types:</td>
                            <td width="74%" align="left" ><input
                                    name="allowed_file_types" type="text" class="inputbox"
                                    id="allowed_file_types"
                                    value="<?php echo $allowed_file_types ?>" size="60"
                                    <?php echo $disable ?> /></td>
                            <td width="2%">
                                <div class="tool">
                                    <a href="" class="tooltip"
                                       title="Types of files allowed to upload."> <img
                                            src="../images/toolTip.png" alt="help" />                </a>            </div>        </td>
                        </tr>
                        <tr>
                            <td align="left"  width="21%">Allow Cloud Front:</td>
                            <td width="74%" align="left" ><input
                                    name="cloud_fornt" type="checkbox" class="inputbox"
                                    id="cloud_fornt"
                                    <?php
                                    if ($cloud_fornt == '1')
                                        echo 'checked=""';
                                    ?>
                                    value="1" /></td>
                            <td width="2%">
                                <div class="tool">
                                    <a href="" class="tooltip"
                                       title="Allow wether to use Amazon Cloud Front Service for Streaming.">
                                        <img src="../images/toolTip.png" alt="help" />                </a>            </div>        </td>
                        </tr>
                    </table>
                </div>

                <div id="tab5" class="tab_content">
                    <table width="100%" border="0" align="center" cellpadding="5"
                           cellspacing="">

                        <tr>

                            <td align="left"  width="21%">Cookie Mode:</td>

                            <td width="74%" align="left" ><input
                                    class="inputbox" name="cookie_mode" type="radio" value="first"
                                    <?php echo $first_mode; ?> />First Cookie Wins <input
                                    class="inputbox" name="cookie_mode" type="radio" value="last"
                                    <?php echo $last_mode; ?> />Last Cookie Wins
                            <td width="2%">



                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Cookie Mode: Apply to all rapidresidual affiliates cookies">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                        <tr>

                            <td align="left"  width="21%">Expired on:</td>

                            <td  align="left" width="74%"><select
                                    name="cookie_expiry" class="inputbox" id="cookie_expiry"
                                    <?php echo $disable ?>>

                                    <?php
                                    $arr_cookie_expiry = array(
                                        '7',
                                        '30',
                                        '60',
                                        '90',
                                        '120',
                                        '360'
                                    );

                                    foreach ($arr_cookie_expiry as $evalue) {
                                        ?>

                                        <option
                                            value="<?php echo $evalue; ?>"
                                            <?php if ($evalue == $cookie_expiry) { ?> selected="selected"
                                            <?php } ?>><?php echo $evalue; ?></option>	

                                        <?php
                                    }
                                    ?>		

                                </select> Days</td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Expired on: This is the Expiry of Affiliate Cookies"> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>





                        <!--

            <tr>

            <td colspan="2" align="left"><font ><em><font color="#FF0000">(If set to Test Mode your payment buttons will link to the PayPal Sandbox for testing. You have to have accounts set up through</font> <a href="https://developer.paypal.com/" target="_blank">PayPal Sandbox</a> <font color="#FF0000">to use this feature.)</font></em></font></td>

</tr>

                        -->
                    </table>
                </div>

                <div id="tab6" class="tab_content">
                    <table width="100%" border="0" align="center" cellpadding="5"
                           cellspacing="">

                        <tr>

                            <td width="21%" align="left" valign="top" >Mailer:</td>

                            <td width="74%" align="left" ><select
                                    class="required" name="mailer" id="mailer"
                                    <?php echo $disable ?>>

                                    <option
                                    <?php
                                    if ($mailer == 'mail')
                                        echo 'selected';
                                    ?>
                                        value="mail">PHP Mail</option>

                                    <!--<option <?php
                                    if ($mailer == 'sendmail')
                                        echo 'selected';
                                    ?> value="sendmail">Sendmail</option> -->

                                    <option
                                    <?php
                                    if ($mailer == 'smtp')
                                        echo 'selected';
                                    ?>
                                        value="smtp">SMTP</option>
                                </select></td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Select which mailer for the delivery of site email."> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>



                        <tr>

                            <td width="21%" align="left" valign="top" >From
                                Email:</td>

                            <td width="74%" align="left" ><input
                                    name="email_from" type="text" class="inputbox email"
                                    id="email_from" value='<?php echo $email_from_name ?>' size="60"
                                    <?php echo $disable ?>></td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="From email The default from email which is used while sending all outgoing email.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>



                        <tr>

                            <td width="21%" align="left" valign="top" >From
                                Name:</td>

                            <td width="74%" align="left" ><input
                                    name="from_name" type="text" class="inputbox" id="from_name"
                                    value='<?php echo $from_name ?>' size="60"
                                    <?php echo $disable ?>></td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="From Name: The default from name which is used while sending all outgoing email.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>





                        <tr>

                            <td width="21%" align="left" valign="top" >SMTP
                                Security:</td>

                            <td width="74%" align="left" ><select
                                    name="smtpsecure" id="smtpsecure" <?php echo $disable ?>>

                                    <option value="none"
                                    <?php
                                    if ($smtpsecure == 'none')
                                        echo 'selected';
                                    ?>>None</option>

                                    <option value="ssl"
                                    <?php
                                    if ($smtpsecure == 'ssl')
                                        echo 'selected';
                                    ?>>SSL</option>

                                    <option value="tls"
                                    <?php
                                    if ($smtpsecure == 'tls')
                                        echo 'selected';
                                    ?>>TTL</option>
                                </select></td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Select Security Model for your SMTP server use"> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>





                        <tr>

                            <td width="21%" align="left" valign="top" >SMTP
                                Host:</td>

                            <td width="74%" align="left" ><input
                                    name="smtphost" type="text" class="inputbox" id="smtphost"
                                    value='<?php echo $smtphost ?>' size="60" <?php echo $disable ?>>							</td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Enter the name of the SMTP host."> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>





                        <tr>

                            <td width="21%" align="left" valign="top" >SMTP
                                Port:</td>

                            <td width="74%" align="left" ><input
                                    name="smtpport" type="text" class="inputbox number"
                                    id="smtpport" value='<?php echo $smtpport ?>' size="60"
                                    <?php echo $disable ?>></td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Enter SMPT Port. 25 is most unsercure server and 465 is for most scecure servers.">

                                        <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>
                        <tr>

                            <td width="21%" align="left" valign="top" >SMTP
                                Username:</td>

                            <td width="74%" align="left" ><input
                                    name="smtpusername" type="text" class="inputbox email"
                                    id="smtpusername" value='<?php echo $smtpusername ?>' size="60"
                                    <?php echo $disable ?>></td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Enter SMTP username to access server"> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>
                        <tr>

                            <td width="21%" align="left" valign="top" >SMTP
                                Password:</td>

                            <td width="74%" align="left" ><input
                                    name="smtppassword" type="password" class="inputbox alphanum"
                                    id="smtppassword" value='<?php echo $smtppassword ?>' size="60"
                                    <?php echo $disable ?>></td>

                            <td width="2%">

                                <div class="tool">

                                    <a href="" class="tooltip"
                                       title="Enter SMTP password to access server"> <img
                                            src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" >Email Footer
                                Details:</td>
                            <td align="left" ><textarea name="mail_footer"
                                                        rows="5" cols="45" class="inputbox" <?php echo $disable ?>><?php echo $mailer_details; ?></textarea></td>
                            <td valign="top"><a href="" class="tooltip"
                                                title="Email Footer Details : Here you should put contact information to be compliant with the Can-Spam Act. 

                                                It will be included at the bottom of all outgoing emails."><img
                                        src="../images/toolTip.png" alt="help" /></a></td>
                        </tr>
                        <?php if ($obj_pri->getRole() == 1) { ?>
                            <tr>

                                <td width="21%" align="left" >Send Test
                                    Email </td>

                                <td width="74%" align="left" ><input type="button"
                                                                     name="Button" class="button_disabled" id="testsmtp"
                                                                     value="Test Email Configuration" style="float: left" />
                                    <div id="loading"
                                         style="display: none; float: left; width: auto;">
                                        <img src="/images/admin/loader.gif" border="0"
                                             align="absmiddle" /> wait...								</div> <span id="page-content"></span></td>

                                <td width="2%">

                                    <div class="tool">

                                        <a href="" class="tooltip"
                                           title="Email Footer Details : Here you should put contact information to be compliant with the Can-Spam Act. 

                                           It will be included at the bottom of all outgoing emails.">

                                            <img src="../images/toolTip.png" alt="help" />									</a>								</div>							</td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>


            </div>
            <?php if ($obj_pri->getRole() == 1) {
                $disable = 'disable="disable"'; ?>
                <div style="width: 100%;float: left;margin: 10px 0px;">                            
                    <input type="submit" id="submitform" name="submit"	value="Update Site Settings" class="inputbox" >
                </div>   
            </form>

        <?php } ?>
    </div>
    <div class="content-wrap-bottom"></div>
</div>

<?php include_once("footer.php"); ?>