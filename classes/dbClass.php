<?php
/**
 * Created by Sebbans.
 * Date: 2018-04-01
 * Time: 22:15
 */

class dbClass{


    private $dbhost = DB_HOST;
    private $dbuser = DB_USER;
    private $dbpass = DB_PASS;
    private $dbname = DB_DATABASE;

    private $conn;

    public $dbErrors = array();
    public $stmt;


    public function __construct(){
        $dsn = "mysql:host=$this->dbhost;dbname=$this->dbname;";

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try{
            $this->conn = new PDO($dsn, $this->dbuser, $this->dbpass, $options);
        }catch (PDOException $e){
            $this->dbErrors[] = $e->getMessage();
        }

    }

    public function query($query){
        $this->stmt = $this->conn->prepare($query);
    }

    public function execute($arr = array()){
        return $this->stmt->execute($arr);
    }

    public function resultAssoc(){
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resultSingle(){
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }


}

