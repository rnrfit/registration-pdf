<?php
class Apparatus{
	private $_db;

    function __construct(){    	  
    $db = new Database(); 
    	$this->_db = $db->datacon; 
    }

    public function get_apparatus(){	
	try {
			$stmt = $this->_db->prepare('SELECT * FROM `apparatus` WHERE pid = 1 and  `IsActive` = 1 order by gname Asc');
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
}