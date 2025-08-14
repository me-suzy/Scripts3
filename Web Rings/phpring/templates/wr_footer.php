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
global $ring_id,$ringconfig;
if ($ringconfig[ring_id] >0){
?>

<p align=center>
	<a href="index.php?ring=<?php echo $ring_id ?>">Ring home</a> | 
	<a href="index.php?ring=<?php echo $ring_id ?>">List sites</a> | 
	<a href="index.php?op=search&amp;ring=<?php echo $ring_id ?>">Search</a> | 
	<a href="index.php?op=spy&amp;ring=<?php echo $ring_id ?>">Search spy</a> | 
	<a href="index.php?op=random&amp;ring=<?php echo $ring_id ?>">Random site</a> |
	<a href="index.php?op=join&amp;ring=<?php echo $ring_id ?>">Add site</a> | 
	<a href="index.php?op=update&amp;ring=<?php echo $ring_id ?>">Update site</a> | 
	<a href="index.php?op=code&amp;ring=<?php echo $ring_id ?>">Get code</a> 
	<?php if ($_COOKIE[user] != "") { ?> 
		| <a href="index.php?op=logout&amp;ring=<?php echo $ring_id ?>">Logout</a>
	<?php } ?>
</p>

<?php
}
?>

</td></tr></table>
</td></tr></table>



