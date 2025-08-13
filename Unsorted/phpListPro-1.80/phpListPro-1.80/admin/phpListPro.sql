# --------------------------------------------------------
#
# Table structure for table 'sites'
#

CREATE TABLE sites (
   id int(15) DEFAULT '0' NOT NULL,
   votes int(10) DEFAULT '0' NOT NULL,
   hits int(10) DEFAULT '0' NOT NULL,
   site_name varchar(100) NOT NULL,
   site_addr varchar(100) NOT NULL,
   site_desc text NOT NULL,
   email varchar(50) NOT NULL,
   name varchar(50) NOT NULL,
   password varchar(50) NOT NULL,
   banner_addr varchar(100) NOT NULL,
   banner_width int(3) DEFAULT '0' NOT NULL,
   banner_height int(3) DEFAULT '0' NOT NULL,
   rating double(16,4) DEFAULT '0.0000' NOT NULL,
   approved int(1) NOT NULL,
   emailapproved int(1) NOT NULL,
   tilt_time int(14) DEFAULT '0' NOT NULL,
   cat int(6) DEFAULT '0' NOT NULL,
   inactive int(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (id)
);

#
# Dumping data for table 'sites'
#

INSERT INTO sites VALUES ( '985827230', '0', '3', 'SmartISoft', 'http://www.smartisoft.com', 'Get best PHP Script\\\'s on the NET :-)))', 'ef@smartisoft.com', 'Erich Fuchs', '12345678', 'http://www.smartisoft.com/images/no_banner11.gif', '468', '60', '5.0000', '', '', '','0','0');

# --------------------------------------------------------
#
# Table structure for table 'useronline'
#

CREATE TABLE useronline (
   timestamp int(14) DEFAULT '0' NOT NULL,
   ip varchar(40) NOT NULL,
   PRIMARY KEY (timestamp)
);


# --------------------------------------------------------
#
# Table structure for table 'variables'
#

CREATE TABLE variables (
   counter int(14) DEFAULT '0' NOT NULL,
   resettimestamp int(14) DEFAULT '0' NOT NULL
);

#
# Dumping data for table 'variables'
#

INSERT INTO variables VALUES ( '0', '988377304');

# --------------------------------------------------------
#
# Table structure for table 'votes'
#

CREATE TABLE votes (
   id int(14) DEFAULT '0' NOT NULL,
   ip varchar(40) NOT NULL,
   timestamp int(14) DEFAULT '0' NOT NULL,
   refer varchar(100) NOT NULL,
   status varchar(5) NOT NULL
);
