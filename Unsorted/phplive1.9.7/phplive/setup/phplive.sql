#
# Table structure for table `chat_admin`
#

DROP TABLE IF EXISTS chat_admin;
CREATE TABLE chat_admin (
  userID int(10) unsigned NOT NULL auto_increment,
  login varchar(15) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  name varchar(50) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  company varchar(50) NOT NULL default '',
  available_status tinyint(1) NOT NULL default '0',
  last_active_time int(10) unsigned NOT NULL default '0',
  console_close_min mediumint(3) unsigned NOT NULL default '10',
  session_sid int(10) unsigned NOT NULL default '0',
  created int(10) unsigned NOT NULL default '0',
  aspID int(10) unsigned NOT NULL default '1',
  signal tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (userID),
  KEY created (created),
  KEY aspID (aspID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chat_asp`
#

DROP TABLE IF EXISTS chat_asp;
CREATE TABLE chat_asp (
  aspID int(10) unsigned NOT NULL auto_increment,
  login varchar(15) NOT NULL default '',
  password varchar(15) NOT NULL default '',
  company varchar(50) NOT NULL default '',
  contact_name varchar(50) NOT NULL default '',
  contact_email varchar(160) NOT NULL default '',
  max_dept mediumint(3) NOT NULL default '0',
  max_users mediumint(3) NOT NULL default '0',
  footprints tinyint(1) NOT NULL default '0',
  created int(10) unsigned NOT NULL default '0',
  last_login int(10) unsigned NOT NULL default '0',
  active_status tinyint(1) NOT NULL default '0',
  initiate_chat tinyint(1) NOT NULL default '0',
  trans_message varchar(255) NOT NULL default '',
  trans_email text NOT NULL,
  PRIMARY KEY  (aspID),
  KEY created (created),
  KEY max_dept (max_dept),
  KEY max_users (max_users),
  KEY active_status (active_status)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatcanned`
#

DROP TABLE IF EXISTS chatcanned;
CREATE TABLE chatcanned (
  cannedID int(10) unsigned NOT NULL auto_increment,
  userID int(10) unsigned NOT NULL default '0',
  deptID int(10) unsigned NOT NULL default '0',
  type char(1) NOT NULL default '',
  name varchar(20) NOT NULL default '',
  message mediumtext NOT NULL,
  PRIMARY KEY  (cannedID),
  KEY userID (userID),
  KEY type (type),
  KEY deptID (deptID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatdepartments`
#

DROP TABLE IF EXISTS chatdepartments;
CREATE TABLE chatdepartments (
  deptID int(10) unsigned NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  transcript_save tinyint(1) NOT NULL default '0',
  transcript_share tinyint(1) NOT NULL default '0',
  transcript_expire_string varchar(10) NOT NULL default '',
  transcript_expire int(10) NOT NULL default '0',
  email varchar(150) NOT NULL default '',
  aspID int(10) unsigned NOT NULL default '1',
  initiate_chat tinyint(1) NOT NULL default '0',
  status_image_offline varchar(20) NOT NULL default '',
  status_image_online varchar(20) NOT NULL default '',
  message mediumtext NOT NULL,
  greeting text NOT NULL,
  PRIMARY KEY  (deptID),
  KEY aspID (aspID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatfootprints`
#

DROP TABLE IF EXISTS chatfootprints;
CREATE TABLE chatfootprints (
  printID int(10) unsigned NOT NULL auto_increment,
  ip varchar(20) NOT NULL default '',
  created int(10) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL default '',
  aspID int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (printID),
  KEY ip (ip),
  KEY created (created),
  KEY aspID (aspID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatfootprintstats`
#

DROP TABLE IF EXISTS chatfootprintstats;
CREATE TABLE chatfootprintstats (
  aspID int(10) unsigned NOT NULL default '0',
  statdate int(10) unsigned NOT NULL default '0',
  created int(10) unsigned NOT NULL default '0',
  pageviews int(10) unsigned NOT NULL default '0',
  uniquevisits int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (aspID,statdate)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatfootprintsunique`
#

DROP TABLE IF EXISTS chatfootprintsunique;
CREATE TABLE chatfootprintsunique (
  ip varchar(20) NOT NULL default '',
  created int(10) unsigned NOT NULL default '0',
  updated int(10) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL default '',
  aspID int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (ip,aspID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatfootprinturlstats`
#

DROP TABLE IF EXISTS chatfootprinturlstats;
CREATE TABLE chatfootprinturlstats (
  statID int(10) unsigned NOT NULL auto_increment,
  aspID int(10) unsigned NOT NULL default '0',
  statdate int(10) unsigned NOT NULL default '0',
  created int(10) unsigned NOT NULL default '0',
  url char(255) NOT NULL default '',
  clicks int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (statID),
  KEY aspID (aspID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatrefer`
#

DROP TABLE IF EXISTS chatrefer;
CREATE TABLE chatrefer (
  aspID int(10) unsigned NOT NULL default '0',
  created int(10) unsigned NOT NULL default '0',
  ip char(20) NOT NULL default '',
  refer_url char(255) NOT NULL default '',
  PRIMARY KEY  (aspID,ip),
  KEY created (created)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatrequestlogs`
#

DROP TABLE IF EXISTS chatrequestlogs;
CREATE TABLE chatrequestlogs (
  chat_session char(20) NOT NULL default '',
  userID int(10) unsigned NOT NULL default '0',
  deptID tinyint(2) NOT NULL default '0',
  ip char(20) NOT NULL default '',
  hostname char(150) NOT NULL default '',
  display_resolution char(20) NOT NULL default '',
  browser_os char(60) NOT NULL default '',
  created int(10) unsigned NOT NULL default '0',
  status tinyint(1) NOT NULL default '0',
  url char(255) NOT NULL default '',
  aspID int(10) unsigned NOT NULL default '1',
  KEY userID (userID),
  KEY browser_os (browser_os),
  KEY display_resolution (display_resolution),
  KEY created (created),
  KEY status (status),
  KEY deptID (deptID),
  KEY aspID (aspID),
  KEY chat_session (chat_session)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatrequests`
#

DROP TABLE IF EXISTS chatrequests;
CREATE TABLE chatrequests (
  requestID int(10) unsigned NOT NULL auto_increment,
  userID int(10) NOT NULL default '0',
  deptID int(10) NOT NULL default '0',
  aspID int(10) unsigned NOT NULL default '0',
  from_screen_name varchar(50) NOT NULL default '',
  sessionID int(10) unsigned NOT NULL default '0',
  created int(10) unsigned NOT NULL default '0',
  status tinyint(1) NOT NULL default '1',
  ip_address varchar(20) NOT NULL default '',
  browser_type varchar(60) NOT NULL default '',
  display_resolution varchar(20) NOT NULL default '',
  visitor_time varchar(30) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  question varchar(150) NOT NULL default '',
  PRIMARY KEY  (requestID),
  KEY toID (userID),
  KEY fromID (from_screen_name),
  KEY sessionID (sessionID),
  KEY deptID (deptID),
  KEY aspID (aspID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatsessionlist`
#

DROP TABLE IF EXISTS chatsessionlist;
CREATE TABLE chatsessionlist (
  sessionID int(10) unsigned NOT NULL default '0',
  screen_name char(50) NOT NULL default '',
  updated int(10) unsigned NOT NULL default '0',
  KEY sessionID (sessionID),
  KEY userID (screen_name)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatsessions`
#

DROP TABLE IF EXISTS chatsessions;
CREATE TABLE chatsessions (
  sessionID int(10) unsigned NOT NULL default '0',
  screen_name char(50) NOT NULL default '',
  created int(10) unsigned NOT NULL default '0',
  initiate char(15) NOT NULL default '',
  PRIMARY KEY  (sessionID),
  KEY userID (screen_name)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chattranscripts`
#

DROP TABLE IF EXISTS chattranscripts;
CREATE TABLE chattranscripts (
  chat_session varchar(20) NOT NULL default '',
  userID int(10) unsigned NOT NULL default '0',
  from_screen_name varchar(50) NOT NULL default '',
  created int(10) unsigned NOT NULL default '0',
  deptID tinyint(2) NOT NULL default '0',
  plain text NOT NULL,
  formatted text NOT NULL,
  aspID int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (chat_session),
  KEY deptID (deptID),
  KEY userID (userID),
  KEY aspID (aspID)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `chatuserdeptlist`
#

DROP TABLE IF EXISTS chatuserdeptlist;
CREATE TABLE chatuserdeptlist (
  userID int(10) unsigned NOT NULL default '0',
  deptID int(10) unsigned NOT NULL default '0',
  ordernum mediumint(3) unsigned NOT NULL default '0',
  KEY userID (userID),
  KEY deptID (deptID),
  KEY ordernum (ordernum)
) TYPE=MyISAM;
