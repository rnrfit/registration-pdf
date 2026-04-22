<?php 
$menu = "Dashboard";
include('ctrls/c_header.php');
include('classes/Event.php');
include('classes/Apparatus.php');
include('classes/Participant.php');
$participant = new Participant();
$apparatus = new Apparatus();
$event = new Event(); 
$eventid = "44";
$panel =0;
if ( !empty($_POST)){
            $eventid=    $_POST['eventid'];
            $category =  $_POST['Category'];
            $apid =  $_POST['apid'];
            $gender =  $_POST['gender'];
            // $groupid   =    $_POST['groupid'];       
         
}//if post

if (!empty($_GET['panel'])) {
  $panel = $_REQUEST['panel'];  
}


?>
<!-- Theme JS files -->
<!-- Global stylesheets -->
<style type="text/css">
   
    td{
        padding-top:8px;
        padding-bottom:8px;
    }
</style>
<section>
        <div class="lp spe-bot-red-3">
          <div class="inn-title">
                        <h2><i class="fa fa-check" aria-hidden="true"></i> REPORTS</h2>
                        <p></p>
          </div>
          <div class="hom-top-trends row">
            <!-- Content area -->
            <div class="content">
                <!-- Default ordering -->
                <div class="card">
                    <!-- form -->
                    <form action="reportlist.php" method="get" target="new" class="form-horizontal" id="adminprintsheetform">
                          <div class="row">
                          <div class="col-lg-3">
                                <select class="form-control" id="eventid" name="eventid" required="">
                                    <option value="">Select Event</option>
                                    <?php 
                                   // $sql='SELECT * FROM `events` where parentid !=0 and Id> 22 order by Id desc';
                                    $sql='SELECT * FROM `events` where parentid !=0 order by Id desc';
                                       foreach ($comm->executesql($sql) as $row) 
                                        {
                                          echo "<option value=".$row['Id'];    
                                          if ($row['Id'] == $eventid) echo ' selected="selected"';
                                          echo ">".$row['Name']."</option>";
                                        }?>
                                </select>
                          </div>
                          <div class="col-lg-1"> 
                            <select class="form-control" id="gender" name="gender" required>
                                                                        <option value="">Gender</option>
                                                                        <option value="M">M</option>
                                                                        <option value="F" selected>F</option>
                            </select>
                          </div>
                          <div class="col-lg-2"> <select class="form-control" id="Category" name="Category" required>
                                   <option value="">Select Category</option>
                                        <?php 
                                   $sql="SELECT distinct category FROM `eventcategory` where eventid=44";
                                      
                                       foreach ($comm->executesql($sql) as $row) {                                 
                                           echo "<option value='".$row['category'];                      
                                           echo "'>".$row['category']."</option>";
                                       }                                
                                   ?>
                                                                </select>
                          </div> 
                          <div class="col-lg-2">
                              <select class="form-control" id="club" name="club">
                                   <option value="">Select Club</option>
                                        <?php 
                                   $sql="SELECT distinct club  FROM `registrations` where event in (44) order by club";
                                      
                                       foreach ($comm->executesql($sql) as $row) {                                 
                                           echo "<option value='".$row['club'];                      
                                           echo "'>".$row['club']."</option>";
                                       }                                
                                   ?>
                              </select>
                          </div>
                          <div class="col-lg-2">
                              <select class="form-control" id="apid" name="apid">
                                  <option value="">Select Apparatus</option>
                                  <?php 
                                      foreach ($apparatus->get_apparatus() as $row)
                                      {
                                                       echo "<option value=".$row['apid']   ;
                                                       echo ">".$row['gname']."</option>";
                                      }?>
                              </select>
                          </div>
                        </div>
                      </form>
                    <!-- form -->
                    <!-- table -->

                  <table class="table datatable-select-checkbox" >
<tbody>
<?php  
if ($_SESSION["type"] == "admin" ) {  
//if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "admin" ) {  
echo '<tr><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="s_admin_signsheet.php" style ="width:120px;" value="Live"></td><td>View Live Screen</td></tr>
<tr><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="../igl_individualrank.php" style ="width:120px;" value="IndividualRank" formtarget="_blank"></td><td>Individual Rank</td></tr>
';

}
else
{
  //  echo '<tr><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="display.php" style ="width:180px;" value="Display"></td><td>Select Event,Category.Control display of Live Screen</td></tr>';
     echo '<tr><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="printsignsheet.php" style ="width:180px;" value="Signsheet"></td><td>Select Event,Category,Gender.Print empty sign sheet</td></tr>';

    echo '<tr><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="printscoresheet.php" style ="width:180px;" value="Scoresheet"></td><td>Select Event,Category,Gender.Print empty score sheet</td></tr>
    <tr style ="background-color:yellow;"><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="agim_leaderboardrpt.php" style ="width:180px;" value="Leaderboard"></td><td >Click to View Leaderboard.</td></tr>
    
    <tr style =""><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="artistic_teamchamp.php" style ="width:180px;" value="ArtisticTeamChampionship"></td><td >Select Event,Category,Gender. Use this page print Team Championship Results.</td></tr>
    <tr style =""><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="artistic_allaround.php" style ="width:180px;" value="ArtisticAllAround"></td><td >Select Event,Gender,Category. Use this page to view All Around Results.</td></tr>
   
       
     <tr style ="background-color:yellow;"><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="printagimresult.php" style ="width:180px;" value="PrintResultpdf"></td><td >Select Event,Gender,Level and Category. Use this page print complete results.</td></tr>
     ';
}
//<tr style ="background-color:yellow;"><td><input type="submit" name=btnSubmit[] class="btn btn-primary btn-sm btnsubmit" data-target="printpdf.php" style ="width:180px;" value="PrintTop10Rankpdf"></td><td >Select Event,Gender,Level and Category. Use this page print Top 10 Ranks in pdf.</td></tr>     
?>
  </tbody>
  </table>
                    <!-- table -->
                </div>
                <!-- /default ordering -->
              </div>
              <!-- /content area -->
          </div>
    </div>
</section>
 <script type="text/javascript">
$(document).ready(function() {
   
    $(".btnsubmit").on("click", function(){
      $("#adminprintsheetform").attr("action", $(this).attr('data-target'));
$("#adminprintsheetform").submit();
    })
} );
</script>
<?php include('ctrls/footer.php'); ?>