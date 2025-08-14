<?php
// *************************************************************************************************
// Title: 		PHP AGTC-Membership system v1.1a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
//
// YOU MUST INCLUDE THIS BIT OF CODE TO CHECK LOGIN AND USER LEVEL AUTHORITY
// PROTECT ANY OF YOUR PAGES BY INCLUDING THIS SECTION OF CODE AT THE VERY TOP OF YOUR PROTECTED PAGE
// IF YOU WANT TO CHANGE LEVEL AUTHORITY, INCLUDE THE APPRORIATE LEVEL CHECK PAGE
// THIS EXAMPLE IS FOR LEVEL 1 AUTHORITY (level1_check.php)
session_start();
include "level1_check.php";

?>
<?php
echo "Level is :".$_SESSION['level']."<br>";
echo "Welcome to the demonstration index page, you are logged in. This page will accept all levels<br><br>Click on selection here for demonstration pages -->&nbsp;<a href='adduser.php'>Add User Page</a> ¦ <a href='admin.php'>Admin Page</a>¦  <a href='login.php'>Log in Page</a>¦  <a href='forgot.php'>Forgot Password Page</a>¦  <a href='logout.php'>Log out Page</a><BR>
<br>REMEMBER: If your using this on your server, change the config.php file with your details or you will not work properly.<br>
Lets play ! .. Change index.php 'level1_check.php' to 'level2_check.php' Try loging in as a level 1 user, then try level 3 user, it will let level 3 user in but not level 1 user, this is because level 3 user can access any level below but not above." ;
 ?>
 <p>
*************************************************************************************************<br>
Title: 		PHP AGTC-Membership system v1.1a<br>
Developed by: Andy Greenhalgh<br>
Email:		andy@agtc.co.uk<br>
Website:		agtc.co.uk<br>
Copyright:	2005(C)Andy Greenhalgh - (AGTC)<br>
Licence:		GPL, You may distribute this software under the terms of this General Public License<br>
*************************************************************************************************<br>

</p>