<?php
error_reporting(1);
include_once 'header.php';
?>
<div id="main">
    <?php echo $msg = "<div class='warning'><img src='../images/warning.png' align='absmiddle'>Please check the following before you procced with the installation. You will encounter errors if permissions are not set right.</div>"; 
	
	
	if(!extension_loaded('ionCube Loader')){
		echo $msg = "<div class='error'><img src='../images/warning.png' align='absmiddle'>Site error: the file  ionCube PHP Loader v4.0.x is required for this application.</div>";
	}
	
	if ($installation == 'yes') {
	$disabled="disabled=disabled;";
	$style="style='font-color:#666;'";
	echo $msg = "<div class='success' style='width: 816px;'><img src='../images/tick.png' align='absmiddle'> RapidResidualPro is successfully Installed.Please remove the install directory inorder to work site properly,<br> if deleted click on this link <a href='../index.php'>Click here</a>.</div>";
		} 
		else {$style='';}			
	 ?>
        <div class="wrap">
            <div class="wrap2">
                <div class="content">
                    <div class="container">
                        <div class="content-1">
                            <div class="ins_left">
                                <div id="stepbar">
                                    <div class="t">
                                        <div class="t">
                                            <div class="t"></div>
                                        </div>
                                    </div>
                                    <div class="m">
                                        <div>
                                            <img src="images/step1-mouseon_212x41.png" border="0" />
                                        </div>
                                        <div>
                                            <img src="images/step2-mouseoff_205x33.png" border="0" />
                                        </div>
                                        <div>
                                            <img src="images/step3-mouseoff_205x33.png" border="0" />
                                        </div>
                                        <div>
                                            <img src="images/step4-mouseoff_205x35.png" border="0" />
                                        </div>
                                        <div>
                                            <img src="images/step5-mouseoff_205x33.png" border="0" />
                                        </div>
                                    </div>
                                    <div class="b">
                                        <div class="b">
                                            <div class="b"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ins_right">
							
                                <TABLE width="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                                    <TR>
                                        <TD bgcolor="#FFFFFF" colspan="3" valign="top">
                                            <!-- End Header Code -->	
                                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td valign="top">
                                                        <br>
                                                        <b>Some hosts require permissions to be 755.<br>
    																If you encounter problems with permissions at 777 then try 755.</b>
                                                        
                                                       
														<fieldset style="padding:10px;">
															<legend>Installation</legend>
																<p>
																
																<input type="radio" name="install" value="1" <?php echo $disabled?> onclick="document.getElementById('next').disabled=false;"/>
																<span <?php echo $style?>> New installation</span>
																<br>
																<input type="radio" name="install" value="2" onclick="checkinstallation();" /> Repair installation <span id="wait"></span>
																<p>
																
														</fieldset>
														
                                                        <br>
														<fieldset style="padding:10px;">
														<legend>Notices</legend>
                                                        <ul style="padding-left:20px;">
															
															<li style="list-style:disc">Red highlight means the permissions need to be changed.</li>
                                                            <li style="list-style:disc">Green highlight means it should be set right.</li>
                                                            <li><p><a href="index.php"> Click here to refresh page to recheck permissions</a>.</p></li>
                                                        </ul>
														</fieldset>
                                                        <br>
                                                       
                                                        <table width="100%" border="0" cellspacing="1" cellpadding="8" >
                                                            <tr>
                                                                <th class="tbtext" style="border:0px;"><b>File/Folder Name</b></th>
                                                                <th class="tbtext" style="border:0px;"><div align="left"><b>Minimum Requirement </b></div></th>
                                                            <th class="tbtext" style="border:0px;"><strong>Current Settings </strong></th>
                                                </tr>
                                                <?php
                                                // Setting File names for whome permissions to be checked
                                                $arr_files = array('../common/config.php',
                                                    '../ipn/paypal/ipn.log',
                                                    '../images/marketing',
                                                    '../images/uploads',
                                                    '../dumper',
                                                    '../dumper/csv',
                                                    '../dumper/sql_dumps',
                                                    '../images/documents',
                                                    '../images/media',
                                                    '../images/payment_buttons',
                                                    '../templates_c',
                                                    '../templates',
                                                    '../member/templates_c',
                                                    '../.htaccess'
                                                );

                                                $perm = '0777';
                                                $arr_chk_perm = 1;
                                                chmodDirectory("../templates", 0);
                                                foreach ($arr_files as $value) {
                                                    chmod($value, 0777);
                                                    clearstatcache();
                                                    $configmod = substr(sprintf('%o', fileperms($value)), -4);
                                                    if ($configmod != '0777') {
                                                        //$arr_chk_perms[] = 'no';
                                                    }
                                                    // $trcss = (($configmod != $perm) ? "background-color:#fd7a7a;";$arr_chk_perm1 : "background-color:#91f587;");
                                                    if ($configmod != $perm) {
                                                        $trcss = "background-color:#fd7a7a;";
                                                        $arr_chk_perm = 0;
                                                    } else {
                                                        $trcss = "background-color:#91f587;";
                                                    }
													$dir=str_replace('../','',$value);
                                                    echo "<tr class=\"tbtext\" style=" . $trcss . ">";
                                                    echo " <td class=\"tbtext\" style=\"border:0px;\">" . $dir . "</td>";
                                                    echo " <td class=\"tbtext\" style=\"border:0px;\" > $perm</td>";
                                                    echo " <td class=\"tbtext\" style=\"border:0px;\" > $configmod</td>";
                                                    echo "</tr>";
                                                }

                                                if ((int) ini_get('upload_max_filesize') < '50') {
                                                    $trcss = "background-color:#fd7a7a;";
                                                    $arr_chk_perm = 0;
                                                }
                                                else
                                                       $trcss= "background-color:#91f587;";
                                                echo "<tr class=\"tbtext\" style=" . $trcss . ">";

                                                if ((int) ini_get('upload_max_filesize') < '50') {
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">upload_max_filesize</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">50M</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">" . ini_get('upload_max_filesize') . "</td>";
                                                }
                                                  echo "</tr>";
                                                    
                                                   
													
													
													 if ((int) ini_get('max_execution_time') < 400) {
                                                        $trcss = "background-color:#fd7a7a;";
                                                        $arr_chk_perm = 0;
                                                    }

                                                    else
                                                        $trcss= "background-color:#91f587;";

                                                    echo "<tr class=\"tbtext\" style=" . $trcss . ">";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">max_execution_time</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">400s</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">" . ini_get('max_execution_time') . "s</td>";
                                                    echo "</tr>";
													
													 if ((int) ini_get('memory_limit') < 256) {
                                                        $trcss = "background-color:#fd7a7a;";
                                                        $arr_chk_perm = 0;
                                                    }

                                                    else
                                                        $trcss= "background-color:#91f587;";

                                                    echo "<tr class=\"tbtext\" style=" . $trcss . ">";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">memory_limit</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">256M</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">" . ini_get('memory_limit') . "</td>";
                                                    echo "</tr>";
													
													
													if ((int) ini_get('max_file_uploads') < 80) {
                                                        $trcss = "background-color:#fd7a7a;";
                                                        $arr_chk_perm = 0;
                                                    }

                                                    else
                                                        $trcss= "background-color:#91f587;";

                                                    echo "<tr class=\"tbtext\" style=" . $trcss . ">";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">max_file_uploads</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">80M</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">" . ini_get('max_file_uploads') . "M</td>";
                                                    echo "</tr>";
													
													
													if ((int) ini_get('post_max_size') < 80) {
                                                        $trcss = "background-color:#fd7a7a;";
                                                        $arr_chk_perm = 0;
                                                    }

                                                    else
                                                        $trcss= "background-color:#91f587;";

                                                    echo "<tr class=\"tbtext\" style=" . $trcss . ">";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">post_max_size</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">80M</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">" . ini_get('post_max_size') . "</td>";
                                                    echo "</tr>";
													
													if ( (int) ini_get('safe_mode') == 1) {
                                                        $trcss = "background-color:#fd7a7a;";
                                                        $arr_chk_perm = 0;
														$mode ='On';
                                                    }

                                                    else
													 {
                                                        $trcss= "background-color:#91f587;";
														$mode ='Off';
														}

                                                    echo "<tr class=\"tbtext\" style=" . $trcss . ">";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">safe_mode</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">Off</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">" . $mode . "</td>";
                                                    echo "</tr>";
													
													
                                                    if (ini_get('file_uploads') == '0') {
                                                        $trcss = "background-color:#fd7a7a;";
                                                        $arr_chk_perm = 0;
                                                    }
                                                    else
                                                        $trcss= "background-color:#91f587;";

                                                    if (ini_get('file_uploads'))
                                                        $file_uploads = 'On'; 
                                                    else
                                                        $file_uploads= "Off";
                                                    
                                                    echo "<tr class=\"tbtext\" style=" . $trcss . ">";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">allow_url_include</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">On</td>";
                                                    echo "<td class=\"tbtext\" style=\"border:0px;\">" . $file_uploads . "</td>";
                                                    echo "</tr>";
                                                    ?>



                                                </table>

                                                <?php
                                                $arr_chk_perms = 1;
                                                ?>

                                                <br>



                                                <br>



                                                <a name="comp">



                                                    <?php
                                                    if ($arr_chk_perms == 0) {



                                                        echo "<form method='post' action='install2.php' class='frm_right'><input type='button' value='Proceed to Next Step' disabled='disabled'></form";
                                                    } else {
                                                        ?>



                                                        <form method="post" action="install2.php" class="frm_right">



                                                            <input type="hidden" name="step1" id="step1" value="yes" />



                                                            <input type="submit" value="Proceed to Next 2" id="next" name="next" disabled="disabled">



                                                        </form>	



                                                        <?php
                                                    }
                                                    ?>



                                                </a>  </td>



                                        </tr>



                                    </table>



                                    <!-- Start Footer Code -->	



                                    </TD>



                                    </TR>



                                    <TR>	



                                        <TD height="98" background="../images/admin/admin_red1_ftbg.jpg"><div align="right" valign="bottom" class="tbtext">



                                            </DIV></TD>	



                                        <TD class="copyright" colspan="3" background="../images/admin/admin_bg.jpg" valign="bottom" align="center">



                                    <TR>



                                        <TD class="copyright" colspan="3" background="../images/admin/admin_bg.jpg" valign="bottom" align="center">

                                    </TR>



                                    </TABLE>



                                </div>



                                <br />



                            </div>



                        </div>



                    </div><!-- end of content -->



                </div>



            </div><!-- end of wrap -->



       

    </div><!-- end of main -->



    <?php

    function chmodDirectory($path = ".", $level = 0) {

        $ignore = array('images', '.', '..');



        $dh = @opendir($path);



        while (false !== ( $file = readdir($dh) )) { // Loop through the directory
            if (!in_array($file, $ignore)) {

                if (is_dir("$path/$file")) {

                    chmod("$path/$file", 0777);

                    chmodDirectory("$path/$file", ($level + 1));
                } else {

                    chmod("$path/$file", 0777); // desired permission settings
                }//elseif
            }//if in array
        }//while



        closedir($dh);
    }

    //function

    include_once 'footer.php';
    ?>
	<script>
function checkinstallation()
{
	
	
	  xmlhttp=new XMLHttpRequest();
	  document.getElementById('wait').innerHTML='Wait...';
	  
	  xmlhttp.onreadystatechange=function(){
		  if (xmlhttp.readyState==4 && xmlhttp.status==200){
		 //alert(xmlhttp.responseText);
		  	if(xmlhttp.responseText=='yes')
			{
		 		document.getElementById('wait').innerHTML= '<span style="color:grean"><img src="/images/tick.png" alt="Verified" align="absmiddle" height="14"/> Ready for fresh installation</span>';
				document.getElementById('next').disabled=false;
			}
			else
			{
				document.getElementById('wait').innerHTML= '<br><span style="color:red">Rapid Residual Pro is not Installed. Please select a new installation option.</span>';
				document.getElementById('next').disabled=true;
			}
			
		  }
	
	  }
	  xmlhttp.open("GET","chk_installation.php",true);
	  xmlhttp.send();
	
	
}
</script>