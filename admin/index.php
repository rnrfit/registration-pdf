<?php
require_once('classes/constants.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gymnastics</title>
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700%7CMontserrat:300,500%7COswald:400,500" rel="stylesheet">

    <!-- FONTAWESOME ICONS -->
    <link rel="stylesheet" href="<?php echo $url ?>assets/css/font-awesome.min.css">

    <!-- ALL CSS FILES -->
    <link rel="stylesheet" href="<?php echo $url ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $url ?>assets/css/bootstrap.css">

    <!-- MOB.CSS ONLY FOR MOBILE AND TABLET VIEWS -->
    <link rel="stylesheet" href="<?php echo $url ?>assets/css/mob.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-end" style="width:100% !important;">

            <section>
                <div class="col-md-3 col-sm-6">
                    <div class="p-jc-in">
                        <div class="p-jc-in-1">
                            <img src="<?php echo $url ?>assets/images/logo.jpg" />
                        </div>
                        <h3>Enter Credentials</h3>
                        <form role="form" method="post" action="index_pro.php" autocomplete="off">
                        <div class="p-jc-list">
                            <ul>
                                <li><input type="text" name="username" class="form-control" id="username" tabindex="1" required maxlength="5"></li>
                                <li><input type="password" name="password" class="form-control" id="password"
                                         maxlength="5" tabindex="2" required></li>
                                <input type="hidden" name="panel" id="panel" value="1">
                                <!-- <li>
                                    <div>
                                        <label>Select Panel:</label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="1" required />
                                            P1
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="2" required />
                                            P2
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="3" required />
                                            P3
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="4" required />
                                            P4
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="5" required />
                                            P5
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="6" required />
                                            P6
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="7" required />
                                            P7
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="8" required />
                                            P8
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="9" required />
                                            P9
                                        </label>
                                        <label>
                                            <input type="radio" name="panel" id="panel" value="10" required />
                                            P10
                                        </label>
                                    </div>
                                </li> -->
                            </ul>
                        </div>
                        <input type="hidden" name="salthash" value="<?php echo $db->setFormHash('LOGIN');?>">
                        <input type="submit" name="Login" value="Login" class="loginbutton">
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!--== Bootstrap & Latest JS ==-->
    <script type="text/javascript" src="<?php echo $url ?>assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $url ?>assets/js/bootstrap.js"></script>
    
</body>

</html>