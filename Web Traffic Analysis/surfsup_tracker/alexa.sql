CREATE TABLE `useragent` (
  `alexa_id` int(100) NOT NULL auto_increment,
  `alexa_agent` text NOT NULL,
  `alexa_ip` text NOT NULL,
  `import_date` timestamp(14) NOT NULL,
  PRIMARY KEY  (`alexa_id`)
);