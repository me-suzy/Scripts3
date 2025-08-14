INSERT into `xcart_config` VALUES ('shownewestlinks','Show links to newest products page?','Y','Appearance','4','Checkbox');
INSERT into `xcart_config` VALUES ('shownewest','Show newest Products on home page?','Y','Appearance','5','Checkbox');
INSERT into `xcart_config` VALUES ('showfeatured','Show featured Products on home page?','Y','Appearance','7','Checkbox');
INSERT into `xcart_config` VALUES ('number_newest','Number of Newest on Home page','4','Appearance','6','Text');

INSERT into `xcart_config` VALUES ('shownewestmenu','Show newest Products link in menu?','Y','Appearance','3','Checkbox');

INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('US', 'No newest items', 'txt_no_newest', 'There have been no items added in this period.', 'text');
INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('US', 'newest items', 'lbl_newest_products', 'Newest Products', 'lbl');