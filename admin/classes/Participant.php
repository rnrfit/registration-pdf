<?php
class Participant{
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

	public function get_participantbyid($regid){	
	try {			
			$sql="SELECT * FROM `registrations` WHERE `id` = '$regid'";
			print_r($sql);
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {		    
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
	
	//events.php
	//get participant count of each category of that event
    public function get_participants_count($eventid){	
	try {
			//get category of this event
			$categories=$this->get_eventcategory($eventid);
			$sql="SELECT";//dynamic sql
			foreach ($categories as $row)
		    {
		    	$sql .=" count(case when eventtype = '".$row['category']."' then 1 end) as '".$row['category']."',";
		    }
		    $sql .=" (case when gender = 'M' then 'BOYS' else 'GIRLS' end )'gender' FROM `registrations` where Event='".$eventid."' group by gender";
		  
			//$sql="SELECT count(case when eventtype = 'Junior' then 1 end) as 'Junior',count(case when eventtype = 'Senior' then 1 end) as 'Senior' ,(case when gender = 'M' then 'MAG' else 'WAG' end )'gender'  FROM `registrations` where Event='".$eventid."' group by gender";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
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

	public function get_participants_List($eventid){	
	try {
			// $sql = "SELECT '' as SFid, eo.id as reg_id, eo.name as participant_name,'' as father_name,level, `Gender`, `Event`,rcategory,eo.City,`dob`, contingent, phone as phone_no, club,  email, `eventtype`, EventDate, `amount` as Total,aadhaar  FROM `registrations` eo left join events e on e.Id=Event where e.Id=".$eventid." ORDER BY participant_name";
		//SELECT '' as SFid, eo.id as reg_id, eo.name as participant_name,'' as father_name,GROUP_CONCAT(gname) appname,level, eo.`Gender`, `Event`,rcategory,eo.City,`dob`, phone as phone_no, club, email, `eventtype`, `amount` as Total,remarks,aadhaar FROM `registrations` eo LEFT JOIN gymnast_fees gf on eo.id=gf.regid INNER JOIN gymnast_apparatus ga on eo.id=ga.reg_id INNER join apparatus ap on ga.apid=ap.apid where Event='$eventid' GROUP by reg_id ORDER BY participant_name
			$sql="SELECT '' as SFid, eo.id as reg_id, eo.name as participant_name,'' as father_name,level, eo.`Gender`, `Event`,rcategory,eo.City,`dob`, phone as phone_no, club, email, `eventtype`, `amount` as Total,remarks,aadhaar FROM `registrations` eo LEFT JOIN gymnast_fees gf on eo.id=gf.regid where Event='44' GROUP by reg_id ORDER BY participant_name";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_participants_apparatus($regid){	
	try {
			$sql="SELECT eo.name as participant_name,'' as father_name,GROUP_CONCAT(gname) appname,level, eo.`Gender` FROM `registrations` eo LEFT JOIN gymnast_fees gf on eo.id=gf.regid INNER JOIN gymnast_apparatus ga on eo.id=ga.reg_id INNER join apparatus ap on ga.apid=ap.apid where eo.id='$regid' GROUP by reg_id ORDER BY participant_name";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_apparatus_Video($regid){	
	try {
			$sql="SELECT eo.name as participant_name,gname,level, eo.`Gender`,fileurl FROM `registrations` eo INNER JOIN gymnast_apparatus ga on eo.id=ga.reg_id INNER join apparatus ap on ga.apid=ap.apid where eo.id='$regid' ORDER BY participant_name";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_username($memberid){	
	try {
			$stmt = $this->_db->prepare('SELECT username FROM users WHERE memberid = :memberid');
			$stmt->execute(array('memberid' => $memberid));			
			$row = $stmt->fetch();			
			return $row['username'];
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_participant_name($memberid){	
	try {
		$sql="SELECT name,id FROM registrations where id = $memberid";
		
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetch();			
			return $row['name'];
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//used to assign participants to group
	//old
	public function get_participants_byevent_type($eventid){	
	try {	
			$sql = "SELECT id as rid,r.name,gender,city,club,eventtype FROM registrations r where id not in (SELECT reg_id FROM `panel_gymnast`) and r.event='$eventid' order by name,eventtype";			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//used to assign group to Panel
	public function get_participants_bygroup($eventid){	
	try {	
			$sql = "SELECT id as rid,r.name,gender,eventtype,p.groupid FROM registrations r INNER JOIN panel_gymnast p on r.id=p.reg_id and r.event='".$eventid."' order by name";			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//used to assign gymnasts to Judges for superadmin login
	public function get_participants_all($eventid,$apid){	
	try {				
		$sql = "SELECT id as rid,r.name,gender,eventtype,panel FROM registrations r INNER JOIN panel_gymnast p on r.id=p.reg_id where r.event='".$eventid."' and apid!='$apid'";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//used to assign gymnasts to Judges
	public function get_participants_bypanel($eventid,$panelid,$apid){	
	try {				
		$sql = "SELECT id as rid,r.name,gender,eventtype,panel FROM registrations r INNER JOIN panel_gymnast p on r.id=p.reg_id where r.event='".$eventid."' and `panel` = '$panelid' and apid!='$apid'";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//used to assign gymnasts to Judges
	public function get_gymnasts_bypanel($eventid,$panelid){	
	try {		

	$sql = "SELECT id ,r.name FROM registrations r INNER JOIN panel_gymnast p on r.id=p.reg_id and r.event='".$eventid."' AND `panel` = '$panelid' where Isactive=0 order by name";
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

	//used to assign gymnasts to create groups
	//creategroup.php
	public function get_gymnasts($eventid,$cat,$gender,$club){	
	try {		

		$sql = "SELECT id as rid,r.name,gender,city,club,eventtype FROM registrations r where id not in (SELECT reg_id FROM `panel_gymnast`) and r.event='".$eventid."' and club='".$club."' and gender='".$gender."' and eventtype='".$cat."' order by name,eventtype";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//secretary.php
	public function get_gymnast_bypanel($panel){	
	try {		

	$sql = "SELECT r.id,r.name,gname,cat,a.apid,ed.eventid,groupid FROM panel_gymnast ed INNER join registrations r on r.id = ed.reg_id INNER JOIN apparatus a on a.apid=ed.apid WHERE ed.panel=$panel limit 1";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_gymnasts_bygroup($eventid,$groupid){	
	try {		

	$sql = "SELECT id ,r.name FROM registrations r INNER JOIN panel_gymnast p on r.id=p.reg_id and r.event='".$eventid."' AND `groupid` = '$groupid' where Isactive=0 order by name";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//used to create groups of clubs
	//creategroup.php
	public function get_clubs_byevent($eventid,$gender,$category){	
	try {	
			$sql = "SELECT club,gender,eventtype,count(id)ct FROM registrations r where id not in  (SELECT reg_id FROM `panel_gymnast`) and r.event='$eventid' and gender ='$gender' and eventtype='$category' group by club";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}


	//used to assign group to Panel //assign_gymnastto_panel_app.php
	public function get_group($eventid){	
	try {	
			$sql = "SELECt DISTINCT groupid,panel,p.apid,gname,r1,r2 FROM panel_gymnast p left JOIN apparatus a on a.apid=p.apid where eventid='".$eventid."'";			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}


	//used to get score on preconfirm.php
	public function get_participant_score($eventid,$regid){	
	try {	
			$sql = "SELECT reg_id,total,category,apid,s.eventid,dscore,escore,de,e1,e2,e3,e4,e5,e6,total,avg,penalty FROM `gymnast_score` s where avg is null and eventid='".$eventid."' and reg_id='".$regid."'";			
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
	public function get_participant_scorebyapid($eventid,$regid,$apid){	
	try {	
			$sql="SELECT ed.eventid,category,dscore,escore,de,e1,e2,e3,e4,e5,e6,panel,total,penalty FROM gymnast_score ed WHERE ed.eventid=$eventid and reg_id=$regid and apid=$apid ";	
//echo $sql;
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function get_participant_scorebyigl($eventid,$regid,$apid){	
	try {	
			$sql="SELECT * FROM igl_score ed WHERE ed.eventid=$eventid and reg_id=$regid and apid=$apid ";	
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

public function qualifierclub($eventid,$gender,$apid,$category){	
	try {	
			$sql="SELECT distinct club  FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and r.gender='".$gender."' and s.apid=$apid and category='".$category."' and qr Is not null ";	
			
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
	//QualifierMag.php
public function qualifierrpt($eventid,$gender,$apid,$category){	
	try {	
			$sql="SELECT r.id, name,r.gender,gname,club,s.total,subtotal,rank FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and r.gender='".$gender."' and s.apid=$apid and category='".$category."' and qr Is not null ORDER BY total desc,subtotal DESC";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//qualifierlist.php
public function generatequalifier($eventid,$gender,$apid,$category){	
	try {	
			$sql="SELECT r.id, name,r.gender,gname,club,s.total,subtotal,rank,penalty,dscore,category FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and r.gender='".$gender."' and s.apid=$apid and category='".$category."' ORDER BY total desc,subtotal DESC";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function qualifiervault($eventid,$gender,$apid,$category){	
	try {	
			$sql="SELECT s.reg_id,name,round((s.total+g.total)/2,2) avg1,s.total vault1,g.total vault2,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid
inner join (select * from gymnast_score g where eventid='".$eventid."' and apid='17' and category='".$category."') g on g.reg_id=s.reg_id where s.eventid='".$eventid."' and a.apid='6' and r.gender='".$gender."' and s.category='".$category."' ORDER BY avg1 DESC,vault1 desc,vault2 desc";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//qualifierlist.php
	public function getvaultscore($eventid,$gender,$category,$club){	
	try {	
			$sql="SELECT r.id,s.reg_id,name,round((s.total+g.total)/2,2) avg1,s.total vault1,g.total vault2,club,s.category FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid
inner join (select * from gymnast_score g where eventid='$eventid'  and apid='17' and category='".$category."') g on g.reg_id=s.reg_id where s.eventid='$eventid' and club='".$club."' and a.apid='6' and r.gender='$gender' and s.category='".$category."' ORDER BY avg1 DESC,vault1 desc,vault2 desc";	
			
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
	public function get_qualifierrpt($eventid,$gender,$apid,$category){	
	try {	
			$sql="select a.* from (SELECT r.id, name,r.gender,gname,club,s.* FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and r.gender='".$gender."' and s.apid=$apid and category='".$category."' and qr !='') as a LEFT JOIN (SELECT r.id, name,r.gender,club,s.* FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and s.apid=$apid and category='".$category."' and qr Is not null) AS a2 ON a.club = a2.club AND a.total <= a2.total GROUP BY a.name HAVING COUNT(*) <= 2 ORDER BY a.total  DESC ";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//

public function get_startorder($eventid,$gender,$apid,$category){	
	try {	
			$sql="SELECT r.id, name,r.gender,gname,club,s.startorder,qr FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and r.gender='".$gender."' and s.apid=$apid and category='".$category."' and qr !='' order by s.startorder ";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

public function get_rankforqualifier($eventid,$gender,$apid,$category){	
	try {	
			$sql="SELECT r.id, name,r.gender,gname,total,club,s.startorder,qr,rank FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and r.gender='".$gender."' and s.apid=$apid and category='".$category."' and qr !='' order by rank ";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

public function get_participant_gender($memberid){	
	try {
			$stmt = $this->_db->prepare('SELECT gender,id FROM registrations where id = :memberid');
			$stmt->execute(array('memberid' => $memberid));			
			$row = $stmt->fetch();			
			return $row['gender'];
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function get_ap_name($memberid){	
	try {
			$stmt = $this->_db->prepare('SELECT apid,gname FROM `apparatus` where apid= :memberid');
			$stmt->execute(array('memberid' => $memberid));			
			$row = $stmt->fetch();			
			return $row['gname'];
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function get_participantforconfirmation($eventid){	
	try {
			$stmt = $this->_db->prepare("SELECT r.id,r.name,club,gname,total,category,a.apid,s.eventid,dscore,escore,de,e1,e2,e3,e4,e5, total,avg FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where avg is null and eventid=$eventid order by name");
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//------------UPDATE
	public function update_gymnastscore($dscore,$escore,$de,$e1,$e2,$e3,$e4,$e5,$e6,$eventid,$regid,$apid,$penalty){	
	try {
			 $sql = "UPDATE gymnast_score SET dscore=?,escore=?,de=?,e1=?,e2=?,e3=?,e4=?,e5=?,penalty=? WHERE eventid=? and reg_id=? and apid=?";
        $q = $this->_db->prepare($sql);
        $q->execute(array($dscore,$escore,$de,$e1,$e2,$e3,$e4,$e5,$penalty,$eventid,$regid,$apid));   
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//------------UPDATE at secretary_update.php
public function update_fullscore($dscore,$escore,$de,$e1,$e2,$e3,$e4,$avg,$penalty,$subtotal,$total,$eventid,$regid,$apid){	
	try {
			 $sql = "UPDATE gymnast_score SET dscore=?,escore=?,de=?,e1=?,e2=?,e3=?,e4=?,avg=?,penalty=?,subtotal=?,total=? WHERE eventid=? and reg_id=? and apid=?";  
			 
        $q = $this->_db->prepare($sql);
        $q->execute(array($dscore,$escore,$de,$e1,$e2,$e3,$e4,$avg,$penalty,$subtotal,$total,$eventid,$regid,$apid));   
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//igl.php
	public function update_tramp_status($team,$regid,$lastid,$status, $aname,$gender){	
	try {
				$sql="UPDATE `panel_apparatus` SET `club`=?,`reg_id`=?,`lastid`=?,`Status`=? WHERE aname=? and gender=?";		
		        $q = $this->_db->prepare($sql);
		        $q->execute(array($team,$regid,$lastid,$status, $aname,$gender)); 	       
		} 
		catch(PDOException $e) {
		}
	}
//INSERT
//secretary.php
	public function set_gymnastscore($regid,$cat,$apid,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$e5,$e6,$penalty,$avg,$subtotal, $total){	
	try {
			$sql = "INSERT INTO `gymnast_score` ( `reg_id`, `level`, `category`, `apid`, `eventid`, `dtime`, `panel`, `dscore`, `escore`, `de`,e1,e2,e3,e4,penalty,avg,subtotal,total) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		    $q = $this->_db->prepare($sql);
		    $q->execute(array($regid,0,$cat,$apid,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$penalty,$avg,$subtotal,$total));
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function add_gymnastscore($regid,$level,$sublevel,$cat,$apid,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$e5,$e6,$penalty,$avg,$subtotal, $total){	
	try {
			$sql = "INSERT INTO `gymnast_score` ( `reg_id`, `level`, sublevel,`category`, `apid`, `eventid`, `dtime`, `panel`, `dscore`, `escore`, `de`,e1,e2,e3,e4,penalty,avg,subtotal,total) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		    $q = $this->_db->prepare($sql);
		    $q->execute(array($regid,$level,$sublevel,$cat,$apid,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$penalty,$avg,$subtotal,$total));
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//INSERT
	public function set_usaigcscore($regid,$level,$cat,$apid,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$e5,$e6,$penalty,$avg,$subtotal, $total){	
	try {
			$sql = "INSERT INTO `gymnast_score` ( `reg_id`, `level`, `category`, `apid`, `eventid`, `dtime`, `panel`, `dscore`, `escore`, `de`,e1,e2,e3,e4,penalty,avg,subtotal,total) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		    $q = $this->_db->prepare($sql);
		    $q->execute(array($regid,$level,$cat,$apid,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$penalty,$avg,$subtotal,$total));
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//igl.php
	public function set_iglscore($reg_id, $eventid, $team, $apid, $category, $bonus,$stick, $target, $connection, $difficulty, $escore, $e1, $execution, $penalty, $total,$remarks){	
	try {
			$sql = "INSERT INTO `igl_score`(`reg_id`,`eventid`, `team`,`apid`,`category`,`bonus`,`stick`, `target`, `connection`, `difficulty`, `escore`, `e1`, `execution`, `penalty`, `total`,remarks) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		    $q = $this->_db->prepare($sql);
		    $q->execute(array($reg_id, $eventid, $team, $apid, $category, $bonus,$stick, $target, $connection, $difficulty, $escore, $e1, $execution, $penalty, $total,$remarks));

		    $sql="UPDATE `igl_scorestatus` SET `status`=?";
		        $q = $this->_db->prepare($sql);
		        $q->execute(array(''));

		    $sql="UPDATE `igl_scorestatus` SET `status`=? WHERE `apid` = ? and regid=?";
		        $q = $this->_db->prepare($sql);
		        $q->execute(array('Scored',$apid,$reg_id)); 
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}


	//igl.php
	public function set_tiescore($reg_id, $eventid, $team,$status){	
	try {	
			if($status=='stick')
			{	
				$sql = "SELECT `stick` FROM `igl_match_tie` WHERE eventid=? AND `team` = ?";			
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array($eventid, $team));					
				$stickdata = $stmt->fetch(PDO::FETCH_ASSOC);
				$stick=$stickdata['stick'];
				$stick=$stick+1;
				$sql = "UPDATE `igl_match_tie`  SET `stick` = ? WHERE `eventid` = ? AND `team` LIKE ?";
			    $q = $this->_db->prepare($sql);
			    $q->execute(array($stick, $eventid, $team));
			}
			else
			{
				$sql = "SELECT `miss` FROM `igl_match_tie` WHERE eventid=? AND `team` = ?";			
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array($eventid, $team));					
				$stickdata = $stmt->fetch(PDO::FETCH_ASSOC);
				$stick=$stickdata['miss'];
				$stick=$stick+1;
				$sql = "UPDATE `igl_match_tie`  SET `miss` = ? WHERE `eventid` = ? AND `team` LIKE ?";
			    $q = $this->_db->prepare($sql);
			    $q->execute(array($stick, $eventid, $team));
			}

		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

//igl.php
	public function update_iglscore($bonus,$stick, $target, $connection,$difficulty,$e1,$execution,$penalty,$total,$eventid,$regid,$apid){	
	try {
			$sql ="UPDATE igl_score SET bonus=?,stick=?, target=?, connection=?, difficulty=?,e1=?,execution=?,penalty=?,total=? WHERE eventid=? and reg_id=? and apid=?";
        	$q = $this->_db->prepare($sql);
        	$q->execute(array($bonus,$stick, $target, $connection,$difficulty,$e1,$execution,$penalty,$total,$eventid,$regid,$apid)); 	       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//igl.php
	public function set_igl_round($score,$eventid,$apid, $team,$regid){	
	try {
				$sql = "SELECT Gender FROM `registrations` WHERE `id` = ?";			
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array($regid));					
				$Genderdata = $stmt->fetch(PDO::FETCH_ASSOC);
				
				if ($Genderdata['Gender'] == 'M')
				{
					$sql = "SELECT  `M` FROM `igl_round` WHERE eventid=? and `apname` = ? and team=?";	
					
					$stmt = $this->_db->prepare($sql);
					$stmt->execute(array($eventid,$apid, $team));					
					$tscore = $stmt->fetch(PDO::FETCH_ASSOC);
					$teamscore=$tscore['M'];
					
					if(isset($teamscore))
						$score = $score + $teamscore;
				 	$sql = "UPDATE `igl_round` SET `M` = ? WHERE eventid=? and `apname` = ? and team=?";
			        $q = $this->_db->prepare($sql);
			        $q->execute(array($score,$eventid,$apid, $team));
		        }
		        else
		        {
		        	$sql = "SELECT  `F` FROM `igl_round` WHERE eventid=? and `apname` = ? and team=?";	
					
					$stmt = $this->_db->prepare($sql);
					$stmt->execute(array($eventid,$apid, $team));					
					$tscore = $stmt->fetch(PDO::FETCH_ASSOC);
					$teamscore=$tscore['F'];
					
					if(isset($teamscore))
						$score = $score + $teamscore;
				 	$sql = "UPDATE `igl_round` SET `F` = ? WHERE eventid=? and `apname` = ? and team=?";
			        $q = $this->_db->prepare($sql);
			        $q->execute(array($score,$eventid,$apid, $team));
		        }  
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//igl.php
	public function update_igl_round($regid,$oldscore,$score,$eventid,$apid, $team){	
	try {
				// $sql = "SELECT  `score` FROM `igl_round` WHERE eventid=? and `apname` = ? and team=?";				
				// $stmt = $this->_db->prepare($sql);
				// $stmt->execute(array($eventid,$apid, $team));					
				// $tscore = $stmt->fetch(PDO::FETCH_ASSOC);
				// $teamscore=$tscore['score'];
				// if(isset($teamscore))
				// $score=	$teamscore-$oldscore+$score;			
					
			 // 	$sql = "UPDATE `igl_round` SET `score` = ? WHERE eventid=? and `apname` = ? and team=?";
		  //       $q = $this->_db->prepare($sql);
		  //       $q->execute(array($score,$eventid,$apid, $team));  
		$sql = "SELECT Gender FROM `registrations` WHERE `id` = ?";			
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array($regid));					
				$Genderdata = $stmt->fetch(PDO::FETCH_ASSOC);
				
				if ($Genderdata['Gender'] == 'M')
				{
					$sql = "SELECT  `M` FROM `igl_round` WHERE eventid=? and `apname` = ? and team=?";	
					
					$stmt = $this->_db->prepare($sql);
					$stmt->execute(array($eventid,$apid, $team));					
					$tscore = $stmt->fetch(PDO::FETCH_ASSOC);
					$teamscore=$tscore['M'];
					
					if(isset($teamscore))
						$score=	$teamscore-$oldscore+$score;
				 	$sql = "UPDATE `igl_round` SET `M` = ? WHERE eventid=? and `apname` = ? and team=?";
			        $q = $this->_db->prepare($sql);
			        $q->execute(array($score,$eventid,$apid, $team));
		        }
		        else
		        {
		        	$sql = "SELECT  `F` FROM `igl_round` WHERE eventid=? and `apname` = ? and team=?";	
					
					$stmt = $this->_db->prepare($sql);
					$stmt->execute(array($eventid,$apid, $team));					
					$tscore = $stmt->fetch(PDO::FETCH_ASSOC);
					$teamscore=$tscore['F'];
					
					if(isset($teamscore))
						$score=	$teamscore-$oldscore+$score;
				 	$sql = "UPDATE `igl_round` SET `F` = ? WHERE eventid=? and `apname` = ? and team=?";
			        $q = $this->_db->prepare($sql);
			        $q->execute(array($score,$eventid,$apid, $team));
		        }  
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//igl.php
	public function update_igl_scoreboard($team,$status,$regid, $apid){	
	try {
				//$sql="UPDATE `igl_scorestatus` SET `status` = '' WHERE `igl_scorestatus`.`icid` != 7";
				$sql="UPDATE `igl_scorestatus` SET `status` = ''";
				$q = $this->_db->prepare($sql);
		        $q->execute(array()); 
				$sql="UPDATE `igl_scorestatus` SET team ='$team',`status`='$status',`regid` = $regid WHERE apid=$apid";
		
		        $q = $this->_db->prepare($sql);
		        $q->execute(array($team,$status,$regid, $apid)); 	       
		} 
		catch(PDOException $e) {
		}
	}

//igl.php
	public function update_igl_scoreboardnext($club,$regid, $apid){	
	try {		
		// $sql="UPDATE `igl_scorestatus` SET `status` = '' WHERE `igl_scorestatus`.`icid` != 7";
		// 		$q = $this->_db->prepare($sql);
		//         $q->execute(array()); 
		//         print_r($sql);
				$sql="UPDATE `igl_scorestatus` SET team =?,`apid`=?,`regid`=?,`status`=? WHERE `icid` = '7'";
		        $q = $this->_db->prepare($sql);
		        $q->execute(array($club,$apid,$regid,'UpNext')); 	       
		} 
		catch(PDOException $e) {
			 echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//igl.php
	public function update_igl_scoreboardstatus($team,$match,$status){	
	try {		
				$sql="UPDATE `igl_scorestatus` SET team =?,`match`=?,`status`=? WHERE `icid` = '7'";
		        $q = $this->_db->prepare($sql);
		       
		        $q->execute(array($team,$match,$status)); 	       
		} 
		catch(PDOException $e) {
			 echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

//vault.php
	public function insert_gymnastscore($regid,$cat,$apid,$total,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$e5,$e6,$avg,$penalty,$subtotal){	
	try {
			$sql = "INSERT INTO `gymnast_score`(`reg_id`,`category`, `apid`, `total`, `eventid`, `dtime`, `panel`, `dscore`, `escore`, `de`, `e1`, `e2`, `e3`, `e4`, `e5`, `e6`, `avg`, `penalty`, `subtotal`) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		    $q = $this->_db->prepare($sql);
		    $q->execute(array($regid,$cat,$apid,$total,$eventid,$dtime,$panel,$dscore,$escore,$de,$e1,$e2,$e3,$e4,$e5,$e6,$avg,$penalty,$subtotal));  		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//register.php
	public function participantadd($name, $gender, $dob, $eventid, $club, $event,$regdate,$Email,$Phone,$amount,$datepaid){	
	try {
			$sql = "INSERT INTO `registrations`(`name`, `Gender`, `dob`, `Event`, `club`, `eventtype`, `regdate`,email,phone,`amount`,`datepaid`) values (?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array( $name, $gender, $dob, $eventid, $club, $event,$regdate,$Email,$Phone,$amount,$datepaid));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function participantedit($name, $gender, $dob, $club, $event){	
	try {
			$sql = "UPDATE `registrations` SET `name`=?, `Gender`=?, `dob`=?, `club`=?, `eventtype`=?";
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array( $name, $gender, $dob, $club, $event));           
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function addqualifierstofinals($name,$Gender,$Event,$club,$eventtype,$startorder){	
	try {

			$sql = "SELECT *  FROM `registrations` WHERE `name` LIKE '$name' AND `club` LIKE '$club' and event=$Event";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();		
			if($row ==null)
			{
				$sql = "INSERT INTO `registrations`(`name`, `Gender`, `Event`, `club`,`eventtype`,`startorder`) values(?,?,?,?,?,?)";
	            $stmt = $this->_db->prepare($sql);
				$stmt->execute(array($name,$Gender,$Event,$club,$eventtype,$startorder));      
			}     
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	//
	public function createstartorder($qr,$startorder,$rank,$eventid,$regid,$apparatus){	
	try {
			$sql = "UPDATE gymnast_score SET qr=?,startorder=?,rank=? WHERE eventid=? and reg_id=? and apid=?";
			
            $stmt = $this->_db->prepare($sql);
			$stmt->execute(array($qr,$startorder,$rank,$eventid,$regid,$apparatus));    
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//-----------------------------------------------------------------
	//used to get score on tumb_preconfirm.php,tumbling_score.php
	public function get_tumbling_score($eventid,$regid,$routine){	
	try {	
			$sql = "SELECT reg_id,category,s.eventid,dscore,escore,e1,e2,e3,e4,panel,total,penalty FROM `tumbling_score` s where eventid='".$eventid."' and reg_id='".$regid."' and apname='".$routine."'";			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//used to get score on tramp_preconfirm.php,trampoline.php
	public function get_trampoline_score($eventid,$regid,$routine){	
	try {	
			$sql = "SELECT reg_id,category,s.eventid,dscore,escore,e1,e2,e3,e4,panel,`horizontal`, `tof`,total,penalty FROM `trampoline_score` s where eventid='".$eventid."' and reg_id='".$regid."' and apname='".$routine."'";			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//tumbling_score.php
	public function set_tumblingscore($regid,$eventid,$cat,$panel,$apname,$dscore,$escore,$e1,$e2,$e3,$e4,$penalty,$total,$dtime){	
	try {
			 	$sql = "INSERT INTO `tumbling_score` (`reg_id`, `eventid`, `category`, `panel`,apname, `dscore`, `escore`, `e1`, `e2`, `e3`, `e4`, `penalty`,total,dtime) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		        $q = $this->_db->prepare($sql);
		        $q->execute(array($regid,$eventid,$cat,$panel,$apname,$dscore,$escore,$e1,$e2,$e3,$e4,$penalty,$total,$dtime));  
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//tumbling_score.php
	public function Update_tumblingscore($regid,$eventid,$apname,$dscore,$escore,$penalty,$total){	
	try {
			 	$sql = "UPDATE tumbling_score SET `dscore`=?, `escore`=?,`penalty`=?,total=? WHERE eventid=? and reg_id=? and apname=?";
		        $q = $this->_db->prepare($sql);
		        $q->execute(array($dscore,$escore,$penalty,$total,$eventid,$regid,$apname));  
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	
	//trampoline.php
	public function set_trampolinescore($regid,$eventid,$cat,$panel,$apname,$dscore,$escore,$e1,$e2,$e3,$e4,$horizontal,$tof,$penalty,$total,$dtime){	
	try {
			 	$sql = "INSERT INTO `trampoline_score`(`reg_id`, `eventid`, `category`,`panel`,apname, `dscore`, `escore`, `e1`, `e2`, `e3`, `e4`, `horizontal`,`tof`,`penalty`,total,dtime) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		        $q = $this->_db->prepare($sql);
		        $q->execute(array($regid,$eventid,$cat,$panel,$apname,$dscore,$escore,$e1,$e2,$e3,$e4,$horizontal,$tof,$penalty,$total,$dtime));  
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//trampoline.php
	public function Update_trampolinescore($regid,$eventid,$apname,$dscore,$escore,$horizontal,$tof,$penalty,$total){	
	try {
			 	$sql = "UPDATE trampoline_score SET `dscore`=?, `escore`=?,`horizontal`=?, `tof`=?, `penalty`=?,total=? WHERE eventid=? and reg_id=? and apname=?";
		        $q = $this->_db->prepare($sql);
		        $q->execute(array($dscore,$escore,$horizontal,$tof,$penalty,$total,$eventid,$regid,$apname));  
		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//tram_calculate.php
	public function update_trampoline_total($regid,$eventid,$total,$routine){	
	try {
			$sql = "UPDATE trampoline_score SET total=$total WHERE eventid=$eventid and reg_id=$regid and apname='$routine'";  
		    $q = $this->_db->prepare($sql);
		    $q->execute(array());  		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//tumbling_calculate.php
	public function update_tumbling_total($regid,$eventid,$total,$routine){	
	try {
			$sql = "UPDATE tumbling_score SET total=$total WHERE eventid=$eventid and reg_id=$regid and apname='$routine'";  
		    $q = $this->_db->prepare($sql);
		    $q->execute(array());  		       
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//generate trampolinequalifier
	public function getclub_bytable($eventid,$gender,$tblname,$category){	
	try {	
		
			$sql="SELECT distinct club  FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."'  ";	//and qr Is not null
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//tramp_generatequalifier.php
	public function tramp_generatequalifier($eventid,$gender,$tblname,$category){	
	try {	
			$sql="SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,club FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."' order BY total desc,dscore DESC,escore desc,tof DESC,horizontal DESC";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	public function tumb_generatequalifier($eventid,$gender,$tblname,$category){	
	try {	
			$sql="SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,club FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."' order BY total desc,dscore DESC,escore DESC";	
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array());			
			$row = $stmt->fetchAll();			
			return $row;
		} 
		catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	//printresults.php
	public function artistic_generatequalifier($eventid,$gender,$tblname,$category,$level,$apid){	
	try {	
			if($level!='')
			$sql="SELECT reg_id,s.*,Category,eventid, r.gender,dob,phone,name,club FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and r.level='".$level."' and category='".$category."' and apid='".$apid."' order BY total desc,subtotal desc,dscore DESC,penalty DESC";
			else
			$sql="SELECT reg_id,s.*,Category,eventid, r.gender,dob,phone,name,club FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."' and apid='".$apid."' order BY total desc,subtotal desc,dscore DESC,penalty DESC";	
			//echo $sql;
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