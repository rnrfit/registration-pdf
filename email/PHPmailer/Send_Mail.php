<?php
function Send_Mail($to,$subject,$body)
{
require("lib/PHPMailer/PHPMailerAutoload.php");
include 'class.phpmailer.php';
$mail = new PHPMailer;
$mail->IsHTML(true);
$mail->setFrom('info@rnrfit.com', 'RNR');
$mail->addAddress($to);
$mail->Subject  = $subject;
$mail->Body     = $body;
if(!$mail->send()) {
  echo 'Message was not sent.';
  echo 'Mailer error: ' . $mail->ErrorInfo;
} else {
  echo 'Message has been sent.';
}

}
?>

