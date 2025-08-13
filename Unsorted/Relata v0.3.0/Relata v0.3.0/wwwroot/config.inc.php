<?
//******************************************************************************
/*
GPL Copyright Notice

Relata
Copyright (C) 2001-2002 Stratabase, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
//******************************************************************************

// the root dir. You must include a trailing /
$_PHPLIB["basedir"] = "/usr/www/apache/htdocs/avinesh/relata-0.2.6/wwwroot/";

// this is the web dir. You must include a trailing /
$_PHPLIB["webdir"] = "http://www.hostname.tld/relata-0.2.6/wwwroot/";

// Database settings
$db_type = "mysql";	// 'pgsql' or 'mysql'
$db_name = "relata";
$db_host = "localhost";
$db_username = "root";
$db_password = "";

// SMTP host - leave as localhost unless mail server resides on external server
$smtp_host="localhost";

// Path to PHP binary
$php_path="/www/php/bin/php"; //path to PHP binary
// Windows 9x: "c://php//bin//php.exe"

// TABLE NAMES
$account		= "account";
$activity		= "activity";
$activity_link	= "activity_link";
$contact_opportunity = "contact_opportunity";

$contact 		= "contact";
$contact_account= "contact_account";
$contact_group	= "contact_group";
$contact_xfield	= "contact_xfield";
$extra_field	= "extra_field";
$group			= "groups";
// if you modify any of the above variables in this block, then you will have to manually modify
// contact/lib.php

$opportunity	= "opportunity";
$relata_user	= "relata_user";
$sales_stage	= "sales_stage";
$message = "message";
$blast_log = "blast_log";
$contact_message = "contact_message";

$group_message_vars = "group_message_vars";
// if you modify the table name of group_message_vars, then you will have to manually
// edit lib/group_email_class.php accordingly

//////////////// MORE STUFF YOU DONT HAVE TO MODIFY \\\\\\\\\\\\\\\\\\

$_PHPLIB["libdir"] = $_PHPLIB["basedir"] . "lib/phplib/";

require($_PHPLIB["basedir"] . "lib/prepend.php");

?>