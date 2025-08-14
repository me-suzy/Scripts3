CREATE TABLE foo_sessions (
autoid int(11) NOT NULL auto_increment,
sid varchar(100) NOT NULL default '',
data text NOT NULL,
addr varchar(100) NOT NULL default '',
opened int(14) default NULL,
expire int(14) default NULL,
PRIMARY KEY (autoid),
) TYPE=MyISAM;
