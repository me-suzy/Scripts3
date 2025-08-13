# phpMyAdmin MySQL-Dump
#
# Host: localhost
# Generation Time: Mar 06, 2002 at 12:15 PM
# Database : `relata`
# --------------------------------------------------------

#
# Table structure for table `account`
#

CREATE TABLE account (
  user_id varchar(32) NOT NULL default '',
  account_id int(11) NOT NULL auto_increment,
  account_name varchar(250) NOT NULL default '0',
  account_address varchar(250) NOT NULL default '0',
  account_city varchar(250) NOT NULL default '',
  account_state varchar(250) NOT NULL default '',
  account_zip varchar(10) NOT NULL default '0',
  account_country varchar(250) NOT NULL default '',
  account_website varchar(250) NOT NULL default '',
  account_phone varchar(250) NOT NULL default '',
  account_fax varchar(250) NOT NULL default '',
  timedate_create datetime NOT NULL default '0000-00-00 00:00:00',
  account_status tinyint(4) NOT NULL default '0',
  account_desc text NOT NULL,
  PRIMARY KEY (account_id),
  KEY user_id(user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `active_sessions`
#

CREATE TABLE active_sessions (
  sid varchar(32) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  val text,
  changed varchar(14) NOT NULL default '',
  PRIMARY KEY (name,sid),
  KEY changed(changed)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `activity`
#

CREATE TABLE activity (
  user_id varchar(32) NOT NULL default '',
  activity_id int(11) NOT NULL auto_increment,
  activity_desc text NOT NULL,
  date date NOT NULL default '0000-00-00',
  starttime time NOT NULL default '00:00:00',
  endtime time NOT NULL default '00:00:00',
  priority tinyint(4) NOT NULL default '0',
  is_calendar_item char(1) NOT NULL default '',
  palm_record_id int(8) NOT NULL default '0',
  ismodified varchar(5) NOT NULL default 'false',
  isarchived varchar(5) NOT NULL default 'false',
  isnew varchar(5) NOT NULL default 'true',
  isdeleted varchar(5) NOT NULL default 'false',
  isprivate varchar(5) NOT NULL default 'false',
  entry_date varchar(10) NOT NULL default '',
  entry_time varchar(10) NOT NULL default '',
  last_mod_date varchar(10) NOT NULL default '',
  last_mod_time varchar(10) NOT NULL default '',
  note text NOT NULL,
  catindex int(5) NOT NULL default '0',
  iscompleted varchar(5) NOT NULL default 'false',
  isuntimed varchar(5) NOT NULL default 'false',
  isalarmed varchar(5) NOT NULL default 'false',
  alarmadvtime int(2) NOT NULL default '0',
  alarmadvunit int(2) NOT NULL default '0',
  isrepeating varchar(5) NOT NULL default 'false',
  repeattype int(2) NOT NULL default '0',
  repeatenddate date NOT NULL default '0000-00-00',
  repeatfrequency int(2) NOT NULL default '1',
  repeaton varchar(90) NOT NULL default '0',
  repeatstartweek int(2) NOT NULL default '0',
  PRIMARY KEY (user_id,activity_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contact`
#

CREATE TABLE contact (
  user_id varchar(32) NOT NULL default '',
  contact_id int(11) NOT NULL auto_increment,
  fname varchar(250) NOT NULL default '',
  mname varchar(250) NOT NULL default '',
  lname varchar(250) NOT NULL default '',
  prefix varchar(5) NOT NULL default '',
  title varchar(20) NOT NULL default '',
  company varchar(250) NOT NULL default '',
  hm_address varchar(250) NOT NULL default '',
  hm_city varchar(250) NOT NULL default '',
  hm_state varchar(250) NOT NULL default '',
  hm_zip varchar(15) NOT NULL default '',
  hm_country varchar(250) NOT NULL default '',
  bus_address varchar(250) NOT NULL default '',
  bus_city varchar(250) NOT NULL default '',
  bus_state varchar(250) NOT NULL default '',
  bus_zip varchar(15) NOT NULL default '',
  bus_country varchar(250) NOT NULL default '',
  alt_address varchar(250) NOT NULL default '',
  alt_city varchar(250) NOT NULL default '',
  alt_state varchar(250) NOT NULL default '',
  alt_zip varchar(15) NOT NULL default '',
  alt_country varchar(250) NOT NULL default '',
  website1 varchar(250) NOT NULL default '',
  website2 varchar(250) NOT NULL default '',
  palm_custfield1 varchar(250) NOT NULL default '',
  palm_custfield2 varchar(250) NOT NULL default '',
  palm_custfield3 varchar(250) NOT NULL default '',
  palm_custfield4 varchar(250) NOT NULL default '',
  palm_notes text NOT NULL,
  palm_category int(11) NOT NULL default '0',
  palm_id int(11) NOT NULL default '0',
  user_notes text NOT NULL,
  email_type char(1) NOT NULL default '',
  is_prospect char(1) NOT NULL default '',
  entry_date varchar(25) NOT NULL default '',
  entry_time varchar(25) NOT NULL default '',
  ismodified varchar(5) NOT NULL default 'false',
  last_mod_date varchar(25) NOT NULL default '',
  last_mod_time varchar(25) NOT NULL default '',
  palm_dphone tinyint(2) NOT NULL default '1',
  contact_lbl1 tinyint(4) NOT NULL default '0',
  contact_lbl2 tinyint(4) NOT NULL default '1',
  contact_lbl3 tinyint(4) NOT NULL default '2',
  contact_lbl4 tinyint(4) NOT NULL default '3',
  contact_lbl5 tinyint(4) NOT NULL default '4',
  contact_val1 varchar(250) NOT NULL default '',
  contact_val2 varchar(250) NOT NULL default '',
  contact_val3 varchar(250) NOT NULL default '',
  contact_val4 varchar(250) NOT NULL default '',
  contact_val5 varchar(250) NOT NULL default '',
  palm_record_id int(11) NOT NULL default '0',
  isarchived varchar(5) NOT NULL default 'false',
  isdeleted varchar(5) NOT NULL default 'false',
  isnew varchar(5) NOT NULL default 'true',
  isprivate varchar(5) NOT NULL default 'false',
  palm_catname varchar(16) NOT NULL default 'Unfiled',
  PRIMARY KEY (contact_id,user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contact_account`
#

CREATE TABLE contact_account (
  contact_id int(11) NOT NULL default '0',
  account_id int(11) NOT NULL default '0',
  PRIMARY KEY (contact_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contact_group`
#

CREATE TABLE contact_group (
  contact_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  PRIMARY KEY (contact_id,group_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contact_opportunity`
#

CREATE TABLE contact_opportunity (
  contact_id int(11) NOT NULL default '0',
  opp_id int(11) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contact_xfield`
#

CREATE TABLE contact_xfield (
  contact_id int(11) NOT NULL default '0',
  xfield_id int(11) NOT NULL default '0',
  xfield_value varchar(250) NOT NULL default '0',
  PRIMARY KEY (contact_id,xfield_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `extra_field`
#

CREATE TABLE extra_field (
  user_id varchar(32) NOT NULL default '0',
  xfield_id int(11) NOT NULL auto_increment,
  xfield_name varchar(250) NOT NULL default '0',
  PRIMARY KEY (user_id,xfield_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `group_message_vars`
#

CREATE TABLE group_message_vars (
  user_id varchar(32) NOT NULL default '',
  name varchar(40) NOT NULL default '',
  value text NOT NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `groups`
#

CREATE TABLE groups (
  user_id varchar(32) NOT NULL default '0',
  group_id int(11) NOT NULL auto_increment,
  group_name varchar(250) NOT NULL default '0',
  group_desc text NOT NULL,
  PRIMARY KEY (group_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `opportunity`
#

CREATE TABLE opportunity (
  user_id varchar(32) NOT NULL default '0',
  opp_id int(11) NOT NULL auto_increment,
  ss_id int(11) NOT NULL default '0',
  opp_title varchar(250) NOT NULL default '',
  entry_date date NOT NULL default '0000-00-00',
  close_date varchar(250) NOT NULL default '',
  opp_desc text NOT NULL,
  potential_revenue varchar(11) NOT NULL default '0',
  probability tinyint(4) NOT NULL default '0',
  PRIMARY KEY (opp_id,user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `relata_user`
#

CREATE TABLE relata_user (
  user_id varchar(32) NOT NULL default '',
  login varchar(250) NOT NULL default '',
  password varchar(250) NOT NULL default '',
  login_date date default NULL,
  login_time time default NULL,
  PRIMARY KEY (user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `sales_stage`
#

CREATE TABLE sales_stage (
  ss_id tinyint(4) NOT NULL default '0',
  ss_title varchar(250) NOT NULL default '0',
  PRIMARY KEY (ss_id)
) TYPE=MyISAM;

