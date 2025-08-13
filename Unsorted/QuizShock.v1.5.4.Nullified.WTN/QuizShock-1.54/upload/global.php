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
error_reporting(7);
require('inc/config.inc' . $script_ext);
require('inc/lib.inc' . $script_ext);
require('inc/' . $TS_SCRIPTS['DB_CLASS']);
require('inc/' . 'ts_user_' . $C_OPTS['USER_MODULE'] . '.inc' . $script_ext);
$db = new db_sql($DB_INFO['HOSTNAME'], $DB_INFO['USERNAME'], $DB_INFO['PASSWORD'], $DB_INFO['DATABASE']);
if( !$db->connect() )
{
	echo $C_OPTS['DB_NO_CONNECT_ERROR_MSG'];
	exit;
}

if( !$db->select_db() )
{
	echo $C_OPTS['DB_NO_SELECT_ERROR_MSG'];
	exit;
}

$db->die_on_error = 1;

$ts_user = new ts_user();

$ts_user->validate();
$TEMPLATES = get_templates();
$OPTIONS = get_options();
if( $OPTIONS['SEND_NOCACHE_HEADERS'] )
{
	$date = gmdate("D, d M Y H:i:s") . " GMT";
	header("Last-Modified: $date");
	header("Expires: $date");
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: pre-check=0, post-check=0, max-age=0");

} // end if
if( $OPTIONS['ENABLE_GZIP_COMPRESSION'] )
{
	if( !@function_exists("ob_start") )
	{
		t_error_out("You have enabled GZIP page compression in your options, however the output buffering functions 
		(<b>ob_start()</b>) could not be found. Please ensure that you are using PHP 4.0.4 or greater (PHP says you are running <b>" . phpversion() . "</b>). 
		If you are not sure, contact your system administrator");
	}
	elseif( !@function_exists("ob_gzhandler") )
	{
		t_error_out("You have enabled GZIP page compression in your options, however the gzip compression functions (<b>ob_gzhandler()</b>) 
		could not be found. Please ensure that you are suing 4.0.4 or greater (PHP says you are running <b>" . phpversion() . "</b>) and have the ZLIB extension installed. 
		If you are not sure, contact your system administrator. ");
	}
	ob_start("ob_gzhandler");
}
if( !$OPTIONS['TRIVIA_ONLINE'] )
{
	if(!$ts_user->is_admin())
	{
		t_error_out($OPTIONS['OFFLINE_MESSAGE']);
	}
}

?>
