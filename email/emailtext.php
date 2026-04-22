<?php 

class Body{

    function header(){
$message = '<table class="table borderless" border="0" style="border:none">';
    $message .= '<thead>';
    $message .= '<tr>';
    $message .= '<th width="250px"><img src="http://rconnect.rnrfit.com/assets/img/email_logo.png" style="" width="150px" height="80px"></th>';

    $message .= '        <th width="200px">Connect @ RnRFit <br>Ph: 080 43027373<br>Email: info@rnrfit.com</th>';
    $message .= '</tr>';
    $message .= '</thead>';
    $message .= '<tbody>';
    $message .= '<tr><td colspan="2">';
    return $message;

    }

     function footer()
     {

        $str='</td>

						</tr>

						<tr>

							<td colspan="2" style="height:75px;background:#404040;text-align:center;">

								<table cellpadding="0" cellspacing="0" width="800px">

									<tr>

										<td><img src="http://rconnect.rnrfit.com/assets/img/email_logo.png" style="" width="150px" height="80px"><br><span style="color:white;font-size:12px;">Powered by RnRFit</span></td>

										<td width="50%" style="font-size:12px;color:#fff;"><span style="color:white; ">For any further assistance mail us :<br> info@rnrfit.com</span></td>



										<td width="50%" style="text-align:right;">

											<a href="https://www.facebook.com/rnrfit.gymnastics/" target="_blank">
												<img src="https://getactiveindia.com/assets/img/logo/fb.png" width="26px"/>
											</a>
										</td>

									</tr>

								</table>

							</td>

						</tr>

                                </table>

                            </td>

                        </tr>

                </table>

                </body>

            </html>';



        return $str;

    }


        function emailsuccessbody($user_data,$event_data,$txnid,$mihpayid,$amount,$payment_invoice_data,$mode_of_payment,$status)
        {

        $str='

            Details of the recently registered person : <br/>

            Emp Id :  '. $user_data['empid'] .'<br/>
            Company :  '. $user_data['Company'] .'<br/>

            Email : '.$user_data['email'].'<br/>

            Mobile :'.$user_data['phone'].'<br/>

            Event :'.$event_data.'<br/>

            Payment Transaction Details: <br/>

            Order ID :'.$txnid.'<br/>

            Txn No : '.$mihpayid.'<br/>

            Txn Amount :'.$amount.' INR<br/>

            Payment Mode :'.$mode_of_payment.' <br/>

            Txn Status :'.$status.'<br/>
			<h4>Details of the Registered Participants for '.$event_data.':</h4>

            <table border=\'1\' cellspacing=\'0\'>

                <tr>
					<th>Sl. No.</th>
					<th>Participant Name</th>
					<th>Event Type</th>					
				</tr>';
		
		$counter = 1;
		$row_count=count($payment_invoice_data);
         for ($i = 0; $i < count($payment_invoice_data); $i++)
        {

            $str.='<tr><td>'.$counter.'</td><td>'.ucwords($payment_invoice_data[$i]['participant']).'</td><td>'.ucwords($payment_invoice_data[$i]["eventtype"]).'</td>';
                    
					// if($counter==0) {
					// 	$str.='<td  rowspan=\''.$row_count.'\'>'.$amount.'</td>';
					// }                 
                $str.=' </tr>';

            $counter++;
        }
        return $str;
    }

}
 ?>