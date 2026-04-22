<?php
ob_start();
include 'controls/header.php';
include('inc/Participant.php');
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
$participant = new Participant();
$all_districts=$participant->all_district('17');
if (!empty($_GET)) {
 $id=$_GET['id'];
}
ob_end_flush();
?>
<style type="text/css">
    .form-control{
        color: #000000 !important;
    }
    .btn {
    width: 100%;
    color: #000;
    font-size: 14px;
    height: 42px;
    background: #ffc210;
    padding: 2px 4px;
    display: inline-block;
    font-size: 14px;
    text-transform: uppercase;
    border-radius: 2px;
    margin-top: 2px;
    font-weight: 600;
    border: 0px;
}
</style>
<section>
  <div class="booking-bg-s lp">
    <div class="booking-bg-1">
      <div class="bg-book" style="padding-top: 10px !important;">
        <div class="text-center spe-title-wid">
          <h2 style="font-size: 20px !important;">Bengaluru Gymnasts Association </h2>
          <h2 style="font-size: 10px !important;"> </h2>
        </div>
       
        <div class="book-form">
          <form class="form-horizontal" id="commentForm" action="aer_entryform.php" method="post" enctype="multipart/form-data">  
           <div class="card-header">
              <h3 style="font-size: 20px !important;">Download</h3>
            </div> 
             <div id="name" class="form-group ">
             
            </div>
            <div class="form-group row">
              <div class="col-lg-3">
                <a href="bga_1.php?id=<?php echo $id; ?>" class="btn" target="_blank">ENTRY FORM</a> &nbsp; 
              </div>
             
            </div>       
            <div class="card-header">
              <hr>
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