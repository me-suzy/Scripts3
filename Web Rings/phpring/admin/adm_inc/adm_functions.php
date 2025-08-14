<?php
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
if (!defined('IN_PHPRING')) {
	die("You can't access this file directly");
}

function configform($vars) {
	$modes = array('book','dvd','music','electronics');
        if ($vars[site_isactive] == 1) {
		$active[1] = " selected";
  	} else {
		$active[0] = " selected";
	}
	if ($vars[site_allow_newrings] == 1) {
		$newring[1] = " selected";
	} else {
		$newring[0] = " selected";
	}
	echo "<center>";
	echo "<p><b>Site config</b><p>";
	echo "<form action=wr_admin.php?op=config method=POST>";
	echo "<input type=hidden name=site_allow_newrings value=1>";
	echo "<table border=0>";
	echo "<tr><td>Site name</td><td><input type=text name=site_name value=\"$vars[site_name]\"></td></tr>";
	echo "<tr><td>Site url</td><td><input type=text name=site_url value=\"$vars[site_url]\"></td></tr>";
	echo "<tr><td>Site is active</td><td><select name=site_isactive><option value=0 $active[0]>No<option value=1 $active[1]>Yes</td></tr>";
	echo "<tr><td>Serial #</td><td><input type=text name=serial value=\"$vars[serial]\"></td></tr>";
	echo "<tr><td>Admin email</td><td><input type=text name=site_admin_email value=\"$vars[site_admin_email]\"></td></tr>";
	echo "<tr><td colspan=2>Amazon options*</td></tr>";
	echo "<tr><td>Amazon ID</td><td><input type=text name=amz_tag value=\"$vars[amz_tag]\"></td></tr>";
	echo "<tr><td>Product type</td><td><select name=amz_prods>";
	foreach ($modes as $mode) {
		echo "<option value=$mode>$mode";
	}
	echo "</select></td></tr>";
	echo "<tr><td colspan=2><input type=submit value=\"Save config\"></td></tr>";
	echo "</table></form>";
	echo "<p>* If you wish, you may enter your amazon associate ID to display products related to the keywords you provide for your webring.  You may choose the type of product you prefer to list.<p>";
	echo "PHP-ring will occasionally search the Plebius website for related Amazon products.  A portion of these (50% or fewer) will link to amazon using our associate ID, the rest will be directed to amazon using the ID you enter.  To turn this feature off, simply enter the word 'no' in the amazon ID box.  <b>Enter 'no' if your site promotes sexually explicit material or otherwise violates amazon's TOS.</b>";
	echo "<p>";
}

function configwrite($vars) {
	global $db;
	foreach ($vars as $key=>$val) {
		$db->sql_query("UPDATE "._CONFIG_TABLE." SET $key = '".addslashes_data($val)."'");
	}
	return true;
}

function is_admin($admin) {
	global $db;
	$arr = explode(":", stripslashes_data(base64_decode(stripslashes_data($admin))));
	if ($arr[0] == "" OR $arr[1] == "" OR time() - $arr[2] > 3600) {
		return false;
	}
	$row = $db->sql_fetchrow($db->sql_query("SELECT admin_pass FROM "._ADMINS_TABLE." WHERE admin_uname='$arr[0]'"));
	if ($row[admin_pass] == "" OR $row[admin_pass] != $arr[1]) {
		return false;
	} else if ($row[admin_pass] == $arr[1]) {
		return true;
	}
	return false;
}


eval(base64_decode("ZnVuY3Rpb24gY2hlY2tfc2VyaWFsKCRzZXJpYWwpIHsNCiAgICAgcmV0dXJuIG1kNSgkc2VyaWFsKSA9PSAiYWJmMmJmNTIzYjdiOWZiOTU3Y2JkMGM0ZDMxNDRmY2YiOw0KfQ0KDQo="));
// end
/*function save_serial() {
      global $db;
      if (!check_serial(intval(trim($_POST[serial])))) {
          errmsg(array("Serial number not valid"));
      } else {
          $serial_num = intval($_POST[serial]);
          $result = $db->sql_query("UPDATE "._CONFIG_TABLE." SET serial =$serial_num WHERE 1") or errmsg($db->sql_error());
	echo "<h1>$serial_num</h1>";
      }
}*/
eval(base64_decode("ZnVuY3Rpb24gc2F2ZV9zZXJpYWwoKSB7DQogICAgICBnbG9iYWwgJGRiOw0KICAgICAgaWYgKCFjaGVja19zZXJpYWwoaW50dmFsKHRyaW0oJF9QT1NUW3NlcmlhbF0pKSkpIHsNCiAgICAgICAgICBlcnJtc2coYXJyYXkoIlNlcmlhbCBudW1iZXIgbm90IHZhbGlkIikpOw0KICAgICAgfSBlbHNlIHsNCiAgICAgICAgICAkc2VyaWFsID0gaW50dmFsKHRyaW0oJF9QT1NUW3NlcmlhbF0pKTsNCiAgICAgICAgICBpZiAoJGRiLT5zcWxfcXVlcnkoIlVQREFURSAiLl9DT05GSUdfVEFCTEUuIiBTRVQgc2VyaWFsPSckc2VyaWFsJyIpKSB7DQogICAgICAgICAgICAgIGVjaG8gIjxiPjxjZW50ZXI+U2VyaWFsIHdhcyBzdWNjZXNzZnVsbHkgc2F2ZWQhPC9jZW50ZXI+PC9iPiI7DQogICAgICAgICB9IGVsc2Ugew0KICAgICAgICAgICAgIGVycm1zZygkZGItPnNxbF9lcnJvcigpKTsNCiAgICAgICAgIH0NCiAgICAgIH0NCn0NCg=="));
// end

?>
