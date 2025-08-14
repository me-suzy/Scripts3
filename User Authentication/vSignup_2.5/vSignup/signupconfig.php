<?
/*
File: signupconfig.php
Script Name: vSignup 2.5
Author: Vincent Ryan Ong
Email: support@beanbug.net

Description:
vSignup is a member registration script which utilizes vAuthenticate
for its security handling. This handy script features email verification,
sending confirmation email message, restricting email domains that are 
allowed for membership, and much more.

This script is a freeware but if you want to give donations,
please send your checks (coz cash will probably be stolen in the
post office) to:

Vincent Ryan Ong
Rm. 440 Wellington Bldg.
655 Condesa St. Binondo, Manila
Philippines, 1006
*/

$dbhost = "localhost";	// change this to reflect your db host name
$dbusername = "root";	// change this to reflect your db username
$dbpassword = "";	// change this to reflect your db password
$dbport = "3306";	// default is 3306; change this if different

$dbname = "signup-test"; 	// change this to the name of your 

$RelLogin = "login.php";	// change this to the relative path to login.php. This is not the same with authconfig.php's $login (which is set to the FULL URL of the login page)

// The $_SERVER['HTTP_HOST'] takes care of the root directory of the web server
// This makes it possible to implement the script even on IP-based systems.
// For name-based systems, just think of $_SERVER['HTTP_HOST'] as the domain name
// example: $_SERVER['HTTP_HOST'] will have to be www.yourdomain.com
// For IP-based systems, this will replace the IP address
// example: $_SERVER['HTTP_HOST'] will have to be 66.199.47.5
$confirm = "http://" . $_SERVER['HTTP_HOST'] . "/Scripts/vSignup/confirm.php";	// change this to reflect the URL location of confirm.php 

$adminemail = "admin@domain.com";	// change this to reflect the admin's email address (used for email notifications)	

?>