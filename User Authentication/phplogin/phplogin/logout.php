<?php

/* ===============================
== Basic PHP/MySQL Authentication 
== by x0x 
== Email: x0x@ukshells.co.uk
================================*/

include_once("inc/auth.inc.php");
$user = _check_auth($_COOKIE);
_logout_user($_COOKIE);
?>