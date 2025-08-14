-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 22, 2004 at 10:13 PM
-- Server version: 4.1.4
-- PHP Version: 4.3.8
-- 
-- Database: `signup-test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `authteam`
-- 

CREATE TABLE `authteam` (
  `id` int(4) NOT NULL auto_increment,
  `teamname` varchar(25) NOT NULL default '',
  `teamlead` varchar(25) NOT NULL default '',
  `status` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `teamname` (`teamname`,`teamlead`)
);

-- 
-- Dumping data for table `authteam`
-- 

INSERT INTO `authteam` (`id`, `teamname`, `teamlead`, `status`) VALUES (1, 'Ungrouped', 'sa', 'active');
INSERT INTO `authteam` (`id`, `teamname`, `teamlead`, `status`) VALUES (2, 'Admin', 'sa', 'active');
INSERT INTO `authteam` (`id`, `teamname`, `teamlead`, `status`) VALUES (3, 'Temporary', 'sa', 'active');
INSERT INTO `authteam` (`id`, `teamname`, `teamlead`, `status`) VALUES (7, 'Group 1', 'sa', 'active');
INSERT INTO `authteam` (`id`, `teamname`, `teamlead`, `status`) VALUES (8, 'Group 2', 'test', 'active');
INSERT INTO `authteam` (`id`, `teamname`, `teamlead`, `status`) VALUES (9, 'Group 3', 'admin', 'active');

-- --------------------------------------------------------

-- 
-- Table structure for table `authuser`
-- 

CREATE TABLE `authuser` (
  `id` int(11) NOT NULL auto_increment,
  `uname` varchar(25) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `team` varchar(25) NOT NULL default '',
  `level` int(4) NOT NULL default '0',
  `status` varchar(10) NOT NULL default '',
  `lastlogin` datetime default NULL,
  `logincount` int(11) default NULL,
  PRIMARY KEY  (`id`)
);

-- 
-- Dumping data for table `authuser`
-- 

INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (1, 'sa', '9df3b01c60df20d13843841ff0d4482c', 'Admin', 1, 'active', '2004-11-22 22:09:35', 11);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (2, 'admin', '9df3b01c60df20d13843841ff0d4482c', 'Admin', 1, 'active', '2003-03-29 17:17:21', 0);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (3, 'test', '9df3b01c60df20d13843841ff0d4482c', 'Temporary', 999, 'active', '2004-11-22 21:56:38', 1);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (11, 'G1-0001', '9df3b01c60df20d13843841ff0d4482c', 'Group 1', 5, 'active', '2003-04-04 10:59:02', 0);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (12, 'G1-0002', '9df3b01c60df20d13843841ff0d4482c', 'Group 1', 2, 'active', '0000-00-00 00:00:00', 0);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (13, 'G2-0001', '9df3b01c60df20d13843841ff0d4482c', 'Group 2', 5, 'active', '2003-04-03 00:46:20', 0);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (14, 'G2-0002', '9df3b01c60df20d13843841ff0d4482c', 'Group 2', 6, 'active', '2003-04-03 00:48:04', 0);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (15, 'G2-0003', '9df3b01c60df20d13843841ff0d4482c', 'Group 2', 3, 'active', '2003-04-04 10:31:16', 0);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (16, 'G3-0001', '9df3b01c60df20d13843841ff0d4482c', 'Group 3', 10, 'active', '0000-00-00 00:00:00', 0);
INSERT INTO `authuser` (`id`, `uname`, `passwd`, `team`, `level`, `status`, `lastlogin`, `logincount`) VALUES (17, 'G3-0002', '9df3b01c60df20d13843841ff0d4482c', 'Group 3', 4, 'active', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `emailer`
-- 

CREATE TABLE `emailer` (
  `id` int(11) NOT NULL auto_increment,
  `profile` varchar(20) NOT NULL default '',
  `email` varchar(40) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `emailmessage` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`,`profile`),
  KEY `profile` (`profile`)
);

-- 
-- Dumping data for table `emailer`
-- 

INSERT INTO `emailer` (`id`, `profile`, `email`, `name`, `subject`, `emailmessage`) VALUES (2, 'Default', 'membership@domain.com', 'Membership', 'Your Membership Confirmation', '---\r\nThis is an automated email message. If you are not the intended recipient of this email, please disregard this and accept our apologies for any inconvenience caused.\r\n---\r\n\r\nHi [[FNAME]]!\r\n\r\nYour account has been created. Below are the your details as entered during the signup process. We suggest you keep this information safe so that you can refer to it in case you forget your password (although we do have a password reminder tool).\r\n\r\nUsername: [[UNAME]]\r\nPassword: [[PASSWD]]\r\n\r\nFirst Name: [[FNAME]]\r\nLast Name: [[LNAME]]\r\nEmail Address: [[EMAIL]]\r\n\r\nRegards,\r\nMembership Department');
INSERT INTO `emailer` (`id`, `profile`, `email`, `name`, `subject`, `emailmessage`) VALUES (5, 'Password Reminder', 'membership@domain.com', 'Membership', 'Your Password Reminder', '---\r\nThis is an automated email message. If you are not the intended recipient of this email, please disregard this and accept our apologies for any inconvenience caused.\r\n---\r\n\r\nHi [[FNAME]]!\r\n\r\nYou have recently requested to have your password emailed to you via this email address ([[EMAIL]]). \r\n\r\nWe do not have a way of getting your current password because it is encrypted (which means your password is really secured) so we generated a new one for you. Take this as a temporary password and change it as soon as you login.\r\n\r\nYour new password is: [[NEWPASS]]\r\n\r\nIf you encounter any problems regarding this, feel free to contact us via our website. We will be more than willing to help you out.\r\n\r\nThank you and have a wonderful day!\r\n\r\nSincerely,\r\nMembership Department');
INSERT INTO `emailer` (`id`, `profile`, `email`, `name`, `subject`, `emailmessage`) VALUES (6, 'Notify and Confirm', 'membership@domain.com', 'Membership', 'Member Activation', '---\r\nThis is an automated email message. If you are not the intended recipient of this email, please disregard this and accept our apologies for any inconvenience caused.\r\n---\r\n\r\nHi [[FNAME]]!\r\n\r\nYour account has been created but you would need to confirm it first before you can have access to the protected area.\r\n\r\nPlease click [[CONFIRM]] to confirm your membership. If your email client does not support HTML messages, open a new browser then copy the above URL and paste it in the location bar. \r\n\r\nIf you encounter any problems, let us know so that we can help you out.\r\n\r\nRegards,\r\nMembership Department');

-- --------------------------------------------------------

-- 
-- Table structure for table `signup`
-- 

CREATE TABLE `signup` (
  `id` int(11) NOT NULL auto_increment,
  `uname` varchar(25) NOT NULL default '',
  `fname` varchar(30) NOT NULL default '',
  `lname` varchar(20) NOT NULL default '',
  `email` varchar(45) NOT NULL default '',
  `country` varchar(20) default NULL,
  `zipcode` bigint(20) default NULL,
  `datejoined` datetime default NULL,
  `confirmkey` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

-- 
-- Dumping data for table `signup`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `signupsetup`
-- 

CREATE TABLE `signupsetup` (
  `id` int(11) NOT NULL auto_increment,
  `validemail` text,
  `profile` varchar(20) NOT NULL default '',
  `defaultgroup` varchar(25) NOT NULL default '',
  `defaultlevel` int(4) NOT NULL default '0',
  `autoapprove` tinyint(4) NOT NULL default '0',
  `autosend` tinyint(4) NOT NULL default '0',
  `autosendadmin` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
);

-- 
-- Dumping data for table `signupsetup`
-- 

INSERT INTO `signupsetup` (`id`, `validemail`, `profile`, `defaultgroup`, `defaultlevel`, `autoapprove`, `autosend`, `autosendadmin`) VALUES (1, '', 'Default', 'Ungrouped', 999, 0, 1, 0);
