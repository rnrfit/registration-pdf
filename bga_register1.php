<?php
ob_start();
include 'controls/header.php';
include 'inc/Participant.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$participant = new Participant();
//$all_districts = $participant->all_district('17');

$eventtype = "";
$msg = "";
$gender = '';
$eventid = 42;
$category = '';
$apid = 0;
$i = 0;
$saved = 0;
$msg = "";
$reg_id='';
if (!empty($_POST)) {
  $_POST = $comm->objdb->sanitizeArray($_POST);

  if (empty($_POST['name'])) {
    $msg = "Please Enter at least one participant name.";
  } else {
    $comm->objdb->checkPostInputVariables('email', 'TEXT', 'bga_register.php', 50);
    // $comm->objdb->checkPostInputVariables('city', 'TEXT', 'aer.php', 20);
    $comm->objdb->checkPostInputVariables('phone', 'TEXT', 'bga_register.php', 10);
    $comm->objdb->checkPostInputVariables('name', 'TEXT', 'bga_register.php', 50);

    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $contingent = '';
    $Event = 44;
    $datepaid = '';
    $txnid = 'AER_';
    $txnid .= mt_rand(99999, 999999);
    if (!empty($_POST)) {
      $fname='';
      $club='';
      $name = $_POST['name']; //lastname
      $lastname = $_POST['lastname'];
      $fathername = $_POST['fathername'];
      $policyno = $_POST['policyno'];
      $district = '';
      $eventgroup = '';
      $eventcategory ='';
      $dob = $_POST['dob'];
      $aadhaar = $_POST['aadhaar'];
      $Gender = $_POST['Gender'];
      $policycompany = $_POST['policycompany'];
     
      $role = '';
      $height = $_POST['height'];
      $weight = $_POST['weight'];
      $bloodgroup = $_POST['bloodgroup'];
      $costumesize = $_POST['costumesize'];
      $tshirtsize = $_POST['tshirtsize'];
      $tracksuit = $_POST['tracksuit'];
      $shoessize = $_POST['shoessize'];
      $address = $_POST['address'];
      $school = '';
      $grade = '';
      $artistic='';
      $rhythmic='';
      $aerobic='';
      $acrobatic='';
      if (isset($_POST['artistic']))
        $artistic=$_POST['artistic'];
      if (isset($_POST['rhythmic']))
        $rhythmic=$_POST['rhythmic'];
      if (isset($_POST['aerobic']))
        $aerobic=$_POST['aerobic'];
      if (isset($_POST['acrobatic']))
        $acrobatic=$_POST['acrobatic'];
      $Bankname = $_POST['Bankname'];
      $Branch =$_POST['Branch'];
      $accountno = $_POST['accountno'];
      $parent = $_POST['parent'];
      $ifsccode =$_POST['ifsccode'];
      $parentphone =$_POST['parentphone'];
      
      $state = '';
      $city ='';
      $regdate = date("Y-m-d");
      $photo = '';
      $idproof = '';
      $policyfile='';
      $date = DateTime::createFromFormat("Y-m-d", $dob);
      $year = $date->format("Y");

      $checkifexists = $participant->get_participant_byname_aer($name, $eventid, $email);

      //print_r($checkifexists);
      if (empty($checkifexists)) {
        //print_r('expression')       ;
        $reg_id = $participant->participantadd_aer($fname,$name,$lastname,$fathername,$district,$club,$eventgroup,$eventcategory,$dob,$aadhaar,$Gender,$policyno,$policycompany,$role,$height,$weight,$bloodgroup,$costumesize,$tshirtsize,$tracksuit,$shoessize,$address,$email,$phone,$school,$grade,$Bankname,$Branch,$accountno,$ifsccode,$parent,$parentphone,$Event,$txnid,$photo,$idproof,$city,$state,$artistic,$rhythmic,$aerobic,$acrobatic);


        //upload here
        if (!empty($_FILES['photo']['name'])) //when no pic selected
        {
            $temp = explode(".", $_FILES["photo"]["name"]);
            $newfilename = $reg_id . '_p.' . end($temp);
            $target = "assets/aer/" . $newfilename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                $photo = $target;       
            } //if move
        }
        if (!empty($_FILES['idproof']['name'])) //when no pic selected
        {
            $temp = explode(".", $_FILES["idproof"]["name"]);
            $newfilename = $reg_id . '_i.' . end($temp);
            $target = "assets/aer/" . $newfilename;

            if (move_uploaded_file($_FILES['idproof']['tmp_name'], $target)) {
                $idproof = $target;      
            } //if move
        }
        if (!empty($_FILES['policyfile']['name'])) //when no pic selected
        {
            $temp = explode(".", $_FILES["policyfile"]["name"]);
            $newfilename = $reg_id . '_m.' . end($temp);
            $target = "assets/aer/" . $newfilename;

            if (move_uploaded_file($_FILES['policyfile']['tmp_name'], $target)) {
                $policyfile = $target;                         
            } //if move
        }
        $participant->participantupdate_aer($photo, $idproof,$policyfile, $reg_id);       

        $saved = 1;
      } else {
        $msg = $name . " is already registered";
      }
    } //if
    if (!empty($reg_id)) {
      $msg = "Successfully saved";
      header('Location:bga_download.php?id=' . $reg_id);
      exit();
    } else {
      $msg = "Data can't be saved";
    }
  }
}
ob_end_flush();
?>
<style type="text/css">
  .form-control {
    color: #000000 !important;
  }
</style>
<section>
  <div class="booking-bg-s lp">
    <div class="booking-bg-1">
      <div class="bg-book" style="padding-top: 10px !important;">
        <div class="text-center spe-title-wid">
          <h2 style="font-size: 20px !important;">Bengaluru Gymnasts Association</h2>
          <!-- <h2 style="font-size: 10px !important;">State Aerobics Gymnasts Selection 2022 </h2> -->
        </div>
        <div class="row" align="center"> <strong><?php echo $msg; ?></strong></div>
        <div class="book-form">
          <form class="form-horizontal" id="commentForm" action="bga_register.php" method="post" enctype="multipart/form-data">
            <!-- <div class="card-header">
              <h3 style="font-size: 20px !important;">Event Information</h3>
            </div> -->
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
                <label>Aadhar No.</label>
                <input type="text" name="aadhaar" class="form-control" id="aadhaar" minlength="10" maxlength="12" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
              </div>
            </div>
            <div class="form-group ">
              <label>Father's Name</label>
              <input type="text" class="form-control" id="fathername" placeholder="Father Name" name="fathername" minlength="5" maxlength="100" required />
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

            </div>
            <div class="form-group row">
              <label>Medical Policy No.</label>
              <input type="text" class="form-control" id="policyno" placeholder="policyno" name="policyno" onkeypress="lettersNumbersCheck(this)" maxlength="20" required/>
            </div>
            <div id="name" class="form-group ">
              <label>Name of the company of Policy</label>
              <input type="text" name="policycompany" class="form-control" required id="policycompany" maxlength="60" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" >
            </div>
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Height(in cm):</label>
                <input type="text" class="form-control" id="height" placeholder="height" name="height" required maxlength="10" minlength="2" />
              </div>
              <div class="col-lg-6">
                <label>Weight(in kg):</label>
                <input type="text" class="form-control" id="weight" placeholder="weight" name="weight" required maxlength="10" minlength="2" />
              </div>
            </div>
            <div class="form-group row">
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
                <input type="text" class="form-control" id="ifsccode" placeholder="ifsccode" name="ifsccode" required maxlength="20" minlength="4" />
              </div>
            </div>
            <div class="form-group row"> <label>Only Gymnast/Participant Bank Details to be provided.</label>
            </div>
            <div class="form-group row">
              <div class="col-lg-2">
                <label>Discipline</label>
              </div>
              <div class="col-lg-8 row">
                  <label class="checkbox-inline" for="app1">
                    <input type="checkbox" id="artistic" name="artistic" value="artistic" class="roomselect"> Artistic
                  </label>
                  <label class="checkbox-inline" for="app2">
                    <input type="checkbox" id="rhythmic" name="rhythmic" value="rhythmic" class="roomselect"> Rhythmic
                  </label>
                  <label class="checkbox-inline" for="app3">
                    <input type="checkbox" id="aerobic" name="aerobic" value="aerobic" class="roomselect"> Aerobic
                  </label>
                  <label class="checkbox-inline" for="app4">
                    <input type="checkbox" id="acrobatic" name="acrobatic" value="acrobatic" class="roomselect"> Acrobatic
                  </label>                 
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
                <label>T-Shirt size:</label>
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
                <label>Track suit size:</label>
                <select class="form-control" id="tracksuit" name="tracksuit" required>
                  <option value="">Size</option>
                  <option value="XS">XS</option>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                </select>
              </div>
              <div class="col-lg-6">
                <label>Shoes size:</label>
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
            <div class="form-group row">
              <div class="col-lg-5">
                <label>Accompanying the Gymnast</label>
                <input type="text" name="parent" class="form-control" id="parent" minlength="2" maxlength="100" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required>
              </div>
              <div class="col-lg-6">
                <label>Phone</label>
                <input type="tel" class="form-control" id="parentphone" placeholder="Mobile" name="parentphone" required onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" minlength="10" />
              </div>
            </div>
            <div class="form-group ">
              <label>Photo Upload:</label>
              <input type="file" class="form-control" name="photo" style="width:200px" />
            </div>
            <div class="form-group ">
              <label>Aadhar Upload:</label>
              <input type="file" class="form-control" name="idproof" style="width:200px" />
            </div>
            <div class="form-group ">
              <label>Medical Policy Upload:</label>
              <input type="file" class="form-control" name="policyfile" style="width:200px" />
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
  function lettersNumbersCheck(name) {
    var regEx = /^[0-9a-zA-Z]+$/;
    if (name.value.match(regEx)) {
      return true;
    } else {
      return false;
    }
  }
</script>
</body>

</html>