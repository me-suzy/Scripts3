<?php

/**************************************************************
 * File: 		Download Control File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: none
**************************************************************/

# Include Root File for package url/path and required files
	include ('../../coin_includes/session_set.php');

# File to load
	IF ($_GPV['id'] == '' OR $_GPV['id'] <= 0 ) {$id = 1;} ELSE {$id = $_GPV['id'];}

# Get file name of passed
	$result = '';
	$connection = mysql_connect($_DBCFG['dbhost'], $_DBCFG['dbuname'], $_DBCFG['dbpass']) or die ("Unable To Connect!");
	$query = "SELECT dload_filename FROM ".$_DBCFG['downloads']." WHERE dload_id = ".$id;
	$result = mysql_db_query($_DBCFG['dbname'], $query, $connection) or die ("Database Error On Query to get file.");

	IF (mysql_num_rows($result) == 1) {list($dload_filename) = mysql_fetch_row($result);}

# Build meta refresh url for file to load
	IF ($dload_filename != '') {$_url = 'http://www.phpcoin.com/downloads/'.$dload_filename;}

# Update counter
	$result	= '';
	$query	= "UPDATE ".$_DBCFG['downloads']." SET dload_count = dload_count + 1 WHERE dload_id = ".$id;
	$result	= mysql_db_query($_DBCFG['dbname'], $query, $connection) or die ("Database Error On Query to update file.");

# Call redirect
	IF (!$_url) {$_url = $_CCFG['_PKG_REDIRECT_ROOT'];}
	header("Location: $_url");
	exit();
?>
