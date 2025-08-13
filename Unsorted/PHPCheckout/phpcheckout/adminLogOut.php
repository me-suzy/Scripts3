<?php 
/*
If you enable register_globals, session_unregister() should be used since session 
variables are registered as global variables when session data is deserialized.
http://www.php.net/manual/en/ref.session.php
*/
session_start();
function session_clear() {
// if session exists, unregister all variables that exist and destroy session
  $exists = "no";
  $session_array = explode(";",session_encode());
  for ($x = 0; $x < count($session_array); $x++) {
    $name  = substr($session_array[$x], 0, strpos($session_array[$x],"|")); 
	if (session_is_registered($name)) {
	  session_unregister('$name');
	  $exists = "yes";
	}
  }
if ($exists != "no") {
    session_destroy();
	}
}
session_clear();
if(!session_is_registered(session_name())) {
	echo"<h1 style=\"color:green;\">Logged Out</h1>";
}else{
	echo"<h1 style=\"color:red;\">NOT Logged Out</h1>";
	echo"Please contact the system administrator.";
}
?>