#
# Table structure for table `users`
#

CREATE TABLE i_users (
  id int(10) unsigned NOT NULL auto_increment,
  username varchar(16) NOT NULL default '',
  passwd varchar(16) NOT NULL default '',
  email varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

