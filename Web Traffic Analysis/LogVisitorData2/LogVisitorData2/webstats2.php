Download the full script here
<?php
/*
http://www.iamskill.com

Include this file in any pages you want to track stats for.

PHP script to track the data of a visitor to a page on your website, tracks their ip, the page they're on, the time of visit & the referring url from where they came from.. Basically just do an include 'webstats2.php'; on any webpage you want to track visitor data. To view all the data just add ?skill_mode=2 to the file name e.g. http://youdomain.com/webstats2.php?skill_mode=2 & it will output the data of every visitor to your site. There's also a nice little paging system so you're not bombarded with thousands of results all at once. Change the following variables to your own:

- Added web stats & top 5 referrers which excludes hits from your own domain, do a find for $dommy & change this value to your domain name

- Also it now logs user browser info.
*/

$pagename = "webstats2.php?skill_mode=2"; //change this if you change the filename
$tablename = "webstats2"; //change this if you change the table name

$username = ""; //change username to the one you use to connect to your own mysql database here
$password = ""; //password for database here
$database = ""; //database name where you want to create your webstats

$conn = mysql_connect("mysql",$username,$password);
mysql_select_db($database) or die(mysql_error());

/*
//Un-comment the code below & run once to create the database, then comment it again.

mysql_query("CREATE TABLE `webstats2` (
`id` INT( 25 ) NOT NULL AUTO_INCREMENT ,
`ip` VARCHAR( 255 ) NOT NULL ,
`refer` VARCHAR( 255 ) NOT NULL ,
`url` VARCHAR( 255 ) NOT NULL ,
`date` VARCHAR( 255 ) NOT NULL ,
`page` VARCHAR( 255 ) NOT NULL ,
`browser` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` ) ) ");
*/



$ref=@$HTTP_REFERER;
$ip1 = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$page = $_SERVER['REQUEST_URI'];

$dom = $_SERVER['HTTP_HOST'];
$browser = $_SERVER['HTTP_USER_AGENT'];
$mode = $_REQUEST['skill_mode'];

//Paging function, split stats into 10 per page

function paging($tablename, $pagename)
{
$x = mysql_query("SELECT * FROM $tablename");
$numrows = mysql_num_rows($x);

$startpage = $_REQUEST['startpage'];

if(!isset($startpage)) {
$start = 0;
}

$startcount = ($startpage - 0);


$limit = 10;

$i=0;
$pagenumber=1;

echo " <a href=\"$pagename&startpage=0\" >First</a> ";

for($i=0;$i < $numrows;$i=$i+$limit)
{

if($i != $startcount && $i < ($startcount + ($limit * 10) ) && $i > ($startcount - ($limit * 10) ))
{
$t1 = $i+$limit;
$t2 = $i + 1;

if($t1 > $numrows)
{
$t1 = $numrows;
}

echo " <a href=\"$pagename&startpage=$i\" title= 'Show Results $t2 to $t1 of $numrows'>$pagenumber</a> ";

}
if($i == $startcount) {

echo "<td><font color=black>$pagenumber</font></td>";

}
$pagenumber++;
}
$last = $i-$limit;
echo " <a href=\"$pagename&startpage=$last\" > Last</a> <br>";

return $limit;
}

//generate average hits, top referrers etc

function generatestats($tablename, $pagename)
{

$x = mysql_query ( "SELECT * FROM $tablename" );
$totalViews = mysql_num_rows ( $x );

$x = mysql_query ( "SELECT DISTINCT(ip) FROM $tablename") ;
$totalUniques = mysql_num_rows ( $x );

$x = mysql_query("SELECT TO_DAYS(MAX(date)) - TO_DAYS(MIN(date)) AS record FROM $tablename");

while ($row = mysql_fetch_array($x)) {
$numdays = $row["record"];
}

$avgHits = round($totalUniques/$numdays);
$avgdayuniques = round($totalViews/$numdays);
$avghourHits = round($avgHits/24);


echo "<table border = 1 width = \"100%\">";
echo "<tr bgcolor=\"#DDDDFF\"><td>Total Hits<td>Total Unique Hits<td>Average Daily Unique Visitors<td>Average Daily Page Views<td>Average Hourly Hits<td>Days Recorded";

echo "<tr bgcolor=\"#DDDDFF\"><td>$totalViews<td>$totalUniques<td>$avgHits<td>$avgdayuniques<td>$avghourHits<td>$numdays";

echo "</table><br>";

$num = 5; //only show the top 5 referrers

echo "<table border = 1 width = \"100%\">";
echo "<tr bgcolor=\"#CCCCFF\"><td>Top Referrer<td>Hits<tr>";

$dommy = "http://www.yourdomain.com/"; //don't count referrals from innerpages, change this to your domain

$x = mysql_query ("SELECT COUNT(refer) AS num,refer FROM $tablename WHERE refer NOT LIKE '%$dommy%' AND refer != '' GROUP BY refer ORDER BY num DESC LIMIT $num");

while ($row = mysql_fetch_array($x)) {

$referrer = $row["refer"];
$hits = $row["num"];
echo "<td> <a href=$referrer target='_blank'>$referrer</a> <td> $hits <tr> ";
}



echo "</table><br>";


}



if($mode == 2)
{
$startpage = $_REQUEST['startpage'];

if(!isset($startpage)) {
$start = 0;
}

$startcount = ($startpage - 0);

generatestats($tablename, $pagename);
$limit = paging($tablename, $pagename);

$x = mysql_query("SELECT * FROM $tablename");
echo "<table border = 1 width = \"100%\">";
echo "<tr bgcolor=\"#DDDDFF\"><td>IP<td>Domain/Webpage<td>Time<td>Browser<td>Referrer";

$x = mysql_query("SELECT * FROM $tablename ORDER BY date DESC limit $startcount, $limit");



while($row = mysql_fetch_array($x))
{
$ip = $row['ip'];
$referrer = $row['refer'];
$domain = $row['url'];
$page = $row['page'];
$date = $row['date'];
$browser = $row['browser'];

echo "<tr bgcolor=\"#FFFFFF\"><td>$ip <td> $domain$page <td>$date<td>$browser <td width=\"30%\"> <a href='$referrer' target=\"_blank\">$referrer</a>";
}

echo "</table>";
paging($tablename, $pagename);
echo"<br>";




echo "<center><br> Powered by <a href='http://www.iamskill.com' target=\"_blank\">I Am Skill 3D</a>";

}
else
{

mysql_query("INSERT INTO $tablename (ip, refer, url, date, page, browser) VALUES('$ip1', '$ref', '$dom', now(), '$page', '$browser') ");
}


?>


