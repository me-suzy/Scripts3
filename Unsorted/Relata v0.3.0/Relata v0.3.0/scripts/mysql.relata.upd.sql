# Modification Script for 'activity' and 'contact' tables
# Avinesh Bangar
# Updated: March 6, 2002
#
# No data loss will occur (make sure all users are not using Relata, 
# but back up your Relata database: mysqldump relata > relata.sql
# This is mostly for Relata 0.2.4 and Relata 0.2.5 users upgrading to Relata 0.3.0
#
#============================
#Activity Table modifications
#============================

ALTER TABLE activity CHANGE is_calendar_item is_calendar_item CHAR (1) DEFAULT 'N' NOT NULL,
ALTER TABLE activity CHANGE ismodified ismodified VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE activity CHANGE isarchived isarchived VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE activity CHANGE isnew isnew VARCHAR (5) DEFAULT 'true' NOT NULL,
ALTER TABLE activity CHANGE isdeleted isdeleted VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE activity CHANGE isprivate isprivate VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE activity ADD note text NOT NULL, 
ALTER TABLE activity ADD catindex INT (5) DEFAULT '0' NOT NULL, 
ALTER TABLE activity ADD iscompleted VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE activity ADD isuntimed VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE activity ADD isalarmed VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE activity ADD alarmadvtime INT (2) DEFAULT '0' NOT NULL, 
ALTER TABLE activity ADD alarmadvunit INT (2) DEFAULT '0' NOT NULL, 
ALTER TABLE activity ADD isrepeating VARCHAR (5) DEFAULT 'false' NOT NULL, 
ALTER TABLE activity ADD repeattype INT (2) DEFAULT '0' NOT NULL, 
ALTER TABLE activity ADD repeatenddate DATE DEFAULT '0000-00-00' NOT NULL, 
ALTER TABLE activity ADD repeatfrequency INT (2) DEFAULT '1' NOT NULL, 
ALTER TABLE activity ADD repeaton VARCHAR (90) DEFAULT '0' NOT NULL, 
ALTER TABLE activity ADD repeatstartweek INT (2) DEFAULT '0' NOT NULL

#============================
#Contact Table modifications
#============================

ALTER TABLE contact CHANGE ismodified ismodified VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE contact CHANGE is_archived isarchived VARCHAR (5) DEFAULT 'false' NOT NULL, 
ALTER TABLE contact CHANGE is_deleted isdeleted VARCHAR (5) DEFAULT 'false' NOT NULL,
ALTER TABLE contact CHANGE is_new isnew VARCHAR (5) DEFAULT 'true' NOT NULL,
ALTER TABLE contact CHANGE is_private isprivate VARCHAR (5) DEFAULT 'false' NOT NULL
ALTER TABLE contact ADD palm_catname VARCHAR (16) DEFAULT 'unfiled' NOT NULL

