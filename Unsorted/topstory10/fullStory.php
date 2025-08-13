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
mysql_query("UPDATE ".$tst["tbl"]["articles"]." SET hits=hits+1");
$getData=mysql_query("SELECT * FROM ".$tst["tbl"]["articles"]." WHERE id='$id' ");
$data=mysql_fetch_object($getData);
?>
<table class="text" border="0" width="600" cellspacing="5">
<tr>
	<td class="heading"><? echo stripslashes($data->heading) ?></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td><? echo nl2br(stripslashes($data->body)) ?></td>
</tr>
<tr>
	<td>
	<br><br>
	<? 
	echo stripslashes($data->name).'&nbsp;'.stripslashes($data->surname).', '.stripslashes($data->country).'&nbsp;&nbsp;';
	echo '<span class="smalltext">('.reformat_date(stripslashes($data->datePosted)).')</span>';
	?>
	<tr>
	<td><a class="smallLink" href="mailto:<? echo stripslashes($data->email) ?>"><? echo stripslashes($data->email) ?></a></td>
</tr>
<tr>
	<td><a class="smallLink" href="<? echo stripslashes($data->website) ?>"><? echo stripslashes($data->website) ?></a></td>
	</tr>	
<tr>
	<td class="smalltext"><? echo $tst["lang"]["displayed"]." ".$data->hits." ".$tst["lang"]["times"] ?></td>
	</tr>

<tr>
	<td align="right"><a class="link1" href="<? echo $tst["url"] ?>/index.php"><? echo $tst["lang"]["back2Annc"] ?></a></td>
</tr>
</table>
<?
include($tst["footerfile"]);
?>