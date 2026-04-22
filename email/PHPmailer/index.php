<?php
require 'Send_Mail.php';
$to = "gunjang.gupta@gmail.com";
$subject = "Welcome to rnrfit";

$message = '<html><body>';

	$message .= '<table class="table borderless" border="0" style="border:none">';
    $message .= '<thead>';
	$message .= '<tr>';
	$message .= '<th width="400px"><img src="http://rnrfit.javvadilakshman.com/assets/img/email_logo.png" style="" width="200px" height="100px"></th>';

	$message .= '        <th width="200px">Connect @ RnR Fit <br>Ph: 080 655 95533 <br>Email: info@rnrfit.com</th>';
	$message .= '</tr>';
    $message .= '</thead>';
    $message .= '<tbody>';
	$message .= '<tr>';
	$message .= '<td>WELCOME<br><br>';
	$message .= 'Dear '.$father_name.'<br><br>
					Welcome to the World of Gymnastics. This Portal has been created so that you can follow your<br>
					child’s progress through the various stages of Gymnastics.<br><br>
					Your credentials are as follows:<br><br></td>';
	$message .= '</tr>';
	$message .= '<tr></tr>';	
    $message .= '</tbody>';
	$message .= "</table>";
	$message .= "</body></html>";
$body = $message; // HTML  tags
Send_Mail($to,$subject,$body);
echo "after";
?>