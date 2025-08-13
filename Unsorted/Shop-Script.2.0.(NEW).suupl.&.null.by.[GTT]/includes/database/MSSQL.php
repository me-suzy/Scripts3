<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


//	database functions :: MS SQL


function db_connect($host,$user,$pass) //create connection
{
	return mssql_connect ($host,$user,$pass);
}

function db_select_db($name) //select database
{
	return mssql_select_db($name);
}

function db_query($s) //database query
{ 
	return mssql_query ($s);
}

function db_fetch_row($q) //row fetching
{
	return mssql_fetch_row($q);
}

function db_set_identity($table,$val = "OFF") //actual for MSSQL only
{
	return db_query("SET IDENTITY_INSERT $table $val");
}

function db_insert_id($gen_name = "") //id of last inserted record
				//$gen_name is used for InterBase
{
	$qr = db_query( "SELECT SCOPE_IDENTITY()" );
	$row = db_fetch_row($qr);
	return $row[0];	   
}

function db_error() //database error message
{
	return mssql_get_last_message();
}

?>