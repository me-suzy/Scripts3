<?php
/*******************************************************************\
*   File Name onews/link.php                                        *
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
<card id="news1" title="<? echo $wap_title ?>">
<do type="prev" label="Back"><prev/></do>
<p>
<? 
$id=$_GET["id"];
DB_connect($dbn,$host,$user,$pass);
$result = mysql_query("SELECT * FROM ".$dbpre."content WHERE id=$id");
while ($row = mysql_fetch_object($result))    {


$title = $row->title; 
$done = $row->introtext; 
$more = $row->fulltext;
if ($more==""){
$reon="";
}else{
$reon="<a href=\"more.php?id=$id\">...Read More</a>";}


      	$arrDateTime = explode(" ", $row->created);
      	$arrDate = explode("-", $arrDateTime[0]);
     	$arrTime = explode(":", $arrDateTime[1]);

	$time =	$row->created = strftime ("%d %b %Y", mktime ($arrTime[0],$arrTime[1],$arrTime[2],$arrDate[1],$arrDate[2],$arrDate[0])  );

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
$hmmm = "<b>$time<br />$title....</b><br/>$done<br/>$reon<br/>";

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
 <a href="<? echo "link.php?id=$id&amp;over=$tmp"?>">Back...</a> 
<?
};

}else{
$over=0;
};

print substr($hmmm,$over,$trim);
$over=$over+$trim;
if (strlen($done)>$over){
?>
 <a href="<?print "link.php?id=$id&amp;over=$over"?>">...Read on</a> 
<?
};
} else {
print $hmmm;
}; 
 }   
 ?> 
</p></card> </wml>