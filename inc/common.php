<?php
class Common
{
	public $_db;
	public  $objdb;
 
	public function __construct(){
		$db = new Database();
		$this->objdb = $db;  
		$this->_db = $db->datacon;  	
	}

	
	public function getRows($table,$conditions = array()){
	
		$records = $this->objdb->getRows($table,$conditions);
		return $records;
	}
	public function getvalue($sql1){
       
        $records = $this->objdb->getvalue($sql1);
		return $records;
    }

    public function executesql($sql){       
        $records = $this->objdb->executeQuery($sql);
		return $records;
    }
    public function insertupdate($sql){       
        $records = $this->objdb->executeupdate($sql);
		return $records;
    }
}
?>