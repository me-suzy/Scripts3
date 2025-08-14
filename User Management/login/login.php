<?php

include 'config.php';

ob_start();
echo "<center><font size\"2\" face=\"Tahoma\"> Network-13 Login System </font></center><br>";
echo "Login..<br>";
echo "<form action=\"./login.php\" method=\"POST\">";
echo "Name: <br><input type=\"text\" name=\"username\"><br>";
echo "Your email: <br><input type=\"text\" name=\"email\"><br>";
echo "Pass: <br><input type=\"password\" name=\"password\"><br>";
echo "<input type=\"submit\" value=\"Login!\">";
echo "</form>";
echo "<br>Dont have an account? Register <a href=\"register.php\">here!</a>";
echo "<br>Forgot your password? Click <a href=\"reset.php\">here!</a>";

$connection = @mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$dbs = @mysql_select_db($database, $connection) or
die(mysql_error());

$sql = "SELECT * FROM `users` WHERE username = '$_POST[username]' AND password = '$_POST[password]' AND email = '$_POST[email]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());
$num = @mysql_num_rows($result);

if ($num != 0) {
$cookie_name = "auth";
$cookie_value = "fook";
$cookie_expire = "0";
$cookie_domain = $domain;

setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain  . $directory . "admin.php");

ob_end_flush();

exit;
}
?>