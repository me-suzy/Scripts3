<?php

class Database{
  
    // private instant variables
    var $dbConnectionID;
    var $query_result;
    var $host;
    var $database;
    var $user;
    var $password;
  
    /* 
    constructor
    connect to datbase server and select specified database
    */
  
    function Database($host="localhost", $db="-beta", $user="root", $pwd=""){
        $this->host = $host;
        $this->database = $db;
        $this->user = $user;
        $this->password = $pwd;
        $this->connect();
    }
    
    function connect() {
        $this->dbConnectionID = mysql_connect($this->host, $this->user, $this->password);
        if (!$this->dbConnectionID) {
            echo mysql_errno() . " : " . mysql_error();
            exit;
        } else {
            $choice = mysql_select_db($this->database, $this->dbConnectionID);
            if (!$choice) {
                echo mysql_errno() . " : " . mysql_error();
                exit;
            }
        } 
    }
  
    function execQuery($sql) {
        $this->query_result = mysql_query($sql, $this->dbConnectionID);
        if (!$this->query_result) {
            die(" Invalid SQL Query...<br>");
        } else {
            return $this->query_result;
        }
    }
  
    function getNextRecord() {
        return mysql_fetch_array($this->query_result);
    }
  
    function freeResult() {
        mysql_free_result($this->query_result);
    }

    function getAutoIncrementId() {
       if ($this->dbConnectionID != 0) {
          if (($id = mysql_insert_id($this->dbConnectionID)) != 0) {
             return $id;
          } else {
             die ("no ID available...<br>");
          }
       } 
    }
   
    function close(){
        mysql_close ($this->dbConnectionID);
    }
}
?>
