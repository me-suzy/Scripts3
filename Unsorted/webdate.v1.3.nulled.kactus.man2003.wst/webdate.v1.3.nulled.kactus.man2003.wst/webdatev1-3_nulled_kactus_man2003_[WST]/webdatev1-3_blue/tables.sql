CREATE TABLE dt_address_book (
  id int(11) NOT NULL auto_increment,
  member_id int(11) default NULL,
  contact_profile_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_badwords (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_billing_history (
  id int(11) NOT NULL auto_increment,
  member_id int(11) default NULL,
  order_id varchar(255) default NULL,
  order_date int(11) default NULL,
  amount float default NULL,
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_blocked (
  id int(10) NOT NULL auto_increment,
  member_id int(10) NOT NULL default '0',
  blocked_id int(10) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;
CREATE TABLE dt_body_types (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_charge (
  id int(11) NOT NULL auto_increment,
  amount varchar(20) default NULL,
  name varchar(255) default NULL,
  type varchar(255) default NULL,
  quantity int(11) default NULL,
  description text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_countries (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_drinking (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_educations (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_eye_colors (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_faq (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  show_it int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_faq_answers (
  id int(11) NOT NULL auto_increment,
  faq_topic_id int(11) default NULL,
  name varchar(255) default NULL,
  description text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_favourites (
  id int(11) NOT NULL auto_increment,
  profile_id int(11) default NULL,
  owner_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_food (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_genders (
  id int(10) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;
CREATE TABLE dt_hair_colors (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_heights (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_im_messages (
  id int(10) NOT NULL auto_increment,
  rid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  message text NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;
CREATE TABLE dt_interests (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_interests_x (
  id int(11) NOT NULL auto_increment,
  profile_id int(11) default NULL,
  interest_id int(11) default NULL,
  val int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_lang_rates (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_languages (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_marital_status (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_matchfinder (
  id int(11) NOT NULL auto_increment,
  member_id int(11) default NULL,
  age_from int(11) default NULL,
  age_to int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_members (
  id int(11) NOT NULL auto_increment,
  login varchar(25) default NULL,
  pswd varchar(20) default NULL,
  email varchar(255) default NULL,
  name varchar(40) default NULL,
  gender varchar(10) default NULL,
  age int(11) default NULL,
  country varchar(255) default NULL,
  looking_for varchar(255) default NULL,
  ip_addr varchar(15) default NULL,
  reg_date int(11) default NULL,
  status int(11) default NULL,
  system_status int(11) default NULL,
  system_status_end int(11) default NULL,
  unlimited int(11) default NULL,
  unlimited_end int(11) default NULL,
  matchfinder int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_messages (
  id int(10) NOT NULL auto_increment,
  sid int(10) NOT NULL default '0',
  rid int(10) NOT NULL default '0',
  subject varchar(50) NOT NULL default '',
  message text NOT NULL,
  timesent int(10) NOT NULL default '0',
  is_read tinyint(1) NOT NULL default '0',
  show_in_inbox tinyint(1) NOT NULL default '0',
  show_in_sent tinyint(1) NOT NULL default '0',
  is_deleted tinyint(1) NOT NULL default '0',
  is_deleted2 tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;
CREATE TABLE dt_occupations (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_payment_security (
  id int(11) NOT NULL auto_increment,
  member_id int(11) default NULL,
  order_id int(11) default NULL,
  security_key varchar(255) default NULL,
  amount float default NULL,
  order_date int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_photos (
  id int(11) NOT NULL auto_increment,
  member_id varchar(255) default NULL,
  filename_1 varchar(255) default NULL,
  filename_2 varchar(255) default NULL,
  filename_3 varchar(255) NOT NULL default '',
  filename_4 varchar(255) NOT NULL default '',
  filename_5 varchar(255) NOT NULL default '',
  filename_6 varchar(255) NOT NULL default '',
  filename_7 varchar(255) NOT NULL default '',
  filename_8 varchar(255) NOT NULL default '',
  filename_9 varchar(255) NOT NULL default '',
  filename_10 varchar(255) NOT NULL default '',
  filename_11 varchar(255) NOT NULL default '',
  filename_12 varchar(255) NOT NULL default '',
  filename_13 varchar(255) NOT NULL default '',
  filename_14 varchar(255) NOT NULL default '',
  filename_15 varchar(255) NOT NULL default '',
  filename_16 varchar(255) NOT NULL default '',
  filename_17 varchar(255) NOT NULL default '',
  filename_18 varchar(255) NOT NULL default '',
  filename_19 varchar(255) NOT NULL default '',
  filename_20 varchar(255) NOT NULL default '',
  private_1 tinyint(1) NOT NULL default '0',
  private_2 tinyint(1) NOT NULL default '0',
  private_3 tinyint(1) NOT NULL default '0',
  private_4 tinyint(1) NOT NULL default '0',
  private_5 tinyint(1) NOT NULL default '0',
  private_6 tinyint(1) NOT NULL default '0',
  private_7 tinyint(1) NOT NULL default '0',
  private_8 tinyint(1) NOT NULL default '0',
  private_9 tinyint(1) NOT NULL default '0',
  private_10 tinyint(1) NOT NULL default '0',
  private_11 tinyint(1) NOT NULL default '0',
  private_12 tinyint(1) NOT NULL default '0',
  private_13 tinyint(1) NOT NULL default '0',
  private_14 tinyint(1) NOT NULL default '0',
  private_15 tinyint(1) NOT NULL default '0',
  private_16 tinyint(1) NOT NULL default '0',
  private_17 tinyint(1) NOT NULL default '0',
  private_18 tinyint(1) NOT NULL default '0',
  private_19 tinyint(1) NOT NULL default '0',
  private_20 tinyint(1) NOT NULL default '0',
  password varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_prepared_members (
  id int(11) NOT NULL auto_increment,
  login varchar(25) default NULL,
  pswd varchar(20) default NULL,
  email varchar(255) default NULL,
  ip_addr varchar(15) default NULL,
  step int(11) default NULL,
  idate int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_profile (
  id int(11) NOT NULL auto_increment,
  member_id int(11) default NULL,
  country int(11) default NULL,
  state varchar(255) default NULL,
  city varchar(255) default NULL,
  email varchar(255) default NULL,
  name varchar(255) default NULL,
  gender varchar(20) default NULL,
  birth_day int(11) default NULL,
  birth_month varchar(6) default NULL,
  birth_year int(11) default NULL,
  marital_status int(11) default NULL,
  children int(11) default NULL,
  drinking int(11) default NULL,
  smoking int(11) default NULL,
  food int(11) default NULL,
  eye_color int(11) default NULL,
  hair_color int(11) default NULL,
  height int(11) default NULL,
  body_type int(11) default NULL,
  race int(11) default NULL,
  religion int(11) default NULL,
  occupation int(11) default NULL,
  education int(11) default NULL,
  lang_1 int(11) default NULL,
  lang_1_rate int(11) default NULL,
  lang_2 int(11) default NULL,
  lang_2_rate int(11) default NULL,
  lang_3 int(11) default NULL,
  lang_3_rate int(11) default NULL,
  lang_4 int(11) default NULL,
  lang_4_rate int(11) default NULL,
  looking_for varchar(10) default NULL,
  age_from int(11) default NULL,
  age_to int(11) default NULL,
  general_info text,
  appearance_info text,
  looking_for_info text,
  status int(11) default NULL,
  finish_status int(11) default NULL,
  not_newbie int(11) default NULL,
  lastlogin int(10) NOT NULL default '0',
  zipcode varchar(5) NOT NULL default '',
  longitude double default NULL,
  latitude double default NULL,
  photo_pass varchar(25) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_races (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_reasons (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  reason text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_relationship (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_relationship_x (
  id int(11) NOT NULL auto_increment,
  profile_id int(11) default NULL,
  relationship_id int(11) default NULL,
  val int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_religions (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_smoking (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_stamps_balance (
  id int(11) NOT NULL auto_increment,
  member_id int(11) default NULL,
  balance int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_states (
  id int(11) NOT NULL auto_increment,
  parent_id int(11) default NULL,
  name varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_tips (
  id int(11) NOT NULL auto_increment,
  tip_text varchar(255) default NULL,
  visible int(11) default NULL,
  link varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE dt_usersonline (
  id int(8) NOT NULL auto_increment,
  timestamp int(15) NOT NULL default '0',
  ip varchar(40) NOT NULL default '',
  login varchar(25) NOT NULL default '',
  userid int(10) NOT NULL default '0',
  session_id varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;
CREATE TABLE dt_videos (
  id int(11) NOT NULL auto_increment,
  member_id int(11) NOT NULL default '0',
  filename_1 varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;
CREATE TABLE webDate_bd_access (
  id int(11) NOT NULL auto_increment,
  uid int(11) default NULL,
  sid int(11) default NULL,
  r int(11) default NULL,
  w int(11) default NULL,
  d int(11) default NULL,
  adds int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE webDate_bd_log (
  id int(11) NOT NULL auto_increment,
  uid int(11) default NULL,
  on_login int(11) default NULL,
  on_create int(11) default NULL,
  on_edit int(11) default NULL,
  on_search int(11) default NULL,
  on_delete int(11) default NULL,
  on_loading_module int(11) default NULL,
  on_error int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE webDate_bd_services (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  admin_only int(11) default NULL,
  link varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE webDate_bd_users (
  id int(11) NOT NULL auto_increment,
  login varchar(255) default NULL,
  pswd varchar(255) default NULL,
  status int(11) default NULL,
  admin_rights int(11) default NULL,
  log_user int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
INSERT INTO dt_body_types VALUES (1, 'Ample');
INSERT INTO dt_body_types VALUES (2, 'Athletic');
INSERT INTO dt_body_types VALUES (3, 'Average');
INSERT INTO dt_body_types VALUES (4, 'Cuddly');
INSERT INTO dt_body_types VALUES (5, 'Slim');
INSERT INTO dt_body_types VALUES (6, 'Very Cuddly');
INSERT INTO dt_charge VALUES (1, '25', '10 Contact Stamps', '0', 10, 'Allows you to contact 10 members.');
INSERT INTO dt_charge VALUES (2, '30', '20 Contact Stamps', '0', 20, 'Allows you to contact 20 people.');
INSERT INTO dt_charge VALUES (3, '50', '50 Contact Stamps', '0', 50, 'Allows you to contact 50 people.');
INSERT INTO dt_charge VALUES (4, '40', '3 Month Subscription', '1', 3, 'Contact as many users as you want for 3 months.');
INSERT INTO dt_charge VALUES (5, '60', '6 Month Subscription', '1', 6, 'Contact as many members as you want for 6 months.');
INSERT INTO dt_countries VALUES (1, 'United States');
INSERT INTO dt_countries VALUES (2, 'APO/FPO');
INSERT INTO dt_countries VALUES (3, 'Canada');
INSERT INTO dt_countries VALUES (4, 'United Kingdom');
INSERT INTO dt_countries VALUES (5, 'Afghanistan');
INSERT INTO dt_countries VALUES (6, 'Albania');
INSERT INTO dt_countries VALUES (7, 'Algeria');
INSERT INTO dt_countries VALUES (8, 'American Samoa');
INSERT INTO dt_countries VALUES (9, 'Andorra');
INSERT INTO dt_countries VALUES (10, 'Angola');
INSERT INTO dt_countries VALUES (11, 'Anguilla');
INSERT INTO dt_countries VALUES (12, 'Antigua and Barbuda');
INSERT INTO dt_countries VALUES (13, 'Argentina');
INSERT INTO dt_countries VALUES (14, 'Armenia');
INSERT INTO dt_countries VALUES (15, 'Aruba');
INSERT INTO dt_countries VALUES (16, 'Australia');
INSERT INTO dt_countries VALUES (17, 'Austria');
INSERT INTO dt_countries VALUES (18, 'Azerbaijan Republic');
INSERT INTO dt_countries VALUES (19, 'Bahamas');
INSERT INTO dt_countries VALUES (20, 'Bahrain');
INSERT INTO dt_countries VALUES (21, 'Bangladesh');
INSERT INTO dt_countries VALUES (22, 'Barbados');
INSERT INTO dt_countries VALUES (23, 'Belarus');
INSERT INTO dt_countries VALUES (24, 'Belgium');
INSERT INTO dt_countries VALUES (25, 'Belize');
INSERT INTO dt_countries VALUES (26, 'Benin');
INSERT INTO dt_countries VALUES (27, 'Bermuda');
INSERT INTO dt_countries VALUES (28, 'Bhutan');
INSERT INTO dt_countries VALUES (29, 'Bolivia');
INSERT INTO dt_countries VALUES (30, 'Bosnia and Herzegovina');
INSERT INTO dt_countries VALUES (31, 'Botswana');
INSERT INTO dt_countries VALUES (32, 'Brazil');
INSERT INTO dt_countries VALUES (33, 'British Virgin Islands');
INSERT INTO dt_countries VALUES (34, 'Brunei Darussalam');
INSERT INTO dt_countries VALUES (35, 'Bulgaria');
INSERT INTO dt_countries VALUES (36, 'Burkina Faso');
INSERT INTO dt_countries VALUES (37, 'Burma');
INSERT INTO dt_countries VALUES (38, 'Burundi');
INSERT INTO dt_countries VALUES (39, 'Cambodia');
INSERT INTO dt_countries VALUES (40, 'Cameroon');
INSERT INTO dt_countries VALUES (41, 'Canada');
INSERT INTO dt_countries VALUES (42, 'Cape Verde Islands');
INSERT INTO dt_countries VALUES (43, 'Cayman Islands');
INSERT INTO dt_countries VALUES (44, 'Central African Republic');
INSERT INTO dt_countries VALUES (45, 'Chad');
INSERT INTO dt_countries VALUES (46, 'Chile');
INSERT INTO dt_countries VALUES (47, 'China');
INSERT INTO dt_countries VALUES (48, 'Colombia');
INSERT INTO dt_countries VALUES (49, 'Comoros');
INSERT INTO dt_countries VALUES (50, 'Congo, Democratic Republic of the');
INSERT INTO dt_countries VALUES (51, 'Congo, Republic of the');
INSERT INTO dt_countries VALUES (52, 'Cook Islands');
INSERT INTO dt_countries VALUES (53, 'Costa Rica');
INSERT INTO dt_countries VALUES (54, 'Cote d Ivoire (Ivory Coast)');
INSERT INTO dt_countries VALUES (55, 'Croatia, Republic of');
INSERT INTO dt_countries VALUES (56, 'Cuba');
INSERT INTO dt_countries VALUES (57, 'Cyprus');
INSERT INTO dt_countries VALUES (58, 'Czech Republic');
INSERT INTO dt_countries VALUES (59, 'Denmark');
INSERT INTO dt_countries VALUES (60, 'Djibouti');
INSERT INTO dt_countries VALUES (61, 'Dominica');
INSERT INTO dt_countries VALUES (62, 'Dominican Republic');
INSERT INTO dt_countries VALUES (63, 'Ecuador');
INSERT INTO dt_countries VALUES (64, 'Egypt');
INSERT INTO dt_countries VALUES (65, 'El Salvador');
INSERT INTO dt_countries VALUES (66, 'Equatorial Guinea');
INSERT INTO dt_countries VALUES (67, 'Eritrea');
INSERT INTO dt_countries VALUES (68, 'Estonia');
INSERT INTO dt_countries VALUES (69, 'Ethiopia');
INSERT INTO dt_countries VALUES (70, 'Falkland Islands (Islas Malvinas)');
INSERT INTO dt_countries VALUES (71, 'Fiji');
INSERT INTO dt_countries VALUES (72, 'Finland');
INSERT INTO dt_countries VALUES (73, 'France');
INSERT INTO dt_countries VALUES (74, 'French Guiana');
INSERT INTO dt_countries VALUES (75, 'French Polynesia');
INSERT INTO dt_countries VALUES (76, 'Gabon Republic');
INSERT INTO dt_countries VALUES (77, 'Gambia');
INSERT INTO dt_countries VALUES (78, 'Georgia');
INSERT INTO dt_countries VALUES (79, 'Germany');
INSERT INTO dt_countries VALUES (80, 'Ghana');
INSERT INTO dt_countries VALUES (81, 'Gibraltar');
INSERT INTO dt_countries VALUES (82, 'Greece');
INSERT INTO dt_countries VALUES (83, 'Greenland');
INSERT INTO dt_countries VALUES (84, 'Grenada');
INSERT INTO dt_countries VALUES (85, 'Guadeloupe');
INSERT INTO dt_countries VALUES (86, 'Guam');
INSERT INTO dt_countries VALUES (87, 'Guatemala');
INSERT INTO dt_countries VALUES (88, 'Guernsey');
INSERT INTO dt_countries VALUES (89, 'Guinea');
INSERT INTO dt_countries VALUES (90, 'Guinea-Bissau');
INSERT INTO dt_countries VALUES (91, 'Guyana');
INSERT INTO dt_countries VALUES (92, 'Haiti');
INSERT INTO dt_countries VALUES (93, 'Honduras');
INSERT INTO dt_countries VALUES (94, 'Hong Kong');
INSERT INTO dt_countries VALUES (95, 'Hungary');
INSERT INTO dt_countries VALUES (96, 'Iceland');
INSERT INTO dt_countries VALUES (97, 'India');
INSERT INTO dt_countries VALUES (98, 'Indonesia');
INSERT INTO dt_countries VALUES (99, 'Iran');
INSERT INTO dt_countries VALUES (100, 'Iraq');
INSERT INTO dt_countries VALUES (101, 'Ireland');
INSERT INTO dt_countries VALUES (102, 'Israel');
INSERT INTO dt_countries VALUES (103, 'Italy');
INSERT INTO dt_countries VALUES (104, 'Jamaica');
INSERT INTO dt_countries VALUES (105, 'Jan Mayen');
INSERT INTO dt_countries VALUES (106, 'Japan');
INSERT INTO dt_countries VALUES (107, 'Jersey');
INSERT INTO dt_countries VALUES (108, 'Jordan');
INSERT INTO dt_countries VALUES (109, 'Kazakhstan');
INSERT INTO dt_countries VALUES (110, 'Kenya Coast Republic');
INSERT INTO dt_countries VALUES (111, 'Kiribati');
INSERT INTO dt_countries VALUES (112, 'Korea, North');
INSERT INTO dt_countries VALUES (113, 'Korea, South');
INSERT INTO dt_countries VALUES (114, 'Kuwait');
INSERT INTO dt_countries VALUES (115, 'Kyrgyzstan');
INSERT INTO dt_countries VALUES (116, 'Laos');
INSERT INTO dt_countries VALUES (117, 'Latvia');
INSERT INTO dt_countries VALUES (118, 'Lebanon');
INSERT INTO dt_countries VALUES (119, 'Liechtenstein');
INSERT INTO dt_countries VALUES (120, 'Lithuania');
INSERT INTO dt_countries VALUES (121, 'Luxembourg');
INSERT INTO dt_countries VALUES (122, 'Macau');
INSERT INTO dt_countries VALUES (123, 'Macedonia');
INSERT INTO dt_countries VALUES (124, 'Madagascar');
INSERT INTO dt_countries VALUES (125, 'Malawi');
INSERT INTO dt_countries VALUES (126, 'Malaysia');
INSERT INTO dt_countries VALUES (127, 'Maldives');
INSERT INTO dt_countries VALUES (128, 'Mali');
INSERT INTO dt_countries VALUES (129, 'Malta');
INSERT INTO dt_countries VALUES (130, 'Marshall Islands');
INSERT INTO dt_countries VALUES (131, 'Martinique');
INSERT INTO dt_countries VALUES (132, 'Mauritania');
INSERT INTO dt_countries VALUES (133, 'Mauritius');
INSERT INTO dt_countries VALUES (134, 'Mayotte');
INSERT INTO dt_countries VALUES (135, 'Mexico');
INSERT INTO dt_countries VALUES (136, 'Moldova');
INSERT INTO dt_countries VALUES (137, 'Monaco');
INSERT INTO dt_countries VALUES (138, 'Mongolia');
INSERT INTO dt_countries VALUES (139, 'Montserrat');
INSERT INTO dt_countries VALUES (140, 'Morocco');
INSERT INTO dt_countries VALUES (141, 'Mozambique');
INSERT INTO dt_countries VALUES (142, 'Namibia');
INSERT INTO dt_countries VALUES (143, 'Nauru');
INSERT INTO dt_countries VALUES (144, 'Nepal');
INSERT INTO dt_countries VALUES (145, 'Netherlands');
INSERT INTO dt_countries VALUES (146, 'Netherlands Antilles');
INSERT INTO dt_countries VALUES (147, 'New Caledonia');
INSERT INTO dt_countries VALUES (148, 'New Zealand');
INSERT INTO dt_countries VALUES (149, 'Nicaragua');
INSERT INTO dt_countries VALUES (150, 'Niger');
INSERT INTO dt_countries VALUES (151, 'Nigeria');
INSERT INTO dt_countries VALUES (152, 'Niue');
INSERT INTO dt_countries VALUES (153, 'Norway');
INSERT INTO dt_countries VALUES (154, 'Oman');
INSERT INTO dt_countries VALUES (155, 'Pakistan');
INSERT INTO dt_countries VALUES (156, 'Palau');
INSERT INTO dt_countries VALUES (157, 'Panama');
INSERT INTO dt_countries VALUES (158, 'Papua New Guinea');
INSERT INTO dt_countries VALUES (159, 'Paraguay');
INSERT INTO dt_countries VALUES (160, 'Peru');
INSERT INTO dt_countries VALUES (161, 'Philippines');
INSERT INTO dt_countries VALUES (162, 'Poland');
INSERT INTO dt_countries VALUES (163, 'Portugal');
INSERT INTO dt_countries VALUES (164, 'Puerto Rico');
INSERT INTO dt_countries VALUES (165, 'Qatar');
INSERT INTO dt_countries VALUES (166, 'Romania');
INSERT INTO dt_countries VALUES (167, 'Russian Federation');
INSERT INTO dt_countries VALUES (168, 'Rwanda');
INSERT INTO dt_countries VALUES (169, 'Saint Helena');
INSERT INTO dt_countries VALUES (170, 'Saint Kitts-Nevis');
INSERT INTO dt_countries VALUES (171, 'Saint Lucia');
INSERT INTO dt_countries VALUES (172, 'Saint Pierre and Miquelon');
INSERT INTO dt_countries VALUES (173, 'Saint Vincent and the Grenadines');
INSERT INTO dt_countries VALUES (174, 'San Marino');
INSERT INTO dt_countries VALUES (175, 'Saudi Arabia');
INSERT INTO dt_countries VALUES (176, 'Senegal');
INSERT INTO dt_countries VALUES (177, 'Seychelles');
INSERT INTO dt_countries VALUES (178, 'Sierra Leone');
INSERT INTO dt_countries VALUES (179, 'Singapore');
INSERT INTO dt_countries VALUES (180, 'Slovakia');
INSERT INTO dt_countries VALUES (181, 'Slovenia');
INSERT INTO dt_countries VALUES (182, 'Solomon Islands');
INSERT INTO dt_countries VALUES (183, 'Somalia');
INSERT INTO dt_countries VALUES (184, 'South Africa');
INSERT INTO dt_countries VALUES (185, 'Spain');
INSERT INTO dt_countries VALUES (186, 'Sri Lanka');
INSERT INTO dt_countries VALUES (187, 'Sudan');
INSERT INTO dt_countries VALUES (188, 'Suriname');
INSERT INTO dt_countries VALUES (189, 'Svalbard');
INSERT INTO dt_countries VALUES (190, 'Swaziland');
INSERT INTO dt_countries VALUES (191, 'Sweden');
INSERT INTO dt_countries VALUES (192, 'Switzerland');
INSERT INTO dt_countries VALUES (193, 'Syria');
INSERT INTO dt_countries VALUES (194, 'Tahiti');
INSERT INTO dt_countries VALUES (195, 'Taiwan');
INSERT INTO dt_countries VALUES (196, 'Tajikistan');
INSERT INTO dt_countries VALUES (197, 'Tanzania');
INSERT INTO dt_countries VALUES (198, 'Thailand');
INSERT INTO dt_countries VALUES (199, 'Togo');
INSERT INTO dt_countries VALUES (200, 'Tonga');
INSERT INTO dt_countries VALUES (201, 'Trinidad and Tobago');
INSERT INTO dt_countries VALUES (202, 'Tunisia');
INSERT INTO dt_countries VALUES (203, 'Turkey');
INSERT INTO dt_countries VALUES (204, 'Turkmenistan');
INSERT INTO dt_countries VALUES (205, 'Turks and Caicos Islands');
INSERT INTO dt_countries VALUES (206, 'Tuvalu');
INSERT INTO dt_countries VALUES (207, 'Uganda');
INSERT INTO dt_countries VALUES (208, 'Ukraine');
INSERT INTO dt_countries VALUES (209, 'United Arab Emirates');
INSERT INTO dt_countries VALUES (212, 'Uruguay');
INSERT INTO dt_countries VALUES (213, 'Uzbekistan');
INSERT INTO dt_countries VALUES (214, 'Vanuatu');
INSERT INTO dt_countries VALUES (215, 'Vatican City State');
INSERT INTO dt_countries VALUES (216, 'Venezuela');
INSERT INTO dt_countries VALUES (217, 'Vietnam');
INSERT INTO dt_countries VALUES (218, 'Virgin Islands (U.S.)');
INSERT INTO dt_countries VALUES (219, 'Wallis and Futuna');
INSERT INTO dt_countries VALUES (220, 'Western Sahara');
INSERT INTO dt_countries VALUES (221, 'Western Samoa');
INSERT INTO dt_countries VALUES (222, 'Yemen');
INSERT INTO dt_countries VALUES (223, 'Yugoslavia');
INSERT INTO dt_countries VALUES (224, 'Zambia');
INSERT INTO dt_countries VALUES (225, 'Zimbabwe');
INSERT INTO dt_drinking VALUES (1, 'Non drinker');
INSERT INTO dt_drinking VALUES (2, 'Light/social drinker');
INSERT INTO dt_drinking VALUES (3, 'Regular drinker');
INSERT INTO dt_educations VALUES (1, 'High school not completed');
INSERT INTO dt_educations VALUES (2, 'High school');
INSERT INTO dt_educations VALUES (3, 'Technical training/Diploma');
INSERT INTO dt_educations VALUES (4, 'College');
INSERT INTO dt_educations VALUES (5, 'Bachelors Degree');
INSERT INTO dt_educations VALUES (6, 'Graduate/Masters Degree');
INSERT INTO dt_educations VALUES (7, 'Doctoral Degree/Ph.D');
INSERT INTO dt_eye_colors VALUES (1, 'Blue');
INSERT INTO dt_eye_colors VALUES (2, 'Grey');
INSERT INTO dt_eye_colors VALUES (3, 'Brown');
INSERT INTO dt_eye_colors VALUES (4, 'Black');
INSERT INTO dt_eye_colors VALUES (5, 'Green');
INSERT INTO dt_eye_colors VALUES (6, 'Hazel');
INSERT INTO dt_faq VALUES (1, 'Topic 1', 1);
INSERT INTO dt_faq_answers VALUES (1, 1, 'How do I sign up?', 'Simply click sign up!');
INSERT INTO dt_food VALUES (1, 'Vegetarian');
INSERT INTO dt_food VALUES (3, 'Non Vegetarian');
INSERT INTO dt_genders VALUES (1,'Male');
INSERT INTO dt_genders VALUES (2,'Female');
INSERT INTO dt_hair_colors VALUES (1, 'Other');
INSERT INTO dt_hair_colors VALUES (2, 'White / Grey');
INSERT INTO dt_hair_colors VALUES (3, 'Bald');
INSERT INTO dt_hair_colors VALUES (4, 'Black');
INSERT INTO dt_hair_colors VALUES (5, 'Brown');
INSERT INTO dt_hair_colors VALUES (6, 'Auburn');
INSERT INTO dt_hair_colors VALUES (7, 'Red');
INSERT INTO dt_hair_colors VALUES (8, 'Light brown');
INSERT INTO dt_hair_colors VALUES (9, 'Blonde');
INSERT INTO dt_heights VALUES (1, '5\'1\'\'or below (less than 155cm)');
INSERT INTO dt_heights VALUES (2, '5\'2\'\'-5\'6\'\' (157cm-169cm)');
INSERT INTO dt_heights VALUES (3, '5\'7\'\'-5\'11\'\' (170cm-180cm)');
INSERT INTO dt_heights VALUES (4, '6\'0\'\' (183cm) or above');
INSERT INTO dt_interests VALUES (1, 'Nature');
INSERT INTO dt_interests VALUES (2, 'Arts / Crafts');
INSERT INTO dt_interests VALUES (3, 'Astrology / New Age');
INSERT INTO dt_interests VALUES (4, 'Museums / Galleries');
INSERT INTO dt_interests VALUES (5, 'Cars / Motorcycles');
INSERT INTO dt_interests VALUES (6, 'Gardening');
INSERT INTO dt_interests VALUES (7, 'Billiards / Pool / Darts');
INSERT INTO dt_interests VALUES (8, 'Music - Country');
INSERT INTO dt_interests VALUES (9, 'Music - Alternative');
INSERT INTO dt_interests VALUES (10, 'Music - Christian / Gospel');
INSERT INTO dt_interests VALUES (11, 'Music - Classical / Opera');
INSERT INTO dt_interests VALUES (12, 'Music - Dance / Electronic');
INSERT INTO dt_interests VALUES (13, 'Nightclubs / Clubs');
INSERT INTO dt_interests VALUES (14, 'Music - Blues/Jazz');
INSERT INTO dt_interests VALUES (15, 'Music - Latin');
INSERT INTO dt_interests VALUES (16, 'Music - New Age');
INSERT INTO dt_interests VALUES (17, 'Music - Pop / R&B');
INSERT INTO dt_interests VALUES (18, 'Music - Rock');
INSERT INTO dt_interests VALUES (19, 'Music - World');
INSERT INTO dt_interests VALUES (20, 'Hiking / Camping');
INSERT INTO dt_interests VALUES (21, 'Politics');
INSERT INTO dt_interests VALUES (22, 'Literature / History');
INSERT INTO dt_interests VALUES (23, 'Religion');
INSERT INTO dt_interests VALUES (24, 'Baseball / Softball');
INSERT INTO dt_interests VALUES (25, 'Boxing / Wrestling');
INSERT INTO dt_interests VALUES (26, ' Cycling');
INSERT INTO dt_interests VALUES (27, 'Cricket');
INSERT INTO dt_interests VALUES (28, 'Football / Soccer / Rugby');
INSERT INTO dt_interests VALUES (29, 'Golf');
INSERT INTO dt_interests VALUES (30, 'Gym / Aerobics');
INSERT INTO dt_interests VALUES (31, 'Shopping');
INSERT INTO dt_interests VALUES (32, 'Martial Arts');
INSERT INTO dt_interests VALUES (33, 'Singing / Playing Instrument');
INSERT INTO dt_interests VALUES (34, 'Motor Racing');
INSERT INTO dt_interests VALUES (35, 'Athletics');
INSERT INTO dt_interests VALUES (36, 'Mountaineering');
INSERT INTO dt_interests VALUES (37, 'Dancing');
INSERT INTO dt_interests VALUES (38, 'Sailing / Boating');
INSERT INTO dt_interests VALUES (39, 'Ice / Snow Sports');
INSERT INTO dt_interests VALUES (40, 'Tennis / Racket Sports');
INSERT INTO dt_interests VALUES (41, 'Volleyball / Basketball');
INSERT INTO dt_interests VALUES (42, 'Computers / Internet');
INSERT INTO dt_interests VALUES (43, 'Water Sports');
INSERT INTO dt_interests VALUES (44, 'Theatre / Ballet');
INSERT INTO dt_interests VALUES (45, 'Travel / Sightseeing');
INSERT INTO dt_interests VALUES (46, 'Volunteer / Charity');
INSERT INTO dt_interests VALUES (47, 'Movies / Cinema');
INSERT INTO dt_interests VALUES (48, 'Yoga / Meditation');
INSERT INTO dt_interests VALUES (49, 'Cooking');
INSERT INTO dt_interests VALUES (50, 'Food and Wine');
INSERT INTO dt_lang_rates VALUES (1, 'Fluent');
INSERT INTO dt_lang_rates VALUES (2, 'Some');
INSERT INTO dt_lang_rates VALUES (3, 'Minimal');
INSERT INTO dt_lang_rates VALUES (4,'Spoken only');
INSERT INTO dt_lang_rates VALUES (5,'Spoken and Written');
INSERT INTO dt_languages VALUES (1, 'Afrikaans');
INSERT INTO dt_languages VALUES (2, 'Arabic');
INSERT INTO dt_languages VALUES (3, 'Belgian');
INSERT INTO dt_languages VALUES (4, 'Bulgarian');
INSERT INTO dt_languages VALUES (5, 'Burmese');
INSERT INTO dt_languages VALUES (6, 'Cantonese');
INSERT INTO dt_languages VALUES (7, 'Croatian');
INSERT INTO dt_languages VALUES (8, 'Danish');
INSERT INTO dt_languages VALUES (9, 'Dutch');
INSERT INTO dt_languages VALUES (10, 'English');
INSERT INTO dt_languages VALUES (11, 'Esperanto');
INSERT INTO dt_languages VALUES (12, 'Estonian');
INSERT INTO dt_languages VALUES (13, 'Finnish');
INSERT INTO dt_languages VALUES (14, 'French');
INSERT INTO dt_languages VALUES (15, 'German');
INSERT INTO dt_languages VALUES (16, 'Greek');
INSERT INTO dt_languages VALUES (17, 'Gujrati');
INSERT INTO dt_languages VALUES (18, 'Hebrew');
INSERT INTO dt_languages VALUES (19, 'Hindi');
INSERT INTO dt_languages VALUES (20, 'Hungarian');
INSERT INTO dt_languages VALUES (21, 'Icelandic');
INSERT INTO dt_languages VALUES (22, 'Indian');
INSERT INTO dt_languages VALUES (23, 'Indonesian');
INSERT INTO dt_languages VALUES (24, 'Italian');
INSERT INTO dt_languages VALUES (25, 'Japanese');
INSERT INTO dt_languages VALUES (26, 'Korean');
INSERT INTO dt_languages VALUES (27, 'Latvian');
INSERT INTO dt_languages VALUES (28, 'Lithuanian');
INSERT INTO dt_languages VALUES (29, 'Malay');
INSERT INTO dt_languages VALUES (30, 'Mandarin');
INSERT INTO dt_languages VALUES (31, 'Marathi');
INSERT INTO dt_languages VALUES (32, 'Moldovian');
INSERT INTO dt_languages VALUES (33, 'Nepalese');
INSERT INTO dt_languages VALUES (34, 'Norwegian');
INSERT INTO dt_languages VALUES (35, 'Persian');
INSERT INTO dt_languages VALUES (36, 'Polish');
INSERT INTO dt_languages VALUES (37, 'Portuguese');
INSERT INTO dt_languages VALUES (38, 'Punjabi');
INSERT INTO dt_languages VALUES (39, 'Romanian');
INSERT INTO dt_languages VALUES (40, 'Russian');
INSERT INTO dt_languages VALUES (41, 'Serbian');
INSERT INTO dt_languages VALUES (42, 'Spanish');
INSERT INTO dt_languages VALUES (43, 'Swedish');
INSERT INTO dt_languages VALUES (44, 'Tagalog');
INSERT INTO dt_languages VALUES (45, 'Taiwanese');
INSERT INTO dt_languages VALUES (46, 'Tamil');
INSERT INTO dt_languages VALUES (47, 'Telugu');
INSERT INTO dt_languages VALUES (48, 'Thai');
INSERT INTO dt_languages VALUES (49, 'Tongan');
INSERT INTO dt_languages VALUES (50, 'Turkish');
INSERT INTO dt_languages VALUES (51, 'Ukrainian');
INSERT INTO dt_languages VALUES (52, 'Urdu');
INSERT INTO dt_languages VALUES (53, 'Vietnamese');
INSERT INTO dt_languages VALUES (54, 'Visayan');
INSERT INTO dt_marital_status VALUES (1, 'Attached');
INSERT INTO dt_marital_status VALUES (2, 'Divorced');
INSERT INTO dt_marital_status VALUES (3, 'Married');
INSERT INTO dt_marital_status VALUES (4, 'Single');
INSERT INTO dt_marital_status VALUES (5, 'Separated');
INSERT INTO dt_marital_status VALUES (6, 'Widow/Widower');
INSERT INTO dt_occupations VALUES (1, 'Accounting');
INSERT INTO dt_occupations VALUES (2, 'Admin/Customer Service');
INSERT INTO dt_occupations VALUES (3, 'Advert/Media/Entertain');
INSERT INTO dt_occupations VALUES (4, 'Construction');
INSERT INTO dt_occupations VALUES (5, 'Education & Science');
INSERT INTO dt_occupations VALUES (6, 'Engineering');
INSERT INTO dt_occupations VALUES (7, 'Financial Services');
INSERT INTO dt_occupations VALUES (8, 'Government');
INSERT INTO dt_occupations VALUES (9, 'Healthcare & Medical');
INSERT INTO dt_occupations VALUES (10, 'Hospitality & Tourism');
INSERT INTO dt_occupations VALUES (11, 'I.T/Communications');
INSERT INTO dt_occupations VALUES (12, 'Legal');
INSERT INTO dt_occupations VALUES (13, 'Management/HR');
INSERT INTO dt_occupations VALUES (14, 'Retail/Consumer Prdts');
INSERT INTO dt_occupations VALUES (15, 'Sales & Marketing');
INSERT INTO dt_occupations VALUES (16, 'Trades & Services');
INSERT INTO dt_occupations VALUES (17, 'Homemaker');
INSERT INTO dt_occupations VALUES (18, 'Retired');
INSERT INTO dt_occupations VALUES (19, 'Self-employed');
INSERT INTO dt_occupations VALUES (20, 'Student');
INSERT INTO dt_occupations VALUES (21, 'Farming');
INSERT INTO dt_occupations VALUES (22, 'Other');
INSERT INTO dt_races VALUES (1, 'African');
INSERT INTO dt_races VALUES (2, 'African American');
INSERT INTO dt_races VALUES (3, 'Asian');
INSERT INTO dt_races VALUES (4, 'Caucasian');
INSERT INTO dt_races VALUES (5, 'East Indian');
INSERT INTO dt_races VALUES (6, 'Hispanic');
INSERT INTO dt_races VALUES (7, 'Indian');
INSERT INTO dt_races VALUES (8, 'Latino');
INSERT INTO dt_races VALUES (9, 'Mediterranean');
INSERT INTO dt_races VALUES (10, 'Middle Eastern');
INSERT INTO dt_races VALUES (11, 'Mixed Race');
INSERT INTO dt_reasons VALUES (1, 'Wrong information', 'Information you have entered is invalid!');
INSERT INTO dt_reasons VALUES (2, 'Sex-oriented information', 'You entered sex-oriented information!');
INSERT INTO dt_relationship VALUES (1, 'Activity Partner');
INSERT INTO dt_relationship VALUES (2, 'Friendship');
INSERT INTO dt_relationship VALUES (3, 'Marriage');
INSERT INTO dt_relationship VALUES (4, 'Relationship');
INSERT INTO dt_relationship VALUES (5, 'Romance');
INSERT INTO dt_relationship VALUES (6, 'Casual');
INSERT INTO dt_relationship VALUES (7, 'Travel Partner');
INSERT INTO dt_religions VALUES (1,'Adventist');
INSERT INTO dt_religions VALUES (2,'Agnostic');
INSERT INTO dt_religions VALUES (3,'Catholic');
INSERT INTO dt_religions VALUES (5,'Other');
INSERT INTO dt_religions VALUES (6,'Mormon');
INSERT INTO dt_religions VALUES (7,'Protestant');
INSERT INTO dt_religions VALUES (8,'Methodist');
INSERT INTO dt_religions VALUES (9,'Hindu');
INSERT INTO dt_religions VALUES (10,'Buddist');
INSERT INTO dt_religions VALUES (12,'Islam - Shia');
INSERT INTO dt_religions VALUES (13,'Jewish');
INSERT INTO dt_smoking VALUES (1, 'Non smoker');
INSERT INTO dt_smoking VALUES (2, 'Light/social smoker');
INSERT INTO dt_smoking VALUES (3, 'Regular smoker');
INSERT INTO dt_smoking VALUES (4, 'Smoke Cigars/Pipes');
INSERT INTO dt_smoking VALUES (5,'No Smoking at all');
INSERT INTO dt_smoking VALUES (6,'I want to quit');
INSERT INTO dt_states VALUES (1,NULL,'Alabama');
INSERT INTO dt_states VALUES (2,NULL,'Alaska');
INSERT INTO dt_states VALUES (3,NULL,'Arizona');
INSERT INTO dt_states VALUES (4,NULL,'Arkansas');
INSERT INTO dt_states VALUES (5,NULL,'California');
INSERT INTO dt_states VALUES (6,NULL,'Colorado');
INSERT INTO dt_states VALUES (7,NULL,'Connecticut');
INSERT INTO dt_states VALUES (8,NULL,'Delaware');
INSERT INTO dt_states VALUES (9,NULL,'Florida');
INSERT INTO dt_states VALUES (10,NULL,'Georgia');
INSERT INTO dt_states VALUES (11,NULL,'Hawaii');
INSERT INTO dt_states VALUES (12,NULL,'Idaho');
INSERT INTO dt_states VALUES (13,NULL,'Illinois');
INSERT INTO dt_states VALUES (14,NULL,'Indiana');
INSERT INTO dt_states VALUES (15,NULL,'Iowa');
INSERT INTO dt_states VALUES (16,NULL,'Kansas');
INSERT INTO dt_states VALUES (17,NULL,'Kentucky');
INSERT INTO dt_states VALUES (18,NULL,'Louisiana');
INSERT INTO dt_states VALUES (19,NULL,'Maine');
INSERT INTO dt_states VALUES (20,NULL,'Maryland');
INSERT INTO dt_states VALUES (21,NULL,'Massachusetts');
INSERT INTO dt_states VALUES (22,NULL,'Michigan');
INSERT INTO dt_states VALUES (23,NULL,'Minnesota');
INSERT INTO dt_states VALUES (24,NULL,'Mississippi');
INSERT INTO dt_states VALUES (25,NULL,'Missouri');
INSERT INTO dt_states VALUES (26,NULL,'Montana');
INSERT INTO dt_states VALUES (27,NULL,'Nebraska');
INSERT INTO dt_states VALUES (28,NULL,'Nevada');
INSERT INTO dt_states VALUES (29,NULL,'New Hampshire');
INSERT INTO dt_states VALUES (30,NULL,'New Jersey');
INSERT INTO dt_states VALUES (31,NULL,'New Mexico');
INSERT INTO dt_states VALUES (32,NULL,'New York');
INSERT INTO dt_states VALUES (33,NULL,'North Carolina');
INSERT INTO dt_states VALUES (34,NULL,'North Dakota');
INSERT INTO dt_states VALUES (35,NULL,'Ohio');
INSERT INTO dt_states VALUES (36,NULL,'Oklahoma');
INSERT INTO dt_states VALUES (37,NULL,'Oregon');
INSERT INTO dt_states VALUES (38,NULL,'Pennsylvania');
INSERT INTO dt_states VALUES (39,NULL,'Rhode Island');
INSERT INTO dt_states VALUES (40,NULL,'South Carolina');
INSERT INTO dt_states VALUES (41,NULL,'South Dakota');
INSERT INTO dt_states VALUES (42,NULL,'Tennessee');
INSERT INTO dt_states VALUES (43,NULL,'Texas');
INSERT INTO dt_states VALUES (44,NULL,'Utah');
INSERT INTO dt_states VALUES (45,NULL,'Vermont');
INSERT INTO dt_states VALUES (46,NULL,'Virginia');
INSERT INTO dt_states VALUES (47,NULL,'Washington');
INSERT INTO dt_states VALUES (48,NULL,'West Virginia');
INSERT INTO dt_states VALUES (49,NULL,'Wisconsin');
INSERT INTO dt_states VALUES (50,NULL,'Wyoming');
INSERT INTO dt_tips VALUES (1, 'Getting Married..... Click here!', 1, '');
INSERT INTO dt_tips VALUES (2, 'I was just having a browse.... Click here!', 1, '');
INSERT INTO dt_tips VALUES (3, 'Don\'t give up hope... More stories!', 1, '');
INSERT INTO dt_tips VALUES (4, 'Read how people have succeeded in meeting their match online!', 1, '');
INSERT INTO dt_tips VALUES (5, 'These tips can be completely customized in the admin area!', 1, '');
INSERT INTO webDate_bd_services VALUES (1,'Admin Users Management',1,'users');
INSERT INTO webDate_bd_services VALUES (2,'Admin Users Access',1,'users_access');
INSERT INTO webDate_bd_services VALUES (3,'Admin Users Events',1,'users_log');
INSERT INTO webDate_bd_services VALUES (35,'Censored words list','','badwords');
INSERT INTO webDate_bd_services VALUES (5,'Body types','','body_types');
INSERT INTO webDate_bd_services VALUES (6,'Countries list','','countries');
INSERT INTO webDate_bd_services VALUES (7,'Drinking','','drinking');
INSERT INTO webDate_bd_services VALUES (8,'Educations','','educations');
INSERT INTO webDate_bd_services VALUES (9,'Eye colors','','eye_colors');
INSERT INTO webDate_bd_services VALUES (10,'Food','','food');
INSERT INTO webDate_bd_services VALUES (11,'Hair colors','','hair_colors');
INSERT INTO webDate_bd_services VALUES (12,'Heights','','heights');
INSERT INTO webDate_bd_services VALUES (13,'Language\'s rates','','lang_rates');
INSERT INTO webDate_bd_services VALUES (14,'Languages list','','languages');
INSERT INTO webDate_bd_services VALUES (15,'Marital status List','','marital_status');
INSERT INTO webDate_bd_services VALUES (16,'Occupations List','','occupations');
INSERT INTO webDate_bd_services VALUES (17,'Races list','','races');
INSERT INTO webDate_bd_services VALUES (18,'Religions list','','religions');
INSERT INTO webDate_bd_services VALUES (19,'Smoking','','smoking');
INSERT INTO webDate_bd_services VALUES (20,'Relationship','','relationship');
INSERT INTO webDate_bd_services VALUES (21,'Interests','','interests');
INSERT INTO webDate_bd_services VALUES (22,'Decline reasons','','reasons');
INSERT INTO webDate_bd_services VALUES (23,'New profiles','','new_profiles');
INSERT INTO webDate_bd_services VALUES (24,'Billing information','','charge');
INSERT INTO webDate_bd_services VALUES (30,'Profile Management','','all_profiles.php');
INSERT INTO webDate_bd_services VALUES (26,'FAQ Topics','','faq_topics');
INSERT INTO webDate_bd_services VALUES (27,'FAQ Answers','','faq_answers');
INSERT INTO webDate_bd_services VALUES (31,'All Members','','members');
INSERT INTO webDate_bd_services VALUES (32,'Newsletter','','newsletter.php');
INSERT INTO webDate_bd_services VALUES (33,'Genders list','','genders');
