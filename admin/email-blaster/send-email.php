<?php

include_once("../session.php");
include_once("../header.php");


if (isset($_POST['send']))
{

	//$email_from_name	= $_POST["email_from_name"];
	$subject 			= $_POST["subject"];
	$message 			= $_POST["message"];
	//$footer		 		= $_POST["mailer_details"];
	//$webmaster_email	= $_POST["from_address"];

	

	$q = "select email_from_name,from_name   from ".$prefix."site_settings";
	$r = $db->get_a_line($q);
	@extract($r);

	$q = "select email, firstname, lastname, username from ".$prefix."members where id='".$mid."'";
	$r = $db->get_a_line($q);
	
	extract($r);
	$first_name = $firstname;
	$last_name = $lastname;
	
	// send email to member
	$subject=stripslashes($subject);
	$subject = preg_replace("/{(.*?)}/e","$$1",$subject);
	
	$message1=stripslashes($message);
	$message1 = preg_replace('/\{([a-zA-Z0-9_]*)\}/e', "$$1", $message1);
	
	
	
	if($common->sendemail($from_name, $email_from_name, $email, $subject, $message1, ''))
	{
		$subject=addslashes($subject);
		$message=addslashes($message);
		$insql = "insert  ".$prefix."email_log set  subject ='$subject',message='$message',member_id='$mid'";
		$db->insert($insql);
		$msg = '<div class="success"><img align="absmiddle" src="../../images/tick.png"> Email has been successfully sent to '.$email . '</div>';
	}
	

	
}
$subject=stripslashes($subject);
$message=stripslashes($message);
//$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
//echo $returncontent;
?>



<!-- ###################### Error Message Start ###################### -->
<?php echo $msg;?>

<!-- ###################### Error Message End ###################### -->


<!-- ###################### Content Area Start ###################### -->
<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong>Single Member  Mail</strong></p>

<div class="buttons">
    <a href="index.php">Go back</a>
    </div>

<div class="formborder">

<form action="send-email.php" method="post">
<input type="hidden" name="mid" value='<?php echo $mid?>'>
<table align="center" width="95%" border="0" cellpadding="3" cellspacing="3">
<tr>
	<td class="tbtext" colspan="2" align="center">Variables used in Email: {first_name}, {last_name},</td>
</tr>
<tr>
  <td class="tbtext" colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td width="20%" align="left" class="tbtext"><b>Subject:&nbsp;</b></td>
	<td>
	  <input type="text" name="subject" class="inputbox" value="<?php echo $subject?>" size=60 >	  </td>
</tr>
<tr>
	<td class="tbtext" align="left" valign=top><b>Message:&nbsp;</b></td>
	<td>
	  <textarea name="message" cols="60" rows="8" class="inputbox"><?php echo $message ?></textarea>	  </td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2"><input type="submit" name="send" value="Send" class="inputbox"></td>
	</tr>
</table>
</form>


  
  
  
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto();
		});
</script>
<?php 
include_once("../footer.php");
?>