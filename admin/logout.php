<?php
session_start() ;
session_destroy();
$_SESSION = array();
echo "   <script type=\"text/javascript\"> window.location=\"index.php\";</script> "; 
?>