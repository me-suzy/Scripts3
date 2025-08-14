<?php

require("config.php");

$sql="CREATE TABLE $db_table (
  id int(20) NOT NULL auto_increment,
  added varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  site_name varchar(255) NOT NULL,
  url varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  queue tinyint(4) DEFAULT '0' NOT NULL,
  PRIMARY KEY  (id)
)";

$result = mysql_query($sql) or print ("Can't create the table '$db_table' in the database.<br />" . $sql . "<br />" . mysql_error());

mysql_close();

if ($result != false)
{
echo "Table '$db_table' was created!  Don't forget to delete this file from your server!\n";
}

?>