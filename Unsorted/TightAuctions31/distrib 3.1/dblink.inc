<?
  // Initializes the $link needed for database operations

  $link = mysql_pconnect("localhost", $DB_Username, $DB_Password); 
  mysql_select_db($DB_Database, $link );		
?>
