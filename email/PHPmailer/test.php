<html>
<head>
<title>PHPMailer - Mail() basic test</title>
</head>
<body>

<?php
require("lib/PHPMailer/PHPMailerAutoload.php");
require 'class.phpmailer.php';
$mail = new PHPMailer;
$mail->setFrom('info@rnrfit.com', 'RNR');
$mail->addAddress('gunjang.gupta@gmail.com', 'gunjan');
$mail->Subject  = 'First PHPMailer Message';
$mail->Body     = 'Hi! This is my first e-mail sent through PHPMailer.';
if(!$mail->send()) {
  echo 'Message was not sent.';
  echo 'Mailer error: ' . $mail->ErrorInfo;
} else {
  echo 'Message has been sent.';
}

?>

</body>
</html>
