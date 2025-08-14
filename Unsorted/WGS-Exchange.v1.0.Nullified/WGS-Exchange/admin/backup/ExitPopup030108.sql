# ExitPopup 1.0.1 MySQL Dump

DROP TABLE IF EXISTS blacklist;
CREATE TABLE blacklist (  id int(10) NOT NULL auto_increment,  type char(20) NOT NULL,  content char(255) NOT NULL,  PRIMARY KEY (id));



DROP TABLE IF EXISTS emails;
CREATE TABLE emails (  id int(10) NOT NULL auto_increment,  name varchar(8) NOT NULL,  subject varchar(255) NOT NULL,  message text NOT NULL,  type varchar(20) NOT NULL,  PRIMARY KEY (id));

INSERT INTO emails VALUES ( '1', 'approve', 'Your account has been approved!', 'Hello [%name%],\r\n\r\nWelcome to our Exit-Popup Exchange!\r\n\r\nAccount: [%account%]\r\nPassword: [%password%]\r\n\r\nPlease go to the following URL to get your HTML code:\r\n[%appurl%]\r\n\r\nRegards\r\nExitPopup Webmaster', 'system' );
INSERT INTO emails VALUES ( '2', 'signup', 'Your Popup-Exchange Account', 'Hello [%name%],\r\n\r\nHere is the information you will need to activate your account!\r\n\r\nAccount: [%account%]\r\nPassword: [%password%]\r\n\r\nTo activate it go to [%acturl%] and enter your password there!\r\n\r\nRegards\r\nExitPopup Webmaster', 'system' );
INSERT INTO emails VALUES ( '3', 'none', 'Your account has been deleted!', 'Hello [%name%],\r\n\r\nUnfortunately we have to inform you that your account [%account%] has been deleted.\r\n\r\nRegards\r\nExitPopup Webmaster', 'system' );
INSERT INTO emails VALUES ( '4', 'notify', 'New Account Notification', 'If you are moderating new signups you should have a look at the queue:\r\n\r\nAccount: [%account%]\r\nURL: [%url%]\r\n', 'system' );
INSERT INTO emails VALUES ( '5', 'lostpw', 'Your Requested Password', 'Here is the information you have requested:\r\n\r\nAccount: [%account%]\r\nPassword: [%password%]\r\n\r\nRegards\r\nExitPopup Webmaster', 'system' );


DROP TABLE IF EXISTS mods;
CREATE TABLE mods (  id int(10) NOT NULL auto_increment,  username char(10) NOT NULL,  password char(10) NOT NULL,  email char(80) NOT NULL,  super int(1) DEFAULT '0' NOT NULL,  process int(1) DEFAULT '0' NOT NULL,  setup int(1) DEFAULT '0' NOT NULL,  html int(1) DEFAULT '0' NOT NULL,  blacklist int(1) DEFAULT '0' NOT NULL,  mail int(1) DEFAULT '0' NOT NULL,  moderator int(1) DEFAULT '0' NOT NULL,  PRIMARY KEY (id));

INSERT INTO mods VALUES ( '1', 'admin', 'pass', 'contact@yourdomain.com', '1', '0', '0', '0', '0', '0', '0' );


DROP TABLE IF EXISTS options;
CREATE TABLE options (  version char(5) NOT NULL,  scripturl char(255) NOT NULL,  adminemail char(255) NOT NULL,  ratio char(10) NOT NULL,  checkdup int(1) DEFAULT '0' NOT NULL,  moderate int(1) DEFAULT '0' NOT NULL,  timeoffset int(10) DEFAULT '0' NOT NULL,  dateformat char(10) NOT NULL,  timeformat char(10) NOT NULL,  sitetitle char(50) NOT NULL,  verifyurl int(1) DEFAULT '0' NOT NULL,  verifyemail int(1) DEFAULT '0' NOT NULL,  notify int(1) DEFAULT '0' NOT NULL,  signup int(1) DEFAULT '0' NOT NULL,  credits int(10) DEFAULT '0' NOT NULL,  hours int(2) DEFAULT '0' NOT NULL);

INSERT INTO options VALUES ( '1.0.1', 'http://www.yourdomain.com/', 'contact@yourdomain.com', '7', '1', '0', '2', 'F d', 'h:i a', 'ExitPopup Exhange', '1', '0', '1', '1', '1000', '3' );


DROP TABLE IF EXISTS accounts;
CREATE TABLE accounts (  id int(10) NOT NULL auto_increment,  account char(255) NOT NULL,  name char(255) NOT NULL,  password char(255) NOT NULL,  title char(255) NOT NULL,  url char(255) NOT NULL,  email char(255) NOT NULL,  type int(1) DEFAULT '0' NOT NULL,  active int(1) DEFAULT '0' NOT NULL,  status char(10) NOT NULL,  time int(10) DEFAULT '0' NOT NULL,  apptime int(10) DEFAULT '0' NOT NULL,  moderator char(255) NOT NULL,  lastuse int(10) DEFAULT '0' NOT NULL,  ins int(10) DEFAULT '0' NOT NULL,  out int(10) DEFAULT '0' NOT NULL,  credits int(10) DEFAULT '0' NOT NULL,  ip char(15) NOT NULL,  PRIMARY KEY (id));



DROP TABLE IF EXISTS sites;
CREATE TABLE sites (  id int(10) NOT NULL auto_increment,  url char(255) NOT NULL,  type char(1) NOT NULL,  out int(10) DEFAULT '0' NOT NULL,  PRIMARY KEY (id));

INSERT INTO sites VALUES ( '1', 'http://www.yourdomain.com', '', '0' );


