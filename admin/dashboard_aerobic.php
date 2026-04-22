<?php include('ctrls/c_header.php');
include('classes/Event.php');
include('classes/Participant.php');
$panel ='';
$eventid='';
if (!empty($_GET['panel'])) {
  $panel = $_REQUEST['panel'];   
}
if (!empty($_GET['eventid'])) {
  $eventid = $_REQUEST['eventid'];   
}

error_reporting(E_ALL);
$eventname='';
	
	$sqlevent="SELECT * FROM `events` WHERE `Id` =$eventid";
		$eventdata=$comm->executesql($sqlevent);
		
		$eventname=$eventdata[0]['Name'];
		$scoreurl=$eventdata[0]['registerurl'];
		$eventtype=$eventdata[0]['Category'];
	
?>
<style type="text/css">
tr {
   line-height: 5px;
   min-height: 5px;
   height: 5px;
}
</style>
<section>
        <div class="lp spe-bot-red-3">
            <div class="inn-title">
                <h2><i class="fa fa-check" aria-hidden="true"></i> <?php echo $eventname; ?></h2>
                <p></p>
            </div>
            <div class="hom-top-trends row">
             <!--Panel block-->
             <?php 
$sql="SELECT * FROM `eventcategory` WHERE `eventid` =$eventid";

// if($eventtype=='Artistic' && $_SESSION["type"] == "secretory")
// 	$sql .=" and `panel` = $panel";
	
if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "admin" || $_SESSION["type"] == "secretory") {     	
     
    foreach ($comm->executesql($sql) as $row)
	{		
		    $catgaries=['Individual Men','Individual Women','Mix Pair','Trio','Group','Aero Dance'];
			 echo '<div class="col-md-3 col-sm-6"><div class="p-jc-in">';             					      
                    echo '<div class="p-jc-in-1">
						        <h4>'.$row["category"].'</h4>
						    </div>';
					    	
							echo '<div class="p-jc-list">
							      <ul>';
					    	
						    foreach ($catgaries as $rowinner)
						    {						     		
						     	 echo '<li><a class="navbar-item textred"  href="'.$scoreurl.'?agegroup='.$row["category"].'&event='.$rowinner.'&eventid='.$eventid.'">
		                                '.$rowinner.'
		                            	</a></li>';
		                    }
                            
                    echo '     </ul>
			    </div></div></div>';
	}
}
              ?>
            <!--Panel block-->
            </div>
        </div>
</section>