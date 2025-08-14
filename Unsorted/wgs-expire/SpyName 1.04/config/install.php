<?
/*****************************************************************/
/* Program Name         : WGS-Expire				             */
/* Program Version      : 1.02                                   */
/* Program Author       : Webguy Studios                         */
/* Site                 : http://www.webguystudios.com           */
/* Email                : contact@webguystudios.com              */
/*                                                               */
/* Copyright (c) 2002,2003 webguystudios.com All rights reserved.   */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

	include "../lib/config.php";
	include "../lib/db.php";
  
  function sql($s) {
    print nl2br($s)."<br>\n";
    mysql_query($s)
      or die(mysql_error());
    print "<br>\n";
  }
  
  $vars = config_read();
  db_connect();
  extract($vars);
//  sql("DROP DATABASE IF EXISTS $dbname");
//  sql("CREATE DATABASE IF NOT EXISTS $dbname");
  mysql_select_db($dbname) 
    or die(mysql_error());

//-----------------------------------------
  $s =<<<_SQL
  CREATE TABLE IF NOT EXISTS member
  (
    id  INTEGER AUTO_INCREMENT PRIMARY KEY,
    active	BOOL DEFAULT 'FALSE' NOT NULL,
    login VARCHAR(50) UNIQUE,
    first VARCHAR(50),
    last VARCHAR(50),
    email VARCHAR(50),
    acctype INTEGER NOT NULL REFERENCES plan(id),
    sstat INTEGER DEFAULT 0 NOT NULL,
    news BOOL,
    passwd  VARCHAR(50),
	subscr_id	VARCHAR(100)
  ) TYPE=InnoDB
_SQL;
  sql($s);    

//-----------------------------------------
  $s =<<<_SQL
  CREATE TABLE IF NOT EXISTS domain_name
  (
    id  INTEGER AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(255) UNIQUE,
    state	VARCHAR(255) NOT NULL DEFAULT 'uncknown',
    since TIMESTAMP,
    stamp TIMESTAMP
  ) TYPE=InnoDB
_SQL;
  sql($s);    
//-----------------------------------------
  $s =<<<_SQL
  CREATE TABLE IF NOT EXISTS monitor
  (
    id  INTEGER AUTO_INCREMENT PRIMARY KEY,
    member  INTEGER  NOT NULL REFERENCES member(id),
    domain_name  INTEGER NOT NULL REFERENCES domain_name(id),
	notified	BOOL DEFAULT 0
  ) TYPE=InnoDB
_SQL;
  sql($s);    
//-----------------------------------------
  $s =<<<_SQL
  CREATE TABLE IF NOT EXISTS plan
  (
    id  INTEGER AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(255) NOT NULL,
    cost  DECIMAL(6,2) NOT NULL DEFAULT 0,
    domains  INTEGER  NOT NULL DEFAULT 0,
    linkpop VARCHAR(4) NOT NULL,
    digger VARCHAR(4) NOT NULL,
    yamoz VARCHAR(4) NOT NULL
    
  ) TYPE=InnoDB
_SQL;
  sql($s);    
//-----------------------------------------
/*  $s =<<<_SQL
  ALTER TABLE plan ADD COLUMN linkpop VARCHAR(4) NOT NULL
_SQL;
  sql($s);
//-----------------------------------------
/*  $s =<<<_SQL
  ALTER TABLE plan ADD COLUMN digger VARCHAR(4) NOT NULL
_SQL;
  sql($s);
//-----------------------------------------
/*  $s =<<<_SQL
  ALTER TABLE plan ADD COLUMN yamoz VARCHAR(4) NOT NULL
_SQL;
  sql($s);
//-----------------------------------------
/*  $s =<<<_SQL
  CREATE TABLE IF NOT EXISTS search_stat
  (
    id      INTEGER AUTO_INCREMENT PRIMARY KEY,
    stamp   TIMESTAMP NOT NULL,
    member  INTEGER REFERENCES member(id),
    zone    INTEGER,
    state   INTEGER,
    period  INTEGER,
    
    contain_type1 INTEGER,
    contain1 VARCHAR(50),
    
    contain_type2 INTEGER,
    contain2 VARCHAR(50),
    
    length_type INTEGER,
    length  INTEGER,
    
    hyphens BOOL,
    numbers BOOL
  ) TYPE=InnoDB
_SQL;
  sql($s);    
*/
  mysql_close();
?>
