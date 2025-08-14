<?php
/*******************************************************************\
*   File Name onews/more.php                                        *
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
<? include("../config.php"); ?>


<? 
$id=$_GET["id"];
DB_connect($dbn,$host,$user,$pass);
$result = mysql_query("SELECT * FROM ".$dbpre."content WHERE id=$id");
while ($row = mysql_fetch_object($result))    {


$title = $row->title; 
$done = $row->fulltext; 

?>
<card id="news1" title="<? echo $title ?>">
<do type="prev" label="Back"><prev/></do>
<p>
<?

$done=eregi_replace("&nbsp;"," ",$done);
$done=eregi_replace("&","&amp;",$done);
$done=eregi_replace("<BR>"," <br />",$done);
$done=eregi_replace("<br>","<br />",$done);
$done=eregi_replace("</p>","<br />",$done);
$done=eregi_replace("<strong>","<b>",$done);
$done=eregi_replace("</strong>","</b>",$done);
$done=eregi_replace("<B>","<b>",$done);
$done=eregi_replace("</B>","</b>",$done);
$done=eregi_replace("{mosimage}"," ",$done);
$title=eregi_replace("&","&amp;",$title);
$atags = "<b><br />";
$done = strip_tags($done, $atags);
$hmmm = "$done<br/>";

if (strlen($done)>$trim){
$wellover=substr($done,$trim+$over,1);
while($wellover!="\n"){
$wellover=substr($done,$trim+$over,1);
$trim=$trim-1;
};
$trim++;
if (isset($over)){
if ($over>=$trim){
$tmp=$over-$trim;
?>
 <a href="<? echo "more.php?id=$id&amp;over=$tmp"?>">Back...</a> 
<?
};

}else{
$over=0;
};

print substr($hmmm,$over,$trim);
$over=$over+$trim;
if (strlen($done)>$over){
?>
 <a href="<?print "more.php?id=$id&amp;over=$over"?>">...Read on</a> 
<?
};
} else {
print $hmmm;
}; 
 }   
 ?> 
</p></card> </wml>