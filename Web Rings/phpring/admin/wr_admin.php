<?php

/*
	PHP-Ring Webring System v0.9.1
	Author: Martin Kretzmann
	Last modified: October 31, 2004
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
include("../includes/wr_config.php"); error_reporting(E_ALL ^ E_NOTICE);
include("../pbl_db/db.php");
include("../includes/wr_functions.php");
include("adm_inc/adm_functions.php");
if (file_exists('../install.php')) {
die(errmsg(array('Please delete file install.php before continuing')));
}

if (defined("RING_OK_b3e")) {
error_reporting(E_ALL ^ E_NOTICE);
$cookie = $_COOKIE[admin];

if (($cookie == "" OR $_GET[stop] == 1 OR !is_admin($cookie)) && $_GET[op] != "login" && $_GET[op] != "logout") {
	include("../templates/wr_header.php");
	echo "Enter admin username and password to log in";
	if ($_POST OR $_GET[stop] == 1) {
		errmsg(array("Incorrect username or password."));
	}
	echo "<form action=\"$_SERVER[PHP_SELF]?op=login\" method=POST>";
	echo "<table border=0><tr><td>Username</td><td><input type=text name=uname></td></tr>";
	echo "<tr><td>Password</td><td><input type=password name=pass></td></tr>";
	echo "<tr><td colspan=2><input type=submit value=\"Log in\"></td></tr></table>";
} else {



function adminhome() {
	global $db;
	include("../templates/wr_header.php");
	admin_menu();
	$config = $db->sql_fetchrow($db->sql_query("SELECT site_numrings, site_numsites, site_hits_out FROM "._CONFIG_TABLE));
	$numusers = $db->sql_numrows($db->sql_query("SELECT user_id FROM "._USERS_TABLE));
	echo "<p><center>There are <b>$numusers</b> registered users.<p>There are <b>$config[site_numsites] sites</b> in <b>$config[site_numrings] rings</b> and they have received <b>$config[site_hits_out] hits</b>.<br><a href=wr_admin.php?op=resync>Update these numbers!</a></center><p>";
}


function adm_login() {
	$admin = base64_encode("$_POST[uname]:".md5($_POST[pass]).":".time());
	if (is_admin($admin)) {
		setcookie("admin","$admin",NULL,'/','',0);
		header("Location: wr_admin.php");
	} else {
		header("Location: wr_admin.php?stop=1");
	}
}

function adm_logout() {
	setcookie("admin",NULL,NULL,'/','',0);
	header("Location: wr_admin.php?nocache");
}

function admin_menu() {
     global $db;
     echo "<center><h2>Ring admin</h2></center>";
     $row = $db->sql_fetchrow($db->sql_query("SELECT * FROM "._CONFIG_TABLE));
echo "<br><center><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\r\n<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\r\n<input type=\"hidden\" name=\"business\" value=\"alsogut@hotmail.com\">\r\n<input type=\"hidden\" name=\"no_note\" value=\"1\">\r\n<input type=\"hidden\" name=\"currency_code\" value=\"USD\">\r\n<input type=\"hidden\" name=\"tax\" value=\"0\">\r\n<input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/x-click-but21.gif\" border=\"0\" name=\"submit\" alt=\"Make payments with PayPal - it's fast, free and secure!\">\r\n</form></center>";
     if ($row[site_url] == "" OR $row[site_name] == "" OR $row[site_admin_email] == "") {
	echo "<p align=center><b>It appears you have not yet configured your site.  Click <a href=wr_admin.php?op=config>here</a> to do it now</b></p>";
     }
echo "
<p align=center><b><a href=wr_admin.php>Admin menu</a>:</b>
<a href=wr_admin.php?op=config>Config</a> | 
<a href=wr_admin.php?op=editring>Rings admin</a> | 
<a href=wr_admin.php?op=editsite>Sites admin</a> | 
<a href=wr_admin.php?op=validate>Sites waiting approval</a> | 
<a href=wr_admin.php?op=edituser>User admin</a> | 
<a href=wr_admin.php?op=logout>Log out</a> | 
<a href=http://www.plebius.org/forum.php>Support forums</a><br>
</p>
";
     if ($row[serial] == "" OR !check_serial($row[serial])) {
	echo "<center><b><a href=\"http://scripts.plebius.org/\">Remove copyright notices!</a> or <a href=wr_admin.php?op=enter_serial>Enter serial #($row[serial])</a></b></center>";
     }
     switch($_GET[op]) {
	case "editring":
		$vars = array('ring_id','ring_name');
		show_list(_RINGS_TABLE, $vars);
		break;
	case "editsite":
		$vars = array('site_id','site_name');
		show_list(_SITES_TABLE, $vars);
		break;
	case "edituser":
		$vars = array('user_id','user_name');
		show_list(_USERS_TABLE, $vars);
		break;
     }
}

function show_list($table, $vars) {
    global $db;
    $start = ($_GET[nh] > 0) ? $_GET[nh] : 0;
    $prev  = $start - 10;
    $next  = $start + 10;
    $vars0 = $vars[0];
    $vars1 = $vars[1];
    if ($_GET[d_op] == "") {
		$result = $db->sql_query("SELECT * FROM $table LIMIT $start,10");
		echo "<br><table border=1 align=center>";
		echo "<tr><td>user_id</td><td>site_id</td><td>ring_id</td><td>$vars[1]</td><td>Op</td></tr>";
		while ($row = $db->sql_fetchrow($result)) {
			echo "<tr><td>$row[user_id]</td><td>$row[site_id]</td><td>$row[ring_id]</td><td>$row[$vars1]</td><td><a href=\"wr_admin.php?op=$_GET[op]&$vars0=$row[$vars0]&d_op=edit\">edit</a> | <a href=\"wr_admin.php?op=$_GET[op]&$vars0=$row[$vars0]&d_op=delete\">delete</a></tr>";
			$start++;
		}
		echo "</table>";
  	echo "<p align=center>";
  	echo ($prev >= 0) ? "<a href=wr_admin.php?op=$_GET[op]&amp;nh=$prev>Prev</a>" : 'Prev';
  	echo " | ";
  	echo ($next == $start) ? "<a href=wr_admin.php?op=$_GET[op]&amp;nh=$next>Next</a>" : 'Next';
  }
} 

function enter_serial() {
	include("../templates/wr_header.php");
	admin_menu();
	echo "<center>";
	echo "<p>Enter serial # you received in box below.<p>";	
	echo "<form action=wr_admin.php?op=save_serial method=POST>";
	echo "<table border=0><tr><td>Serial:</td><td><input type=text name=serial></td></tr>";
	echo "<tr><td colspan=2 align=center><input type=submit value=\"Save\"></td></tr></table></form>";
	echo "</center>";
}

function config() {
	include("../templates/wr_header.php");
	admin_menu();
	if (!$_POST) {
		global $db;
		configform($db->sql_fetchrow($db->sql_query("SELECT * FROM "._CONFIG_TABLE)));
        } else {
		if (configwrite($_POST)) {
			echo "<p><b>Changes saved</b><p>";
		} else {
			echo "<p>There was a problem. Go back and fix it<p>";
		}
        }
}

function ringform($mode, $vars) {
        if ($vars[ring_isactive] == 1) {
		$active[1] = " selected";
  	} else {
		$active[0] = " selected";
	}
	echo "<center>";
	echo "<form action=\"wr_admin.php?op=editring&d_op=save$mode\" method=POST>";
	echo "<input type=hidden name=ring_id value=\"$vars[ring_id]\">";
	echo "<table border=0>";
	echo "<tr><td>Ring name</td><td><input type=text name=ring_name value=\"$vars[ring_name]\"></td></tr>";
	echo "<tr><td>Ring url<br><small>Location of index.php?ring=xxx or an off-site page</td><td><input type=text name=ring_url value=\"$vars[ring_url]\"></td></tr>";
	echo "<tr><td>Ring is active</td><td><select name=ring_isactive><option value=0 $active[0]>No<option value=1 $active[1]>Yes</td></tr>";
	echo "<tr><td>Ring email</td><td><input type=text name=ring_email value=\"$vars[ring_email]\"></td></tr>";
	echo "<tr><td>Image URL</td><td><input type=text name=ring_img_url value=\"$vars[ring_img_url]\"></td></tr>";
	echo "<tr><td colspan=2>Ring join text.  Put any rules here<br><textarea cols=50 rows=5 name=ring_join_text>$vars[ring_join_text]</textarea></td></tr>";
	echo "<tr><td colspan=2>Ring description<br><textarea cols=50 rows=5 name=ring_description>$vars[ring_description]</textarea></td></tr>";
	echo "<tr><td colspan=2>Ring keywords - separate with comma<br><textarea cols=50 rows=3 name=ring_keywords>$vars[ring_keywords]</textarea></td></tr>";
	echo "<tr><td colspan=2><input type=submit value=\"Save ring\"></td></tr>";
	echo "</table></form>";
	echo "</center>";
}
function siteform($mode, $vars) {
        if ($vars[site_isactive] == 1) {
		$active[1] = " selected";
  	} else {
		$active[0] = " selected";
	}
	echo "<center>";
	echo "<form action=\"wr_admin.php?op=editsite&d_op=save$mode\" method=POST>";
	echo "<input type=hidden name=site_id value=$vars[site_id]>";
	echo "<table border=0>";
	echo "<tr><td>Site name</td><td><input type=text name=site_name value=\"$vars[site_name]\"></td></tr>";
	echo "<tr><td>Site url</td><td><input type=text name=site_url value=\"$vars[site_url]\"></td></tr>";
	echo "<tr><td>Site is active</td><td><select name=site_isactive><option value=0 $active[0]>No<option value=1 $active[1]>Yes</td></tr>";
	echo "<tr><td colspan=2>Site description<br><textarea cols=50 rows=5 name=site_description>$vars[site_description]</textarea></td></tr>";
	echo "<tr><td colspan=2>Site keywords<br><textarea cols=50 rows=3 name=site_keywords>$vars[site_keywords]</textarea></td></tr>";
	echo "<tr><td>Hits</td><td><input type=text name=site_hits_out value=\"$vars[site_hits_out]\"></td></tr>";
	echo "<tr><td>Ring ID</td><td><input type=text name=ring_id value=\"$vars[ring_id]\"></td></tr>";
	echo "<tr><td colspan=2><input type=submit value=\"Save site\"></td></tr>";
	echo "</table></form>";
	echo "</center>";
}
function userform($mode, $vars) {
	echo "<center>";
	echo "<form action=\"wr_admin.php?op=edituser&d_op=save$mode\" method=POST>";
	echo "<input type=hidden name=user_id value=$vars[user_id]>";
	echo "<table border=0>";
	echo "<tr><td>Username:</td><td>";
	if ($mode == "new") {
		echo "<input name=user_name value=\"$vars[user_name]\">";
	} else {
		echo "<b>$vars[user_name]</b></td></tr>";
	}
	echo "<tr><td>Password</td><td><input type=password name=user_pass value=\"\"></td></tr>";
	echo "<tr><td>Email</td><td><input type=text name=user_email value=\"$vars[user_email]\"></td></tr>";
	echo "<tr><td colspan=2 align=center><input type=submit value=\"Save user\"></td></tr>";
	echo "</table></form>";
	echo "</center>";
}
function savesite($mode, $vars) {
	global $db;
	if ($mode == "saveedit") {
		foreach($vars as $key=>$val) {
			$db->sql_query("UPDATE "._SITES_TABLE." SET $key = '".addslashes_data($val)."' WHERE site_id='$vars[site_id]'");
		}
	} else if ($mode == "savenew") {
		$time = time();
		$db->sql_query("INSERT INTO "._SITES_TABLE." VALUES ( NULL, ".
				"'".intval($vars[ring_id])."', '0', '$time', '".
				addslashes_data($vars[site_name])."', '".addslashes_data($vars[site_url])."',  '".
				addslashes_data($vars[site_description])."', '".addslashes_data($vars[site_keywords])."',  '".
				intval($vars[site_hits_out])."', '".intval($vars[site_isactive]) ."')") 
				or  die(errmsg($db->sql_error()));
		$db->sql_query("UPDATE "._CONFIG_TABLE." SET site_numsites = site_numsites + 1");
		$db->sql_query("UPDATE "._RINGS_TABLE." SET ring_numsites = ring_numsites + 1 WHERE ring_id = $vars[ring_id]");
	} else {
		echo "<b><center><font color=red>There was some sort of problem.</font></center></b>";
	}
}

function savering($mode, $vars) {
	global $db;
	if ($mode == "saveedit") {
		foreach($vars as $key=>$val) {
			$db->sql_query("UPDATE "._RINGS_TABLE." SET $key = '".addslashes_data($val)."' WHERE ring_id='$vars[ring_id]'");
		}
	} else if ($mode == "savenew") {
		$time = time();
		$db->sql_query("INSERT INTO "._RINGS_TABLE." VALUES ( NULL, ".
				"'0', '$time', '$time','0','0','".addslashes_data($vars[ring_keywords])."', '".
				addslashes_data($vars[ring_description])."', $vars[ring_isactive], ".
				"'0', '".addslashes_data($vars[ring_join_text])."', '".addslashes_data($vars[ring_url])."', '".
				addslashes_data($vars[ring_name])."', '".addslashes_data($vars[ring_email])."',  '".
				addslashes_data($vars[ring_img_url])."' )") or  errmsg($db->sql_error());
		$db->sql_query("UPDATE "._CONFIG_TABLE." SET site_numrings = site_numrings + 1");
	} else {
		echo "<b><center><font color=red>There was some sort of problem.</font></center></b>";
	}
}
function saveuser($mode, $vars) {
	global $db;
	$vars[user_pass] = md5($_POST[user_pass]);
	if ($mode == "saveedit") {
		foreach($vars as $key=>$val) {
			$db->sql_query("UPDATE "._USERS_TABLE." SET $key = '".addslashes_data($val)."' WHERE user_id='$vars[user_id]'");
		}
	} else if ($mode == "savenew") {
		$time = time();
		if ($db->sql_numrows($db->sql_query("SELECT user_id FROM "._USERS_TABLE." WHERE LOWER(user_name) = '".addslashes_data(strtolower($vars[user_name]))."'")) > 0) {
			include('../templates/wr_header.php');
			admin_menu();
			echo '<center>';
			errmsg(array('Username already exists'));
			echo '</center>';
			include('../templates/wr_footer.php');
			exit;			
		} else {
			$db->sql_query("INSERT INTO "._USERS_TABLE." VALUES ( NULL, ".
				"'".addslashes_data($vars[user_name])."', '".
				addslashes_data($vars[user_pass])."', $time, ".
				"'".addslashes_data($vars[user_email])."', $time )") or  errmsg($db->sql_error());
		}
	} else {
		echo "<b><center><font color=red>There was some sort of problem.</font></center></b>";
	}
}

function edituser() {
	global $db;
	$d_op = ($_POST[d_op] != "") ? $_POST[d_op] : $_GET[d_op];
	$user_id = ($_POST[user_id] != "") ? intval($_POST[user_id]) : intval($_GET[user_id]);

	if ($d_op == "savenew" OR $d_op == "saveedit") {
		saveuser("$d_op", $_POST);
		header("Location: wr_admin.php?op=edituser");
	} else if ($d_op == "delete") {
		include("../templates/wr_header.php");
		admin_menu();
		echo "<br><center><b>Are you sure you want to permanently delete this user and all of their sites and webrings?</b><p>";
		echo "<a href=wr_admin.php?op=edituser&amp;d_op=deleteconfirm&amp;user_id=$user_id>Yes</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href=wr_admin.php?op=edituser>No</a><p><br></center>";
	} else if ($d_op == "deleteconfirm") {
		$db->sql_query("DELETE FROM "._USERS_TABLE." WHERE user_id=$user_id LIMIT 1");
		header("Location: wr_admin.php?op=edituser");
	} else {
		include("../templates/wr_header.php");
		admin_menu();
		echo "<center><p><b>Edit users</b><p>";
		echo "</center>";
		if ($d_op == "") {
			$d_op = "new";
			echo "<p align=center>Add new user</p>";
		}
		echo userform($d_op, $db->sql_fetchrow($db->sql_query("SELECT * FROM "._USERS_TABLE." WHERE user_id = $user_id")));
	}
}

function editsite() {
	global $db;
	$d_op = ($_POST[d_op] != "") ? $_POST[d_op] : $_GET[d_op];
	$site_id = ($_POST[site_id] != "") ? intval($_POST[site_id]) : intval($_GET[site_id]);

	if ($d_op == "savenew" OR $d_op == "saveedit") {
		savesite("$d_op", $_POST);
		header("Location: wr_admin.php?op=editsite");
	} else if ($d_op == "delete") {
		include("../templates/wr_header.php");
		admin_menu();
		echo "<br><center><b>Are you sure you want to permanently delete this site?</b><p>";
		echo "<a href=wr_admin.php?op=editsite&amp;d_op=deleteconfirm&amp;site_id=$site_id>Yes</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href=wr_admin.php?op=editsite>No</a><p><br></center>";
	} else if ($d_op == "deleteconfirm") {
		$sitenum = $db->sql_numrows($db->sql_query("DELETE FROM "._SITES_TABLE." WHERE site_id=$site_id"));
		header("Location: wr_admin.php?op=editsite");
	} else {
		include("../templates/wr_header.php");
		admin_menu();
		echo "<center><p><b>Edit sites</b><p>";
		echo "</center>";
		if ($d_op == "") {
			$d_op = "new";
			echo "<p align=center>Add new site</p>";
		}
		echo siteform($d_op, $db->sql_fetchrow($db->sql_query("SELECT * FROM "._SITES_TABLE." WHERE site_id = $site_id")));
	}
}

function editring() {
	global $db,$ring_id;
	$d_op = ($_POST[d_op] != "") ? $_POST[d_op] : $_GET[d_op];
	$ring_id = ($_POST[ring_id] != "") ? intval($_POST[ring_id]) : intval($_GET[ring_id]);

	if ($d_op == "savenew" OR $d_op == "saveedit") {
		savering("$d_op", $_POST);
		header("Location: wr_admin.php?op=editring");		
	} else if ($d_op == "delete") {
		include("../templates/wr_header.php");
		admin_menu();
		echo "<br><center><b>Are you sure you want to permanently delete this ring and all its sites?</b><p>";
		echo "<a href=\"wr_admin.php?op=editring&d_op=deleteconfirm&ring_id=$ring_id\">Yes</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href=wr_admin.php?op=editring>No</a><p><br></center>";
	} else if ($d_op == "deleteconfirm") {
		$db->sql_query("DELETE FROM "._RINGS_TABLE." WHERE ring_id=$ring_id");
		$db->sql_query("DELETE FROM "._SITES_TABLE." WHERE ring_id=$ring_id");
		header("Location: wr_admin.php?op=editring");
	} else {
		include("../templates/wr_header.php");
		$ring_id = intval($_POST[ring_id]);
		if ($ring_id == "" OR $ring_id == 0) {
			$ring_id = intval($_GET[ring_id]);
		}
		admin_menu(); 
		echo "<center><p><b>Edit rings</b><p>";
		echo "</center>";
		if ($ring_id == "" OR $ring_id == 0) {
			$d_op = "new";
			echo "<p align=center>Add new ring</p>";
		}
		echo ringform($d_op, $db->sql_fetchrow($db->sql_query("SELECT * FROM "._RINGS_TABLE." WHERE ring_id=$ring_id ")));
	}
}

function resync() {
	global $db;
	$numsites = $db->sql_numrows($db->sql_query("SELECT site_id FROM "._SITES_TABLE." WHERE site_isactive=1")); 
	$numrings = $db->sql_numrows($db->sql_query("SELECT ring_id FROM "._RINGS_TABLE));
	$db->sql_query("UPDATE "._CONFIG_TABLE." SET site_numsites = $numsites, site_numrings = $numrings ");
	$res = $db->sql_query("SELECT ring_id FROM "._RINGS_TABLE);
	while ($row = $db->sql_fetchrow($res)) {
		$numsites = $db->sql_numrows($db->sql_query("SELECT site_id FROM "._SITES_TABLE." WHERE ring_id = $row[ring_id]"));
		$db->sql_query("UPDATE "._RINGS_TABLE." SET ring_numsites = $numsites");
	}
	header("Location: wr_admin.php");
}

function validate(){
	global $db;
	include("../templates/wr_header.php");
	admin_menu();
	if ($_GET[d_op] == "approve" && $_GET[site_id] >0) {
		$db->sql_query("UPDATE "._SITES_TABLE." SET site_isactive = 1 WHERE site_id=$_GET[site_id]");
		echo "<p align=center><b>Site activated</b></p>";
	}
	$result = $db->sql_query("SELECT * FROM "._SITES_TABLE." WHERE site_isactive=0");
	$num = $db->sql_numrows($result);
	echo "<p align=center><b>There are $num sites awaiting validation.</b></p>";
	echo "<table border=1 align=center><tr bgcolor=gray><td>Ring</td><td>Name</td><td>Desc</td><td>Keywords</td><td>URL</td><td>op</td></tr>";
	while($row=$db->sql_fetchrow($result)) {
		echo "<tr><td>$row[ring_id]</td><td><a href=$row[site_url]>$row[site_name]</a> </td><td>$row[site_description] </td><td> $row[site_keywords] </td><td> $row[site_url]  </td><td> ";
		echo "<small><a href=wr_admin.php?op=validate&d_op=approve&site_id=$row[site_id]>Approve</a> | <a href=wr_admin.php?op=editsite&site_id=$row[site_id]>Edit</a> | <a href=wr_admin.php?op=editsite&d_op=delete&site_id=$row[site_id]>Delete</a></small></td></tr>";
	}
	echo "</table>";
	include("../templates/wr_footer.php");
}


switch($_GET[op]) {

	case "login":
		adm_login();
		break;

	case "logout":
		adm_logout();
		break;

	case "enter_serial":
		enter_serial();
		break;

	case "save_serial":
		include("../templates/wr_header.php");
		admin_menu();
		save_serial();
		break;

	case "config":
		config();
		break;

	case "editring":
		editring();
		break;

	case "editsite":
		editsite();
		break;

	case "edituser":
		edituser();
		break;

	case "resync":
		resync();
		break;

	case "validate":
		validate();
		break;

	default:
		adminhome();
		break;

}

















}
} else {
	echo "You have either broken the script or have illegally attempated to remove copyright information.  To get the script working again, undo whatever it is you did.";
}

include("../templates/wr_footer.php");

eval(base64_decode("aWYgKCFkZWZpbmVkKCJSSU5HX09LX2IzZSIpICYmICFkZWZpbmVkKCJSSU5HX09LX2IyZSIpKSB7DQogICAgZWNobyAiPG1ldGEgaHR0cC1lcXVpdj1yZWZyZXNoIGNvbnRlbnQ9XCIwO1VSTD1odHRwOi8vd3d3LnBsZWJpdXMub3JnL2NnaS1iaW4vcGF5cGFsLnBsXCI+IjsNCn0gZWxzZSBpZiAoZGVmaW5lZCgiUklOR19PS19iM2UiKSkgew0KICAgIGIzZSgpOw0KfSBlbHNlIGlmIChkZWZpbmVkKCJSSU5HX09LX2IyZSIpKSB7DQogICAgYjJlKCk7DQp9IGVsc2Ugew0KICAgIGVjaG8gIjxwIGFsaWduPWNlbnRlcj5Qb3dlcmVkIGJ5IDxhIGhyZWY9XCJodHRwOi8vc2NyaXB0cy5wbGViaXVzLm9yZy9cIj5QSFAtUmluZzwvYT4gJmNvcHk7IDIwMDQgPGEgaHJlZj1cImh0dHA6Ly93d3cucGxlYml1cy5vcmcvXCI+UGxlYml1cyBQcmVzczwvYT48L3A+IjsNCn0NCg0K"));
// end
?>
</body></html>
