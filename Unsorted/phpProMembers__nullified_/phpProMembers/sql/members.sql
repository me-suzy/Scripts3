# MySQL-Front Dump 2.2
#
# Host: www.CyKuH.com   Database: gacybert_gacybertech
#--------------------------------------------------------
# Server version 3.23.45


#
# Table structure for table 'account_types'
#

CREATE TABLE `account_types` (
  `id` int(11) NOT NULL auto_increment,
  `account_name` varchar(255) NOT NULL default '',
  `account_setup_fee` decimal(10,2) NOT NULL default '0.00',
  `account_fee` decimal(10,2) NOT NULL default '0.00',
  `length` int(11) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '0',
  `wait_period` tinyint(4) NOT NULL default '0',
  `renewal` tinyint(4) NOT NULL default '0',
  `clickbank` varchar(255) default NULL,
  `regnow` varchar(255) default NULL,
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;



#
# Dumping data for table 'account_types'
#
INSERT INTO account_types VALUES("1","admin","0.00","0.00","9999999","0","0","0","","");



#
# Table structure for table 'members'
#

CREATE TABLE `members` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(35) NOT NULL default '',
  `last_name` varchar(35) NOT NULL default '',
  `address1` varchar(50) NOT NULL default '',
  `address2` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `postal_code` varchar(25) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `telephone_number` varchar(20) NOT NULL default '',
  `e_mail_address` varchar(35) NOT NULL default '',
  `user_name` varchar(25) NOT NULL default '',
  `user_password` varchar(25) NOT NULL default '',
  `name_on_card` varchar(50) default NULL,
  `card_number` int(20) default NULL,
  `exp_date` varchar(20) default NULL,
  `billing_address1` varchar(35) default NULL,
  `billing_address2` varchar(50) default NULL,
  `billing_city` varchar(50) default NULL,
  `billing_state` varchar(50) default NULL,
  `billing_postal_code` varchar(25) default NULL,
  `billing_country` varchar(50) default NULL,
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;



#
# Dumping data for table 'members'
#
INSERT INTO members VALUES("","God","Password","","","","","","","","info@mydomain.com","God","Password","","0","","","","","","","");


#
# Table structure for table 'members_stats'
#

CREATE TABLE `members_stats` (
  `id` int(11) NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL default '',
  `pages` varchar(50) NOT NULL default '',
  `time` timestamp(14) NOT NULL,
  `ip` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;



#
# Dumping data for table 'members_stats'
#


#
# Table structure for table 'members_waiting_approval'
#

CREATE TABLE `members_waiting_approval` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(50) NOT NULL default '',
  `last_name` varchar(50) NOT NULL default '',
  `address1` varchar(50) NOT NULL default '',
  `address2` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `postal_code` varchar(50) default NULL,
  `country` varchar(50) NOT NULL default '',
  `telephone_number` varchar(20) default NULL,
  `e_mail_address` varchar(35) NOT NULL default '',
  `user_name` varchar(35) NOT NULL default '',
  `user_password` varchar(35) NOT NULL default '',
  `payment_type` varchar(35) NOT NULL default '',
  `account_type_name` varchar(50) NOT NULL default '',
  `name_on_card` varchar(35) default NULL,
  `card_number` varchar(20) default NULL,
  `exp_date` varchar(10) default NULL,
  `billing_address1` varchar(50) default NULL,
  `billing_address2` varchar(50) default NULL,
  `billing_city` varchar(35) default NULL,
  `billing_state` varchar(35) default NULL,
  `billing_postal_code` varchar(35) default NULL,
  `billing_country` varchar(35) default NULL,
  `start_date` date NOT NULL default '0000-00-00',
  `paid_until_date` date NOT NULL default '0000-00-00',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;



#
# Dumping data for table 'members_waiting_approval'
#


#
# Table structure for table 'memberships'
#

CREATE TABLE `memberships` (
  `id` int(10) NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL default '',
  `payment_type` varchar(30) NOT NULL default '',
  `package` varchar(255) NOT NULL default '',
  `start_date` date NOT NULL default '0000-00-00',
  `paid_until_date` date NOT NULL default '0000-00-00',
  `active` tinyint(3) unsigned NOT NULL default '0',
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;



#
# Dumping data for table 'memberships'
#
INSERT INTO memberships VALUES("1","God","credit card","admin","2002-04-01","2010-04-30","1");



#
# Table structure for table 'method_of_payment'
#

CREATE TABLE `method_of_payment` (
  `id` int(11) NOT NULL auto_increment,
  `payment_type` varchar(50) NOT NULL default '',
  `sub_type` varchar(50) NOT NULL default '',
  `payment_address` text NOT NULL,
  `active` int(11) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;



#
# Dumping data for table 'method_of_payment'
#
INSERT INTO method_of_payment VALUES("1","credit card","visa","\t\t\t\t","1");
INSERT INTO method_of_payment VALUES("2","credit card","master card","\t\t\t\t","0");
INSERT INTO method_of_payment VALUES("3","credit card","discover","\t\t\t\t","0");
INSERT INTO method_of_payment VALUES("4","credit card","american express","\t\t\t\t","0");
INSERT INTO method_of_payment VALUES("5","check","","1111 SomeWhere St.\r\nSomeWhere Out There, TN 999999\t\t\t\t","0");
INSERT INTO method_of_payment VALUES("6","paypal","","\t\t\t\t","1");
INSERT INTO method_of_payment VALUES("7","clickbank","","\t\t\t\t","0");
INSERT INTO method_of_payment VALUES("8","regnow","","\t\t\t\t","0");


#
# Table structure for table 'payment_history'
#

CREATE TABLE `payment_history` (
  `id` int(3) NOT NULL auto_increment,
  `cust_id` varchar(50) NOT NULL default '0',
  `payment_date` date default '0000-00-00',
  `amount` float default '0',
  `type` varchar(50) default '0',
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;



#
# Dumping data for table 'payment_history'
#


CREATE TABLE tbl_Files (
  id_files tinyint(3) unsigned NOT NULL auto_increment,
  bin_data longblob NOT NULL,
  description tinytext NOT NULL,
  filename varchar(50) NOT NULL default '',
  filesize varchar(50) NOT NULL default '',
  filetype varchar(50) NOT NULL default '',
  PRIMARY KEY  (id_files)
) TYPE=MyISAM;
