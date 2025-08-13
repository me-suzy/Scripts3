<?php
/************************************************************************/
/*  Program Name         : QuizShock                                    */
/*  Program Version      : 1.5.4                                        */
/*  Program Author       : Pineapple Technologies                       */
/*  Supplied by          : CyKuH [WTN]                                  */
/*  Nullified by         : CyKuH [WTN]                                  */
/*  Distribution         : via WebForum and Forums File Dumps           */
/*                  (c) WTN Team `2004                                  */
/*   Copyright (c)2002 Pineapple Technologies. All Rights Reserved.     */
/************************************************************************/

class db_sql
{
	
	var $database;
	var $hostname;
	var $username;
	var $password;
	var $link;
	var $debug;

	var $die_on_error;
	function db_sql($hostname, $username, $password, $database)
	{
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;

		$this->debug=0;
		$this->die_on_error=0;
	}

	function debug_on()
	{
		$this->debug=1;
	}

	function debug_off()
	{
		$this->debug=0;
	}

	function connect()
	{
	
		if(!($this->link = @mysql_pconnect($this->hostname,$this->username,$this->password)))
			return false;
		else
			return true;
	}

	function select_db()
	{
		if(!@mysql_select_db($this->database, $this->link))
			return false;
		else
			return true;
	}

	function query($query)
	{

		if($this->debug)
		{
			echo "<b>Executing Query:</b> $query<br>\n\n";
		}

		if(!($result = @mysql_query($query, $this->link)))
		{
			if($this->die_on_error)
				$this->error_out($query);
			else
				return 0;
		}

		return $result;
	}

	function fetch_array($result)
	{
		return @mysql_fetch_array($result, MYSQL_ASSOC);
	}

	function query_one_result($query)
	{
		return @mysql_result(mysql_query($query), 0);
	}
	
	function insert_from_array($array, $table, $unique_field="")
	{
		if($unique_field)
		{
			if($this->query_one_result("SELECT COUNT(*) FROM $table WHERE $unique_field='$form_options[$unique_field]'"))
				return 0;
		}
	
		$query = "INSERT INTO $table (";

		while(@list($key,$value) = @each($array))
		{
			$fieldnames[] = "$key";
			$fieldvalues[] = "'$value'";
			
		} // end while

		$query .= implode(",", $fieldnames);


		$query .= ") VALUES (" . implode(",", $fieldvalues) . ")";
		$this->query($query);
 
		return $this->query_one_result("SELECT id FROM $table ORDER BY id DESC LIMIT 1");
	
	} // end function insert_from_array
		
	function update_from_array($array, $table, $id)
	{
		$query = "UPDATE $table SET ";
		while(@list($key,$value) = @each($array))
			$fields[] = "$key='$value'";

		$query .= @implode(",", $fields);
		$query .= " WHERE id=$id";
		$this->query($query);
	
	} // end function update_from_array

	function num_rows($resultid)
	{
		return @mysql_num_rows($resultid);
	}

	function data_seek($resultid, $rownum)
	{
		return @mysql_data_seek($resultid, $rownum);
	}

	function get_error_msg()
	{
		return @mysql_error();
	}

	function get_error_num()
	{
		return @mysql_errno();
	}

	function error_out($query)
	{
		global $C_OPTS, $OPTIONS;

		$error_msg = $this->get_error_msg();
		$error_num = $this->get_error_num();
		
		if( $C_OPTS['SHOW_DB_ERRORS'] )
		{
			echo "There was an error querying the database, MySQL said:<br><br>";;
			echo "<font color=red><b>($error_num) $error_msg</b></font>";
			echo "<br><br>The following query was attemped:<br><br>";
			echo '<b>' . htmlspecialchars($query) . '</b>';
			
		}
		
		else
		{
			echo $C_OPTS['DB_QUERY_ERROR_MSG'];
		}
		if( $C_OPTS['EMAIL_ON_ERROR'] )
		{
			$body =  "There was an error querying the database, MySQL said:\n\n"
				."($error_num) $error_msg\n\n"
				."The following query was attemped:\n\n"
				."$query\n\n\n\n"
				."Script: http://" . getenv('HTTP_HOST') . getenv('SCRIPT_NAME') . "\n"
				."Referer: " . getenv('HTTP_REFERER') . "\n";
				
			ts_mail($C_OPTS['ERROR_EMAIL'], $OPTIONS['TRIVIA_SITE_NAME'] . ' Database Error', $body);
		}

		exit;
	}


} // end class db_sql

?>