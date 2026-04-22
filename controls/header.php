<?php 
include('inc/dbconfig.php');
include_once('inc/common.php');
include('inc/constants.php');
error_reporting(E_ALL); // Error/Exception engine, always use E_ALL

ini_set('display_errors', False); 

ini_set('log_errors', TRUE); // Error/Exception file logging engine.
ini_set('error_log', 'error_log'); // Logging file path
$comm = new Common();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Gymnastics:Live Results,National level Competition Results | Indiangymnastics.live</title>
    <!-- META TAGS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="Indiangymanstics.live is a web based scoring system for gymnastics.Fast, reliable, and easy-to-use,works on all modern devices without any installation." />
        <meta name="keywords" content="Competition Results,Live Results,National level events" />
        
        <meta property="og:title" content="Gymnastics:Competition Results,Live Results,National level events | Indiangymnastics.live" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="https://www.indiangymnastics.live/" />
        <meta property="og:image" content="https://www.indiangymnastics.live/assets/images/usaigc.png" />
        <meta property="og:description" content="Indiangymanstics.live is a web based scoring system for gymnastics.Fast, reliable, and easy-to-use,works on all modern devices without any installation." />
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" content="Indiangymnastics" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:width" content="286" />
        <meta property="og:image:height" content="200" />


        <meta name="author" content="Indiangymnastics" />
        <meta name="rating" content="general" />
        <meta name="application-name" content="Indiangymnastics" />
        
    <!-- FAV ICON(BROWSER TAB ICON) -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700%7CMontserrat:300,500%7COswald:400,500" rel="stylesheet">

    <!-- FONTAWESOME ICONS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- ALL CSS FILES -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <!-- MOB.CSS ONLY FOR MOBILE AND TABLET VIEWS -->
    <link rel="stylesheet" href="assets/css/mob.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="assets/js/html5shiv.js"></script>
  <script src="assets/js/respond.min.js"></script>
  <![endif]-->
</head>

<body>
    <!-- Preloader -->
   <!--  <div id="preloader">
        <div id="status">&nbsp;</div>
    </div> -->

    <!--SECTION: LEFT MENU-->
    <section>
        <!-- LEFT SIDE NAVIGATION MENU -->
        <div class="menu">
            <ul>
                <!-- MAIN MENU -->
                <li>
                    <a href="/" class="menu-lef-act"><img src="assets/images/icon/s1.png" alt=""> Home</a>
                </li>
                <li>
                    <a href="bga_register.php">Bengaluru Gymnast Association</a>
                </li>
                <li>
                    <a href="aer_register.php">Gymnasts Association of Karnataka</a>
                </li>
                
            </ul>
        </div>

        <!-- RIGHT SIDE NAVIGATION MENU -->
        <!-- MOBILE MENU(This mobile menu show on 0px to 767px windows only) -->
        <div class="mob-menu">
            <span><i class="fa fa-bars" aria-hidden="true"></i></span>
        </div>
        <div class="mob-close">
            <span><i class="fa fa-times" aria-hidden="true"></i></span>
        </div>
    </section>

    <!-- TOP BAR -PHONE EMAIL AND TOP MENU -->
    <section class="i-head-top">
        <div class="i-head row">
            <!-- TOP CONTACT INFO -->
            <div class="i-head-left i-head-com col-md-6">
                <ul>
                    <li><a href="#">phone: +91 9632961700</a>
                    </li>
                    <li><a href="#">Email: info@rnrfit.com</a>
                    </li>
                </ul>
            </div>
            <!-- TOP FIXED MENU -->
            <div class="i-head-right i-head-com col-md-6 col-sm-12 col-xs-12">
                <ul>
                   <!--  <li class="top-scal"><a href="register.php"><i class="fa fa-ticket" aria-hidden="true"></i> Register | MUMBAI GAMES</a>
                    </li> -->
                    <!-- <li class="top-scal-1"><a href="tumbling_register.php"><i class="fa fa-registered" aria-hidden="true"></i> Register | Trampoline Championship</a>
                    </li> -->
                    <li><a href="#" class="tr-menu"><i class="fa fa-chevron-down" aria-hidden="true"></i> Browse</a>
                        <div class="cat-menu">

                            <div class="col-md-3 col-sm-6 cm1 mob-hid">
                                <h4>Gymnastics Branches</h4>
                                <ul>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> artistic</a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> rhythmic</a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> trampoline</a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> tumbling</a>
                                    </li>                                   
                                </ul>
                            </div>
                            <div class="col-md-3 col-sm-6 cm1 mob-hid">
                                <h4>Previous events</h4>
                                <ul>
                                 <li><a href="mumbaigames2artistic.php"><i class="fa fa-angle-right" aria-hidden="true"></i>2020: Mumbai Games Season 2 Artistic Gymnastics Championship</a>
                                    </li>
                                   <li><a href="mumbaigames2.php"><i class="fa fa-angle-right" aria-hidden="true"></i>2020: Mumbai Games Season 2 Trampoline Gymnastics Championship</a>
                                    </li>
                                    <li><a href="mumbaigames2tumbling.php"><i class="fa fa-angle-right" aria-hidden="true"></i>2020: Mumbai Games Season 2 Tumbling Gymnastics Championship</a>
                                    </li>                               
                                   
                                    
                                </ul>

                            </div>
                            <div class="col-md-6">
                                <h4>2020 Live Results</h4>
                                <div class="foot-pop top-me-ev">
                                    <ul>
                                        <li>
                                                <a href="vgim2020.php">
                                                    <img src="assets/images/trends/vgim.jpg" alt="">
                                                    <div class="foot-pop-eve top-me-text">
                                                        <span>Artistic: Nov 7th, 2020</span>
                                                        <h4>Bangalore:RnRFit VGIM 2020</h4>
                                                    </div>
                                                </a>
                                        </li>
                                        <li>
                                                <a href="rnrfit2020.php">
                                                    <img src="assets/images/trends/usaigc.jpg" alt="">
                                                    <div class="foot-pop-eve top-me-text">
                                                        <span>Artistic: Feb 29th, 2020</span>
                                                        <h4>Bangalore:USAIGC/IAIGC ARTISTIC GYMNASTICS INVITATIONAL MEET 2020</h4>
                                                    </div>
                                                </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="cbb2-home-nav-bot mob-hid">
                                <ul>
                                    <li>One of the  Best Gymnastics Scoring Software <span>Call us on: +91 91080 01170</span>
                                    </li>
                                    <li><a href="ryp.php" class="cbb2-ani-btn-join"><i class="fa fa-user" aria-hidden="true"></i> Register</a>
                                    </li>
                                  <!--   <li>
                                        <a href="join-our-club.html" class="cbb2-ani-btn"><i class="fa fa-life-buoy" aria-hidden="true"></i> </a>
                                    </li> -->
                                </ul>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
    </section>