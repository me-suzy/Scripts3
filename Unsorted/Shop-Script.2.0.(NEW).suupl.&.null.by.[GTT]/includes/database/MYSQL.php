<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


//	database functions :: MySQL


function db_connect($host,$user,$pass) //create connection
{
	return mysql_connect($host,$user,$pass);
}

function db_select_db($name) //select database
{
	return mysql_select_db($name);
}

function db_query($s) //database query
{
	return mysql_query($s);
}

function db_fetch_row($q) //row fetching
{
	return mysql_fetch_row($q);
}

function db_set_identity($table) //actual for MSSQL only
{
	return 1;
}

function db_insert_id($gen_name = "") //id of last inserted record
				//$gen_name is used for InterBase
{
	return mysql_insert_id();
}

function db_error() //database error message
{
	return mysql_error();
}

?>