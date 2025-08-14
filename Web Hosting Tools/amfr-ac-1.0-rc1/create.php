<?php
session_start();
if(!$_SESSION['rand'])
{
header('Location: new.php');
exit;
}
# VARS NEEDED FROM config.php"
# $cppass
# $cpuser
# $quota
# $ftp
# $mail
# $list
# $sql
# $addon
# $subdomains
# $park
# $bandwidth
# $host
# $dbuser
# $dbpass
# $dbname
# $ns1
# $ns2

include('config.php');
$domain2 = $_POST['domain2'];
$domain = $_POST['domain'];
$user = $_POST['user'];
$pass = rand();
$email = $_POST['email'];
$key = $_POST['c2'];
if(!$user || !$domain || !$domain2 || !$email)
{
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Not all fields were filled in!</B></FONT></CENTER></BODY></HTML>";
exit;
}
$image2 = $_POST['image2'];
if($image == "yes")
{
if($image2 != $_SESSION['rand'])
{
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Number does not match the one in the picture!</B></FONT></CENTER></BODY></HTML>";
exit;
}
}
if(strlen($user) > 8)
{
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Username cannot be longer than 8 characters!</B></FONT></CENTER></BODY></HTML>";
exit;
}
if(eregi('www\.', $domain2))
{
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Invalid domain!</B></FONT></CENTER></BODY></HTML>";
exit;
}
$link = mysql_connect($host, $dbuser, $dbpass);
if($skeys == "yes") {
$result = mysql_db_query($dbname, "SELECT * FROM amfr_keys WHERE amfrkey='$key'");
if (mysql_num_rows($result) == 0) {
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><B><FONT FACE='verdana' COLOR='red'>Invalid signup key!</B></FONT></CENTER></BODY></HTML>";
exit;
}
}
if($domain == 1 || $domain == 2 || $domain == 3 || $domain == 4 || $domain == 5 || $domain == 6 || $domain == 7) {
switch($domain) {
case 1:
$domain3 = $domain2.".com";
break;
case 2:
$domain3 = $domain2.".net";
break;
case 3:
$domain3 = $domain2.".org";
break;
case 4:
$domain3 = $domain2.".info";
break;
case 5:
$domain3 = $domain2.".us";
break;
case 6:
$domain3 = $domain2.".cc";
break;
case 7:
$domain3 = $domain2.".biz";
break;
}
}
else {
$domain3 = $domain2.".".$domain;
}
$result = mysql_db_query($dbname, "SELECT * FROM amfr_users WHERE user='$user'");
if (!mysql_num_rows($result) == 0) {
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Username already taken!</B></FONT></CENTER></BODY></HTML>";
exit;
}
$result = mysql_db_query($dbname, "SELECT * FROM amfr_users WHERE domain='$domain3'");
if (!mysql_num_rows($result) == 0) {
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Subdomain/Domain already taken!</B></FONT></CENTER></BODY></HTML>";
exit;
}
$result = mysql_db_query($dbname, "SELECT * FROM amfr_users WHERE email='$email'");
if (!mysql_num_rows($result) == 0) {
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Email already used!</B></FONT></CENTER></BODY></HTML>";
exit;
}
$file = file_get_contents("http://".$cpuser.":".$cppass."@localhost:2086/scripts/wwwacct?sign=&plan=undefined&domain=".$domain3."&username=".$user."&password=".$pass."&quota=".$quota."&cgi=1&frontpage=1&maxftp=".$ftp."&maxpop=".$mail."&maxlst=".$list."&maxsql=".$sql."&maxsub=".$subdomains."&maxpark=".$park."&maxaddon=".$addon."&bwlimit=".$bandwidth."&cpmod=x&customip=--Auto+Assign--&msel=n%2Cy%2Cunlimited%2Cy%2Cunlimited%2Cunlimited%2Cunlimited%2Cunlimited%2Cunlimited%2Cunlimited%2Cunlimited%2Cy%2C0%2C0&contactemail=".urlencode($email));
if(ereg('<img src=\/icons\/error\.gif>', $file)) {
if(ereg(' username ', $file)) {
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>Username already taken!</B></FONT></CENTER></BODY></HTML>";
exit;
}
else {
echo "<HTML><HEAD><TITLE>Error</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana' COLOR='red'><B>There was an error with your signup!</B></FONT></CENTER></BODY></HTML>";
exit;
}
}
mail($email, "Free hosting account set up", "A free cPanel hosting account has been set up for you.\nAccount details:\nUsername: $user\nPassword: $pass\nDomain: ".$domain2.".".$domain."\nTo login to cpanel go to http://$domain3/cpanel\nIf you are using your own domain please set your name servers to '$ns1' and '$ns2'".".", "From: Automailer");
echo "<HTML><HEAD><TITLE>Account set up</TITLE></HEAD><BODY><CENTER><FONT FACE='verdana'><h2>Account set up!</H2><BR><B>Check email for details!</B></CENTER></BODY></HTML>";
$result = mysql_db_query($dbname, "INSERT INTO amfr_users values('$user', '$domain3', '$email')");
if($skeys =="yes") {
$result = mysql_db_query($dbname, "INSERT INTO amfr_used_keys values('$key', '$user')");
$sql = "DELETE FROM amfr_keys WHERE amfrkey='$key'";
if($mkeys != "yes")
{
$result = mysql_db_query($dbname, $sql);
}
}
mysql_close($link);
?>