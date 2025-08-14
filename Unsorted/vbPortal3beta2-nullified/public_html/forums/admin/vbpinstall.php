<?php
error_reporting(7);

function gotonext($extra="") {
	global $step,$thisscript;
	$nextstep = $step+1;
	echo "<p>$extra</p>\n";
	echo("<p><a href=\"$thisscript?step=$nextstep\"><b>Click here to continue --&gt;</b></a></p>\n");
}

require ("./global.php");

?>
<HTML><HEAD>
<META content="text/html; charset=windows-1252" http-equiv=Content-Type>
<META content="MSHTML 5.00.3018.900" name=GENERATOR></HEAD>
<link rel="stylesheet" href="../cp.css">
<title>vbPortal installation script</title>
</HEAD>
<BODY>
<table width="100%" bgcolor="#3F3849" cellpadding="2" cellspacing="0" border="0"><tr><td>
<table width="100%" bgcolor="#524A5A" cellpadding="3" cellspacing="0" border="0"><tr>
<td><a href="http://www.phpportals.com/" target="_blank"><img src="cp_logo.gif" width="160" height="49" border="0" alt="Click here to visit the support forums."></a></td>
<td width="100%" align="center">
<p><font size="2" color="#F7DE00"><b>vbPortal 3.0 install script</b></font></p>
<p><font size="1" color="#F7DE00"><b>This should only take a few minutes to complete</b></font></p>
</td></tr></table></td></tr></table>
<br>
<?php

if (!$step) {
  $step = 1;
}

// ******************* STEP 1 *******************
if ($step==1) {
  ?>
 <br>This script will guide you through the necessary changes to do a new install of vbPortal 3.0 on your vBulletin site.
 <br> 
 <p>If you have previously installed vbPortal 2.xx You will have to un-install first.<br>
 For obvious reasons this will not un-install the old weblinks or downloads tables.<br>
 If you wish to un-install vbPortal 2.x you may do so now by <a href=./vbpinstall.php?step=7 target=_self><b>Clicking here --&gt;</B></a></b></p>
	  </p>
 <br> 
 <p>ABORT THIS INSTALL BY <a href=./vbpinstall.php?step=6 target=_self><b>CLICKING HERE --&gt;</B></a></b>
 <br><br>
 <p><b>Option 1: vbPortal Lite...</b>
  Select this Option to install all vbPortal except the topics option.<br>
  continue the Lite install option by <a href=./vbpinstall.php?step=3 target=_self><b>Clicking here --&gt;</B></a></b></p>
 
 <p><b>Option 2: Full Install</b>
 This Option will do the full install of vbPortal including topics.<br>
 Installing with this option also requires that you modify the vBulletin scripts.<br>
 You may install the Lite version and install the topics option here at at later date.<br>
 Continue with the Full Install including topics by <a href=./vbpinstall.php?step=2 target=_self><b>Clicking here --&gt;</B></a></b></p>
 
 <p><b>Option 3: Install Topics</b>
 This will install the vbPortal topics.<br>
 Installing with this option also requires that you modify the vBulletin scripts.<br>
 Continue installing topics by <a href=./vbpinstall.php?step=9 target=_self><b>Clicking here --&gt;</B></a></b></p>
 
 <p><b>Option 4: Un-Install topics</b>
 This will un-install vbPortal 3.0 topics<br>
 If you installed topics by mistake, or just want to remove the option.<br>
 Un-Install by <a href=./vbpinstall.php?step=13 target=_self><b>Clicking here --&gt;</B></a></b></p>
 
 <p><b>Option 5: Un-Install vbPortal 3.0</b>
 This will un-install vbPortal 3.0<br>
 If you decide you would rather work without vbPortal 3.0.<br>
 Un-Install by <a href=./vbpinstall.php?step=10 target=_self><b>Clicking here --&gt;</B></a></b></p>
 
 <?php
 }

// ******************* STEP 2 *******************
if ($step==2) {

  echo("<br><b>Modifying the 'post' and 'search' tables</b><br>");
// begin modification to the "post' table
  $DB_site->query("ALTER TABLE post ADD topic int(3) DEFAULT '0' NOT NULL AFTER editdate");
// end modification to "post"

// begin modification to the "search' table
  $DB_site->query("ALTER TABLE search ADD topic int(3) DEFAULT '0' NOT NULL AFTER ipaddress");
// end modification to "search"


echo ("<b>Adding the 'vbPortal' topics table</b><br>");

$DB_site->query("CREATE TABLE nuke_topics (
   topicid int(3) NOT NULL auto_increment,
   topicname varchar(20) NOT NULL,
   topicimage varchar(20) NOT NULL,
   topictext varchar(40) NOT NULL,
   counter int(11) NOT NULL,
   PRIMARY KEY (topicid)
)");

$DB_site->query("INSERT INTO nuke_topics VALUES (1, 'News', 'news.gif', 'News', 0)");
$DB_site->query("INSERT INTO nuke_topics VALUES (2, 'Info', 'info.gif', 'Information', 0)");
$DB_site->query("INSERT INTO nuke_topics VALUES (3, 'Misc', 'misc.gif', 'Miscellanious', 0)");

gotonext();
}
// ******************* STEP 3 *******************
if ($step==3) {
echo ("<b>Adding one index and the 'vbPortal' tables</b><br>");

$DB_site->query("ALTER TABLE thread add index (pollid)");

$DB_site->query("CREATE TABLE nuke_advblocks (
   bid int(10) unsigned NOT NULL auto_increment,
   bkey varchar(255) NOT NULL,
   title varchar(255) NOT NULL,
   content text NOT NULL,
   url varchar(255) NOT NULL,
   position char(1) DEFAULT 'l' NOT NULL,
   weight decimal(10,1) DEFAULT '0.0' NOT NULL,
   active tinyint(3) unsigned DEFAULT '1' NOT NULL,
   refresh int(10) unsigned NOT NULL,
   last_update timestamp(14) NOT NULL,
   templates int(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (bid)
)");

$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','main','Main Menu','<strong><big>·</big></strong> <a href=\"/index.php\">Home</a><br>
<strong><big>·</big></strong> <a href=\"/forums/index.php\">Forums</a><br>
<strong><big>·</big></strong> <a href=\"/topics.php\">Topics</a><br>
<strong><big>·</big></strong> <a href=\"/friend.php\">Recommend Us</a><br>
<strong><big>·</big></strong> <a href=\"/reviews.php\">Reviews</a><br>
<strong><big>·</big></strong> <a href=\"/links.php\">Web Links</a><br>
<strong><big>·</big></strong> <a href=\"/stats.php\">Stats</a><br>
<strong><big>·</big></strong> <a href=\"/top.php\">Top 10</a><br>
<strong><big>·</big></strong> <a href=\"/download.php\">Downloads</a><br>','','l','0.5','1','0','20010712164939','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','online','Who\'s Online','','','l','8.0','1','0','20010701164256','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','search','Search Box','','','r','1.0','1','0','20010701163405','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','ephem','Ephemerids','','','l','10.0','1','0','20010712220956','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','category','News Categories','','','r','2.0','1','0','00000000000000','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','calendar','Calendar','','','l','4.0','1','0','00000000000000','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','poll','Survey','','','r','4.0','1','0','00000000000000','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','login','User\'s Login','','','r','2.0','1','0','20010701163405','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','past','Past Articles','','','r','6.0','1','0','20010701115244','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','php','Example PHP Block','print strftime(\'%A, %B %e, %Y %I:%M %p %Z\');','','r','8.0','1','0','00000000000000','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','showbirthdays','Today\'s Birthdays','','','l','7.0','1','0','20010701164256','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','activetopics','Active Topics','','','r','7.0','1','0','20010710070842','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','pminfo','Private Messages','','','l','9.0','1','0','20010701164240','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','showevents','Todays Events','','','l','5.0','1','0','20010701163345','1')");
$DB_site->query("INSERT INTO nuke_advblocks VALUES('NULL','admin','Administration','<!-- This box will appear only if your
logged in as Admin. Use the Full URL for entering hyperlinks -->
<strong><big>·</big></strong> <a href=\"/admin.php\">Administration</a><br>
<strong><big>·</big></strong> <a href=\"/admin.php?op=logout\">Logout</a><br>
<strong><big>·</big></strong> <a href=\"/forums/admin/index.php\">VB Admin</a><br>
','','l','2.0','1','0','00000000000000','1')");

$DB_site->query("CREATE TABLE nuke_centerblocks (
   bid int(10) NOT NULL auto_increment,
   bkey varchar(15) NOT NULL,
   title varchar(60) NOT NULL,
   content text NOT NULL,
   url varchar(200) NOT NULL,
   position char(1) NOT NULL,
   weight int(10) DEFAULT '1' NOT NULL,
   active int(1) DEFAULT '1' NOT NULL,
   refresh int(10) NOT NULL,
   last_update timestamp(14) NOT NULL,
   templates int(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (bid)
)");

$DB_site->query("INSERT INTO nuke_centerblocks VALUES('NULL','news','The News','','','l','3','1','0','20010712231207','1')");
$DB_site->query("INSERT INTO nuke_centerblocks VALUES('NULL','downloads','Downloads','','','l','4','0','0','20010712231202','1')");
$DB_site->query("INSERT INTO nuke_centerblocks VALUES('NULL','links','Links','','','l','6','0','0','20010708225027','1')");
$DB_site->query("INSERT INTO nuke_centerblocks VALUES('NULL','messagebox','Message Box','','','l','1','1','0','20010712231218','1')");
$DB_site->query("INSERT INTO nuke_centerblocks VALUES('NULL','activetopics','Active Topics','','','l','5','1','0','20010712231202','1')");


$DB_site->query("CREATE TABLE nuke_forumblocks (
   bid int(10) unsigned NOT NULL auto_increment,
   bkey varchar(255) NOT NULL,
   title varchar(255) NOT NULL,
   content text NOT NULL,
   url varchar(255) NOT NULL,
   position char(1) DEFAULT 'l' NOT NULL,
   weight decimal(10,1) DEFAULT '0.0' NOT NULL,
   active tinyint(3) unsigned DEFAULT '1' NOT NULL,
   refresh int(10) unsigned NOT NULL,
   last_update timestamp(14) NOT NULL,
   templates int(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (bid)
)");

$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','forum','Forum Menu','<strong><big>·</big></strong> <a href=\"../index.php\">Home</a><br>
<strong><big>·</big></strong> <a href=\"../topics.php\">Topics</a><br>
<strong><big>·</big></strong> <a href=\"../sections.php\">Sections</a><br>
<strong><big>·</big></strong> <a href=\"../reviews.php\">Reviews</a><br>
<strong><big>·</big></strong> <a href=\"../stats.php\">Stats</a><br>
<strong><big>·</big></strong> <a href=\"../top.php\">Top 10</a><br>
<strong><big>·</big></strong> <a href=\"../links.php\">Web Links</a><br>
<strong><big>·</big></strong> <a href=\"../download.php\">Downloads</a><br>','','l','0.5','1','0','00000000000000','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','search','Search Box','','','l','1.0','0','0','20010713084100','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','ephem','Ephemerids','','','l','10.0','0','0','20010713084132','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','calendar','Calendar','','','l','4.0','1','0','20010713084303','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','poll','Survey','','','l','4.0','0','0','20010713084110','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','showbirthdays','Today\'s Birthdays','','','l','7.0','0','0','20010713084121','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','activetopics','Active Topics','','','l','7.0','0','0','20010713084124','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','showevents','Todays Events','','','l','5.0','0','0','20010713084115','1')");
$DB_site->query("INSERT INTO nuke_forumblocks VALUES('NULL','admin','Administration','<!-- This box will appear only if your
logged in as Admin. Use the Full URL for entering hyperlinks -->
<strong><big>·</big></strong> <a href=\"../admin.php\">Administration</a><br>
<strong><big>·</big></strong> <a href=\"../admin.php?op=logout\">Logout</a><br>
<strong><big>·</big></strong> <a href=\"admin/index.php\">VB Admin</a><br>
','','l','2.0','1','0','00000000000000','1')");

$DB_site->query("CREATE TABLE nuke_advheadlines (
   id int(10) unsigned NOT NULL auto_increment,
   sitename varchar(255) NOT NULL,
   rssurl varchar(255) NOT NULL,
   siteurl varchar(255) NOT NULL,
   PRIMARY KEY (id)
)");

$DB_site->query("INSERT INTO nuke_advheadlines VALUES('1','AbsoluteGames','http://files.gameaholic.com/agfa.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('2','AppWatch','http://static.appwatch.com/appwatch.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('3','BrunchingShuttlecocks','http://www.brunching.com/brunching.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('4','BSDToday','http://www.bsdtoday.com/backend/bt.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('5','DailyDaemonNews','http://daily.daemonnews.org/ddn.rdf.php3','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('6','DigitalTheatre','http://www.dtheatre.com/backend.php3?xml=yes','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('7','DotKDE','http://dot.kde.org/rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('8','DrDobbsTechNetCast','http://www.technetcast.com/tnc_headlines.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('9','exoScience','http://www.exosci.com/exosci.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('10','FreakTech','http://sunsite.auc.dk/FreakTech/FreakTech.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('11','Freshmeat','http://freshmeat.net/backend/fm.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('12','GeekNik','http://www.geeknik.net/backend/weblog.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('13','Gnotices','http://news.gnome.org/gnome-news/rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('14','HappyPenguin','http://happypenguin.org/html/news.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('15','HollywoodBitchslap','http://hollywoodbitchslap.com/hbs.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('16','HotWired','http://www.hotwired.com/webmonkey/meta/headlines.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('17','JustLinux','http://www.justlinux.com/backend/features.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('18','KDE','http://www.kde.org/news/kdenews.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('19','LAN-Systems','http://www.lansystems.com/backend/gazette_news_backend.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('20','Lin-x-pert','http://www.lin-x-pert.com/linxpert_apps.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('21','Linux.com','http://linux.com/mrn/front_page.rss','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('22','Linux.nu','http://www.linux.nu/backend/lnu.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('23','LinuxCentral','http://linuxcentral.com/backend/lcnew.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('24','Linuxdev.net','http://linuxdev.net/archive/news.cdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('25','LinuxM68k','http://www.linux-m68k.org/linux-m68k.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('26','LinuxNewbie','http://www.linuxnewbie.org/news.cdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('27','Linuxpower','http://linuxpower.org/linuxpower.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('28','LinuxPreview','http://linuxpreview.org/backend.php3','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('29','LinuxWeelyNews','http://lwn.net/headlines/rss','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('30','Listology','http://listology.com/recent.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('31','MaximumBSD1','http://www.maximumbsd.com/backend/weblog.rdf1','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('32','MicroUnices','http://mu.current.nu/mu.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('33','MozillaNewsBot','http://www.mozilla.org/newsbot/newsbot.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('34','NewsForge','http://www.newsforge.com/newsforge.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('35','NewsTrolls','http://newstrolls.com/newstrolls.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('36','PBSOnline','http://cgi.pbs.org/cgi-registry/featuresrdf.pl','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('37','PDABuzz','http://www.pdabuzz.com/netscape.txt','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('38','Perl.com','http://www.perl.com/pace/perlnews.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('39','PerlMonks','http://www.perlmonks.org/headlines.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('40','PerlNews','http://news.perl.org/perl-news-short.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('41','PHP-Nuke','http://phpnuke.org/backend.php','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('42','PHPBuilder','http://phpbuilder.com/rss_feed.php','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('43','Protest.net','http://www.protest.net/netcenter_rdf.cgi','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('44','RivaExtreme','http://rivaextreme.com/ssi/rivaextreme.rdf.cdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('45','SciFi-News','http://www.technopagan.org/sf-news/rdf.php','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('46','Segfault','http://segfault.org/stories.xml','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('47','SisterMachineGun','http://www.smg.org/index/mynetscape.html','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('48','Slashdot','http://slashdot.org/slashdot.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('49','SolarisCentral','http://www.SolarisCentral.org/news/SolarisCentral.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('50','Technocrat','http://technocrat.net/rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('51','Themes.org','http://www.themes.org/news.rdf.phtml','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('52','TheNextLevel','http://www.the-nextlevel.com/rdf/tnl.rdf','')");
$DB_site->query("INSERT INTO nuke_advheadlines VALUES('53','DrDobbs','http://www.technetcast.com/tnc_headlines.rdf','')");


$DB_site->query("CREATE TABLE nuke_banner (
   bid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   imptotal int(11) NOT NULL,
   impmade int(11) NOT NULL,
   clicks int(11) NOT NULL,
   imageurl varchar(100) NOT NULL,
   clickurl varchar(200) NOT NULL,
   date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   PRIMARY KEY (bid)
)");

$DB_site->query("INSERT INTO nuke_banner VALUES('2','1','0','11756','23','http://www.qksrv.net/image-694929-804494','http://www.qksrv.net/click-694929-804494','2001-06-29 17:01:12')");
$DB_site->query("INSERT INTO nuke_banner VALUES('3','2','0','141','2','http://www.qksrv.net/image-801566-5042834','http://www.qksrv.net/click-801566-5042834','2001-07-13 21:23:06')");


$DB_site->query("CREATE TABLE nuke_bannerclient (
   cid int(11) NOT NULL auto_increment,
   name varchar(60) NOT NULL,
   contact varchar(60) NOT NULL,
   email varchar(60) NOT NULL,
   login varchar(10) NOT NULL,
   passwd varchar(10) NOT NULL,
   extrainfo text NOT NULL,
   PRIMARY KEY (cid)
)");


$DB_site->query("CREATE TABLE nuke_bannerfinish (
   bid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   impressions int(11) NOT NULL,
   clicks int(11) NOT NULL,
   datestart datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   dateend datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   PRIMARY KEY (bid)
)");


$DB_site->query("CREATE TABLE nuke_counter (
   type varchar(80) NOT NULL,
   var varchar(80) NOT NULL,
   count int(10) unsigned NOT NULL
)");

$DB_site->query("INSERT INTO nuke_counter VALUES('total','hits','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','WebTV','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','Lynx','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','MSIE','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','Opera','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','Konqueror','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','Netscape','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','Bot','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('browser','Other','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','Windows','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','Linux','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','Mac','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','FreeBSD','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','SunOS','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','IRIX','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','BeOS','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','OS/2','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','AIX','0')");
$DB_site->query("INSERT INTO nuke_counter VALUES('os','Other','0')");


$DB_site->query("CREATE TABLE nuke_downloads_categories (
   cid int(11) NOT NULL auto_increment,
   title varchar(50) NOT NULL,
   cdescription text NOT NULL,
   PRIMARY KEY (cid)
)");


$DB_site->query("CREATE TABLE nuke_downloads_downloads (
   lid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   sid int(11) NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   date datetime NOT NULL,
   name varchar(100) NOT NULL,
   email varchar(100) NOT NULL,
   hits int(11) NOT NULL,
   submitter varchar(60) NOT NULL,
   downloadratingsummary double(6,4) DEFAULT '0.0000' NOT NULL,
   totalvotes int(11) NOT NULL,
   totalcomments int(11) NOT NULL,
   filesize int(11) NOT NULL,
   version varchar(10) NOT NULL,
   homepage varchar(200) NOT NULL,
   PRIMARY KEY (lid)
)");


$DB_site->query("CREATE TABLE nuke_downloads_editorials (
   downloadid int(11) NOT NULL,
   adminid varchar(60) NOT NULL,
   editorialtimestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   editorialtext text NOT NULL,
   editorialtitle varchar(100) NOT NULL,
   PRIMARY KEY (downloadid)
)");


$DB_site->query("CREATE TABLE nuke_downloads_modrequest (
   requestid int(11) NOT NULL auto_increment,
   lid int(11) NOT NULL,
   cid int(11) NOT NULL,
   sid int(11) NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   modifysubmitter varchar(60) NOT NULL,
   brokendownload int(3) NOT NULL,
   name varchar(100) NOT NULL,
   email varchar(100) NOT NULL,
   filesize int(11) NOT NULL,
   version varchar(10) NOT NULL,
   homepage varchar(200) NOT NULL,
   PRIMARY KEY (requestid),
   KEY requestid (requestid)
)");


$DB_site->query("CREATE TABLE nuke_downloads_newdownload (
   lid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   sid int(11) NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   name varchar(100) NOT NULL,
   email varchar(100) NOT NULL,
   submitter varchar(60) NOT NULL,
   filesize int(11) NOT NULL,
   version varchar(10) NOT NULL,
   homepage varchar(200) NOT NULL,
   PRIMARY KEY (lid)
)");


$DB_site->query("CREATE TABLE nuke_downloads_subcategories (
   sid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   title varchar(50) NOT NULL,
   PRIMARY KEY (sid)
)");


$DB_site->query("CREATE TABLE nuke_downloads_votedata (
   ratingdbid int(11) NOT NULL auto_increment,
   ratinglid int(11) NOT NULL,
   ratinguser varchar(60) NOT NULL,
   rating int(11) NOT NULL,
   ratinghostname varchar(60) NOT NULL,
   ratingcomments text NOT NULL,
   ratingtimestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   PRIMARY KEY (ratingdbid)
)");


$DB_site->query("CREATE TABLE nuke_ephem (
   eid int(11) NOT NULL auto_increment,
   did int(2) NOT NULL,
   mid int(2) NOT NULL,
   yid int(4) NOT NULL,
   content text NOT NULL,
   PRIMARY KEY (eid)
)");

$DB_site->query("INSERT INTO nuke_ephem VALUES('4','12','6','2001','This is a day in History')");


$DB_site->query("CREATE TABLE nuke_faqAnswer (
   id tinyint(4) NOT NULL auto_increment,
   id_cat tinyint(4) NOT NULL,
   question varchar(255) NOT NULL,
   answer text NOT NULL,
   PRIMARY KEY (id)
)");

$DB_site->query("INSERT INTO nuke_faqAnswer VALUES('1','1','Can You see This','I hope!')");


$DB_site->query("CREATE TABLE nuke_faqCategories (
   id_cat tinyint(3) NOT NULL auto_increment,
   categories varchar(255) NOT NULL,
   PRIMARY KEY (id_cat)
)");

$DB_site->query("INSERT INTO nuke_faqCategories VALUES('1','Test')");


$DB_site->query("CREATE TABLE nuke_links_categories (
   cid int(11) NOT NULL auto_increment,
   title varchar(50) NOT NULL,
   cdescription text NOT NULL,
   PRIMARY KEY (cid)
)");


$DB_site->query("CREATE TABLE nuke_links_editorials (
   linkid int(11) NOT NULL,
   adminid varchar(60) NOT NULL,
   editorialtimestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   editorialtext text NOT NULL,
   editorialtitle varchar(100) NOT NULL,
   PRIMARY KEY (linkid)
)");

$DB_site->query("CREATE TABLE nuke_links_links (
   lid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   sid int(11) NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   name varchar(60) NOT NULL,
   email varchar(60) NOT NULL,
   hits int(11) NOT NULL,
   submitter varchar(60) NOT NULL,
   linkratingsummary double(6,4) DEFAULT '0.0000' NOT NULL,
   totalvotes int(11) NOT NULL,
   totalcomments int(11) NOT NULL,
   PRIMARY KEY (lid)
)");


$DB_site->query("CREATE TABLE nuke_links_modrequest (
   requestid int(11) NOT NULL auto_increment,
   lid int(11) NOT NULL,
   cid int(11) NOT NULL,
   sid int(11) NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   modifysubmitter varchar(60) NOT NULL,
   brokenlink int(3) NOT NULL,
   PRIMARY KEY (requestid),
   KEY requestid (requestid)
)");


$DB_site->query("CREATE TABLE nuke_links_newlink (
   lid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   sid int(11) NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   name varchar(60) NOT NULL,
   email varchar(60) NOT NULL,
   submitter varchar(60) NOT NULL,
   PRIMARY KEY (lid)
)");


$DB_site->query("CREATE TABLE nuke_links_subcategories (
   sid int(11) NOT NULL auto_increment,
   cid int(11) NOT NULL,
   title varchar(50) NOT NULL,
   PRIMARY KEY (sid)
)");


$DB_site->query("CREATE TABLE nuke_links_votedata (
   ratingdbid int(11) NOT NULL auto_increment,
   ratinglid int(11) NOT NULL,
   ratinguser varchar(60) NOT NULL,
   rating int(11) NOT NULL,
   ratinghostname varchar(60) NOT NULL,
   ratingcomments text NOT NULL,
   ratingtimestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   PRIMARY KEY (ratingdbid)
)");


$DB_site->query("CREATE TABLE nuke_message (
   title varchar(100) NOT NULL,
   content text NOT NULL,
   date varchar(14) NOT NULL,
   expire int(7) NOT NULL,
   active int(1) DEFAULT '1' NOT NULL,
   view int(1) DEFAULT '1' NOT NULL
)");

$DB_site->query("INSERT INTO nuke_message VALUES('Welcome to  vbPortal 3.0 Beta Release','<font size = \"2\"><b>This is phpPortals hack vbPortal 3.0b , the Content Management System for vBulletin 2.X For more information please visit <a href=\"http://www.phpportals.com\">phpPortals</a></b></font>','993177178','2592000','1','1')");


$DB_site->query("CREATE TABLE nuke_referer (
   rid int(11) NOT NULL auto_increment,
   url varchar(100) NOT NULL,
   PRIMARY KEY (rid)
)");


$DB_site->query("CREATE TABLE nuke_reviews (
   id int(10) NOT NULL auto_increment,
   date date DEFAULT '0000-00-00' NOT NULL,
   title varchar(150) NOT NULL,
   text text NOT NULL,
   reviewer varchar(20) NOT NULL,
   email varchar(60) NOT NULL,
   score int(10) NOT NULL,
   cover varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   url_title varchar(50) NOT NULL,
   hits int(10) NOT NULL,
   PRIMARY KEY (id)
)");


$DB_site->query("CREATE TABLE nuke_reviews_add (
   id int(10) NOT NULL auto_increment,
   date date DEFAULT '0000-00-00' NOT NULL,
   title varchar(150) NOT NULL,
   text text NOT NULL,
   reviewer varchar(20) NOT NULL,
   email varchar(60) NOT NULL,
   score int(10) NOT NULL,
   url varchar(100) NOT NULL,
   url_title varchar(50) NOT NULL,
   PRIMARY KEY (id)
)");


$DB_site->query("CREATE TABLE nuke_reviews_comments (
   cid int(10) NOT NULL auto_increment,
   rid int(10) NOT NULL,
   userid varchar(25) NOT NULL,
   date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   comments text NOT NULL,
   score int(10) NOT NULL,
   PRIMARY KEY (cid)
)");


$DB_site->query("CREATE TABLE nuke_reviews_main (
   title varchar(100) NOT NULL,
   description text NOT NULL
)");

$DB_site->query("INSERT INTO nuke_reviews_main VALUES('Reviews Section Title','Reviews Section Long Description')");

$DB_site->query("CREATE TABLE nuke_seccont (
  artid int(11) NOT NULL auto_increment,
  secid int(11) NOT NULL default '0',
  title text NOT NULL,
  content text NOT NULL,
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (artid)
)");



$DB_site->query("CREATE TABLE nuke_sections (
  secid int(11) NOT NULL auto_increment,
  secname varchar(40) NOT NULL default '',
  image varchar(50) NOT NULL default '',
  PRIMARY KEY  (secid)
)");



  gotonext();
}


if ($step==4) {
  ?>
 <p>You have completed the install of the vbPortal tables on your vBulletin site.</p>
 <p>You may now procede to the script and style changes by <a href=./vbpinstall.php?step=5 target=_self><b>Clicking here --&gt;</B></a></b>
 <br>
 <?php
}

if ($step==5) {
  echo("<br>Click<a href=vbpscripts.html target=_blank><b> here</b></a> for instructions on the vB script mods needed.<br>");
  echo("<br>Don't forget to delete the existing and upload the new default.style. You can go to the vBulletin CP <b><a href=index.php target=_blank>here</a></b>");
}
if ($step==6) {
 ?>
 <p>Thank you for trying vbPortal.</p>
 <br>
 <?php
}

if ($step==7) {
  ?>
 <p>This script will un-install the vbPortal 2.x database changes from your vBulletin site.<br><br>
 <b>Prvious versions of Downloads or WebLinks will not be un-installed for obvious reasons.</b><br>
 <p><b>This is provided "as-is" without warranty or support</b></p>
 <br> 
 <p>ABORT THIS UN-INSTALL BY <a href=./vbpinstall.php?step=6 target=_self><b>CLICKING HERE --&gt;</B></a></b>
 <br><br>
 <p>Continue with the un-install by <a href=./vbpinstall.php?step=8 target=_self><b>Clicking here --&gt;</B></a></b></p>
 <br><br>

 <?php

}

if ($step==8) {

  $DB_site->query("DROP TABLE addon");
  $DB_site->query("ALTER TABLE post DROP topic");
  $DB_site->query("ALTER TABLE user DROP showleftcolumn");
  $DB_site->query("DROP TABLE P_bblock");
  $DB_site->query("DROP TABLE P_lblocks");
  $DB_site->query("DROP TABLE P_mblock");
  $DB_site->query("DROP TABLE P_rblocks");
  $DB_site->query("DROP TABLE menu");
  $DB_site->query("DROP TABLE P_headlines");
  $DB_site->query("DROP TABLE topics");
  
?>
<br>
<b>You have completed the vBPortal 2.x Database un-install.</b><br><br>
 You may return to the vbPortal 3.0 install by <a href=./vbpinstall.php?step=1 target=_self><b>Clicking here --&gt;</B></a>
 <br>
<?
}

// ******************* STEP 9 *******************
if ($step==9) {

// begin modification to the "post' table
  $DB_site->query("ALTER TABLE post ADD topic int(3) DEFAULT '0' NOT NULL AFTER editdate");
// end modification to "post"

// begin modification to the "search' table
  $DB_site->query("ALTER TABLE search ADD topic int(3) DEFAULT '0' NOT NULL AFTER ipaddress");
// end modification to "search"



$DB_site->query("CREATE TABLE nuke_topics (
   topicid int(3) NOT NULL auto_increment,
   topicname varchar(20) NOT NULL,
   topicimage varchar(20) NOT NULL,
   topictext varchar(40) NOT NULL,
   counter int(11) NOT NULL,
   PRIMARY KEY (topicid)
)");

$DB_site->query("INSERT INTO nuke_topics VALUES (1, 'News', 'news.gif', 'News', 0)");
$DB_site->query("INSERT INTO nuke_topics VALUES (2, 'Info', 'info.gif', 'Information', 0)");
$DB_site->query("INSERT INTO nuke_topics VALUES (3, 'Misc', 'misc.gif', 'Miscellanious', 0)");
?>
<br>
<p><b>Done making alterations for topics and installing the table.</b>
<br> 
<p>EXIT  BY <a href=./vbpinstall.php?step=6 target=_self><b>CLICKING HERE --&gt;</B></a></b>
<br><br>
<p>or Return to the install procedure by <a href=./vbpinstall.php?step=1 target=_self><b>Clicking here --&gt;</B></a></b></p>
<?php
}


// ******************* STEP 10 *******************
if ($step==10) {
  ?>
 <p>This script will remove vbPortal 3.0 from your vBulletin site.</p>
  <br> 
 <p>ABORT THE UN-INSTALL BY <a href=./vbpinstall.php?step=6 target=_self><b>CLICKING HERE --&gt;</B></a></b>
 <br>
 <p>Continue with the uninstall of 3.0 by <a href=./vbpinstall.php?step=11 target=_self><b>Clicking here --&gt;</B></a></b>
 <br>
 <?php
 }



// ******************* STEP 11 *******************
if ($step==11) {
    echo("<br><b>Modifying the tables</b><br>");
  // modification to the "post' table
   $DB_site->query("ALTER TABLE post DROP topic");
  // modification to the "search' table
   $DB_site->query("ALTER TABLE search DROP topic");
  
// Dropping the 34 Nuke tables
$DB_site->query("DROP TABLE IF EXISTS nuke_advblocks");
$DB_site->query("DROP TABLE IF EXISTS nuke_centerblocks");
$DB_site->query("DROP TABLE IF EXISTS nuke_forumblocks");
$DB_site->query("DROP TABLE IF EXISTS nuke_advheadlines");
$DB_site->query("DROP TABLE IF EXISTS nuke_banner");
$DB_site->query("DROP TABLE IF EXISTS nuke_bannerclient");
$DB_site->query("DROP TABLE IF EXISTS nuke_bannerfinish");
$DB_site->query("DROP TABLE IF EXISTS nuke_counter");
$DB_site->query("DROP TABLE IF EXISTS nuke_downloads_categories");
$DB_site->query("DROP TABLE IF EXISTS nuke_downloads_downloads");
$DB_site->query("DROP TABLE IF EXISTS nuke_downloads_editorials");
$DB_site->query("DROP TABLE IF EXISTS nuke_downloads_modrequest");
$DB_site->query("DROP TABLE IF EXISTS nuke_downloads_newdownload");
$DB_site->query("DROP TABLE IF EXISTS nuke_downloads_subcategories");
$DB_site->query("DROP TABLE IF EXISTS nuke_downloads_votedata");
$DB_site->query("DROP TABLE IF EXISTS nuke_ephem");
$DB_site->query("DROP TABLE IF EXISTS nuke_faqAnswer");
$DB_site->query("DROP TABLE IF EXISTS nuke_faqCategories");
$DB_site->query("DROP TABLE IF EXISTS nuke_links_categories");
$DB_site->query("DROP TABLE IF EXISTS nuke_links_editorials");
$DB_site->query("DROP TABLE IF EXISTS nuke_links_links");
$DB_site->query("DROP TABLE IF EXISTS nuke_links_modrequest");
$DB_site->query("DROP TABLE IF EXISTS nuke_links_newlink");
$DB_site->query("DROP TABLE IF EXISTS nuke_links_subcategories");
$DB_site->query("DROP TABLE IF EXISTS nuke_links_votedata");
$DB_site->query("DROP TABLE IF EXISTS nuke_message");
$DB_site->query("DROP TABLE IF EXISTS nuke_referer");
$DB_site->query("DROP TABLE IF EXISTS nuke_reviews");
$DB_site->query("DROP TABLE IF EXISTS nuke_reviews_add");
$DB_site->query("DROP TABLE IF EXISTS nuke_reviews_comments");
$DB_site->query("DROP TABLE IF EXISTS nuke_reviews_main");
$DB_site->query("DROP TABLE IF EXISTS nuke_seccont");
$DB_site->query("DROP TABLE IF EXISTS nuke_sections");
$DB_site->query("DROP TABLE IF EXISTS nuke_topics");

gotonext();
}


if ($step==12) {
  
 echo "<br>You have completed the uninstall of the vbPortal 3.0 tables from your vBulletin site.<br>";
 echo "Don't forget to delete the existing vbportal default.style and revert you templates.<br>";
 echo "you can go to vBulletin Control Panel <b><a href=index.php target=_self>here</a></b><br>";

}

// ******************* STEP 13 *******************
if ($step==13) {
    echo("<br><b>Modifying the tables</b><br>");
  // modification to the "post' table
   $DB_site->query("ALTER TABLE post DROP topic");
  // modification to the "search' table
   $DB_site->query("ALTER TABLE search DROP topic");
  
// Dropping the topics table
$DB_site->query("DROP TABLE IF EXISTS nuke_topics");

gotonext();
}


if ($step==14) {
  
 echo "<br>You have completed the uninstall of the vbPortal topics table from your vBulletin site.<br>";
 echo "Don't forget to remove the mods from the newthread.php, editpost.php and search.php scripts<br>";
 echo "EXIT  BY <a href=./vbpinstall.php?step=6 target=_self><b>CLICKING HERE --&gt;</B></a></b>";
 echo "<br><br>";
 echo "or Return to the install procedure by <a href=./vbpinstall.php?step=1 target=_self><b>Clicking here --&gt;</B></a></b>";

}

?>
</body>
</html>