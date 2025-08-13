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

///////////////////////////////////////////////////////////////////////////////
// DATABASE INFORMATION
///////////////////////////////////////////////////////////////////////////////

// - DATABASE CONNECTION INFO -
// THIS IS WHAT YOU NEED TO EDIT TO SET UP THE PROGRAM

$DB_INFO['HOSTNAME'] = "localhost";	// the host your database is on. Usually "localhost" (if on the same server as your site).
$DB_INFO['USERNAME'] = "triviash_ts";	// username to use when logging into the database
$DB_INFO['PASSWORD'] = "733green";	// password to use when logging into the database
$DB_INFO['DATABASE'] = "triviash_ts";	// database the program will use

///////////////////////////////////////////////////////////////////////////////
// OPTIONS
///////////////////////////////////////////////////////////////////////////////

// Which user module to use. 
// "standard" will use QuizShock's built-in user database, "vbulletin" will allow
// TriviaShock to share your vBulletin member database
$C_OPTS['USER_MODULE'] = 'standard';

// Whether or not to use MySQL's HEAP tables (memory based tables) during game sessions.
// During game sessions, the questions which will be given to the player are stored in a temporary table
// MySQL's memory-based HEAP tables are a very good option for this and are much faster than disk tables
// If you have problems with HEAP tables, set this option to 0 to use regular disk-based MyISAM tables
$C_OPTS['USE_HEAP_TABLES'] = 1;

// Whether or not to print database errors on the screen
$C_OPTS['SHOW_DB_ERRORS'] = 1;

// Email when there is a database query error?
$C_OPTS['EMAIL_ON_ERROR'] = 1;

// Where to email on errors
$C_OPTS['ERROR_EMAIL'] = "user@youdomain.c0m";

// Message to display to user when a connection to the database cannot be made
$C_OPTS['DB_NO_CONNECT_ERROR_MSG'] = "<br><br><center><table cellspacing=0 cellpadding=0 border=0 width=500><tr><td bgcolor=\"#000000\">
					<table cellspacing=1 cellpadding=4 border=0 width=\"100%\">
					<tr><td bgcolor=\"#000000\">
					<font size=2 face=\"verdana,arial,helvetica\" color=\"#FFFFFF\">
					<b>QuizShock Database Error</b>
					</td></tr>
					
					<tr><td bgcolor=\"#FFFFFF\" align=center><font size=2 face=\"verdana,arial,helvetica\" color=\"#000000\">
					There was an error connecting to the database. You can try again by reloading this
					page or you can check back later.
					</td></tr></table>
					</td></tr></table></center>";

// Message to display to user when a connection to the database cannot be made
$C_OPTS['DB_NO_SELECT_ERROR_MSG'] = "<br><br><center><table cellspacing=0 cellpadding=0 border=0 width=500><tr><td bgcolor=\"#000000\">
					<table cellspacing=1 cellpadding=4 border=0 width=\"100%\">
					<tr><td bgcolor=\"#000000\">
					<font size=2 face=\"verdana,arial,helvetica\" color=\"#FFFFFF\">
					<b>QuizShock Database Error</b>
					</td></tr>
					
					<tr><td bgcolor=\"#FFFFFF\" align=center><font size=2 face=\"verdana,arial,helvetica\" color=\"#000000\">
					There was an error selecting the database. You can try again by reloading this
					page or you can check back later.
					</td></tr></table>
					</td></tr></table></center>";
					

// Message to display to user when a connection to the database cannot be made
$C_OPTS['DB_QUERY_ERROR_MSG'] = "<br><br><center><table cellspacing=0 cellpadding=0 border=0 width=500><tr><td bgcolor=\"#000000\">
					<table cellspacing=1 cellpadding=4 border=0 width=\"100%\">
					<tr><td bgcolor=\"#000000\">
					<font size=2 face=\"verdana,arial,helvetica\" color=\"#FFFFFF\">
					<b>QuizShock Database Error</b>
					</td></tr>
					
					<tr><td bgcolor=\"#FFFFFF\" align=center><font size=2 face=\"verdana,arial,helvetica\" color=\"#000000\">
					There was an error querying the database. You can try again by reloading this
					page or you can check back later.
					</td></tr></table>
					</td></tr></table></center>";
					
?>