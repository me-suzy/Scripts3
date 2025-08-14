<?php

/**************************************************************
 * File: 		Database API / Core Functions- File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- basic database API core file / functions
 *	- Typical Create / Connect:
 *		$db_coin = new db_funcs();
 *		$db_coin->db_connect();
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("db.php", $_SERVER["PHP_SELF"])) {
		require_once ('session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01');
		exit;
	}

/**************************************************************
 * Database Config Load and DBMS Check
**************************************************************/

# Determine Database and include proper class file
	switch($_DBCFG['dbms'])
	{
		case "mysql":
			require_once ($_CCFG['_PKG_PATH_DBSE'].'db_mysql.php');
			break;
		default:
			require_once ($_CCFG['_PKG_PATH_DBSE'].'db_mysql.php');
			break;
	}


/**************************************************************
 * Database API Functions
**************************************************************/

	/**************************************************************
	 * Function:	db_read_prefix($suppress_err_flag=0)
	 * Arguments:	none
	 * Returns:		string	- DB Config. table prefix value
	 * Notes:
	 *	- none
	**************************************************************/
	function db_read_prefix($suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		$db_coin->db_connect_check();
		return $db_coin->db_return_prefix();
	}


	/**************************************************************
	 * Function:	db_connect($suppress_err_flag=0)
	 * Arguments:	none
	 * Returns:		database connect result
	 * Notes:
	 *	- Call to connect to configured database.
	**************************************************************/
	function db_connect($suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		return $db_coin->db_connect();
	}


	/**************************************************************
	 * Function:	db_query_execute($query, $suppress_err_flag=0)
	 * Arguments:	$query	- Executable SQL statement.
	 * Returns:		$result	- Result set
	 * Notes:
	 *	- none
	**************************************************************/
	function db_query_execute($query, $suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		$db_coin->db_connect_check();
		return $db_coin->db_query_execute($query);
	}


	/**************************************************************
	 * Function:	db_fetch_array($result, $suppress_err_flag=0)
	 * Arguments:	$result	- Valid result set
	 * Returns:		$row	- Record as array ($row)
	 * Notes:
	 *	- none
	**************************************************************/
	function db_fetch_array($result, $suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		$db_coin->db_connect_check();
		return $db_coin->db_fetch_array($result);
	}


	/**************************************************************
	 * Function:	db_fetch_row($result, $suppress_err_flag=0)
	 * Arguments:	$result	- Valid result set
	 * Returns:		row		- Record as row variables
	 * Notes:
	 *	- none
	**************************************************************/
	function db_fetch_row($result, $suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		$db_coin->db_connect_check();
		return $db_coin->db_fetch_row($result);
	}


	/**************************************************************
	 * Function:	db_query_numrows($result, $suppress_err_flag=0)
	 * Arguments:	$result	- Valid result set
	 * Returns:		integer	- Number of rows in set
	 * Notes:
	 *	- none
	**************************************************************/
	function db_query_numrows($result, $suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		$db_coin->db_connect_check();
		return $db_coin->db_query_numrows($result);
	}


	/**************************************************************
	 * Function:	db_query_insertid($suppress_err_flag=0)
	 * Arguments:	none
	 * Returns:		integer	- Inserted record id
	 * Notes:
	 *	- none
	**************************************************************/
	function db_query_insertid($suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		return $db_coin->db_query_insertid();
	}


	/**************************************************************
	 * Function:	db_query_affected_rows( $suppress_err_flag=0)
	 * Arguments:	none
	 * Returns:		integer	- Inserted record id
	 * Notes:
	 *	- Do not do connect check or will always return false.
	**************************************************************/
	function db_query_affected_rows($suppress_err_flag=0)
	{
		global $db_coin;
		$db_coin->db_set_suppress_errors($suppress_err_flag);
		return $db_coin->db_query_affected_rows();
	}


	/**************************************************************
	 * Function:	db_query_count()
	 * Arguments:	none
	 * Returns:		integer	- query count
	 * Notes:
	 *	- Do not do connect check or will always return false.
	**************************************************************/
	function db_query_count()
	{
		global $db_coin;
		return $db_coin->db_query_count();
	}

	/**************************************************************
	 * Function:	db_query_strings()
	 * Arguments:	none
	 * Returns:		string	- query strings
	 * Notes:
	 *	- Do not do connect check or will always return false.
	**************************************************************/
	function db_query_strings()
	{
		global $db_coin;
		return $db_coin->db_query_strings();
	}

?>
