<?php
session_start();
include_once("session.php");
include_once("header.php");
$GetFile = file("../html/admin/addmessages.html");
$Content = join("",$GetFile);
$mid= $_GET['mid'];
$pshort = $_GET['pid'];
$today	= date("Y-m-d H:i:s");

$q = "select * from ".$prefix."products where pshort = '$pshort'";
$r22 = $db->get_a_line($q);
$prod = $r22['product_name'];
$Title = "Add Coaching Message For ".$prod;


if (isset($_POST['submit']))
{
	// Parse form data
	$message 	=  $_POST["message"];
	$product 	=  addslashes($_POST["product"]);
	$mid		=  addslashes($_POST["mid"]);

	// Get admin email and site email details
	$q = "select email_from_name, mailer_details from ".$prefix."site_settings";
	$a = $db->get_a_line($q);
	@extract($a);

	$q = "select webmaster_email from ".$prefix."admin_settings";
	$b = $db->get_a_line($q);
	@extract($b);


	// send new member coaching message email to admin
	$q = "select subject, message as message2 from ".$prefix."emails where type='Email sent to member for new Admin coaching message'";
	$r = $db->get_a_line($q);
	@extract($r);

	// Get member details
	$q = "select email, firstname, lastname from ".$prefix."members where id='$mid'";
	$r = $db->get_a_line($q);
	extract($r);

	// Get product name
	$q = "select product_name as productname from ".$prefix."products where pshort='$product'";
	$rb = $db->get_a_line($q);
	extract($rb);

	$loginurl = $http_path."/member/index.php";
	$message = stripslashes($message);
	$message= str_replace("\'", "'", $message);

	$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
	$message2 = preg_replace("/{(.*?)}/e","$$1",$message2);
	$message2 = $message2."\r\n\r\n".$mailer_details;
	$header	= "From: ".$email_from_name." <".$webmaster_email.">";
		
	$common->sendemail($email_from_name,$webmaster_email,$email,$subject,$message2,$header);

	// File uploading section starts
			
	if($_FILES["file"]['name'])
	{
	
	if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "application/pdf") || ($_FILES["file"]["type"] == "application/msword") || ($_FILES["file"]["type"] == "application/vnd.ms-excel")|| ($_FILES["file"]["type"] == "application/vnd.ms-powerpoint")|| ($_FILES["file"]["type"] == "text/plain"))
){
		if($_FILES["file"]["size"] <= $_POST['max_size']){
			if ($_FILES["file"]["error"] > 0)
			{
				$_SESSION['notification']['error_status'] = 'yes';
 	 			$_SESSION['notification']['msg'] = $_FILES["file"]["error"];
			}else
			{
				$file_path = '../document/';
		    	$file_path = $file_path .time().$_FILES["file"]["name"];
		    	if (file_exists("../document/" . $_FILES["file"]["name"]))
		      	{
		      		$_SESSION['notification']['error_status'] = 'yes';
 	 				$_SESSION['notification']['msg'] = $_FILES["file"]["name"] . 'already exists.';		
		      	}
		    	else
		      	{
		      		move_uploaded_file($_FILES["file"]["tmp_name"], $file_path);
		      	}
    		}
		}else{
			$_SESSION['notification']['error_status'] = 'yes';
 	 		$_SESSION['notification']['msg'] = 'File size exceeds the limit, file size must be equal to or less than 5 MB.';		
		}
		}else
 	 	{
	  		$_SESSION['notification']['error_status'] = 'yes';
 	 		$_SESSION['notification']['msg'] = 'File format not valid.';
	    }
	}  
	// File uploading section ends


	$message = addslashes($message);
	$message= str_replace("$", "$ ", $message);
	$message= str_replace("\r\n", "<br>", $message);
	
	if($_SESSION['notification']['error_status'] == 'yes'){
		$_SESSION['msgbody'] = $_POST["message"];
		header("Location: addmessage.php?pid=".$product."&mid=".$mid);
		exit;
	}else{
		unset($_SESSION['notification']);
		// Update database
		$set	= "message='$message'";
		$set	.= ", upload_file='$file_path'";
		$set	.= ", mid='$mid'";
		$set	.= ", product='$product'";
		$set	.= ", admin='1'";
		$set	.= ", date_added='$today'";
		$pid = $db->insert_data_id("insert into ".$prefix."member_messages set $set");
		header("Location: member_messages.php?mid=$mid&product=$product&msg=a");
	}
}
?>
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<?php if($_SESSION['notification']){ ?>
	<div class='error'><img src='/images/crose.png' border='0'> <?php echo $_SESSION['notification']['msg'];?> </div>
<?php } ?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo $Title ?></strong></p>
<br>
<a id="pagination"></a>

<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="50%" valign="middle">&nbsp;</td>
	 <td align="right">
	 <div class="buttons">
     	<a style="cursor:pointer;" href="member_messages.php?mid=<?php echo $mid;?>&product=<?php echo $pshort;?>">Go Back</a>
	 </div>
	 
	 </td>
	 </tr>	
</table>
 
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="50%" valign="middle">
	<form action="addmessage.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="mid" value="<?php echo $mid;?>">
<input type="hidden" name="product" value="<?php echo $pshort;?>">
<table width="95%" align="center" border="0">
      <tr>
	<td colspan="2" align="left" class="logotext">
	<b>Message:</b>	</td>
	</tr>
<tr>
	<td colspan="2" align="left" class="logotext">
	<b>
		<label>
		<textarea class="inputbox" name="message" cols="107" rows="10"><?php echo $_SESSION['msgbody'];?></textarea>
		</label>
	</b>
	</td>
	</tr>

<tr>
  <td colspan="2" align="left" class="tbtext">
  	<b>Upload File:</b>
  </td>
  </tr>
<tr>
  <td colspan="2" align="left" class="tbtext"></td>
</tr>
<tr>
  <td colspan="2" align="left" class="tbtext">  
   <input type="hidden" name="max_size" id = "max_size" value="5242880" />
   <input type = "hidden" name="uploaded_file" id="uploaded_file" value="<?php echo $uploaded_file?>">
	<input type="file" name="file" id="file" />&nbsp; <?php if($uploaded_file){ ?><a href="<?php echo $uploaded_file;?>" target="_blank">View File</a><?php } ?>
	(<strong>File Formats:</strong> jpg, png, gif, txt, doc, ppt, xls, pdf)
</td>
</tr>

<tr>
  <td class="logotext" align="left">&nbsp;</td>
  <td class="logotext" align="left">&nbsp;</td>
</tr>

<tr>
<td colspan="2" align="left">
	<input type="submit" name="submit" value="Save"  class="inputbox"> 
	<input type="reset" name="Reset" value="Reset" class="inputbox"></td>
</tr>
</table>
</form>

	 </td>
	 <td>&nbsp;</td>
	 </tr>	
</table>
	

</div>
<div class="content-wrap-bottom"></div>
</div>
<?php 
unset($_SESSION['notification']); unset($_SESSION['msgbody']);
include_once("footer.php");
?>