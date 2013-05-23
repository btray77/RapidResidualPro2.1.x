<?php
if(count($_POST))
{
	foreach($_POST as $key => $items)
	{
		$$key =trim($items);
	}
echo testemail($mail_type,$email_from,$from_name,$smtpsecure,$smtphost,$smtpport,$smtpusername, $smtppassword, $mail_footer);

}

function testemail($mail_type,	$email_from, $from_name, $smtpsecure, $smtphost, $smtpport, $smtpusername, $smtppassword, $mail_footer){
if(!require_once($_SERVER['DOCUMENT_ROOT'].'/common/phpmailer/class.phpmailer.php')) 
	die ("Sorry! class.phpmailer.php not found");
        global $error;
        $mail = new PHPMailer();  // create a new object
        if ($mail_type == 'smtp') {
            $mail->Mailer = $mail_type; // enable SMTP
            $mail->IsHTML();
            $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true;  // authentication enabled
            $mail->SMTPSecure = $smtpsecure; // secure transfer enabled REQUIRED for Gmail
            $mail->Host = $smtphost; // SMTP HOST
            $mail->Port = $smtpport; //465 
            $mail->Username =  $smtpusername;
            $mail->Password = $smtppassword;
            $mail->SetFrom($email_from, "System Generated Email");
            $mail->Subject = "Check SMTP configuration Email";
            $body = nl2br("Dear Admin <p> Your e-mail is configured correctly.</p> <p> Enjoy using Rapid Residual Pro!.</p>");
            $mail->Body = $body;
            $mail->AddAddress($email_from);
            if (!$mail->Send()) {
				$error = '<span style="color:red">Mail error: ' . $mail->ErrorInfo . "</span>";
            } else {
                return "<span style='color:green'><img src='/images/tick.png' height='14' align='absmiddle'> SMTP is successfully configured.<span>";
            }
        } else {
            $header = 'MIME-Version: 1.0' . "\r\n";
            $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $header .= "From: System Generated Email <" . $email_from . ">";
			$body = nl2br("Dear Admin <p> Your e-mail is configured correctly.</p> <p> Enjoy using Rapid Residual Pro!.</p>");
            if (!mail($email_from, "Check SMTP configuration Email", $body, $header)) {
                return "<span style='color:red'>
				 <img src='/images/crose.png' height='14' align='absmiddle'> Mail error: Sorry your server can not send email using PHP mail. Please configure your SMTP in site settings.
				 </span>";
            } else {
                return "<span style='color:green'><img src='/images/tick.png' height='14' align='absmiddle'> PHP Mail is successfully configured.<span>";
            }
        }
    }
?>