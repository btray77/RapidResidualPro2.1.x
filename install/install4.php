<?php session_start();
if (isset($_POST['submit']))
	{
// Set variables
	$admin_login		= trim(stripslashes($_POST['admin_login']));
	$admin_email		= trim(stripslashes($_POST['admin_email']));
	$paypal_email		= trim(stripslashes($_POST['paypal_email']));
	$license_key  		= trim(stripslashes($_POST['license_key']));
	$admin_pass		    = md5(trim(stripslashes($_POST['admin_pass'])));
	 $prot_down			= $_SESSION['prot_down'];
    $swf_down			= $_SESSION['swf_down']; 
	
	//$member_pass		= md5(trim(stripslashes($_POST['member_pass'])));
	// Create the tables
	include("createtables.php");
	header("Location:install5.php");
	}
else
	{
	include_once 'header.php';
?>
		<div id="main">
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
                                            <img src="images/step1-mouseoff_205x33.png" border="0" />
                                        </div>
                                        <div>
                                            <img src="images/step2-mouseoff_205x33.png" border="0" />
                                        </div>
                                        <div>
                                            <img src="images/step3-mouseoff_205x33.png" border="0" />
        	                               </div>
                                       <div>
                                           <img src="images/step4-mouseon_212x40.png" border="0" />
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
                                <form action="install4.php" method="post" name="form1" id="form1">
                                    <table border="0" align="center" width="100%">
                                          <tr>
                                    <td class="tbtext" colspan="2" align="left"><br><h1>Admin Details</h1></td>
                                    </tr>
                                          <tr>
                                    <td width="27%"  align="left" class="tbtext">Admin Login*</td>
                                    <td width="73%" align="left" class="tbtext"><input type="text" name="admin_login" class="required" ></td>
                                    </tr>
                                    <tr>
                                    <td class="tbtext" align="left">Admin Password*</td>
                                    <td class="tbtext" align="left"><input type="password" name="admin_pass" class="required"></td>
                                   </tr>
                                    <tr>
                                    <td class="tbtext" align="left">Admin Email*</td>
                                    <td class="tbtext" align="left"><input type="text" name="admin_email" class="required"></td>
                                    </tr>
                                    <tr>
                                   <td class="tbtext" align="left">PayPal Email*</td>
                                    <td class="tbtext" align="left"><input type="text" name="paypal_email" class="required"></td>
                                    </tr>
                                    <tr>
                                    <td class="tbtext" align="left">License*</td>
                                    <td class="tbtext" align="left"><input type=text name=license_key class="required" value="" onblur="verifykey(this.value)">
									<span id="status" style="padding-left:10px;"></span></td>
                                    </tr>
                                    <tr>
                                    <td colspan="2" align="right">
                                    <br><br>
                                    <input type="hidden" name="step3" id="step3" value="yes" />
                                    <input type="submit" name="submit" value="Finish Installation" class="inputbox" id="finished" disabled="disabled"> 
                                    </td>
                                    </tr>
                                    </table>
                                 </form>
                            </div>    
                                <br />
                        </div>
						</div>
					</div><!-- end of content -->
				</div>
			</div><!-- end of wrap -->
		</div><!-- end of main -->
<script>
function verifykey(key)
{
	if(key){
	
	  xmlhttp=new XMLHttpRequest();
	  document.getElementById('status').innerHTML='Wait...';
	  
	  xmlhttp.onreadystatechange=function(){
		  if (xmlhttp.readyState==4 && xmlhttp.status==200){
		  	if(xmlhttp.responseText==1)
			{
		 		document.getElementById('status').innerHTML= '<span style="color:grean"><img src="/images/tick.png" alt="Verified" align="absmiddle" height="14"/> Verified</span>';
				document.getElementById('finished').disabled=false;
			}
			else
			{
				document.getElementById('status').innerHTML= '<span style="color:red"><img src="/images/crose.png" alt="Verified" align="absmiddle" height="14" /> Incorrect</span>';
				document.getElementById('finished').disabled=true;
			}
			
		  }
	
	  }
	  xmlhttp.open("GET","checkkey.php?key="+key,true);
	  xmlhttp.send();
	
	}
}
</script>
<?php 

	include_once 'footer.php';

	}

?>