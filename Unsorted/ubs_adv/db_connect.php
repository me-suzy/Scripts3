<?
class db_connect {
  
  var $database = "";

  var $link_id  = 0;
  var $query_id = 0;
  var $record   = array();

  var $errdesc    = "";
  var $errno   = 0;
  var $show_error = 1;
   
  var $server   = "";
  var $user     = "";
  var $password = "";

  var $appname  = "Character battle system";
  
	function connect() {
    		if ( 0 == $this->link_id ) {
      			
      			$this->link_id=mysql_connect($this->server,$this->user,$this->password);
      			
      			if (!$this->link_id) {
        			$this->print_error("Link-ID == false, connect failed");
      			}
      			if ($this->database!="") {
        			$this->select_db($this->database);
      			}
    		}
  	}
	
	function geterrdesc() {
    		$this->error=mysql_error();
    		return $this->error;
  	}

  	function geterrno() {
    		$this->errno=mysql_errno();
    		return $this->errno;
  	}

  	function select_db($database="") {
    		if ($database!="") {
      		$this->database=$database;
    		}
	    	if(!@mysql_select_db($this->database, $this->link_id)) {
      			$this->print_error("cannot use database ".$this->database);
    		}
  	}

  	function query($query_string) {
    		$this->query_id = mysql_query($query_string,$this->link_id);
    		if (!$this->query_id) {
      			$this->print_error("Invalid SQL: ".$query_string);
    		}
    		return $this->query_id;
  	}

  	function fetch_array($query_id=-1) {
    		if ($query_id!=-1) {
      			$this->query_id=$query_id;
    		}
    		$this->record = mysql_fetch_array($this->query_id);

    		return $this->record;
  	}

  	function free_result($query_id=-1) {
    		if ($query_id!=-1) {
      			$this->query_id=$query_id;
    		}
    		return @mysql_free_result($this->query_id);
  	}

  	function query_first($query_string) {
    		$this->query($query_string);
    		$returnarray=$this->fetch_array($this->query_id);
    		$this->free_result($this->$query_id);
    		return $returnarray;
  	}

  	function num_rows($query_id=-1) {
    		if ($query_id!=-1) {
      			$this->query_id=$query_id;
    		}
    		return mysql_num_rows($this->query_id);
  	}

  	function insert_id() {
    		return mysql_insert_id($this->link_id);
  	}

  	function print_error($msg) {
    		$this->errdesc=mysql_error();
    		$this->errno=mysql_errno();
    		
    		global $adminmail;
    		$message="Database error in $this->appname: $msg\n<br>";
    		$message.="mysql error: $this->errdesc\n<br>";
    		$message.="mysql error number: $this->errno\n<br>";
    		$message.="Date: ".date("d.m.Y @ H:i")."\n<br>";
    		$message.="Script: ".getenv("REQUEST_URI")."\n<br>";
    		$message.="Referer: ".getenv("HTTP_REFERER")."\n<br><br>";

    		if($this->show_error) $message = "$message";
		else $message = "\n<!-- $message -->\n";
      		eval("dooutput(\"".gettemplate("db_error")."\");");
      		die("");
    	}
}
