# phpMyAdmin MySQL-Dump
# version 2.3.0-rc2
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Vert: localhost
# Generert den: 09. Nov, 2002 klokka 00:42 AM
# Server versjon: 4.00.01
# PHP Versjon: 4.2.3
# Database : `mm`
# --------------------------------------------------------

#
# Tabell-struktur for tabell `favorites`
#

CREATE TABLE favorites (
  favid int(11) NOT NULL auto_increment,
  owner varchar(50) default NULL,
  fav_user varchar(50) default NULL,
  favdate timestamp(8) NOT NULL,
  PRIMARY KEY  (favid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabell-struktur for tabell `mail`
#

CREATE TABLE mail (
  mailid int(11) NOT NULL auto_increment,
  mail_to varchar(50) default NULL,
  mail_from varchar(50) default NULL,
  title varchar(150) default NULL,
  message text,
  maildate timestamp(8) NOT NULL,
  status varchar(10) default NULL,
  PRIMARY KEY  (mailid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabell-struktur for tabell `matchprofiles`
#

CREATE TABLE matchprofiles (
  id int(11) NOT NULL auto_increment,
  username_p1 varchar(50) default NULL,
  username_p2 varchar(50) default NULL,
  score int(11) default NULL,
  matchdate timestamp(8) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabell-struktur for tabell `user`
#

CREATE TABLE user (
  username varchar(20) NOT NULL default '',
  passwd varchar(20) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  sex varchar(50) default NULL,
  age int(11) default NULL,
  marital varchar(50) default NULL,
  build varchar(50) default NULL,
  hair varchar(50) default NULL,
  height int(11) default NULL,
  weight int(11) default NULL,
  eye varchar(50) default NULL,
  place varchar(50) default NULL,
  occupation varchar(50) default NULL,
  religion varchar(50) default NULL,
  education varchar(50) default NULL,
  children varchar(50) default NULL,
  about text,
  registered datetime default NULL,
  lastlogin varchar(8) default NULL,
  lastviewed timestamp(8) NOT NULL,
  hits int(11) default NULL,
  i1 tinyint(4) default NULL,
  i2 tinyint(4) default NULL,
  i3 tinyint(4) default NULL,
  i4 tinyint(4) default NULL,
  i5 tinyint(4) default NULL,
  i6 tinyint(4) default NULL,
  i7 tinyint(4) default NULL,
  i8 tinyint(4) default NULL,
  i9 tinyint(4) default NULL,
  i10 tinyint(4) default NULL,
  i11 tinyint(4) default NULL,
  i12 tinyint(4) default NULL,
  i13 tinyint(4) default NULL,
  i14 tinyint(4) default NULL,
  i15 tinyint(4) default NULL,
  i16 tinyint(4) default NULL,
  i17 tinyint(4) default NULL,
  i18 tinyint(4) default NULL,
  i19 tinyint(4) default NULL,
  i20 tinyint(4) default NULL,
  lo_sex varchar(50) default NULL,
  lo_agefrom int(11) default NULL,
  lo_ageto int(11) default NULL,
  lo_heightfrom int(11) default NULL,
  lo_heightto int(11) default NULL,
  lo_build varchar(50) default NULL,
  lo_place varchar(50) default NULL,
  lo_marital varchar(50) default NULL,
  lo_weightfrom int(11) default NULL,
  lo_weightto int(11) default NULL,
  lo_children varchar(50) default NULL,
  lo_religion varchar(50) default NULL,
  lo_hair varchar(50) default NULL,
  image varchar(50) default NULL,
  ban int(11) default NULL,
  verify varchar(15) default NULL,
  isSpecial int(11) default '0',
  wantSpecial int(11) default '0',
  prefs varchar(10) default NULL,
  PRIMARY KEY  (username)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabell-struktur for tabell `visitors`
#

CREATE TABLE visitors (
  visitid int(11) NOT NULL auto_increment,
  visitor varchar(50) default NULL,
  owner varchar(50) default NULL,
  visitdate timestamp(8) NOT NULL,
  PRIMARY KEY  (visitid)
) TYPE=MyISAM;

