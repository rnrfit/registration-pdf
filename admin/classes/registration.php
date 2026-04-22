<?php
class Registration{
	private $_db;

    function __construct(){
    	$db = new Database(); 
    	$this->_db = $db->datacon; 
    }
    //events.php
    //get category of an event
    public function get_eventcategory($eventid){	
	try {			
			$sql="SELECT category FROM `eventcategory` WHERE `eventid` ='".$eventid."'";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {		    
		}
	}

	public function get_association(){	
	try {
			$stmt = $this->_db->prepare('SELECT * FROM `organisation`');
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_participants_List(){	
	try {
			$sql = "SELECT  `id`,`name`, `lastname`, `fathername`, `district`, `eventgroup`, `eventcategory`, `dob`, `aadhaar`, `Gender`, `policyno`, `policycompany`, `role`, `height`, `weight`, `bloodgroup`, `costumesize`, `tshirtsize`, `tracksuit`, `shoessize`, `address`, `email`, `phone`, `school`, `grade`, `Bankname`, `Branch`, `accountno`, `ifsccode`, `parent`, `Event`, `orderid`, `photo`, `idproof`, `City`, `state`  FROM `aer` ORDER BY name";
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