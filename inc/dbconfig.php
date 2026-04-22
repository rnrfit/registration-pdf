<?php
session_start();
class Database
{
    // Database credentials
    private $dbHost     = 'localhost';
    private $dbUsername = 'root';//ebby
    private $dbPassword = '';
    private $dbName     = 'events';//events
    // private $dbUsername = 'getact6_ebby';
    // private $dbPassword = 'dev@rnrfit';
    // private $dbName     = 'getact6_events';//getact6_events
   
     public $datacon;

    //private static $cont  = null;
    /*
     * Connect to the database and return db connecction
     */
    function __construct(){
        if(!isset($this->datacon)){
            // Connect to the database
            try
            {                
                    $conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName",$this->dbUsername,$this->dbPassword);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                    $this->datacon = $conn;
                    $conn=null;
            }
            catch(PDOException $e){
                    die("Failed to connect with MySQL: " . $e->getMessage());
            }
        }
    }
    public static function getPostValue($value, $format="CHAR"){
        if(isset($_REQUEST[$value]) && !is_array($_REQUEST[$value]) )
        {
            if($format == "INT") 
            {              
                return intval($_REQUEST[$value]);
            }
            else
                return $_REQUEST[$value];
        }
        return "";
    }

    public function getdata($sql1)
     {
        $query = $this->datacon->prepare($sql1);
        $query->execute();           
        $stmt = $query->fetchAll(PDO::FETCH_ASSOC);
        //$this->disconnect();
        return $stmt;
    }
    public function getvalue($sql1){
       
        $query = $this->datacon->prepare($sql1);
        $query->execute();           
        $stmt = $query->fetchColumn();
        //$nume = $pdo->query("SELECT id FROM registrations ORDER BY id DESC LIMIT 1")->fetchColumn();
        //$this->disconnect();
        return $stmt;
    }
    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table,$conditions = array()){
    
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        /*if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){

                $pre = ($i > 0)?' AND ':'';
                
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }*/
         if(!empty($conditions)&& is_array($conditions)){
                $whereSql = ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
                $sql .=$whereSql;
            }
       
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by']; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
       
        $query = $this->datacon->prepare($sql);
        
        $query->execute();
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'single':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        }else{
            if($query->rowCount() > 0){
                $data = $query->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        //self::disconnect();
        return !empty($data)?$data:false;
    }
    
    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public static function insert($table,$data){
       
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;
            /*
            if(!array_key_exists('created',$data)){
                $data['created'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }*/

            $columnString = implode(',', array_keys($data));
            $valueString = ":".implode(',:', array_keys($data));
            $sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")";
            $query = $this->datacon->prepare($sql);
            foreach($data as $key=>$val){
                $val = htmlspecialchars(strip_tags($val));
                $query->bindValue(':'.$key, $val);
            }
            $insert = $query->execute();
            if($insert){
                $data['id'] = $this->datacon->lastInsertId();
                $this->disconnect();
                return $data;
            }else{
                $this->disconnect();
                return false;
            }
        }else{
            return false;
        }
    }
    
    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public static function update($table,$data,$conditions){
      
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            /*
            if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }*/
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $val = htmlspecialchars(strip_tags($val));
                $colvalSet .= $pre.$key."='".$val."'";
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
            $query = $this->datacon->prepare($sql);
            $update = $query->execute();
            $this->disconnect();
            return $update?$query->rowCount():false;
        }else{
              $this->disconnect();
            return false;
        }
    }
    
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public static function delete($table,$conditions){
      
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $sql = "DELETE FROM ".$table.$whereSql;
        $delete = $this->datacon->exec($sql);
       $this->disconnect();
        return $delete?$delete:false;
    }

    public function executeQuery($sql) {

        $query = $this->datacon->prepare($sql);
        $query->execute();
        if($query->rowCount() > 0){
                $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        $query=null;
        return !empty($data)?$data:false;
    }
    public function executeupdate($sql) {

        $query = $this->datacon->prepare($sql);
        $query->execute();       
        return true;
    }

    public function sanitizeArray($array = null){
        if(is_array($array) && $array != null)
        {
            foreach ($array as $key => $value) {
                if(is_array($value))
                    $this->sanitizeArray($value);
                else
                    //$array[$key] = htmlspecialchars(mysql_escape_string($value));
                    $array[$key] = htmlspecialchars(strip_tags($value));
            }
            return $array;
        }

    }

    public function checkPostInputVariables($name, $type, $redirect, $maxlength=NULL){
        $validation = true;
        $typeArray = array('TEXT', 'INT', 'ARRAY');
        if(isset($_POST[$name]))
        {
            if($type == 'ARRAY')
            {
                if(!is_array($_POST[$name]))
                    $validation = false;
            }
            elseif($type == 'INT')
            {
                $int=(int)$_POST[$name];
                $int = filter_var($_POST[$name],FILTER_SANITIZE_NUMBER_INT);
                if (filter_var($int, FILTER_VALIDATE_INT)) {
                    $validation = true;
                   
                } else {
                    $validation = false;
                }                                  
            }
        }
        else
            $validation = false;
        if($maxlength != NULL){
            if(is_array($_POST[$name]) || strlen($_POST[$name])>$maxlength)
                $validation = false;
        }
        if(!$validation){
            header('location:'.$redirect.'?msg='.$name.' is not valid');
            exit();
        }
    }

    public static function checkGetInputVariables($name, $type, $redirect, $maxlength=NULL){
        $validation = true;

        $typeArray = array('TEXT', 'INT', 'ARRAY');
        if(isset($_GET[$name]))
        {
            if($type == 'ARRAY')
            {
                if(!is_array($_GET[$name]))
                    $validation = false;
            }
            elseif($type == 'INT')
            {
                $int=(int)$_GET[$name];
                $int = filter_var($_GET[$name],FILTER_SANITIZE_NUMBER_INT);
                
                if (filter_var($int, FILTER_VALIDATE_INT)) {
                    $validation = true;
                   
                } else {
                    $validation = false;
                }                                  
            }
        }
        else
            $validation = false;
        if($maxlength != NULL){
            if(is_array($_GET[$name]) || strlen($_GET[$name])>$maxlength)
                $validation = false;
        }
        if(!$validation){
            header('location:'.$redirect.'?msg='.$name.' is not valid');
            exit();
        }
    }
    
    public function setFormHash($formname="DEFAULT"){
        $_SESSION['HASH'][$formname] = md5(str_shuffle("gfgttytre565876986534sfxfgxvxaeafn"));

        return $_SESSION['HASH'][$formname];
    }

    public function validateFormHash($formname, $value){
       
        if(isset( $_SESSION['HASH'][$formname]))
        {             
            return  $_SESSION['HASH'][$formname] === $value ? true : false;
        }
        else
            return false;
    }

    
}

?>