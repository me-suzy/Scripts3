# phpMyAdmin SQL Dump
# version 2.5.7-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jul 22, 2004 at 07:56 PM
# Server version: 4.0.17
# PHP Version: 4.3.4
# 
# Database : `test`
# 

# --------------------------------------------------------

#
# Table structure for table `bcb_spider`
#

CREATE TABLE `bcb_spider` (
  `url` varchar(255) NOT NULL default '',
  `agent` varchar(255) NOT NULL default '',
  `timestamp` timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table `bcb_spider`
#

INSERT INTO `bcb_spider` VALUES ('http://bluecollarbrain.com/index.php', 'Mozilla/4.0 compatible ZyBorg/1.0 (wn-2.zyborg@looksmart.net; http://www.WISEnutbot.com)', 20040714053223);
INSERT INTO `bcb_spider` VALUES ('http://bluecollarbrain.com/index.php?page=l', 'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', 20040715090427);
INSERT INTO `bcb_spider` VALUES ('http://bluecollarbrain.com/index.php?page=p3p', 'msnbot/0.11 (+http://search.msn.com/msnbot.htm)', 20040715101327);
INSERT INTO `bcb_spider` VALUES ('http://bluecollarbrain.com/index.php?content=home', 'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', 20040715122746);
INSERT INTO `bcb_spider` VALUES ('http://bluecollarbrain.com/index.php', 'Googlebot/2.1 (+http://www.google.com/bot.html)', 20040716072127);
