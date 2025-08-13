
/* -------------------------------------------------------- 
  Database : relata
  2002-06-03 12:03:19
-------------------------------------------------------- */ 

/* -------------------------------------------------------- 
  Sequences 
-------------------------------------------------------- */ 
CREATE SEQUENCE "account_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "activity_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "contact_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "group_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "group_user_id_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "opportunity_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "ph_comm_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "ss_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "user_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 
CREATE SEQUENCE "xfield_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1; 

/* -------------------------------------------------------- 
  Table structure for table "account" 
-------------------------------------------------------- */
CREATE TABLE "account" (
   "user_id" varchar(32) NOT NULL,
   "account_id" int8 DEFAULT nextval('account_seq'::text) NOT NULL,
   "account_name" varchar NOT NULL,
   "account_address" varchar,
   "account_city" varchar,
   "account_state" varchar,
   "account_zip" varchar,
   "account_country" varchar,
   "account_website" varchar,
   "account_phone" varchar,
   "account_fax" varchar,
   "timedate_create" varchar NOT NULL,
   "account_status" varchar NOT NULL,
   "account_desc" text,
   CONSTRAINT "account_pkey" PRIMARY KEY ("account_id", "user_id")
);


/* -------------------------------------------------------- 
  Table structure for table "active_sessions" 
-------------------------------------------------------- */
CREATE TABLE "active_sessions" (
   "sid" varchar(32) DEFAULT '' NOT NULL,
   "name" varchar(32) DEFAULT '' NOT NULL,
   "val" text,
   "changed" varchar(14) DEFAULT '' NOT NULL,
   CONSTRAINT "active_sessions_pkey" PRIMARY KEY ("name", "sid")
);


/* -------------------------------------------------------- 
  Table structure for table "activity" 
-------------------------------------------------------- */
CREATE TABLE "activity" (
   "user_id" varchar(32) NOT NULL,
   "activity_id" int8 DEFAULT nextval('activity_seq'::text) NOT NULL,
   "activity_desc" text,
   "date" varchar(12) DEFAULT '0000-00-00',
   "starttime" varchar(10) DEFAULT '00:00:00',
   "endtime" varchar(10) DEFAULT '00:00:00',
   "priority" int2,
   "is_calendar_item" varchar(3),
   "palm_record_id" int8,
   "ismodified" varchar(5) DEFAULT 'false',
   "isarchived" varchar(5) DEFAULT 'false',
   "isnew" varchar(5) DEFAULT 'true',
   "isdeleted" varchar(5) DEFAULT 'false',
   "isprivate" varchar(5) DEFAULT 'false',
   "entry_date" varchar(12),
   "entry_time" varchar(12),
   "last_mod_date" varchar(12),
   "last_mod_time" varchar(12),
   "note" text,
   "catindex" int8,
   "iscompleted" varchar(7) DEFAULT 'false',
   "isuntimed" varchar(7) DEFAULT 'false',
   "isalarmed" varchar(7) DEFAULT 'false',
   "alarmadvtime" int2,
   "alarmadvunit" int2,
   "isrepeating" varchar(7) DEFAULT 'false',
   "repeattype" int2,
   "repeatenddate" varchar(12) DEFAULT '0000-00-00',
   "repeatfrequency" int2 DEFAULT 1,
   "repeaton" varchar(90) DEFAULT '0',
   "repeatstartweek" int2,
   CONSTRAINT "activity_pkey" PRIMARY KEY ("activity_id", "user_id")
);


/* -------------------------------------------------------- 
  Table structure for table "contact" 
-------------------------------------------------------- */
CREATE TABLE "contact" (
   "user_id" varchar(32) NOT NULL,
   "contact_id" int8 DEFAULT nextval('contact_seq'::text) NOT NULL,
   "fname" varchar,
   "mname" varchar,
   "lname" varchar,
   "prefix" varchar,
   "title" varchar,
   "company" varchar,
   "hm_address" varchar,
   "hm_city" varchar,
   "hm_state" varchar,
   "hm_zip" varchar,
   "hm_country" varchar,
   "bus_address" varchar,
   "bus_city" varchar,
   "bus_state" varchar,
   "bus_zip" varchar,
   "bus_country" varchar,
   "alt_address" varchar,
   "alt_city" varchar,
   "alt_state" varchar,
   "alt_zip" varchar,
   "alt_country" varchar,
   "website1" varchar,
   "website2" varchar,
   "palm_custfield1" varchar,
   "palm_custfield2" varchar,
   "palm_custfield3" varchar,
   "palm_custfield4" varchar,
   "palm_notes" text,
   "palm_category" int8,
   "palm_id" int8,
   "user_notes" text,
   "email_type" char(1),
   "is_prospect" char(1),
   "entry_date" varchar,
   "entry_time" varchar,
   "ismodified" varchar DEFAULT 'false',
   "last_mod_date" varchar,
   "last_mod_time" varchar,
   "palm_dphone" int8 DEFAULT 1,
   "contact_lbl1" int8,
   "contact_lbl2" int8 DEFAULT 1,
   "contact_lbl3" int8 DEFAULT 2,
   "contact_lbl4" int8 DEFAULT 3,
   "contact_lbl5" int8 DEFAULT 4,
   "contact_val1" varchar,
   "contact_val2" varchar,
   "contact_val3" varchar,
   "contact_val4" varchar,
   "contact_val5" varchar,
   "palm_record_id" int8,
   "isarchived" varchar(5) DEFAULT 'false',
   "isdeleted" varchar(5) DEFAULT 'false',
   "isnew" varchar(5) DEFAULT 'true',
   "isprivate" varchar(5) DEFAULT 'false',
   "palm_catname" varchar(16) DEFAULT 'Unfiled',
   CONSTRAINT "contact_pkey" PRIMARY KEY ("contact_id", "user_id")
);


/* -------------------------------------------------------- 
  Table structure for table "contact_account" 
-------------------------------------------------------- */
CREATE TABLE "contact_account" (
   "contact_id" int8 NOT NULL,
   "account_id" int8 NOT NULL,
   CONSTRAINT "contact_account_pkey" PRIMARY KEY ("contact_id")
);


/* -------------------------------------------------------- 
  Table structure for table "contact_group" 
-------------------------------------------------------- */
CREATE TABLE "contact_group" (
   "contact_id" int8 NOT NULL,
   "group_id" int8 NOT NULL,
   CONSTRAINT "contact_group_pkey" PRIMARY KEY ("contact_id", "group_id")
);


/* -------------------------------------------------------- 
  Table structure for table "contact_opportunity" 
-------------------------------------------------------- */
CREATE TABLE "contact_opportunity" (
   "contact_id" int8,
   "opp_id" int8
);


/* -------------------------------------------------------- 
  Table structure for table "contact_xfield" 
-------------------------------------------------------- */
CREATE TABLE "contact_xfield" (
   "contact_id" int8 NOT NULL,
   "xfield_id" int8 NOT NULL,
   "xfield_value" varchar NOT NULL,
   CONSTRAINT "contact_xfield_pkey" PRIMARY KEY ("contact_id", "xfield_id")
);


/* -------------------------------------------------------- 
  Table structure for table "extra_field" 
-------------------------------------------------------- */
CREATE TABLE "extra_field" (
   "user_id" varchar(32) NOT NULL,
   "xfield_id" int8 DEFAULT nextval('xfield_seq'::text) NOT NULL,
   "xfield_name" varchar NOT NULL,
   CONSTRAINT "extra_field_pkey" PRIMARY KEY ("user_id", "xfield_id")
);


/* -------------------------------------------------------- 
  Table structure for table "group_message_vars" 
-------------------------------------------------------- */
CREATE TABLE "group_message_vars" (
   "name" varchar(40) NOT NULL,
   "value" text,
   "user_id" varchar(32)
);


/* -------------------------------------------------------- 
  Table structure for table "groups" 
-------------------------------------------------------- */
CREATE TABLE "groups" (
   "user_id" varchar(32) NOT NULL,
   "group_id" int8 DEFAULT nextval('group_seq'::text) NOT NULL,
   "group_name" varchar NOT NULL,
   "group_desc" text NOT NULL,
   CONSTRAINT "group_pkey" PRIMARY KEY ("group_id")
);


/* -------------------------------------------------------- 
  Table structure for table "opportunity" 
-------------------------------------------------------- */
CREATE TABLE "opportunity" (
   "user_id" varchar(32) NOT NULL,
   "opp_id" int8 DEFAULT nextval('opportunity_seq'::text) NOT NULL,
   "ss_id" int8,
   "opp_title" varchar NOT NULL,
   "entry_date" date NOT NULL,
   "close_date" date NOT NULL,
   "opp_desc" text NOT NULL,
   "potential_revenue" varchar NOT NULL,
   "probability" int8 NOT NULL,
   CONSTRAINT "opportunity_pkey" PRIMARY KEY ("opp_id")
);


/* -------------------------------------------------------- 
  Table structure for table "relata_user" 
-------------------------------------------------------- */
CREATE TABLE "relata_user" (
   "user_id" varchar(32) NOT NULL,
   "login" varchar NOT NULL,
   "password" varchar NOT NULL,
   "login_date" date NOT NULL,
   "login_time" time NOT NULL,
   CONSTRAINT "relata_user_pkey" PRIMARY KEY ("user_id")
);


/* -------------------------------------------------------- 
  Table structure for table "sales_stage" 
-------------------------------------------------------- */
CREATE TABLE "sales_stage" (
   "ss_id" int8 NOT NULL,
   "ss_title" varchar NOT NULL,
   CONSTRAINT "sales_stage_pkey" PRIMARY KEY ("ss_id")
);


/* No Views found */

/* No Triggers found */

/* No Functions found */
