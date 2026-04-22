<?php
$menu = "Dashboard";
include('ctrls/c_header.php');
include('classes/Event.php');
include('classes/Participant.php');

$event = new Event(); 
$participant = new Participant(); 

if ($_SESSION["type"] == "superadmin") 
{           

  //$Allevents=$event->get_events();
   $Allevents=$event->get_eventafterid(42);
}
else
{  
  $eventid=$_SESSION['eventid'];
  //$Allevents[0]=$event->get_event($eventid);
  $Allevents=$event->get_eventafterid(15);
}
//print_r($Allevents);
$panel=$_SESSION["panel"];
?> 
<style type="text/css">
tr {
   line-height: 5px;
   min-height: 5px;
   height: 5px;
}
</style>
<div class="container" >
  <div class="row" style="margin-top:50px">
<?php
            foreach ($Allevents as $row1)
            {
              $EventDate=date("d/m/Y", strtotime($row1['EventDate']));
                echo'<div class="col-md-4">
                    <div class="hom-trend">
                        
                        <div class="hom-trend-con">
                            <span><i class="fa fa-futbol-o" aria-hidden="true"></i> '.$EventDate.'</span>
                            <a href="GymnastList.php?eventid='.$row1['Id'].'">
                                <h4>'.$row1['Name'].'</h4>
                            </a>
                            <table class="myTable" style="width:350px;">
                            <tbody><tr><th></th>';
                            $categories=$participant->get_eventcategory($row1['Id']);
                            foreach ($categories as $row2)//header to get categories
                            {
                              echo    '<th class="e_h1">'.$row2['category'].'</th>';
                            }
                            echo    '</tr>';

                            foreach ($participant->get_participants_count($row1['Id']) as $row) 
                            { 
                                 echo  ' <tr><td><h4>'.$row['gender'].'</h4></td>';
                                  foreach ($categories as $row3)
                                  {
                                    echo    '<td>'.$row[$row3['category']].'</td>';
                                  }
                                  echo '</tr>';
                            }//inner for loop        
        echo '</tbody></table>';                         
        echo '<div>';
       // echo '<a class="btngreen-sm" href="'.$row1['registerurl'].'?panel=1&eventid='.$row1['Id'].'">Enter Scores</a>';
       // echo '<a class="btngreen-sm" href="dashboard.php?eventid='.$row1['Id'].'">Enter Scores</a>&nbsp;';
         echo '<a class="btngreen-sm" href="dashboardbulk.php?eventid='.$row1['Id'].'">Bulk Entry</a>';
        echo '</div>';      
                        echo'</div>
                    </div>
                </div>';
            }//outr       
 ?>
  </div>
</div>  
<!-- container -->
<?php include('ctrls/footer.php'); ?>