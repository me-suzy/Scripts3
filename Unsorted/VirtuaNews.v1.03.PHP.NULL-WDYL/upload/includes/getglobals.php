<?php


unset($regon);

if (function_exists("ini_get")) {
  $regon = @ini_get("register_globals");
} else {
  $regon = @get_cfg_var("register_globals");
}
if ($regon != 1) {
  @extract($HTTP_SERVER_VARS,EXTR_SKIP);
  @extract($HTTP_COOKIE_VARS,EXTR_SKIP);
  @extract($HTTP_POST_FILES,EXTR_SKIP);
  @extract($HTTP_POST_VARS,EXTR_SKIP);
  @extract($HTTP_GET_VARS,EXTR_SKIP);
  @extract($HTTP_ENV_VARS,EXTR_SKIP);
} else {
  foreach ($HTTP_POST_FILES AS $key => $val) {
    $$key = $HTTP_POST_FILES[$key];
  }
}

unset($regon);

function addslashesarray(&$arr) {

  foreach ($arr AS $key => $val) {
    if (is_string($val) & ((strtoupper($key) != $key) | ("".intval($key) == "$key"))) {
      $arr["$key"] = addslashes($val);
    } elseif (is_array($val) & (($key == 'HTTP_POST_VARS') | ($key == 'HTTP_GET_VARS') | (strtoupper($key) != $key))) {
      $arr["$key"] = addslashesarray($val);
    }
  }

  return $arr;
}

if (!get_magic_quotes_gpc() & is_array($GLOBALS)) {
  $GLOBALS = addslashesarray($GLOBALS);
}

set_magic_quotes_runtime(0);

/*======================================================================*\
|| ####################################################################
|| # File: includes/getglobals.php
|| ####################################################################
\*======================================================================*/

?>