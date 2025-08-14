# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: localhost
# Generation Time: Mar 25, 2002 at 10:37 AM
# Server version: 3.23.49
# PHP Version: 4.1.2
# Database : `goblin_demo`
# --------------------------------------------------------

#
# Table structure for table `whatdafaq`
#

CREATE TABLE whatdafaq (
  id int(11) NOT NULL auto_increment,
  question varchar(255) default NULL,
  answer text,
  category int(11) default NULL,
  section enum('yes','no') NOT NULL default 'no',
  publish enum('yes','no') NOT NULL default 'no',
  ordering int(11) default NULL,
  date date default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='FAQs stored here';

#
# Dumping data for table `whatdafaq`
#

INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (1, 'Introducing WhatDaFaq', '', 1, 'yes', 'yes', 7, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (2, 'What is WhatDaFaq ?!?', '<p>WhatDaFaq is a FAQ creation tool. It allows you to build up a list of FAQs for your site visitors, using this simple admin tool.', 1, 'no', 'yes', 8, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (3, 'What is WhatDaFaq written in?', 'WhatDaFaq is written in <a href="http://www.php.net" target="_blank">PHP</a> (a free, Open Source server side scripting language), talking to <a href="http://www.mysql.com" target="_blank">MySQL</a>', 1, 'no', 'yes', 9, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (4, 'Where can I get it?', '<p>Right here! Pick your download:\r\n<p><a href=http://www.pinkgoblin.com/scripts/whatdafaq.zip>Zip</a> : <a href=http://www.pinkgoblin.com/scripts/whatdafaq.tar.gz>GZip</a> : <a href=http://www.pinkgoblin.com/scripts/whatdafaq.tar.bz2>BZip</a>', 1, 'no', 'yes', 10, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (5, 'Is there a demo?', '<p>You\'re looking at it. You can also try the administration tool <a href=whatdaadmin.php>WhatDaAdmin</a>\r\n<p>One thing, as a test of your ability... try to avoid posting...\r\n<h2 align=center>testing!</h2>\r\n<p>or\r\n<h2 align=center>asdf!</h2>\r\n<p>We\'re sure you have a brain. Why not use it to speed the flow of knowledge around the world?\r\n<p><b>Note:</b> in the demo version, you can\'t edit this category, so these FAQs you\'re reading stay put.', 1, 'no', 'yes', 11, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (6, 'Installing WhatDaFaq', '', 1, 'yes', 'yes', 12, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (7, 'Creating FAQs', '', 1, 'yes', 'yes', 16, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (8, 'What directory should I install it under?', '<p>The zips will expand into the subdirectory ./whatdafaq\r\n<p>It\'s recommended you seperate the index.php file, which your visitors will see, from the admin file.\r\n<p>In general, we recommend you replace the files included in index.php and whatdaadmin.php with those for your own site.\r\n<p>Of course to get it up and running for your own demo, the whatdafaq directory is fine.', 1, 'no', 'yes', 13, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (9, 'You include a few files. What do they do?', '<p>For layout purposes, we include header.php and footer.php at the top and bottom respectively. These files you should replace with your own.\r\n<p>dbconnect.php provides the connection to MySQL.\r\n<p>auth.php envokes the session that controls access to whatdaadmin.php', 1, 'no', 'yes', 14, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (10, 'How do I configure the demo version on my machine?', '<p>Once you\'ve unzipped the files somewhere under your web directory, update whatever MySQL database you wish to use with whatdafaq.sql\r\n<p>Now update dbconnect.php with your MySQL username / password etc.', 1, 'no', 'yes', 15, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (11, 'What are headings?', '<p>Headings are visual grouping off FAQs. They appear on the left hand side by default, in index.php.\r\n<p>When you add / edit an item in the FAQ list, use the switch "Section Heading" to define a Heading.', 1, 'no', 'yes', 17, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (12, 'What is Position?', '<p>Position is the order, from the top, that an item appears within a FAQ Category.\r\n<p>When you add / edit you can set the position of the item relative to the other items you find.', 1, 'no', 'yes', 18, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (13, 'I added an item - why doesn\'t appear to visitors?', '<p>Make sure you set "Publish" to "Yes".\r\n<p>Publish allows you to add a series of FAQs but keep them invisible until you are ready to display them.', 1, 'no', 'yes', 19, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (20, 'How do I move an item from one category to another?', '<p>Once you\'ve added the item, you can move the category it\'s in by editing it.\r\n<p>In general, the "FAQ Category:" select box at the top is used to set which category you want to work on.', 1, 'no', 'yes', 20, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (21, 'Where can I edit the current categories?', '<p>There\'s no admin interface for editing categories. This is for a good reason.\r\n<p>Every new item gets assigned to a category. If you then delete the category, you\'re asking for trouble.\r\n<p>At the moment, you have to manually update the "whatdafaqcat" table to edit / create categories. We suggest you plan out from the start what categories you\'re going to have.', 1, 'no', 'yes', 21, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (22, 'Chilling Out', '', 2, 'yes', 'yes', 5, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (23, 'What\'s a good way to chill out online?', '<p>Try this <a href=http://www.beliefnet.com/story/3/story_385_1.html>Guided Meditation</a>. Will space you out nicely, in the comfort of your busy office.', 2, 'no', 'yes', 6, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (24, 'Roadkill Trivia', '', 3, 'yes', 'yes', 2, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (25, 'The first frisbee', '<p>It\'s a little know fact that the origional frisbee was a cat.\r\n<p>At the end of the sixties, Frisbee took his last stroll on the freeway, spending the last of his nine lives under the wheel of a truck.\r\n<p>It was a long hot summer and Frisbee\'s owners search everywhere. For weeks they couldn\'t find him.\r\n<p>Until one day...\r\n<p>The rest we leave to the imagination', 3, 'no', 'yes', 2, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (26, 'Roadkill Recipes', '', 3, 'yes', 'yes', 3, NULL);
INSERT INTO whatdafaq (id, question, answer, category, section, publish, ordering, date) VALUES (27, 'Truck Driver Surprise', '<p><b>Broiled Venison Tenderloin</b>\r\n<p>Ingredients: <br>\r\n(1) venison tenderloin (or backstrap) <br>\r\n(1) onion sliced <br>\r\ngarlic to taste <br>\r\npepper to taste <br>\r\nsalt to taste <br>\r\n(2) slices bacon\r\n<p>Directions: <br>\r\nSoak the venison in salt water to remove all blood (approximately 20 minutes). Rinse well and place on broiler rack. Add garlic, salt and pepper. Wrap the bacon slices (widthwise) around the tenderloin. Slice the onion and place the slices on top of the bacon. Broil for 20 minutes. Cover the tenderloin with foil and broil for 5-10 minutes.\r\n<p>Slice and serve with your favorite vegetables.', 3, 'no', 'yes', 4, NULL);
# --------------------------------------------------------

#
# Table structure for table `whatdafaqcat`
#

CREATE TABLE whatdafaqcat (
  id int(11) NOT NULL auto_increment,
  category varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Categories of FAQs';

#
# Dumping data for table `whatdafaqcat`
#

INSERT INTO whatdafaqcat (id, category) VALUES (1, 'WhatDaFaq FAQs');
INSERT INTO whatdafaqcat (id, category) VALUES (2, 'Life, The Universe and Everything');
INSERT INTO whatdafaqcat (id, category) VALUES (3, 'About Roadkill');
