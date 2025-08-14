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

function addslashes_data ($data) {
	if (!ini_get(magic_quotes_gpc)) {
		$data = addslashes($data);
	}
	return $data;
}

function stripslashes_data ($data) {
	if (!ini_get(magic_quotes_gpc)) {
		$data = stripslashes($data);
	}
	return $data;
}

function make_data_safe ($var) {
	if (preg_match("/^[0-9]/", $var)) {
		return addslashes($var);
	} else {
		return intval($var);
	}
}

function errmsg($errs) {
	echo "<p><font color=red>The following errors occured when attempting to process your request:</font><ul>";
	foreach ($errs as $err) {
		echo "<li>$err";
 	}
	echo "</ul>";
	return false;
}

function amzlite() {
	global $db,$ringconfig,$siteconfig;
        if (strtolower($siteconfig[amz_tag])==strtolower("no")) {
		$file = "";
	} else {
		$db->sql_query("DELETE FROM "._AMZ_TABLE." WHERE time < ".time() - 604000);
		$words = split("/,/", $ringconfig[ring_keywords]);
	        $maxrand = count( $words ) - 1;
	       	srand((double)microtime()*1000000);
		$random = rand(0, $maxrand);
	        $random = intval($random);
	        $url = "http://www.plebius.org/api.php?op=amazon&keyword=".urlencode($words[$random])."&tag=$siteconfig[amz_tag]&mode=$siteconfig[amz_prods]";
		$amzcache = $db->sql_fetchrow($db->sql_query("SELECT cachetime, html FROM "._AMZ_TABLE." WHERE url='".md5($url)."'"));
		if ($amzcache[cachetime] == "") {
			$file = join('', file($url));
			$db->sql_query("INSERT INTO "._AMZ_TABLE." VALUES (".time().", '".md5($url)."', '".addslashes_data($file)."')");
		} else {
			$file = $amzcache[html];
		}
		echo $file;
	}
}


eval(base64_decode("ZGVmaW5lKCJSSU5HX09LX2IyZSIsIHRydWUpOw0KZnVuY3Rpb24gYjJlKCkgew0KICAgIGdsb2JhbCAkZGI7DQogICAgJHJvdyA9ICRkYi0+c3FsX2ZldGNocm93KCRkYi0+c3FsX3F1ZXJ5KCJTRUxFQ1Qgc2VyaWFsIEZST00gIi5fQ09ORklHX1RBQkxFKSk7DQogICAgaWYgKG1kNSgkcm93WydzZXJpYWwnXSkgIT0gImFiZjJiZjUyM2I3YjlmYjk1N2NiZDBjNGQzMTQ0ZmNmIikgew0KICAgICAgICBlY2hvICI8cCBhbGlnbj1jZW50ZXI+UG93ZXJlZCBieSA8YSBocmVmPVwiaHR0cDovL3NjcmlwdHMucGxlYml1cy5vcmcvXCI+UEhQLVJpbmc8L2E+IKkgMjAwNCA8YSBocmVmPVwiaHR0cDovL3d3dy5wbGViaXVzLm9yZy9cIj5QbGViaXVzIFByZXNzPC9hPi48L3A+IjsNCiAgICB9DQp9DQo="));
// end

?>
