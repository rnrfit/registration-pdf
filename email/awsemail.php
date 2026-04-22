<?php

include ('PHPmailer/class.phpmailer.php');

Class Awsemail {

public function __construct() {}

public function sendmail($subject,$body,$email)
{
	error_reporting(E_ALL);
    ini_set('display_errors', '1');
		$mail = new PHPMailer();
		$mail->IsSMTP(true); // SMTP
$mail->SMTPAuth   = true;  // SMTP authentication
$mail->Mailer = "smtp";
$mail->SMTPSecure = 'tls';
$mail->Host= 'email-smtp.us-west-2.amazonaws.com'; // Amazon SES
$mail->Port = "587";  // SMTP Port
$mail->Username = "";  // SMTP  Username
$mail->Password = ""; // SMTP Password*/
$mail->setFrom("info@rnrfit.com", 'IndiangymansticsLive');

$mail->WordWrap = 50;
$mail->IsHTML(true);
//-------------
//------------------
$mail->AddAddress($email);
$mail->AddCC('info@rnrfit.com');
$mail->Subject = $subject;
$mail->Body    = $body;
$mail->AltBody = $body;

		if(!$mail->Send())
		{
		   echo "Message could not be sent. <p>";
		   echo "Mailer Error: " . $mail->ErrorInfo;
		   exit;
		}
		else
		{
			//echo 'email sent to'.$email;
		}
}//send mail
}
?>
