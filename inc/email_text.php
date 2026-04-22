<?php 

class Emailcontent{

    function header()
    {
        $message = '<html><body>';
        $message .= '<table class="table borderless" border="0" style="border:none">';
        $message .= '<thead>';
        $message .= '<tr>';
        $message .= '<th width="400px"><img src="'.$GLOBALS['url'].'/assets/images/iglogo.jpg" style="" width="200px"></th>';
        $message .= '        <th width="200px">Connect @ INDIANGYMNASTICS.LIVE <br>Ph: '.$GLOBALS['Phone'].' <br>Email: '.$GLOBALS['setFrom'].'</th>';
        $message .= '</tr>';
        $message .= '<tr><td><br></td></tr>';

        return $message;
    }

    function headerwithText($headertext)
    {
        $message = '<html><body>';
        $message = ' <table style="border: 1px solid #cccccc" cellpadding="4" cellspacing="4" width="750">
        <tbody>
        <tr>
            <td>
                <table>
                    <tr>
                        <td width="350" height="100" align="center" style="padding-left: 5px" rowspan="3">
                            <a href="'.$GLOBALS['url'].'" style="text-decoration: none;" target="_blank">
                                <img src="'.$GLOBALS['url'].'/assets/images/iglogo.jpg" alt="" border="0" valign="bottom"><br>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td width="290" height="100" style="border-bottom: 1px #cccccc dotted; vertical-align: bottom;
                            font-weight: bold; font-size: 20px; font-family: Trebuchet MS">
                            '.$headertext.'
                        </td>
                    </tr>
                    <tr>
                        <td >&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>';

        return $message;
    }

     function footer()
     {
        $message = '<tr><td>System generated receipt, no signature required.<br></td></tr>';
        $message .= '<tr><td><br></td></tr>';
        $message .= '<tr><td></td></tr>';
        $message .= '<tr><td>'.$GLOBALS['Company'].'</td></tr>';
        $message .= '<tr><td>'.$GLOBALS['City'].'</td></tr>';
        $message .= '</tbody>';
        $message .= "</table>";  
        $message .= "</body></html>";
        return $message;
    }

    function genericfooter()
    {
        $message = '<tr><td>With Warm Regards<br></td></tr>';
        $message .= '<tr><td><br></td></tr>';
        $message .= '<tr><td></td></tr>';
        $message .= '<tr><td>Team '.$GLOBALS['Company'].'</td></tr>';
        $message .= '<tr><td>'.$GLOBALS['City'].'</td></tr>';

        $message .= '</tbody>';
        $message .= "</table>";  
        $message .= "</body></html>";
        return $message;
    }

    function footerborder()
    {
        $message = '<tr>
                <td colspan="2" style="padding: 10px 0px 10px 15px; font-family: Trebuchet MS; font-size: 15px"
                    bgcolor="#FFFAEA">
                    For any clarifications please mail us at <a href="mailto:'.$GLOBALS['setFrom'].'" style="color: #1274c0"
                        target="_blank">'.$GLOBALS['setFrom'].'</a> .
                </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-family: Trebuchet MS; font-size: 15px">
                        Thank You,<br>
                        <br>
                        <strong>Warm Regards,<br>
                            <font color="#EE1C25">Support Team - <span>'.$GLOBALS['Company'].'</span></font></strong>
                    </td>
                </tr>
                 <tr><td colspan="2" align="center" style="padding:0;Margin:0;font-size:0px"> <img src="https://www.indiangymnastics.live/assets/images/agim2022.jpg" width="500px" alt=""></tr>
                <tr style="border-bottom: 1px #cccccc dotted">
                    <td colspan="2" style="background-color: #d72719; padding: 10px 0px 10px 15px; text-align: center;
                        font-family: Trebuchet MS; font-size: 11px">
                        <a href="'.$GLOBALS['url'].'" style="color: #fff" target="_blank">www.<span class="il">'.$GLOBALS['Company'].'</span></a>
                    </td>
                </tr>
                </tbody>
            </table>';
        
        return $message;
    }
    function footerborder2()
    {
        $message = '<tr>
                <td style="padding: 10px 0px 10px 15px; font-family: Trebuchet MS; font-size: 15px"
                    bgcolor="#FFFAEA">
                    For any clarifications please mail us at <a href="mailto:'.$GLOBALS['setFrom'].'" style="color: #1274c0"
                        target="_blank">'.$GLOBALS['setFrom'].'</a> .
                </td>
                </tr>
                <tr>
                    <td style="font-family: Trebuchet MS; font-size: 15px">
                        Thank You,<br>
                        <br>
                        <strong>Warm Regards,<br>
                            <font color="#EE1C25">Support Team - <span>'.$GLOBALS['Company'].'</span></font></strong>
                    </td>
                </tr>
                <tr style="border-bottom: 1px #cccccc dotted">
                    <td style="background-color: #d72719; padding: 10px 0px 10px 15px; text-align: center;
                        font-family: Trebuchet MS; font-size: 11px">
                        <a href="'.$GLOBALS['url'].'" style="color: #fff" target="_blank">www.<span class="il">'.$GLOBALS['Company'].'</span>.com</a>
                    </td>
                </tr>
                </tbody>
            </table>';
        
        return $message;
    }    
}
 ?>