#
# Table structure for table `jobnum`
#

CREATE TABLE jobnum (
  recordid int(11) NOT NULL auto_increment,
  username text NOT NULL,
  password text NOT NULL,
  PRIMARY KEY  (recordid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `jobs`
#

CREATE TABLE jobs (
  recordid int(11) NOT NULL auto_increment,
  jobid text NOT NULL,
  title text NOT NULL,
  company text NOT NULL,
  location text NOT NULL,
  description text NOT NULL,
  contact text NOT NULL,
  email text NOT NULL,
  url text NOT NULL,
  publishdate text NOT NULL,
  PRIMARY KEY  (recordid)
) TYPE=MyISAM;

