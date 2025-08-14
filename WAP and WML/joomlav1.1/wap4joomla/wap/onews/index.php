<?php
/*******************************************************************\
*   File Name onews/index.php                                       *
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
<?php include("../config.php"); ?>
<card id="news1" title="<? echo $wap_title ?>">
<do type="prev" label="Back"><prev/></do>
<p align="center"><b>News Categories</b></p>
<p align="center"><small>
<? DB_connect($dbn,$host,$user,$pass);
$result = mysql_query("SELECT * FROM ".$dbpre."categories where section='1' and published=1 ORDER BY ordering");
while ($row = mysql_fetch_object($result))	 {
$cid = $row->id;
$catname = $row->title;
$catname=eregi_replace("&","&amp;",$catname); ?>
<a href="cat.php?id=<? echo "$cid" ?>"><? echo "$catname" ?></a><br/>    
 <?      }
mysql_free_result($result);?> 
</small></p></card> </wml>