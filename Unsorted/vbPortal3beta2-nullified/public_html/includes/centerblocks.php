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
$centerblocks_modules = '';
$dibc = opendir('includes/center/');
while($g = readdir($dibc)) {
	if(substr($g, -3, 3) == 'php') {
		include 'includes/center/' . $g;
	}
}
closedir($dibc);
function centerblocks() {
	global
		$pollsactive,
		$nuketable,
		$centerblocks_modules
		;
	
	$result = mysql_query("SELECT bid, bkey, title, content, url, position, weight, active, refresh, last_update, UNIX_TIMESTAMP(last_update) AS unix_update, templates FROM nuke_centerblocks WHERE active=1 ORDER BY weight");
	while($row = mysql_fetch_array($result)) {
        if(isset($centerblocks_modules[$row[bkey]])) {
			if(function_exists('nukecode')) {
				if($centerblocks_modules[$row[bkey]][support_nukecode]) {
					$row[content] = nukecode($row[content]);
				}
			}
            if ($row[bkey] == poll){
				$pollsactive = 1;
		    }
           $centerblocks_modules[$row[bkey]][func_display]($row);
		}
		else {
			$nuke_title = "Block Type $row[bkey] Not Found";
			$nuke_content = "The block type $row[bkey] doesn't seem to exisit.  Please check your includes/center/ directory.";
			eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
		}
	}
}
?>