CREATE TABLE guests (
  id int(4) NOT NULL auto_increment,
  name varchar(64) NOT NULL default '',
  email varchar(64) NOT NULL default '',
  nick varchar(32) NOT NULL default '',
  clan varchar(32) NOT NULL default ' - clanlos -',
  status int(1) unsigned zerofill NOT NULL default '0',
  time varchar(16) NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  PRIMARY KEY  (id)
);
