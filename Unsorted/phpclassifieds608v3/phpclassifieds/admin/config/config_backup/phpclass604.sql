# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# Host: localhost Database : php

# --------------------------------------------------------
#
# Table structure for table 'ad'
#

CREATE TABLE ad (
   siteid int(11) NOT NULL auto_increment,
   sitetitle varchar(100),
   sitedescription text,
   siteurl varchar(100),
   sitedate varchar(10),
   expiredate varchar(12),
   sitecatid int(11),
   sitehits int(11),
   sitevotes double(16,4),
   sites_userid int(11),
   sites_pass varchar(12),
   custom_field_1 varchar(50),
   custom_field_2 varchar(50),
   custom_field_3 varchar(50),
   custom_field_4 varchar(50),
   custom_field_5 varchar(50),
   custom_field_6 varchar(50),
   custom_field_7 varchar(50),
   custom_field_8 varchar(50),
   picture int(11) DEFAULT '0',
   img_stored varchar(70),
   datestamp timestamp(8),
   f1 varchar(50),
   f2 varchar(50),
   f3 varchar(50),
   f4 varchar(50),
   f5 varchar(50),
   f6 varchar(50),
   f7 varchar(50),
   f8 varchar(50),
   f9 varchar(50),
   f10 varchar(50),
   f11 varchar(50),
   f12 varchar(50),
   f13 varchar(50),
   f14 varchar(50),
   f15 varchar(50),
   ad_username varchar(50),
   valid tinyint(4) DEFAULT '0',
   PRIMARY KEY (siteid)
);


# --------------------------------------------------------
#
# Table structure for table 'category'
#

CREATE TABLE category (
   catid int(11) NOT NULL auto_increment,
   catfatherid int(11),
   catname varchar(100),
   catdescription varchar(150),
   catimage varchar(100) DEFAULT 'default.gif',
   total varchar(5) DEFAULT '0',
   latest_date varchar(8),
   cattpl varchar(50),
   allowads char(2),
   catfullname varchar(150),
   PRIMARY KEY (catid)
);


# --------------------------------------------------------
#
# Table structure for table 'picture'
#

CREATE TABLE picture (
   id int(4) NOT NULL auto_increment,
   pictures_siteid varchar(6),
   bin_data longblob,
   filename varchar(50),
   filesize varchar(50),
   filetype varchar(50),
   PRIMARY KEY (id)
);


# --------------------------------------------------------
#
# Table structure for table 'template'
#

CREATE TABLE template (
   tplid int(11) NOT NULL auto_increment,
   name varchar(50),
   f1_caption varchar(50),
   f1_type varchar(50),
   f1_mandatory char(3),
   f1_length varchar(5),
   f1_filename varchar(50),
   f2_caption varchar(50),
   f2_type varchar(50),
   f2_mandatory char(3),
   f2_length varchar(5),
   f2_filename varchar(50),
   f3_caption varchar(50),
   f3_type varchar(50),
   f3_mandatory char(3),
   f3_length varchar(5),
   f3_filename varchar(50),
   f4_caption varchar(50),
   f4_type varchar(50),
   f4_mandatory char(3),
   f4_length varchar(5),
   f4_filename varchar(50),
   f5_caption varchar(50),
   f5_type varchar(50),
   f5_mandatory char(3),
   f5_length varchar(5),
   f5_filename varchar(50),
   f6_caption varchar(50),
   f6_type varchar(50),
   f6_mandatory char(3),
   f6_length varchar(5),
   f6_filename varchar(50),
   f7_caption varchar(50),
   f7_type varchar(50),
   f7_mandatory char(3),
   f7_length varchar(5),
   f7_filename varchar(50),
   f8_caption varchar(50),
   f8_type varchar(50),
   f8_mandatory char(3),
   f8_length varchar(5),
   f8_filename varchar(50),
   f9_caption varchar(50),
   f9_type varchar(50),
   f9_mandatory char(3),
   f9_length varchar(5),
   f9_filename varchar(50),
   f10_caption varchar(50),
   f10_type varchar(50),
   f10_mandatory char(3),
   f10_length varchar(5),
   f10_filename varchar(50),
   f11_caption varchar(50),
   f11_type varchar(50),
   f11_mandatory char(3),
   f11_length varchar(5),
   f11_filename varchar(50),
   f12_caption varchar(50),
   f12_type varchar(50),
   f12_mandatory char(3),
   f12_length varchar(5),
   f12_filename varchar(50),
   f13_caption varchar(50),
   f13_type varchar(50),
   f13_mandatory char(3),
   f13_length varchar(5),
   f13_filename varchar(50),
   f14_caption varchar(50),
   f14_type varchar(50),
   f14_mandatory char(3),
   f14_length varchar(5),
   f14_filename varchar(50),
   f15_caption varchar(50),
   f15_type varchar(50),
   f15_mandatory char(3),
   f15_length varchar(5),
   f15_filename varchar(50),
   PRIMARY KEY (tplid)
);


# --------------------------------------------------------
#
# Table structure for table 'user'
#

CREATE TABLE user (
   userid int(11) DEFAULT '0' NOT NULL,
   name varchar(100),
   adressfield1 varchar(100),
   adressfield2 varchar(100),
   adressfield3 varchar(100),
   phone varchar(30),
   email varchar(50) NOT NULL,
   pass varchar(12),
   registered timestamp(8),
   emelding tinyint(4),
   num_ads int(11) DEFAULT '0',
   country varchar(50),
   hide_email tinyint(4),
   custom_1 varchar(50),
   usr_1 varchar(150),
   usr_2 varchar(150),
   usr_3 varchar(150),
   usr_4 varchar(150),
   usr_5 varchar(150),
   password_enc varchar(30),
   PRIMARY KEY (email)
);

