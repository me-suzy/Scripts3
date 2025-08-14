CREATE TABLE `xcart_news` (
  `newsid` int(11) NOT NULL auto_increment,
  `newsHead` varchar(255) default NULL,
  `newsBody` text,
  `enabled` char(1) NOT NULL default 'Y',
  `orderby` int(5) NOT NULL default '0',
  PRIMARY KEY  (`newsid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

INSERT INTO `xcart_modules` ( `moduleid` , `module_name` , `module_descr` , `active` ) 
VALUES (
'48', 'News', 'News Module', 'Y'
);

