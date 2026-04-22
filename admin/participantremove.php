<?php
include('classes/constants.php');
include('classes/common.php');
$comm = new Common();
if (!empty($_REQUEST)) 
{   
   
    $id = $_GET['id'];
    $eventid = $_GET['eventid'];
	
	
	$sql="Delete FROM `registrations` WHERE `id` = $id";
	$comm ->insertupdate($sql);
header("location:GymnastList.php?eventid=$eventid");	
				exit();	
	
}
?>