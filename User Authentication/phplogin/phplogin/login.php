<?php

/* ===============================
== Basic PHP/MySQL Authentication 
== by x0x 
== Email: x0x@ukshells.co.uk
================================*/

session_start();
include_once("inc/auth.inc.php");
_already_logged($_COOKIE);

if(isset($_POST['submit']))
{
  $user_data = _check_database(fm($_POST['user']),fm($_POST['pass']));
  if($user_data == 0) login_page();
  else _set_cookie($user_data,fm($_POST['rem']),session_id(),fm($_POST['user']));
} else
  login_page();
?>