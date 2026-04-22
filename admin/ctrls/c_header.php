<?php 
include('classes/constants.php');
include('classes/common.php');
$comm = new Common();

if (!isset($_SESSION['type']) && empty($_SESSION['type'])) {
    echo "   <script type=\"text/javascript\"> window.location=\"index.php\";</script> ";
}
$Panel=0;
$role = '';
$eventid   =   '';
$category  =   '';
$gender   =   '';  
if ($_SESSION["type"] == "superadmin") {
    $role = 'superadmin';
    $Panel='';
}
else {
  $Panel=$_SESSION["panel"];
}
if ( !empty($_REQUEST['eventid']))
 $eventid   =    $_REQUEST['eventid'];

if ( !empty($_REQUEST['Category'])){            
   
    $category  =    $_REQUEST['Category'];
    $gender   =    $_REQUEST['gender'];        
}

$condition = array('AppName' => 'logo1');
$records=$comm->getRows('appconfig',$condition);
$condition = array('AppName' => 'logo2');
$logo2=$comm->getRows('appconfig',$condition);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gymnastics:Results,Live Results,National</title>
    <!-- META TAGS -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- FAV ICON(BROWSER TAB ICON) -->
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700%7CMontserrat:300,500%7COswald:400,500" rel="stylesheet">

    <!-- FONTAWESOME ICONS -->
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

    <!-- ALL CSS FILES -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">

    <!-- MOB.CSS ONLY FOR MOBILE AND TABLET VIEWS -->
    <link rel="stylesheet" href="../assets/css/mob.css">
<script type="text/javascript" src="<?php echo $url ?>assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $url ?>assets/js/bootstrap.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="../assets/js/html5shiv.js"></script>
  <script src="../assets/js/respond.min.js"></script>
  <![endif]-->
</head>
<body>

    <!--SECTION: LEFT MENU-->
    <section>
    <div class="mob-menu">
            <span><i class="fa fa-bars" aria-hidden="true"></i></span>
        </div>
        <div class="mob-close">
            <span><i class="fa fa-times" aria-hidden="true"></i></span>
        </div>
    </section>
<!-- TOP BAR -PHPNE EMAIL AND TOP MENU -->
    <section class="i-head-top">
        <div class="i-head row">
            <!-- TOP CONTACT INFO -->
            <div class="i-head-left i-head-com col-md-6">
                <ul>
                    <li><a href="#"><?php echo ucwords($_SESSION["type"]); ?>(Panel <?php echo $Panel; ?>)</a>
                    </li>
                </ul>
            </div>
            <!-- TOP FIXED MENU -->
            <div class="i-head-right i-head-com col-md-6 col-sm-12 col-xs-12">
                <ul>
                <?php 
      if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "secretory" || $_SESSION["type"] == "admin") 
      {
              echo '<li class="nav-item">
                    <a class="nav-link text-white" href="associationlist.php">ASSN</a>
                </li>';
               
                  echo '<li class="nav-item">
                    <a class="nav-link text-white" href="GymnastList.php?eventid=44">Participants</a>
                </li>';
       
              
                
      }
      // echo '<li class="nav-item">
      //               <a class="nav-link text-white" href="reportlist.php">Reports</a>
      //               </li>';
      if ($_SESSION["type"] == "secretory") 
      { 
         // echo '<li class="nav-item">
         //            <a class="nav-link text-white" href="tramp_generatequalifier.php">Generate Qualifier</a>
         //            </li>';
      }
      if ($_SESSION["type"] == "superadmin") 
      {               
              // echo '<li class="nav-item dropdown">
              //     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              //      <i class="fa fa-cog" aria-hidden="true"></i>
              //     </a>
              //     <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              //       <a style="color: black !important;" href="eventlist.php">Add Event</a>
              //       <a style="color: black !important;" href="qualifierlist.php">Generate Qualifier</a>
              //       <a style="color: black !important;" href="gymnasttransfer.php">Add Gymnast</a>
              //       <a style="color: black !important;" href="verifycount.php">Verify Count</a>
              //       <a style="color: black !important;" href="penaltyadd.php">Add Penalty</a>
              //       <div class="dropdown-divider"></div>
              //        <a style="color: black !important;" class="alink" href="users.php">Users</a>
              //     </div>
              //   </li>';                   
        } 
?>  
<li class="nav-item" ><a class="nav-link text-white" href="logout.php"><i class="fa fa-power-off"></i></a></li>  
    </ul>
            </div>
        </div>
    </section>