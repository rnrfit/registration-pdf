<?php include('inc/headers/c_header.php');
include('inc/Common/Participant.php'); ?>
<?php 
$eventid = "";
$eventtype = "";

if (!empty($_GET['regid'])) {
    $regid = $_REQUEST['regid']; 

    $sqlcat = "SELECT *  FROM registrations WHERE id =$regid";   
    $rec = $comm->executesql($sqlcat); 
    $data=$rec[0]; 

}
if ( !empty($_POST))
{      
        $regid=$_POST['regid'];
        $club=$_POST['club'];
        $aadhaar=$_POST['aadhaar']; 
        $eventtype=$_POST['eventtype']; //
        $name=$_POST['name'];             
        $gender=$_POST['gender'];  
        $exd = DateTime::createFromFormat('d M, Y', $_POST['dob']);
        $checkdt=explode("-",$_POST['dob']);
        if(count($checkdt)>1)  
                  $dob=$_POST['dob'];
        else    
                  $dob= date_format($exd, 'Y-m-d');

        $sql = "UPDATE registrations  set `name`='$name', `Gender`='$gender', `dob`='$dob', `club`='$club', `eventtype`='$eventtype',aadhaar='$aadhaar' WHERE id = $regid";
        $comm->insertupdate($sql);
}
 ?>
<!-- Theme JS files --> 
    <link href="assets/css/components.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script type="text/javascript" src="assets/js/pages/datatables_extension_buttons_init.js"></script>
 <div class="container">

<!-- Page header -->
            <div class="page-header page-header-light" style="height:70px;">
                <div class="page-header-content header-elements-md-inline" >
                    <div class="page-title d-flex">
                    <h4><a href="events.php"><i class="icon-arrow-left52 mr-2"></i></a> <span class="font-weight-semibold">Gymnast Edit</span></h4>
                    </div>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
            <!-- Default ordering -->
                <div class="card">
                    <div class="card-header header-elements-inline" style="height:40px;">
                        Edit
                    </div>
                    <!--  -->
                     <form action="Gymnastedit.php" method="post" class="form-horizontal">
                    <input id="regid" name="regid" type="hidden" value="<?php echo $regid ?>"></input>
                     <div class="row">
                      <div class="col-md-2">Name</div>
                       <div class="col-md-6">
                <div class="form-group">                   
                   <input type="text" class="form-control" id="name" name="name"
                        placeholder="Enter Name" value="<?php echo $data['name'] ?>" maxlength="50" required/>
                </div>
            </div>
                    </div>
                <!--  -->
                 <div class="row">
                      <div class="col-md-2">Gender</div>
                       <div class="col-md-6">
                <div class="form-group">                   
                    <div>
                        <label>
                            <input type="radio" name="gender" id="gender"  value="M" <?php if($data['Gender'] =='M') echo "checked"; ?> required/>
                            Male
                        </label>
                        <label>
                            <input type="radio" name="gender" id="gender"  value="F" <?php if($data['Gender'] =='F') echo "checked"; ?> required/>
                            Female
                        </label>
                    </div>
                </div>
            </div>
                    </div>
                    <!--  -->
 <!--  -->
                 <div class="row">
                      <div class="col-md-2">DOB</div>
                     
                      <div class="col-md-4">
                <div class="form-group">
                    
                    <div class="input-group">
                        <input type="date" class="form-control" id="dob" value="<?php echo $data['dob'] ?>" name="dob" placeholder=" Date of Birth">
                    </div>
                
            </div>
            </div>
                    </div>
                    <!--  -->
                                                      <!--  -->
                  <div class="row">
                      <div class="col-md-2">Club</div>
                       <div class="col-md-6">
                <div class="form-group">                   
                   <input type="text" class="form-control" id="club" name="club"
                        placeholder="Enter club Name" value="<?php echo $data['club'] ?>" maxlength="25" required/>
                </div>
            </div>
                    </div>
                    <!--  -->
                
                                       <!--  -->
                  <div class="row">
                      <div class="col-md-2">Category</div>
                       <div class="col-md-6">
               <div>
                        <label>
                            <input type="radio" name="eventtype" id="eventtype" <?php if($data['eventtype'] =='Junior') echo "checked"; ?>  value="Junior" required/>
                            Junior
                        </label>
                        <label>
                            <input type="radio" name="eventtype" id="eventtype" <?php if($data['eventtype'] =='Senior') echo "checked"; ?> value="Senior" required/>
                            Senior
                        </label>
                    </div>
            </div>
                    </div>
                    <!--  -->
              
                                       <!--  -->
                  <div class="row">
                      <div class="col-md-2">Aadhaar</div>
                       <div class="col-md-6">
                <div class="form-group">                   
                   <input type="text" class="form-control" id="aadhaar" name="aadhaar"
                        placeholder="Enter aadhaar No" value="<?php echo $data['aadhaar'] ?>" maxlength="10" minlength="10" required/>
                </div>
            </div>
                    </div>
                    <div class="row"></div>
                     <div class="row"> <div class="col-md-2"></div> <div class="col-md-6"><div class="form-group">   <input type="submit" value="SUBMIT" class="btn btn-outline-danger btn-lg btn-block" /></div></div></div>
                     </form>
                    <!--  -->
 </div>
                <!-- /default ordering -->

                </div>
            <!-- /content area -->
            </div>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<?php include('inc/headers/footer.php'); ?>
