<?php
ob_start();
include 'controls/header.php';
include('inc/Participant.php');
include('inc/Event.php');
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
$participant = new Participant();
$event = new Event();
$eventid = 89;
$all_districts=$participant->all_district('17');
$eventdata=$event->geteventsbyid($eventid);
$eventname = !empty($eventdata) && isset($eventdata[0]['Name']) ? $eventdata[0]['Name'] : '';
$eventdate = (!empty($eventdata) && !empty($eventdata[0]['EventDate'])) ? date("jS F Y", strtotime($eventdata[0]['EventDate'])) : 'To Be Announced';
$eventtype = "";
$msg = "";
$gender = '';

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
    $comm->objdb->checkPostInputVariables('email', 'TEXT', 'index.php', 50);
    $comm->objdb->checkPostInputVariables('city', 'TEXT', 'index.php', 20);
    $comm->objdb->checkPostInputVariables('phone', 'TEXT', 'index.php', 10);
    $comm->objdb->checkPostInputVariables('name', 'TEXT', 'index.php', 50);

    
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $contingent = '';
    $Event = $eventid;
    $datepaid = '';
    $txnid = 'GAK_';
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
        $aerobic = '0';
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
                $target = "assets/gak/" . $newfilename;
              
                if(move_uploaded_file($_FILES['photo']['tmp_name'], $target))
                {
                    $photo=$target;
                }//if move

                $temp1 = explode(".", $_FILES["idproof"]["name"]);
                $tname = $reg_id . '_i.' . end($temp1);
                $target1 = "assets/gak/" . $tname;

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
      header('Location:aer_form_pdf.php?id=' . $reg_id);
      exit();
    } else {
      $msg = "Data can't be saved";
    }
  }
}
ob_end_flush();
?>
<style type="text/css">
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Sora:wght@500;600;700&display=swap');

  :root {
    --reg-bg-start: #fff4df;
    --reg-bg-end: #e8f6ff;
    --reg-accent: #d95f02;
    --reg-accent-dark: #a84a06;
    --reg-heading: #17263c;
    --reg-text: #334155;
    --reg-muted: #64748b;
    --reg-border: #d7e3ef;
    --reg-surface: rgba(255, 255, 255, 0.9);
    --reg-focus: rgba(217, 95, 2, 0.2);
    --reg-shadow: 0 18px 45px rgba(23, 38, 60, 0.14);
  }

  .booking-bg-s.lp {
    padding: 28px 0 42px;
    position: relative;
    overflow: hidden;
  }

  .booking-bg-s.lp::before,
  .booking-bg-s.lp::after {
    content: "";
    position: absolute;
    border-radius: 999px;
    opacity: 0.25;
    pointer-events: none;
  }

  .booking-bg-s.lp::before {
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, #ffbf69 0%, rgba(255, 191, 105, 0) 70%);
    top: -60px;
    left: -90px;
  }

  .booking-bg-s.lp::after {
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, #4ea8de 0%, rgba(78, 168, 222, 0) 72%);
    bottom: -120px;
    right: -120px;
  }

  .booking-bg-1 {
    position: relative;
    z-index: 1;
  }

  .bg-book {
    max-width: 980px;
    margin: 0 auto;
    padding: 20px 28px 28px !important;
    border-radius: 22px;
    background: linear-gradient(145deg, var(--reg-bg-start), var(--reg-bg-end));
    box-shadow: var(--reg-shadow);
    border: 1px solid rgba(255, 255, 255, 0.72);
    animation: regFadeIn 0.7s ease;
  }

  .spe-title-wid {
    margin-bottom: 18px;
  }

  .spe-title-wid h2 {
    margin: 0;
  }

  .spe-title-wid h2:first-child {
    font-family: 'Sora', sans-serif;
    color: var(--reg-heading);
    letter-spacing: 0.5px;
    font-size: 30px !important;
    line-height: 1.2;
  }

  .spe-title-wid h2:last-child {
    font-family: 'Outfit', sans-serif;
    color: var(--reg-accent-dark);
    margin-top: 6px;
    font-size: 15px !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
  }

  .book-form {
    margin-top: 8px;
    background: var(--reg-surface);
    border-radius: 16px;
    padding: 20px 18px 16px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
  }

  .row[align="center"] strong {
    display: inline-block;
    margin: 6px 0 16px;
    padding: 8px 14px;
    border-radius: 999px;
    font-family: 'Outfit', sans-serif;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.78);
    color: #0f172a;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.07);
  }

  .card-header {
    background: transparent;
    border: 0;
    padding: 0;
    margin: 14px 0 12px;
  }

  .card-header h3 {
    font-family: 'Sora', sans-serif;
    color: var(--reg-heading);
    font-size: 18px !important;
    border-left: 5px solid var(--reg-accent);
    padding-left: 12px;
    margin: 0;
  }

  #commentForm {
    font-family: 'Outfit', sans-serif;
    color: var(--reg-text);
  }

  #commentForm .form-group {
    margin-bottom: 16px;
  }

  #commentForm label {
    color: var(--reg-heading);
    font-weight: 600;
    margin-bottom: 6px;
    display: inline-block;
  }

  .form-control {
    color: #0f172a !important;
    background: #ffffff;
    border: 1px solid var(--reg-border);
    border-radius: 10px;
    min-height: 44px;
    padding: 10px 12px;
    box-shadow: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
  }

  .form-control:focus {
    border-color: var(--reg-accent);
    box-shadow: 0 0 0 4px var(--reg-focus);
    transform: translateY(-1px);
  }

  input[type="file"].form-control {
    width: 100% !important;
    padding: 8px 12px;
    min-height: 46px;
    background: #ffffff;
  }

  .btn.btn-primary {
    background: linear-gradient(135deg, var(--reg-accent), #f48c06);
    border-color: transparent;
    border-radius: 12px;
    min-width: 170px;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 11px 22px;
    transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
  }

  .btn.btn-primary:hover,
  .btn.btn-primary:focus {
    filter: brightness(0.95);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(217, 95, 2, 0.28);
  }

  #commentForm .form-group {
    animation: regSlideUp 0.45s ease both;
  }

  #commentForm .form-group:nth-of-type(2) { animation-delay: 0.03s; }
  #commentForm .form-group:nth-of-type(3) { animation-delay: 0.06s; }
  #commentForm .form-group:nth-of-type(4) { animation-delay: 0.09s; }
  #commentForm .form-group:nth-of-type(5) { animation-delay: 0.12s; }

  .event-date-box {
    min-height: 44px;
    border: 1px dashed #f6b26b;
    background: linear-gradient(135deg, #fff8ec, #ffffff);
    border-radius: 10px;
    padding: 10px 12px;
    line-height: 1.2;
  }

  .event-date-box .event-date-label {
    display: block;
    font-size: 12px;
    text-transform: uppercase;
    color: var(--reg-muted);
    letter-spacing: 0.6px;
    margin-bottom: 4px;
  }

  .event-date-box .event-date-value {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: var(--reg-heading);
  }

  @keyframes regFadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes regSlideUp {
    from { opacity: 0; transform: translateY(7px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 991px) {
    .bg-book {
      padding: 16px 14px 20px !important;
      border-radius: 16px;
    }

    .book-form {
      padding: 14px 12px 10px;
    }

    .spe-title-wid h2:first-child {
      font-size: 24px !important;
    }
  }

  @media (max-width: 640px) {
    .spe-title-wid h2:first-child {
      font-size: 20px !important;
    }

    .spe-title-wid h2:last-child {
      font-size: 12px !important;
    }

    .btn.btn-primary {
      width: 100%;
      min-width: 100%;
    }

    .col-sm-10 {
      width: 100%;
    }
  }
</style>
<section>
  <div class="booking-bg-s lp">
    <div class="booking-bg-1">
      <div class="bg-book" style="padding-top: 10px !important;">
        <div class="text-center spe-title-wid">
          <h2 style="font-size: 20px !important;">Gymnasts Association of Karnataka </h2>
          <h2 style="font-size: 10px !important;"><?php echo $eventname; ?> </h2>
        </div>
        <div class="row" align="center"> <strong><?php echo $msg; ?></strong></div>
        <div class="book-form">
          <form class="form-horizontal" id="commentForm" action="index.php" method="post" enctype="multipart/form-data">  
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
            
            </div>
            <div class="form-group ">
              <label>Accompanying the Gymnast:</label>
              <input type="text" name="parent" class="form-control" id="parent" minlength="2" maxlength="100" onkeypress="return (event.charCode === 32) || (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" required>
            </div>
            <div class="form-group ">
              <label>Photo Upload:</label>
              <input type="file" class="form-control" name="photo"/>
            </div>
            <div class="form-group ">
              <label>Id Proof:</label>
              <input type="file" class="form-control" name="idproof"/>
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