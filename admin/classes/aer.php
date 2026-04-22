<?php
class Aer{
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

	public function get_participants_List($eventid){	
	try {
			$sql = "SELECT  `id`, fname,`name`, `lastname`, `fathername`, `district`,`club`, `eventgroup`, `eventcategory`, `dob`, `aadhaar`, `Gender`, `policyno`, `policycompany`, `role`, `height`, `weight`, `bloodgroup`, `costumesize`, `tshirtsize`, `tracksuit`, `shoessize`, `address`, `email`, `phone`, `school`, `grade`, `Bankname`, `Branch`, `accountno`, `ifsccode`, `parent`, `Event`, `orderid`, `photo`, `idproof`, `City`, `state`  FROM `aer` where Event=".$eventid." ORDER BY lastname";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//used to get score on calculate.php
	public function get_participant_scorebyapid($eventid,$regid){	
	try {	
			$sql="SELECT ed.eventid,eventgroup,dscore,escore,de,e1,e2,e3,e4,a1,a2,total,cjp FROM aerobic_score ed WHERE ed.eventid=$eventid and reg_id=$regid ";	

			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function set_gymnastscore($eventid, $reg_id, $eventgroup, $eventcat, $place, $total, $dscore, $d1, $d2, $escore, $e1, $e2, $e3, $e4, $de, $a1, $a2, $a3, $a4, $ascore, $cjp, $diff, $subtotal){	
	try {
			$sql = "INSERT INTO `aerobic_score` (`eventid`, `reg_id`, `eventgroup`, `eventcat`, `pair`, `total`, `dscore`, `d1`, `d2`, `escore`, `e1`, `e2`, `e3`, `e4`, `de`, `a1`, `a2`, `a3`, `a4`, `ascore`, `cjp`, `diff`, `subtotal`) values(?, ?, ?,?,?, ?,?, ?,?,?,?,?,?,?,?,?,?,?, ?, ?, ?, ?,?)";
		    $q = $this->_db->prepare($sql);
		    $q->execute(array($eventid, $reg_id, $eventgroup, $eventcat, $place, $total, $dscore, $d1, $d2, $escore, $e1, $e2, $e3, $e4, $de, $a1, $a2, $a3, $a4, $ascore, $cjp, $diff, $subtotal));
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
}
?>