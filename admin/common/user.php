<?php
include('password.php');
class User extends Password{

    private $_db;
    private $visitcount=0;
     public $reg=0;
    function __construct($db){
    	parent::__construct();
    
    	$this->_db = $db;
    }

	private function get_user_hash($username){
		try 
		{
			$username = md5($username);
			//compare using md5
			//md5(username)=md5(username)
			$stmt ="SELECT password FROM users WHERE MD5(username) ='$username' AND active='Yes'";			
			$records =$this->_db->executeQuery($stmt);			
			return $records[0]['password'];
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

public function reset_pass($username,$hashedpassword){	

		try {				
			$stmt = "SELECT * FROM `users` where role='3' and username= '$username'";
			$records =$this->_db->executeQuery($stmt);	
			$pwd=$records[0]['username'];
			if($pwd!='')
			{
				$sql="UPDATE users SET password ='$hashedpassword' WHERE role='3' and username ='$username'";
				$records =$this->_db->executeQuery($stmt);				
				return 1;
			}
		else
			return 0;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}


public function get_pass($username){	

		try {
			$stmt = $this->_db->prepare('SELECT password FROM users WHERE username = :username AND active="Yes" ');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			
			return password_get_info($row['password']);

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

public function get_userid($username){	

		try {
			$stmt = $this->_db->prepare('SELECT password FROM users WHERE username = :username AND active="Yes" ');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			
			return password_get_info($row['password']);

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function login($username,$password){
		$hashed = $this->get_user_hash($username);
		
		if($this->password_verify($password,$hashed) == 1){
		    
		    $_SESSION['loggedin'] = true;
		    return true;
		}
		else
		{
			return false;
		}
	}

	public function usertype($username,$type){
			try {
			$stmt = "SELECT role FROM users WHERE username = '$username' ";
			$records =$this->_db->executeQuery($stmt);
			return $records[0]['role'];

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function usertype_1($username,$type){
			try {
			$stmt = "SELECT * FROM users WHERE username = '$username' ";
			$records =$this->_db->executeQuery($stmt);	
		
			$_SESSION['eventid'] = $records[0]['Eventid'];			
			return $records[0]['id'];
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

		
	public function logout(){
		session_destroy();
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}		
	}
	
}


?>