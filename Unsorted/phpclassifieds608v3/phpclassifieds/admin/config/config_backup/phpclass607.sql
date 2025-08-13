# phpMyAdmin MySQL-Dump
# version 2.3.0-rc2
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Vert: localhost
# Generert den: 09. Okt, 2002 klokka 23:40 PM
# Server versjon: 4.00.01
# PHP Versjon: 4.2.3
# Database : `phpold`
# --------------------------------------------------------

#
# Tabell-struktur for tabell `ad`
#

CREATE TABLE ad (
  siteid int(11) NOT NULL auto_increment,
  sitetitle varchar(100) default NULL,
  sitedescription text,
  siteurl varchar(100) default NULL,
  sitedate varchar(10) default NULL,
  expiredate varchar(12) default NULL,
  sitecatid int(11) default NULL,
  sitehits int(11) default NULL,
  sitevotes double(16,4) default NULL,
  sites_userid int(11) default NULL,
  sites_pass varchar(12) default NULL,
  custom_field_1 varchar(50) default NULL,
  custom_field_2 varchar(50) default NULL,
  custom_field_3 varchar(50) default NULL,
  custom_field_4 varchar(50) default NULL,
  custom_field_5 varchar(50) default NULL,
  custom_field_6 varchar(50) default NULL,
  custom_field_7 varchar(50) default NULL,
  custom_field_8 varchar(50) default NULL,
  picture int(11) default '0',
  img_stored varchar(70) default NULL,
  datestamp timestamp(8) NOT NULL,
  f1 varchar(50) default NULL,
  f2 varchar(50) default NULL,
  f3 varchar(50) default NULL,
  f4 varchar(50) default NULL,
  f5 varchar(50) default NULL,
  f6 varchar(50) default NULL,
  f7 varchar(50) default NULL,
  f8 varchar(50) default NULL,
  f9 varchar(50) default NULL,
  f10 varchar(50) default NULL,
  f11 varchar(50) default NULL,
  f12 varchar(50) default NULL,
  f13 varchar(50) default NULL,
  f14 varchar(50) default NULL,
  f15 varchar(50) default NULL,
  notify int(11) default NULL,
  ad_username varchar(50) default NULL,
  valid tinyint(4) default '0',
  expire_days int(11) default '0',
  sold tinyint(4) default NULL,
  PRIMARY KEY  (siteid)
) TYPE=MyISAM;

#
# Data-ark for tabell `ad`
#

# --------------------------------------------------------

#
# Tabell-struktur for tabell `category`
#

CREATE TABLE category (
  catid int(11) NOT NULL auto_increment,
  catfatherid int(11) default NULL,
  catname varchar(100) default NULL,
  catdescription varchar(150) default NULL,
  catimage varchar(100) default 'default.gif',
  total varchar(5) default '0',
  latest_date varchar(8) default NULL,
  cattpl varchar(50) default NULL,
  allowads char(2) default NULL,
  catfullname varchar(150) default NULL,
  PRIMARY KEY  (catid)
) TYPE=MyISAM;

#
# Data-ark for tabell `category`
#

# --------------------------------------------------------

#
# Tabell-struktur for tabell `picture`
#

CREATE TABLE picture (
  id int(4) NOT NULL auto_increment,
  pictures_siteid varchar(6) default NULL,
  bin_data longblob,
  filename varchar(50) default NULL,
  filesize varchar(50) default NULL,
  filetype varchar(50) default NULL,
  imagew varchar(10) default NULL,
  imageh varchar(10) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Data-ark for tabell `picture`
#

# --------------------------------------------------------

#
# Tabell-struktur for tabell `template`
#

CREATE TABLE template (
  tplid int(11) NOT NULL auto_increment,
  name varchar(50) default NULL,
  f1_caption varchar(50) default NULL,
  f1_type varchar(50) default NULL,
  f1_mandatory char(3) default NULL,
  f1_length varchar(5) default NULL,
  f1_filename varchar(50) default NULL,
  f2_caption varchar(50) default NULL,
  f2_type varchar(50) default NULL,
  f2_mandatory char(3) default NULL,
  f2_length varchar(5) default NULL,
  f2_filename varchar(50) default NULL,
  f3_caption varchar(50) default NULL,
  f3_type varchar(50) default NULL,
  f3_mandatory char(3) default NULL,
  f3_length varchar(5) default NULL,
  f3_filename varchar(50) default NULL,
  f4_caption varchar(50) default NULL,
  f4_type varchar(50) default NULL,
  f4_mandatory char(3) default NULL,
  f4_length varchar(5) default NULL,
  f4_filename varchar(50) default NULL,
  f5_caption varchar(50) default NULL,
  f5_type varchar(50) default NULL,
  f5_mandatory char(3) default NULL,
  f5_length varchar(5) default NULL,
  f5_filename varchar(50) default NULL,
  f6_caption varchar(50) default NULL,
  f6_type varchar(50) default NULL,
  f6_mandatory char(3) default NULL,
  f6_length varchar(5) default NULL,
  f6_filename varchar(50) default NULL,
  f7_caption varchar(50) default NULL,
  f7_type varchar(50) default NULL,
  f7_mandatory char(3) default NULL,
  f7_length varchar(5) default NULL,
  f7_filename varchar(50) default NULL,
  f8_caption varchar(50) default NULL,
  f8_type varchar(50) default NULL,
  f8_mandatory char(3) default NULL,
  f8_length varchar(5) default NULL,
  f8_filename varchar(50) default NULL,
  f9_caption varchar(50) default NULL,
  f9_type varchar(50) default NULL,
  f9_mandatory char(3) default NULL,
  f9_length varchar(5) default NULL,
  f9_filename varchar(50) default NULL,
  f10_caption varchar(50) default NULL,
  f10_type varchar(50) default NULL,
  f10_mandatory char(3) default NULL,
  f10_length varchar(5) default NULL,
  f10_filename varchar(50) default NULL,
  f11_caption varchar(50) default NULL,
  f11_type varchar(50) default NULL,
  f11_mandatory char(3) default NULL,
  f11_length varchar(5) default NULL,
  f11_filename varchar(50) default NULL,
  f12_caption varchar(50) default NULL,
  f12_type varchar(50) default NULL,
  f12_mandatory char(3) default NULL,
  f12_length varchar(5) default NULL,
  f12_filename varchar(50) default NULL,
  f13_caption varchar(50) default NULL,
  f13_type varchar(50) default NULL,
  f13_mandatory char(3) default NULL,
  f13_length varchar(5) default NULL,
  f13_filename varchar(50) default NULL,
  f14_caption varchar(50) default NULL,
  f14_type varchar(50) default NULL,
  f14_mandatory char(3) default NULL,
  f14_length varchar(5) default NULL,
  f14_filename varchar(50) default NULL,
  f15_caption varchar(50) default NULL,
  f15_type varchar(50) default NULL,
  f15_mandatory char(3) default NULL,
  f15_length varchar(5) default NULL,
  f15_filename varchar(50) default NULL,
  PRIMARY KEY  (tplid)
) TYPE=MyISAM;

#
# Data-ark for tabell `template`
#

INSERT INTO template VALUES (1, '-', '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', NULL);
# --------------------------------------------------------

#
# Tabell-struktur for tabell `user`
#

CREATE TABLE user (
  userid int(11) NOT NULL default '0',
  name varchar(100) default NULL,
  adressfield1 varchar(100) default NULL,
  adressfield2 varchar(100) default NULL,
  adressfield3 varchar(100) default NULL,
  phone varchar(30) default NULL,
  email varchar(50) NOT NULL default '',
  pass varchar(12) default NULL,
  registered timestamp(8) NOT NULL,
  emelding tinyint(4) default NULL,
  num_ads int(11) default '0',
  country varchar(50) default NULL,
  hide_email tinyint(4) default NULL,
  custom_1 varchar(50) default NULL,
  verify int(11) default NULL,
  usr_1 varchar(150) default NULL,
  usr_2 varchar(150) default NULL,
  usr_3 varchar(150) default NULL,
  usr_4 varchar(150) default NULL,
  usr_5 varchar(150) default NULL,
  password_enc varchar(30) default NULL,
  credits int(11) default '0',
  status tinyint(4) default NULL,
  PRIMARY KEY  (email)
) TYPE=MyISAM;

#
# Data-ark for tabell `user`
#


