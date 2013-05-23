<?php
require_once $_SERVER['DOCUMENT_ROOT']."/admin/include.php";
$reciver_email = 'yasir.rehamn@live.com';
$reciver_name = 'Yasir Rehman';

if($_POST["send_mail"]){
	
	$sender_name = trim($_POST["name"]);
	$sender_email = trim($_POST["email"]);
	
	$site = trim($_POST["site"]);
	$phone =trim($_POST["phone"]);
	
	$subject = trim($_POST["subject"]);
	$message = trim($_POST["message"]);
	
	if(empty($sender_name)){
			$errors[] = "Your name is required"; 	
	}
	
	if(empty($subject)){
			$errors[] = "subject is required"; 	
	}
	
	if(empty($message)){
			$errors[] = "You need to type message"; 	
	}
	
	if(empty($sender_email)){
			$errors[] = "You need to type your email"; 	
	}
	else if ( !preg_match( "/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/", $sender_email ) )
    {
        	$errors[] = "Check your email, it is invalid"; 	
    }
	
	if(empty($phone)){
			$errors[] = "Your phone number is required for contact purpose"; 	
	}
	else if (!preg_match('/^\(?[0-9]{3}\)?|[0-9]{3}[-. ]? [0-9]{3}[-. ]?[0-9]{4}$/', $phone)) {
	
			$errors[] = "Invalid phone number, please follow the example"; 	
	
	}
	
	if(empty($site)){
			$errors[] = "We find your website url missing."; 	
	}
	else if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $site)) {
	
			$errors[] = "It seem to be your website url is wrong.."; 	
	
	}
	
	if(count($errors)==0){
		
			$body = "<h2>Report a bug</h2>
					<table cellpadding=\"4\"><tr><td><b>Name:</b></td><td>{$sender_name}</td></tr><tr><td><b>Website: </b></td><td>{$site}</td></tr>
						<tr><td><b>Phone Number: </b></td>	<td>{$phone}</td></tr></table>";
					
		
			$message = $body . nl2br($message);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
			$headers .= "To: {$reciver_name} <{$reciver_email}>" . "\r\n";
			$headers .= "From: {$sender_name} <{$sender_email}>" . "\r\n";
			$headers .= "CC: Yasir <yasir.rehman@live.com>" . "\r\n";
			
			if($common->sendemail($reciver_name, $sender_email, 'support@rapidresidualpro.com', $subject, $message, $headers))
			{
				header("Location:report-bug.php?success=1");	
				
			}
			else {
				 $errors[] = "Fail to send message, Internal error accour. ";
				 echo $message;	 
			}
	
	}
	
	//preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	
	
	
	
	
}
?>
<link href="/common/newLayout/css.css" rel="stylesheet" type="text/css" />
<?php if(count($errors)){ ?>
<div class="errors"><?php echo implode('<br>', $errors); ?></div> 

<?php } ?>

<?php if($_GET['success']){ ?>
	<div class="success" style="width:auto">Thank You! your request is sent successfully!</div>		
<?php } ?>
<style>table tr td{font-size:12px;}</style>
<div class="form">
<h4>Post your bug...</h4>
<form method="post">
<table cellpadding="3" id="bug_reporter" cellspacing="0" border="0">
<tr>
	<td>Name: </td>
    <td><input name="name" value="<?php echo $_POST["name"] ?>" size="30"></td>
</tr>
<tr>
	<td>WebSite: </td>
    <td><input name="site" value="<?php echo $_POST["site"] ?>" size="30"></td>
</tr>
<tr>
	<td>Email: </td>
    <td><input name="email" value="<?php echo $_POST["email"] ?>" size="30"></td>
</tr>
<tr>
	<td>Phone Number: </td>
    <td><input name="phone" value="<?php echo $_POST["phone"] ?>"> example. (232) 555-5555</td>
</tr>
<tr>
	<td>Subject: </td>
    <td><input name="subject" value="<?php echo $_POST["subject"] ?>" size="30"></td>
</tr>
<tr>
	<td>Message: </td>
    <td><textarea name="message" cols="50" rows="7"><?php echo $_POST["message"] ?></textarea></td>
</tr>
<tr>
	<td>&nbsp;</td>
    <td><input type="submit" name="send_mail" value="Report Bug"></td>
</tr>

</table>
</form>
</div>