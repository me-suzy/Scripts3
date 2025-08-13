<?php
#######################################
# TOPSTORY BASIC Version 1.0 , Released: 02-08-2003 
# Copyright (C) 2001 MERT YALDIZ - mertyaldiz@superonline.com 
#
# This program is free software; you can redistribute it and/or 
# modify it under the terms of the GNU General Public License 
# as published by the Free Software Foundation; either version 2 
# of the License, or (at your option) any later version. 
#
# This program is distributed in the hope that it will be useful, 
# but WITHOUT ANY WARRANTY; without even the implied warranty of 
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
# GNU General Public License for details. 
#
# You should have received a copy of the GNU General Public License 
# along with this program; if not, write to the Free Software 
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.   
# 
#####################################

/**
 * DISPLAYING THE LATEST NEWS 
 * CALL FROM A STATIC HTML PAGE
 * WITH JAVASCRIPT
 */
include("inc/config.php");
$getTot=mysql_query("SELECT * FROM ".$tst["tbl"]["articles"]."");
$totalAnn=mysql_num_rows($getTot);
$getTop=mysql_query("SELECT id,heading,datePosted FROM ".$tst["tbl"]["articles"]." WHERE status='1'  ORDER BY id DESC LIMIT 0,".$tst["conf"]["topNewsList"]."");
if(!mysql_num_rows($getTop)<1) {
	?>
	document.write('<table class="text">');
	document.write('<tr><td class="heading"><? echo $tst["lang"]["latestHeadlinesHeader"] ?></td></tr>');
	document.write('<tr><td>');
	<?
	while($row=mysql_fetch_array($getTop)) {
		?>
		document.write('<a class="headlines" href="<? echo $tst["url"].'/fullStory.php?id='.$row['id'] ?>"><li><? echo $row['heading'] ?></li></a>');
		document.write('&nbsp;&nbsp;<span class="smalltext">(<? echo reformat_date($row['datePosted']) ?>)</span>');
		<?
		if(($row['datePosted'] + $tst["conf"]["laps"]) > time()) {
			?>
			document.write('<span class="heading">&nbsp;<i><? echo $tst["lang"]["new"] ?></i></span>');
			<?
			}
		echo"document.write('<br><br>');";
	}
	echo"document.write('</td></tr>');";
	echo"document.write('<tr><td>');";
	if($totalAnn > $tst["conf"]["topNewsList"]) {
		?>
		document.write('<a class="link1" href="<? echo $tst["url"] ?>/index.php"><? echo $tst["lang"]["moreArticles"] ?></a>');
		<?
	}
	?>
	document.write('</td></tr></table>');
	<?
}
?>