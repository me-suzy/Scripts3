<?#//v.1.0.1
session_start();
?>
<FONT FACE=Courier>
 Starting upgrade... <?=$DB_NAME?><BR><BR>
<?
  if(!@mysql_connect($DB_HOST,$DB_USER,$DB_PASSWORD))
  {
     print "Connection failed!<BR>$DB_HOST,$DB_USER,$DB_PASSWORD";
     exit;
  }
  if(!@mysql_select_db($DB_NAME))
  {
     print "Database selection failed!<BR>$DB_NAME<BR>".mysql_error();
     exit;
  }

  print "Creating PHPAUCTIONPROPLUS_accounts table... ";
  flush();
//error in query fixed "operation" mis-spelled. MH.
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_accounts(
                                 id int(11) NOT NULL auto_increment,
                                 user varchar(32) NOT NULL default '',
                                 description varchar(255) NOT NULL default '',
                                 operation_date varchar(8) NOT NULL default '',
                                 operation_type int(1) NOT NULL default '0',
                                 operation_amount double NOT NULL default '0',
                                 account_balance double NOT NULL default '0',
                                 auction varchar(32) NOT NULL default '',
                                 KEY id (id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.<BR><BR> ";
  }
  flush();

  print "Creating PHPAUCTIONPROPLUS_adminusers table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_adminusers (
                                      id int(11) NOT NULL auto_increment,
                                      username varchar(32) NOT NULL default '',
                                      password varchar(32) NOT NULL default '',
                                      created varchar(8) NOT NULL default '',
                                      lastlogin varchar(14) NOT NULL default '',
                                      status int(2) NOT NULL default '0',
                                      KEY id (id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR>";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR>";
  }
  flush();

  print "Creating PHPAUCTIONPROPLUS_auctions table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_auctions (
                                id varchar(32) NOT NULL default '',
                                user varchar(32) default NULL,
                                title tinytext,
                                starts timestamp(14) NOT NULL,
                                description text,
                                pict_url tinytext,
                                category int(11) default NULL,
                                minimum_bid double(16,4) default NULL,
                                reserve_price double(16,4) default NULL,
                                auction_type char(1) default NULL,
                                duration varchar(7) default NULL,
                                increment double(8,4) NOT NULL default '0.0000',
                                location tinytext,
                                location_zip varchar(6) default NULL,
                                shipping char(1) default NULL,
                                payment tinytext,
                                international char(1) default NULL,
                                ends timestamp(14) NOT NULL,
                                current_bid double(16,4) default NULL,
                                closed char(2) default NULL,
                                photo_uploaded char(1) default NULL,
                                quantity int(11) default NULL,
                                suspended int(1) default '0',
                                PRIMARY KEY (id),
                                KEY id (id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
    	print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_bids table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_bids (
                                   auction varchar(32) default NULL,
                                   bidder varchar(32) default NULL,
                                   bid double(16,4) default NULL,
                                   bidwhen timestamp(14) NOT NULL,
                                   quantity int(11) default '0') TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
    	print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_categories table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_categories (
                                         cat_id int(4) NOT NULL auto_increment,
                                         parent_id int(4) default NULL,
                                         cat_name tinytext,
                                         deleted int(1) default NULL,
                                         sub_counter int(11) default NULL,
                                         counter int(11) default NULL,
                                         cat_colour tinytext NOT NULL,
                                         cat_image tinytext NOT NULL,
                                         PRIMARY KEY  (cat_id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_categories_plain table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_categories_plain (
                                            id int(11) NOT NULL auto_increment,
                                            cat_id int(11) default NULL,
                                            cat_name tinytext,
                                            PRIMARY KEY (id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	   print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_counters table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_counters (
                  users int(11) default '0',
                  auctions int(11) default '0',
                  closedauctions int(11) NOT NULL default '0',
                  inactiveusers int(11) NOT NULL default '0',
                  bids int(11) NOT NULL default '0',
                  transactions int(11) NOT NULL default '0',
                  totalamount double NOT NULL default '0',
                  resetdate varchar(8) NOT NULL default '',
                  fees double NOT NULL default '0',
                  suspendedauction int(11) NOT NULL default '0') TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_countries table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_countries (
                                      country_id int(2) NOT NULL auto_increment,
                                      country varchar(30) NOT NULL default '',
                                      PRIMARY KEY (country_id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_durations table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_durations (
                           days double(6,4) NOT NULL default '0.0000',
                           description varchar(30) default NULL) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }
  //check with Gian if "rater" is mis-spelled or not.(?) Possible typo. MH
  print "Creating PHPAUCTIONPROPLUS_feedbacks table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_feedbacks (
                            rated_user_id varchar(32) default NULL,
                            rater_user_nick varchar(20) default NULL,
                            feedback mediumtext,
                            rate int(2) default NULL,
                            feedbackdate timestamp(14) NOT NULL) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_help table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_help (
                                                 topic varchar(40) default NULL,
                                                 helptext text) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_increments table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_increments (
                            id char(3) default NULL,
                            low double(16,4) default NULL,
                            high double(16,4) default NULL,
                            increment double(16,4) default NULL) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_news table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_news (
                          id varchar(32) NOT NULL default '',
                          title varchar(200) NOT NULL default '',
                          content longtext NOT NULL,
                          new_date int(8) NOT NULL default '0',
                          suspended int(1) NOT NULL default '0') TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_payments table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_payments (
                           id int(2) default NULL,
                           description varchar(30) default NULL) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPRO_request table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_request (
                                req_auction varchar(32) default NULL,
                                req_user varchar(32) default NULL,
                                req_text text,
                                req_date timestamp(14) NOT NULL) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_settings table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_settings (
                        sitename varchar(255) NOT NULL default '',
                        siteurl varchar(255) NOT NULL default '',
                        cookiesprefix varchar(100) NOT NULL default '',
                        loginbox int(1) NOT NULL default '0',
                        newsbox int(1) NOT NULL default '0',
                        newstoshow int(11) NOT NULL default '0',
                        moneyformat int(1) NOT NULL default '0',
                        moneydecimals int(11) NOT NULL default '0',
                        moneysymbol int(1) NOT NULL default '0',
                        currency varchar(10) NOT NULL default '',
                        showacceptancetext int(1) NOT NULL default '0',
                        acceptancetext longtext NOT NULL,
                        adminmail varchar(100) NOT NULL default '',
                        err_font varchar(5) NOT NULL default '',
                        std_font varchar(5) NOT NULL default '',
                        sml_font varchar(5) NOT NULL default '',
                        tlt_font varchar(5) NOT NULL default '',
                        nav_font varchar(5) NOT NULL default '',
                        footer_font varchar(5) NOT NULL default '',
                        bordercolor char(1) NOT NULL default '0',
                        headercolor char(1) NOT NULL default '0',
                        tableheadercolor varchar(4) NOT NULL default '0000',
                        linkscolor char(1) NOT NULL default '0',
                        vlinkscolor char(1) NOT NULL default '0',
                        banners int(1) NOT NULL default '0',
                        newsletter int(1) NOT NULL default '0',
                        logo varchar(255) NOT NULL default '',
                        timecorrection int(11) NOT NULL default '0',
                        cron int(1) NOT NULL default '0',
                        archiveafter int(11) NOT NULL default '0',
                        datesformat enum('USA','EUR') NOT NULL default 'USA',
                        feetype enum('prepay','pay') NOT NULL default 'prepay',
                        sellersetupfee int(1) NOT NULL default '0',
                        sellersetuptype int(11) NOT NULL default '0',
                        sellerfinalfee int(11) NOT NULL default '0',
                        sellerfinaltype tinyint(4) NOT NULL default '0',
                        sellersetupvalue double NOT NULL default '0',
                        sellerfinalvalue double NOT NULL default '0',
                        buyerfinalfee int(11) NOT NULL default '0',
                        buyerfinaltype int(11) NOT NULL default '0',
                        buyerfinalvalue double NOT NULL default '0',
                        paypaladdress varchar(255) NOT NULL default '',
                        errortext text NOT NULL,
                        errormail varchar(255) NOT NULL default '',
                        signupfee int(1) NOT NULL default '0',
                        signupvalue double NOT NULL default '0') TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
    	print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_users table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_users (
                                       id varchar(32) NOT NULL default '',
                                       nick varchar(20) default NULL,
                                       password varchar(32) default NULL,
                                       name tinytext,
                                       address tinytext,
                                       city varchar(25) default NULL,
                                       prov varchar(10) default NULL,
                                       country varchar(4) default NULL,
                                       zip varchar(6) default NULL,
                                       phone varchar(40) default NULL,
                                       email varchar(50) default NULL,
                                       reg_date timestamp(14) NOT NULL,
                                       rate_sum int(11) default NULL,
                                       rate_num int(11) default NULL,
                                       birthdate int(8) default NULL,
                                       suspended int(1) default '0',
                                       nletter int(1) NOT NULL default '0',
                                       balance double NOT NULL default '0',
                                       auc_watch varchar(20) default 'disabled',
                                       item_watch text,
                                       PRIMARY KEY (id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }

  print "Creating PHPAUCTIONPROPLUS_winners table... ";
  if(@mysql_query("CREATE TABLE PHPAUCTIONPROPLUS_winners (
                                        id int(11) NOT NULL auto_increment,
                                        auction varchar(32) NOT NULL default '',
                                        seller varchar(32) NOT NULL default '',
                                        winner varchar(32) NOT NULL default '',
                                        bid double NOT NULL default '0',
                                        closingdate timestamp(14) NOT NULL,
                                        fee double NOT NULL default '0',
                                        KEY id (id)) TYPE=MyISAM;"))
  {
	    print "done.<BR><BR> ";
  }
  else
  {
	    print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
  }
		if(!$HTTP_SESSION_VARS[DB] == "current")
		{
     print "Creating phpads_acls table... ";
     if(@mysql_query("CREATE TABLE phpads_acls (
     bannerID mediumint(9) NOT NULL default '0',
     acl_con set('and','or') NOT NULL default '',
     acl_type enum('clientip','useragent','weekday','domain','source','time','language') NOT NULL default 'clientip',
     acl_data varchar(255) NOT NULL default '',
     acl_ad set('allow','deny') NOT NULL default '',
     acl_order int(10) unsigned NOT NULL default '0',
     PRIMARY KEY  (bannerID,acl_order),
     KEY bannerID (bannerID)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
	       print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
     }

     print "Creating phpads_adclicks table... ";
     if(@mysql_query("CREATE TABLE phpads_adclicks (
                                     bannerID mediumint(9) NOT NULL default '0',
                                     t_stamp timestamp(14) NOT NULL,
                                     host varchar(255) NOT NULL default '',
                                     KEY clientID (bannerID)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
	       print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
     }

     print "Creating phpads_adstats table... ";
     if(@mysql_query("CREATE TABLE phpads_adstats (
                                    views int(11) NOT NULL default '0',
                                    clicks int(11) NOT NULL default '0',
                                    day date NOT NULL default '0000-00-00',
                                    BannerID smallint(6) NOT NULL default '0',
                                    PRIMARY KEY  (day,BannerID)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
	       print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
     }

     print "Creating phpads_adviews table... ";
     if(@mysql_query("CREATE TABLE phpads_adviews (
                                     bannerID mediumint(9) NOT NULL default '0',
                                     t_stamp timestamp(14) NOT NULL,
                                     host varchar(255) NOT NULL default '',
                                     KEY clientID (bannerID)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
    	   print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
     }

     print "Creating phpads_banners table... ";
     if(@mysql_query("CREATE TABLE phpads_banners (
     bannerID mediumint(9) NOT NULL auto_increment,
     clientID mediumint(9) NOT NULL default '0',
     active enum('true','false') NOT NULL default 'true',
     weight tinyint(4) NOT NULL default '1',
     seq tinyint(4) NOT NULL default '0',
     banner blob NOT NULL,
     width smallint(6) NOT NULL default '0',
     height smallint(6) NOT NULL default '0',
     format enum('gif','jpeg','png','html','url','web','swf') NOT NULL default 'gif',
     url varchar(255) NOT NULL default '',
     alt varchar(255) NOT NULL default '',
     status varchar(255) NOT NULL default '',
     keyword varchar(255) NOT NULL default '',
     bannertext varchar(255) NOT NULL default '',
     target varchar(8) NOT NULL default '',
     description varchar(255) NOT NULL default '',
     autohtml enum('true','false') NOT NULL default 'true',
     PRIMARY KEY  (bannerID)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
	      print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
     }

     print "Creating phpads_clients table... ";
     if(@mysql_query("CREATE TABLE phpads_clients (
                  clientID mediumint(9) NOT NULL auto_increment,
                  clientname varchar(255) NOT NULL default '',
                  contact varchar(255) default NULL,
                  email varchar(64) NOT NULL default '',
                  views mediumint(9) default NULL,
                  clicks mediumint(9) default NULL,
                  clientusername varchar(64) NOT NULL default '',
                  clientpassword varchar(64) NOT NULL default '',
                  expire date default '0000-00-00',
                  activate date default '0000-00-00',
                  permissions mediumint(9) default NULL,
                  language varchar(64) default NULL,
                  active enum('true','false') NOT NULL default 'true',
                  weight tinyint(4) NOT NULL default '1',
                  parent mediumint(9) NOT NULL default '0',
                  report enum('true','false') NOT NULL default 'true',
                  reportinterval mediumint(9) NOT NULL default '7',
                  reportlastdate date NOT NULL default '0000-00-00',
                  reportdeactivate enum('true','false') NOT NULL default 'true',
                  PRIMARY KEY  (clientID)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
	       print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
     }

     print "Creating phpads_session table... ";
     if(@mysql_query("CREATE TABLE phpads_session (
                                      SessionID varchar(32) NOT NULL default '',
                                      SessionData blob NOT NULL,
                                      LastUsed timestamp(14) NOT NULL,
                                      PRIMARY KEY (SessionID)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
	       print "<FONT COLOR=RED>ERROR.</FONT><BR><BR> ";
     }

     print "Creating phpads_zones table... ";
     if(@mysql_query("CREATE TABLE phpads_zones (
                    zoneid mediumint(9) NOT NULL auto_increment,
                    zonename varchar(255) NOT NULL default '',
                    description varchar(255) NOT NULL default '',
                    zonetype smallint(6) NOT NULL default '0',
                    what blob NOT NULL,
                    width smallint(6) NOT NULL default '0',
                    height smallint(6) NOT NULL default '0',
                    retrieval enum('random','cookie') NOT NULL default 'random',
                    cachecontents blob,
                    cachetimestamp int(11) NOT NULL default '0',
                    PRIMARY KEY  (zoneid)) TYPE=MyISAM;"))
     {
	       print "done.<BR><BR> ";
     }
     else
     {
	       print "<FONT COLOR=RED>ERROR".mysql_error()."</FONT><BR><BR> ";
     }
  }
?>
