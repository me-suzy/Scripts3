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
global $ring_id,$ringconfig,$siteconfig;
?>

<html>
<head>
<title>PHP-Ring</title>
</head>
<body>

<h1><center>PHP-RING</center></h1>
<table border=0 cellpadding=0 cellspacing=0 bgcolor="#000000" width=800 align=center><tr><td>
<table border=0 cellpadding=7 cellspacing=1 bgcolor="#000000" width=100%>
<?php
// some ring stuff... careful with the PHP syntax

if ($ringconfig[ring_id] >0) {
echo '<tr><td bgcolor="#FFFFFF" width=200 valign=top>';

echo '<b>'.$ringconfig[ring_name].'</b><br><img src="'.$ringconfig[ring_img_url].'">';
echo "<br><br><img src=\"images/black_dot.gif\" width=150 height=1><br>";
echo '<b>Description:</b><br>';
echo $ringconfig[ring_description];
echo "<br><br><img src=\"images/black_dot.gif\" width=150 height=1><br>";
echo "<b>Ring stats:</b><br>";
echo "Number of sites: $ringconfig[ring_numsites]<br>";
echo "Hits sent to sites: $ringconfig[ring_hits_out]";
echo "<br><br><img src=\"images/black_dot.gif\" width=150 height=1><br>";
?>
<b>Menu</b><br>
	<big><strong>&middot;</strong></big> <a href="<?php echo $ringconfig[ring_url]; ?>">Ring home</a><br> 
	<big><strong>&middot;</strong></big> <a href="index.php?ring=<?php echo $ring_id ?>">List sites</a><br> 
	<big><strong>&middot;</strong></big> <a href="index.php?op=search&amp;ring=<?php echo $ring_id ?>">Search</a><br> 
	<big><strong>&middot;</strong></big> <a href="index.php?op=spy&amp;ring=<?php echo $ring_id ?>">Search spy</a><br> 
	<big><strong>&middot;</strong></big> <a href="index.php?op=random&amp;ring=<?php echo $ring_id ?>">Random site</a><br>
	<big><strong>&middot;</strong></big> <a href="index.php?op=join&amp;ring=<?php echo $ring_id ?>">Add site</a><br>
	<big><strong>&middot;</strong></big> <a href="index.php?op=update&amp;ring=<?php echo $ring_id ?>">Update site</a> <br> 
	<big><strong>&middot;</strong></big> <a href="index.php?op=code&amp;ring=<?php echo $ring_id ?>">Get code</a> 
	<?php if ($_COOKIE[user] != "") { ?> 
		<br><big><strong>&middot;</strong></big> <a href="index.php?op=logout&amp;ring=<?php echo $ring_id ?>">Logout</a>
	<?php } else { ?>
		<br><big><strong>&middot;</strong></big> <a href="index.php?op=reg&amp;ring=<?php echo $ring_id ?>">Register</a>
	<?php }

echo "<br><br><img src=\"images/black_dot.gif\" width=150 height=1><br>";
error_reporting(0); if ($siteconfig[amz_tag] != "no") { echo '<b>Related products</b><br>'; amzlite(); }echo '</td>'; } 

// now back to HTML
?>



<td bgcolor="#FFFFFF" valign=top>
<br>

<?php
if ($ringconfig[ring_id] >0){
?>

<center>
<form action=index.php method=GET style="margin-bottom:0">
<input type=hidden name=op value=search>
<input type=hidden name=ring value=<?php echo $ring_id ?>>
<input type=text name=q value=""> 
<input type=submit value="Search">
</form>
</center>

<?php
}
?>
