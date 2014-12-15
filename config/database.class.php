<?php
    
    include_once 'psl-config.php';
    
class Database{
 
    // specify your own database credentials
    private $host = HOST;
    private $db_name = DATABASE;
    private $username = USER;
    private $password = PASSWORD;
//    private $host = "localhost:3306";
//    private $db_name = "projetoagenda";
//    private $username = "root";
//    private $password = "connect";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>