<?php


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

class s24_sql {
	function connect() {
		if (0 == $this->link_id) {
			if (empty($this->password)) {
				$this->link_id = @mysql_connect($this->host,$this->username)
					or $this->err("Could not connect to database. Using host: ".$this->host.", username: ".$this->username." and password: ".$this->password.". Check if host, username and password are correct!");
			} else {
				$this->link_id = @mysql_connect($this->host,$this->username,$this->password)
					or $this->err("Could not connect to database. Using host: ".$this->host.", username: ".$this->username." and password: ".$this->password.". Check if host, username and password are correct!");
			}
		}
		$link = $this->link_id;
		return $link;
	}
	function pconnect() {
		if (0 == $this->link_id) {
			if (empty($this->password)) {
				$this->link_id = @mysql_pconnect($this->host,$this->username)
					or $this->err("Could not connect to database. Using host: ".$this->host.", username: ".$this->username." and password: ".$this->password.". Check if host, username and password are correct!");
			} else {
				$this->link_id = @mysql_pconnect($this->host,$this->username,$this->password)
					or $this->err("Could not connect to database. Using host: ".$this->host.", username: ".$this->username." and password: ".$this->password.". Check if host, username and password are correct!");
			}
		}
		$link = $this->link_id;
		return $link;
	}
	function select_db() {
		@mysql_select_db($this->dbname, $this->link_id)
			or $this->err("Unable to select database: ".$this->dbname.". Check if database name is correct!");
	}
	function err($error) {
		head("MySQL Error");
	    $this->err_desc = mysql_error();
    	$this->err_no = mysql_errno();
	    global $emergencymail;
		$message = "Database error: $error\n";
		$message .= "MySQL Error: $this->err_desc\n";
		$message .= "MySQL Error Number: $this->err_no\n";
		$message .= "Date: ".date("m/d/Y h:i:s a")."\n";
		$message .= "URL: ".getenv("REQUEST_URI")."\n";
		$message .= "Referer: ".getenv("HTTP_REFERER")."\n";
		@mail ($emergencymail,"$this->scriptname Database error!",$message);
		echo "<table><tr><td>";
		echo "<p>There seems to be a slight problem with our database.\n";
		echo "Please try again by pressing the refresh button in your browser.</p>";
		echo "An Email has been dispatched to our <a href=\"mailto:$adminemail\">Technical Staff</a>, who you can also contact if the problem persists.</p>";
		echo "<p>Sorry for any inconvenience.</p>";
		echo "</td></tr></table>";
		footer();
		exit;
	}
	function query($sql) {
		$this->result = mysql_query($sql)
			or $this->err("Invalid SQL: $sql");
		return $this->result;
	}
	function fetch_row($result) {
		$this->result = $result;
		$this->row = mysql_fetch_row($this->result);
		return $this->row;
	}
	function fetch_array($result) {
		$this->result = $result;
		$this->row = mysql_fetch_array($this->result);
		return $this->row;
	}
	function num_rows($result) {
		$this->result=$result;
		return mysql_num_rows($this->result);
	}
	function free_result($result) {
		$this->result = $result;
		return @mysql_free_result($this->result);
	}
	function err_desc() {
		$this->error = mysql_error();
		return $this->error;
	}
	function err_no() {
		$this->errno = mysql_errno();
		return $this->errno;
	}
	function close($link) {
		$this->sqllink = $link;
		$this->close = mysql_close($this->sqllink);
		return $this->close;
	}
}

$s24_sql=new s24_sql;
$s24_sql->scriptname="ExitPopup";
$s24_sql->dbname=$mysql_db;
$s24_sql->host=$mysql_host;
$s24_sql->username=$mysql_user;
$s24_sql->password=$mysql_passwd;

?>