<?php
require "settings.php";
require "lib/mysql.lib";
require "lib/bann.lib";
?>


<title>eDate 2.5 - Complete Dating Solution</title>
<link rel="stylesheet" href="style.css">
<body bgcolor="#333333" text="#333333" link="#3366CC" vlink="#3366CC" alink="#3366CC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="250" bgcolor="#C43318"><img src="images/header.jpg" height="250"></td>
  </tr>
  <tr>
    <td height="2" bgcolor="#000000"></td>
  </tr>
  <tr>
    <td bgcolor="#F0F0F0"><b>&nbsp;&nbsp;&nbsp; <a href=index.php>Home</a> | <a href=search.php>Search</a> 
      | 
      <?php
if ($auth)
{?>
      <a href=member_center.php>Membership</a> | <a href=editdetails.php>Account</a> 
      | <a href=profile.php>Profile</a> | <a href=manage_pictures.php>Pictures</a> 
      <?php 
      if ($tm0message) echo "| <a href=messages.php>Messaging</a> ";
	  if ($tm0chat) echo "| <a href=\"#\" onclick=\"window.open('chat.php','','height=400,width=550')\">Chat</a>";
	  echo "| <a href='mem.php?mid=$auth'>Public</a> ";
	  echo "| <a href=logout.php>LogOut</a> ";
	  }else {
?>
      <a href=register.php>Register</a> | <a href=login.php>Login</a> 
      <?php };
?>
      | <a href=mailto:contact@yourdomain.com>Support</a> </b></td>
  </tr>
  <tr>
    <td height="1" bgcolor="#DDDDDD"></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#FFFFFF">     
<br>