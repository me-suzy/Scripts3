<?php
/************************************************************************/
/* BCB Spider Tracker: Simple Search Engine Bot Tracking                */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004 by www.bluecollarbrain.com                        */
/* http://bluecollarbrain.com                                           */
/*                                                                      */
/* This program is free software. You may use it as you wish.           */
/*   This File: spider_config.php                                       */ 
/*   This file holds your MySQL database connection info.               */
/*   I suggest you leave this file here, but you can move it if you     */
/*   want - just change the other files where they require this file.   */
/*                                                                      */
/************************************************************************/
// Edit these to reflect your database setup
$username="root";					// MySQL username
$pass="";     					// MySQL Password
$db="test";							// MySQL database
$db_host="localhost"; 						//MySQL Host
$tablename = "bcb_spider";  	       	// change this value if you rename your db table.

// Don't edit below this line -----------------------------------------------------------------------------
// Connect to MySQL Database
$connSpider = mysql_connect("$db_host", "$username", "$pass") or die("Invalid server or user."); 	
mysql_select_db("$db", $connSpider);

?>
