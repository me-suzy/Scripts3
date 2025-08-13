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
include("inc/config.php");
include($tst["headerfile"]);
$getTot=mysql_query("SELECT * FROM ".$tst["tbl"]["articles"]."");
$totalAnn=mysql_num_rows($getTot);
$getTop=mysql_query("SELECT id,heading,datePosted FROM ".$tst["tbl"]["articles"]." WHERE status='1'  ORDER BY id DESC");
if(!mysql_num_rows($getTop)<1) {
	echo'<table class="text">';
	echo'<tr><td class="heading">'.$tst["lang"]["headlinesHeader"].'</td></tr>';
	echo'<tr><td>';
	while($row=mysql_fetch_array($getTop)) {
		echo'<a class="headlines" href="'.$tst["url"].'/fullStory.php?id='.$row['id'].'"><li>'.$row['heading'].'</a>';
		echo' &nbsp;&nbsp;<span class="smalltext">('.reformat_date($row['datePosted']).')</span>';
		if(($row['datePosted'] + $tst["conf"]["laps"]) > time()) {
			echo '<span class="heading">&nbsp;<i>'.$tst["lang"]["new"].'</i></span>';
		}
		echo"<br><br>";
	}
	echo'</td></tr>';
	echo'<tr><td>';
	echo'</td></tr></table>';
}
include($tst["footerfile"]);
?>