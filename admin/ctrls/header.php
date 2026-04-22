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
$condition = array('AppName' => 'event');
$eventid=$comm->getRows('appconfig',$condition);
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
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700%7CMontserrat:300,500%7COswald:400,500" rel="stylesheet">

    <!-- FONTAWESOME ICONS -->
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

    <!-- ALL CSS FILES -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">

    <!-- MOB.CSS ONLY FOR MOBILE AND TABLET VIEWS -->
    <link rel="stylesheet" href="../assets/css/mob.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="../assets/js/html5shiv.js"></script>
  <script src="../assets/js/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div> 
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
                    <!-- <li><a href="#">phone: +01 6514 7415</a>
                    </li>
                    <li><a href="#">Email: sports2017@sports.com</a>
                    </li> -->
                </ul>
            </div>
            <!-- TOP FIXED MENU -->
            <div class="i-head-right i-head-com col-md-6 col-sm-12 col-xs-12">
                <ul>
                <?php 
     if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "secretory" || $_SESSION["type"] == "admin") {
            // echo'<li class="top-scal"><a href="dashboard.php"><i class="fa fa-ticket" aria-hidden="true"></i> Secretary</a>
            //         </li>
            //         <li class="top-scal-1"><a href="view.php?eventid='.$eventid[0]['AppValue'].'&panel='.$Panel.'"><i class="fa fa-registered" aria-hidden="true"></i>View</a>
            //         </li>';
                      }
            if ($_SESSION["type"] == "superadmin") {               
                //    echo '<li class="top-scal-1"><a href="event-register.html"><i class="fa fa-registered" aria-hidden="true"></i>Print</a>
                //     </li>
                //     <li><a href="#" class="tr-menu"><i class="fa fa-chevron-down" aria-hidden="true"></i> More</a>
                //         <div class="cat-menu">

                //             <div class="col-md-3 col-sm-6 cm1 mob-hid">
                //                 <h4>Features</h4>
                //                 <ul>
                //                     <li><a href="EventList.php"><i class="fa fa-angle-right" aria-hidden="true"></i> Add Event</a>
                //                     </li>
                //                     <li><a href="qualifierlist.php"><i class="fa fa-angle-right" aria-hidden="true"></i> Generate Qualifier</a>
                //                     </li>
                //                     <li><a href="gymnasttransfer.php"><i class="fa fa-angle-right" aria-hidden="true"></i> Add Gymnast</a>
                //                     </li>
                //                     <li><a href="verifycount.php"><i class="fa fa-angle-right" aria-hidden="true"></i> Verify Count</a>
                //                     </li>
                //                     <li><a href="penaltyadd.php"><i class="fa fa-angle-right" aria-hidden="true"></i> Add Penalty</a>
                //                     </li>
                //                 </ul>
                //             </div>
                //             <div class="cbb2-home-nav-bot mob-hid">
                //                 <ul>
                //                     <li> <a class="cbb2-ani-btn-join" href="users.php">Users</a>
                //                 </ul>
                //             </div>
                //         </div>

                //     </li>
                // </ul>';
              }
              if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "secretory" || $_SESSION["type"] == "admin") {
                 // echo'   <li class="top-scal"><a href="totalscore.php"><i class="fa fa-ticket" aria-hidden="true"></i> All</a>
                 //    </li>
                 //    <li class="top-scal-1"><a href="publish.php"><i class="fa fa-registered" aria-hidden="true"></i> Publish</a>
                 //    </li>';
                      }
                ?>
            </div>
        </div>
    </section>