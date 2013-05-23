<?php
session_start();
include_once("session.php");
include_once("header.php");
$Title = "Edit Message";
$GetFile = file("../html/admin/edit_message.html");
$Content = join("",$GetFile);
$mid= $_GET['mid'];
$id= $_GET['id'];

if (isset($_POST['submit']))
{
	
	
	// Parse form input through Post
	$message = addslashes($_POST["message"]);
	$id = $_POST["id"];
	$mid = $_POST['mid'];
	$product = $_POST['product'];
	
	
	
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
	}else{
		$file_path = $_POST['uploaded_file'];		
	} 
	// File uploading section ends

	if($_SESSION['notification']['error_status'] == 'yes'){
		$_SESSION['msgbody'] = $_POST["message"];
		header("Location: addmessage.php?pid=".$product."&mid=".$mid);
		header("Location: edit_message.php?id=".$id."&mid=".$mid."&product=".$product);
		exit;
	}else{
		unset($_SESSION['notification']);
		// Write to database
		// Set Data to be inserted into database
		$set = "message	= '$message'";
		$set .= ",upload_file='$file_path'";
		$db->insert("update ".$prefix."member_messages set $set where id = '$id'");
		$msg = "e";
		header("Location: member_messages.php?msg=e&mid=$mid&product=$product");
	}
	
}
else
{
	// Get data to populate fields on page
	$sql = "select * from ".$prefix."member_messages where id = '$id'";
	$rs = $db->get_a_line($sql);
	$message = stripslashes($rs['message']);
	$message= str_replace("<br>", "\r\n", $message);
	$uploaded_file = $rs['upload_file'];
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
     	<a style="cursor:pointer;" href="member_messages.php?mid=<?php echo $mid;?>&product=<?php echo $product;?>">Go Back</a>
	 </div> 
	 </td>
	 </tr>	
</table>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="50%" valign="middle">
	<form name="editmessage" action="edit_message.php" method="post" enctype="multipart/form-data" onsubmit="return formCheck(editpage);">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="mid" value="<?php echo $mid;?>">
<input type="hidden" name="product" value="<?php echo $product;?>">

<table width="95%" align="center" border="0">
<tr>
  <td colspan="2" align="left" class="tbtext"><b>Coaching Message:</b></td>
  </tr>
<tr>
  <td colspan="2" align="left" class="tbtext"></td>
</tr>
<tr>
  <td colspan="2" align="left" class="tbtext">  
   <textarea name="message" cols="107" rows="8" class="inputbox" id="message"><?php if($_SESSION['msgbody']){ echo $_SESSION['msgbody']; }else{ echo $message; }?></textarea>
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
	<input type="file" name="file" id="file" />&nbsp; 
	<?php
		if($uploaded_file){ 
	?>
		<a href="<?php echo $uploaded_file;?>" target='_new'>
			<img src='/images/1297768652_kghostview.png' title='View File' alt='View File' border='0' align='absmiddle'>
		</a> View File
	<?php
		}
	?>
	(<strong>File Formats:</strong> jpg, png, gif, txt, doc, ppt, xls, pdf)
</td>
</tr>

<tr>
  <td colspan="2" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="left">	
	    <input type="submit" name="submit" value="Update Coaching Message" class="inputbox"></td>
</tr>

<tr>
	<td colspan="2" align="center">	</td>
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