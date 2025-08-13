<?php
include("header.php");
echo "$header";

$sql1 = mysql_query("SELECT * FROM st_categories WHERE cid='$cid'");
$result1 = mysql_fetch_array($sql1);
$cid = $result1["cid"];
$catname = $result1["catname"];
$advert = $result1["advert"];

if($advert!==""){ eval("\$catsadvert = \"".fetchtemplate('cats_advert')."\";"); echo "$catsadvert";}
eval("\$catsname = \"".fetchtemplate('cats_catname')."\";"); echo "$catsname";
eval("\$catsbeforelinks = \"".fetchtemplate('cats_beforelinks')."\";"); echo "$catsbeforelinks";

$q = mysql_query("SELECT *, date_format(date,'$displaydate') AS formatted FROM st_links WHERE approved='Y' AND catid='$cid' ORDER BY linkid DESC LIMIT $archivelimit");
while($result2 = mysql_fetch_array($q)){
$linkid = $result2["linkid"];
$date = $result2["formatted"];
$des = $result2["des"];
$numpics = $result2["numpics"];
$catid = $result2["catid"];
$url = $result2["url"];

eval("\$catslink = \"".fetchtemplate('cats_links')."\";"); echo "$catslink";}
eval("\$catsafterlink = \"".fetchtemplate('cats_afterlinks')."\";"); echo "$catsafterlink";
if($advert!==""){ eval("\$catsadvert = \"".fetchtemplate('cats_advert')."\";"); echo "$catsadvert";}
echo "$footer"; eval("\$cpr = \"".fetchtemplate('index_copyright')."\";"); echo "$cpr";
?>
</BODY>
</HTML>