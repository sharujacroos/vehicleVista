<?php


class DbConnector {
    private $dbHost = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName = "vehicleVista";
    private $con;
    
    public function __construct(){
        try {
            $dsn = "mysql:host=$this->dbHost;dbname=$this->dbName";
            $this->con = new PDO($dsn, $this->dbUsername, $this->dbPassword);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            die("Connection Failed<br>.$ex");
        }
    }
    
    public function getConnection() {
        return $this->con;
    }
}

