<?php

/* ===============================
== Basic PHP/MySQL Authentication 
== by x0x 
== Email: x0x@ukshells.co.uk
================================*/

include_once("inc/auth.inc.php");
$user = _check_auth($_COOKIE);

/* if user gets here they are authenticated */
/* the stuff below here is my test data */

_begin_html();
_fake_nav();
_good_user();
_end_html();
?>