<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
// inspirated in 'db class for mysql' from PHPLIB
// this class is used in all scripts
// do NOT fiddle unless you know what you are doing

class DB_Sql_vc
{
	var $database = '';
	var $idlink = 0;
	var $idquery = 0;
	var $record = array();
	var $errdesc = '';
	var $errno = 0;
	var $reporterror = 1;
	var $server = 'localhost';
	var $user = 'root';
	var $password = '';
	var $appname = 'vCard PRO';
	var $numqueries = 1;

	function connect() {
		global $usepconnect;

		if (0 == $this->idlink)
		{
			if (empty($this->password))
			{
				if ($usepconnect == 1)
				{
					$this->idlink = mysql_pconnect($this->server,$this->user);
				}else{
					$this->idlink = mysql_connect($this->server,$this->user);
				}
			}else{
				if ($usepconnect == 1)
				{
					$this->idlink = mysql_pconnect($this->server,$this->user,$this->password);
				}else{
					$this->idlink = mysql_connect($this->server,$this->user,$this->password);
				}
			}
			if (!$this->idlink)
			{
				$this->stop('linkid == false, connect failed');
			}
			if ($this->database!='')
			{
				if(!mysql_select_db($this->database, $this->idlink))
				{
					$this->stop('cannot use database '.$this->database);
				}
			}
		}
	}

	function geterrdesc() {
		$this->error = mysql_error();
		return $this->error;
	}
	function geterrno() {
		$this->errno = mysql_errno();
		return $this->errno;
	}
	function select_db($database='') {
		if (!empty($database))
		{
			$this->database = $database;
		}
		if (!mysql_select_db($this->database, $this->idlink))
		{
			$this->stop('cannot use database '.$this->database);
		}
	}
	function query($query_string) {
		global $query_count,$showqueries,$explain,$querytime,$numqueries;
		
		$numqueries++;
		if ($showqueries)
		{
			echo "QUERY: $query_string\n";
			global $pagestarttime;
			$pageendtime = microtime();
			$starttime = explode(' ',$pagestarttime);
			$endtime = explode(' ',$pageendtime);
			$beforetime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
			echo "Time before: $beforetime\n";
		}
		$this->idquery = mysql_query($query_string,$this->idlink);
		if (!$this->idquery)
		{
			$this->stop('INVALID SQL: '.$query_string);
		}
		$query_count++;
		if ($showqueries)
		{
			$pageendtime = microtime();
			$starttime = explode(" ",$pagestarttime);
			$endtime = explode(" ",$pageendtime);
			$aftertime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
			$querytime += $aftertime-$beforetime;
			echo "Time after:  $aftertime\n";
			if ($explain && substr(trim(strtoupper($query_string)),0,6) =='SELECT')
			{
				$explain_id = mysql_query("EXPLAIN $query_string",$this->idlink);
				echo "</pre>\n<table width='100%' border='1' cellpadding='2' cellspacing='1'><tr><td><b>table</b></td><td><b>type</b></td><td><b>possible_keys</b></td><td><b>key</b></td><td><b>key_len</b></td><td><b>ref</b></td><td><b>rows</b></td><td><b>Extra</b></td></tr>\n";
					while ($array = mysql_fetch_array($explain_id))
					{
						echo "<tr><td>$array[table]&nbsp;</td><td>$array[type]&nbsp;</td><td>$array[possible_keys]&nbsp;</td><td>$array[key]&nbsp;</td><td>$array[key_len]&nbsp;</td><td>$array[ref]&nbsp;</td><td>$array[rows]&nbsp;</td><td>$array[Extra]&nbsp;</td></tr>\n";
					}
				echo '</table><BR><hr size=1 noshade><pre>';
			}else{
				echo '<hr size=1 noshade>';
			}
		}
		return $this->idquery;
	}
	function fetch_array($idquery=-1,$query_string='')
	{
		if ($idquery != -1)
		{
			$this->idquery=$idquery;
		}
		if ( isset($this->idquery) )
		{
			$this->record = mysql_fetch_array($this->idquery);
		}else{
			if (isset($query_string))
			{
				$this->stop('INVALID QUERY ID ('.$this->idquery.') ON THIS QUERY: '.$query_string.'');
			}else{
				$this->stop('INVALID QUERY ID '.$this->idquery.' specified');
			}
		}
		return $this->record;
	}
	function free_result($idquery=-1)
	{
		if ($idquery != -1)
		{
			$this->idquery = $idquery;
		}
		return @mysql_free_result($this->idquery);
	}
	function query_first($query_string)
	{
		$idquery = $this->query($query_string);
		$returnarray = $this->fetch_array($idquery, $query_string);
		$this->free_result($idquery);
		return $returnarray;
	}
	function data_seek($pos,$idquery=-1) {
		if ($idquery != -1)
		{
			$this->idquery = $idquery;
		}
		return mysql_data_seek($this->idquery, $pos);
	}
	function num_rows($idquery=-1) {
		if ($idquery != -1)
		{
			$this->idquery = $idquery;
		}
		return mysql_num_rows($this->idquery);
	}
	function num_fields($idquery=-1){
		if ($idquery != -1)
		{
			$this->idquery = $idquery;
		}
		return mysql_num_fields($this->idquery);
	}
	function affected_rows($idquery=-1) {
		return mysql_affected_rows($this->idquery);
	}
	function insert_id() {
		return mysql_insert_id($this->idlink);
	}
	function count_records($table) {
		return mysql_result(mysql_query("SELECT COUNT(*) AS numero FROM $table"),0,"numero");
	}
	function close() {
		return mysql_close($this->idlink);
	}
	function stop($msg) {
		$this->errdesc = mysql_error();
		$this->errno = mysql_errno();
		if ($this->reporterror == 1)
		{
			$message  = "Database error in $this->appname:\n$msg\n";
			$message .= "mysql error: $this->errdesc\n";
			$message .= "mysql error number: $this->errno\n";
			$message .= "Date: " . date("Y/m/d - h:i:s A") . "\n";
			$message .= "Script: " . getenv("REQUEST_URI") . "\n";
			$message .= "Referer: " . getenv("HTTP_REFERER") . "\n";
			echo "</td></tr></table>\n<p>\n<pre>\n$message \n";
			//      echo "\n<!-- $message -->\n";
			die("");
		}
	}
}

?>