
CREATE TABLE chatmessages (
  id int(11) NOT NULL auto_increment,
  member int(11) NOT NULL default '0',
  channel varchar(255) NOT NULL default '',
  target int(11) NOT NULL default '0',
  line varchar(255) NOT NULL default '',
  rdate int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY member (member),
  KEY channel (channel),
  KEY target (target),
  KEY rdate (rdate)
) TYPE=MyISAM COMMENT='Chat messages.';

CREATE TABLE event (
  id int(11) NOT NULL auto_increment,
  sender varchar(255) default NULL,
  title varchar(255) default NULL,
  contents text,
  type varchar(255) default NULL,
  user_id int(11) default NULL,
  credits float(11,2) default NULL,
  status int(11) default NULL,
  rdate int(11) default NULL,
  PRIMARY KEY  (id),
  KEY type (type),
  KEY user_id (user_id),
  KEY status (status),
  KEY sender (sender),
  KEY rdate (rdate)
) TYPE=MyISAM;

CREATE TABLE members (
  id int(11) NOT NULL auto_increment,
  login varchar(255) default NULL,
  pswd varchar(255) default NULL,
  fname varchar(255) default NULL,
  lname varchar(255) default NULL,
  email varchar(255) default NULL,
  city varchar(255) default NULL,
  state varchar(255) default NULL,
  country varchar(255) default NULL,
  zip varchar(255) default NULL,
  phone varchar(255) default NULL,
  fax varchar(255) default NULL,
  prcode varchar(255) default NULL,
  status int(11) default NULL,
  rdate int(11) default NULL,
  PRIMARY KEY  (id),
  KEY login (login),
  KEY status (status),
  KEY email (email),
  KEY rdate (rdate)
) TYPE=MyISAM;

CREATE TABLE menus (
  id int(11) NOT NULL auto_increment,
  topic varchar(100) default NULL,
  link varchar(100) default NULL,
  level int(7) default NULL,
  PRIMARY KEY  (id),
  KEY level (level)
) TYPE=MyISAM;

CREATE TABLE pictures (
  id int(11) NOT NULL auto_increment,
  member int(11) default NULL,
  picture varchar(255) NOT NULL default '',
  details text,
  type varchar(80) default NULL,
  rdate int(11) default NULL,
  status int(11) default NULL,
  PRIMARY KEY  (id),
  KEY member (member),
  KEY type (type),
  KEY rdate (rdate),
  KEY status (status)
) TYPE=MyISAM;

CREATE TABLE profiles (
  id int(11) NOT NULL default '0',
  birthdate date default NULL,
  yahoo varchar(30) default NULL,
  msn varchar(30) default NULL,
  aol varchar(30) default NULL,
  icq varchar(30) default NULL,
  sex varchar(30) default NULL,
  likes varchar(30) default NULL,
  maritalstatus varchar(30) default NULL,
  height varchar(30) default NULL,
  weight varchar(30) default NULL,
  skin varchar(30) default NULL,
  ethnicity varchar(30) default NULL,
  eyes varchar(30) default NULL,
  hair varchar(30) default NULL,
  languages varchar(255) default NULL,
  occupation varchar(255) default NULL,
  details text,
  type varchar(80) default NULL,
  ldate int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ldate (ldate),
  KEY type (type),
  KEY birthdate (birthdate)
) TYPE=MyISAM;

CREATE TABLE sysvars (
  id int(11) NOT NULL auto_increment,
  description text,
  name varchar(255) default NULL,
  value text,
  vtype varchar(255) default NULL,
  PRIMARY KEY  (id),
  KEY vtype (vtype)
) TYPE=MyISAM;    