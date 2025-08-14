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

// do not change this stuff unless you know what you're doing
global $db, $ring_id, $site_id, $site,$ringconfig;
$ring = $ringconfig;
$site = $siteconfig;
// you can change the following code to make it look how you want, but be careful not to mess up the php stuff.

$codeform = "
<center>
<table width=300 border=0 cellpadding=0 cellspacing=1 bgcolor=black><tr><td>
<table width=300 border=0 cellpadding=4 cellspacing=1><tr><td align=center bgcolor=white>
	<a href=\"$siteconfig[site_url]?ring=$ring_id\" target=_top><img border=0 src=\"$ring[ring_img_url]\" alt=\"$ring[ring_name]\"></a><br><a href=\"$site[site_url]?ring=$ring_id\">$ring[ring_name]</a>
</td></tr><tr><td align=center bgcolor=white><small>
	<a href=\"$site[site_url]?op=prev&amp;site=$site_id&amp;ring=$ring_id\">Previous site</a> :
	<a href=\"$site[site_url]?op=random&amp;ring=$ring_id\">Random</a> :
	<a href=\"$site[site_url]?op=next&amp;site=$site_id&amp;ring=$ring_id\">Next site</a> :
	<a href=\"$site[site_url]?ring=$ring_id\">List sites</a>
</small></td></tr><tr><td align=center bgcolor=white>
	<small>Powered by <a href=\"http://scripts.plebius.org/\" target=_top>PHP-Ring</a></small>
</td></tr></table>
</td></tr></table>
<small><a href=\"http://www.plebius.org/\">Psychology News</a></small></center><p>
";

?>
