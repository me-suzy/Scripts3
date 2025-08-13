<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


//	database functions :: INTERBASE


function db_connect($host,$user,$pass) //create connection
{
	$default_charset = "ISO8859_1"; //default character set of your database
	return ibase_connect($host,$user,$pass, $default_charset);
}

function db_select_db($name) //select database
{
	return true;
}

function db_query($s) //database query
{
	return ibase_query($s);
}

function db_fetch_row($q) //row fetching
{
	return ibase_fetch_row($q);
}

function db_set_identity($table) //actual for MSSQL only
{
	return 1;
}

function db_insert_id( $gen_name = "" ) //id of last inserted record
{
	$qr = db_query( "select GEN_ID($gen_name,0) from RDB\$Database" );
	$row = db_fetch_row( $qr );
	return $row[0];
}

function db_error() //database error message
{
	return ibase_errmsg();
}

?>