<?php
class Participant
{
	private $_db;

    function __construct(){
    	$db = new Database(); 
    	$this->_db = $db->datacon; 
    }

    public function get_state(){	
	try {	
			$sql="SELECT * FROM `states`";	
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

    //register.php
	public function participantadd($name, $City,$state,$gender, $dob, $eventid, $club,$level ,$event,$category,$email, $phone,$regdate,$contingent,$aadhaar,$orderid){	
	try {
			$sql = "INSERT INTO `registrations`(`name`, `City`,`Gender`, `dob`, `Event`, `club`,state,level, `eventtype`,rcategory,`email`, `phone`, `regdate`,contingent,aadhaar,orderid) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array( $name,$City, $gender, $dob, $eventid, $club,$state,$level, $event,$category,$email, $phone,$regdate,$contingent,$aadhaar,$orderid));    
			 return $this->_db->lastInsertId();       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function apparatusadd( $eventid,$apid,$reg_id, $gender ){	
	try {
			$sql = "INSERT INTO `gymnast_apparatus`(`eventid`, `apid`, `reg_id`, `gender`) VALUES (?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array(  $eventid,$apid,$reg_id, $gender));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_participant_apparatus($regid){	
	try {
			$sql="SELECT * FROM `gymnast_apparatus` ga INNER JOIN apparatus ap on ga.apid=ap.apid WHERE `reg_id` ='$regid' ";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function updatefees($datepaid,$amount,$reg_id){	
	try {
			$sql = "UPDATE `registrations` SET `datepaid`=?,`amount`=? where `id`=?";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($datepaid,$amount,$reg_id));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_participant_apparatus($reg_id){	
	try {	
			$sql="SELECT * FROM `gymnast_apparatus` WHERE `reg_id` = '$reg_id' ";	
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_participant_byorderid($orderid){	
	try {	
			$sql="SELECT * FROM `registrations` WHERE `orderid` = '$orderid' ";	

			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;

		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function participantfeesadd($regid, $eventid,$orderid,$txnid,$fees, $amountpaid,$paymentdate ,$paymentmode,$remarks)
	{	
		try 
		{
			$sql = "INSERT INTO `gymnast_fees`(`regid`, `eventid`, `orderid`, `txnid`, `fees`, `amountpaid`, `paymentdate`, `paymentmode`, `remarks`) VALUES (?,?,?,?,?,?,?,?,?)";
	        $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($regid, $eventid,$orderid,$txnid,$fees, $amountpaid,$paymentdate ,$paymentmode,$remarks));   
			    
		} 
		catch(PDOException $e) {
			    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function get_participant_byname($name,$eventid){	
	try {	
			$sql="SELECT * FROM `registrations` WHERE `name` LIKE '$name' and Event='$eventid' and amount !=0";	
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function Finalclub($eventid,$gender,$category){	
	try {	
			$sql="SELECT distinct club  FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."' and qr Is not null ";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//results_profile
	public function get_participant_byid($regid){	
	try {	
			$sql="SELECT * FROM `registrations` WHERE `id` = $regid ";	
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;

		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function get_apparatusbyid($apid){	
	try {
		$sql="SELECT * FROM `apparatus` WHERE `apid` = $apid";
		
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//---------IGL DATA----------------------------------------------------------------------------
	public function get_participant_igl($eventid,$regid){	
	try {	
			$sql="SELECT * FROM `registrations` WHERE `id` = $regid AND `Event` LIKE '$eventid'";	
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;

		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_participant_iglscore($eventid,$regid,$apid){	
	try {	
			$sql="SELECT * FROM igl_score ed WHERE ed.eventid=$eventid and reg_id=$regid and apid=$apid ";
			//print_r($sql);
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_team($teamname){	
	try {	
			$sql="SELECT * FROM `team` WHERE `teamname` LIKE '$teamname'";	
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function getclub_bytable($eventid,$gender,$tblname,$category){	
	try {	
			$sql="SELECT distinct club  FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."'  ";	//and qr Is not null
			//print_r($sql);
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function artistic_generatequalifier($eventid,$gender,$tblname,$category,$apid){	
	try {	
			$sql="SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,club FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."' and apid='".$apid."' order BY total desc,subtotal desc,dscore DESC,penalty DESC";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function artistic_getscorebyclub($eventid,$gender,$tblname,$category,$apid,$club){	
	try {	
			$sql="SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,club FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and club='$club' and category='".$category."' and apid='".$apid."' order BY total desc,subtotal desc,dscore DESC,penalty DESC";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function participant_score($regid){	
	try {	
			$sql="SELECT r.name,r.Gender,r.club,r.level,r.eventtype,r.rcategory,gs.total FROM registrations r INNER JOIN `gymnast_score` gs on r.id=gs.reg_id WHERE gs.`reg_id` = '$regid'";	//and qr Is not null
			//print_r($sql);
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
}
?>