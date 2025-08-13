DROP TABLE IF EXISTS Accounts ;
CREATE TABLE Accounts (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Username varchar(50) NOT NULL UNIQUE default '',
  Password varchar(50) NOT NULL default '',
  Contact_name varchar(70) NOT NULL default '',
  Contact_email varchar(70) NOT NULL default '',
  Contact_phone varchar(15) NOT NULL default '',
  Contact_address varchar(15) NOT NULL default '',
  Userlevel varchar(10) NOT NULL default '',
  Last_Login  date NOT NULL default '0000-00-00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS News;
CREATE TABLE News (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Headline varchar(50) NOT NULL default '',
  Poster varchar(50) NOT NULL default '',
  Content TEXT NOT NULL default '',
  Date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS Venues ;
CREATE TABLE Venues (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Name varchar(15) NOT NULL UNIQUE default '',
  County varchar(50) NOT NULL default '',
  Address varchar(50) NOT NULL default '',
  Phone varchar(30) NOT NULL default '',
  Email varchar(30) NOT NULL default '',
  Website varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
INSERT INTO Venues VALUES (0001, 'No Venue','','','','','');

DROP TABLE IF EXISTS Leagues;
CREATE TABLE Leagues (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Name varchar(15) NOT NULL UNIQUE default '',
  Fullname varchar(100) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
    
DROP TABLE IF EXISTS Agegroups ;
CREATE TABLE Agegroups (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Name varchar(15) NOT NULL UNIQUE default '',
  Fullname varchar(100) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
INSERT INTO Agegroups VALUES (0001, 'No Agegroup', '');

DROP TABLE IF EXISTS Divisions ;
CREATE TABLE Divisions (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Name varchar(15) NOT NULL UNIQUE default '',
  Fullname varchar(100) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
INSERT INTO Divisions VALUES (0001, 'No Division', '');
    
DROP TABLE IF EXISTS Teams;
CREATE TABLE Teams (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Position tinyint(4) NOT NULL default '0',
  Name varchar(70) NOT NULL UNIQUE default '',
  Played tinyint(4) NOT NULL default '0',
  Wins tinyint(4) NOT NULL default '0',
  Draws tinyint(4) NOT NULL default '0',
  Loses tinyint(4) NOT NULL default '0',
  GF tinyint(4) NOT NULL default '0',
  GA tinyint(4) NOT NULL default '0',
  GD tinyint(5) NOT NULL default '0',
  Pts tinyint(5) NOT NULL default '0',
  League varchar(15) NOT NULL default '',
  Agegroup varchar(15) NOT NULL default '',
  Division varchar(15) NOT NULL default '',
  Venue varchar(15) NOT NULL default '',
  Website varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS Fixtures;
CREATE TABLE Fixtures (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Game varchar(50) NOT NULL default '',
  Venue varchar(15) NOT NULL,
  League varchar(15) NOT NULL default '',
  Agegroup varchar(15) NOT NULL default '',
  Division varchar(15) NOT NULL default '',
  Date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
    
DROP TABLE IF EXISTS Games;
CREATE TABLE Games (
  Id int(4) unsigned zerofill NOT NULL auto_increment,
  Name varchar(50) NOT NULL default '',
  Score varchar(5) NOT NULL default '0-0',
  PoG varchar(50) NOT NULL default '',
  League varchar(15) NOT NULL default '',
  Agegroup varchar(15) NOT NULL default '',
  Division varchar(15) NOT NULL default '',
  Date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS Layout;
CREATE TABLE Layout (
  versionsrc varchar(10) NOT NULL default '1.0',
  serverpath varchar(100) NOT NULL default '',
  headersrc TEXT NOT NULL default '',
  footersrc TEXT NOT NULL default '',
  indexsrc TINYTEXT NOT NULL default '',
  leaguesrc TINYTEXT NOT NULL default '',
  newssrc TINYTEXT NOT NULL default ''
) TYPE=MyISAM;

INSERT INTO Layout VALUES (
'1.0',
'http://localhost/phpfootball/',
'<p>global header content</p>',
'<p>global footer content</p>',
'scripts/news.mainnews.php?dbfield=Content&dbtable=News&Id=0001',
'scripts/show.php?dbtable=Teams&dbfield=Position&urled=Division,Venue,League,Agegroup',
'scripts/show.php?dbtable=News&dbfield=Date'
);

DROP TABLE IF EXISTS Modules ;
CREATE TABLE Modules (
  league TEXT NOT NULL default '',
  news TEXT NOT NULL default ''
) TYPE=MyISAM;

INSERT INTO Modules VALUES (
'[ <a href=../league.index.php target=_parent>league</a> ]',
'[ <a href=../news.index.php target=_parent>news</a> ]'
);

DROP TABLE IF EXISTS Help ;
CREATE TABLE Help (
  league TEXT NOT NULL default '',
  news TEXT NOT NULL default ''
) TYPE=MyISAM;

INSERT INTO Help VALUES (
'<p>You can use this to view/create/edit games,fixtures,teams,leagues,divisions,venues</p>
<p>Every time you enter a game results in the database team statistics are created/updated for the teams that played that game</p>
<p>However its your responsability to add the league,division and website for each team</p>
<p>Only admins can create/edit enteries under the league section</p>',
'<p>You can use this to view/create/edit news</p>
<p>Admins can edit/create news either in a text mode or a GUI html mode and chose whatever name from the users as the poster</p>
<p>Registered users can post news too but only in a html GUI mode and only for their name</p>
<p>Admins can chose a specific news entry wich is the one to be displayed on the main PHPFootball index</p>'
);