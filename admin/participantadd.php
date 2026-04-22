<?php include('ctrls/c_header.php');
include('classes/Event.php');
include('classes/Participant.php');
$participant = new Participant();
$event = new Event();
$eventid = "";
$eventtype = "";
$msg = "";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!empty($_GET['eventid'])) {
    $eventid = $_REQUEST['eventid']; 
}

 if ( !empty($_POST))
    {      
        // var_dump($_POST) ;
            $club=$_POST['club'];             
            $regid=$_POST['regid']; 
            $gender=$_POST['gender']; 
            $name=$_POST['name'];
            $level=$_POST['level'];
            // $dob= $_POST['dob'];  
            $Paid=0;            
            $amount=0;
            $datepaid='';
            $eventid = $_POST['eventid']; 
            $category=$_POST['category'];     
            $target = "../assets/images/gymnast/";
    
            $pic ="";
            //upload photo

            // $newfilename='';
            // $temp = explode(".", $_FILES["photo"]["name"]);
            // $picname = $regid . 'photo.' . end($temp);
            // $target = $target . $picname;

            //$pic = ($_FILES['photo']['name']); 
            // if ($_FILES['photo']['name'] != "")//when pic selected
            // {   
            //             $filename = $_FILES['photo']['name'];
            //             $ext = pathinfo($filename, PATHINFO_EXTENSION);
            //             $allowed = array('jpg','gif','png','jpeg');
            //             if( ! in_array( $ext, $allowed ) )
            //             {
            //                 echo "<script type=\"text/javascript\">alert('Only jpg,png format allowed in photo');</script>";
            //             }
            //             else
            //             {
            //                 //upload file 
            //                 if(move_uploaded_file($_FILES['photo']['tmp_name'], $target))
            //                 {  
            //                     $sql = "UPDATE `registrations` SET photo='$picname',name='$name', Gender='$gender', teamid='$club' where`id`='$regid'";               
                                
            //                     $comm->insertupdate($sql);               
            //                     $msg="Successfully saved";
            //                 }
            //             } 
            // }//upload photo
            // else
            // {
               $sql = "UPDATE registrations  set `name`='$name',`Gender`='$gender', `club`='$club' , `eventtype`='$category',level ='$level' WHERE `id`= $regid";
            //$sql = "UPDATE registrations  set `eventtype`='$category' WHERE `id`= $regid";
               //print_r($sql);
               
                $comm->insertupdate($sql); 
                $selected=0;
                for ($i = 1; $i <= 5; $i++) //check if apparatus is selected
                {
                    if (!empty($_POST['app' . $i])) 
                    {
                        $selected=1;
                    } //if
                } 
                if($selected==1)
                {
                    $sql = "DELETE FROM `gymnast_apparatus` WHERE `reg_id` = $regid";
                    $comm->insertupdate($sql); 
                }
                for ($i = 1; $i <= 5; $i++) 
                {
                    if (!empty($_POST['app' . $i])) 
                    {
                        $participant->apparatusadd( $eventid,$_POST['app' . $i],$regid, $gender );
                        $saved=1;
                    } //if
                }              
               $msg="Successfully saved"; 
               //header("location:GymnastList.php?eventid=$eventid");
                echo "<script type=\"text/javascript\"> window.location=\"GymnastList.php?eventid=$eventid\";</script> ";    
            //}
                  
    }
$condition = array('Id' => $eventid);
$eventdata=$comm->getRows('events',$condition);
$eventname=$eventdata[0]['Name'];
$eventtype=$eventdata[0]['Type'];
if (!empty($_GET['regid'])) {
    $regid = $_REQUEST['regid']; 

    $sqlcat = "SELECT *  FROM registrations WHERE id =$regid";   
    $rec = $comm->executesql($sqlcat); 
    $data=$rec[0]; 

}
 ?>
<!-- REGISTER MY INFORMATION -->
    <section>
        <div class="booking-bg-s lp">
            <div class="booking-bg-1">
                <div class="bg-book">
                    <div class="spe-title-1 spe-title-wid">
                        <h2><?php echo $eventname ?> - <span>Profile update</span> </h2>
                        <div class="hom-tit">
                            <div class="hom-tit-1"></div>
                            <div class="hom-tit-2"></div>
                            <div class="hom-tit-3"></div>
                        </div>
                    </div>
                    <div class="book-succ"><?php echo $msg; ?></div>
                    <div class="book-form">
                        <form class="form-horizontal" id="er_form" name="er_form" action="participantadd.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="regid" id="regid" value="<?php echo $regid;?>">
                        <input type="hidden" name="eventid" id="eventid" value="<?php echo $eventid;?>">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter Name" value="<?php echo $data['name']; ?>" maxlength="50" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">DOB</label>
                                <div class="col-sm-10">
                                <?php echo $data['dob']; ?>
                                    <!-- <input  type="date" date="yyyy-MM-dd" class="form-control" id="dob" name="dob" placeholder="DOB" value=""> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Club</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="club" name="club" >
                                                 <option value="">Select Club</option>
                    <?php  foreach ($event->get_cluboflatestevent($eventid) as $row)
                    {
                                    echo '<option value="'.$row['club'].'"';                                                     
                                                         if ($row['club'] == $data['club']) echo ' selected="selected"';
                                                       echo ">".$row['club']."</option>";
                                      }?>
                                                
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Age Group</label>
                                <div class="col-sm-10">
                                   <select class="form-control" id="category" name="category" >
                                                 <option value="">Select Category</option>
                            <?php  
                                     foreach ($event->get_eventcategory($eventid) as $row)
                                      {
                                        echo '<option value="'.$row['category'].'"';
                                        if ($row['category'] == $data['eventtype']) echo ' selected="selected"';
                                        echo ">".$row['category']."</option>";
                                      }
                          ?>
                                                </select>
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="control-label col-sm-2">Level</label>
                                <div class="col-sm-10">
                                   <select class="form-control" id="level" name="level" >
                                    <option value="">Select Level</option>
                                       <option value="1" <?php if($data['level'] =='1') echo "selected"; ?>>1</option>
                                       <option value="2" <?php if($data['level'] =='2') echo "selected"; ?>>2</option>
                                       <option value="3" <?php if($data['level'] =='3') echo "selected"; ?>>3</option>
                                       <option value="4" <?php if($data['level'] =='4') echo "selected"; ?>>4</option>
                                       <option value="5" <?php if($data['level'] =='5') echo "selected"; ?>>5</option>
                                       <option value="6" <?php if($data['level'] =='6') echo "selected"; ?>>6</option>
                                       <option value="7" <?php if($data['level'] =='7') echo "selected"; ?>>7</option>
                                       <option value="8" <?php if($data['level'] =='8') echo "selected"; ?>>8</option>
                                       <option value="9" <?php if($data['level'] =='9') echo "selected"; ?>>9</option>
                                       <option value="10" <?php if($data['level'] =='10') echo "selected"; ?>>10</option>
                                       <option value="Open" <?php if($data['level'] =='Open') echo "selected"; ?>>Open</option>
                                    </select>
                                </div>
                            </div>                          
                            <div class="form-group">
                                <label class="control-label col-sm-2">Gender</label>
                                <div class="col-sm-10">
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
                            <div class="form-group">
                            <label class="control-label col-sm-2">Apparatus:</label>
                            <div class="col-sm-10">
                                    <?php $appname=$participant->get_participants_apparatus($regid);
                                    echo $appname[0]['appname']; ?>
                                </div>
                            
                            </div>
                            <div class="form-group">
                            <label class="control-label col-sm-2">Select Apparatus:</label>
                            <div class="col-sm-10">
                                    <label class ="checkbox-inline" for = "app1" >
                                            <input type ="checkbox" id = "app1" name="app1" value ="6" class="roomselect"> VT
                                         </label>
                                         <label class ="checkbox-inline" for = "app2" >
                                            <input type ="checkbox" id = "app2" name="app2" value ="7" class="roomselect"> UB
                                         </label>
                                          <label class ="checkbox-inline" for = "app3" >
                                            <input type ="checkbox" id = "app3" name="app3" value ="8" class="roomselect"> BB
                                         </label>
                                          <label class ="checkbox-inline" for = "app4">
                                            <input type ="checkbox" id = "app4" name="app4" value ="9" class="roomselect"> FX
                                         </label>
                                         <label class ="checkbox-inline" for = "app5">
                                            <input type ="checkbox" id = "app5" name="app5" value ="19" class="roomselect"> Door Bar
                                         </label>
                                </div>                            
                            </div>
                           <!--  <div class="form-group">
                                <label class="control-label col-sm-2">Photo:</label>
                                <div class="col-sm-10">
                                     <input type="file" name="photo" />
                                </div>
                            </div> -->
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-10">
                                    <input type="submit" value="submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include('ctrls/footer.php'); ?>