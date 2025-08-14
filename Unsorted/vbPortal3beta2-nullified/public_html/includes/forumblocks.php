<?php
 /***********************************************************************
 * vbPortal: CMS mod for vBulletin                                      
 * vBulletin is Copyright ©2000, 2001, Jelsoft Enterprises Limited.     
 * ===========================                                          
 * vbPortal by wajones                                                  
 * Copyright (c) 2001 by William A. Jones                               
 * http://www.phpportals.com                                            
 *
 * Based in part of the Advanced Blocks System
 * Copyright (c) 2001 Patrick Kellum (webmaster@quahog-library.com)
 * http://quahog-library.com/
 *
 * Based in part of the blocks system in PHP-Nuke
 * Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
 * http://phpnuke.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *****************************************************************************/
// load the block types
$forumblocks_modules = '';
$dib = opendir('includes/forum/');
while($f = readdir($dib)) {
	if(substr($f, -3, 3) == 'php') {
		include 'includes/forum/' . $f;
	}
}
closedir($dib);
function forumblocks($side) {
	global
		$nuketable,
		$block_title,
		$block_content,
	    $themesidebox,
		$forumblocks_modules,
		$block_side // give theme builders a little room to play :-)
	;
	$side = strtolower($side[0]);
	$block_side = $side;
	$result = mysql_query("SELECT bid, bkey, title, content, url, position, weight, active, refresh, last_update, UNIX_TIMESTAMP(last_update) AS unix_update, templates FROM nuke_forumblocks where position='$side' AND active=1 ORDER BY weight");
	while($row = mysql_fetch_array($result)) {
		if(isset($forumblocks_modules[$row[bkey]])) {
			if(function_exists('nukecode')) {
				if($forumblocks_modules[$row[bkey]][support_nukecode]) {
					$row[content] = nukecode($row[content]);
				}
			}
			$forumblocks_modules[$row[bkey]][func_display]($row);
		}
		else {
			$block_title = "Block Type $row[bkey] Not Found";
			$block_content = "The block type $row[bkey] doesn't seem to exisit.  Please check your includes/forum/ directory.";
			eval("\$themesidebox .= \"".gettemplate("P_themesidebox_left")."\";");
		}
	}
}
?>