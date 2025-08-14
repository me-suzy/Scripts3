<?php
include 'config.php';
$tmp = $_GET['action'];
if($tmp == "signout"){
$cookie_name = "auth";
$cookie_value = "";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "login.php");
}

if($_COOKIE['auth'] == "fook"){
echo "Welcome to the members section";

echo "<br><a href=\"admin.php?action=signout\">Sign Out</a>";
}else{
header ("Location: http://" . $domain . $directory . "login.php");
exit;
}
?>

<html>
<head>
</head>
<body>
<br>
<font size="2" face="Tahoma">Put all your content here!</font>
<br>
<a href="http://network-13.com" target="_NEW">Network-13</a>