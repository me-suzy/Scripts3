# MySQL dump 8.16
#
# Host: localhost    Database: phpauctionpro
#--------------------------------------------------------
# Server version	3.23.47

#
# Table structure for table 'PHPAUCTIONPROPLUS_accounts'
#

CREATE TABLE PHPAUCTIONPROPLUS_accounts (
  id int(11) NOT NULL auto_increment,
  user varchar(32) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  operation_date varchar(8) NOT NULL default '',
  operation_type int(1) NOT NULL default '0',
  opeartion_amount double NOT NULL default '0',
  account_balance double NOT NULL default '0',
  auction varchar(32) NOT NULL default '',
  KEY id (id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_accounts'
#


#
# Table structure for table 'PHPAUCTIONPROPLUS_adminusers'
#

CREATE TABLE PHPAUCTIONPROPLUS_adminusers (
  id int(11) NOT NULL auto_increment,
  username varchar(32) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  created varchar(8) NOT NULL default '',
  lastlogin varchar(14) NOT NULL default '',
  status int(2) NOT NULL default '0',
  KEY id (id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_adminusers'
#

INSERT INTO PHPAUCTIONPROPLUS_adminusers VALUES (10,'admin','64297660d228fa9c8eabc6f9e94151ac','20011224','20020219120251',1);
INSERT INTO PHPAUCTIONPROPLUS_adminusers VALUES (11,'admin2','c27a69df6526fdd03ad06ae3c4ccb97a','20020218','20020218193745',1);

#
# Table structure for table 'PHPAUCTIONPROPLUS_auctions'
#

CREATE TABLE PHPAUCTIONPROPLUS_auctions (
  id varchar(32) NOT NULL default '',
  user varchar(32) default NULL,
  title tinytext,
  starts timestamp(14) NOT NULL,
  description text,
  pict_url tinytext,
  category int(11) default NULL,
  minimum_bid double(16,4) default NULL,
  reserve_price double(16,4) default NULL,
  buy_now double(16,4) default NULL,
  auction_type char(1) default NULL,
  duration varchar(7) default NULL,
  increment double(8,4) NOT NULL default '0.0000',
  location tinytext,
  location_zip varchar(6) default NULL,
  shipping char(1) default NULL,
  payment tinytext,
  international char(1) default NULL,
  ends timestamp(14) NOT NULL,
  current_bid double(16,4) default NULL,
  closed char(2) default NULL,
  photo_uploaded char(1) default NULL,
  quantity int(11) default NULL,
  suspended int(1) default '0',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_auctions'
#


#
# Table structure for table 'PHPAUCTIONPROPLUS_bids'
#
CREATE TABLE PHPAUCTIONPROPLUS_bids (
  id int(11) NOT NULL auto_increment,
  auction varchar(32) default NULL,
  bidder varchar(32) default NULL,
  bid double(16,4) default NULL,
  bidwhen timestamp(14) NOT NULL,
  quantity int(11) default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;#
# Dumping data for table 'PHPAUCTIONPROPLUS_bids'
#


#
# Table structure for table 'PHPAUCTIONPROPLUS_categories'
#

CREATE TABLE PHPAUCTIONPROPLUS_categories (
  cat_id int(4) NOT NULL auto_increment,
  parent_id int(4) default NULL,
  cat_name tinytext,
  deleted int(1) default NULL,
  sub_counter int(11) default NULL,
  counter int(11) default NULL,
  cat_colour tinytext NOT NULL,
  cat_image tinytext NOT NULL,
  PRIMARY KEY  (cat_id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_categories'
#

INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (1,0,'Art & Antiques',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (2,1,'Ancient World',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (3,1,'Amateur Art',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (4,1,'Ceramics & Glass',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (5,4,'Glass',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (6,5,'40s, 50s & 60s',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (7,5,'Art Glass',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (8,5,'Carnival',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (9,5,'Contemporary Glass',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (10,5,'Porcelain',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (11,5,'Chalkware',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (12,5,'Chintz & Shelley',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (13,5,'Decorative',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (14,1,'Fine Art',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (16,1,'Painting',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (17,1,'Photographic Images',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (18,1,'Prints',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (19,1,'Books & Manuscripts',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (20,1,'Cameras',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (21,1,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (22,1,'Musical Instruments',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (23,1,'Orientalia',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (24,1,'Post-1900',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (25,1,'Pre-1900',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (26,1,'Scientific Instruments',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (27,1,'Silver & Silver Plate',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (28,1,'Textiles & Linens',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (29,0,'Books',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (30,29,'Arts, Architecture & Photography',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (31,29,'Audiobooks',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (32,29,'Biographies & Memoirs',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (33,29,'Business & Investing',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (34,29,'Children\'s Books',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (35,29,'Computers & Internet',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (36,29,'Cooking, Food & Wine',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (37,29,'Entertainment',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (38,29,'Foreign Language Instruction',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (40,29,'Health, Mind & Body',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (41,29,'History',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (42,29,'Home & Garden',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (43,29,'Horror',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (44,29,'Literature & Fiction',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (45,29,'Animals',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (46,29,'Catalogs',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (47,29,'Children',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (48,29,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (49,29,'Illustrated',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (50,29,'Men',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (51,29,'News',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (54,29,'Women',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (55,29,'Mystery & Thrillers',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (56,29,'Nonfiction',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (57,29,'Parenting & Families',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (58,29,'Poetry',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (59,29,'Rare',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (60,29,'Reference',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (61,29,'Religion & Spirituality',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (62,29,'Contemporary',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (63,29,'Historical',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (64,29,'Regency',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (65,29,'Science & Nature',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (66,29,'Science Fiction & Fantasy',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (67,29,'Sports & Outdoors',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (68,29,'Teens',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (69,29,'Textbooks',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (70,29,'Travel',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (71,0,'Clothing & Accessories',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (72,71,'Accessories',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (73,71,'Clothing',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (74,71,'Watches',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (75,0,'Coins & Stamps',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (76,75,'Coins',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (77,75,'Philately',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (78,0,'Collectibles',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (79,78,'Advertising',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (80,78,'Animals',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (81,78,'Animation',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (82,78,'Antique Reproductions',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (83,78,'Autographs',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (84,78,'Barber Shop',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (85,78,'Bears',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (86,78,'Bells',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (87,78,'Bottles & Cans',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (88,78,'Breweriana',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (89,78,'Cars & Motorcycles',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (90,78,'Cereal Boxes & Premiums',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (91,78,'Character',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (92,78,'Circus & Carnival',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (93,78,'Collector Plates',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (94,78,'Dolls',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (95,78,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (96,78,'Historical & Cultural',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (97,78,'Holiday & Seasonal',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (98,78,'Household Items',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (99,78,'Kitsch',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (100,78,'Knives & Swords',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (101,78,'Lunchboxes',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (102,78,'Magic & Novelty Items',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (103,78,'Memorabilia',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (104,78,'Militaria',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (105,78,'Music Boxes',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (106,78,'Oddities',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (107,78,'Paper',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (108,78,'Pinbacks',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (109,78,'Porcelain Figurines',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (110,78,'Railroadiana',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (111,78,'Religious',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (112,78,'Rocks, Minerals & Fossils',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (113,78,'Scientific Instruments',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (114,78,'Textiles',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (115,78,'Tobacciana',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (116,0,'Comics, Cards & Science Fiction',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (117,116,'Anime & Manga',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (118,116,'Comic Books',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (119,116,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (120,116,'Godzilla',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (121,116,'Star Trek',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (122,116,'The X-Files',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (123,116,'Toys',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (124,116,'Trading Cards',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (125,0,'Computers & Software',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (126,125,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (127,125,'Hardware',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (128,125,'Internet Services',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (129,125,'Software',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (130,0,'Electronics & Photography',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (131,130,'Consumer Electronics',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (132,130,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (133,130,'Photo Equipment',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (134,130,'Recording Equipment',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (135,130,'Video Equipment',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (136,0,'Gemstones & Jewelry',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (137,136,'Ancient',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (138,136,'Beaded Jewelry',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (139,136,'Beads',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (140,136,'Carved & Cameo',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (141,136,'Contemporary',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (142,136,'Costume',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (143,136,'Fine',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (144,136,'Gemstones',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (145,136,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (146,136,'Gold',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (147,136,'Necklaces',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (148,136,'Silver',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (149,136,'Victorian',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (150,136,'Vintage',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (151,0,'Home & Garden',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (152,151,'Baby Items',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (153,151,'Crafts',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (154,151,'Furniture',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (155,151,'Garden',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (156,151,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (157,151,'Household Items',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (158,151,'Pet Supplies',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (159,151,'Tools & Hardware',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (160,151,'Weddings',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (161,0,'Movies & Video',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (162,161,'DVD',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (163,161,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (164,161,'Laser Discs',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (165,161,'VHS',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (166,0,'Music',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (167,166,'CDs',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (168,166,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (169,166,'Instruments',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (170,166,'Memorabilia',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (171,166,'Records',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (172,166,'Tapes',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (173,0,'Office & Business',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (174,173,'Briefcases',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (175,173,'Fax Machines',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (176,173,'General Equipment',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (177,173,'Pagers',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (178,0,'Other Goods & Services',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (179,178,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (180,178,'Metaphysical',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (181,178,'Property',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (182,178,'Services',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (183,178,'Tickets & Events',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (184,178,'Transportation',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (185,178,'Travel',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (186,0,'Sports & Recreation',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (187,186,'Apparel & Equipment',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (188,186,'Exercise Equipment',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (189,186,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (190,0,'Toys & Games',0,0,0,'','http://customdecksandponds.com/Picture21_0.gif');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (191,190,'Action Figures',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (192,190,'Beanie Babies & Beanbag Toys',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (193,190,'Diecast',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (194,190,'Fast Food',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (195,190,'Fisher-Price',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (196,190,'Furby',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (197,190,'Games',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (198,190,'General',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (199,190,'Giga Pet & Tamagotchi',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (200,190,'Hobbies',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (201,190,'Marbles',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (202,190,'My Little Pony',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (203,190,'Peanuts Gang',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (204,190,'Pez',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (205,190,'Plastic Models',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (206,190,'Plush Toys',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (207,190,'Puzzles',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (208,190,'Slot Cars',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (209,190,'Teletubbies',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (210,190,'Toy Soldiers',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (211,190,'Vintage Tin',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (212,190,'Vintage Vehicles',0,0,0,'','');
INSERT INTO PHPAUCTIONPROPLUS_categories VALUES (216,214,'33333333333',0,0,0,'','');

#
# Table structure for table 'PHPAUCTIONPROPLUS_categories_plain'
#

CREATE TABLE PHPAUCTIONPROPLUS_categories_plain (
  id int(11) NOT NULL auto_increment,
  cat_id int(11) default NULL,
  cat_name tinytext,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_categories_plain'
#

INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (1,1,'Art & Antiques');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (2,3,'&nbsp; &nbsp;Amateur Art');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (3,2,'&nbsp; &nbsp;Ancient World');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (4,19,'&nbsp; &nbsp;Books & Manuscripts');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (5,20,'&nbsp; &nbsp;Cameras');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (6,4,'&nbsp; &nbsp;Ceramics & Glass');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (7,5,'&nbsp; &nbsp;&nbsp; &nbsp;Glass');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (8,6,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;40s, 50s & 60s');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (9,7,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Art Glass');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (10,8,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Carnival');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (11,11,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Chalkware');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (12,12,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Chintz & Shelley');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (13,9,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Contemporary Glass');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (14,13,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Decorative');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (15,10,'&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Porcelain');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (16,14,'&nbsp; &nbsp;Fine Art');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (17,21,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (18,22,'&nbsp; &nbsp;Musical Instruments');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (19,23,'&nbsp; &nbsp;Orientalia');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (20,16,'&nbsp; &nbsp;Painting');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (21,17,'&nbsp; &nbsp;Photographic Images');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (22,24,'&nbsp; &nbsp;Post-1900');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (23,25,'&nbsp; &nbsp;Pre-1900');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (24,18,'&nbsp; &nbsp;Prints');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (25,26,'&nbsp; &nbsp;Scientific Instruments');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (26,27,'&nbsp; &nbsp;Silver & Silver Plate');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (27,28,'&nbsp; &nbsp;Textiles & Linens');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (28,29,'Books');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (29,45,'&nbsp; &nbsp;Animals');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (30,30,'&nbsp; &nbsp;Arts, Architecture & Photography');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (31,31,'&nbsp; &nbsp;Audiobooks');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (32,32,'&nbsp; &nbsp;Biographies & Memoirs');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (33,33,'&nbsp; &nbsp;Business & Investing');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (34,46,'&nbsp; &nbsp;Catalogs');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (35,47,'&nbsp; &nbsp;Children');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (36,35,'&nbsp; &nbsp;Computers & Internet');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (37,62,'&nbsp; &nbsp;Contemporary');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (38,36,'&nbsp; &nbsp;Cooking, Food & Wine');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (39,37,'&nbsp; &nbsp;Entertainment');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (40,38,'&nbsp; &nbsp;Foreign Language Instruction');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (41,48,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (42,40,'&nbsp; &nbsp;Health, Mind & Body');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (43,63,'&nbsp; &nbsp;Historical');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (44,41,'&nbsp; &nbsp;History');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (45,42,'&nbsp; &nbsp;Home & Garden');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (46,43,'&nbsp; &nbsp;Horror');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (47,49,'&nbsp; &nbsp;Illustrated');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (48,44,'&nbsp; &nbsp;Literature & Fiction');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (49,50,'&nbsp; &nbsp;Men');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (50,55,'&nbsp; &nbsp;Mystery & Thrillers');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (51,51,'&nbsp; &nbsp;News');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (52,56,'&nbsp; &nbsp;Nonfiction');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (53,57,'&nbsp; &nbsp;Parenting & Families');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (54,58,'&nbsp; &nbsp;Poetry');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (55,59,'&nbsp; &nbsp;Rare');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (56,60,'&nbsp; &nbsp;Reference');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (57,64,'&nbsp; &nbsp;Regency');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (58,61,'&nbsp; &nbsp;Religion & Spirituality');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (59,65,'&nbsp; &nbsp;Science & Nature');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (60,66,'&nbsp; &nbsp;Science Fiction & Fantasy');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (61,67,'&nbsp; &nbsp;Sports & Outdoors');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (62,68,'&nbsp; &nbsp;Teens');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (63,69,'&nbsp; &nbsp;Textbooks');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (64,70,'&nbsp; &nbsp;Travel');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (65,54,'&nbsp; &nbsp;Women');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (66,71,'Clothing & Accessories');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (67,72,'&nbsp; &nbsp;Accessories');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (68,73,'&nbsp; &nbsp;Clothing');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (69,74,'&nbsp; &nbsp;Watches');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (70,75,'Coins & Stamps');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (71,76,'&nbsp; &nbsp;Coins');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (72,77,'&nbsp; &nbsp;Philately');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (73,78,'Collectibles');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (74,79,'&nbsp; &nbsp;Advertising');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (75,80,'&nbsp; &nbsp;Animals');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (76,81,'&nbsp; &nbsp;Animation');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (77,82,'&nbsp; &nbsp;Antique Reproductions');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (78,83,'&nbsp; &nbsp;Autographs');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (79,84,'&nbsp; &nbsp;Barber Shop');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (80,85,'&nbsp; &nbsp;Bears');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (81,86,'&nbsp; &nbsp;Bells');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (82,87,'&nbsp; &nbsp;Bottles & Cans');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (83,88,'&nbsp; &nbsp;Breweriana');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (84,89,'&nbsp; &nbsp;Cars & Motorcycles');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (85,90,'&nbsp; &nbsp;Cereal Boxes & Premiums');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (86,91,'&nbsp; &nbsp;Character');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (87,92,'&nbsp; &nbsp;Circus & Carnival');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (88,93,'&nbsp; &nbsp;Collector Plates');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (89,94,'&nbsp; &nbsp;Dolls');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (90,95,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (91,96,'&nbsp; &nbsp;Historical & Cultural');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (92,97,'&nbsp; &nbsp;Holiday & Seasonal');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (93,98,'&nbsp; &nbsp;Household Items');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (94,99,'&nbsp; &nbsp;Kitsch');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (95,100,'&nbsp; &nbsp;Knives & Swords');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (96,101,'&nbsp; &nbsp;Lunchboxes');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (97,102,'&nbsp; &nbsp;Magic & Novelty Items');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (98,103,'&nbsp; &nbsp;Memorabilia');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (99,104,'&nbsp; &nbsp;Militaria');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (100,105,'&nbsp; &nbsp;Music Boxes');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (101,106,'&nbsp; &nbsp;Oddities');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (102,107,'&nbsp; &nbsp;Paper');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (103,108,'&nbsp; &nbsp;Pinbacks');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (104,109,'&nbsp; &nbsp;Porcelain Figurines');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (105,110,'&nbsp; &nbsp;Railroadiana');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (106,111,'&nbsp; &nbsp;Religious');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (107,112,'&nbsp; &nbsp;Rocks, Minerals & Fossils');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (108,113,'&nbsp; &nbsp;Scientific Instruments');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (109,114,'&nbsp; &nbsp;Textiles');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (110,115,'&nbsp; &nbsp;Tobacciana');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (111,116,'Comics, Cards & Science Fiction');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (112,117,'&nbsp; &nbsp;Anime & Manga');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (113,118,'&nbsp; &nbsp;Comic Books');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (114,119,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (115,120,'&nbsp; &nbsp;Godzilla');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (116,121,'&nbsp; &nbsp;Star Trek');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (117,122,'&nbsp; &nbsp;The X-Files');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (118,123,'&nbsp; &nbsp;Toys');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (119,124,'&nbsp; &nbsp;Trading Cards');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (120,125,'Computers & Software');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (121,126,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (122,127,'&nbsp; &nbsp;Hardware');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (123,128,'&nbsp; &nbsp;Internet Services');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (124,129,'&nbsp; &nbsp;Software');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (125,130,'Electronics & Photography');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (126,131,'&nbsp; &nbsp;Consumer Electronics');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (127,132,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (128,133,'&nbsp; &nbsp;Photo Equipment');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (129,134,'&nbsp; &nbsp;Recording Equipment');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (130,135,'&nbsp; &nbsp;Video Equipment');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (131,136,'Gemstones & Jewelry');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (132,137,'&nbsp; &nbsp;Ancient');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (133,138,'&nbsp; &nbsp;Beaded Jewelry');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (134,139,'&nbsp; &nbsp;Beads');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (135,140,'&nbsp; &nbsp;Carved & Cameo');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (136,141,'&nbsp; &nbsp;Contemporary');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (137,142,'&nbsp; &nbsp;Costume');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (138,143,'&nbsp; &nbsp;Fine');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (139,144,'&nbsp; &nbsp;Gemstones');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (140,145,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (141,146,'&nbsp; &nbsp;Gold');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (142,147,'&nbsp; &nbsp;Necklaces');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (143,148,'&nbsp; &nbsp;Silver');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (144,149,'&nbsp; &nbsp;Victorian');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (145,150,'&nbsp; &nbsp;Vintage');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (146,151,'Home & Garden');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (147,152,'&nbsp; &nbsp;Baby Items');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (148,153,'&nbsp; &nbsp;Crafts');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (149,154,'&nbsp; &nbsp;Furniture');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (150,155,'&nbsp; &nbsp;Garden');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (151,156,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (152,157,'&nbsp; &nbsp;Household Items');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (153,158,'&nbsp; &nbsp;Pet Supplies');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (154,159,'&nbsp; &nbsp;Tools & Hardware');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (155,160,'&nbsp; &nbsp;Weddings');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (156,161,'Movies & Video');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (157,162,'&nbsp; &nbsp;DVD');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (158,163,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (159,164,'&nbsp; &nbsp;Laser Discs');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (160,165,'&nbsp; &nbsp;VHS');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (161,166,'Music');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (162,167,'&nbsp; &nbsp;CDs');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (163,168,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (164,169,'&nbsp; &nbsp;Instruments');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (165,170,'&nbsp; &nbsp;Memorabilia');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (166,171,'&nbsp; &nbsp;Records');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (167,172,'&nbsp; &nbsp;Tapes');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (168,173,'Office & Business');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (169,174,'&nbsp; &nbsp;Briefcases');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (170,175,'&nbsp; &nbsp;Fax Machines');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (171,176,'&nbsp; &nbsp;General Equipment');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (172,177,'&nbsp; &nbsp;Pagers');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (173,178,'Other Goods & Services');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (174,179,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (175,180,'&nbsp; &nbsp;Metaphysical');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (176,181,'&nbsp; &nbsp;Property');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (177,182,'&nbsp; &nbsp;Services');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (178,183,'&nbsp; &nbsp;Tickets & Events');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (179,184,'&nbsp; &nbsp;Transportation');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (180,185,'&nbsp; &nbsp;Travel');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (181,186,'Sports & Recreation');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (182,187,'&nbsp; &nbsp;Apparel & Equipment');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (183,188,'&nbsp; &nbsp;Exercise Equipment');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (184,189,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (185,190,'Toys & Games');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (186,191,'&nbsp; &nbsp;Action Figures');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (187,192,'&nbsp; &nbsp;Beanie Babies & Beanbag Toys');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (188,193,'&nbsp; &nbsp;Diecast');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (189,194,'&nbsp; &nbsp;Fast Food');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (190,195,'&nbsp; &nbsp;Fisher-Price');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (191,196,'&nbsp; &nbsp;Furby');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (192,197,'&nbsp; &nbsp;Games');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (193,198,'&nbsp; &nbsp;General');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (194,199,'&nbsp; &nbsp;Giga Pet & Tamagotchi');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (195,200,'&nbsp; &nbsp;Hobbies');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (196,201,'&nbsp; &nbsp;Marbles');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (197,202,'&nbsp; &nbsp;My Little Pony');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (198,203,'&nbsp; &nbsp;Peanuts Gang');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (199,204,'&nbsp; &nbsp;Pez');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (200,205,'&nbsp; &nbsp;Plastic Models');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (201,206,'&nbsp; &nbsp;Plush Toys');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (202,207,'&nbsp; &nbsp;Puzzles');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (203,208,'&nbsp; &nbsp;Slot Cars');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (204,209,'&nbsp; &nbsp;Teletubbies');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (205,210,'&nbsp; &nbsp;Toy Soldiers');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (206,211,'&nbsp; &nbsp;Vintage Tin');
INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (207,212,'&nbsp; &nbsp;Vintage Vehicles');

#
# Table structure for table 'PHPAUCTIONPROPLUS_counters'
#

CREATE TABLE PHPAUCTIONPROPLUS_counters (
  users int(11) default '0',
  auctions int(11) default '0',
  closedauctions int(11) NOT NULL default '0',
  inactiveusers int(11) NOT NULL default '0',
  bids int(11) NOT NULL default '0',
  transactions int(11) NOT NULL default '0',
  totalamount double NOT NULL default '0',
  resetdate varchar(8) NOT NULL default '',
  fees double NOT NULL default '0',
  suspendedauction int(11) NOT NULL default '0'
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_counters'
#

INSERT INTO PHPAUCTIONPROPLUS_counters VALUES (0,0,0,0,0,0,0,'20020218',0,0);

#
# Table structure for table 'PHPAUCTIONPROPLUS_countries'
#

CREATE TABLE PHPAUCTIONPROPLUS_countries (
  country_id int(2) NOT NULL auto_increment,
  country varchar(30) NOT NULL default '',
  PRIMARY KEY  (country_id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_countries'
#

INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (1,'Afghanistan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (2,'Albania');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (3,'Algeria');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (4,'American Samoa');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (5,'Andorra');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (6,'Angola');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (7,'Anguilla');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (8,'Antarctica');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (9,'Antigua And Barbuda');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (10,'Argentina');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (11,'Armenia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (12,'Aruba');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (13,'Australia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (14,'Austria');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (15,'Azerbaijan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (16,'Bahamas');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (17,'Bahrain');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (18,'Bangladesh');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (19,'Barbados');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (20,'Belarus');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (21,'Belgium');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (22,'Belize');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (23,'Benin');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (24,'Bermuda');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (25,'Bhutan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (26,'Bolivia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (27,'Bosnia and Herzegowina');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (28,'Botswana');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (29,'Bouvet Island');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (30,'Brazil');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (31,'British Indian Ocean Territory');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (32,'Brunei Darussalam');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (33,'Bulgaria');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (34,'Burkina Faso');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (35,'Burma');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (36,'Burundi');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (37,'Cambodia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (38,'Cameroon');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (39,'Canada');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (40,'Cape Verde');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (41,'Cayman Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (42,'Central African Republic');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (43,'Chad');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (44,'Chile');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (45,'China');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (46,'Christmas Island');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (47,'Cocos (Keeling) Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (48,'Colombia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (49,'Comoros');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (50,'Congo');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (51,'Congo, the Democratic Republic');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (52,'Cook Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (53,'Costa Rica');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (54,'Cote d\'Ivoire');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (55,'Croatia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (56,'Cyprus');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (57,'Czech Republic');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (58,'Denmark');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (59,'Djibouti');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (60,'Dominica');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (61,'Dominican Republic');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (62,'East Timor');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (63,'Ecuador');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (64,'Egypt');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (65,'El Salvador');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (66,'England');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (67,'Equatorial Guinea');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (68,'Eritrea');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (69,'Estonia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (70,'Ethiopia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (71,'Falkland Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (72,'Faroe Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (73,'Fiji');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (74,'Finland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (75,'France');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (76,'French Guiana');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (77,'French Polynesia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (78,'French Southern Territories');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (79,'Gabon');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (80,'Gambia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (81,'Georgia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (82,'Ghana');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (83,'Gibraltar');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (84,'Great Britain');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (85,'Greece');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (86,'Greenland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (87,'Grenada');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (88,'Guadeloupe');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (89,'Guam');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (90,'Guatemala');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (91,'Guinea');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (92,'Guinea-Bissau');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (93,'Guyana');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (94,'Haiti');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (95,'Heard and Mc Donald Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (96,'Holy See (Vatican City State)');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (97,'Honduras');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (98,'Hong Kong');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (99,'Hungary');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (100,'Iceland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (101,'India');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (102,'Indonesia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (103,'Ireland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (104,'Israel');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (105,'Italy');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (106,'Jamaica');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (107,'Japan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (108,'Jordan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (109,'Kazakhstan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (110,'Kenya');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (111,'Kiribati');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (112,'Korea (South)');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (113,'Kuwait');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (114,'Kyrgyzstan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (115,'Lao People\'s Democratic Republ');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (116,'Latvia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (117,'Lebanon');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (118,'Lesotho');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (119,'Liberia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (120,'Liechtenstein');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (121,'Lithuania');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (122,'Luxembourg');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (123,'Macau');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (124,'Macedonia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (125,'Madagascar');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (126,'Malawi');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (127,'Malaysia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (128,'Maldives');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (129,'Mali');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (130,'Malta');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (131,'Marshall Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (132,'Martinique');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (133,'Mauritania');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (134,'Mauritius');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (135,'Mayotte');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (136,'Mexico');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (137,'Micronesia, Federated States o');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (138,'Moldova, Republic of');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (139,'Monaco');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (140,'Mongolia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (141,'Montserrat');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (142,'Morocco');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (143,'Mozambique');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (144,'Namibia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (145,'Nauru');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (146,'Nepal');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (147,'Netherlands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (148,'Netherlands Antilles');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (149,'New Caledonia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (150,'New Zealand');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (151,'Nicaragua');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (152,'Niger');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (153,'Nigeria');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (154,'Niuev');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (155,'Norfolk Island');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (156,'Northern Ireland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (157,'Northern Mariana Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (158,'Norway');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (159,'Oman');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (160,'Pakistan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (161,'Palau');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (162,'Panama');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (163,'Papua New Guinea');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (164,'Paraguay');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (165,'Peru');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (166,'Philippines');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (167,'Pitcairn');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (168,'Poland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (169,'Portugal');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (170,'Puerto Rico');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (171,'Qatar');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (172,'Reunion');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (173,'Romania');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (174,'Russian Federation');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (175,'Rwanda');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (176,'Saint Kitts and Nevis');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (177,'Saint Lucia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (178,'Saint Vincent and the Grenadin');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (179,'Samoa (Independent)');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (180,'San Marino');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (181,'Sao Tome and Principe');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (182,'Saudi Arabia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (183,'Scotland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (184,'Senegal');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (185,'Seychelles');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (186,'Sierra Leone');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (187,'Singapore');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (188,'Slovakia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (189,'Slovenia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (190,'Solomon Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (191,'Somalia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (192,'South Africa');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (193,'South Georgia and the South Sa');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (194,'Spain');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (195,'Sri Lanka');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (196,'St. Helena');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (197,'St. Pierre and Miquelon');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (198,'Suriname');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (199,'Svalbard and Jan Mayen Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (200,'Swaziland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (201,'Sweden');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (202,'Switzerland');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (203,'Taiwan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (204,'Tajikistan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (205,'Tanzania');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (206,'Thailand');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (207,'Togo');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (208,'Tokelau');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (209,'Tonga');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (210,'Trinidad and Tobago');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (211,'Tunisia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (212,'Turkey');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (213,'Turkmenistan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (214,'Turks and Caicos Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (215,'Tuvalu');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (216,'Uganda');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (217,'Ukraine');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (218,'United Arab Emiratesv');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (219,'United States');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (220,'Uruguay');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (221,'Uzbekistan');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (222,'Vanuatu');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (223,'Venezuela');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (224,'Viet Nam');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (225,'Virgin Islands (British)');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (226,'Virgin Islands (U.S.)');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (227,'Wales');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (228,'Wallis and Futuna Islands');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (229,'Western Sahara');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (230,'Yemen');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (231,'Zambia');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (232,'Zimbabwe');
INSERT INTO PHPAUCTIONPROPLUS_countries VALUES (236,'Germany');

#
# Table structure for table 'PHPAUCTIONPROPLUS_durations'
#

CREATE TABLE PHPAUCTIONPROPLUS_durations (
  days double(6,4) NOT NULL default '0.0000',
  description varchar(30) default NULL
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_durations'
#

INSERT INTO PHPAUCTIONPROPLUS_durations VALUES (1.0000,'1 day');
INSERT INTO PHPAUCTIONPROPLUS_durations VALUES (3.0000,'3 days');
INSERT INTO PHPAUCTIONPROPLUS_durations VALUES (7.0000,'1 week');
INSERT INTO PHPAUCTIONPROPLUS_durations VALUES (30.0000,'1 month');
INSERT INTO PHPAUCTIONPROPLUS_durations VALUES (60.0000,'2 months');
INSERT INTO PHPAUCTIONPROPLUS_durations VALUES (90.0000,'3 months');
INSERT INTO PHPAUCTIONPROPLUS_durations VALUES (15.0000,'2 weeks');

#
# Table structure for table 'PHPAUCTIONPROPLUS_feedbacks'
#

CREATE TABLE PHPAUCTIONPROPLUS_feedbacks (
  rated_user_id varchar(32) default NULL,
  rater_user_nick varchar(20) default NULL,
  feedback mediumtext,
  rate int(2) default NULL,
  feedbackdate timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_feedbacks'
#


#
# Table structure for table 'PHPAUCTIONPROPLUS_help'
#

CREATE TABLE PHPAUCTIONPROPLUS_help (
  topic varchar(40) default NULL,
  helptext text
) TYPE=MyISAM;

#
# Dumping data for table `PHPAUCTIONPROPLUS_help`
#

INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('General', 'Welcome to the PHPAuction, a web site that lets you buy and sell items in auction format.\r\n<br><br>\r\nRegistered users may place items up for auction and may bid on other user\'s items.  To register, you must provide your name, an address, and your email address.  You must be 18 years or older.\r\n<br><br>\r\nWhen selling an item, you may enter a description of the item, upload a photograph, and indicate the minimum bid and a reserve price for the item.  You also indicate what payments you will accept and whether you or the buyer will pay shipping.\r\n<br><br>\r\nSellers are notified by email when an auction is concluded.  If a winner exists, the winner and seller are provided each other\'s contact information.\r\n<br>\r\n\r\n\r\n\r\n\r\n\r\n');
INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('Registering', 'To register as a new user, click on Register at the top of the window.  You will be asked for your name, a username and password, and contact information, including your email address.\r\n<br><br>\r\n<center><font color=red>You must be at least 18 years of age to register.</font></center>\r\n<br><br>\r\n');
INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('Bidding', 'To bid on an item, type your maximum bid amount in the box next to the item description, and click the \'Go\' button.  Your bid must be above the Minimum Bid amount specified in the box.\r\n<br>You will be asked to confirm your bid.  Fill in your username and password and click \'Submit\' to complete your bid.\r\n<br><br>\r\nNote*** Maximum bid and proxy bid are the same thing. To place a proxy bid, just enter the maximum amount you are willing to pay.  Automatic bids will be placed for you up to your maximum amount. \r\nUsing proxy bidding, you can win the item at the lowest possible price. Your maximum bid is only placed when a competing bidder bids the same amount. <br>\r\nPlease note that if the current high bidder\'s maximum bid is higher than the bid you place, you are immediately outbid because your bid is not high enough to beat the earlier maximum bid.  Remember: Proxy bidding will confidentially & automatically bid up to your maximum, but in order to avoid being outbid, you must have the highest maximum bid.\r\nAlso, if another bidder placed a higher maximum bid, your bid will increase to your maximum bid because proxy bidding bids on your behalf up to your maximum bid. \r\n');
INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('Selling', 'To sell an item, you must be a <a href=\'help.php?topic=Registering\'>registered user</a>.\r\n<br><br>\r\nClick on \'Sell an Item\' at the top of the window to create a new auction.  Indicate the title and description of your item, and select a graphic image from your local hard drive if you wish to upload a picture of the item.\r\n<br><br>\r\nSpecify the minimum bid and reserve price (optional) for your auction, and what types of payment you will accept.  While this site allows you to specify payment methods, it does not process the payment for you.\r\n<br><br>\r\nChoose the category in which your item should be.  You may suggest a new category, but you must select an existing category for your new auction.\r\n<br><br>\r\n');
INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('Auction Watch', 'Auction Watch is your assistant to keep an eye on auctions which are opened pertaining to your keywords.<br><br>To use this feature, insert keyword(s) into the Auction Watch list that you are interested in.  When these keywords appear in an auction\'s title or description, you will receive an e-mail informing you that an auction has been opened containing your keyword(s).  You may also add a user\'s nickname and if that user opens an auction\r\nyou will get notification by e-mail. All keywords must be separated by a space when entering into your Auction Watch list.<br>\r\n<br><br>\r\n');
INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('Item Watch', 'Item watch notifies\r\nyou when someone bids on the auctions that you have added to your Item\r\nWatch. \r\n<br><br>\r\n');
INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('Dutch Auctions', 'Dutch Auctions are used to sell multiple quantities of the same item. They allow sellers to sell the same item in various quantities as one single lot in an auction. With a Dutch Auction the items do not have to be listed separately saving time.\r\nIn a Dutch Auction, all the winning bidders pay the same price. The final price is determined by the lowest winning bid. So, even if your bid is higher than the lowest winning bid, you will still pay the lower amount. The highest bidders of the available quantities win, and the lowest bid within the group of winners sets the final price. \r\nAs an example, if three people bid on one of three items, the price each bidder would pay would be the third highest bidder\'s bid amount and each would receive one item as per their bid.\r\nIf there are multiple buyers bidding on various quantities in a lot the highest bidders are the ones that get first service. The highest bidders\' request for their quantities will be honored, while quantities last. In other words, the lowest winning bidder may not get the quantity they wanted. Therefore, it is not always good to be the lowest successful bidder. The highest bidder will get theirs first which may leave you with just one item, or an amount less than you wanted. \r\nIf you want more than one of an item in a Dutch Auction don\'t be stingy with your maximum bid. By maximizing your bid you may win your requested quantitiy and pay the lowest winning bid price instead of paying the lowest bid price with only one item. \r\n\r\n');
INSERT INTO PHPAUCTIONPROPLUS_help VALUES ('Feedback System', 'You may only leave feedback about another user if you have had a closed auction transaction with that user.  In order to leave feedback, you must go to the item page of that closed auction, or go to your control panel and view your winners/sellers list. From the list,  click on the user\'s name to go to their profile page.    On the user\'s profile page, click on Leave Feedback.\r\n<br><br>\r\nWe use a Star rating system  to rate users\' feedback, with one star being a low rating and a five star being a high rating.  You must fill in all form elements to leave feedback.  You must choose your star rating, as well as, leave your written comment about the transaction and/or user.');

#
# Table structure for table 'PHPAUCTIONPROPLUS_increments'
#

CREATE TABLE PHPAUCTIONPROPLUS_increments (
  id char(3) default NULL,
  low double(16,4) default NULL,
  high double(16,4) default NULL,
  increment double(16,4) default NULL
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_increments'
#

INSERT INTO PHPAUCTIONPROPLUS_increments VALUES ('1',0.0000,0.9900,0.2500);
INSERT INTO PHPAUCTIONPROPLUS_increments VALUES ('2',1.0000,9.9900,0.5000);
INSERT INTO PHPAUCTIONPROPLUS_increments VALUES ('3',10.0000,29.9900,1.0000);
INSERT INTO PHPAUCTIONPROPLUS_increments VALUES ('4',30.0000,99.9900,2.0000);
INSERT INTO PHPAUCTIONPROPLUS_increments VALUES ('5',100.0000,249.9900,5.0000);
INSERT INTO PHPAUCTIONPROPLUS_increments VALUES ('6',250.0000,499.9900,10.0000);
INSERT INTO PHPAUCTIONPROPLUS_increments VALUES ('7',500.0000,999.9900,25.0000);

#
# Table structure for table 'PHPAUCTIONPROPLUS_news'
#

CREATE TABLE PHPAUCTIONPROPLUS_news (
  id varchar(32) NOT NULL default '',
  title varchar(200) NOT NULL default '',
  content longtext NOT NULL,
  new_date int(8) NOT NULL default '0',
  suspended int(1) NOT NULL default '0'
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_news'
#

INSERT INTO PHPAUCTIONPROPLUS_news VALUES ('21721d73a583e17761db04ec0c7cecf3','Official News','This is the official news section?',20021802,0);

#
# Table structure for table 'PHPAUCTIONPROPLUS_payments'
#

CREATE TABLE PHPAUCTIONPROPLUS_payments (
  id int(2) default NULL,
  description varchar(30) default NULL
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_payments'
#

INSERT INTO PHPAUCTIONPROPLUS_payments VALUES (1,'Checks');
INSERT INTO PHPAUCTIONPROPLUS_payments VALUES (2,'Money Order');
INSERT INTO PHPAUCTIONPROPLUS_payments VALUES (3,'Paypal');
INSERT INTO PHPAUCTIONPROPLUS_payments VALUES (4,'MasterCard or Visa');
INSERT INTO PHPAUCTIONPROPLUS_payments VALUES (5,'Wire Transfer');


#
# Table structure for table `PHPAUCTIONPROPLUS_proxybid`
#

CREATE TABLE PHPAUCTIONPROPLUS_proxybid (
  itemid varchar(32) default NULL,
  userid varchar(32) default NULL,
  bid double(16,4) default NULL
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_proxybid'
#

#
# Table structure for table 'PHPAUCTIONPROPLUS_request'
#

CREATE TABLE PHPAUCTIONPROPLUS_request (
  req_auction varchar(32) default NULL,
  req_user varchar(32) default NULL,
  req_text text,
  req_date timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_request'
#


#
# Table structure for table 'PHPAUCTIONPROPLUS_settings'
#

CREATE TABLE PHPAUCTIONPROPLUS_settings (
  sitename varchar(255) NOT NULL default '',
  siteurl varchar(255) NOT NULL default '',
  cookiesprefix varchar(100) NOT NULL default '',
  loginbox int(1) NOT NULL default '0',
  newsbox int(1) NOT NULL default '0',
  newstoshow int(11) NOT NULL default '0',
  moneyformat int(1) NOT NULL default '0',
  moneydecimals int(11) NOT NULL default '0',
  moneysymbol int(1) NOT NULL default '0',
  currency varchar(10) NOT NULL default '',
  showacceptancetext int(1) NOT NULL default '0',
  acceptancetext longtext NOT NULL,
  adminmail varchar(100) NOT NULL default '',
  err_font varchar(5) NOT NULL default '',
  std_font varchar(5) NOT NULL default '',
  sml_font varchar(5) NOT NULL default '',
  tlt_font varchar(5) NOT NULL default '',
  nav_font varchar(5) NOT NULL default '',
  footer_font varchar(5) NOT NULL default '',
  bordercolor char(1) NOT NULL default '0',
  headercolor char(1) NOT NULL default '0',
  tableheadercolor varchar(4) NOT NULL default '0000',
  linkscolor char(1) NOT NULL default '0',
  vlinkscolor char(1) NOT NULL default '0',
  banners int(1) NOT NULL default '0',
  newsletter int(1) NOT NULL default '0',
  logo varchar(255) NOT NULL default '',
  timecorrection int(11) NOT NULL default '0',
  cron int(1) NOT NULL default '0',
  archiveafter int(11) NOT NULL default '0',
  datesformat enum('USA','EUR') NOT NULL default 'USA',
  feetype enum('prepay','pay') NOT NULL default 'prepay',
  sellersetupfee int(1) NOT NULL default '0',
  sellersetuptype int(11) NOT NULL default '0',
  sellerfinalfee int(11) NOT NULL default '0',
  sellerfinaltype tinyint(4) NOT NULL default '0',
  sellersetupvalue double NOT NULL default '0',
  sellerfinalvalue double NOT NULL default '0',
  buyerfinalfee int(11) NOT NULL default '0',
  buyerfinaltype int(11) NOT NULL default '0',
  buyerfinalvalue double NOT NULL default '0',
  paypaladdress varchar(255) NOT NULL default '',
  errortext text NOT NULL,
  errormail varchar(255) NOT NULL default '',
  signupfee int(1) NOT NULL default '0',
  signupvalue double NOT NULL default '0',
  picturesgallery int(1) NOT NULL default '0',
  maxpictures int(11) NOT NULL default '0',
  maxpicturesize int(11) NOT NULL default '0',
  picturesgalleryfee int(11) NOT NULL default '0',
  picturesgalleryvalue double NOT NULL default '0',
  buy_now int(1) NOT NULL default '1'
) TYPE=MyISAM;


#
# Dumping data for table 'PHPAUCTIONPROPLUS_settings'
#

INSERT INTO PHPAUCTIONPROPLUS_settings VALUES ('ProPlusTest', 'http://www.phpauction.org/proplus/', 'PHPAUCTIONPREFIX', 1, 1, 5, 1, 2, 2, 'USD', 1, 'By clicking below you agree to the terms of this website.', 'webmaster@phpauction.org', '33e12', '22i22', '21b22', '24b12', '22i22', '11i22', 'r', 'c', 'r', 'i', 'b', 2, 1, 'logo.gif', 0, 2, 30, 'USA', 'pay', 1, 1, 1, 2, '10', '1', 1, 2, '1', 'software@phpauction.org', 'this is my error text.', 'youraddress@yourdomain.com', 2, '1', 1, 5, 100, 1, '1', 2);   

#
# Table structure for table 'PHPAUCTIONPROPLUS_users'
#

CREATE TABLE PHPAUCTIONPROPLUS_users (
  id varchar(32) NOT NULL default '',
  nick varchar(20) default NULL,
  password varchar(32) default NULL,
  name tinytext,
  address tinytext,
  city varchar(25) default NULL,
  prov varchar(10) default NULL,
  country varchar(4) default NULL,
  zip varchar(6) default NULL,
  phone varchar(40) default NULL,
  email varchar(50) default NULL,
  reg_date timestamp(14) NOT NULL,
  rate_sum int(11) default NULL,
  rate_num int(11) default NULL,
  birthdate int(8) default NULL,
  suspended int(1) default '0',
  nletter int(1) NOT NULL default '0',
  balance double NOT NULL default '0',
  auc_watch varchar(20) default 'disabled',
  item_watch text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_users'
#


#
# Table structure for table 'PHPAUCTIONPROPLUS_winners'
#

CREATE TABLE PHPAUCTIONPROPLUS_winners (
  id int(11) NOT NULL auto_increment,
  auction varchar(32) NOT NULL default '',
  seller varchar(32) NOT NULL default '',
  winner varchar(32) NOT NULL default '',
  bid double NOT NULL default '0',
  closingdate timestamp(14) NOT NULL,
  fee double NOT NULL default '0',
  KEY id (id)
) TYPE=MyISAM;

#
# Dumping data for table 'PHPAUCTIONPROPLUS_winners'
#


#
# Table structure for table 'phpads_acls'
#

CREATE TABLE phpads_acls (
  bannerID mediumint(9) NOT NULL default '0',
  acl_con set('and','or') NOT NULL default '',
  acl_type enum('clientip','useragent','weekday','domain','source','time','language') NOT NULL default 'clientip',
  acl_data varchar(255) NOT NULL default '',
  acl_ad set('allow','deny') NOT NULL default '',
  acl_order int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (bannerID,acl_order),
  KEY bannerID (bannerID)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_acls'
#


#
# Table structure for table 'phpads_adclicks'
#

CREATE TABLE phpads_adclicks (
  bannerID mediumint(9) NOT NULL default '0',
  t_stamp timestamp(14) NOT NULL,
  host varchar(255) NOT NULL default '',
  KEY clientID (bannerID)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_adclicks'
#


#
# Table structure for table 'phpads_adstats'
#

CREATE TABLE phpads_adstats (
  views int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  day date NOT NULL default '0000-00-00',
  BannerID smallint(6) NOT NULL default '0',
  PRIMARY KEY  (day,BannerID)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_adstats'
#


#
# Table structure for table 'phpads_adviews'
#

CREATE TABLE phpads_adviews (
  bannerID mediumint(9) NOT NULL default '0',
  t_stamp timestamp(14) NOT NULL,
  host varchar(255) NOT NULL default '',
  KEY clientID (bannerID)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_adviews'
#


#
# Table structure for table 'phpads_banners'
#

CREATE TABLE phpads_banners (
  bannerID mediumint(9) NOT NULL auto_increment,
  clientID mediumint(9) NOT NULL default '0',
  active enum('true','false') NOT NULL default 'true',
  weight tinyint(4) NOT NULL default '1',
  seq tinyint(4) NOT NULL default '0',
  banner blob NOT NULL,
  width smallint(6) NOT NULL default '0',
  height smallint(6) NOT NULL default '0',
  format enum('gif','jpeg','png','html','url','web','swf') NOT NULL default 'gif',
  url varchar(255) NOT NULL default '',
  alt varchar(255) NOT NULL default '',
  status varchar(255) NOT NULL default '',
  keyword varchar(255) NOT NULL default '',
  bannertext varchar(255) NOT NULL default '',
  target varchar(8) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  autohtml enum('true','false') NOT NULL default 'true',
  PRIMARY KEY  (bannerID)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_banners'
#


#
# Table structure for table 'phpads_clients'
#

CREATE TABLE phpads_clients (
  clientID mediumint(9) NOT NULL auto_increment,
  clientname varchar(255) NOT NULL default '',
  contact varchar(255) default NULL,
  email varchar(64) NOT NULL default '',
  views mediumint(9) default NULL,
  clicks mediumint(9) default NULL,
  clientusername varchar(64) NOT NULL default '',
  clientpassword varchar(64) NOT NULL default '',
  expire date default '0000-00-00',
  activate date default '0000-00-00',
  permissions mediumint(9) default NULL,
  language varchar(64) default NULL,
  active enum('true','false') NOT NULL default 'true',
  weight tinyint(4) NOT NULL default '1',
  parent mediumint(9) NOT NULL default '0',
  report enum('true','false') NOT NULL default 'true',
  reportinterval mediumint(9) NOT NULL default '7',
  reportlastdate date NOT NULL default '0000-00-00',
  reportdeactivate enum('true','false') NOT NULL default 'true',
  PRIMARY KEY  (clientID)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_clients'
#


#
# Table structure for table 'phpads_session'
#

CREATE TABLE phpads_session (
  SessionID varchar(32) NOT NULL default '',
  SessionData blob NOT NULL,
  LastUsed timestamp(14) NOT NULL,
  PRIMARY KEY  (SessionID)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_session'
#


#
# Table structure for table 'phpads_zones'
#

CREATE TABLE phpads_zones (
  zoneid mediumint(9) NOT NULL auto_increment,
  zonename varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  zonetype smallint(6) NOT NULL default '0',
  what blob NOT NULL,
  width smallint(6) NOT NULL default '0',
  height smallint(6) NOT NULL default '0',
  retrieval enum('random','cookie') NOT NULL default 'random',
  cachecontents blob,
  cachetimestamp int(11) NOT NULL default '0',
  PRIMARY KEY  (zoneid)
) TYPE=MyISAM;

#
# Dumping data for table 'phpads_zones'
#


