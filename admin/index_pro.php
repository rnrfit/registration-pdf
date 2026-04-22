<?php
require_once('classes/constants.php');

$db = new Database();
$user = new User($db); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ( !empty($_POST)){

	$_POST = $db->sanitizeArray($_POST);
	$panel = 0;
	if(isset($_POST['panel']) && !is_array($_POST['panel']) && intval($_POST['panel']) >=1 && intval($_POST['panel'])<=10)
	{
		$panel = intval($_POST['panel']);
	}
	else
	{
		//header
	}
	if(!$db->validateFormHash('LOGIN', $_POST['salthash']))
	{
		$error[] = 'Invalid server request found';
		echo "Invalid server request found";
		exit();
	}

	$username = $_POST['username'];
	$password = $_POST['password'];

	$type = "";
	if($user->login($username,$password)){ 	

			$_SESSION['username']=$username;
			
			if($user->usertype($username,$type) == 'superadmin')
			{

				$_SESSION['id'] = $user->usertype_1($username,$type);	
				$_SESSION["type"] = "superadmin";
				$_SESSION["panel"] = '';

				header("location:GymnastList.php");	
				//header("location:events.php");	
				exit();		
			}
			if($user->usertype($username,$type) == 'admin')
			{		
				//print_r($user->usertype($username,$type))	;die;
				$_SESSION['id'] = $user->usertype_1($username,$type);

				$_SESSION["type"] = "admin";
				$_SESSION["panel"] = $panel;
				//print_r($_SESSION)	;die;			
				header("location:GymnastList.php");	
				exit();
				die;
			}	
			
		exit;	
	} else {
		$error[] = 'Wrong Username or Password.';
		echo "Wrong Username or Password";
	}

}//!end if submit
else
{
	echo "not post";
}
//define page title
$title = 'IndianGymnastics';
?>


?>