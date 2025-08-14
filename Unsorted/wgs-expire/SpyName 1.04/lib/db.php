<?
  function db_connect() {
  	$vars = config_read();
    extract($vars);
    mysql_connect($dbhost,$dbuser,$dbpassword) or die(mysql_error());
    mysql_select_db($dbname) or die(mysql_error());
  }
  
  function db_close() {
    mysql_close();
  }
?>