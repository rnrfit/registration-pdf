<?php 
$condition 	= array('Id' =>  $eventid );
$logo=$comm->getRows('events',$condition);
$date=date_create($logo[0]['EventDate']);
        $date=date_format($date,"d/m/Y");
   // echo '<div class="row" align="center"><table width="800" align="center" border="0"><tr><td><img src="'.$logo[0]['rptheader2'].'" height="120" width="1000" alt=""></td></tr></table></div>';
  echo '
<table width="1000" align="center" border="0" style="padding:0px;">
<tr><td style="width:100px;"><img style="height:80px;width:80px;" src="'.$logo[0]['logo1'].'" alt=""></td>
<td>
<table align="center">
<tr><td align="center"><strong><h2>'.$logo[0]['Description'].'</h2></strong></td></tr>

<tr><td align="center"><strong>'.$logo[0]['city'].'</strong></td></tr>
<tr><td align="center">'.$date.'</td></tr>
</table></td></tr>
</table>';
//<tr><td align="center"><strong>'.$logo[0]['address'].'</strong></td></tr>
?>

<?php if(isset($_SESSION['msg'])):?>
	<div class="alert alert-<?php echo $_SESSION['msg'][1];?>" role="alert">
	  <?php echo $_SESSION['msg'][0];?>
	</div>
<?php unset($_SESSION['msg']); endif; ?>