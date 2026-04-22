<?php 
include('inc/dbconfig.php');
include_once('inc/common.php');

$comm = new Common();
$cat   =  '';

 $dataGet = $comm->objdb->sanitizeArray($_GET);
 echo '<style>.image2 {
  position: absolute;
  top: 30px;
  left: 200px;
}
     </style>';
    if ( !empty($dataGet)){            
        $panel      =    (isset($dataGet['panel']) && !is_array($dataGet['panel'])) ? intval($dataGet['panel']) : 0;
        $gender     =     (isset($dataGet['gender']) && !is_array($dataGet['gender'])) ?$dataGet['gender']: '';  
        $apid       =     (isset($dataGet['apid']) && !is_array($dataGet['apid'])) ?$dataGet['apid']: 0; 
        $cat  		=    (isset($category) && !is_array($category)) ?$category: ''; 
    	$eventid  	=      (isset($eventid ) && !is_array($eventid )) ? intval($eventid ) : 0;
    	
    	$condition 	= array('Id' =>  $eventid );
	$logo=$comm->getRows('events',$condition);
	if($cat=='Junior')
    echo '<div class="row" align="center"><table width="800" align="center" border="0"><tr><td><img src="'.$logo[0]['rptheader2'].'" height="120" width="1000" alt=""></td></tr></table></div>';    
else
	 echo '<div class="row" align="center"><table width="800" align="center" border="0"><tr><td><img src="'.$logo[0]['rptheader'].'" height="100" width="1000" alt=""></td></tr></table></div>';  
    }
?>

<?php if(isset($_SESSION['msg'])):?>
	<div class="alert alert-<?php echo $_SESSION['msg'][1];?>" role="alert">
	  <?php echo $_SESSION['msg'][0];?>
	</div>
<?php unset($_SESSION['msg']); endif; ?>