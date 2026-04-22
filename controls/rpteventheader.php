<?php 

$condition = array('AppName' => 'logo1');
$logo1=$comm->getRows('appconfig',$condition);
$condition = array('AppName' => 'logo2');
$logo2=$comm->getRows('appconfig',$condition);
$sql1="SELECT * FROM `events` WHERE `Id` = $eventid";
$eventdata = $comm->executesql($sql1);
$eventName=$eventdata[0]['Name'];
$EventDate=date("d/m/Y", strtotime($eventdata[0]['EventDate'])); 
echo '<style>.image2 {
  position: absolute;
  top: 30px;
  left: 200px;
}
     </style>';
$condition 	= array('Id' =>  $eventid );
	$logo=$comm->getRows('events',$condition);
echo '<div class="row" align="center"><table width="800" align="center" border="0"><tr><td><img src="'.$logo[0]['rptheader'].'" height="120" width="1000" alt=""><img class="image2" src="assets/images/comp.jpg" /></td></tr></table></div>';  

// echo '<div class="row">
// <table width="800" align="center" border="0">
// <tr><td width="50"><img src="'.$eventdata[0]['logo1'].'" height="70px" width="70px" alt=""></td>
// <td colspan="2">
// <table align="center">
// <tr><td align="center"><strong>'.$eventName.'</strong></td></tr>
// <tr><td align="center">'.$eventdata[0]['address'].'</td></tr>
// <tr><td align="center">'.$eventdata[0]['city'].'</td></tr>
// <tr><td align="center">'.$EventDate.'</td></tr>
// </table></td>';
// if ($eventdata[0]['logo2']!='')
// echo '<td width="50"><img src="'.$eventdata[0]['logo2'].'" height="70px" width="70px" alt=""></td></tr>';
// else
// echo '<td width="50"></td></tr>';
// echo '</table>
// </div>';
//echo '<div class="row" align="center"><img src="assets/images/header.jpeg" height="120" width="1200" alt="">
//</div>';
?>