<?php
$menu = "User";
include('inc/headers/c_header.php');
include('inc/Common/Users.php');

$user_id = 0;
$type='';
if(isset($_REQUEST['memberid']) && !empty($_REQUEST['memberid']))
{
    $user_id=$_GET['memberid'];
    $type='edit';
}
if (!empty($_POST['btnsubmit'])) 
{
    $type='insert';

    $user_id =   $_POST['user_id']; 
   
    $username='';
    if(!empty($_POST['username']))
         $username=$_POST['username'];

    $password='';
    if(!empty($_POST['password']))
         $password=$_POST['password'];
    $type = $_POST['utype'];
    $active = "Yes";

//username,password,email,type,active,LocationId
    switch($type){
        case "insert":  
       
                $options = array('cost' => 8);
                require_once 'passwordfile.php';
                $hashedpassword = password_hash($password, PASSWORD_BCRYPT, $options);      
                $sql="INSERT INTO `users`(`username`, `password`, `active`, `type`) VALUES('".$_POST['username']."','".$hashedpassword."','".$active."','".$_POST['type']."')";
                $insert = $comm->insertupdate($sql);
            break;
        case "edit":  
        $options = array('cost' => 8);
                require_once 'passwordfile.php';
                $hashedpassword = password_hash($password, PASSWORD_BCRYPT, $options);     
              
                $sql="UPDATE `users` SET `username`='".$_POST['username']."',`password`='".$hashedpassword."',`type`='".$_POST['type']."' where memberid=$user_id";
                $update = $comm->insertupdate($sql);
                break;
            }
}
$user = new User();
$records = $user->get_user($user_id);  

?>
 <div class="container">

<!-- Page header -->

            <div class="page-header page-header-light" style="height:70px;">

                <div class="page-header-content header-elements-md-inline" >
                    <div class="page-title d-flex">
                    <h4><a href="events.php"><i class="icon-arrow-left52 mr-2"></i></a> <span class="font-weight-semibold">Add User</span></h4>
                    </div>
                </div>

            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
            <!-- Default ordering -->
                <div class="card">
                 <form class="form-horizontal" action="user_create.php" method="post" >
                                <div class="panel panel-flat">
                                <div class="panel-body">  
                                <input type="hidden" class="form-control" id="user_id" name="user_id"  value="<?php echo $records['memberid'];?>"  />
                                    <input type="hidden" id="utype" name="utype"  value="<?php echo $type;?>"  />
                                    <div class="form-group">
                                            <label class="col-lg-3 control-label">User Name:</label>
                                            <div class="col-lg-9">
                                               <input name="username" type="text" class="form-control input-lg"  placeholder="" value="<?php echo $records['username'];?>">
                                               
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">Password:</label>
                                            <div class="col-lg-9">
                                               <input name="password" type="password"  class="form-control input-lg"  placeholder="" value="">                                               
                                    </div></div>
                                    <div class="form-group">
                                         <label class="col-lg-3 control-label">Type:</label>
                                            <div class="col-lg-9">
                                           <select class="form-control input-lg" id="type" name="type" value="<?php echo !empty($$records['type'])?$$records['type']:'';?>" tabindex="1">
                                                <option value="">Select User Type</option>
                                                <option value="SuperAdmin">SuperAdmin</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Secretory">Secretory</option>
                                                 <option value="D1">D1</option>
                                                 <option value="D2">D2</option>
                                                 <option value="E1">E1</option>
                                                 <option value="E2">E2</option>
                                                 <option value="E3">E3</option>
                                                 <option value="E4">E4</option>
                                                 <option value="E5">E5</option>
                                                
                                            </select>
                                            <!--<input name="batchtype" type="text" class="form-control input-lg"  placeholder="" value="<?php echo !empty($type)?$type:'';?>">-->
                                            <?php if (!empty($typeError)): ?>
                                                <span class="help-inline"><?php echo $typeError;?></span>
                                            <?php endif; ?>
                                    </div></div>                                    
                                    <div >
                                        <button type="submit" class="btn btn-danger">Create</button>
                        <a class="btn btn-danger" href="users.php">Back</a>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    </div>
                                </div>
                                </div>
                            </form>



 </div>
                <!-- /default ordering -->

                </div>
            <!-- /content area -->
            </div>

<?php include('inc/headers/footer.php'); ?>
