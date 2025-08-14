# MySQL-Front Dump 2.1
#
# Host: localhost   Database: -beta
#--------------------------------------------------------
# Server version 3.23.40-max-nt


#
# Table structure for table 'aboo_contact'
#

DROP TABLE IF EXISTS `aboo_contact`;
CREATE TABLE `aboo_contact` (
  `cID` int(11) NOT NULL auto_increment,
  `uID_FK` int(11) NOT NULL default '0',
  `cfirstname` varchar(50) default NULL,
  `cname` varchar(50) NOT NULL default '',
  `caddr1` varchar(100) default NULL,
  `caddr2` varchar(100) default NULL,
  `czip` varchar(10) default NULL,
  `ccity` varchar(50) default NULL,
  `ccountry` varchar(50) default NULL,
  `cemail` varchar(50) default NULL,
  `cinstantmsg` varchar(50) default NULL,
  `cwww` varchar(100) default NULL,
  `cphone` varchar(50) default NULL,
  `ccell` varchar(50) default NULL,
  `cphonepro` varchar(50) default NULL,
  `cfax` varchar(50) default NULL,
  `cfirm` varchar(50) default NULL,
  `cposition` varchar(50) default NULL,
  `cmisc` varchar(255) default NULL,
  `cprivacy` varchar(10) NOT NULL default 'PRIVATE',
  `ccategory` int(2) NOT NULL default '0',
  UNIQUE KEY `cID` (`cID`)
) TYPE=MyISAM;



#
# Dumping data for table 'aboo_contact'
#


#
# Table structure for table 'aboo_contact_domain'
#

DROP TABLE IF EXISTS `aboo_contact_domain`;
CREATE TABLE `aboo_contact_domain` (
  `cID_FK` int(11) NOT NULL default '0',
  `dID_FK` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cID_FK`,`dID_FK`)
) TYPE=MyISAM;



#
# Dumping data for table 'aboo_contact_domain'
#


#
# Table structure for table 'aboo_domain'
#

DROP TABLE IF EXISTS `aboo_domain`;
CREATE TABLE `aboo_domain` (
  `dID` int(11) NOT NULL auto_increment,
  `dname` varchar(50) NOT NULL default '',
  `dmisc` varchar(255) default NULL,
  UNIQUE KEY `d_ID` (`dID`)
) TYPE=MyISAM;



#
# Dumping data for table 'aboo_domain'
#


#
# Table structure for table 'aboo_user'
#

DROP TABLE IF EXISTS `aboo_user`;
CREATE TABLE `aboo_user` (
  `uID` int(11) NOT NULL auto_increment,
  `ulogin` varchar(50) NOT NULL default '',
  `upasswd` varchar(20) NOT NULL default '',
  `ufirstname` varchar(50) default NULL,
  `uname` varchar(50) NOT NULL default '',
  `uemail` varchar(50) default NULL,
  `ugodlike` varchar(10) NOT NULL default 'NO',
  `ucategories` varchar(255) NOT NULL default '',
  `upreferences` varchar(255) NOT NULL default '',
  `ulist_preferences` varchar(255) NOT NULL default '',
  `ualt_preferences` varchar(255) NOT NULL default '',
  `umaxcontacts` int(4) NOT NULL default '0',
  `ucolor_preferences` varchar(255) NOT NULL default '',
  `ulanguage` varchar(5) NOT NULL default '',
  UNIQUE KEY `uID` (`uID`)
) TYPE=MyISAM;



#
# Dumping data for table 'aboo_user'
#
INSERT INTO `aboo_user` VALUES("1","godlike","godlike","firstname","Name","youremail@yourdomain.com","YES","Home,Pro.","priCtOn,pubCtOn,domCtOn,FIELD_ALL,POSITION_ANYWHERE,-1,AS_TYPED_CASE,50,confirmDelOn","0,1,-1,-1","7,10,11,-1,-1,-1","500","#7B8590,#BAC2C7,#3E4860,12,#883322,12","en");


#
# Table structure for table 'aboo_user_domain'
#

DROP TABLE IF EXISTS `aboo_user_domain`;
CREATE TABLE `aboo_user_domain` (
  `uID_FK` int(11) NOT NULL default '0',
  `dID_FK` int(11) NOT NULL default '0',
  `uadminright` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`uID_FK`,`dID_FK`)
) TYPE=MyISAM;



#
# Dumping data for table 'aboo_user_domain'
#
