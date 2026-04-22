<?php
ob_start();
include 'controls/header.php';
include('inc/Participant.php');
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
$participant = new Participant();
$all_districts=$participant->all_district('17');

$eventtype = "";
$msg = "";
$gender = '';
$eventid = 42;
$category = '';
$apid = 0;
$i = 0;
$saved = 0;
$msg = "";
if (!empty($_POST)) {
  $_POST = $comm->objdb->sanitizeArray($_POST);

  if (empty($_POST['name'])) {
    $msg = "Please Enter at least one participant name.";
  } else {
    $comm->objdb->checkPostInputVariables('email', 'TEXT', 'aer.php', 50);
    $comm->objdb->checkPostInputVariables('city', 'TEXT', 'aer.php', 20);
    $comm->objdb->checkPostInputVariables('phone', 'TEXT', 'aer.php', 10);
    $comm->objdb->checkPostInputVariables('name', 'TEXT', 'aer.php', 50);

    
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $contingent = '';
    $Event = 44;
    $datepaid = '';
    $txnid = 'AER_';
    $txnid .= mt_rand(99999, 999999);
    if (!empty($_POST)) {
     
        $name = $_POST['name'];//lastname
        $fname = $_POST['name'];
        $lastname = $_POST['lastname'];
        $fathername = $_POST['parent'];
        $policyno = '';
        $district = $_POST['district'];    
        $eventgroup = $_POST['eventgroup'];      
        $eventcategory = $_POST['eventcategory'];    
        $dob = $_POST['dob'];
        $aadhaar = $_POST['aadhaar'];
        $Gender = $_POST['Gender'];
        $policyno = '';
        $policycompany = '';
        $role = '';
        $height = $_POST['height'];
        $weight = $_POST['weight'];
        $bloodgroup = $_POST['bloodgroup'];
        $costumesize = $_POST['costumesize'];
        $tshirtsize = $_POST['tshirtsize'];
        $tracksuit = $_POST['tracksuit'];
        $shoessize = $_POST['shoessize'];
        $address = $_POST['address'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $Bankname = '';
        $Branch = '';
        $accountno = '';
        $parent = $_POST['parent'];
        $parentphone = '';
        $ifsccode = '';
        $state ='';
        $club = '';
        $artistic = '';
        $rhythmic = '';
        $aerobic = '1';
        $acrobatic = '';
        $photo = '';
        $city = $_POST['city'];
        $regdate = date("Y-m-d");        
         $photo='';
                $idproof='';
        $date = DateTime::createFromFormat("Y-m-d", $dob);
        $year= $date->format("Y");
        

        $checkifexists = $participant->get_participant_byname_aer($name,$eventid,$email);

        //print_r($checkifexists);
        if (empty($checkifexists)) {   
        //print_r('expression')       ;
           $reg_id=$participant->participantadd_aer($fname,$name,$lastname,$fathername,$district,$club,$eventgroup,$eventcategory,$dob,$aadhaar,$Gender,$policyno,$policycompany,$role,$height,$weight,$bloodgroup,$costumesize,$tshirtsize,$tracksuit,$shoessize,$address,$email,$phone,$school,$grade,$Bankname,$Branch,$accountno,$ifsccode,$parent,$parentphone,$eventid,$txnid,$photo,$idproof,$city,$state,$artistic,$rhythmic,$aerobic,$acrobatic);
          
                //upload here
                $temp = explode(".", $_FILES["photo"]["name"]);
                $newfilename = $reg_id . '_p.'. end($temp);
                $target = "assets/aer/" . $newfilename;
              
                if(move_uploaded_file($_FILES['photo']['tmp_name'], $target))
                {
                    $photo=$target;
                }//if move

                $temp1 = explode(".", $_FILES["idproof"]["name"]);
                $tname = $reg_id . '_i.' . end($temp1);
                $target1 = "assets/aer/" . $tname;

                if(move_uploaded_file($_FILES['idproof']['tmp_name'], $target1))
                {
                    $idproof=$target1;         
                }//if move

                $participant->participantupdate_aer($photo,$idproof,'',$reg_id);
                 //upload here
          
          $saved = 1;
        } else {
          $msg = $name . " is already registered";
        }
      } //if      
    if ($saved == 1) {
      $msg = "Successfully saved";
      header('Location:aer_download.php?id=' . $reg_id);
      exit();
    } else {
      $msg = "Data can't be saved";
    }
  }
}
ob_end_flush();
?>
<style type="text/css">
    .form-control{
        color: #000000 !important;
    }
</style>
<section>
  <div class="booking-bg-s lp">
    <div class="booking-bg-1">
      <div class="bg-book" style="padding-top: 10px !important;">
        <div class="text-center spe-title-wid">
          <h2 style="font-size: 20px !important;">Gymnasts Association of Karnataka </h2>
          <h2 style="font-size: 10px !important;">State Aerobics Gymnasts Selection 2022 </h2>
        </div>
        <div class="row" align="center"> <strong><?php echo $msg; ?></strong></div>
        <div class="book-form">
          <form class="form-horizontal" id="commentForm" action="aer_register.php" method="post" enctype="multipart/form-data">  
           <div class="card-header">
              <h3 style="font-size: 20px !important;">Event Information</h3>
            </div> 
             <div id="name" class="form-group ">
              <label>Name</label>
                <select class="form-control" id="district" name="district">
                                <option value="0">Select District</option>
                                <?php 
                                      foreach ($all_districts as $row)
                                      {
                                        echo "<option value=".$row['district_title'];    
                                        
                                        echo ">".$row['district_title']."</option>";
                                      }
                                    ?>
                    </select>
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Group</label>
               <select class="form-control" id="eventgroup" name="eventgroup" required>
                <option value="">Group</option>
                <option value="Sub Junior">Sub Junior</option>
                <option value="Junior I">Junior I</option>
                <option value="Junior II">Junior II</option>
                <option value="Senior">Senior</option>
              </select>
              </div>
              <div class="col-lg-6">
                <label>Event</label>
                <select class="form-control" id="eventcategory" name="eventcategory" required>
                <option value="">Event</option>
                <option value="Individual Men">Individual Men</option>
                <option value="Individual Women">Individual Women</option>
                <option value="Mix Pair">Mix Pair</option>
                <option value="Trio">Trio</option>
                <option value="Group">Group</option>
                <option value="Aero Dance">Aero Dance</option>
              </select>
              </div>
            </div>       
            <div class="card-header">
              <h3 style="font-size: 20px !important;">Details of Gymnast</h3>
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
                <label>First Name</label>
                <input type="text" name="name" class="form-control" id="name" minlength="2" maxlength="40" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required>
              </div>
              <div class="col-lg-6">
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" id="lastname" minlength="1" maxlength="40" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Dob</label>
                <input type="date" date="yyyy-MM-dd" name="dob" id="dob" value="2010-01-01" class="form-control" required>
              </div>
              <div class="col-lg-6">
                <label>Aadhar</label>
                <input type="text" name="aadhaar" class="form-control" id="aadhaar" minlength="10" maxlength="12" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Gender:</label>
                <select id="Gender" name="Gender" class="form-control" required>
                  <option value="Male" selected>Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
               <div class="col-lg-6">
                <label>Blood Group</label>
                <select class="form-control" id="bloodgroup" name="bloodgroup" required>
                <option value="">Blood Group</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="A+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
              </div>
              <!-- <div class="col-lg-6">
                <label>Policy No.</label>
                <input type="text" class="form-control" id="policyno" placeholder="policyno" name="policyno" required onkeypress="lettersNumbersCheck(this)" maxlength="20" minlength="2" />
              </div> -->
            </div>
             <div class="form-group ">
              <label>Father Name</label>
              <input type="text" class="form-control" id="parent" placeholder="Father Name" name="parent" minlength="5" maxlength="100" required />
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
               <label>School</label>
              <input type="text" class="form-control" id="school" placeholder="School" name="school" minlength="5" maxlength="100" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required />
              </div>
              <div class="col-lg-6">
                <label>Grade</label>
              <input type="text" class="form-control" id="grade" placeholder="Grade" name="grade" required onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" minlength="1"  maxlength="100" />
              </div>
            </div>
            <!-- <div id="name" class="form-group ">
              <label>Name of the company of Policy</label>
              <input type="text" name="policycompany" class="form-control" id="policycompany" minlength="2" maxlength="60" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required>
            </div> -->
            <!-- <div class="form-group row">
              <div class="col-lg-5">
                <label>Role:</label>
                <input type="text" class="form-control" id="role" placeholder="role" name="role" required onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" maxlength="20" minlength="2" />
              </div>             
            </div> -->
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Height(in cm):</label>
                <input type="text" class="form-control" id="height" placeholder="height" name="height" required maxlength="20" minlength="2" />
              </div>
              <div class="col-lg-6">
                <label>Weight(in kg):</label>
                 <input type="text" class="form-control" id="weight" placeholder="weight" name="weight" required maxlength="20" minlength="2" />
              </div>
            </div>
             
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Costume size:</label>
                <select class="form-control" id="costumesize" name="costumesize" required>
                  <option value="">Size</option>
                  <option value="XS">XS</option>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                </select>
              </div>
              <div class="col-lg-6">
                <label>Tshirt size:</label>
                 <select class="form-control" id="tshirtsize" name="tshirtsize" required>
                  <option value="">Size</option>
                  <option value="XS">XS</option>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Tracksuit size:</label>
                <select class="form-control" id="tracksuit" name="tracksuit" required>
                  <option value="">Size</option>
                  <option value="XS">XS</option>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                </select>
              </div>
              <div class="col-lg-6">
                <label>Shoessize size:</label>
                  <select class="form-control" id="shoessize" name="shoessize" required>
                  <option value="">Size</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                </select>
              </div>
            </div>
            
              <div class="form-group ">
              <label>Address</label>
              <input type="text" class="form-control" id="address" placeholder="address" name="address" minlength="10" maxlength="100" required />
            </div>
             <div class="form-group row">
              <div class="col-lg-5">
               <label>Email</label>
              <input type="email" class="form-control" id="email" placeholder="Email" name="email" maxlength="50" required />
              </div>
              <div class="col-lg-6">
                <label>Phone</label>
              <input type="tel" class="form-control" id="phone" placeholder="Mobile" name="phone" required onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" minlength="10" />
              </div>
            </div>
            <!--  <div class="form-group row">
              <div class="col-lg-5">
               <label>Bankname</label>
              <input type="text" class="form-control" id="Bankname" placeholder="Bankname" name="Bankname" maxlength="50" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required />
              </div>
              <div class="col-lg-6">
                <label>Branch</label>
              <input type="text" class="form-control" id="Branch" placeholder="Branch" name="Branch" required onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" maxlength="50" minlength="4" />
              </div>
            </div>
             <div class="form-group row">
              <div class="col-lg-5">
               <label>Account no</label>
              <input type="text" class="form-control" id="accountno" placeholder="accountno" name="accountno" maxlength="20" required />
              </div>
              <div class="col-lg-6">
                <label>IFSC code</label>
              <input type="text" class="form-control" id="ifsccode" placeholder="ifsccode" name="ifsccode" required  maxlength="20" minlength="4" />
              </div>
            </div> -->
             <div class="card-header">
              <h3 style="font-size: 20px !important;">Details of the section trial at which participation is requested</h3>
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
               <label>Venue/City</label>
              <input type="text" class="form-control" id="city" placeholder="City" name="city" required onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" value="Bengaluru" maxlength="20" minlength="2" />
              </div>
              <div class="col-lg-6">
                <label>Date</label>
                  <label>20th March 2022</label>
              <!-- <input type="date" class="form-control" id="edate" placeholder="edate" name="edate" required /> -->
              </div>
            </div>
            <div class="form-group ">
              <label>Accompanying the Gymnast:</label>
              <input type="text" name="parent" class="form-control" id="parent" minlength="2" maxlength="100" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required>
            </div>
            <div class="form-group ">
              <label>Photo Upload:</label>
              <input type="file" class="form-control" name="photo" style="width:200px"/>
            </div>
            <div class="form-group ">
              <label>Id Proof:</label>
              <input type="file" class="form-control" name="idproof" style="width:200px"/>
            </div>
            <div class="form-group">
              <div class=" col-sm-10">
                <input type="submit" value="Submit" name="submit" class="btn btn-primary "></input>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.js"></script>
<!-- <script src="assets/js/custom.js"></script> -->
<script type="text/javascript">
  
</script>
<section>
  <div class="ffoot">
    <div class="lp">
      <!--SECTION: FOOTER-->
      <div class="row foot2">
        <div class="col-md-3">

          <div class="foot2-2 foot-soc foot-com">
            <h4>Follow Us Now</h4>
            <ul>
              <li><a href="#"><i class="fa fa-facebook fb1"></i></a>
              </li>

            </ul>
            <span class="foot-ph">Phone: +96329 61700</span>
          </div>
        </div>
        <div class="col-md-3">

          <div class="foot2-2 foot-soc foot-com">

          </div>
        </div>
        <div class="col-md-6">
          <div class="foot2-32 foot-pop foot-com">
            <h4>GYMNASTICS Events</h4>
            <p>To inculcate in young minds the spirit of healthy competition while exhibiting their talents and
              potential</p>
            <ul>
              <li>
                <img src="assets/images/trends/2.jpeg" alt="">
                <div class="foot-pop-eve">
                  <span>Artistic Gymnastics</span>
                  <h4>29th Sub Jr. Artistic Gymnastics National Championship 2019</h4>
                  <p>JUNE 28TH, 2019</p>
                </div>
              </li>
              <li>
                <img src="assets/images/trends/2.jpg" alt="">
                <div class="foot-pop-eve">
                  <span>Artistic Gymnastics</span>
                  <h4>RYP 55th Jr. Artistic Gymnastics National Championship 2019</h4>
                  <p>AUGUST 23RD, 2017</p>
                </div>
              </li>
              <li>
                <img src="assets/images/trends/6.jpeg" alt="">
                <div class="foot-pop-eve">
                  <span>Artistic Gymnastics</span>
                  <h4>RYP 57th Sr. Artistic Gymnastics National Championship 2019</h4>
                  <p>MAY 3RD, 2019</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!--SECTION: FOOTER-->
      <div class="row">
        <div class="col-md-12 foot4">
          <h5>Our Sponsors</h5>
          <ul>
            <li>
              <a href="#"><img src="assets/images/sponsor/rnr.png" height="80" width="80" alt="">
              </a>
            </li>
            <li>
              <a href="#"><img src="assets/images/sponsor/getactive.png" height="80" width="80" alt="">
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
<!--Footer-->
<section>
  <!-- FOOTER: COPY RIGHTS -->
  <div class="fcopy">
    <div class="lp copy-ri row">
      <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="col-md-4">Powered by&nbsp; <a target="_blank" href="http://rnrfit.com/"><img class="logo1" src="assets/images/rnr.png" alt="rnrfit" style="height:25px;width:25px;"></a></div>
      </div>
      <div class="col-md-6 foot-privacy">
        <ul>
          <li><a href="#">Privacy</a>
          </li>
          <li><a href="#">Terms of use</a>
          </li>
          <li><a href="#">Security</a>
          </li>
          <li><a href="#">Policy</a>
          </li>

        </ul>
      </div>
    </div>
  </div>
</section>
<script> 
function lettersNumbersCheck(name)
{
   var regEx = /^[0-9a-zA-Z]+$/;
   if(name.value.match(regEx))
     {
      return true;
     }
   else
     {     
     return false;
     }
}    
</script>
</body>

</html>