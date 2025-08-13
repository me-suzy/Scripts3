<?
/*

   Copyright (c), 1999, 2000, 2001 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/

	include ("../includes/passwd.inc.php");
	  $err_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\" COLOR=red>";
	  $std_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\">";

		// connect to database
	mysql_connect("$DbHost","$DbUser","$DbPassword") or die (" $ERROR ");
	mysql_select_db("$DbDatabase") or die (" $ERROR ");
	
		// table string (for future use)
		$phpauction_adminusers = "PHPAUCTION_adminusers";
		$phpauction_auctions = "PHPAUCTION_auctions";
		$phpauction_bids = "PHPAUCTION_bids";
		$phpauction_categories = "PHPAUCTION_categories";
		$phpauction_categories_plain = "PHPAUCTION_categories_plain";
		$phpauction_counters = "PHPAUCTION_counters";
		$phpauction_countries = "PHPAUCTION_countries";
		$phpauction_durations = "PHPAUCTION_durations";
		$phpauction_feedbacks = "PHPAUCTION_feedbacks";
		$phpauction_help = "PHPAUCTION_help";
		$phpauction_increments = "PHPAUCTION_increments";
		$phpauction_news = "PHPAUCTION_news";
		$phpauction_payments = "PHPAUCTION_payments";
		$phpauction_request = "PHPAUCTION_request";
		$phpauction_sessions = "PHPAUCTION_sessions";
		$phpauction_settings = "PHPAUCTION_settings";
		$phpauction_users = "PHPAUCTION_users";


#
# Table structure for table `adminusers`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_adminusers");
if (mysql_query("CREATE TABLE $phpauction_adminusers (
  id int(11) NOT NULL auto_increment,
  username varchar(32) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  created varchar(8) NOT NULL default '',
  lastlogin varchar(14) NOT NULL default '',
  status int(2) NOT NULL default '0',
  KEY id (id)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_adminusers : Done</font><BR>"; } 
else { $msg.="$err_font Creating table $phpauction_adminusers : Failed</font><BR>"; }


#
# Dumping data for table `adminusers`
#
# --------------------------------------------------------
#
# Table structure for table `auctions`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_auctions");
if (mysql_query("CREATE TABLE $phpauction_auctions (
  id varchar(32) NOT NULL default '',
  user varchar(32) default NULL,
  title tinytext,
  starts timestamp(14) NOT NULL,
  description text,
  pict_url tinytext,
  category int(11) default NULL,
  minimum_bid double(16,4) default NULL,
  reserve_price double(16,4) default NULL,
  auction_type char(1) default NULL,
  duration char(2) default NULL,
  increment double(8,4) NOT NULL default '0.0000',
  location tinytext,
  location_zip varchar(6) default NULL,
  shipping char(1) default NULL,
  payment tinytext,
  international char(1) default NULL,
  ends timestamp(14) NOT NULL,
  current_bid double(16,4) default NULL,
  closed char(1) default NULL,
  photo_uploaded char(1) default NULL,
  quantity int(11) default NULL,
  suspended int(1) default '0',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_auctions : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_auctions : Failed</font><BR>"; }

#
# Dumping data for table `auctions`
#

# --------------------------------------------------------

#
# Table structure for table `bids`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_bids");
if (mysql_query("CREATE TABLE $phpauction_bids (
  auction varchar(32) default NULL,
  bidder varchar(32) default NULL,
  bid double(16,4) default NULL,
  bidwhen timestamp(14) NOT NULL,
  quantity int(11) default '0'
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_bids : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_bids : Failed</font><BR>"; }

#
# Dumping data for table `bids`
#

# --------------------------------------------------------

#
# Table structure for table `categories`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_categories");
if (mysql_query("CREATE TABLE $phpauction_categories (
  cat_id int(4) NOT NULL auto_increment,
  parent_id int(4) default NULL,
  cat_name tinytext,
  deleted int(1) default NULL,
  sub_counter int(11) default NULL,
  counter int(11) default NULL,
  cat_colour tinytext NOT NULL,
  cat_image tinytext NOT NULL,
  PRIMARY KEY  (cat_id)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_categories : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_categories : Failed</font><BR>"; }

#
# Dumping data for table `categories`
#

mysql_query("INSERT INTO $phpauction_categories VALUES (1, 0, 'Art & Antiques', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (2, 1, 'Ancient World', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (3, 1, 'Amateur Art', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (4, 1, 'Ceramics & Glass', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (5, 4, 'Glass', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (6, 5, '40s, 50s & 60s', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (7, 5, 'Art Glass', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (8, 5, 'Carnival', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (9, 5, 'Contemporary Glass', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (10, 5, 'Porcelain', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (11, 5, 'Chalkware', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (12, 5, 'Chintz & Shelley', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (13, 5, 'Decorative', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (14, 1, 'Fine Art', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (16, 1, 'Painting', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (17, 1, 'Photographic Images', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (18, 1, 'Prints', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (19, 1, 'Books & Manuscripts', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (20, 1, 'Cameras', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (21, 1, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (22, 1, 'Musical Instruments', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (23, 1, 'Orientalia', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (24, 1, 'Post-1900', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (25, 1, 'Pre-1900', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (26, 1, 'Scientific Instruments', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (27, 1, 'Silver & Silver Plate', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (28, 1, 'Textiles & Linens', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (29, 0, 'Books', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (30, 29, 'Arts, Architecture & Photography', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (31, 29, 'Audiobooks', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (32, 29, 'Biographies & Memoirs', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (33, 29, 'Business & Investing', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (34, 29, 'Children\'s Books', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (35, 29, 'Computers & Internet', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (36, 29, 'Cooking, Food & Wine', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (37, 29, 'Entertainment', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (38, 29, 'Foreign Language Instruction', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (40, 29, 'Health, Mind & Body', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (41, 29, 'History', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (42, 29, 'Home & Garden', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (43, 29, 'Horror', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (44, 29, 'Literature & Fiction', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (45, 29, 'Animals', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (46, 29, 'Catalogs', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (47, 29, 'Children', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (48, 29, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (49, 29, 'Illustrated', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (50, 29, 'Men', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (51, 29, 'News', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (54, 29, 'Women', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (55, 29, 'Mystery & Thrillers', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (56, 29, 'Nonfiction', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (57, 29, 'Parenting & Families', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (58, 29, 'Poetry', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (59, 29, 'Rare', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (60, 29, 'Reference', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (61, 29, 'Religion & Spirituality', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (62, 29, 'Contemporary', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (63, 29, 'Historical', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (64, 29, 'Regency', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (65, 29, 'Science & Nature', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (66, 29, 'Science Fiction & Fantasy', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (67, 29, 'Sports & Outdoors', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (68, 29, 'Teens', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (69, 29, 'Textbooks', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (70, 29, 'Travel', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (71, 0, 'Clothing & Accessories', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (72, 71, 'Accessories', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (73, 71, 'Clothing', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (74, 71, 'Watches', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (75, 0, 'Coins & Stamps', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (76, 75, 'Coins', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (77, 75, 'Philately', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (78, 0, 'Collectibles', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (79, 78, 'Advertising', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (80, 78, 'Animals', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (81, 78, 'Animation', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (82, 78, 'Antique Reproductions', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (83, 78, 'Autographs', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (84, 78, 'Barber Shop', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (85, 78, 'Bears', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (86, 78, 'Bells', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (87, 78, 'Bottles & Cans', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (88, 78, 'Breweriana', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (89, 78, 'Cars & Motorcycles', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (90, 78, 'Cereal Boxes & Premiums', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (91, 78, 'Character', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (92, 78, 'Circus & Carnival', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (93, 78, 'Collector Plates', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (94, 78, 'Dolls', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (95, 78, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (96, 78, 'Historical & Cultural', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (97, 78, 'Holiday & Seasonal', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (98, 78, 'Household Items', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (99, 78, 'Kitsch', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (100, 78, 'Knives & Swords', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (101, 78, 'Lunchboxes', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (102, 78, 'Magic & Novelty Items', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (103, 78, 'Memorabilia', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (104, 78, 'Militaria', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (105, 78, 'Music Boxes', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (106, 78, 'Oddities', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (107, 78, 'Paper', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (108, 78, 'Pinbacks', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (109, 78, 'Porcelain Figurines', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (110, 78, 'Railroadiana', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (111, 78, 'Religious', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (112, 78, 'Rocks, Minerals & Fossils', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (113, 78, 'Scientific Instruments', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (114, 78, 'Textiles', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (115, 78, 'Tobacciana', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (116, 0, 'Comics, Cards & Science Fiction', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (117, 116, 'Anime & Manga', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (118, 116, 'Comic Books', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (119, 116, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (120, 116, 'Godzilla', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (121, 116, 'Star Trek', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (122, 116, 'The X-Files', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (123, 116, 'Toys', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (124, 116, 'Trading Cards', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (125, 0, 'Computers & Software', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (126, 125, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (127, 125, 'Hardware', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (128, 125, 'Internet Services', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (129, 125, 'Software', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (130, 0, 'Electronics & Photography', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (131, 130, 'Consumer Electronics', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (132, 130, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (133, 130, 'Photo Equipment', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (134, 130, 'Recording Equipment', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (135, 130, 'Video Equipment', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (136, 0, 'Gemstones & Jewelry', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (137, 136, 'Ancient', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (138, 136, 'Beaded Jewelry', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (139, 136, 'Beads', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (140, 136, 'Carved & Cameo', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (141, 136, 'Contemporary', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (142, 136, 'Costume', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (143, 136, 'Fine', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (144, 136, 'Gemstones', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (145, 136, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (146, 136, 'Gold', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (147, 136, 'Necklaces', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (148, 136, 'Silver', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (149, 136, 'Victorian', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (150, 136, 'Vintage', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (151, 0, 'Home & Garden', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (152, 151, 'Baby Items', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (153, 151, 'Crafts', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (154, 151, 'Furniture', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (155, 151, 'Garden', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (156, 151, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (157, 151, 'Household Items', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (158, 151, 'Pet Supplies', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (159, 151, 'Tools & Hardware', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (160, 151, 'Weddings', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (161, 0, 'Movies & Video', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (162, 161, 'DVD', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (163, 161, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (164, 161, 'Laser Discs', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (165, 161, 'VHS', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (166, 0, 'Music', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (167, 166, 'CDs', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (168, 166, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (169, 166, 'Instruments', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (170, 166, 'Memorabilia', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (171, 166, 'Records', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (172, 166, 'Tapes', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (173, 0, 'Office & Business', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (174, 173, 'Briefcases', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (175, 173, 'Fax Machines', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (176, 173, 'General Equipment', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (177, 173, 'Pagers', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (178, 0, 'Other Goods & Services', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (179, 178, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (180, 178, 'Metaphysical', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (181, 178, 'Property', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (182, 178, 'Services', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (183, 178, 'Tickets & Events', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (184, 178, 'Transportation', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (185, 178, 'Travel', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (186, 0, 'Sports & Recreation', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (187, 186, 'Apparel & Equipment', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (188, 186, 'Exercise Equipment', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (189, 186, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (190, 0, 'Toys & Games', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (191, 190, 'Action Figures', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (192, 190, 'Beanie Babies & Beanbag Toys', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (193, 190, 'Diecast', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (194, 190, 'Fast Food', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (195, 190, 'Fisher-Price', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (196, 190, 'Furby', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (197, 190, 'Games', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (198, 190, 'General', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (199, 190, 'Giga Pet & Tamagotchi', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (200, 190, 'Hobbies', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (201, 190, 'Marbles', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (202, 190, 'My Little Pony', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (203, 190, 'Peanuts Gang', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (204, 190, 'Pez', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (205, 190, 'Plastic Models', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (206, 190, 'Plush Toys', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (207, 190, 'Puzzles', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (208, 190, 'Slot Cars', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (209, 190, 'Teletubbies', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (210, 190, 'Toy Soldiers', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (211, 190, 'Vintage Tin', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (212, 190, 'Vintage Vehicles', 0, 0, 0, '', '')");
mysql_query("INSERT INTO $phpauction_categories VALUES (216, 214, '33333333333', 0, 0, 0, '', '')"); 

$msg.= "$std_font Dumping data to table $phpauction_categories : Done<BR>";


# --------------------------------------------------------
#
# Table structure for table `categories_plain`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_categories_plain");
if (mysql_query("CREATE TABLE $phpauction_categories_plain (
  id int(11) NOT NULL auto_increment,
  cat_id int(11) default NULL,
  cat_name tinytext,
  PRIMARY KEY  (id)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_categories_plain : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_categories_plain : Failed</font><BR>"; }

#
# Dumping data for table `categories_plain`
#

mysql_query("INSERT INTO $phpauction_categories_plain VALUES (1, 1, 'Art & Antiques')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (2, 3, '&nbsp; &nbsp;Amateur Art')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (3, 2, '&nbsp; &nbsp;Ancient World')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (4, 19, '&nbsp; &nbsp;Books & Manuscripts')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (5, 20, '&nbsp; &nbsp;Cameras')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (6, 4, '&nbsp; &nbsp;Ceramics & Glass')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (7, 5, '&nbsp; &nbsp;&nbsp; &nbsp;Glass')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (8, 6, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;40s, 50s & 60s')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (9, 7, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Art Glass')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (10, 8, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Carnival')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (11, 11, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Chalkware')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (12, 12, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Chintz & Shelley')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (13, 9, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Contemporary Glass')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (14, 13, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Decorative')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (15, 10, '&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Porcelain')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (16, 14, '&nbsp; &nbsp;Fine Art')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (17, 21, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (18, 22, '&nbsp; &nbsp;Musical Instruments')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (19, 23, '&nbsp; &nbsp;Orientalia')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (20, 16, '&nbsp; &nbsp;Painting')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (21, 17, '&nbsp; &nbsp;Photographic Images')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (22, 24, '&nbsp; &nbsp;Post-1900')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (23, 25, '&nbsp; &nbsp;Pre-1900')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (24, 18, '&nbsp; &nbsp;Prints')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (25, 26, '&nbsp; &nbsp;Scientific Instruments')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (26, 27, '&nbsp; &nbsp;Silver & Silver Plate')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (27, 28, '&nbsp; &nbsp;Textiles & Linens')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (28, 29, 'Books')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (29, 45, '&nbsp; &nbsp;Animals')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (30, 30, '&nbsp; &nbsp;Arts, Architecture & Photography')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (31, 31, '&nbsp; &nbsp;Audiobooks')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (32, 32, '&nbsp; &nbsp;Biographies & Memoirs')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (33, 33, '&nbsp; &nbsp;Business & Investing')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (34, 46, '&nbsp; &nbsp;Catalogs')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (35, 47, '&nbsp; &nbsp;Children')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (36, 35, '&nbsp; &nbsp;Computers & Internet')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (37, 62, '&nbsp; &nbsp;Contemporary')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (38, 36, '&nbsp; &nbsp;Cooking, Food & Wine')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (39, 37, '&nbsp; &nbsp;Entertainment')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (40, 38, '&nbsp; &nbsp;Foreign Language Instruction')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (41, 48, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (42, 40, '&nbsp; &nbsp;Health, Mind & Body')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (43, 63, '&nbsp; &nbsp;Historical')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (44, 41, '&nbsp; &nbsp;History')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (45, 42, '&nbsp; &nbsp;Home & Garden')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (46, 43, '&nbsp; &nbsp;Horror')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (47, 49, '&nbsp; &nbsp;Illustrated')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (48, 44, '&nbsp; &nbsp;Literature & Fiction')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (49, 50, '&nbsp; &nbsp;Men')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (50, 55, '&nbsp; &nbsp;Mystery & Thrillers')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (51, 51, '&nbsp; &nbsp;News')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (52, 56, '&nbsp; &nbsp;Nonfiction')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (53, 57, '&nbsp; &nbsp;Parenting & Families')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (54, 58, '&nbsp; &nbsp;Poetry')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (55, 59, '&nbsp; &nbsp;Rare')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (56, 60, '&nbsp; &nbsp;Reference')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (57, 64, '&nbsp; &nbsp;Regency')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (58, 61, '&nbsp; &nbsp;Religion & Spirituality')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (59, 65, '&nbsp; &nbsp;Science & Nature')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (60, 66, '&nbsp; &nbsp;Science Fiction & Fantasy')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (61, 67, '&nbsp; &nbsp;Sports & Outdoors')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (62, 68, '&nbsp; &nbsp;Teens')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (63, 69, '&nbsp; &nbsp;Textbooks')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (64, 70, '&nbsp; &nbsp;Travel')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (65, 54, '&nbsp; &nbsp;Women')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (66, 71, 'Clothing & Accessories')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (67, 72, '&nbsp; &nbsp;Accessories')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (68, 73, '&nbsp; &nbsp;Clothing')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (69, 74, '&nbsp; &nbsp;Watches')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (70, 75, 'Coins & Stamps')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (71, 76, '&nbsp; &nbsp;Coins')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (72, 77, '&nbsp; &nbsp;Philately')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (73, 78, 'Collectibles')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (74, 79, '&nbsp; &nbsp;Advertising')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (75, 80, '&nbsp; &nbsp;Animals')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (76, 81, '&nbsp; &nbsp;Animation')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (77, 82, '&nbsp; &nbsp;Antique Reproductions')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (78, 83, '&nbsp; &nbsp;Autographs')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (79, 84, '&nbsp; &nbsp;Barber Shop')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (80, 85, '&nbsp; &nbsp;Bears')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (81, 86, '&nbsp; &nbsp;Bells')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (82, 87, '&nbsp; &nbsp;Bottles & Cans')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (83, 88, '&nbsp; &nbsp;Breweriana')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (84, 89, '&nbsp; &nbsp;Cars & Motorcycles')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (85, 90, '&nbsp; &nbsp;Cereal Boxes & Premiums')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (86, 91, '&nbsp; &nbsp;Character')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (87, 92, '&nbsp; &nbsp;Circus & Carnival')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (88, 93, '&nbsp; &nbsp;Collector Plates')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (89, 94, '&nbsp; &nbsp;Dolls')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (90, 95, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (91, 96, '&nbsp; &nbsp;Historical & Cultural')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (92, 97, '&nbsp; &nbsp;Holiday & Seasonal')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (93, 98, '&nbsp; &nbsp;Household Items')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (94, 99, '&nbsp; &nbsp;Kitsch')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (95, 100, '&nbsp; &nbsp;Knives & Swords')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (96, 101, '&nbsp; &nbsp;Lunchboxes')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (97, 102, '&nbsp; &nbsp;Magic & Novelty Items')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (98, 103, '&nbsp; &nbsp;Memorabilia')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (99, 104, '&nbsp; &nbsp;Militaria')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (100, 105, '&nbsp; &nbsp;Music Boxes')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (101, 106, '&nbsp; &nbsp;Oddities')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (102, 107, '&nbsp; &nbsp;Paper')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (103, 108, '&nbsp; &nbsp;Pinbacks')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (104, 109, '&nbsp; &nbsp;Porcelain Figurines')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (105, 110, '&nbsp; &nbsp;Railroadiana')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (106, 111, '&nbsp; &nbsp;Religious')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (107, 112, '&nbsp; &nbsp;Rocks, Minerals & Fossils')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (108, 113, '&nbsp; &nbsp;Scientific Instruments')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (109, 114, '&nbsp; &nbsp;Textiles')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (110, 115, '&nbsp; &nbsp;Tobacciana')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (111, 116, 'Comics, Cards & Science Fiction')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (112, 117, '&nbsp; &nbsp;Anime & Manga')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (113, 118, '&nbsp; &nbsp;Comic Books')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (114, 119, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (115, 120, '&nbsp; &nbsp;Godzilla')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (116, 121, '&nbsp; &nbsp;Star Trek')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (117, 122, '&nbsp; &nbsp;The X-Files')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (118, 123, '&nbsp; &nbsp;Toys')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (119, 124, '&nbsp; &nbsp;Trading Cards')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (120, 125, 'Computers & Software')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (121, 126, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (122, 127, '&nbsp; &nbsp;Hardware')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (123, 128, '&nbsp; &nbsp;Internet Services')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (124, 129, '&nbsp; &nbsp;Software')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (125, 130, 'Electronics & Photography')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (126, 131, '&nbsp; &nbsp;Consumer Electronics')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (127, 132, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (128, 133, '&nbsp; &nbsp;Photo Equipment')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (129, 134, '&nbsp; &nbsp;Recording Equipment')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (130, 135, '&nbsp; &nbsp;Video Equipment')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (131, 136, 'Gemstones & Jewelry')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (132, 137, '&nbsp; &nbsp;Ancient')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (133, 138, '&nbsp; &nbsp;Beaded Jewelry')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (134, 139, '&nbsp; &nbsp;Beads')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (135, 140, '&nbsp; &nbsp;Carved & Cameo')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (136, 141, '&nbsp; &nbsp;Contemporary')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (137, 142, '&nbsp; &nbsp;Costume')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (138, 143, '&nbsp; &nbsp;Fine')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (139, 144, '&nbsp; &nbsp;Gemstones')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (140, 145, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (141, 146, '&nbsp; &nbsp;Gold')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (142, 147, '&nbsp; &nbsp;Necklaces')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (143, 148, '&nbsp; &nbsp;Silver')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (144, 149, '&nbsp; &nbsp;Victorian')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (145, 150, '&nbsp; &nbsp;Vintage')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (146, 151, 'Home & Garden')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (147, 152, '&nbsp; &nbsp;Baby Items')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (148, 153, '&nbsp; &nbsp;Crafts')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (149, 154, '&nbsp; &nbsp;Furniture')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (150, 155, '&nbsp; &nbsp;Garden')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (151, 156, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (152, 157, '&nbsp; &nbsp;Household Items')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (153, 158, '&nbsp; &nbsp;Pet Supplies')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (154, 159, '&nbsp; &nbsp;Tools & Hardware')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (155, 160, '&nbsp; &nbsp;Weddings')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (156, 161, 'Movies & Video')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (157, 162, '&nbsp; &nbsp;DVD')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (158, 163, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (159, 164, '&nbsp; &nbsp;Laser Discs')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (160, 165, '&nbsp; &nbsp;VHS')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (161, 166, 'Music')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (162, 167, '&nbsp; &nbsp;CDs')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (163, 168, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (164, 169, '&nbsp; &nbsp;Instruments')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (165, 170, '&nbsp; &nbsp;Memorabilia')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (166, 171, '&nbsp; &nbsp;Records')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (167, 172, '&nbsp; &nbsp;Tapes')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (168, 173, 'Office & Business')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (169, 174, '&nbsp; &nbsp;Briefcases')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (170, 175, '&nbsp; &nbsp;Fax Machines')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (171, 176, '&nbsp; &nbsp;General Equipment')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (172, 177, '&nbsp; &nbsp;Pagers')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (173, 178, 'Other Goods & Services')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (174, 179, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (175, 180, '&nbsp; &nbsp;Metaphysical')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (176, 181, '&nbsp; &nbsp;Property')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (177, 182, '&nbsp; &nbsp;Services')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (178, 183, '&nbsp; &nbsp;Tickets & Events')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (179, 184, '&nbsp; &nbsp;Transportation')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (180, 185, '&nbsp; &nbsp;Travel')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (181, 186, 'Sports & Recreation')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (182, 187, '&nbsp; &nbsp;Apparel & Equipment')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (183, 188, '&nbsp; &nbsp;Exercise Equipment')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (184, 189, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (185, 190, 'Toys & Games')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (186, 191, '&nbsp; &nbsp;Action Figures')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (187, 192, '&nbsp; &nbsp;Beanie Babies & Beanbag Toys')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (188, 193, '&nbsp; &nbsp;Diecast')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (189, 194, '&nbsp; &nbsp;Fast Food')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (190, 195, '&nbsp; &nbsp;Fisher-Price')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (191, 196, '&nbsp; &nbsp;Furby')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (192, 197, '&nbsp; &nbsp;Games')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (193, 198, '&nbsp; &nbsp;General')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (194, 199, '&nbsp; &nbsp;Giga Pet & Tamagotchi')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (195, 200, '&nbsp; &nbsp;Hobbies')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (196, 201, '&nbsp; &nbsp;Marbles')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (197, 202, '&nbsp; &nbsp;My Little Pony')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (198, 203, '&nbsp; &nbsp;Peanuts Gang')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (199, 204, '&nbsp; &nbsp;Pez')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (200, 205, '&nbsp; &nbsp;Plastic Models')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (201, 206, '&nbsp; &nbsp;Plush Toys')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (202, 207, '&nbsp; &nbsp;Puzzles')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (203, 208, '&nbsp; &nbsp;Slot Cars')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (204, 209, '&nbsp; &nbsp;Teletubbies')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (205, 210, '&nbsp; &nbsp;Toy Soldiers')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (206, 211, '&nbsp; &nbsp;Vintage Tin')");
mysql_query("INSERT INTO $phpauction_categories_plain VALUES (207, 212, '&nbsp; &nbsp;Vintage Vehicles')");

$msg.= "$std_font Dumping data to table $phpauction_categories_plain : Done<BR>";

# --------------------------------------------------------

#
# Table structure for table `counters`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_counters");
if (mysql_query("CREATE TABLE $phpauction_counters (
  users int(11) default '0',
  auctions int(11) default '0'
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_counters : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_counters : Failed</font><BR>"; }

#
# Dumping data for table `counters`
#

mysql_query("INSERT INTO $phpauction_counters VALUES (0, 0)");

$msg.= "$std_font Dumping data to table $phpauction_caunters : Done<BR>";

# --------------------------------------------------------

#
# Table structure for table `countries`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_countries");
if (mysql_query("CREATE TABLE $phpauction_countries (
  country_id int(2) NOT NULL auto_increment,
  country varchar(30) NOT NULL default '',
  PRIMARY KEY  (country_id)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_countries : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_countries : Failed</font><BR>"; }


#
# Dumping data for table `countries`
#

mysql_query("INSERT INTO $phpauction_countries VALUES (1, 'Afghanistan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (2, 'Albania')");
mysql_query("INSERT INTO $phpauction_countries VALUES (3, 'Algeria')");
mysql_query("INSERT INTO $phpauction_countries VALUES (4, 'American Samoa')");
mysql_query("INSERT INTO $phpauction_countries VALUES (5, 'Andorra')");
mysql_query("INSERT INTO $phpauction_countries VALUES (6, 'Angola')");
mysql_query("INSERT INTO $phpauction_countries VALUES (7, 'Anguilla')");
mysql_query("INSERT INTO $phpauction_countries VALUES (8, 'Antarctica')");
mysql_query("INSERT INTO $phpauction_countries VALUES (9, 'Antigua And Barbuda')");
mysql_query("INSERT INTO $phpauction_countries VALUES (10, 'Argentina')");
mysql_query("INSERT INTO $phpauction_countries VALUES (11, 'Armenia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (12, 'Aruba')");
mysql_query("INSERT INTO $phpauction_countries VALUES (13, 'Australia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (14, 'Austria')");
mysql_query("INSERT INTO $phpauction_countries VALUES (15, 'Azerbaijan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (16, 'Bahamas')");
mysql_query("INSERT INTO $phpauction_countries VALUES (17, 'Bahrain')");
mysql_query("INSERT INTO $phpauction_countries VALUES (18, 'Bangladesh')");
mysql_query("INSERT INTO $phpauction_countries VALUES (19, 'Barbados')");
mysql_query("INSERT INTO $phpauction_countries VALUES (20, 'Belarus')");
mysql_query("INSERT INTO $phpauction_countries VALUES (21, 'Belgium')");
mysql_query("INSERT INTO $phpauction_countries VALUES (22, 'Belize')");
mysql_query("INSERT INTO $phpauction_countries VALUES (23, 'Benin')");
mysql_query("INSERT INTO $phpauction_countries VALUES (24, 'Bermuda')");
mysql_query("INSERT INTO $phpauction_countries VALUES (25, 'Bhutan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (26, 'Bolivia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (27, 'Bosnia and Herzegowina')");
mysql_query("INSERT INTO $phpauction_countries VALUES (28, 'Botswana')");
mysql_query("INSERT INTO $phpauction_countries VALUES (29, 'Bouvet Island')");
mysql_query("INSERT INTO $phpauction_countries VALUES (30, 'Brazil')");
mysql_query("INSERT INTO $phpauction_countries VALUES (31, 'British Indian Ocean Territory')");
mysql_query("INSERT INTO $phpauction_countries VALUES (32, 'Brunei Darussalam')");
mysql_query("INSERT INTO $phpauction_countries VALUES (33, 'Bulgaria')");
mysql_query("INSERT INTO $phpauction_countries VALUES (34, 'Burkina Faso')");
mysql_query("INSERT INTO $phpauction_countries VALUES (35, 'Burma')");
mysql_query("INSERT INTO $phpauction_countries VALUES (36, 'Burundi')");
mysql_query("INSERT INTO $phpauction_countries VALUES (37, 'Cambodia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (38, 'Cameroon')");
mysql_query("INSERT INTO $phpauction_countries VALUES (39, 'Canada')");
mysql_query("INSERT INTO $phpauction_countries VALUES (40, 'Cape Verde')");
mysql_query("INSERT INTO $phpauction_countries VALUES (41, 'Cayman Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (42, 'Central African Republic')");
mysql_query("INSERT INTO $phpauction_countries VALUES (43, 'Chad')");
mysql_query("INSERT INTO $phpauction_countries VALUES (44, 'Chile')");
mysql_query("INSERT INTO $phpauction_countries VALUES (45, 'China')");
mysql_query("INSERT INTO $phpauction_countries VALUES (46, 'Christmas Island')");
mysql_query("INSERT INTO $phpauction_countries VALUES (47, 'Cocos (Keeling) Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (48, 'Colombia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (49, 'Comoros')");
mysql_query("INSERT INTO $phpauction_countries VALUES (50, 'Congo')");
mysql_query("INSERT INTO $phpauction_countries VALUES (51, 'Congo, the Democratic Republic')");
mysql_query("INSERT INTO $phpauction_countries VALUES (52, 'Cook Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (53, 'Costa Rica')");
mysql_query("INSERT INTO $phpauction_countries VALUES (54, 'Cote d\'Ivoire')");
mysql_query("INSERT INTO $phpauction_countries VALUES (55, 'Croatia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (56, 'Cyprus')");
mysql_query("INSERT INTO $phpauction_countries VALUES (57, 'Czech Republic')");
mysql_query("INSERT INTO $phpauction_countries VALUES (58, 'Denmark')");
mysql_query("INSERT INTO $phpauction_countries VALUES (59, 'Djibouti')");
mysql_query("INSERT INTO $phpauction_countries VALUES (60, 'Dominica')");
mysql_query("INSERT INTO $phpauction_countries VALUES (61, 'Dominican Republic')");
mysql_query("INSERT INTO $phpauction_countries VALUES (62, 'East Timor')");
mysql_query("INSERT INTO $phpauction_countries VALUES (63, 'Ecuador')");
mysql_query("INSERT INTO $phpauction_countries VALUES (64, 'Egypt')");
mysql_query("INSERT INTO $phpauction_countries VALUES (65, 'El Salvador')");
mysql_query("INSERT INTO $phpauction_countries VALUES (66, 'England')");
mysql_query("INSERT INTO $phpauction_countries VALUES (67, 'Equatorial Guinea')");
mysql_query("INSERT INTO $phpauction_countries VALUES (68, 'Eritrea')");
mysql_query("INSERT INTO $phpauction_countries VALUES (69, 'Estonia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (70, 'Ethiopia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (71, 'Falkland Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (72, 'Faroe Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (73, 'Fiji')");
mysql_query("INSERT INTO $phpauction_countries VALUES (74, 'Finland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (75, 'France')");
mysql_query("INSERT INTO $phpauction_countries VALUES (76, 'French Guiana')");
mysql_query("INSERT INTO $phpauction_countries VALUES (77, 'French Polynesia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (78, 'French Southern Territories')");
mysql_query("INSERT INTO $phpauction_countries VALUES (79, 'Gabon')");
mysql_query("INSERT INTO $phpauction_countries VALUES (80, 'Gambia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (81, 'Georgia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (82, 'Ghana')");
mysql_query("INSERT INTO $phpauction_countries VALUES (83, 'Gibraltar')");
mysql_query("INSERT INTO $phpauction_countries VALUES (84, 'Great Britain')");
mysql_query("INSERT INTO $phpauction_countries VALUES (85, 'Greece')");
mysql_query("INSERT INTO $phpauction_countries VALUES (86, 'Greenland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (87, 'Grenada')");
mysql_query("INSERT INTO $phpauction_countries VALUES (88, 'Guadeloupe')");
mysql_query("INSERT INTO $phpauction_countries VALUES (89, 'Guam')");
mysql_query("INSERT INTO $phpauction_countries VALUES (90, 'Guatemala')");
mysql_query("INSERT INTO $phpauction_countries VALUES (91, 'Guinea')");
mysql_query("INSERT INTO $phpauction_countries VALUES (92, 'Guinea-Bissau')");
mysql_query("INSERT INTO $phpauction_countries VALUES (93, 'Guyana')");
mysql_query("INSERT INTO $phpauction_countries VALUES (94, 'Haiti')");
mysql_query("INSERT INTO $phpauction_countries VALUES (95, 'Heard and Mc Donald Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (96, 'Holy See (Vatican City State)')");
mysql_query("INSERT INTO $phpauction_countries VALUES (97, 'Honduras')");
mysql_query("INSERT INTO $phpauction_countries VALUES (98, 'Hong Kong')");
mysql_query("INSERT INTO $phpauction_countries VALUES (99, 'Hungary')");
mysql_query("INSERT INTO $phpauction_countries VALUES (100, 'Iceland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (101, 'India')");
mysql_query("INSERT INTO $phpauction_countries VALUES (102, 'Indonesia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (103, 'Ireland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (104, 'Israel')");
mysql_query("INSERT INTO $phpauction_countries VALUES (105, 'Italy')");
mysql_query("INSERT INTO $phpauction_countries VALUES (106, 'Jamaica')");
mysql_query("INSERT INTO $phpauction_countries VALUES (107, 'Japan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (108, 'Jordan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (109, 'Kazakhstan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (110, 'Kenya')");
mysql_query("INSERT INTO $phpauction_countries VALUES (111, 'Kiribati')");
mysql_query("INSERT INTO $phpauction_countries VALUES (112, 'Korea (South)')");
mysql_query("INSERT INTO $phpauction_countries VALUES (113, 'Kuwait')");
mysql_query("INSERT INTO $phpauction_countries VALUES (114, 'Kyrgyzstan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (115, 'Lao People\'s Democratic Republ')");
mysql_query("INSERT INTO $phpauction_countries VALUES (116, 'Latvia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (117, 'Lebanon')");
mysql_query("INSERT INTO $phpauction_countries VALUES (118, 'Lesotho')");
mysql_query("INSERT INTO $phpauction_countries VALUES (119, 'Liberia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (120, 'Liechtenstein')");
mysql_query("INSERT INTO $phpauction_countries VALUES (121, 'Lithuania')");
mysql_query("INSERT INTO $phpauction_countries VALUES (122, 'Luxembourg')");
mysql_query("INSERT INTO $phpauction_countries VALUES (123, 'Macau')");
mysql_query("INSERT INTO $phpauction_countries VALUES (124, 'Macedonia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (125, 'Madagascar')");
mysql_query("INSERT INTO $phpauction_countries VALUES (126, 'Malawi')");
mysql_query("INSERT INTO $phpauction_countries VALUES (127, 'Malaysia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (128, 'Maldives')");
mysql_query("INSERT INTO $phpauction_countries VALUES (129, 'Mali')");
mysql_query("INSERT INTO $phpauction_countries VALUES (130, 'Malta')");
mysql_query("INSERT INTO $phpauction_countries VALUES (131, 'Marshall Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (132, 'Martinique')");
mysql_query("INSERT INTO $phpauction_countries VALUES (133, 'Mauritania')");
mysql_query("INSERT INTO $phpauction_countries VALUES (134, 'Mauritius')");
mysql_query("INSERT INTO $phpauction_countries VALUES (135, 'Mayotte')");
mysql_query("INSERT INTO $phpauction_countries VALUES (136, 'Mexico')");
mysql_query("INSERT INTO $phpauction_countries VALUES (137, 'Micronesia, Federated States o')");
mysql_query("INSERT INTO $phpauction_countries VALUES (138, 'Moldova, Republic of')");
mysql_query("INSERT INTO $phpauction_countries VALUES (139, 'Monaco')");
mysql_query("INSERT INTO $phpauction_countries VALUES (140, 'Mongolia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (141, 'Montserrat')");
mysql_query("INSERT INTO $phpauction_countries VALUES (142, 'Morocco')");
mysql_query("INSERT INTO $phpauction_countries VALUES (143, 'Mozambique')");
mysql_query("INSERT INTO $phpauction_countries VALUES (144, 'Namibia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (145, 'Nauru')");
mysql_query("INSERT INTO $phpauction_countries VALUES (146, 'Nepal')");
mysql_query("INSERT INTO $phpauction_countries VALUES (147, 'Netherlands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (148, 'Netherlands Antilles')");
mysql_query("INSERT INTO $phpauction_countries VALUES (149, 'New Caledonia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (150, 'New Zealand')");
mysql_query("INSERT INTO $phpauction_countries VALUES (151, 'Nicaragua')");
mysql_query("INSERT INTO $phpauction_countries VALUES (152, 'Niger')");
mysql_query("INSERT INTO $phpauction_countries VALUES (153, 'Nigeria')");
mysql_query("INSERT INTO $phpauction_countries VALUES (154, 'Niuev')");
mysql_query("INSERT INTO $phpauction_countries VALUES (155, 'Norfolk Island')");
mysql_query("INSERT INTO $phpauction_countries VALUES (156, 'Northern Ireland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (157, 'Northern Mariana Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (158, 'Norway')");
mysql_query("INSERT INTO $phpauction_countries VALUES (159, 'Oman')");
mysql_query("INSERT INTO $phpauction_countries VALUES (160, 'Pakistan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (161, 'Palau')");
mysql_query("INSERT INTO $phpauction_countries VALUES (162, 'Panama')");
mysql_query("INSERT INTO $phpauction_countries VALUES (163, 'Papua New Guinea')");
mysql_query("INSERT INTO $phpauction_countries VALUES (164, 'Paraguay')");
mysql_query("INSERT INTO $phpauction_countries VALUES (165, 'Peru')");
mysql_query("INSERT INTO $phpauction_countries VALUES (166, 'Philippines')");
mysql_query("INSERT INTO $phpauction_countries VALUES (167, 'Pitcairn')");
mysql_query("INSERT INTO $phpauction_countries VALUES (168, 'Poland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (169, 'Portugal')");
mysql_query("INSERT INTO $phpauction_countries VALUES (170, 'Puerto Rico')");
mysql_query("INSERT INTO $phpauction_countries VALUES (171, 'Qatar')");
mysql_query("INSERT INTO $phpauction_countries VALUES (172, 'Reunion')");
mysql_query("INSERT INTO $phpauction_countries VALUES (173, 'Romania')");
mysql_query("INSERT INTO $phpauction_countries VALUES (174, 'Russian Federation')");
mysql_query("INSERT INTO $phpauction_countries VALUES (175, 'Rwanda')");
mysql_query("INSERT INTO $phpauction_countries VALUES (176, 'Saint Kitts and Nevis')");
mysql_query("INSERT INTO $phpauction_countries VALUES (177, 'Saint Lucia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (178, 'Saint Vincent and the Grenadin')");
mysql_query("INSERT INTO $phpauction_countries VALUES (179, 'Samoa (Independent)')");
mysql_query("INSERT INTO $phpauction_countries VALUES (180, 'San Marino')");
mysql_query("INSERT INTO $phpauction_countries VALUES (181, 'Sao Tome and Principe')");
mysql_query("INSERT INTO $phpauction_countries VALUES (182, 'Saudi Arabia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (183, 'Scotland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (184, 'Senegal')");
mysql_query("INSERT INTO $phpauction_countries VALUES (185, 'Seychelles')");
mysql_query("INSERT INTO $phpauction_countries VALUES (186, 'Sierra Leone')");
mysql_query("INSERT INTO $phpauction_countries VALUES (187, 'Singapore')");
mysql_query("INSERT INTO $phpauction_countries VALUES (188, 'Slovakia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (189, 'Slovenia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (190, 'Solomon Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (191, 'Somalia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (192, 'South Africa')");
mysql_query("INSERT INTO $phpauction_countries VALUES (193, 'South Georgia and the South Sa')");
mysql_query("INSERT INTO $phpauction_countries VALUES (194, 'Spain')");
mysql_query("INSERT INTO $phpauction_countries VALUES (195, 'Sri Lanka')");
mysql_query("INSERT INTO $phpauction_countries VALUES (196, 'St. Helena')");
mysql_query("INSERT INTO $phpauction_countries VALUES (197, 'St. Pierre and Miquelon')");
mysql_query("INSERT INTO $phpauction_countries VALUES (198, 'Suriname')");
mysql_query("INSERT INTO $phpauction_countries VALUES (199, 'Svalbard and Jan Mayen Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (200, 'Swaziland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (201, 'Sweden')");
mysql_query("INSERT INTO $phpauction_countries VALUES (202, 'Switzerland')");
mysql_query("INSERT INTO $phpauction_countries VALUES (203, 'Taiwan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (204, 'Tajikistan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (205, 'Tanzania')");
mysql_query("INSERT INTO $phpauction_countries VALUES (206, 'Thailand')");
mysql_query("INSERT INTO $phpauction_countries VALUES (207, 'Togo')");
mysql_query("INSERT INTO $phpauction_countries VALUES (208, 'Tokelau')");
mysql_query("INSERT INTO $phpauction_countries VALUES (209, 'Tonga')");
mysql_query("INSERT INTO $phpauction_countries VALUES (210, 'Trinidad and Tobago')");
mysql_query("INSERT INTO $phpauction_countries VALUES (211, 'Tunisia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (212, 'Turkey')");
mysql_query("INSERT INTO $phpauction_countries VALUES (213, 'Turkmenistan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (214, 'Turks and Caicos Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (215, 'Tuvalu')");
mysql_query("INSERT INTO $phpauction_countries VALUES (216, 'Uganda')");
mysql_query("INSERT INTO $phpauction_countries VALUES (217, 'Ukraine')");
mysql_query("INSERT INTO $phpauction_countries VALUES (218, 'United Arab Emiratesv')");
mysql_query("INSERT INTO $phpauction_countries VALUES (219, 'United States')");
mysql_query("INSERT INTO $phpauction_countries VALUES (220, 'Uruguay')");
mysql_query("INSERT INTO $phpauction_countries VALUES (221, 'Uzbekistan')");
mysql_query("INSERT INTO $phpauction_countries VALUES (222, 'Vanuatu')");
mysql_query("INSERT INTO $phpauction_countries VALUES (223, 'Venezuela')");
mysql_query("INSERT INTO $phpauction_countries VALUES (224, 'Viet Nam')");
mysql_query("INSERT INTO $phpauction_countries VALUES (225, 'Virgin Islands (British)')");
mysql_query("INSERT INTO $phpauction_countries VALUES (226, 'Virgin Islands (U.S.)')");
mysql_query("INSERT INTO $phpauction_countries VALUES (227, 'Wales')");
mysql_query("INSERT INTO $phpauction_countries VALUES (228, 'Wallis and Futuna Islands')");
mysql_query("INSERT INTO $phpauction_countries VALUES (229, 'Western Sahara')");
mysql_query("INSERT INTO $phpauction_countries VALUES (230, 'Yemen')");
mysql_query("INSERT INTO $phpauction_countries VALUES (231, 'Zambia')");
mysql_query("INSERT INTO $phpauction_countries VALUES (232, 'Zimbabwe')");
mysql_query("INSERT INTO $phpauction_countries VALUES (236, 'Germany')");

$msg.= "$std_font Dumping data to table $phpauction_countries : Done<BR>";

# --------------------------------------------------------

#
# Table structure for table `durations`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_durations");
if (mysql_query("CREATE TABLE $phpauction_durations (
  days int(2) NOT NULL default '0',
  description varchar(30) default NULL
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_durations : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_durations : Failed</font><BR>"; }


#
# Dumping data for table `durations`
#

mysql_query("INSERT INTO $phpauction_durations VALUES (1, '1 day')");
mysql_query("INSERT INTO $phpauction_durations VALUES (3, '3 days')");
mysql_query("INSERT INTO $phpauction_durations VALUES (7, '1 week')");
mysql_query("INSERT INTO $phpauction_durations VALUES (30, '1 month')");
mysql_query("INSERT INTO $phpauction_durations VALUES (60, '2 months')");
mysql_query("INSERT INTO $phpauction_durations VALUES (90, '3 months')");
mysql_query("INSERT INTO $phpauction_durations VALUES (15, '2 weeks')");

$msg.= "$std_font Dumping data to table $phpauction_durations : Done<BR>";

# --------------------------------------------------------

#
# Table structure for table `feedbacks`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_feedbacks");
if (mysql_query("CREATE TABLE $phpauction_feedbacks (
  rated_user_id varchar(32) default NULL,
  rater_user_nick varchar(20) default NULL,
  feedback mediumtext,
  rate int(2) default NULL,
  feedbackdate timestamp(14) NOT NULL
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_feedbacks : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_feedbacks : Failed</font><BR>"; }


#
# Dumping data for table `feedbacks`
#

# --------------------------------------------------------

#
# Table structure for table `help`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_help");
if (mysql_query("CREATE TABLE $phpauction_help (
  topic varchar(40) default NULL,
  helptext text
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_help : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_help : Failed</font><BR>"; }


#
# Dumping data for table `help`
#

mysql_query("INSERT INTO $phpauction_help VALUES ('General', \"Welcome to the PHPAuction, a web site that lets you buy and sell items in auction format.\r\n<br><br>\r\nRegistered users may place items up for auction and may bid on other user\'s items.  To register, you must provide your name, an address, and your email address.  You must be 18 years or older.\r\n<br><br>\r\nWhen selling an item, you may enter a description of the item, upload a photograph, and indicate the minimum bid and a reserve price for the item.  You also indicate what payments you will accept and whether you or the buyer will pay shipping.\r\n<br><br>\r\nSellers are notified by email when an auction is concluded.  If a winner exists, the winner and seller are provided each other\'s contact information.\r\n<br>\r\n\r\n\r\n\r\n\r\n\r\n\")");
mysql_query("INSERT INTO $phpauction_help VALUES ('Registering', \"To register as a new user, click on Register at the top of the window.  You will be asked for your name, a username and password, and contact information, including your email address.\r\n<br><br>\r\n<center><font color=red>You must be at least 18 years of age to register.</font></center>\r\n<br><br>\r\n\")");
mysql_query("INSERT INTO $phpauction_help VALUES ('Bidding', \"To bid on an item, type your bid amount in the box next to the item description, and click the 'Go' button.  Your bid must be above the Minimum Bid amount specified in the box.\r\n<br><br>\r\nYou will be asked to confirm your bid.  Fill in your username and password and click 'Submit' to complete your bid.\r\n<br><br>\r\n\")");
mysql_query("INSERT INTO $phpauction_help VALUES ('Selling', \"To sell an item, you must be a <a href='help.php?topic=Registering'>registered user</a>.\r\n<br><br>\r\nClick on 'Sell an Item' at the top of the window to create a new auction.  Indicate the title and description of your item, and select a graphic image from your local hard drive if you wish to upload a picture of the item.\r\n<br><br>\r\nSpecify the minimum bid and reserve price (optional) for your auction, and what types of payment you will accept.  While this site allows you to specify payment methods, it does not process the payment for you.\r\n<br><br>\r\nChoose the category in which your item should be.  You may suggest a new category, but you must select an existing category for your new auction.\r\n<br><br>\r\n\")");

$msg.= "$std_font Dumping data to table $phpauction_help : Done<BR>";

# --------------------------------------------------------

#
# Table structure for table `increments`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_increments");
if (mysql_query("CREATE TABLE $phpauction_increments (
  id char(3) default NULL,
  low double(16,4) default NULL,
  high double(16,4) default NULL,
  increment double(16,4) default NULL
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_increments : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_increments : Failed</font><BR>"; }


#
# Dumping data for table `increments`
#

mysql_query("INSERT INTO $phpauction_increments VALUES ('1', '0.0000', '0.9900', '0.2500')");
mysql_query("INSERT INTO $phpauction_increments VALUES ('2', '1.0000', '9.9900', '0.5000')");
mysql_query("INSERT INTO $phpauction_increments VALUES ('3', '10.0000', '29.9900', '1.0000')");
mysql_query("INSERT INTO $phpauction_increments VALUES ('4', '30.0000', '99.9900', '2.0000')");
mysql_query("INSERT INTO $phpauction_increments VALUES ('5', '100.0000', '249.9900', '5.0000')");
mysql_query("INSERT INTO $phpauction_increments VALUES ('6', '250.0000', '499.9900', '10.0000')");
mysql_query("INSERT INTO $phpauction_increments VALUES ('7', '500.0000', '999.9900', '25.0000')");

$msg.= "$std_font Dumping data to table $phpauction_increments : Done<BR>";

# --------------------------------------------------------

#
# Table structure for table `news`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_news");
if (mysql_query("CREATE TABLE $phpauction_news (
  id varchar(32) NOT NULL default '',
  title varchar(200) NOT NULL default '',
  content longtext NOT NULL,
  new_date int(8) NOT NULL default '0',
  suspended int(1) NOT NULL default '0'
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_news : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_news : Failed</font><BR>"; }


#
# Dumping data for table `news`
#

# --------------------------------------------------------

#
# Table structure for table `payments`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_payments");
if (mysql_query("CREATE TABLE $phpauction_payments (
  id int(2) default NULL,
  description varchar(30) default NULL
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_payments : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_payments : Failed</font><BR>"; }


#
# Dumping data for table `payments`
#

mysql_query("INSERT INTO $phpauction_payments VALUES (1, 'Checks')");
mysql_query("INSERT INTO $phpauction_payments VALUES (2, 'Money Order')");
mysql_query("INSERT INTO $phpauction_payments VALUES (3, 'Paypal')");
mysql_query("INSERT INTO $phpauction_payments VALUES (4, 'MasterCard or Visa')");
mysql_query("INSERT INTO $phpauction_payments VALUES (5, 'Wire Transfer')");

$msg.= "$std_font Dumping data to table $phpauction_payments : Done<BR>";

# --------------------------------------------------------


#
# Dumping data for table `requests`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_request");
if (mysql_query("CREATE TABLE $phpauction_request (
  req_auction varchar(32) default NULL,
  req_user varchar(32) default NULL,
  req_text text,
  req_date timestamp(14) NOT NULL
) TYPE=MyISAM;"))
{ $msg.="$std_font Creating table $phpauction_request : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_request : Failed</font><BR>"; }


# --------------------------------------------------------

#
# Table structure for table `sessions`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_sessions");
if (mysql_query("CREATE TABLE $phpauction_sessions (
  id varchar(33) default '',
  vars text,
  created timestamp(14) NOT NULL,
  last_visit timestamp(14) NOT NULL
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_sessions : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_sessions : Failed</font><BR>"; }


#
# Dumping data for table `sessions`
#

# --------------------------------------------------------

#
# Table structure for table `settings`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_settings");
if (mysql_query("CREATE TABLE $phpauction_settings (
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
  logo varchar(255) NOT NULL default ''
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_settings : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_settings : Failed</font><BR>"; }


#
# Dumping data for table `settings`
#

mysql_query("INSERT INTO $phpauction_settings VALUES ('PHPAUCTION', 'http://phpauction.org/', 'PHPAUCTIONPREFIX', 1, 1, 5, 1, 2, 2, 'USD', 1, 'By clicking below you agree to the terms of this website.', 'webmaster@phpauction.org', '33f12', '22o22', '21o22', '24o12', '22r22', '11r22', 'p', 'r', 'p', 'p', 'o', 1, 1, 'logo.gif')");

$msg.= "$std_font Dumping data to table $phpauction_settings : Done<BR>";

# --------------------------------------------------------

#
# Table structure for table `users`
#

mysql_query("DROP TABLE IF EXISTS $phpauction_users");
if (mysql_query("CREATE TABLE $phpauction_users (
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
  PRIMARY KEY  (id)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table $phpauction_users : Done<BR>"; } 
else { $msg.="$err_font Creating table $phpauction_users : Failed</font><BR>"; }


#
# Dumping data for table `users`
#

#
# Table structure for table `phpads_acls`
#

mysql_query("DROP TABLE IF EXISTS phpads_acls");
if (mysql_query("CREATE TABLE phpads_acls (
  bannerID mediumint(9) NOT NULL default '0',
  acl_con set('and','or') NOT NULL default '',
  acl_type enum('clientip','useragent','weekday','domain','source','time','language') NOT NULL default 'clientip',
  acl_data varchar(255) NOT NULL default '',
  acl_ad set('allow','deny') NOT NULL default '',
  acl_order int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (bannerID,acl_order),
  KEY bannerID (bannerID)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_acls : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_acls : Failed</font><BR>"; }


#
# Dumping data for table `phpads_acls`
#

# --------------------------------------------------------

#
# Table structure for table `phpads_adclicks`
#

mysql_query("DROP TABLE IF EXISTS phpads_adclicks");
if (mysql_query("CREATE TABLE phpads_adclicks (
  bannerID mediumint(9) NOT NULL default '0',
  t_stamp timestamp(14) NOT NULL,
  host varchar(255) NOT NULL default '',
  KEY clientID (bannerID)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_adclicks : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_adclicks : Failed</font><BR>"; }

#
# Dumping data for table `phpads_adclicks`
#

# --------------------------------------------------------

#
# Table structure for table `phpads_adstats`
#

mysql_query("DROP TABLE IF EXISTS phpads_adstats");
if (mysql_query("CREATE TABLE phpads_adstats (
  views int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  day date NOT NULL default '0000-00-00',
  BannerID smallint(6) NOT NULL default '0',
  PRIMARY KEY  (day,BannerID)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_adstats : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_adstats : Failed</font><BR>"; }

#
# Dumping data for table `phpads_adstats`
#

# --------------------------------------------------------

#
# Table structure for table `phpads_adviews`
#

mysql_query("DROP TABLE IF EXISTS phpads_adviews");
if (mysql_query("CREATE TABLE phpads_adviews (
  bannerID mediumint(9) NOT NULL default '0',
  t_stamp timestamp(14) NOT NULL,
  host varchar(255) NOT NULL default '',
  KEY clientID (bannerID)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_adviews : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_adviews : Failed</font><BR>"; }

#
# Dumping data for table `phpads_adviews`
#

# --------------------------------------------------------

#
# Table structure for table `phpads_banners`
#

mysql_query("DROP TABLE IF EXISTS phpads_banners");
if (mysql_query("CREATE TABLE phpads_banners (
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
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_banners : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_banners : Failed</font><BR>"; }

#
# Dumping data for table `phpads_banners`
#

# --------------------------------------------------------

#
# Table structure for table `phpads_clients`
#

mysql_query("DROP TABLE IF EXISTS phpads_clients");
if (mysql_query("CREATE TABLE phpads_clients (
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
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_clients : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_clients : Failed</font><BR>"; }

#
# Dumping data for table `phpads_clients`
#

# --------------------------------------------------------

#
# Table structure for table `phpads_session`
#

mysql_query("DROP TABLE IF EXISTS phpads_session");
if (mysql_query("CREATE TABLE phpads_session (
  SessionID varchar(32) NOT NULL default '',
  SessionData blob NOT NULL,
  LastUsed timestamp(14) NOT NULL,
  PRIMARY KEY  (SessionID)
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_session : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_session : Failed</font><BR>"; }

#
# Dumping data for table `phpads_session`
#

# --------------------------------------------------------

#
# Table structure for table `phpads_zones`
#

mysql_query("DROP TABLE IF EXISTS phpads_zones");
if (mysql_query("CREATE TABLE phpads_zones (
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
) TYPE=MyISAM;")) 
{ $msg.="$std_font Creating table phpads_zones : Done<BR>"; } 
else { $msg.="$err_font Creating table phpads_zones : Failed</font><BR>"; }

#
# Dumping data for table `phpads_zones`
#