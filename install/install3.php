<?php session_start ();
	function curPageURL() {
		$pageURL = 'http';
		if (@$_SERVER ["HTTPS"] == "on") {
			$pageURL .= "s";
		}
			$pageURL .= "://";
			if ($_SERVER ["SERVER_PORT"] != "80") {
					$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] .$_SERVER ["REQUEST_URI"];
			} else {
					$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
			}
			return $pageURL;
	}
	$httppath = curPageURL ();
	$httppath = str_replace ( "install/install3.php", "", $httppath );
	while ( list ( $key, $value ) = @each ( $_POST ) ) 
	{
			$$key = $value;
	}
	if (isset ( $_POST ['submit'] )) 
	{
			// Set variables
			$host = $_POST ['host'];
		
			$dbname = preg_replace ( '/[^a-zA-Z0-9_-]/', '', $_POST ['dbname'] );
			$dbuser = preg_replace ( '/[^a-zA-Z0-9_-]/', '', $_POST ['uname'] );
			$dbpass = preg_replace ( '/[^a-zA-Z0-9_-]/', '', $_POST ['pass'] );
			
			$prefix = 'rrp_';
			$bpath = $sitepath . "/images/marketing/";
			$bdispath = $httppath . "/images/marketing/";
			$sitepath = $_POST ['sitepath'];
			$httppath = $_POST ['httppath'];
			@mkdir ($sitepath.$_POST ['prot_down'],0777);
			@mkdir ($sitepath.$_POST ['swf_down'],0777);
		    $_SESSION['prot_down'] = $_POST ['prot_down'];
			$_SESSION['swf_down'] 	 = $_POST ['swf_down'];
			
			$pt = "/{{(.*?)}}/e";
			/********** Validating database,user and pass ***********/
			$_SESSION ['dbinfo'] ['dbname'] = $dbname;
			$_SESSION ['dbinfo'] ['dbuser'] = $dbuser;
			@$conn = mysql_connect ( $host, $uname, $pass );
			if ($conn) {
					$dbb = mysql_select_db ( $dbname, $conn );
					if ($dbb) {
					} else {
							$_SESSION ['dbinfo'] ['error'] = 'Database not Found.';
					
							header ( "Location: install3.php" );
				exit ();
					}
			} else {
					//echo "Invalid database username or password.";	exit;
					$_SESSION ['dbinfo'] ['error'] = 'Invalid Database username or password.';
					header ( "Location: install3.php" );
					exit ();
			}
			// Create the config file			
		$config = "<?php \n";
		$config .= "error_reporting(E_ERROR); \nif (!ini_get('display_errors')) { ini_set('display_errors', 1); }\n";
		$config .= "\$host = \"$host\";\n";
		$config .= "\$dbname = '".$dbname."';\n";
		$config .= "\$dbuser = '".$uname."';\n";
		$config .= "\$dbpass = '".$pass."';\n";
		$config .= "\$prefix = \"$prefix\";\n";
		$config .= "\$root_path = \"$sitepath\";\n";
		$config .= "\$_SERVER[DOCUMENT_ROOT] = \"$sitepath\";\n";
		$config .= "\$http_path  = \"$httppath\";\n";
		$config .= "\$mem_cookie  = \"member\";\n";
		$config .= "\$Ptn  = \"$pt\";\n";
		$config .= "\$bannerimg_upload_path  = \"$bpath\";\n";
		$config .= "\$bannerimg_display_path  = \"$bdispath\";\n";
		$config .= "\$cookie_length  = 365*24*60*60;\n";
		$config .= "\$http_prod_path  = \"$httppath\";\n";
		$config .= "\$debug  = \"0\";\n";
		$config .= "?>";
		// Write the config file
		if($fp = fopen ( "../common/config.php", "w" ))
		{
			fwrite ( $fp, $config );
			fclose ( $fp );
		}
		else
		{
			// Config file has wrong permissions so show error message
			$error = '
			<div align="center">
			<table witdh="70%" align="center" style="border: 4px dashed red; background-color: #ffffde; margin: 20px; padding: 15px;">
			<tr>
			<td align="center">
			<h2 color="#cc0000"> Error! Config File Cannot Be Opened</h2>
			<p>Have you ensured that <strong>common/config.php</strong> has permissions of 777 (or 755 on some webhosts)?</p>
			<p>Please use your back button to go back and try again after checking your config file permissions</p>
			</td>
			</tr>
			</table>
			</div>';
			echo $error;
			exit ();
		}
		
		$path = dirname ( $_SERVER ["SCRIPT_NAME"] );
		if ($path == "\\")
			$path = "/";
		else
			$path .= "/";
		$path = str_ireplace ( 'install/', '', $path );
			$ourFileName = "../.htaccess";
			if ($fh = fopen ( $ourFileName, 'w' )) {
			$host_www = str_replace ( 'www.', '', $_SERVER ['HTTP_HOST'] );
			$host_name = str_replace ( '.', '\.', $host_www );
			$stringData =  "RewriteEngine On \n";
			$stringData .= "RewriteBase  $path \n";
			$stringData .= "RewriteCond %{HTTP_HOST} ^$host_name \n"; 
			$stringData .= "RewriteRule ^(.*)$ http://www.$host_www/$1 [R=permanent,L] \n";
			$stringData .= "RewriteRule go/(.*) go.php?var1=$1&var2=$2&var3=$3 \n";
			$stringData .= "RewriteRule goto/(.*) goto.php?var1=$1&var2=$2&var3=$3 \n"; 
			$stringData .= "RewriteRule likes/(.*) likes.php?var1=$1&var2=$2 \n";
			$stringData .= "RewriteRule to/(.*) to.php?var1=$1&var2=$2&var3=$3 \n";
			$stringData .= "RewriteRule referrer/(.*) referrer.php?vars=$1 \n";
			$stringData .= "RewriteRule videos/(.*) download-media.php?video=$1 \n";
			$stringData .= "RewriteRule audios/(.*) download-media.php?audio=$1 \n";
			$stringData .= "RewriteRule download/(.*) download-media.php?download=$1 \n";
			$stringData .= "ErrorDocument 404 /content.php?page=404 \n"; 
		 	$stringData .= "ErrorDocument 403 /content.php?page=403 \n";
			$stringData .= "RewriteRule buynow/(.*)/(.*) buynow.php?pid=$1&gateway=$2 \n";
			$stringData .= "RewriteRule buynow/(.*) buynow.php?pid=$1 \n";
			$stringData .= "RewriteRule yes/(.*) buy.php?var1=$1 \n";
			 
			fwrite ( $fh, $stringData );
			fclose ( $fh );
			} else {
			echo '<div align="center">
			<table witdh="70%" align="center" style="border: 4px dashed red; background-color: #ffffde; margin: 20px; padding: 15px;">
			<tr>
			<td align="center">
			<h2 color="#cc0000"> Error! .htaccess File Cannot Be Opened</h2>
			<p>Have you ensured that <strong>.htaccess</strong> has permissions of 777 (or 755 on some webhosts)?</p>
			<p>Please use your back button to go back and try again after checking your .htaccess file permissions</p>
			</td>
			</tr>
			</table>
			</div>';
		}
	
	header ( "Location: install4.php" );
	} else {
		include_once 'header.php';
		?>
	<div id="main">
	<?php
	
			if ($_SESSION ['dbinfo'] ['error']) {
				?>
				<div class="errorinstaller"><img src="../images/crose.png" align="absmiddle">
			<?php	echo $_SESSION ['dbinfo'] ['error'];?>
					</div>
					 <?php
			}
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
	<div><img src="images/step1-mouseoff_205x33.png" border="0" /></div>
	<div><img src="images/step2-mouseoff_205x33.png" border="0" /></div>
	<div><img src="images/step3-mouseon_212x40.png" border="0" /></div>
	<div><img src="images/step4-mouseoff_205x35.png" border="0" /></div>
	<div><img src="images/step5-mouseoff_205x33.png" border="0" /></div>
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
			<TD bgcolor="#FFFFFF" colspan="3" valign="top"><!-- End Header Code -->
			<form action="install3.php" method="post" name="form1" id="form1"><br>
			<table border=0 width="100%" align="center">
				<tr>
					<td class="tbtext" colspan="2">
					<h1>Database Details</h1>					</td>
				</tr>
				<tr>
					<td width="22%" align="left" nowrap="nowrap" class="tbtext">Hostname* (normally
					localhost)</td>
					<td width="78%" align="left" class="tbtext"><input type=text
						name=host class="required" value="localhost">
					<span style="color:#555;font-size:10px">Mostly Localhost</span></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap" class="tbtext">Database Name*</td>
					<td class="tbtext" align="left"><input type="text" name="dbname" class="required" value="<?php echo $_SESSION ['dbinfo'] ['dbname']; ?>"> 
					<span style="color:#555;font-size:10px">example: username_dbname</span></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap" class="tbtext">Database UserName*</td>
					<td class="tbtext" align="left"><input type="text" name="uname" class="required" value="<?php echo $_SESSION ['dbinfo'] ['dbuser'];	?>"> 
					<span style="color:#555;font-size:10px">Database username</span></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap" class="tbtext">Database Password</td>
					<td class="tbtext" align="left"><input type="password" name="pass"> 
					<span style="color:#555;font-size:10px">Database password</span> </td>
				</tr>
				<!--  <tr>
										<td class="tbtext" align="left">Database Prefix*</td>
										<td class="tbtext" align="left"><input type=text name=prefix class="required" value="rrp_"></td>
										</tr>
				-->
				<tr>
					<td class="tbtext" colspan="2" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td class="tbtext" colspan="2" align="left"><h1>Website Path	Information</h1></td>
				</tr>
				<tr>
					<td class="tbtext" colspan="2" align="center">
					<div style="background-color: #ffffdd; padding: 10px;"><B>Site
					Physical Path - Installation Directory</b><br>
					<br>
					<p align="left">The installer script has attempted to determine your
					Site Physical Path directory where Rapid Residual Pro &trade; will
					be installed. We have copied this path into the Site Physical Path
					box for you. <Br>
					<Br>
					Please ensure that this is the correct directory you want to install
					Rapid Residual Pro &trade; into, or change it to the correct
					directory.</p>
					</div>					</td>
				</tr>
										<?php
					$install = getcwd ();
					$path = dirname ( $install ) . "";
					$path = str_replace ( "\\", "\\\\", $path );
					?>
										<tr>
					<td align="left" nowrap="nowrap" class="tbtext">Site Physical Path*</td>
					<td class="tbtext" align="left"><input type="tex" t name="sitepath"
						size="40" value="<?php
			echo $path;
			?>" class="required">
					  <br />
					<span style="color:#555;font-size:10px">(/home/username/public_html/domain) (full server path)</span></td>
				</tr>
				<tr>
					<td nowrap="nowrap"><span class="tbtext">Site http Path*</span></td>
					<td><input type="text" name="httppath"	size="40" value="<?php	
					$httppath=str_replace('http://','',$httppath);
					$httppath=str_replace('www.','',$httppath);
					echo substr ( 'http://www.'.$httppath, 0, - 1 );?>" class="required" />
					  <br />
					<span style="color:#555;font-size:10px">(http://www.mydomain.com)</span></td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap" class="tbtext">Media Directory [Audios/Videos] </td>
				  <td class="tbtext" align="left"><input type="text" name="prot_down" size="40" value="/images/media/" class="required" />
				    <br />				    <span style="color:#555;font-size:10px">(/images/media/)</span></td>
				  </tr>
				<tr>
				  <td align="left" nowrap="nowrap" class="tbtext">Document Directory </td>
				  <td class="tbtext" align="left"><input type="text" name="swf_down" size="40" value="/images/documents/"	class="required" />
				    <br />				    <span style="color:#555;font-size:10px">(/images/documents/)</span></td>
				  </tr>
				<tr>
					<td align="left" nowrap="nowrap" class="tbtext">&nbsp;</td>
					<td class="tbtext" align="left">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="right"><br>
					<br>
					<input type="hidden" name="step2" id="step2" value="yes" /> <input
						type="submit" name="submit" value="Proceed to Step 4" class="inputbox"></td>
				</tr>
			</table>
			</form>
			<!-- Start Footer Code --></TD>
		</TR>
		
			  
		</TABLE>
	</div>
	<br />
	</div>
	</div>
	</div>
	<!-- end of content --></div>
	</div>
	<!-- end of wrap -->
			
			</div>
	<!-- end of main -->
	<?php
			unset ( $_SESSION ['dbinfo'] );
			include_once 'footer.php';
	}
	?>