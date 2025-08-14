table member_admin
# phpMyAdmin MySQL-Dump
# Host: localhost Database : member
# --------------------------------------------------------

#
# Table structure for table 'member_admin'
#

CREATE TABLE member_admin (
   user_id varchar(10) NOT NULL,
   user_password varchar(32) NOT NULL,
   PRIMARY KEY (user_id)
);

#
# Dumping data for table 'member_admin'
#

INSERT INTO member_admin VALUES ( 'admin', PASSWORD('admin'));


#
# Table structure for table 'member_user'
#

CREATE TABLE member_user (
   id int(10) NOT NULL auto_increment,
   user_id varchar(10) NOT NULL,
   user_password varchar(32) NOT NULL,
   email varchar(35) NOT NULL,
   fullname varchar(50) NOT NULL,
   date date DEFAULT '0000-00-00' NOT NULL,
   comment varchar(100) DEFAULT '0' NOT NULL,
   PRIMARY KEY (id)
);


#
# Dumping data for table 'member_user'
#

INSERT INTO member_user VALUES ( '1', 'user', PASSWORD('user'), 'webmaster@belgradecafe.com', 'Mihailo Jevtic', '2005-05-25', 'Good luck');

