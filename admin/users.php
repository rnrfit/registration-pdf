<?php
$menu = "User";
include('inc/headers/c_header.php');
include('inc/Common/Users.php');

$user = new User();  
$records=$user->get_users();
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
                    <h4><a href="events.php"><i class="icon-arrow-left52 mr-2"></i></a> <span class="font-weight-semibold">User List</span></h4>
                    </div>
                </div>

            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
            <!-- Default ordering -->
                <div class="card">
                  <ul class="navbar-nav ml-auto">
                       <li class="nav-item"> <a href="user_create.php" class="nav-link text-white"><span class="label bg-danger btn-sm">Add User</span></a>  </li>                      
                    </ul>
<table class="table datatable-select-checkbox datatable-button-init-basic" id="table2excel">
                                        <thead>
                                            <tr>
                                           <th ></th>
                                                <th ><i class="fa fa-user fa-fw"></i>&nbsp;User Name</th>
                                                <th ><i class="fa fa-envelope-o fa-fw"></i>&nbsp;&nbsp;Email Address</th>
                                                <th>Type</th>
                                                <th><i class="fa fa-edit fa-fw"></i>&nbsp;&nbsp;Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <?php
                                             foreach ($records as $row) {
                                                        echo '<tr><td></td>';
                                                        echo '<td>'. $row['username'] . '</td>';
                                                        echo '<td>'. $row['email'] . '</td>';                                                  
                                                        echo '<td>'. $row['type']  . '</td>';                                                   
                                                        echo '<td width=250>';
                                                        echo '&nbsp;';
                                                        echo '<a class="fa fa-pencil" href="user_create.php?memberid='.$row['memberid'].'"></a>';
                                                        echo '&nbsp;';
                                                        echo '<a class="fa fa-remove" href="admin_users_delete.php?memberid='.$row['memberid'].'"></a>';
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
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<?php include('inc/headers/footer.php'); ?>
