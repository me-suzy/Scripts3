#
# Tabellenstruktur für Tabelle `bb1_access`
#

DROP TABLE IF EXISTS bb1_access;
CREATE TABLE bb1_access (
  boardid int(11) unsigned NOT NULL default '0',
  userid int(11) unsigned NOT NULL default '0',
  boardpermission tinyint(1) NOT NULL default '0',
  startpermission tinyint(1) NOT NULL default '0',
  replypermission tinyint(1) NOT NULL default '0',
  PRIMARY KEY (boardid,userid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_adminsessions`
#

DROP TABLE IF EXISTS bb1_adminsessions;
CREATE TABLE bb1_adminsessions (
  hash varchar(32) NOT NULL default '',
  userid int(11) unsigned NOT NULL default '0',
  ipaddress varchar(16) NOT NULL default '',
  useragent varchar(100) NOT NULL default '',
  starttime int(11) unsigned NOT NULL default '0',
  lastactivity int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (hash)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_announcements`
#

DROP TABLE IF EXISTS bb1_announcements;
CREATE TABLE bb1_announcements (
  boardid int(11) NOT NULL default '0',
  threadid int(11) NOT NULL default '0',
  PRIMARY KEY (boardid,threadid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_attachments`
#

DROP TABLE IF EXISTS bb1_attachments;
CREATE TABLE bb1_attachments (
  attachmentid int(11) unsigned NOT NULL auto_increment,
  postid int(11) unsigned NOT NULL default '0',
  attachmentname varchar(250) NOT NULL default '',
  attachmentextension varchar(7) NOT NULL default '',
  attachmentsize int(11) unsigned NOT NULL default '0',
  counter int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (attachmentid),
  KEY postid(postid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_avatars`
#

DROP TABLE IF EXISTS bb1_avatars;
CREATE TABLE bb1_avatars (
  avatarid int(11) unsigned NOT NULL auto_increment,
  avatarname varchar(250) NOT NULL default '',
  avatarextension varchar(7) NOT NULL default '',
  width smallint(5) unsigned NOT NULL default '0',
  height smallint(5) unsigned NOT NULL default '0',
  groupid int(11) unsigned NOT NULL default '0',
  needposts mediumint(7) unsigned NOT NULL default '0',
  userid int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (avatarid),
  KEY userid(userid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_bbcodes`
#

DROP TABLE IF EXISTS bb1_bbcodes;
CREATE TABLE bb1_bbcodes (
  bbcodeid int(11) unsigned NOT NULL auto_increment,
  bbcodetag varchar(250) NOT NULL default '',
  bbcodereplacement text NOT NULL,
  bbcodeexample varchar(250) NOT NULL default '',
  bbcodeexplanation text NOT NULL,
  params tinyint(1) unsigned NOT NULL default '1',
  multiuse tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY (bbcodeid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_boards`
#

DROP TABLE IF EXISTS bb1_boards;
CREATE TABLE bb1_boards (
  boardid int(11) unsigned NOT NULL auto_increment,
  styleid int(11) unsigned NOT NULL default '0',
  parentid int(11) unsigned NOT NULL default '0',
  parentlist text NOT NULL,
  childlist text NOT NULL,
  boardorder mediumint(7) unsigned NOT NULL default '1',
  title varchar(70) NOT NULL default '',
  password varchar(25) NOT NULL default '',
  description text NOT NULL,
  prefixuse tinyint(1) NOT NULL default '0',
  prefix text NOT NULL,
  threadcount int(11) unsigned NOT NULL default '0',
  postcount int(11) unsigned NOT NULL default '0',
  lastthreadid int(11) unsigned NOT NULL default '0',
  lastposttime int(11) unsigned NOT NULL default '0',
  lastposterid int(11) unsigned NOT NULL default '0',
  lastposter varchar(50) NOT NULL default '0',
  allowbbcode tinyint(1) NOT NULL default '1',
  allowimages tinyint(1) NOT NULL default '1',
  allowhtml tinyint(1) NOT NULL default '0',
  allowsmilies tinyint(1) NOT NULL default '1',
  allowicons tinyint(1) NOT NULL default '1',
  allowpolls tinyint(1) NOT NULL default '1',
  allowattachments tinyint(1) NOT NULL default '1',
  daysprune smallint(5) unsigned NOT NULL default '0',
  threadsperpage smallint(5) unsigned NOT NULL default '0',
  postsperpage smallint(5) unsigned NOT NULL default '0',
  postorder tinyint(1) NOT NULL default '0',
  countuserposts tinyint(1) NOT NULL default '1',
  hotthread_reply smallint(5) unsigned NOT NULL default '0',
  hotthread_view smallint(5) unsigned NOT NULL default '0',
  moderatenew tinyint(2) NOT NULL default '0',
  enforcestyle tinyint(1) NOT NULL default '0',
  closed tinyint(1) NOT NULL default '0',
  isboard tinyint(1) NOT NULL default '0',
  invisible tinyint(1) NOT NULL default '0',
  PRIMARY KEY (boardid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_events`
#

DROP TABLE IF EXISTS bb1_events;
CREATE TABLE bb1_events (
  eventid int(11) unsigned NOT NULL auto_increment,
  userid int(11) unsigned NOT NULL default '0',
  groupid int(11) unsigned NOT NULL default '0',
  subject varchar(250) NOT NULL default '',
  event mediumtext NOT NULL,
  eventdate date NOT NULL default '0000-00-00',
  public tinyint(1) NOT NULL default '0',
  allowsmilies tinyint(1) NOT NULL default '1',
  PRIMARY KEY (eventid),
  KEY groupid(groupid),
  KEY userid(userid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_folders`
#

DROP TABLE IF EXISTS bb1_folders;
CREATE TABLE bb1_folders (
  folderid int(11) unsigned NOT NULL auto_increment,
  userid int(11) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  PRIMARY KEY (folderid),
  KEY userid(userid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_groups`
#

DROP TABLE IF EXISTS bb1_groups;
CREATE TABLE bb1_groups (
  groupid int(11) unsigned NOT NULL auto_increment,
  title varchar(30) NOT NULL default '',
  canviewboard tinyint(1) NOT NULL default '0',
  canviewoffboard tinyint(1) NOT NULL default '0',
  canusesearch tinyint(1) NOT NULL default '0',
  canusepms tinyint(1) NOT NULL default '0',
  canstarttopic tinyint(1) NOT NULL default '0',
  canreplyowntopic tinyint(1) NOT NULL default '0',
  canreplytopic tinyint(1) NOT NULL default '0',
  canpostwithoutmoderation tinyint(1) NOT NULL default '0',
  caneditownpost tinyint(1) NOT NULL default '0',
  candelownpost tinyint(1) NOT NULL default '0',
  cancloseowntopic tinyint(1) NOT NULL default '0',
  candelowntopic tinyint(1) NOT NULL default '0',
  caneditowntopic tinyint(1) NOT NULL default '0',
  canpostpoll tinyint(1) NOT NULL default '0',
  canvotepoll tinyint(1) NOT NULL default '0',
  canuseavatar tinyint(1) NOT NULL default '0',
  canuploadavatar tinyint(1) NOT NULL default '0',
  canuploadattachments tinyint(1) NOT NULL default '0',
  candownloadattachments tinyint(1) NOT NULL default '0',
  canratethread tinyint(1) NOT NULL default '0',
  canviewmblist tinyint(1) NOT NULL default '0',
  canviewprofile tinyint(1) NOT NULL default '0',
  canviewcalender tinyint(1) NOT NULL default '0',
  canprivateevent tinyint(1) NOT NULL default '0',
  canpublicevent tinyint(1) NOT NULL default '0',
  canrateusers tinyint(1) NOT NULL default '0',
  appendeditnote tinyint(1) NOT NULL default '0',
  avoidfc tinyint(1) NOT NULL default '0',
  ismod tinyint(1) NOT NULL default '0',
  issupermod tinyint(1) NOT NULL default '0',
  canuseacp tinyint(1) NOT NULL default '0',
  maxpostimage smallint(5) NOT NULL default '0',
  maxsigimage smallint(5) NOT NULL default '0',
  maxsiglength smallint(5) unsigned NOT NULL default '0',
  allowedavatarextensions text NOT NULL,
  maxavatarwidth smallint(5) unsigned NOT NULL default '0',
  maxavatarheight smallint(5) unsigned NOT NULL default '0',
  maxavatarsize mediumint(7) unsigned NOT NULL default '0',
  allowedattachmentextensions text NOT NULL,
  maxattachmentsize int(11) unsigned NOT NULL default '0',
  maxusertextlength smallint(5) unsigned NOT NULL default '0',
  default_group tinyint(1) NOT NULL default '0',
  PRIMARY KEY (groupid),
  KEY default_group(default_group)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_icons`
#

DROP TABLE IF EXISTS bb1_icons;
CREATE TABLE bb1_icons (
  iconid int(11) unsigned NOT NULL auto_increment,
  iconpath varchar(250) NOT NULL default '',
  icontitle varchar(250) NOT NULL default '',
  iconorder mediumint(7) unsigned NOT NULL default '0',
  PRIMARY KEY (iconid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_moderators`
#

DROP TABLE IF EXISTS bb1_moderators;
CREATE TABLE bb1_moderators (
  userid int(11) unsigned NOT NULL default '0',
  boardid int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (userid,boardid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_optiongroups`
#

DROP TABLE IF EXISTS bb1_optiongroups;
CREATE TABLE bb1_optiongroups (
  optiongroupid int(11) unsigned NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  showorder mediumint(7) unsigned NOT NULL default '0',
  PRIMARY KEY (optiongroupid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_options`
#

DROP TABLE IF EXISTS bb1_options;
CREATE TABLE bb1_options (
  optionid int(11) unsigned NOT NULL auto_increment,
  optiongroupid int(11) unsigned NOT NULL default '0',
  varname varchar(250) NOT NULL default '',
  value text NOT NULL,
  title varchar(250) NOT NULL default '',
  description text NOT NULL,
  optioncode text NOT NULL,
  showorder mediumint(7) unsigned NOT NULL default '0',
  PRIMARY KEY (optionid),
  KEY optiongroupid(optiongroupid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_permissions`
#

DROP TABLE IF EXISTS bb1_permissions;
CREATE TABLE bb1_permissions (
  boardid int(11) unsigned NOT NULL default '0',
  groupid int(11) unsigned NOT NULL default '0',
  boardpermission tinyint(1) NOT NULL default '0',
  startpermission tinyint(1) NOT NULL default '0',
  replypermission tinyint(1) NOT NULL default '0',
  PRIMARY KEY (boardid,groupid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_polloptions`
#

DROP TABLE IF EXISTS bb1_polloptions;
CREATE TABLE bb1_polloptions (
  polloptionid int(11) unsigned NOT NULL auto_increment,
  pollid int(11) unsigned NOT NULL default '0',
  polloption varchar(250) NOT NULL default '',
  votes mediumint(7) unsigned NOT NULL default '0',
  showorder tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (polloptionid),
  KEY pollid(pollid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_polls`
#

DROP TABLE IF EXISTS bb1_polls;
CREATE TABLE bb1_polls (
  pollid int(11) unsigned NOT NULL auto_increment,
  threadid int(11) unsigned NOT NULL default '0',
  question varchar(100) NOT NULL default '',
  starttime int(11) unsigned NOT NULL default '0',
  choicecount tinyint(3) unsigned NOT NULL default '0',
  timeout mediumint(7) unsigned NOT NULL default '0',
  PRIMARY KEY (pollid),
  KEY threadid(threadid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_posts`
#

DROP TABLE IF EXISTS bb1_posts;
CREATE TABLE bb1_posts (
  postid int(11) unsigned NOT NULL auto_increment,
  parentpostid int(11) unsigned NOT NULL default '0',
  threadid int(11) unsigned NOT NULL default '0',
  userid int(11) unsigned NOT NULL default '0',
  username varchar(50) NOT NULL default '0',
  iconid int(11) unsigned NOT NULL default '0',
  posttopic varchar(100) NOT NULL default '',
  posttime int(11) unsigned NOT NULL default '0',
  message mediumtext NOT NULL,
  attachmentid int(11) unsigned NOT NULL default '0',
  edittime int(11) unsigned NOT NULL default '0',
  editorid int(11) unsigned NOT NULL default '0',
  editor varchar(50) NOT NULL default '',
  editcount mediumint(7) unsigned NOT NULL default '0',
  allowsmilies tinyint(1) NOT NULL default '0',
  showsignature tinyint(1) NOT NULL default '0',
  ipaddress varchar(15) NOT NULL default '',
  visible tinyint(1) NOT NULL default '0',
  reindex tinyint(1) NOT NULL default '0',
  PRIMARY KEY (postid),
  KEY iconid(iconid),
  KEY userid(userid),
  KEY attachmentid(attachmentid),
  KEY threadid(threadid,visible),
  KEY threadid_2(threadid,userid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_privatemessage`
#

DROP TABLE IF EXISTS bb1_privatemessage;
CREATE TABLE bb1_privatemessage (
  privatemessageid int(11) unsigned NOT NULL auto_increment,
  folderid int(11) unsigned NOT NULL default '0',
  senderid int(11) unsigned NOT NULL default '0',
  recipientid int(11) unsigned NOT NULL default '0',
  subject varchar(250) NOT NULL default '',
  message text NOT NULL,
  sendtime int(11) unsigned NOT NULL default '0',
  showsmilies tinyint(1) NOT NULL default '0',
  showsignature tinyint(1) NOT NULL default '0',
  iconid int(11) unsigned NOT NULL default '0',
  view int(11) unsigned NOT NULL default '0',
  reply tinyint(1) NOT NULL default '0',
  forward tinyint(1) NOT NULL default '0',
  deletepm tinyint(1) NOT NULL default '0',
  tracking tinyint(1) NOT NULL default '0',
  PRIMARY KEY (privatemessageid),
  KEY folderid(folderid),
  KEY senderid(senderid),
  KEY recipientid(recipientid),
  KEY iconid(iconid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_profilefields`
#

DROP TABLE IF EXISTS bb1_profilefields;
CREATE TABLE bb1_profilefields (
  profilefieldid int(11) unsigned NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  description text NOT NULL,
  required tinyint(1) NOT NULL default '0',
  showinthread tinyint(1) NOT NULL default '0',
  hidden tinyint(1) NOT NULL default '0',
  maxlength smallint(5) unsigned NOT NULL default '250',
  fieldsize tinyint(3) unsigned NOT NULL default '25',
  fieldorder mediumint(7) unsigned NOT NULL default '0',
  PRIMARY KEY (profilefieldid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_ranks`
#

DROP TABLE IF EXISTS bb1_ranks;
CREATE TABLE bb1_ranks (
  rankid int(11) unsigned NOT NULL auto_increment,
  groupid int(11) unsigned NOT NULL default '1',
  gender tinyint(1) NOT NULL default '0',
  needposts mediumint(7) unsigned NOT NULL default '0',
  ranktitle varchar(70) NOT NULL default '',
  rankimages text NOT NULL,
  PRIMARY KEY (rankid),
  KEY groupid(groupid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_searchs`
#

DROP TABLE IF EXISTS bb1_searchs;
CREATE TABLE bb1_searchs (
  searchid int(11) unsigned NOT NULL auto_increment,
  searchstring varchar(250) NOT NULL default '',
  searchuserid int(11) unsigned NOT NULL default '0',
  postids mediumtext NOT NULL,
  showposts tinyint(1) NOT NULL default '0',
  sortby varchar(25) NOT NULL default '0',
  sortorder varchar(4) NOT NULL default '0',
  searchtime int(11) unsigned NOT NULL default '0',
  userid int(11) unsigned NOT NULL default '0',
  ipaddress varchar(16) NOT NULL default '',
  PRIMARY KEY (searchid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_sessions`
#

DROP TABLE IF EXISTS bb1_sessions;
CREATE TABLE bb1_sessions (
  hash varchar(32) NOT NULL default '',
  userid int(11) unsigned NOT NULL default '0',
  ipaddress varchar(16) NOT NULL default '',
  useragent varchar(100) NOT NULL default '',
  lastactivity int(11) unsigned NOT NULL default '0',
  request_uri varchar(250) NOT NULL default '',
  styleid int(11) unsigned NOT NULL default '0',
  boardid int(11) unsigned NOT NULL default '0',
  threadid int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (hash),
  KEY userid(userid),
  KEY boardid(boardid)
) TYPE=HEAP;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_smilies`
#

DROP TABLE IF EXISTS bb1_smilies;
CREATE TABLE bb1_smilies (
  smilieid int(11) unsigned NOT NULL auto_increment,
  smiliepath varchar(250) NOT NULL default '{imagefolder}/',
  smilietitle varchar(250) NOT NULL default '',
  smiliecode varchar(250) NOT NULL default '',
  smilieorder mediumint(7) unsigned NOT NULL default '0',
  PRIMARY KEY (smilieid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_styles`
#

DROP TABLE IF EXISTS bb1_styles;
CREATE TABLE bb1_styles (
  styleid int(11) unsigned NOT NULL auto_increment,
  stylename varchar(100) NOT NULL default '',
  templatepackid int(11) unsigned NOT NULL default '0',
  subvariablepackid int(11) unsigned NOT NULL default '0',
  default_style tinyint(1) NOT NULL default '0',
  PRIMARY KEY (styleid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_subscribeboards`
#

DROP TABLE IF EXISTS bb1_subscribeboards;
CREATE TABLE bb1_subscribeboards (
  userid int(11) unsigned NOT NULL default '0',
  boardid int(11) unsigned NOT NULL default '0',
  emailnotify tinyint(1) NOT NULL default '0',
  countemails tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (userid,boardid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_subscribethreads`
#

DROP TABLE IF EXISTS bb1_subscribethreads;
CREATE TABLE bb1_subscribethreads (
  userid int(11) unsigned NOT NULL default '0',
  threadid int(11) unsigned NOT NULL default '0',
  emailnotify tinyint(1) NOT NULL default '0',
  countemails tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (userid,threadid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_subvariablepacks`
#

DROP TABLE IF EXISTS bb1_subvariablepacks;
CREATE TABLE bb1_subvariablepacks (
  subvariablepackid int(11) unsigned NOT NULL auto_increment,
  subvariablepackname varchar(100) NOT NULL default '',
  PRIMARY KEY (subvariablepackid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_subvariables`
#

DROP TABLE IF EXISTS bb1_subvariables;
CREATE TABLE bb1_subvariables (
  subvariableid int(11) unsigned NOT NULL auto_increment,
  subvariablepackid int(11) unsigned NOT NULL default '0',
  variable text NOT NULL,
  substitute text NOT NULL,
  PRIMARY KEY (subvariableid),
  KEY subvariablepackid(subvariablepackid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_templatepacks`
#

DROP TABLE IF EXISTS bb1_templatepacks;
CREATE TABLE bb1_templatepacks (
  templatepackid int(11) unsigned NOT NULL auto_increment,
  templatepackname varchar(100) NOT NULL default '',
  templatefolder varchar(250) NOT NULL default '',
  PRIMARY KEY (templatepackid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_templates`
#

DROP TABLE IF EXISTS bb1_templates;
CREATE TABLE bb1_templates (
  templateid int(11) unsigned NOT NULL auto_increment,
  templatepackid int(11) unsigned NOT NULL default '0',
  templatename varchar(100) NOT NULL default '',
  template mediumtext NOT NULL,
  PRIMARY KEY (templateid),
  UNIQUE KEY templatepackid(templatepackid,templatename)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_threads`
#

DROP TABLE IF EXISTS bb1_threads;
CREATE TABLE bb1_threads (
  threadid int(11) unsigned NOT NULL auto_increment,
  boardid int(11) unsigned NOT NULL default '0',
  prefix varchar(250) NOT NULL default '',
  topic varchar(250) NOT NULL default '',
  iconid int(11) unsigned NOT NULL default '0',
  starttime int(11) unsigned NOT NULL default '0',
  starterid int(11) unsigned NOT NULL default '0',
  starter varchar(50) NOT NULL default '',
  lastposttime int(11) unsigned NOT NULL default '0',
  lastposterid int(11) unsigned NOT NULL default '0',
  lastposter varchar(50) NOT NULL default '',
  replycount mediumint(7) unsigned NOT NULL default '0',
  views mediumint(7) unsigned NOT NULL default '0',
  closed tinyint(1) NOT NULL default '0',
  voted smallint(5) unsigned NOT NULL default '0',
  votepoints mediumint(7) unsigned NOT NULL default '0',
  attachments smallint(5) unsigned NOT NULL default '0',
  pollid int(11) unsigned NOT NULL default '0',
  important tinyint(1) NOT NULL default '0',
  visible tinyint(1) NOT NULL default '0',
  PRIMARY KEY (threadid),
  KEY iconid(iconid),
  KEY boardid(boardid,visible,important,lastposttime),
  KEY visible(visible,lastposttime,closed)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_userfields`
#

DROP TABLE IF EXISTS bb1_userfields;
CREATE TABLE bb1_userfields (
  userid int(11) unsigned NOT NULL default '0',
  field1 varchar(250) NOT NULL default '',
  field2 varchar(250) NOT NULL default '',
  field3 varchar(250) NOT NULL default '',
  PRIMARY KEY (userid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_users`
#

DROP TABLE IF EXISTS bb1_users;
CREATE TABLE bb1_users (
  userid int(11) unsigned NOT NULL auto_increment,
  username varchar(50) NOT NULL default '',
  password varchar(50) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  userposts mediumint(7) unsigned NOT NULL default '0',
  groupid int(11) unsigned NOT NULL default '0',
  rankid int(11) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  regdate int(11) unsigned NOT NULL default '0',
  lastvisit int(11) unsigned NOT NULL default '0',
  lastactivity int(11) unsigned NOT NULL default '0',
  usertext text NOT NULL,
  signature text NOT NULL,
  icq varchar(30) NOT NULL default '',
  aim varchar(30) NOT NULL default '',
  yim varchar(30) NOT NULL default '',
  msn varchar(30) NOT NULL default '',
  homepage varchar(250) NOT NULL default '',
  birthday date NOT NULL default '0000-00-00',
  avatarid int(11) unsigned NOT NULL default '0',
  gender tinyint(1) NOT NULL default '0',
  showemail tinyint(1) NOT NULL default '0',
  admincanemail tinyint(1) NOT NULL default '1',
  usercanemail tinyint(1) NOT NULL default '1',
  invisible tinyint(1) NOT NULL default '0',
  usecookies tinyint(1) NOT NULL default '1',
  styleid int(11) unsigned NOT NULL default '0',
  activation int(11) unsigned NOT NULL default '0',
  blocked tinyint(1) NOT NULL default '0',
  daysprune smallint(5) unsigned NOT NULL default '0',
  timezoneoffset char(3) NOT NULL default '',
  startweek tinyint(1) NOT NULL default '0',
  dateformat varchar(10) NOT NULL default '',
  timeformat varchar(10) NOT NULL default '',
  emailnotify tinyint(1) NOT NULL default '0',
  buddylist text NOT NULL,
  ignorelist text NOT NULL,
  receivepm tinyint(1) NOT NULL default '1',
  emailonpm tinyint(1) NOT NULL default '0',
  pmpopup tinyint(1) NOT NULL default '0',
  umaxposts smallint(5) unsigned NOT NULL default '0',
  showsignatures tinyint(1) NOT NULL default '1',
  showavatars tinyint(1) NOT NULL default '1',
  showimages tinyint(1) NOT NULL default '1',
  nosessionhash tinyint(1) NOT NULL default '0',
  ratingcount smallint(5) unsigned NOT NULL default '0',
  ratingpoints mediumint(7) unsigned NOT NULL default '0',
  threadview tinyint(1) NOT NULL default '0',
  PRIMARY KEY (userid),
  KEY username(username),
  KEY groupid(groupid),
  KEY rankid(rankid),
  KEY avatarid(avatarid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_votes`
#

DROP TABLE IF EXISTS bb1_votes;
CREATE TABLE bb1_votes (
  id int(11) unsigned NOT NULL default '0',
  votemode tinyint(1) NOT NULL default '0',
  userid int(11) unsigned NOT NULL default '0',
  ipaddress varchar(15) NOT NULL default '',
  KEY userid(userid,id),
  KEY ipaddress(ipaddress,id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_wordlist`
#

DROP TABLE IF EXISTS bb1_wordlist;
CREATE TABLE bb1_wordlist (
  wordid int(11) unsigned NOT NULL auto_increment,
  word varchar(50) NOT NULL default '',
  PRIMARY KEY (wordid),
  UNIQUE KEY word(word)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `bb1_wordmatch`
#

DROP TABLE IF EXISTS bb1_wordmatch;
CREATE TABLE bb1_wordmatch (
  wordid int(11) unsigned NOT NULL default '0',
  postid int(11) unsigned NOT NULL default '0',
  intopic tinyint(1) NOT NULL default '0',
  PRIMARY KEY (wordid,postid)
) TYPE=MyISAM;