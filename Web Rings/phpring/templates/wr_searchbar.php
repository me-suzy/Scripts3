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

global $query;
echo "<p align=center><small>Search on:
				<a href=\"http://www.google.com/search?q=$query\">Google</a> | 
				<a href=\"http://search.yahoo.com/bin/query?p=$query\">Yahoo</a> | 
				<a href=\"http://www.plebius.org/odp.php?search=$query\">Open Directory</a> | 
				<a href=\"http://www.plebius.org/store.php?op=ShowResults&amp;keyword=$query&amp;mode=book\">Books</a> | 
				<a href=\"http://www.plebius.org/store.php?op=ShowResults&amp;keyword=$query&amp;mode=dvd\">DVD</a> | 
				<a href=\"http://www.plebius.org/store.php?op=ShowResults&amp;keyword=$query&amp;mode=electronics\">Electronics</a> | 
				<a href=\"http://www.plebius.org/store.php?op=ShowResults&amp;keyword=$query&amp;mode=music\">Music</a>
			</small></p>";

?>
