<?php
/*******************************************************************\
*   File Name onews/cat.php                                         *
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
<p><small>
<?
$now = date( "Y-m-d H:i:s", time());
DB_connect($dbn,$host,$user,$pass);
$count = mysql_query("SELECT * FROM ".$dbpre."content WHERE catid=$id AND (publish_up = '0000-00-00 00:00:00' OR publish_up <= NOW()) 	AND (publish_down = '0000-00-00 00:00:00' OR publish_down >= NOW()) ORDER BY created DESC");  
 while ($row1 = mysql_fetch_object($count)) {
$countcat[$i] = $row1->catid;
$i++;}
mysql_free_result($count);
DB_connect($dbn,$host,$user,$pass);
$result = mysql_query("SELECT * FROM ".$dbpre."content WHERE catid=$id AND (publish_up = '0000-00-00 00:00:00' OR publish_up <= NOW()) 	AND (publish_down = '0000-00-00 00:00:00' OR publish_down >= NOW()) ORDER BY created DESC LIMIT $from, $no_list");  
 while ($row = mysql_fetch_object($result)) {
$catid[$t] = $row->catid;
$sid = $row->id;
$title = $row->title;
$t++; 
$title=eregi_replace("&","&amp;",$title); 
$title=eregi_replace("<br>","<br />",$title);
$title=eregi_replace("<strong>","<b>",$title);
$title=eregi_replace("</strong>","</b>",$title);
$title=eregi_replace("<B>","<b>",$title);
$title=eregi_replace("</B>","</b>",$title);
$title=eregi_replace("&nbsp;"," ",$title);
$atags = "<b><br />";
$title = strip_tags($title, $atags); ?>
<a href="link.php?id=<? echo "$sid" ?>">:<? echo " $title" ?></a><br/>
<? }
mysql_free_result($result); 
if ($i < $no_list){echo "";
}else{ $card = ($card+1);
$com = "<a href=\"#news$card\">more</a>";?>
</small></p>
<p><? echo "<br />$com";?></p>
</card> 
<card id="news<? echo $card ?>" title="<? echo $wap_title ?>">
<do type="prev" label="Back"><prev/></do>
<p> <small>
<?
$from = ($from+$t);
DB_connect($dbn,$host,$user,$pass);
$result = mysql_query("SELECT * FROM ".$dbpre."content WHERE catid=$id AND (publish_up = '0000-00-00 00:00:00' OR publish_up <= NOW()) 	AND (publish_down = '0000-00-00 00:00:00' OR publish_down >= NOW()) ORDER BY created DESC LIMIT $from, $no_list");  
 while ($row = mysql_fetch_object($result)) {
$catid[$t] = $row->catid;
$sid = $row->id;
$title = $row->title;
$t++; 
$title=eregi_replace("&","&amp;",$title); 
$title=eregi_replace("<br>","<br />",$title);
$title=eregi_replace("<strong>","<b>",$title);
$title=eregi_replace("</strong>","</b>",$title);
$title=eregi_replace("<B>","<b>",$title);
$title=eregi_replace("</B>","</b>",$title);
$title=eregi_replace("&nbsp;"," ",$title);
$atags = "<b><br />";
$title = strip_tags($title, $atags); ?>
<a href="link.php?id=<? echo "$sid" ?>">:<? echo " $title" ?></a><br/>
<?  }
mysql_free_result($result); }
?>
</small> </p>
</card>
</wml>