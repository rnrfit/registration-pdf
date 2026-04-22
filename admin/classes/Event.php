<?php
class Event {
	private $_db;

    function __construct(){    	

    	$db = new Database(); 
    	$this->_db = $db->datacon;  	
    }

    public function get_events(){	
	try {
		$sql='SELECT * FROM `events` where parentid !=0 order by Id desc';
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());	
			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function get_event($eventid){	
	try {
			$stmt = $this->_db->prepare("SELECT *  FROM events WHERE Id =$eventid");
			$stmt->execute(array());
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function get_eventafterid($eventid){	
	try {		
			$stmt = $this->_db->prepare("SELECT *  FROM events WHERE Id >$eventid and parentid !=0 order by Id desc");
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
	//EventList.php
	public function get_events_parent(){	
	try {
			$stmt = $this->_db->prepare('SELECT e1.*,e2.name as parentname FROM `events` e1 left JOIN events e2 on e1.parentid=e2.Id order by e1.id desc');
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//EventAdd.php
	public function get_mainevents(){	
	try {
			$stmt = $this->_db->prepare('SELECT * FROM `events` where parentid =0 order by Id desc');
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_event_name($eventid){	
	try {
			$stmt = $this->_db->prepare("SELECT Name  FROM events WHERE Id =$eventid");
			$stmt->execute(array());
			$eventname = $stmt->fetchColumn();
			return $eventname;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}


	public function get_cluboflatestevent($eventid){	
	try {		
		//$sql = "SELECT DISTINCT club from registrations where EVENT =(SELECT Id FROM `events`  order by Id DESC limit 1)"; 
		$sql = "SELECT DISTINCT club from registrations where EVENT ='$eventid' order by club";   
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

//s_admin_printscoring
	public function get_participantsbyclub($eventid,$g,$Category,$club){	
	try {
		$sql="SELECT r.id,gender Gender,Category, Event,dob, r.name,r.eventtype,club, `email`, r.phone,amount FROM registrations r left join events e on e.Id=Event where Event='".$eventid."' and gender='".$g."' and eventtype='".$Category."' and club ='$club' order by name,club";
		
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function get_participantsbyclublevel($eventid,$g,$Category,$level,$club){	
	try {
		$sql="SELECT r.id,gender Gender,Category, Event,dob, r.name,r.eventtype,club, `email`, r.phone,amount FROM registrations r left join events e on e.Id=Event where Event='".$eventid."' and gender='".$g."' and eventtype='".$Category."' and club ='$club' and r.level='".$level."' order by name,club";
		
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	
	//s_admin_signsheetgroup
	public function get_signsheetgroup($eventid,$groupid){	
	try {		
		$sql = "SELECT r.Gender,dob,club,amount,eventtype,r.name FROM registrations r INNER JOIN panel_gymnast p on r.id=p.reg_id where r.event='".$eventid."' and groupid='".$groupid."'  order by club,name";   
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_signsheetapparatus($eventid,$apid,$gender){	
	try {		
		$sql = "SELECT id as reg_id, r.name as participant_name,r.`Gender`, `Event`, club  FROM `registrations`  r INNER JOIN `gymnast_apparatus` g on r.id=g.reg_id where eventid='".$eventid."' and apid='".$apid."' and r.gender= '".$gender."'";   
		print_r($sql);
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//s_admin_printscoring
	public function get_eventOn($eventid,$g,$Category,$level){	
	try {

		if($level == "")
		{
			$sql="SELECT r.id,gender Gender, Event,dob, r.name,r.eventtype,club, `email`, r.phone,amount FROM registrations r left join events e on e.Id=Event where Event='".$eventid."' and gender='".$g."' and eventtype='".$Category."' order by name,club";
		}
		else
		{
			$sql="SELECT r.id,gender Gender, Event,dob, r.name,r.eventtype,club, `email`, r.phone,amount FROM registrations r left join events e on e.Id=Event where Event='".$eventid."' and r.level='".$level."' and gender='".$g."' and eventtype='".$Category."' order by name,club";
		}
		
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//check if gymnast is alredy assigned to panel
	//assign_gymnastto_panel.php
	public function get_panel_gymnasts($regid){	
	try {
		$sqlcat = "SELECT * FROM `panel_gymnast` WHERE `reg_id` = $regid";
			$stmt = $this->_db->prepare($sqlcat);
			$stmt->execute(array());
			$eventname = $stmt->fetchColumn();
			return $eventname;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//creategroup_appwise.php
	public function creategroupbasedonapp($eventid,$apid,$reg_id){	
	try {
			$sql = "INSERT INTO `gymnast_apparatus`(`eventid`, `apid`, `reg_id`) VALUES (?,?,?)";
            $stmt = $this->_db->prepare($sql);

			$stmt->execute(array($eventid,$apid,$reg_id));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function Creategroup($eventid,$cat,$regid,$groupid){	
	try {
		
			$sql = "INSERT INTO panel_gymnast(`eventid`, cat,`reg_id`, groupid) values(?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($eventid,$cat,$regid,$groupid));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//creategroup.php
	public function get_eventcategory($eventid){	
	try {
			$sqlcat = "SELECT *  FROM `eventcategory` WHERE `eventid`=$eventid";			
			$stmt = $this->_db->prepare($sqlcat);
			$stmt->execute(array());
			$row = $stmt->fetchAll(); 
			return $row;      
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//assign_gymnastto_panel_app.php
	public function set_panelapp_gymnast($eventid,$apid,$groupid,$panel){	
	try {
			$sqlcat = "SELECT * FROM `panel_gymnast` WHERE `eventid`=$eventid and `groupid`='$groupid' limit 1";
			
			$stmt = $this->_db->prepare($sqlcat);
			$stmt->execute(array());
			$row = $stmt->fetchAll();
			$paneln=$row[0]['r1'];
			$rotation=$row[0]['r2'];
			$comm=',';
			if($paneln=='')
				$comm='';
			$r1=$paneln.$comm.$panel;
			$r2=$rotation.$comm.$apid;
			$sql = "UPDATE panel_gymnast SET `panel`=?,apid=?,Isactive=0,r1=?,r2=? where `eventid`=? and `groupid`=?";
			
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($panel,$apid,$r1,$r2,$eventid,$groupid));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function set_panel_gymnast($eventid,$regid,$panel){	
	try {
			$sql = "UPDATE panel_gymnast SET `panel`=? where `eventid`=? and `reg_id`=?";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($panel,$eventid,$regid));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}


	public function remove_panel_gymnast($eventid,$regid){	
	try {
			$sql = "DELETE FROM  panel_gymnast where `eventid`=? and `reg_id`=?";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($eventid,$regid));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

//assign2_ajax.php
	public function set_gymnast_app($panel,$cat,$eventid,$regid){	
	try {
			$sql = "UPDATE panel_gymnast SET Isactive=0,`cat`='' where `panel`=?";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($panel));    

			$sql = "UPDATE panel_gymnast SET Isactive=1,`cat`=? where `eventid`=? and `reg_id`=?";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($cat,$eventid,$regid));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//get last added event id
	public function lasteventid(){	
	try {
			$eventid = $this->_db->query("SELECT Id FROM events ORDER BY Id DESC LIMIT 1")->fetchColumn();   
			return  $eventid;      
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//Eventadd.php
	public function eventadd($Name,$eventid,$address, $city,$Description,$EventDate,$EndDate,$isdemo,$cnt_qualifier,$cnt_teamchamp,$teams_qualifier,$Type ,$Category,$registerurl){	
	try {
			$sql = "INSERT INTO `events`(`Name`, parentid, `address`, `city`,`Description`,`EventDate`,EndDate,isdemo, `cnt_qualifier`, `cnt_teamchamp`, `teams_qualifier`,`Type`, `Category`, `registerurl`) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $q = $this->_db->prepare($sql);
            $q->execute(array($Name,$eventid, $address, $city,$Description,$EventDate,$EndDate,$isdemo,$cnt_qualifier,$cnt_teamchamp,$teams_qualifier,$Type ,$Category,$registerurl));         
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//Eventadd.php
	public function eventcategoryadd($eventid,$category){	
	try {
			$sql = "INSERT INTO `eventcategory`(`eventid`, `category`) 
                    VALUES (?,?)";
            $q = $this->_db->prepare($sql);
            $q->execute(array($eventid,$category));         
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//add logo to event
	public function eventlogoupdate($logo1,$logo2,$rptheader,$eventid){	
	try {
			 $sql = "UPDATE `events` SET logo1=?,logo2=?,rptheader=? where`Id`=?";               
                $q = $this->_db->prepare($sql);
                $q->execute(array($logo1,$logo2,$rptheader,$eventid));     
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function eventupdate($Name,$Description,$EventDate,$EndDate,$id){	
	try {
			$sql = "UPDATE `events` SET `Name`=?,`Description`=?,`EventDate`=?,EndDate=? where`Id`=?";               
            $q = $this->_db->prepare($sql);
            $q->execute(array($Name,$Description,$EventDate,$EndDate,$id));        
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}


		
}
?>