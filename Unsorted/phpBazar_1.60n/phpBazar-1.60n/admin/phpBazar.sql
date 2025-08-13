

#

# Table structure for table `adcat`

#



DROP TABLE IF EXISTS adcat;

CREATE TABLE adcat (

  id int(5) NOT NULL auto_increment,

  name varchar(25) NOT NULL default '',

  description varchar(50) NOT NULL default '',

  longdescription text NOT NULL,

  ads int(6) NOT NULL default '0',

  human smallint(1) NOT NULL default '0',

  picture varchar(50) NOT NULL default '',

  sfield varchar(50) NOT NULL default '',

  field1 varchar(50) NOT NULL default '',

  field2 varchar(50) NOT NULL default '',

  field3 varchar(50) NOT NULL default '',

  field4 varchar(50) NOT NULL default '',

  field5 varchar(50) NOT NULL default '',

  field6 varchar(50) NOT NULL default '',

  field7 varchar(50) NOT NULL default '',

  field8 varchar(50) NOT NULL default '',

  field9 varchar(50) NOT NULL default '',

  field10 varchar(50) NOT NULL default '',

  field11 varchar(50) NOT NULL default '',

  field12 varchar(50) NOT NULL default '',

  field13 varchar(50) NOT NULL default '',

  field14 varchar(50) NOT NULL default '',

  field15 varchar(50) NOT NULL default '',

  field16 varchar(50) NOT NULL default '',

  field17 varchar(50) NOT NULL default '',

  field18 varchar(50) NOT NULL default '',

  field19 varchar(50) NOT NULL default '',

  field20 varchar(50) NOT NULL default '',

  icon1 varchar(50) NOT NULL default '',

  icon1alt varchar(50) NOT NULL default '',

  icon2 varchar(50) NOT NULL default '',

  icon2alt varchar(50) NOT NULL default '',

  icon3 varchar(50) NOT NULL default '',

  icon3alt varchar(50) NOT NULL default '',

  icon4 varchar(50) NOT NULL default '',

  icon4alt varchar(50) NOT NULL default '',

  icon5 varchar(50) NOT NULL default '',

  icon5alt varchar(50) NOT NULL default '',

  icon6 varchar(50) NOT NULL default '',

  icon6alt varchar(50) NOT NULL default '',

  icon7 varchar(50) NOT NULL default '',

  icon7alt varchar(50) NOT NULL default '',

  icon8 varchar(50) NOT NULL default '',

  icon8alt varchar(50) NOT NULL default '',

  icon9 varchar(50) NOT NULL default '',

  icon9alt varchar(50) NOT NULL default '',

  icon10 varchar(50) NOT NULL default '',

  icon10alt varchar(50) NOT NULL default '',

  passphrase varchar(32) NOT NULL default '',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `adcat`

#



INSERT INTO adcat VALUES (1, 'Category1', 'Short Description Cat1', 'Long Descrition Cat 1 - Here comes your Description!<br>Inform the Users about this Cat. ', 0, 1, 'images/cats/right.gif', '', 'Field1', 'Field2', 'Field3', 'Field4', 'Field5', 'Field6', 'Field7', 'Field8', '', '', '', '', '', '', '', '', '', '', '', '', 'images/icons/top.gif', 'Dominate', 'images/icons/sub.gif', 'Devot', 'images/icons/moneybid.gif', 'Bit Money', 'images/icons/moneyask.gif', 'Ask Money', 'images/icons/smoking.gif', 'Smoker', 'images/icons/nosmoking.gif', 'NonSmoker', 'images/icons/bi.gif', 'Bi', 'images/icons/pierced.gif', 'Pierced', 'images/icons/shaved.gif', 'Shaved', 'images/icons/tatto.gif', 'Tatto', '');

INSERT INTO adcat VALUES (2, 'Category2', 'Short Description Cat2', 'Long Descrition Cat 2 - Here comes your Description!<br>Inform the Users about this Cat. ', 0, 0, 'images/cats/right.gif', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'images/icons/suche.gif', 'Ask', 'images/icons/biete.gif', 'Bid', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

INSERT INTO adcat VALUES (3, 'Category3', 'Short Description Cat3', 'Long Descrition Cat 3 - Here comes your Description!<br>Inform the Users about this Cat. ', 0, 0, 'images/cats/right.gif', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'images/icons/suche.gif', 'Ask', 'images/icons/biete.gif', 'Bid', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

INSERT INTO adcat VALUES (4, 'Category4', 'Short Description Cat4', 'Long Descrition Cat 4 - Here comes your Description!<br>Inform the Users about this Cat. ', 0, 0, 'images/cats/right.gif', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'images/icons/suche.gif', 'Ask', 'images/icons/biete.gif', 'Bid', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

INSERT INTO adcat VALUES (5, 'Category5', 'Short Description Cat5', 'Long Descrition Cat 5 - Here comes your Description!<br>Inform the Users about this Cat. ', 0, 0, 'images/cats/right.gif', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'images/icons/suche.gif', 'Ask', 'images/icons/biete.gif', 'Bid', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

INSERT INTO adcat VALUES (6, 'Category6', 'Short Description Cat6', 'Long Descrition Cat 6 - Here comes your Description!<br>Inform the Users about this Cat. ', 0, 0, 'images/cats/right.gif', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'images/icons/suche.gif', 'Ask', 'images/icons/biete.gif', 'Bid', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

# --------------------------------------------------------



#

# Table structure for table `ads`

#



DROP TABLE IF EXISTS ads;

CREATE TABLE ads (

  id int(11) NOT NULL auto_increment,

  userid int(11) NOT NULL default '0',

  catid int(5) NOT NULL default '0',

  subcatid int(5) NOT NULL default '0',

  viewed int(14) NOT NULL default '0',

  answered int(14) NOT NULL default '0',

  rating double(5,2) NOT NULL default '5.00',

  ratingcount int(5) NOT NULL default '0',

  addate datetime NOT NULL default '0000-00-00 00:00:00',

  adeditdate datetime NOT NULL default '0000-00-00 00:00:00',

  ip varchar(40) NOT NULL default '',

  duration int(2) NOT NULL default '12',

  durationdays int(5) NOT NULL default '0',

  location varchar(50) NOT NULL default '',

  header varchar(50) NOT NULL default '',

  text text NOT NULL,

  _picture varchar(50) NOT NULL default '',

  picture varchar(50) NOT NULL default '',

  _picture2 varchar(50) NOT NULL default '',

  picture2 varchar(50) NOT NULL default '',

  _picture3 varchar(50) NOT NULL default '',

  picture3 varchar(50) NOT NULL default '',

  attachment1 varchar(50) NOT NULL default '',

  attachment2 varchar(50) NOT NULL default '',

  attachment3 varchar(50) NOT NULL default '',

  sfield varchar(50) NOT NULL default '',

  field1 varchar(50) NOT NULL default '',

  field2 varchar(50) NOT NULL default '',

  field3 varchar(50) NOT NULL default '',

  field4 varchar(50) NOT NULL default '',

  field5 varchar(50) NOT NULL default '',

  field6 varchar(50) NOT NULL default '',

  field7 varchar(50) NOT NULL default '',

  field8 varchar(50) NOT NULL default '',

  field9 varchar(50) NOT NULL default '',

  field10 varchar(50) NOT NULL default '',

  field11 varchar(50) NOT NULL default '',

  field12 varchar(50) NOT NULL default '',

  field13 varchar(50) NOT NULL default '',

  field14 varchar(50) NOT NULL default '',

  field15 varchar(50) NOT NULL default '',

  field16 varchar(50) NOT NULL default '',

  field17 varchar(50) NOT NULL default '',

  field18 varchar(50) NOT NULL default '',

  field19 varchar(50) NOT NULL default '',

  field20 varchar(50) NOT NULL default '',

  icon1 smallint(1) NOT NULL default '0',

  icon2 smallint(1) NOT NULL default '0',

  icon3 smallint(1) NOT NULL default '0',

  icon4 smallint(1) NOT NULL default '0',

  icon5 smallint(1) NOT NULL default '0',

  icon6 smallint(1) NOT NULL default '0',

  icon7 smallint(1) NOT NULL default '0',

  icon8 smallint(1) NOT NULL default '0',

  icon9 smallint(1) NOT NULL default '0',

  icon10 smallint(1) NOT NULL default '0',

  publicview smallint(1) NOT NULL default '0',

  deleted int(1) NOT NULL default '0',

  timeoutnotify varchar(32) NOT NULL default '',

  timeoutdays int(10) NOT NULL default '0',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `ads`

#



# --------------------------------------------------------



#

# Table structure for table `adsubcat`

#



DROP TABLE IF EXISTS adsubcat;

CREATE TABLE adsubcat (

  id int(5) NOT NULL auto_increment,

  catid int(5) NOT NULL default '0',

  name varchar(25) NOT NULL default '',

  description varchar(50) NOT NULL default '',

  ads int(6) NOT NULL default '0',

  picture varchar(50) NOT NULL default '',

  notify int(1) NOT NULL default '0',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `adsubcat`

#



INSERT INTO adsubcat VALUES (1, 1, 'Subcategory 1.1', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (2, 1, 'Subcategory 1.2', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (3, 1, 'Subcategory 1.3', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (4, 2, 'Subcategory 2.1', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (5, 2, 'Subcategory 2.2', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (6, 2, 'Subcategory 2.3', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (7, 3, 'Subcategory 3.1', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (8, 3, 'Subcategory 3.2', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (9, 3, 'Subcategory 3.3', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (10, 4, 'Subcategory 4.1', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (11, 4, 'Subcategory 4.2', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (12, 4, 'Subcategory 4.3', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (13, 5, 'Subcategory 5.1', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (14, 5, 'Subcategory 5.2', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (15, 5, 'Subcategory 5.3', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (16, 6, 'Subcategory 6.1', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

INSERT INTO adsubcat VALUES (17, 6, 'Subcategory 6.2', 'Short Desc. Subcat', 0, 'images/cats/right.gif', 0);

# --------------------------------------------------------



#

# Table structure for table `badwords`

#



DROP TABLE IF EXISTS badwords;

CREATE TABLE badwords (

  badword varchar(25) NOT NULL default ''

) TYPE=MyISAM;



#

# Dumping data for table `badwords`

#



INSERT INTO badwords VALUES ('fuck');

INSERT INTO badwords VALUES ('cock');

# --------------------------------------------------------



#

# Table structure for table `banned_ips`

#



DROP TABLE IF EXISTS banned_ips;

CREATE TABLE banned_ips (

  ip varchar(40) NOT NULL default '',

  PRIMARY KEY  (ip)

) TYPE=MyISAM;



#

# Dumping data for table `banned_ips`

#



INSERT INTO banned_ips VALUES ('0.0.0.0');

# --------------------------------------------------------



#

# Table structure for table `banned_users`

#



DROP TABLE IF EXISTS banned_users;

CREATE TABLE banned_users (

  userid int(11) NOT NULL default '0'

) TYPE=MyISAM;



#

# Dumping data for table `banned_users`

#



INSERT INTO banned_users VALUES (0);

# --------------------------------------------------------



#

# Table structure for table `config`

#



DROP TABLE IF EXISTS config;

CREATE TABLE config (

  id int(11) NOT NULL auto_increment,

  type varchar(32) NOT NULL default '',

  name varchar(32) NOT NULL default '',

  value varchar(255) NOT NULL default '',

  value2 varchar(255) NOT NULL default '',

  value3 varchar(255) NOT NULL default '',

  value4 varchar(255) NOT NULL default '',

  value5 varchar(255) NOT NULL default '',

  value6 varchar(255) NOT NULL default '',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `config`

#



INSERT INTO config VALUES (1, 'member', 'sex', 'yes', 'yes', '', '', '', '');

INSERT INTO config VALUES (2, 'member', 'newsletter', 'yes', 'yes', '', '', '', '');

INSERT INTO config VALUES (3, 'member', 'firstname', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (4, 'member', 'lastname', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (5, 'member', 'address', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (6, 'member', 'zip', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (7, 'member', 'city', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (8, 'member', 'state', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (9, 'member', 'country', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (10, 'member', 'phone', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (11, 'member', 'cellphone', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (12, 'member', 'icq', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (13, 'member', 'homepage', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (14, 'member', 'hobbys', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (15, 'member', 'field1', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (16, 'member', 'field2', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (17, 'member', 'field3', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (18, 'member', 'field4', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (19, 'member', 'field5', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (20, 'member', 'field6', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (21, 'member', 'field7', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (22, 'member', 'field8', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (23, 'member', 'field9', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (24, 'member', 'field10', 'yes', 'no', 'text', '', '', '');

INSERT INTO config VALUES (25, 'cat', 'sfield', '1', 'no', '', '', '', '');

INSERT INTO config VALUES (26, 'cat', 'field1', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (27, 'cat', 'field2', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (28, 'cat', 'field3', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (29, 'cat', 'field4', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (30, 'cat', 'field5', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (31, 'cat', 'field6', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (32, 'cat', 'field7', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (33, 'cat', 'field8', '1', 'yes', 'text', '', '', '');

INSERT INTO config VALUES (34, 'cat', 'field9', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (35, 'cat', 'field10', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (36, 'cat', 'field11', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (37, 'cat', 'field12', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (38, 'cat', 'field13', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (39, 'cat', 'field14', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (40, 'cat', 'field15', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (41, 'cat', 'field16', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (42, 'cat', 'field17', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (43, 'cat', 'field18', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (44, 'cat', 'field19', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (45, 'cat', 'field20', '1', 'no', 'text', '', '', '');

INSERT INTO config VALUES (46, 'cat', 'icon1', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (47, 'cat', 'icon2', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (48, 'cat', 'icon3', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (49, 'cat', 'icon4', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (50, 'cat', 'icon5', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (51, 'cat', 'icon6', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (52, 'cat', 'icon7', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (53, 'cat', 'icon8', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (54, 'cat', 'icon9', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (55, 'cat', 'icon10', '1', 'yes', '', '', '', '');

INSERT INTO config VALUES (56, 'cat', 'sfield', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (57, 'cat', 'field1', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (58, 'cat', 'field2', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (59, 'cat', 'field3', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (60, 'cat', 'field4', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (61, 'cat', 'field5', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (62, 'cat', 'field6', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (63, 'cat', 'field7', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (64, 'cat', 'field8', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (65, 'cat', 'field9', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (66, 'cat', 'field10', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (67, 'cat', 'field11', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (68, 'cat', 'field12', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (69, 'cat', 'field13', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (70, 'cat', 'field14', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (71, 'cat', 'field15', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (72, 'cat', 'field16', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (73, 'cat', 'field17', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (74, 'cat', 'field18', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (75, 'cat', 'field19', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (76, 'cat', 'field20', '2', 'no', 'text', '', '', '');

INSERT INTO config VALUES (77, 'cat', 'icon1', '2', 'yes', '', '', '', '');

INSERT INTO config VALUES (78, 'cat', 'icon2', '2', 'yes', '', '', '', '');

INSERT INTO config VALUES (79, 'cat', 'icon3', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (80, 'cat', 'icon4', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (81, 'cat', 'icon5', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (82, 'cat', 'icon6', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (83, 'cat', 'icon7', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (84, 'cat', 'icon8', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (85, 'cat', 'icon9', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (86, 'cat', 'icon10', '2', 'no', '', '', '', '');

INSERT INTO config VALUES (87, 'cat', 'sfield', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (88, 'cat', 'field1', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (89, 'cat', 'field2', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (90, 'cat', 'field3', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (91, 'cat', 'field4', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (92, 'cat', 'field5', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (93, 'cat', 'field6', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (94, 'cat', 'field7', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (95, 'cat', 'field8', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (96, 'cat', 'field9', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (97, 'cat', 'field10', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (98, 'cat', 'field11', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (99, 'cat', 'field12', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (100, 'cat', 'field13', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (101, 'cat', 'field14', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (102, 'cat', 'field15', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (103, 'cat', 'field16', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (104, 'cat', 'field17', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (105, 'cat', 'field18', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (106, 'cat', 'field19', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (107, 'cat', 'field20', '3', 'no', 'text', '', '', '');

INSERT INTO config VALUES (108, 'cat', 'icon1', '3', 'yes', '', '', '', '');

INSERT INTO config VALUES (109, 'cat', 'icon2', '3', 'yes', '', '', '', '');

INSERT INTO config VALUES (110, 'cat', 'icon3', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (111, 'cat', 'icon4', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (112, 'cat', 'icon5', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (113, 'cat', 'icon6', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (114, 'cat', 'icon7', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (115, 'cat', 'icon8', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (116, 'cat', 'icon9', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (117, 'cat', 'icon10', '3', 'no', '', '', '', '');

INSERT INTO config VALUES (118, 'cat', 'sfield', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (119, 'cat', 'field1', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (120, 'cat', 'field2', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (121, 'cat', 'field3', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (122, 'cat', 'field4', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (123, 'cat', 'field5', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (124, 'cat', 'field6', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (125, 'cat', 'field7', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (126, 'cat', 'field8', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (127, 'cat', 'field9', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (128, 'cat', 'field10', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (129, 'cat', 'field11', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (130, 'cat', 'field12', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (131, 'cat', 'field13', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (132, 'cat', 'field14', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (133, 'cat', 'field15', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (134, 'cat', 'field16', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (135, 'cat', 'field17', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (136, 'cat', 'field18', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (137, 'cat', 'field19', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (138, 'cat', 'field20', '4', 'no', 'text', '', '', '');

INSERT INTO config VALUES (139, 'cat', 'icon1', '4', 'yes', '', '', '', '');

INSERT INTO config VALUES (140, 'cat', 'icon2', '4', 'yes', '', '', '', '');

INSERT INTO config VALUES (141, 'cat', 'icon3', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (142, 'cat', 'icon4', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (143, 'cat', 'icon5', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (144, 'cat', 'icon6', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (145, 'cat', 'icon7', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (146, 'cat', 'icon8', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (147, 'cat', 'icon9', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (148, 'cat', 'icon10', '4', 'no', '', '', '', '');

INSERT INTO config VALUES (149, 'cat', 'sfield', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (150, 'cat', 'field1', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (151, 'cat', 'field2', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (152, 'cat', 'field3', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (153, 'cat', 'field4', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (154, 'cat', 'field5', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (155, 'cat', 'field6', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (156, 'cat', 'field7', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (157, 'cat', 'field8', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (158, 'cat', 'field9', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (159, 'cat', 'field10', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (160, 'cat', 'field11', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (161, 'cat', 'field12', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (162, 'cat', 'field13', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (163, 'cat', 'field14', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (164, 'cat', 'field15', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (165, 'cat', 'field16', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (166, 'cat', 'field17', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (167, 'cat', 'field18', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (168, 'cat', 'field19', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (169, 'cat', 'field20', '5', 'no', 'text', '', '', '');

INSERT INTO config VALUES (170, 'cat', 'icon1', '5', 'yes', '', '', '', '');

INSERT INTO config VALUES (171, 'cat', 'icon2', '5', 'yes', '', '', '', '');

INSERT INTO config VALUES (172, 'cat', 'icon3', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (173, 'cat', 'icon4', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (174, 'cat', 'icon5', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (175, 'cat', 'icon6', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (176, 'cat', 'icon7', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (177, 'cat', 'icon8', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (178, 'cat', 'icon9', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (179, 'cat', 'icon10', '5', 'no', '', '', '', '');

INSERT INTO config VALUES (180, 'cat', 'sfield', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (181, 'cat', 'field1', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (182, 'cat', 'field2', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (183, 'cat', 'field3', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (184, 'cat', 'field4', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (185, 'cat', 'field5', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (186, 'cat', 'field6', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (187, 'cat', 'field7', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (188, 'cat', 'field8', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (189, 'cat', 'field9', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (190, 'cat', 'field10', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (191, 'cat', 'field11', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (192, 'cat', 'field12', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (193, 'cat', 'field13', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (194, 'cat', 'field14', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (195, 'cat', 'field15', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (196, 'cat', 'field16', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (197, 'cat', 'field17', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (198, 'cat', 'field18', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (199, 'cat', 'field19', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (200, 'cat', 'field20', '6', 'no', 'text', '', '', '');

INSERT INTO config VALUES (201, 'cat', 'icon1', '6', 'yes', '', '', '', '');

INSERT INTO config VALUES (202, 'cat', 'icon2', '6', 'yes', '', '', '', '');

INSERT INTO config VALUES (203, 'cat', 'icon3', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (204, 'cat', 'icon4', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (205, 'cat', 'icon5', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (206, 'cat', 'icon6', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (207, 'cat', 'icon7', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (208, 'cat', 'icon8', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (209, 'cat', 'icon9', '6', 'no', '', '', '', '');

INSERT INTO config VALUES (210, 'cat', 'icon10', '6', 'no', '', '', '', '');

# --------------------------------------------------------



#

# Table structure for table `confirm`

#



DROP TABLE IF EXISTS confirm;

CREATE TABLE confirm (

  mdhash varchar(100) default NULL,

  username varchar(25) default NULL,

  password varchar(25) default NULL,

  email varchar(50) default NULL,

  sex varchar(15) default NULL,

  date datetime NOT NULL default '0000-00-00 00:00:00',

  newsletter varchar(5) NOT NULL default '',

  firstname varchar(50) NOT NULL default '',

  lastname varchar(50) NOT NULL default '',

  address varchar(50) NOT NULL default '',

  zip varchar(50) NOT NULL default '',

  city varchar(50) NOT NULL default '',

  state varchar(50) NOT NULL default '',

  country varchar(50) NOT NULL default '',

  phone varchar(50) NOT NULL default '',

  cellphone varchar(50) NOT NULL default '',

  icq varchar(50) NOT NULL default '',

  homepage varchar(50) NOT NULL default '',

  hobbys varchar(50) NOT NULL default '',

  field1 varchar(255) NOT NULL default '',

  field2 varchar(255) NOT NULL default '',

  field3 varchar(255) NOT NULL default '',

  field4 varchar(255) NOT NULL default '',

  field5 varchar(255) NOT NULL default '',

  field6 varchar(255) NOT NULL default '',

  field7 varchar(255) NOT NULL default '',

  field8 varchar(255) NOT NULL default '',

  field9 varchar(255) NOT NULL default '',

  field10 varchar(255) NOT NULL default ''

) TYPE=MyISAM;



#

# Dumping data for table `confirm`

#



# --------------------------------------------------------



#

# Table structure for table `confirm_email`

#



DROP TABLE IF EXISTS confirm_email;

CREATE TABLE confirm_email (

  id int(8) default NULL,

  email varchar(50) default NULL,

  mdhash varchar(100) default NULL,

  date datetime NOT NULL default '0000-00-00 00:00:00'

) TYPE=MyISAM;



#

# Dumping data for table `confirm_email`

#



# --------------------------------------------------------



#

# Table structure for table `favorits`

#



DROP TABLE IF EXISTS favorits;

CREATE TABLE favorits (

  userid int(11) NOT NULL default '0',

  adid int(11) NOT NULL default '0'

) TYPE=MyISAM;



#

# Dumping data for table `favorits`

#



# --------------------------------------------------------



#

# Table structure for table `guestbook`

#



DROP TABLE IF EXISTS guestbook;

CREATE TABLE guestbook (

  id int(5) NOT NULL auto_increment,

  name varchar(25) NOT NULL default '',

  email varchar(35) NOT NULL default '',

  icq int(11) NOT NULL default '0',

  http varchar(50) NOT NULL default '',

  message longtext NOT NULL,

  timestamp int(11) NOT NULL default '0',

  ip varchar(40) NOT NULL default '',

  location varchar(35) NOT NULL default '',

  browser varchar(50) NOT NULL default '',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `guestbook`

#



INSERT INTO guestbook VALUES (1, 'Webmaster', 'webmaster@youdomain.com', 0, 'www.youdomain.com', 'Hi dear User <img src=images/smilies/smile.gif>\r\n<BR>\r\n<BR>The Bazar is great <img src=images/smilies/bounce.gif> leave us a Message in our Guestbook\r\n<BR>\r\n<BR>by(t)e Webmaster', 000000000, '0.0.0.0', 'WTN Team', 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)');

# --------------------------------------------------------



#

# Table structure for table `logging`

#



DROP TABLE IF EXISTS logging;

CREATE TABLE logging (

  timestamp int(14) NOT NULL default '0',

  userid int(11) NOT NULL default '0',

  username varchar(25) NOT NULL default '',

  event varchar(50) NOT NULL default '',

  ext varchar(250) NOT NULL default '',

  ip varchar(40) NOT NULL default '',

  ipname varchar(50) NOT NULL default '',

  client varchar(100) NOT NULL default ''

) TYPE=MyISAM;



#

# Dumping data for table `logging`

#



# --------------------------------------------------------



#

# Table structure for table `login`

#



DROP TABLE IF EXISTS login;

CREATE TABLE login (

  id int(11) NOT NULL auto_increment,

  username varchar(25) NOT NULL default '',

  password varchar(25) NOT NULL default '',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `login`

#



INSERT INTO login VALUES (1, 'webmaster', 'changeme');

# --------------------------------------------------------



#

# Table structure for table `notify`

#



DROP TABLE IF EXISTS notify;

CREATE TABLE notify (

  userid int(11) NOT NULL default '0',

  subcatid int(5) NOT NULL default '0'

) TYPE=MyISAM;



#

# Dumping data for table `notify`

#



# --------------------------------------------------------



#

# Table structure for table `pictures`

#



DROP TABLE IF EXISTS pictures;

CREATE TABLE pictures (

  picture_name varchar(50) NOT NULL default '',

  picture_type varchar(10) NOT NULL default '',

  picture_height varchar(10) NOT NULL default '',

  picture_width varchar(10) NOT NULL default '',

  picture_size varchar(10) NOT NULL default '',

  picture_bin mediumblob NOT NULL

) TYPE=MyISAM;



#

# Dumping data for table `pictures`

#



# --------------------------------------------------------



#

# Table structure for table `rating`

#



DROP TABLE IF EXISTS rating;

CREATE TABLE rating (

  type char(3) NOT NULL default '',

  id int(11) NOT NULL default '0',

  ip varchar(40) NOT NULL default '',

  userid int(11) NOT NULL default '0',

  ratedate datetime NOT NULL default '0000-00-00 00:00:00'

) TYPE=MyISAM;



#

# Dumping data for table `rating`

#



# --------------------------------------------------------



#

# Table structure for table `sessions`

#



DROP TABLE IF EXISTS sessions;

CREATE TABLE sessions (

  id varchar(32) NOT NULL default '',

  userid int(11) NOT NULL default '0',

  username varchar(25) NOT NULL default '',

  mod int(1) NOT NULL default '0',

  sessiondate datetime NOT NULL default '0000-00-00 00:00:00'

) TYPE=MyISAM;



#

# Dumping data for table `sessions`

#



# --------------------------------------------------------



#

# Table structure for table `smilies`

#



DROP TABLE IF EXISTS smilies;

CREATE TABLE smilies (

  code char(3) NOT NULL default '',

  file varchar(15) NOT NULL default '',

  name varchar(25) NOT NULL default ''

) TYPE=MyISAM;



#

# Dumping data for table `smilies`

#



INSERT INTO smilies VALUES (':)', 'smile.gif', 'Smile');

INSERT INTO smilies VALUES (':-)', 'smile.gif', 'Smile');

INSERT INTO smilies VALUES (':))', 'lol.gif', 'LOL');

INSERT INTO smilies VALUES (';)', 'wink.gif', 'Winkywinky');

INSERT INTO smilies VALUES (';-)', 'wink.gif', 'Winkywinky');

INSERT INTO smilies VALUES (':(', 'frown.gif', 'Frown');

INSERT INTO smilies VALUES (':-(', 'frown.gif', 'Frown');

INSERT INTO smilies VALUES (':[', 'mad.gif', 'Mad');

INSERT INTO smilies VALUES (':z)', 'grazy.gif', 'Grazy');

INSERT INTO smilies VALUES (':y)', 'crying.gif', 'Crying');

INSERT INTO smilies VALUES (':o)', 'sleepy.gif', 'Sleepy');

INSERT INTO smilies VALUES (':a)', 'alien.gif', 'Alien');

INSERT INTO smilies VALUES (':s)', 'smokie.gif', 'Smokie');

INSERT INTO smilies VALUES (':l)', 'love.gif', 'Loooove');

INSERT INTO smilies VALUES (':L)', 'love2.gif', 'Loooooooooove');

INSERT INTO smilies VALUES (':]', 'biggrin.gif', 'Big Smile');

INSERT INTO smilies VALUES (':-/', 'bounce.gif', 'Bounce');

INSERT INTO smilies VALUES (':b)', 'burnout.gif', 'Burnout');

INSERT INTO smilies VALUES (':&)', 'clown.gif', 'Clown');

INSERT INTO smilies VALUES (':?)', 'confused.gif', 'Confused');

INSERT INTO smilies VALUES (':c)', 'cool.gif', 'Cooooooool');

INSERT INTO smilies VALUES (':e)', 'eek.gif', 'Eeeeeeek');

INSERT INTO smilies VALUES (':f)', 'flash.gif', 'Flash');

INSERT INTO smilies VALUES (':g)', 'girl.gif', 'Girl');

INSERT INTO smilies VALUES (':i)', 'idee.gif', 'Idea');

INSERT INTO smilies VALUES (':r)', 'redface.gif', 'Redface');

INSERT INTO smilies VALUES (':8)', 'rolleyes.gif', 'RollEyes');

INSERT INTO smilies VALUES (':}', 'tongue.gif', 'Tongue');

INSERT INTO smilies VALUES (':t)', 'tasty.gif', 'Tasty');

# --------------------------------------------------------



#

# Table structure for table `userdata`

#



DROP TABLE IF EXISTS userdata;

CREATE TABLE userdata (

  id int(11) NOT NULL auto_increment,

  username varchar(25) NOT NULL default '',

  email varchar(50) NOT NULL default '',

  sex varchar(15) NOT NULL default '',

  newsletter varchar(5) NOT NULL default '',

  level int(1) NOT NULL default '0',

  votes int(3) NOT NULL default '0',

  lastvotedate datetime NOT NULL default '0000-00-00 00:00:00',

  ads int(3) NOT NULL default '0',

  lastaddate datetime NOT NULL default '0000-00-00 00:00:00',

  firstname varchar(50) NOT NULL default '',

  lastname varchar(50) NOT NULL default '',

  address varchar(50) NOT NULL default '',

  zip varchar(7) NOT NULL default '',

  city varchar(50) NOT NULL default '',

  state varchar(50) NOT NULL default '',

  country varchar(50) NOT NULL default '',

  phone varchar(50) NOT NULL default '',

  cellphone varchar(50) NOT NULL default '',

  icq varchar(12) NOT NULL default '',

  homepage varchar(50) NOT NULL default '',

  hobbys varchar(50) NOT NULL default '',

  field1 varchar(255) NOT NULL default '',

  field2 varchar(255) NOT NULL default '',

  field3 varchar(255) NOT NULL default '',

  field4 varchar(255) NOT NULL default '',

  field5 varchar(255) NOT NULL default '',

  field6 varchar(255) NOT NULL default '',

  field7 varchar(255) NOT NULL default '',

  field8 varchar(255) NOT NULL default '',

  field9 varchar(255) NOT NULL default '',

  field10 varchar(255) NOT NULL default '',

  registered varchar(14) NOT NULL default '',

  language char(2) NOT NULL default '',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `userdata`

#



INSERT INTO userdata VALUES (1, 'webmaster', 'webmaster@domain.com', 'm', '1', 9, 1, '2002-02-08 00:00:00', 0, '2002-02-07 00:00:00', 'Webmaster', 'Webmaster', '', '', '', '', 'Russia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'en');

# --------------------------------------------------------



#

# Table structure for table `useronline`

#



DROP TABLE IF EXISTS useronline;

CREATE TABLE useronline (

  timestamp int(14) NOT NULL default '0',

  ip varchar(40) NOT NULL default '',

  file varchar(50) NOT NULL default '',

  username varchar(25) NOT NULL default ''

) TYPE=MyISAM;



#

# Dumping data for table `useronline`

#

# --------------------------------------------------------



#

# Table structure for table `votes`

#



DROP TABLE IF EXISTS votes;

CREATE TABLE votes (

  name varchar(30) NOT NULL default '0',

  votes int(4) default NULL,

  id int(1) NOT NULL default '0',

  PRIMARY KEY  (name)

) TYPE=MyISAM;



#

# Dumping data for table `votes`

#



INSERT INTO votes VALUES ('often', 1, 1);

INSERT INTO votes VALUES ('2-3 weekly', 1, 2);

INSERT INTO votes VALUES ('sometimes', 1, 3);

INSERT INTO votes VALUES ('never', 1, 4);

# --------------------------------------------------------



#

# Table structure for table `webmail`

#



DROP TABLE IF EXISTS webmail;

CREATE TABLE webmail (

  id int(11) NOT NULL auto_increment,

  fromid int(11) NOT NULL default '0',

  fromname varchar(25) NOT NULL default '',

  fromemail varchar(50) NOT NULL default '',

  toid int(11) NOT NULL default '0',

  toname varchar(25) NOT NULL default '',

  toemail varchar(50) NOT NULL default '',

  viewed int(1) NOT NULL default '0',

  answered int(1) NOT NULL default '0',

  timestamp int(14) NOT NULL default '0',

  ip varchar(40) NOT NULL default '',

  subject varchar(255) NOT NULL default '',

  text text NOT NULL,

  deleted int(1) NOT NULL default '0',

  attachment1 varchar(50) NOT NULL default '',

  attachment2 varchar(50) NOT NULL default '',

  attachment3 varchar(50) NOT NULL default '',

  PRIMARY KEY  (id)

) TYPE=MyISAM;



#

# Dumping data for table `webmail`

#





