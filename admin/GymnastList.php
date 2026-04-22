<?php include('ctrls/c_header.php');
include('classes/Event.php');
include('classes/registration.php');
$participant = new Registration(); 

 ?>

<!-- Theme JS files -->
 <!-- Global stylesheets -->
    <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- Core JS files -->
    <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->
    <!-- Theme JS files -->
       <script type="text/javascript" src="assets/js/core/app.js"></script>
    <script type="text/javascript" src="assets/js/plugins/ui/ripple.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script type="text/javascript" src="assets/js/pages/datatables_extension_buttons_init.js"></script>
 <style type="text/css">
   @media (min-width: 1200px)
.container {
    max-width: 1500px !important;
}
 </style>
 <div class="">

<!-- Page header -->
            <div class="page-header page-header-light" style="height:70px;">
                <div class="page-header-content header-elements-md-inline" >
                    <div class="page-title d-flex">
                    <h4><a href="events.php?etype=<?php echo $eventtype;?>"><i class="icon-arrow-left52 mr-2"></i></a> <span class="font-weight-semibold">Gymnast List</span></h4>
                    <div class="col text-right"><a href="register.php?eventid=<?php echo $eventid;?>" class="btn btn-warning btn-sm" >Add Participant</a>&nbsp;<a href="upload_file.php?eventid=<?php echo $eventid;?>" class="btn btn-warning btn-sm" >Upload</a>&nbsp;</div>
                    </div>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
            <!-- Default ordering -->
                <div class="card">
                    <div class="card-header header-elements-inline" style="height:40px;">
                         <h5 class="card-title"></h5>  
                    </div>
<table class="table datatable-select-checkbox datatable-button-init-basic" id="table2excel">
<thead>
<tr>                            
                            <th>Id</th>                                                             
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>DOB</th>                           
                           <!--  <th>Age</th>
                            <th>Gender</th>   -->                          
                            <th>Download</th>
                            <?php 
                             if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "admin") {
                             
                             }
                             ?>     
</tr>
</thead>
<tbody>
<?php
$records=$participant->get_participants_List();
	   foreach ($records as $row) {     
                          echo '<tr>'; 
                           echo '<td>'. $row['id'] . '</td>';
                          echo '<td>'. $row['name'] . '</td>';
                          //  echo '<td>'. $row['contingent'] . '</td>'; 
                          // echo '<td>'. $row['City'] . '</td>'; 
                          echo '<td>'. $row['email'] . '</td>'; 
                          echo '<td>'. $row['phone'] . '</td>'; 
                          if($row['dob']!='0000-00-00')
                          {
                            $date=date_create($row['dob']);
                            $date=date_format($date,"d/m/Y");
                          }
                          else
                            $date='';
                          echo '<td>'.$date.'</td>';
                          $birthdate = new DateTime($row['dob']);
                          $today   = new DateTime('today');
                          $age = $birthdate->diff($today)->y;
                          // echo '<td>'.$age . '</td>';
                          // echo '<td>'. $row['Gender'] . '</td>';
                          echo '<td>';
                          // <a class="navbar-item textred" href="participantadd.php?eventid='.$eventid.'&regid='.$row['id'].'">
                          //           Edit</a>
                          echo '<a class="navbar-item textred" target="_blank" href="bga_1.php?id='.$row['id'].'">
                                     BGA&nbsp;</a> | ';
                          echo '<a class="navbar-item textred" target="_blank" href="aerobic_entryform.php?id='.$row['id'].'">
                                     Aerobic</a>';
                                     
                          echo '</td>';
                          // echo '<td><a href="participantremove.php?id='.$row['reg_id'].'&eventid='.$eventid.'"> <i class="fa fa-times" aria-hidden="true"></i></a></td>';
                             //}
                          echo '</tr>';
              }
	?>
  </tbody>
  </table>
 </div>
                <!-- /default ordering -->

                </div>
            <!-- /content area -->
            </div>
<?php include('ctrls/footer.php'); ?>
