<?php include('ctrls/c_header.php');
include('classes/Event.php');
include('classes/Participant.php');
$participant = new Participant();
$event = new Event();
$eventid = "";
$eventtype = "";
$msg = "";

if (!empty($_GET['eventid'])) {
    $eventid = $_REQUEST['eventid']; 
}

 if ( !empty($_POST))
    {      
            $club=$_POST['club'];             
            $Email=$_POST['Email']; 
            $Phone=$_POST['Phone']; 
            $eventid=$_POST['eventid']; 
            $Paid=0;            
            $amount=0;
            $datepaid='';
            
            for($i=1;$i<=10;$i++)
            {
                if(!empty($_POST['name'.$i])){  
                    $name=$_POST['name'.$i];             
                    $gender=$_POST['gender'.$i];  
                    $event=$_POST['event'.$i]; 
                    // $exd = DateTime::createFromFormat('d M, Y', $_POST['dob'.$i]);
                    // $checkdt=explode("-",$_POST['dob'.$i]);
                    // if(count($checkdt)>1)  
                    //   $dob=$_POST['dob'.$i];
                    // else    
                    //   $dob= date_format($exd, 'Y-m-d');
                    $dob=date("Y-m-d");
                    $regdate=date("Y-m-d");
                    if( $Paid==1)
                    {
                        $amount=0;
                        $datepaid=date("Y-m-d");
                    }
                    $participant->participantadd($name, $gender, $dob, $eventid, $club, $event,$regdate,$Email,$Phone,$amount,$datepaid);
                }//if

            } //for                
             
            $msg="Successfully saved";               
    }
$condition = array('Id' => $eventid);
$eventdata=$comm->getRows('events',$condition);
$eventname=$eventdata[0]['Name'];
$eventtype=$eventdata[0]['Type'];
 ?>
<!-- REGISTER MY INFORMATION -->
    <section>
        <div class="booking-bg-s lp">
            <div class="booking-bg-1">
                <div class="bg-book">
                    <div class="spe-title-1 spe-title-wid">
                        <h2>Register - <span><?php echo $eventname ?></span> </h2>
                        <div class="hom-tit">
                            <div class="hom-tit-1"></div>
                            <div class="hom-tit-2"></div>
                            <div class="hom-tit-3"></div>
                        </div>
                    </div>
                    <div class="book-succ"><?php echo $msg; ?></div>
                    <div class="book-form">
                        <form class="form-horizontal" id="er_form" name="er_form" action="register.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="eventid" id="eventid" value="<?php echo $eventid;?>">
                            <div class="form-group">
                                <label class="control-label col-sm-2">State/Unit</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="club" name="club"  value="" maxlength="50"  required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Email:</label>
                                <div class="col-sm-10">
                                     <input type="email" class="form-control" id="Email" name="Email" placeholder="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="Phone" name="Phone" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  maxlength="10" />
                                </div>
                            </div>
                            <?php

                                    for($i=1;$i<=10;$i++)
                                    {
                                        echo '<div class="form-row">';
                                        echo '<div class="form-group col-md-2">'.$i.'</div>';
                                        echo '<div class="form-group col-md-5"><input type="text" class="form-control" id="name'.$i.'" name="name'.$i.'" placeholder="Enter Name" value="" maxlength="50" /></div>';
                                        echo '<div class="form-group col-md-3">                                
                                    <select id="gender'.$i.'" name="gender'.$i.'" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                            </div>';
                                        echo '<div class="form-group col-md-3"><select class="form-control" id="event'.$i.'" name="event'.$i.'" >
                                                 <option value="">Select Category</option>';
                            
                                     foreach ($event->get_eventcategory($eventid) as $row)
                                      {
                                        echo "<option value=".$row['category'];    
                                        if ($row['category'] == $category) echo ' selected="selected"';
                                        echo ">".$row['category']."</option>";
                                      }
                          
                                                echo '</select></div></div>';
                                    }
                                    ?>                 
                           
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