<?php
include("header.php");
echo "$header";

$timecheck = mysql_fetch_array(mysql_query("SELECT date FROM st_links WHERE approved='Y' AND confirm='Y' ORDER BY linkid DESC LIMIT 1"));
$lastupdated = $timecheck["date"];

if($turncatliston=="Y"){
eval("\$catsbefore = \"".fetchtemplate('index_cats_before')."\";"); echo "$catsbefore";
$s = mysql_query("SELECT * FROM st_categories WHERE visable='Y' ORDER BY $orderedby $wayorder"); 
$catCount = 0;
$catNumber = mysql_num_rows($s);
while($r = mysql_fetch_array($s)){  
  $cid = $r["cid"];  
  $catname = $r["catname"];  
  ++$catCount; 
eval("\$catlinks = \"".fetchtemplate('index_cats_link')."\";"); echo "$catlinks";
if($catCount < $catNumber){ 
eval("\$catbetween = \"".fetchtemplate('index_cats_between')."\";"); echo "$catbetween";}}  
eval("\$catsafter = \"".fetchtemplate('index_cats_after')."\";"); echo "$catsafter";}
#####
eval("\$beforelinks = \"".fetchtemplate('index_beforelinks')."\";"); echo "$beforelinks";
$s2 = mysql_query("SELECT *, date_format(date,'$displaydate') AS formatted FROM st_links WHERE approved='Y' AND confirm='Y' ORDER BY date DESC LIMIT $limitlinks");
while($result2 = mysql_fetch_array($s2)){
$linkid = $result2["linkid"];
$date = $result2["formatted"];
$des = $result2["des"];
$numpics = $result2["numpics"];
$catid = $result2["catid"];
$url = $result2["url"];

$r3 = @mysql_fetch_array(mysql_query("SELECT * FROM st_categories WHERE cid=$catid"));
$cid = $r3["cid"];
$catname = $r3["catname"];

eval("\$thelink = \"".fetchtemplate('index_links')."\";"); echo "$thelink";}
eval("\$afterlinks = \"".fetchtemplate('index_afterlinks')."\";"); echo "$afterlinks";
echo "$footer"; eval("\$cpr = \"".fetchtemplate('index_copyright')."\";"); echo "$cpr";
include("footer.php");
?>
</BODY>
</HTML>