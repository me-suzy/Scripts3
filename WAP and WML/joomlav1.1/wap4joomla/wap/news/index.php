<?php
/*******************************************************************\
*   File Name news/index.php                                        *
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
<p align="center"><b>News Menu</b></p>
<p><small>
<? $now = date( "Y-m-d H:i:s", time() );
DB_connect($dbn,$host,$user,$pass);
$result =  mysql_query("SELECT * FROM ".$dbpre."content_frontpage ORDER BY ordering");
while ($row1 = mysql_fetch_object($result))    {
$cid = $row1->content_id;
$result2 =  mysql_query("SELECT * FROM ".$dbpre."content WHERE id=$cid AND (publish_up = '0000-00-00 00:00:00' OR publish_up <= NOW()) 	AND (publish_down = '0000-00-00 00:00:00' OR publish_down >= NOW())");
while ($row2 = mysql_fetch_object($result2))    { 
$title=$row2->title;
$title=eregi_replace("&","&amp;",$title); ?>
<a href="link.php?id=<? echo "$cid" ?>">: <? echo "$title" ?></a><br/>    
 <?  }  }  
mysql_free_result($result);?> 
</small></p></card> </wml>