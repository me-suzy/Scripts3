<?php
#
# FILE: installer.php
# DATE: 05/31/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Creates RateMyStuff's MySQL database and related tables.
#

require_once("config.php");

message("<b>Beginning RateMyStuff database installation</b><br>");

message("Creating a database named '$dbname'...");
if(!$result = mysql_query("create database $dbname", $db)){
  message("WARNING: Could not create a database named '$dbname' on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
}
message("<br>Choosing database '$dbname'...");
if(!$result = mysql_select_db($dbname)){
  message("ERROR: Could not use the database $dbname on host $dbhost.");
  exit;
}
message("<br>Creating 'users' table on database '$dbname'...");
if(!$result = mysql_query("create table users(id integer default 0 not null auto_increment, username varchar(255) not null, password varchar(255) not null, email varchar(255), bio text, hookup tinyint(1) default 0, created integer(10), lastip varchar(15), active tinyint(1) default 1, primary key(id,username), unique key(id))", $db)){
  message("WARNING: Could not create a 'users' table in database $dbname on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
  if(!$result = mysql_query("SELECT COUNT(*) FROM users", $db)){
    message("ERROR: 'users' table did not exist but couldn't create it.");
    exit;
  }
  else{
    message("UPDATING: 'users' table was already there, adding 'hookup' and 'bio' fields.");
    if($result = mysql_query("SELECT bio, hookup FROM users LIMIT 1", $db)){
      message("SKIPPING: 'users' table already has 'hookup' and 'bio' fields.");
    }
    else{
      if($result = mysql_query("ALTER TABLE users ADD COLUMN bio text")){
        message("<b>UPDATED</b>: added 'bio' column to 'users' table.");
      }
      else{
        $flag = 1;
        message("WARNING: couldn't add 'bio' column to 'users' table even though it was not there.");
      }
      if($result = mysql_query("ALTER TABLE users ADD COLUMN hookup tinyint(1) default 0")){
        message("<b>UPDATED</b>: added 'hookup' column to 'users' table.");
      }
      else{
        $flag = 1;
        message("WARNING: couldn't add 'hookup' column to 'users' table even though it was not there.");
      }
    }
  }
  if($flag){
    message("ERROR: Alteration of table 'users' failed, aborting.");
    exit;
  }
}
message("<br>Creating 'pictures' table on database '$dbname'...");
if(!$result = mysql_query("create table pictures(id integer not null default 0 auto_increment, textid varchar(32) not null, owner varchar(255), filename varchar(255), url text, adddate integer(10), score float default 5.00, numvotes integer, sumvotes integer, lastvote integer(10), active tinyint(1) default 1, approved tinyint(1) default 0, primary key(id,textid), unique key(id))", $db)){
  message("WARNING: Could not create a 'pictures' table in database $dbname on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
  if(!$result = mysql_query("SELECT COUNT(*) FROM pictures", $db)){
    message("ERROR: 'pictures' table did not exist but couldn't create it.");
    exit;
  }
  else{
    message("SKIPPING: 'pictures' table was already there.");
  }
}
message("<br>Creating 'ratings' table on database '$dbname'...");
if(!$result = mysql_query("create table ratings(id integer default 0 not null auto_increment, textid varchar(255) not null, picnum integer not null, score float not null default 0.00, date integer(10), ip varchar(15), primary key(id), unique key(id))", $db)){
  message("WARNING: Could not create a 'ratings' table in database $dbname on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
  if(!$result = mysql_query("SELECT COUNT(*) FROM ratings", $db)){
    message("ERROR: 'ratings' table did not exist but couldn't create it.");
    exit;
  }
  else{
    message("SKIPPING: 'ratings' table was already there.");
  }
}
message("<br>Creating 'reports' table on database '$dbname'...");
if(!$result = mysql_query("create table reports(id integer default 0 not null auto_increment, textid varchar(32) not null, ipaddr varchar(15), date integer(10), handled tinyint(1) default 0, primary key(id), unique key(id))", $db)){
  message("WARNING: Could not create a 'reports' table in database $dbname on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
  if(!$result = mysql_query("SELECT COUNT(*) FROM reports", $db)){
    message("ERROR: 'reports' table did not exist but couldn't create it.");
    exit;
  }
  else{
    message("SKIPPING: 'reports' table was already there.");
  }
}
message("<br>Creating 'hookups' table on database '$dbname'...");
if(!$result = mysql_query("create table hookups(id integer default 0 not null auto_increment, textid varchar(32) not null, sender varchar(32) not null, recipient varchar(32) not null, date integer(10), message text, ipaddr varchar(15), viewed tinyint(1) default 0, primary key(id), unique key(id))", $db)){
  message("WARNING: Could not create a 'hookups' table in database $dbname on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
  if(!$result = mysql_query("SELECT COUNT(*) FROM hookups", $db)){
    message("ERROR: 'hookups' table did not exist but couldn't create it.");
    exit;
  }
  else{
    message("SKIPPING: 'hookups' table was already there.");
  }
}
message("<br>Creating 'bannedips' table on database '$dbname'...");
if(!$result = mysql_query("create table bannedips(id integer default 0 not null auto_increment, ipaddr varchar(15) not null, primary key(id, ipaddr))", $db)){
  message("WARNING: Could not create a 'bannedips' table in database $dbname on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
  if(!$result = mysql_query("SELECT COUNT(*) FROM bannedips", $db)){
    message("ERROR: 'bannedips' table did not exist but couldn't create it.");
    exit;
  }
  else{
    message("SKIPPING: 'bannedips' table was already there.");
  }
}
message("<br>Creating 'bannedemails' table on database '$dbname'...");
if(!$result = mysql_query("create table bannedemails(id integer default 0 not null auto_increment, email varchar(255) not null, primary key(id, email))", $db)){
  message("WARNING: Could not create a 'bannedemails' table in database $dbname on host $dbhost.<br>&nbsp;&nbsp;(perhaps it exists, let me see...)");
  if(!$result = mysql_query("SELECT COUNT(*) FROM bannedemails", $db)){
    message("ERROR: 'bannedemails' table did not exist but couldn't create it.");
    exit;
  }
  else{
    message("SKIPPING: 'bannedemails' table was already there.");
  }
}
message("<br><b>Installation completed!!</b> You should now upload some pictures.");

function message($msg){
  echo '<font face="Verdana, Arial" size="2">';
  echo "$msg<br>";
  echo '</font>';
}

?>