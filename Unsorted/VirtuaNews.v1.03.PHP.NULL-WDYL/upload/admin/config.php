<?php


// type of database running
// (only mysql is supported at the moment)
$dbservertype = "mysql";

// hostname or ip of server
$dbservername = "localhost";

// username and password to log onto db server
$dbusername = "root";
$dbpassword = "";

// name of database
$dbname = "virtuanews";

// technical email address
$technicalemail = "your-name@your-host.com";

// set the staff ids for the users which can prune the admin log
// enter a single id, or a string of ids seperated by a , eg. "1,5,7"
$canprunelog = "1";

// 0 shows no debug info
// 1 allows creation times to be viewed by adding showqueries=1 onto the query string,
// also displays the time in the admin panel
// 2 allows sql queries to be viewed also by adding showqueries=1 onto the query string
// 3 will display all the sql queries at the bottom of each admin page
$debug = 1;

// If you have a problem having the directory /admin/ on your server then change this variable below
// Please ensure you do not have a / as the first or that last character
// You must also edit the file admin/toggle.js to replace admin/ with whatever you want
// Also, you MUST edit global.php and admin.php and edit the line saying require("admin/config.php");
// to point it to this file
$admindirectory = "admin";

/*======================================================================*\
|| ####################################################################
|| # File: admin/config.php
|| ####################################################################
\*======================================================================*/

?>