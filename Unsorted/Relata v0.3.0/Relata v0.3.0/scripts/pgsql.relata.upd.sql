# Modification Script for 'activity' and 'contact' tables
# Avinesh Bangar
# Updated: March 6, 2002
#
#============================
#Activity Table modifications
#============================
#
# **** NOTE ****
#
# http://fts.postgresql.org/db/mw/msg.html?mid=1007575
#
# PostgreSQL does NOT support changing a column type like MySQL
# Please seee above URL for more information
#
# Backup your existing database before continuing
# The 'activity' table alterations WILL result in data loss (none for MySQL) if you have data in the
# 'activity' table, but it will be easier to restore your existing data from the backup
# data set after the alterations below are complete:
#
# Backup the Relata database to relata.sql
# pg_dump relata > relata.sql


ALTER TABLE "activity" DROP "activity_desc";
ALTER TABLE "activity" ADD "activity_desc" text NOT NULL; 
ALTER TABLE "activity" DROP "date";
ALTER TABLE "activity" ADD "date" varchar (12) NOT NULL; 
ALTER TABLE "activity" ALTER "date" SET DEFAULT '0000-00-00';
ALTER TABLE "activity" DROP "starttime";
ALTER TABLE "activity" ADD "starttime" varchar (10) NOT NULL ; 
ALTER TABLE "activity" ALTER "starttime" SET DEFAULT '00:00:00';
ALTER TABLE "activity" DROP "endtime";
ALTER TABLE "activity" ADD "endtime" varchar (10) NOT NULL ; 
ALTER TABLE "activity" ALTER "endtime" SET DEFAULT '00:00:00';
ALTER TABLE "activity" DROP "priority";
ALTER TABLE "activity" ADD "priority" int2 NOT NULL; 
ALTER TABLE "activity" ALTER "priority" SET DEFAULT 0;
ALTER TABLE "activity" DROP "is_calendar_item";
ALTER TABLE "activity" ADD "is_calendar_item" varchar (3) NOT NULL ; 
ALTER TABLE "activity" ALTER "is_calendar_item" SET DEFAULT 'N';
ALTER TABLE "activity" DROP "palm_record_id";
ALTER TABLE "activity" ADD "palm_record_id" int8 NOT NULL ; 
ALTER TABLE "activity" ALTER "palm_record_id" SET DEFAULT 0;

ALTER TABLE "activity" DROP "ismodified";
ALTER TABLE "activity" ADD "ismodified" varchar (5) NOT NULL ; 
ALTER TABLE "activity" ALTER "ismodified" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "isarchived";
ALTER TABLE "activity" ADD "isarchived" varchar (5) NOT NULL ; 
ALTER TABLE "activity" ALTER "isarchived" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "isnew";
ALTER TABLE "activity" ADD "isnew" varchar (5) NOT NULL ; 
ALTER TABLE "activity" ALTER "isnew" SET DEFAULT 'true';
ALTER TABLE "activity" DROP "isdeleted";
ALTER TABLE "activity" ADD "isdeleted" varchar (5) NOT NULL ; 
ALTER TABLE "activity" ALTER "isdeleted" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "isprivate";
ALTER TABLE "activity" ADD "isprivate" varchar (5) NOT NULL ;
ALTER TABLE "activity" ALTER "isprivate" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "entry_date";
ALTER TABLE "activity" ADD "entry_date" varchar (12) NOT NULL ; 
ALTER TABLE "activity" DROP "entry_time";
ALTER TABLE "activity" ADD "entry_time" varchar (12) NOT NULL ; 
ALTER TABLE "activity" DROP "last_mod_date";
ALTER TABLE "activity" ADD "last_mod_date" varchar (12) NOT NULL;

ALTER TABLE "activity" DROP "last_mod_time";
ALTER TABLE "activity" ADD "last_mod_time" varchar (12) NOT NULL ; 
ALTER TABLE "activity" DROP "note";
ALTER TABLE "activity" ADD "note" text NOT NULL ; 
ALTER TABLE "activity" DROP "catindex";
ALTER TABLE "activity" ADD "catindex" int8 NOT NULL ; 
ALTER TABLE "activity" DROP "iscompleted";
ALTER TABLE "activity" ADD "iscompleted" varchar (7) NOT NULL ; 
ALTER TABLE "activity" ALTER "iscompleted" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "isuntimed";
ALTER TABLE "activity" ADD "isuntimed" varchar (7) NOT NULL ; 
ALTER TABLE "activity" ALTER "isuntimed" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "isalarmed";
ALTER TABLE "activity" ADD "isalarmed" varchar (7) NOT NULL ; 
ALTER TABLE "activity" ALTER "isalarmed" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "alarmadvtime";
ALTER TABLE "activity" ADD "alarmadvtime" int2 NOT NULL ; 
ALTER TABLE "activity" DROP "alarmadvunit";
ALTER TABLE "activity" ADD "alarmadvunit" int2 NOT NULL ; 
ALTER TABLE "activity" DROP "isrepeating";
ALTER TABLE "activity" ADD "isrepeating" varchar (7) NOT NULL ; 
ALTER TABLE "activity" ALTER "isrepeating" SET DEFAULT 'false';
ALTER TABLE "activity" DROP "repeattype";
ALTER TABLE "activity" ADD "repeattype" int2 NOT NULL ; 

ALTER TABLE "activity" DROP "repeatenddate";
ALTER TABLE "activity" ADD "repeatenddate" varchar (12) NOT NULL ; 
ALTER TABLE "activity" ALTER "repeatenddate" SET DEFAULT '0000-00-00';
ALTER TABLE "activity" DROP "repeatfrequency";
ALTER TABLE "activity" ADD "repeatfrequency" int2 NOT NULL ; 
ALTER TABLE "activity" ALTER "repeatfrequency" SET DEFAULT 1;
ALTER TABLE "activity" DROP "repeaton";
ALTER TABLE "activity" ADD "repeaton" varchar (90) NOT NULL ; 
ALTER TABLE "activity" ALTER "repeaton" SET DEFAULT '0';
ALTER TABLE "activity" DROP "repeatstartweek";
ALTER TABLE "activity" ADD "repeatstartweek" int2 NOT NULL ; 

#============================
#Contact Table modifications
#============================
# No data loss from making the below modifications from Relata 0.2.4 to Relata 0.2.5

ALTER TABLE "contact" ALTER "ismodified" SET DEFAULT 'false';
ALTER TABLE "contact" DROP "isarchived";
ALTER TABLE "contact" ADD "isarchived" SET DEFAULT 'false';
ALTER TABLE "contact" DROP "isdeleted";
ALTER TABLE "contact" ADD "isdeleted" SET DEFAULT 'false';
ALTER TABLE "contact" DROP "isnew";
ALTER TABLE "contact" ADD "isnew" SET DEFAULT 'true';
ALTER TABLE "contact" DROP "isprivate";
ALTER TABLE "contact" ADD "isprivate" SET DEFAULT 'false';
ALTER TABLE "contact" ADD "palm_catname" SET DEFAULT 'Unfiled';
