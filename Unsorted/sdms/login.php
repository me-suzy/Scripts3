<?php

  require('lib/config.inc');

  @mysql_connect("$cfg[server]", "$cfg[user]", "$cfg[pass]") or die("Unable to connect to SQL Server.");
  @mysql_select_db("$cfg[db]") or die("Unable to select database '$cfg[db]'");

  $result = @mysql_query("SELECT id FROM users WHERE user='$login'");
  if( mysql_num_rows($result) != 1 ) {
      header("Location: index.php");
      exit;
  }

  $result = @mysql_query("SELECT pass != PASSWORD('$pass') FROM users WHERE user='$login'");
  $row = @mysql_fetch_array($result);
  if( $row[0] != 0 ) {
      header("Location: index.php");
      exit;
  }

  $result = @mysql_query("SELECT id,name FROM users WHERE user='$login'");
  $row = @mysql_fetch_array($result);
  $id = $row[id];
  $name = $row[name];

  require('lib/session.inc');
  session_register("id");
  session_register("login");

  header("Location: main.php");

?>
