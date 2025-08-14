<?php
/*******************************************************************\
*   File Name menu.php                                              *
*   Date 15-11-2005                                                 *
*   For WAP4Joomla! WAP Site Builder                                *
*   Writen By Tony Skilton admin@media-finder.co.uk                 *
*   Version 1.1                                                     *
*   Copyright (C) 2005 Media Finder http://www.media-finder.co.uk   *
*   Distributed under the terms of the GNU General Public License   *
*   Please do not remove any of the information above               *
\*******************************************************************/
header("Content-Type: text/vnd.wap.wml");
echo"<?xml version=\"1.0\"?>"; ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN"
			"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
<?php include("config.php");
$database = new database();
DB_connect($dbn,$host,$user,$pass);
$query2 = "SELECT sum(hits) AS count FROM ".$dbpre."stats_agents WHERE type='1'";
$result2=$database->openConnectionWithReturn($query2);
$visits=0;
for ($count = 1; $row = mysql_fetch_row ($result2); ++$count)
{
	$visits = $visits + $row[0];
	$visits .= "<br/>".Visitors;
} ?>
<card id="menu" title="<? echo $wap_title ?>">
<p align="center">
<b><? echo ("$wap_title"); ?><br />Menu</b><br />
<a href="news/index.php">News</a><br />
<a href="onews/index.php">Other News</a><br />
<a href="weather.php">Weather</a><br />
<a href="contact.php">Contact</a><br />
<small><b><? echo "$visits" ?></b></small>
</p>
</card></wml>