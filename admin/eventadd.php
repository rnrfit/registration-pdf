<?php include('ctrls/c_header.php');
include('classes/Event.php');
$event = new Event(); 
$Allevents=$event->get_mainevents();
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
$date_fromate = 'd-m-Y';
$eventid = '0';

if ( !empty($_GET['eventid'])) {    $eventid = $_REQUEST['eventid'];    }
  
    if ( !empty($_POST)){        
            $eventid =      $_POST['eventid'];       
            $Name=          $_POST['Name'];
            $address=$_POST['address'];
            $city=$_POST['city'];
            $cat1 =         $_POST['cat1'];       
            $cat2=          $_POST['cat2'];
            $Type =         $_POST['Type'];       
            $Category=          $_POST['Category'];
            $registerurl=          $_POST['registerurl'];
            $Description =  $_POST['Name'];      
           
            $cnt_qualifier=2;
            if ( !empty($_POST['cnt_qualifier']))
            {
                $cnt_qualifier=          $_POST['cnt_qualifier'];
            }
            $cnt_teamchamp =         $_POST['cnt_teamchamp']; 
            $teams_qualifier=5;
            if ( !empty($_POST['teams_qualifier'])){       
                $teams_qualifier=          $_POST['teams_qualifier'];
            }

            $exd =          DateTime::createFromFormat('d M, Y', $_POST['EventDate']);
            
            $checkEventDate=explode("-",$_POST['EventDate']);
            if(count($checkEventDate)>1)  
              $EventDate=$_POST['EventDate'];
            else    
              $EventDate= date_format($exd, 'Y-m-d');

            $exd1 = DateTime::createFromFormat('d M, Y', $_POST['EndDate']);
            $checkEndDate=explode("-",$_POST['EndDate']);
            if(count($checkEndDate)>1)  
              $EndDate=$_POST['EndDate'];
            else    
              $EndDate= date_format($exd1, 'Y-m-d');

            $isdemo=0;  
            if(!empty($_POST['isdemo']))
             $isdemo=1;

            // if($eventid=="")
            // {
                $event->eventadd($Name,$eventid,$address, $city,$Description,$EventDate,$EndDate,$isdemo,$cnt_qualifier,$cnt_teamchamp,$teams_qualifier,$Type ,$Category,$registerurl);
                    //get latest id so that image name can be appended with eventid
                $neweventid = $event->lasteventid();
                if(!empty($_POST['cat1']))
                $event->eventcategoryadd($neweventid,$cat1);
                if(!empty($_POST['cat2']))
                $event->eventcategoryadd($neweventid,$cat2);
            // }
            // else
            // {
            //     $event->eventupdate($Name,$Description,$EventDate,$EndDate,$id);
            // }         
            //file upload
           
            if(empty($_FILES))//when no pic selected
            {
                echo 'empty';
            }
            else{

                $temp = explode(".", $_FILES["logo1"]["name"]);
                $newfilename = $neweventid . '_l1.'. end($temp);
                $target = "assets/images/" . $newfilename;
                $logo1='';
                $logo2='';
                $rptheader='';
                if(move_uploaded_file($_FILES['logo1']['tmp_name'], $target))
                {
                    $logo1=$target;
                }//if move

                $temp1 = explode(".", $_FILES["logo2"]["name"]);
                $tname = $neweventid . '_l2.' . end($temp1);
                $target1 = "assets/images/" . $tname;

                if(move_uploaded_file($_FILES['logo2']['tmp_name'], $target1))
                {
                    $logo1=$target1;         
                }//if move

                $temp2 = explode(".", $_FILES["rptheader"]["name"]);
                $tname2 = $neweventid . '_rpt.' . end($temp2);
                $target2 = "assets/images/" . $tname2;

                if(move_uploaded_file($_FILES['rptheader']['tmp_name'], $target2))
                {
                    $rptheader=$target2;          
                }//if move
                $event->eventlogoupdate($logo1,$logo2,$rptheader,$neweventid);
            }//else 
            //file upload
            echo "   <script type=\"text/javascript\"> window.location=\"eventlist.php\";</script> "; 
        }
     else {
            $data = $event->get_event($eventid);
    }
 ?>
<!-- REGISTER MY INFORMATION -->
    <section>
        <div class="booking-bg-s lp">
            <div class="booking-bg-1">
                <div class="bg-book">
                    <div class="spe-title-1 spe-title-wid">
                        <h2>Add Event</h2>
                        <div class="hom-tit">
                            <div class="hom-tit-1"></div>
                            <div class="hom-tit-2"></div>
                            <div class="hom-tit-3"></div>
                        </div>
                    </div>
                    <div class="book-succ"><?php echo $msg; ?></div>
                    <div class="book-form">
                        <form class="form-horizontal" id="er_form" name="er_form" action="eventadd.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Main Event</label>
                                <div class="col-sm-10">
                                   <select class="form-control" id="eventid" name="eventid">
                                <option value="0">Select Event</option>
                                <?php 
                                      foreach ($Allevents as $row)
                                      {
                                        echo "<option value=".$row['Id'];    
                                        if ($row['Id'] == $eventid) echo ' selected="selected"';
                                        echo ">".$row['Name']."</option>";
                                      }
                                    ?>
                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Event Name:</label>
                                <div class="col-sm-10">
                                     <input type="text" class="form-control" id="Name" name="Name" placeholder="" value="<?php echo $data['Name'];?>" required />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-2">Address:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="address" name="address" maxlength="100" value="<?php echo $data['address'];?>" required />
                                    </div>
                                <label class="control-label col-sm-2">City:</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="city" name="city" maxlength="20" value="<?php echo $data['city'];?>" required />
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-2">Event Category:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="Category" name="Category">
                                                <option value="Artistic">Artistic</option>
                                                <option value="Trampolining">Trampolining</option>
                                                <option value="Tumbling">Tumbling</option>
                                                </select>
                                    </div>
                                <label class="control-label col-sm-2">Event Type:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="Type" name="Type">
                                <option value="Qualifier">Qualifier</option>
                                <option value="Final">Final</option>
                                <option value="igl">igl</option>
                                </select>
                                    </div>
                            </div>               
                            <div class="form-group">
                                <label class="control-label col-sm-2">Age group 1:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="cat1" name="cat1" placeholder="" />
                                    </div>
                                <label class="control-label col-sm-2">Age group 2:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="cat2" name="cat2"  />
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Event Start Date:</label>
                                <div class="col-sm-4">
                                        <input type="date" class="form-control" id="EventDate" value="<?php echo $data['EventDate'] ?>" name="EventDate" required>
                                    </div>
                                <label class="control-label col-sm-2">End Date:</label>
                                <div class="col-sm-4">
                                        <input type="date" class="form-control" id="EndDate" value="<?php echo $data['EventDate'] ?>" name="EndDate" required>
                                    </div>
                            </div> 
                             <div class="form-group">
                                <label class="control-label col-sm-2">Logo 1:</label>
                                    <div class="col-sm-4">
                                         <input type="file" id="logo1" name="logo1" style="width:200px" />
                                    </div>
                                <label class="control-label col-sm-2">Logo 2:</label>
                                    <div class="col-sm-4">
                                        <input type="file" class="form-control pull-left" name="logo2" style="width:200px" value="<?php echo $data['logo2']; ?>"/>
                                    </div>
                            </div>       
                             <div class="form-group">
                                <label class="control-label col-sm-2">Report Header:</label>
                                    <div class="col-sm-10">
                                       <input type="file" class="form-control pull-left" name="rptheader" style="width:200px" value="<?php echo $data['rptheader']; ?>"/>
                                    </div>
                            </div>     
                            <div class="form-group row">
                                <label class="control-label col-sm-2">Max Qualifying/State:</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="cnt_qualifier" name="cnt_qualifier" value="<?php echo $data['cnt_qualifier'];?>" />
                                    </div>
                                 <label class="control-label col-sm-2">Count for Team Champ:</label>
                                    <div class="col-sm-4">
                                      <input type="text" class="form-control" id="cnt_teamchamp" name="cnt_teamchamp" value="<?php echo $data['cnt_teamchamp'];?>" />
                                    </div>
                            </div>    
                            <div class="form-group">
                                <label class="control-label col-sm-2">Unit/State to combine for Qualifier:</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="teams_qualifier" name="teams_qualifier" value="<?php echo $data['teams_qualifier'];?>"
                             />
                                    </div>
                            </div>    
                            <div class="form-group row">
                                <label class="control-label col-sm-2">Register Url:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="registerurl" name="registerurl" value="<?php echo $data['registerurl'];?>" />
                                    </div>
                                <label class="control-label col-sm-2">&nbsp;</label>
                                    <div class="col-sm-4">
                                       <label class="checkbox-inline"><input type="checkbox" name="isdemo" value="1" <?php
                                if($data['isdemo']=='1' ) echo "checked" ; ?>>Is Demo</label>
                                    </div>
                            </div>                         
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