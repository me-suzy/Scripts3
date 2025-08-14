CREATE TABLE `maaking_users` (
  `userid` int(11) NOT NULL auto_increment,
  `username` varchar(10) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `fullname` varchar(50) default NULL,
  `ipaddress` varchar(50) NOT NULL default '',
  `lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;