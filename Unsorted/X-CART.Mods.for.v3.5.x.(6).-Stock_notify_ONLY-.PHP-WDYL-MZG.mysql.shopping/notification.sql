INSERT INTO xcart_modules VALUES (50,'stock_notify','Allows registered users to ask to be notified when a product is back in stock','Y');

INSERT INTO xcart_languages VALUES ('DE','Notify when back in stock','lbl_stock_notify','Notify when back in stock','Labels');
INSERT INTO xcart_languages VALUES ('FR','Notify when back in stock','lbl_stock_notify','Notify when back in stock','Labels');
INSERT INTO xcart_languages VALUES ('SE','Notify when back in stock','lbl_stock_notify','Notify when back in stock','Labels');
INSERT INTO xcart_languages VALUES ('US','Notify when back in stock','lbl_stock_notify','Notify when back in stock','Labels');

INSERT INTO xcart_languages VALUES ('DE','Your registered email address will be sent notification when this product is back in stock.','txt_added_stock_notify','Your registered email address will be sent notification when this product is back in stock.','Text');
INSERT INTO xcart_languages VALUES ('FR','Your registered email address will be sent notification when this product is back in stock.','txt_added_stock_notify','Your registered email address will be sent notification when this product is back in stock.','Text');
INSERT INTO xcart_languages VALUES ('SE','Your registered email address will be sent notification when this product is back in stock.','txt_added_stock_notify','Your registered email address will be sent notification when this product is back in stock.','Text');
INSERT INTO xcart_languages VALUES ('US','Your registered email address will be sent notification when this product is back in stock.','txt_added_stock_notify','Your registered email address will be sent notification when this product is back in stock.','Text');

INSERT INTO xcart_languages VALUES ('DE','Keep me updated','lbl_stock_notify_button','Keep me updated','Labels');
INSERT INTO xcart_languages VALUES ('FR','Keep me updated','lbl_stock_notify_button','Keep me updated','Labels');
INSERT INTO xcart_languages VALUES ('SE','Keep me updated','lbl_stock_notify_button','Keep me updated','Labels');
INSERT INTO xcart_languages VALUES ('US','Keep me updated','lbl_stock_notify_button','Keep me updated','Labels');

INSERT INTO xcart_languages VALUES ('DE','The following product is now back in stock and can be ordered by clicking on the link below','eml_stock_notification','The following product is now back in stock and can be ordered by clicking on the link below','E-Mail');
INSERT INTO xcart_languages VALUES ('FR','The following product is now back in stock and can be ordered by clicking on the link below','eml_stock_notification','The following product is now back in stock and can be ordered by clicking on the link below','E-Mail');
INSERT INTO xcart_languages VALUES ('SE','The following product is now back in stock and can be ordered by clicking on the link below','eml_stock_notification','The following product is now back in stock and can be ordered by clicking on the link below','E-Mail');
INSERT INTO xcart_languages VALUES ('US','The following product is now back in stock and can be ordered by clicking on the link below','eml_stock_notification','The following product is now back in stock and can be ordered by clicking on the link below','E-Mail');

INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('US', 'add me to stock notify', 'txt_addme_notify', 'Enter your email address to be notified when this product comes back in stock', 'txt');
INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('FR', 'add me to stock notify', 'txt_addme_notify', 'Enter your email address to be notified when this product comes back in stock', 'txt');
INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('SE', 'add me to stock notify', 'txt_addme_notify', 'Enter your email address to be notified when this product comes back in stock', 'txt');
INSERT INTO `xcart_languages` ( `code` , `descr` , `name` , `value` , `topic` ) VALUES ('DE', 'add me to stock notify', 'txt_addme_notify', 'Enter your email address to be notified when this product comes back in stock', 'txt');

CREATE TABLE xcart_notify (
  email varchar(100) NOT NULL default '',
  productid int(11) NOT NULL default '0'
) TYPE=MyISAM;

