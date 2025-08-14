-- Database: `phplogin`

CREATE TABLE `sessions` (
  `session` varchar(32) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `user_name` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`session`)
) TYPE=MyISAM;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(15) NOT NULL default '',
  `user_pass` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

INSERT INTO `users` VALUES (1, 'test', 'test');