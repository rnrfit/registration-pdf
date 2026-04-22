<?php
class Event {
	private $_db;

    function __construct(){    	

    	$db = new Database(); 
    	$this->_db = $db->datacon;  	
    }

    public function get_events(){	
	try {
			$stmt = $this->_db->prepare('SELECT * FROM `events` where parentid !=0 order by Id desc');
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function isExist($event_id){
		try {
			$stmt = $this->_db->prepare("SELECT * FROM `events` where id = '$event_id'");
			$stmt->execute(array());			
			return $stmt->rowCount() ? true : false;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function getteam($teamname){
		try {
			$stmt = $this->_db->prepare("SELECT * FROM `team` where teamname = '$teamname'");
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function getteamscore($teamname,$eventid){
		try {
			$stmt = $this->_db->prepare("SELECT name,photo,team,count(apid)performance,sum(total)total FROM `registrations` r INNER join igl_score s on r.id=s.reg_id where s.eventid='$eventid' and s.team='$teamname' group by r.id order by total desc");
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