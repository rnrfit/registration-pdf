<?php include('ctrls/c_header.php');
include('classes/Registration.php');
$organisation = new Registration();
?>
<!-- Theme JS files -->
 <!-- Global stylesheets -->
    <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="assets/css/colors.css" rel="stylesheet" type="text/css">
    
    <!-- /global stylesheets -->

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

 <div class="container">

<!-- Page header -->
            <div class="page-header page-header-light" style="height:70px;">
                <div class="page-header-content header-elements-md-inline" >
                    <div class="page-title d-flex">
                    <h4><a href="events.php?etype="><i class="icon-arrow-left52 mr-2"></i></a><span class="font-weight-semibold">Association List</span></h4>
                    <div class="col text-right"><a href="orgadd.php" class="btn btn-warning btn-sm">Add Assn</a>&nbsp;</div>
                    </div>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
            <!-- Default ordering -->
                <div class="card">
                    <div class="card-header header-elements-inline" style="height:40px;"></div>
<table class="table datatable-select-checkbox datatable-button-init-basic" id="table2excel">
<thead>
<tr>
<th></th> 
            <th>Name</th> 
                    <th>Address</th>
                    <th>City</th>             
                    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php                     
                      foreach ($organisation->get_association() as $row) {                        
                        echo '<tr><td></td>';                        
                        echo '<td>'. $row['orgname'] . '</td>';
                        echo '<td>'. $row['address'] . '</td>';
                        echo '<td>'. $row['city'] . '</td>';
                        echo '<td><a href="orgadd.php?id='.$row['id'].'">Edit</a>';                     
                        echo '</td>';
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
