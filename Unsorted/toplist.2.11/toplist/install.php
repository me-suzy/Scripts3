<?php
include "config.php";
include "db/db.php";
include "style/default.php";

if($install == ""){
echo "<center><br><br>Best Top List by Szymon Kosok<br><br>Installation script! <br><a href=install.php?install=do>Click here to install this script</a>";
}
if($install == "do"){
echo "<br>Creating table toplista ... ";
$create = "CREATE TABLE toplista (nazwa CHAR(40), url CHAR(50), opis CHAR(255), banner CHAR(255), email CHAR(255), wejsica INT(255), wyjscia INT(255), id INT PRIMARY KEY AUTO_INCREMENT, active CHAR(255), haslo CHAR(255), rating INT, votes INT, user CHAR(100), category INT, regdate INT, lastvote INT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<br>Creating table toplista_news ... ";
$create = "CREATE TABLE toplista_news (news BLOB, newsman CHAR(50), data CHAR(40), id INT PRIMARY KEY AUTO_INCREMENT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<br><br>Ok, now is time to configure your script, fill the fields:<br><br><form method=post action=install.php?install=optionsubmit>
<table>
  <tr>
    <td>Name of list:</td>
    <td><input type=text name=nameoflist></td>
  </tr>
  <tr>
    <td>Style:</td>
    <td><input type=text name=style value=default></td>
  </tr>
  <tr>";

  $path = str_replace("/install.php", "", $PHP_SELF);

    echo "<td>Top List URL:</td>
    <td><input type=text name=siteurl value=" . $HTTP_HOST . $path . "></td>
  </tr>
  <tr>
    <td>Admin mail:</td>
    <td><input type=text name=adminmail></td>
  </tr>
  <tr>
    <td>Admin password</td>
    <td><input type=password name=confpass></td>
  </tr>
  <tr>
    <td>Cookie domain:</td>
    <td><input type=text name=cookiedomain value=" . $HTTP_HOST . "></td>
  </tr>
  <tr>
    <td>Cookie time (in seconds):</td>
    <td><input type=text name=cookietime value=14400></td>
  </tr>
    <tr>
    <td>Max banner height (in pixels):</td>
    <td><input type=text name=maxheight value=400></td>
  </tr>
    <tr>
    <td>Max banner width:</td>
    <td><input type=text name=maxwidth value=60></td>
  </tr>
  <tr>
    <td>Max visible banners (top xx):</td>
    <td><input type=text name=maxbanners value=10></td>
  </tr>
  <tr>
    <td></td>
    <td><input type=submit value=Submit name=submit></td>
  </tr>
</table></form><br><br>Instructions:<br><br>\"Top List URL\" - full address to top list script ex. www.best-scripts.tk ... dont include http:// and slash at the end!<br><br>Style - check <b>style</b> directory for names of styles. If you wanna use style with name 'whatever.php' type in 'whatever' in style field.
";
}
if($install == "optionsubmit"){
echo "<center><br><br>creating toplista_options table ... ";
$create = "CREATE TABLE toplista_options (nameoflist CHAR(100), style CHAR(20), siteurl CHAR(100), adminmail CHAR(100), confpass CHAR(255), cookiedomain CHAR(100), cookietime CHAR(100), nextupd INT, updperday INT, maxheight INT, maxwidth INT, maxbanners INT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<br><br>Filling table with data ... ";
$confpass = md5($confpass);
$updatetime = time() + 2592000;
$create = "INSERT INTO toplista_options VALUES('$nameoflist', '$style', '$siteurl', '$adminmail', '$confpass', '$cookiedomain', '$cookietime', '$updatetime', '30', '$maxheight', '$maxwidth', '$maxbanners')";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');

echo "<center><br><br>creating toplista_menu table ... ";
$create = "CREATE TABLE toplista_menu (menu_addsite INT, menu_editsite INT, menu_lostpass INT, menu_contact INT, menu_taf INT, menu_rules INT, menu_losthtml INT, menu_bannerhosting INT, menu_news INT, menu_viewstats INT, menu_home INT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<br><br>Filling table with data ... ";
$create = "INSERT INTO toplista_menu VALUES('1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1')";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<center><br><br>creating toplista_banned table ... ";
$create = "CREATE TABLE toplista_banned (ip CHAR(40), typeofban INT, id INT PRIMARY KEY AUTO_INCREMENT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<center><br><br>creating toplista_stats table ... ";
$create = "CREATE TABLE toplista_stats (ip CHAR(40), date CHAR(20), inout CHAR(3), siteid INT, id INT PRIMARY KEY AUTO_INCREMENT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<center><br><br>creating toplista_functions table ... ";
$create = "CREATE TABLE toplista_functions (rating INT, anticheat INT, cheatlog INT, szukaj INT, stats INT, autoaccept INT, id INT PRIMARY KEY AUTO_INCREMENT, mailnotif INT, comments INT, checkingsize INT, categories INT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<br><br>Filling table with data ... ";
$create = "INSERT INTO toplista_functions VALUES('1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1')";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<center><br><br>creating toplista_comments table ... ";
$create = "CREATE TABLE toplista_comments (nick CHAR(40), email CHAR(100), data CHAR(30), comment TEXT, id INT PRIMARY KEY AUTO_INCREMENT, siteid INT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<center><br><br>creating toplista_categories table ... ";
$create = "CREATE TABLE toplista_categories (name CHAR(40),id INT PRIMARY KEY AUTO_INCREMENT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<br><br>Filling table with data ... ";
$create = "INSERT INTO toplista_categories VALUES('General', '')";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<center><br><br>creating toplista_ip_blocking table ... ";
$create = "CREATE TABLE toplista_ip_blocking (ip CHAR(20), czas INT, idstrony INT, id INT PRIMARY KEY AUTO_INCREMENT)";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>ok!</font>";
echo "<br><br>Script installed! :) Thx for using our script. Please tell us what do you think about it on our <a href=http://www.best-scripts.tk>forum</a>. :)";
mail("nookie@xtina.pl","Top List Installed!","http://" . $HTTP_HOST . $PHP_SELF . " on " . $SERVER_SOFTWARE . " ...");
}
?>


