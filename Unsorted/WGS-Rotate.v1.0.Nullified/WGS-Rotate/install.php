<?
include_once("config.inc.php");

$query = "CREATE TABLE rotate (
  id int(11) NOT NULL auto_increment,
  url varchar(255) default NULL,
  weight int(11) default NULL,
  hits int(11) default NULL,
  total_hits int(11) default NULL,
  user_id int(10) unsigned default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";
mysql_query($query);



$query = "CREATE TABLE rotate_clicks (
  id int(5) unsigned NOT NULL auto_increment,
  user_id int(5) unsigned NOT NULL default '0',
  url_id int(5) unsigned NOT NULL default '0',
  date date NOT NULL default '0000-00-00',
  count int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";
mysql_query($query);




$query = "CREATE TABLE rotate_usr (
  id int(10) unsigned NOT NULL auto_increment,
  login varchar(20) NOT NULL default '0',
  password varchar(40) NOT NULL default '0',
  email varchar(50) NOT NULL default '0',
  account_type varchar(10) default '0',
  sign_up_date datetime default NULL,
  total_hits int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;";
mysql_query($query);

?>
