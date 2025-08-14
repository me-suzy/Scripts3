INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('US', 'There are no FAQs at the moment', 'txt_no_faqs', 'There are no FAQs at the moment', 'txt');
INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('FR', 'There are no FAQs at the moment', 'txt_no_faqs', 'There are no FAQs at the moment', 'txt');
INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('DE', 'There are no FAQs at the moment', 'txt_no_faqs', 'There are no FAQs at the moment', 'txt');
INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('SE', 'There are no FAQs at the moment', 'txt_no_faqs', 'There are no FAQs at the moment', 'txt');

CREATE TABLE `xcart_faq` (
  `faqid` int(11) NOT NULL auto_increment,
  `faqHead` varchar(255) default NULL,
  `faqBody` text,
  `enabled` char(1) NOT NULL default 'Y',
  `orderby` int(5) NOT NULL default '0',
  PRIMARY KEY  (`faqid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

INSERT INTO `xcart_modules` ( `moduleid` , `module_name` , `module_descr` , `active` ) 
VALUES (
'49', 'faq', 'faq Module', 'Y'
);