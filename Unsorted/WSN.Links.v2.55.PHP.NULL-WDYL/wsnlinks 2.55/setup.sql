drop table if exists {PREFIX}links ;
 drop table if exists {PREFIX}categories ;
 drop table if exists {PREFIX}comments ;
 drop table if exists {PREFIX}settings ;
 drop table if exists {PREFIX}members ;
 drop table if exists {PREFIX}membergroups ;
 create table {PREFIX}links
 (
     id int default '0' NOT NULL auto_increment,
       title text NOT NULL,
       url text NOT NULL,
       description text NOT NULL,
       rating real default '0' NOT NULL,
       votes int default '0' NOT NULL,
       validated int default '0' NOT NULL,
       recipurl text NOT NULL,
       catid int default '0' NOT NULL,
       sumofvotes int default '0' NOT NULL,
       email text NOT NULL,
       time int default '0' NOT NULL,
       hits int default '0' NOT NULL,
       numcomments int default '0' NOT NULL,
       hide tinyint(1) default '0' NOT NULL,
       ownerid int default '0' NOT NULL,
       hitsin int default '0' NOT NULL,
       voterips text NOT NULL,
       voterids text NOT NULL,
       lastedit int default '0' NOT NULL,
       type text NOT NULL,
       filename text NOT NULL,
       filetitle text NOT NULL,
       notify text NOT NULL,
       suspect tinyint(1) default '0' NOT NULL,
       downloads int default '0' NOT NULL,
       pendingedit text NOT NULL,
       funds real default '0' NOT NULL,
       suspended tinyint(1) default '0' NOT NULL,
       recipwith text NOT NULL,
       alias int default '0' NOT NULL,
       expire int default '0' NOT NULL,
       ip text NOT NULL,
       inalbum tinyint(1) default '0' NOT NULL,
       typeorder int default '0' NOT NULL,
 UNIQUE KEY id (id)
   );
   
   create table {PREFIX}categories
 (
     id int default '0' NOT NULL auto_increment,
       name text NOT NULL,
       parent int default '0' NOT NULL,
       validated int default '0' NOT NULL,
       description text NOT NULL,
       time int default '0' NOT NULL,
       parentnames text NOT NULL,
       parentids text NOT NULL,
       numlinks int default '0' NOT NULL,
       hide int default '0' NOT NULL,
       lastlinktime int default '0' NOT NULL,
       custom text NOT NULL,
       lastedit text NOT NULL,
       moderators text NOT NULL,
       headerinfo text NOT NULL,
       related text NOT NULL,
       numsub int default '0' NOT NULL,
       type text NOT NULL,
       permissions text NOT NULL,
       subscribers text NOT NULL,
       subscriber text NOT NULL,
       mixtypes text NOT NULL,
       isalbum int default '0' NOT NULL,
       orderlinks text NOT NULL,
       totalcomments int default '0' NOT NULL,
 UNIQUE KEY id (id)
   );
   
   create table {PREFIX}comments
 (
     id int default '0' NOT NULL auto_increment,
       linkid int default '0' NOT NULL,
       posterid int default '0' NOT NULL,
       postername text NOT NULL,
       message text NOT NULL,
       time int default '0' NOT NULL,
       linkname text NOT NULL,
       validated int default '0' NOT NULL,
       ip text NOT NULL,
       ownerid int default '0' NOT NULL,
       lastedit text NOT NULL,
       type text NOT NULL,
       approved int default '0' NOT NULL,
       votes int default '0' NOT NULL,
       hide int default '0' NOT NULL,
       typeorder int default '0' NOT NULL,
 UNIQUE KEY id (id)
   );
   
   create table {PREFIX}settings
 (
     id int default '0' NOT NULL auto_increment,
       name text NOT NULL,
       content text NOT NULL,
 UNIQUE KEY id (id)
   );
   
   create table {PREFIX}members
 (
     id int default '0' NOT NULL auto_increment,
       name text NOT NULL,
       links int default '0' NOT NULL,
       comments int default '0' NOT NULL,
       time int default '0' NOT NULL,
       usergroup int default '0' NOT NULL,
       password text NOT NULL,
       ip text NOT NULL,
       totalhits int default '0' NOT NULL,
       totalhitsin int default '0' NOT NULL,
       email text NOT NULL,
       validated tinyint(1) default '0' NOT NULL,
       template text NOT NULL,
       language text NOT NULL,
       lastattempt text NOT NULL,
       allowemail text NOT NULL,
       signature text NOT NULL,
       avatarname text NOT NULL,
       stylesheet text NOT NULL,
       allowuseremail text NOT NULL,
       albumid int default '0' NOT NULL,
       failedattempts text NOT NULL,
       totalbytes int default '0' NOT NULL,
       images int default '0' NOT NULL,
 UNIQUE KEY id (id)
   );
   
   create table {PREFIX}membergroups
 (
     id int default '0' NOT NULL auto_increment,
       title text NOT NULL,
       caneditown tinyint(1) default '0' NOT NULL,
       caneditall tinyint(1) default '0' NOT NULL,
       canvote tinyint(1) default '0' NOT NULL,
       isadmin tinyint(1) default '0' NOT NULL,
       canpost tinyint(1) default '0' NOT NULL,
       canemail tinyint(1) default '0' NOT NULL,
       validatecats tinyint(1) default '0' NOT NULL,
       validatecomments tinyint(1) default '0' NOT NULL,
       validatelinks tinyint(1) default '0' NOT NULL,
       validateedits tinyint(1) default '0' NOT NULL,
       cansubmitlinks tinyint(1) default '0' NOT NULL,
       cansubmitcategories tinyint(1) default '0' NOT NULL,
       cansubmitcomments tinyint(1) default '0' NOT NULL,
       caneditownlinks tinyint(1) default '0' NOT NULL,
       caneditowncategories tinyint(1) default '0' NOT NULL,
       caneditowncomments tinyint(1) default '0' NOT NULL,
       caneditownprofile tinyint(1) default '0' NOT NULL,
       caneditalllinks tinyint(1) default '0' NOT NULL,
       caneditallcategories tinyint(1) default '0' NOT NULL,
       caneditallcomments tinyint(1) default '0' NOT NULL,
       caneditallprofiles tinyint(1) default '0' NOT NULL,
       candownloadfiles tinyint(1) default '0' NOT NULL,
       canupload tinyint(1) default '0' NOT NULL,
       canviewip tinyint(1) default '0' NOT NULL,
       limitlinks text NOT NULL,
       canemailmembers int default '0' NOT NULL,
       canusehtml int default '0' NOT NULL,
       limitlinksdaily text NOT NULL,
       canalias tinyint(1) default '0' NOT NULL,
       cancopy tinyint(1) default '0' NOT NULL,
       candeleteown tinyint(1) default '0' NOT NULL,
       candeleteall tinyint(1) default '0' NOT NULL,
 UNIQUE KEY id (id)
   );
   
   INSERT INTO {PREFIX}members(id,name,links,comments,time,usergroup,password,ip,totalhits,totalhitsin,email,validated,template,language,lastattempt,allowemail,signature,avatarname,stylesheet,allowuseremail,albumid,failedattempts,totalbytes,images) VALUES ('0', '{USERNAME}', '0', '0', '{TIME}', '3', '{PASSWORD}', '{IP}', '0', '0', '', '1', '{OURTEMPLATESDIR}', '{OURDEFAULTLANG}', '0', '0', '', '', 'default', '0', '0', '0', '0', '0');INSERT INTO {PREFIX}membergroups(id,title,caneditown,caneditall,canvote,isadmin,canpost,canemail,validatecats,validatecomments,validatelinks,validateedits,cansubmitlinks,cansubmitcategories,cansubmitcomments,caneditownlinks,caneditowncategories,caneditowncomments,caneditownprofile,caneditalllinks,caneditallcategories,caneditallcomments,caneditallprofiles,candownloadfiles,canupload,canviewip,limitlinks,canemailmembers,canusehtml,limitlinksdaily,canalias,cancopy,candeleteown,candeleteall) VALUES ('1','guest','0','0','1','0','0','0','1','1','1','0','1','1','1','0','0','0','0','0','0','0','0','1','1','0','0','0','0','','0','0','0','0'); 
INSERT INTO {PREFIX}membergroups(id,title,caneditown,caneditall,canvote,isadmin,canpost,canemail,validatecats,validatecomments,validatelinks,validateedits,cansubmitlinks,cansubmitcategories,cansubmitcomments,caneditownlinks,caneditowncategories,caneditowncomments,caneditownprofile,caneditalllinks,caneditallcategories,caneditallcomments,caneditallprofiles,candownloadfiles,canupload,canviewip,limitlinks,canemailmembers,canusehtml,limitlinksdaily,canalias,cancopy,candeleteown,candeleteall) VALUES ('2','member','0','0','1','0','0','1','1','0','1','1','1','1','1','1','0','1','1','0','0','0','0','1','1','0','','1','0','','0','0','1','0'); 
INSERT INTO {PREFIX}membergroups(id,title,caneditown,caneditall,canvote,isadmin,canpost,canemail,validatecats,validatecomments,validatelinks,validateedits,cansubmitlinks,cansubmitcategories,cansubmitcomments,caneditownlinks,caneditowncategories,caneditowncomments,caneditownprofile,caneditalllinks,caneditallcategories,caneditallcomments,caneditallprofiles,candownloadfiles,canupload,canviewip,limitlinks,canemailmembers,canusehtml,limitlinksdaily,canalias,cancopy,candeleteown,candeleteall) VALUES ('3','admin','0','0','1','1','0','1','0','0','0','0','1','1','1','1','1','1','1','1','1','1','1','1','1','1','','1','1','','1','1','1','1'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('2','linkcols','2'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('3','catcols','2'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('4','subcatcols','2'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('5','orderlinks','ORDER BY time DESC'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('6','ordercats','ORDER BY time DESC'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('7','email',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('8','notify','yes'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('9','myurl',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('10','perpage','14'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('11','marknew','7'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('12','dateformat','%x'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('13','locale','english'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('14','templatesdir','{OURTEMPLATESDIR}'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('17','excludedtoadmin',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('18','dirurl',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('98','adminbypass','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('20','commentsdateformat','%x - %X'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('21','debug','5'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('22','mixrecip','yes'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('23','searchfields','title,url,description'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('24','ordercomments','ORDER BY time ASC'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('25','condition',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('26','clicktimer','60'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('28','categoryselector',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('29','linktypes','regular,recip'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('30','allowhtml','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('31','defaultlang','{OURDEFAULTLANG}'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('32','languages','{OURLANGUAGES}'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('33','maxsubcats','10'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('34','bannedips',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('36','requiredlinks','title'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('37','requiredcategories','name'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('38','requiredcomments','message'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('39','requiredmembers','name,password'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('40','wsncodes','[url={PARAM}][,][/url][,]<a href={PARAM}>[,]</a>[,]Link text to a URL.[,]regular|||[url][,][/url][,]<a href={CONTENT}>[,]</a>[,]Create a link around a URL.[,]regular|||[font={PARAM}][,][/font][,]<font face={PARAM}>[,]</font>[,]Put text in a particular font.[,]regular|||[b][,][/b][,]<b>[,]</b>[,]Put text in bold.[,]regular|||[i][,][/i][,]<i>[,]</i>[,]Put text in italics.[,]regular'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('42','resetdelay','7'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('41','admindir','{OURADMINDIR}'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('46','registration','direct'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('45','floodcheck','20'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('43','resetfields',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('44','resettime','1068863122'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('48','ratingdecimal','2'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('49','uploadpath',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('50','filetypes','gif,jpg,jpeg,png,zip,gz,txt,lng,pdf'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('51','filesize','1000000'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('52','resetscript',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('53','smilies',':),<img src="{TEMPLATESDIR}/images/smilies/smile.gif" border="0">|||{semicolon}),<img src="{TEMPLATESDIR}/images/smilies/wink.gif" border="0">|||:(,<img src="{TEMPLATESDIR}/images/smilies/frown.gif" border="0">|||:confused:,<img src="{TEMPLATESDIR}/images/smilies/confused.gif" border="0">|||:cool:,<img src="{TEMPLATESDIR}/images/smilies/cool.gif" border="0">|||:D,<img src="{TEMPLATESDIR}/images/smilies/biggrin.gif" border="0">|||:eek:,<img src="{TEMPLATESDIR}/images/smilies/eek.gif" border="0">|||>(,<img src="{TEMPLATESDIR}/images/smilies/mad.gif" border="0">|||:eyebrow:,<img src="{TEMPLATESDIR}/images/smilies/eyebrow.gif" border="0">|||:no:,<img src="{TEMPLATESDIR}/images/smilies/no.gif" border="0">|||:nono:,<img src="{TEMPLATESDIR}/images/smilies/nono.gif" border="0">|||:nod:,<img src="{TEMPLATESDIR}/images/smilies/nod.gif" border="0">|||:rolleyes:,<img src="{TEMPLATESDIR}/images/smilies/rolleyes.gif" border="0">|||:p,<img src="{TEMPLATESDIR}/images/smilies/tongue.gif" border="0">'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('54','memberlistorder','ORDER BY time ASC'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('47','selectorlevels','100'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('55','expiretime','72'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('56','censor','fuck[,]****'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('57','integration',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('58','dontcount','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('59','maxvote','5'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('60','minvote','1'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('77','externallinks','target="_blank"'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('61','debittime','1069054575'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('62','sponsortype','none'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('63','sponsorlinktype','recip'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('64','sponsorcharge','0.30'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('65','totalhits','0'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('66','totalhitsin','0'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('69','sponsoritem','1'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('67','lastautocron','1069130701'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('68','totallinks','0'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('70','totalcomments','0'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('71','avatarsize','50000'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('72','avatarwidth','100'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('73','avatarheight','100'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('74','avatartypes','gif,jpg,jpeg,png'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('75','descriplength','1000'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('76','cattypes','regular'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('78','mainmeta',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('79','maplevels','10'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('80','sitemap',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('94','lastupdate','1068595508'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('95','backupfile',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('99','lastdaily','1069130564'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('87','marknewupdates','5'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('88','maximagewidth','150'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('89','maximageheight','150'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('90','mixcomments','yes'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('91','commenttypes','regular'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('92','orderlinks2','ORDER BY time DESC'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('93','apacherewrite','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('81','stylesheet','default'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('82','modapprove','yes'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('83','standardsize','30'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('84','secondsdelay','3'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('85','standardtable','cellspacing="6" cellpadding="6"'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('86','skiptocat',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('97','backupdelay',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('96','logsearches','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('101','totalmembers','1'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('103','searchperpage','14'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('102','compress','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('104','checkfordup','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('105','redirects','afterlink[,]|||aftercat[,]|||aftercom[,]comments.php?id={LINKID}|||afterreg[,]|||aftereditlink[,]|||aftereditcat[,]|||aftereditcom[,]|||aftereditprofile[,]|||afterlogin[,]|||afterlogout[,]|||afterresetpass[,]index.php?action=userlogin|||aftersubscribecom[,]comments.php?id={ID}|||aftersubscribecat[,]index.php?action=displaycat&catid={CATID}|||afterdellink[,]|||afterdelcat[,]../index.php?action=displaycat&catid={PARENT}|||afterdelcom[,]'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('106','fixedwidth',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('107','checkfordupdomain','no'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('109','checkoutnum',''); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('108','totalmembers','1'); 
INSERT INTO {PREFIX}settings(id,name,content) VALUES ('110','uniquetotal','1'); 

CREATE INDEX wsnindex ON {PREFIX}links (catid,time(5),time(5));
CREATE INDEX wsnindex ON {PREFIX}categories (time(5));
CREATE INDEX wsnindex ON {PREFIX}comments (linkid,time(5));
CREATE INDEX wsnindex ON {PREFIX}members (id,time(5));
CREATE INDEX wsnindex ON {PREFIX}membergroups (id);