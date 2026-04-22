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
	if($_SESSION["type"] == "secretory")
	{ 
		//login with secretary role
		$panel =$_SESSION["panel"];
		$sqluser="SELECT * FROM `users` WHERE `memberid` =".$_SESSION['id'];	
		$userdata=$comm->executesql($sqluser);
		$eventid=$userdata[0]['Eventid'];
		
	}
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
$sql="SELECT * FROM `panel_apparatus` where eventtype='$eventtype' and gender='F' and eventid='$eventid'";

// if($eventtype=='Artistic' && $_SESSION["type"] == "secretory")
// 	$sql .=" and `panel` = $panel";
	
if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "admin" || $_SESSION["type"] == "secretory") {     	
     
    foreach ($comm->executesql($sql) as $row)
	{
		

		$sqlcat="SELECT * FROM `eventcategory` WHERE `eventid` =$eventid";
		    $catgaries=$comm->executesql($sqlcat);
		    $dispgender='';
			if($gender=='F')
			    	$dispgender='Girls';
			else
			    	$dispgender='Boys';
		    if($eventtype=='Artistic')
		    {
			     echo '<div class="col-md-2 col-sm-6"><div class="p-jc-in">';
			     $gender=($row["gender"]=='M')?'MAG':'WAG';
		    }
			else
			{
			     echo '<div class="col-md-3 col-sm-6"><div class="p-jc-in">';
			     $gender=$row["gender"]; 
			}
             					      
                    switch ($eventtype)
					{
					    case 'Artistic':
					    	if($row["apparatus"] !='17')
					    	{
					    	echo '<div class="p-jc-in-1">
						        <h4>'.$row["aname"].'</h4>
						    </div>';
					    	echo'<h3>'.$gender.'</h3>';
							echo '<div class="p-jc-list">
							      <ul>';
							  }
					    	
						    foreach ($catgaries as $rowinner)
						    {						     		
						     	if($row["apparatus"]=='6')
						     	{
			                        echo '<li><a class="navbar-item textred"  href="agim_vault.php?cat='.$rowinner["category"].'&apid='.$row["apparatus"].'&gender='.$row["gender"].'&panel='.$row["panel"].'&eventid='.$eventid.'">
			                                Enter Scores - '.$rowinner["category"].'
			                            </a></li>';
			                    }
			                    elseif($row["apparatus"] !='17')
			                    {
			                       echo '<li><a class="navbar-item textred"  href="'.$scoreurl.'?cat='.$rowinner["category"].'&apid='.$row["apparatus"].'&gender='.$row["gender"].'&panel='.$row["panel"].'&eventid='.$eventid.'">
		                                Enter Scores - '.$rowinner["category"].'
		                            	</a></li>';
			                    }
		                    }
		             		
		             		break;
					    case 'Trampolining':					        
		                   	echo'<h3>'.$dispgender.'</h3>';
							echo '<div class="p-jc-list">
							        <ul>';	                    	
     							
						    foreach ($catgaries as $rowinner)
						    {						     		
						     	echo '<li><a class="navbar-item textred"  href="'.$scoreurl.'?rt=&cat='.$rowinner["category"].'&gender='.$row["gender"].'&panel='.$row["panel"].'&eventid='.$eventid.'">
		                                Enter Scores - '.$rowinner["category"].'
		                            	</a></li>';
		                        if($rowinner["category"]=='Above16')
		                        {
		                        	echo '<li><a class="navbar-item textred"  href="'.$scoreurl.'?rt=routine2&cat='.$rowinner["category"].'&gender='.$row["gender"].'&panel='.$row["panel"].'&eventid='.$eventid.'">
		                                Routine 2 - '.$rowinner["category"].'
		                            	</a></li>';
		                        }			                       
		                    }
		             		
		             		break;
					    case 'Tumbling':
					    	echo '<div class="p-jc-in-1">
						        <h2>'.$row["aname"].'</h2>
						    </div>';
					    	echo'<h3>'.$dispgender.'</h3>';
							echo '<div class="p-jc-list">
							        <ul>';
					    	     							
						    foreach ($catgaries as $rowinner)
						    {						     
						            echo '<li><a class="navbar-item textred"  href="'.$scoreurl.'?rt='.$row["aname"].'&cat='.$rowinner["category"].'&gender='.$row["gender"].'&panel='.$row["panel"].'&eventid='.$eventid.'">
		                                Enter Scores - '.$rowinner["category"].'
		                            	</a></li>';
			                    
		                    }
		             		
		             		break;
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