<html>
<!--
<?php
// CHECK ENV
// determine if php is running
if(1==0)
{
	echo "-->You are not running PHP - Please contact your system administrator.<!--";
}else{
	echo "-->";
}
// /* <? */ Securety info
$vcardversion	= '2.6';
$thisscript	= 'install.php';
if($HTTP_GET_VARS['step'])
{
	$step = $HTTP_GET_VARS['step'];
}else{
	$step = $HTTP_POST_VARS['step'];
}
$userip = $HTTP_ENV_VARS['REMOTE_ADDR'];
$userphpself = $HTTP_SERVER_VARS['PHP_SELF'];
$userpathtran = $HTTP_SERVER_VARS['PATH_TRANSLATED'];
$usersafemode = get_cfg_var("safe_mode");

//include('./define.inc.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (function_exists("set_time_limit")==1 && get_cfg_var("safe_mode")==0)
{
	@set_time_limit(1200);
}

if (get_cfg_var("safe_mode") != 0)
{
	$installnote ="<p><b>Note:</b> In your server PHP configuration the <b>Safe Mode is active</b>, You will need edit your config.inc.php file to allow safe mode uploading!</p>";
	$safemode ="1";
}
$onvservers	= "0"; // set this to 1 if you're on Vservers and get disconnected after running an ALTER TABLE command

function dodb_queries()
{
	global $DB_site,$query,$explain,$onvservers;
	while (list($key,$val)=each($query))
	{
		echo "<p>$explain[$key]</p>\n";
		echo "<!-- ".htmlspecialchars($val)." -->\n\n";
		flush();
		if ($onvservers==1 and substr($val, 0, 5)=="ALTER")
		{
			$DB_site->reporterror=0;
		}
		$DB_site->query($val);
		if ($onvservers==1 and substr($val, 0, 5)=="ALTER")
		{
			$DB_site->connect();
			$DB_site->reporterror=1;
		}
	}
	unset ($query);
	unset ($explain);
}
function gotonext($extra="") {
	global $step,$thisscript;
	
	$nextstep = $step+1;
	echo "<div align=\"center\"><p><a href=\"$thisscript?step=$nextstep\"><b>Click here to continue ==&gt;&gt;</b></a> $extra</p>\n</div>";
echo <<<EOF
<p align="right"><a href="http://www.belchiorfoundry.com" target="_blank">Belchior Foundry</a></p>
EOF;
}
function stripslashesarray(&$arr) {
	while (list($key,$val) = each($arr))
	{
		if ((strtoupper($key)!=$key || "".intval($key)=="$key") && $key!="argc" && $key!="argv")
		{
			if(is_string($val))
			{
				$arr[$key] = stripslashes($val);
			}
			if(is_array($val))
			{
				$arr[$key] = stripslashesarray($val);
			}
		}
	}
	return $arr;
}
if (get_magic_quotes_gpc() and is_array($GLOBALS))
{
	$GLOBALS = stripslashesarray($GLOBALS);
}
set_magic_quotes_runtime(0);

if($HTTP_GET_VARS['see_phpinfo']==1)
{
	phpinfo();
	exit;
}
?>
<HTML>
	<HEAD>
	<title>vCard Install Script</title>
<STYLE TYPE="text/css">
	<!--
	A { text-decoration: none; }
	A:hover { text-decoration: underline; }
	H1 { font-family: arial,helvetica,sans-serif; font-size: 18pt; font-weight: bold;}
	H2 { font-family: arial,helvetica,sans-serif; font-size: 14pt; font-weight: bold;}
	BODY,TD,FORM,INPUT,TEXTAREA { font-family: arial,helvetica,sans-serif; font-size: 10pt; }
	TH { font-family: arial,helvetica,sans-serif; font-size: 11pt; font-weight: bold; }
	.textpre {font-family : "Courier New", Courier, monospace; font-size : 1px; font-weight : bold;}
	//-->
</STYLE>
	</HEAD>
<BODY onLoad="window.defaultStatus=' '" leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="Black">
	<tr valign="middle" bgcolor="#9999CC">
		<td align="left">
		<a href="http://www.belchiorfoundry.com/" target="_blank"><img src="../img/icon_powered.gif" width="102" height="47" alt="vCard Logo" border="0" align="right"></a>
		<H1>vCard Script</H1>
		<b>Installation: version <?php echo $vcardversion; ?></b></td>
	</TR>
</TABLE>
<BR>

<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="Black">
<TR VALIGN="top" BGCOLOR="#CCCCCC"><TD ALIGN="left">
Note: Please be patient as some parts of this may take quite some time.<br>
</TD></TR>
</TABLE><BR>

<?php
if ($step == "")
{
?>
<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="Black">
<tr valign="top" bgcolor="#CCCCCC"><TD ALIGN="left">
<p>Welcome to vCard Installation Script.</p>
<p>Running this script will do an install of vCard database strucuctury and default data onto your server.</p>
</TD></TR>
</TABLE><BR>

	<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
	<tr valign="baseline" bgcolor="#CCCCCC"><th bgcolor="#9999CC" colspan="3" align="center"><b> Database Check </b></th></tr>
	<tr valign="baseline" bgcolor="#CCCCCC" align="center">
		<td bgcolor="#CCCCFF"><b>vCard version <?php echo $vcardversion; ?></b></td>
		<td><b>System Requirements:</b></td>
		<td><b>Your System:</b></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>PHP version</b></td>
		<td>PHP 4 >= 4.0.4</td>
		<td>Your PHP version: <b><?php echo phpversion();?></b></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>MySQL version</b></td>
		<td>MySQL version 3.22 or higher</td>
		<td>See your MySQL version (<a href="install.php?see_phpinfo=1#module_mysql" target="_blank">check ver.</a>)</td>
	</tr>
	</table>
<?php
	$step = 1;
	gotonext();
}
if ($step == 2)
{
	if(file_exists("./config.inc.php")==0)
	{
		echo "<p><b>Cannot find config.inc.php file in admin directory.</b>/p>
		<p>Make sure that you have uploaded it and that it is in the admin directory.</p>
		";
	}
	else
	{
		include "./config.inc.php";
?>
<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=1 WIDTH=600 BGCOLOR="#000000" ALIGN="CENTER">
<TR VALIGN="top" BGCOLOR="#CCCCCC"><TD ALIGN="left">
<p>Attention: If you want setup your control panel in other language (default is english). Translate the language lib set inside the directory languages/english. You can change the language set into control panel later .</p>
</TD></TR>
</TABLE><BR>
	<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
	<tr valign="baseline" bgcolor="#CCCCCC"><th bgcolor="#9999CC" colspan="3" align="center"><b> Database Check </b><br>Please confirm the details below</th></tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
	        <td bgcolor="#CCCCFF"><b>Database server hostname / IP address</b></td>
	        <td><?php echo "$hostname"; ?> </td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
	        <td bgcolor="#CCCCFF"><b>Database UserName</b></td>
	        <td><?php echo "$dbUser"; ?> </td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
	        <td bgcolor="#CCCCFF"><b>DataBase Password</b></td>
	        <td><?php echo "$dbPass"; ?> </td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
	        <td bgcolor="#CCCCFF"><b>DataBase Name</b></td>
	        <td><?php echo "$dbName"; ?> </td>
	<tr>
	        <td colspan=2  bgcolor="#9999CC"><p>Only continue to the next step if those details are correct. If they are not, please edit your <b>config.inc.php</b> file and reupload it. The next step will test database connectivity.</p></td>
	</td>
	</tr>
	</table>
<?php
	}
	gotonext();
	exit;
}
if ($step >= 3)
{
	include("./config.inc.php");
}
if ($step == 3)
{
	// step 3 and after, we are ok loading this file
	include("./db_mysql.inc.php");
	$DB_site=new DB_Sql_vc;
	$DB_site->server = $hostname;
	$DB_site->user = $dbUser;
	$DB_site->password = $dbPass;
	$DB_site->database = $dbName;
	$DB_site->connect();
	$DB_site->reporterror = 0;
	$DB_site->connect();

	$errno = $DB_site->errno;
	if ($DB_site->idlink != 0)
	{
		if ($errno != 0)
		{
			if ($errno =='1049')
			{
				echo "<p>You have specified a non existent database. Trying to create one now...</p>";
				$DB_site->query("CREATE DATABASE $dbName");
				echo "<p>Trying to connect again...</p>";
				$DB_site->select_db($dbname);
				$errno=$DB_site->geterrno();
				if ($errno==0)
				{
					echo "<p>Connect succeeded!</p>";
					echo "<p><a href=\"install.php?step=".($step+1)."\">Click here to continue --></a></p>";
				}else{
					echo "<p>Connect failed again! Please ensure that the database and server is correctly configured and try again.</p>";
					exit;
				}
			}else{
				echo "<p>Connect failed: unexpected error from the database.</p>";
				echo "<p>Error number: ".$DB_site->errno."</p>";
				echo "<p>Error description: ".$DB_site->errdesc."</p>";
				echo "<p>Please ensure that the database and server is correctly configured and try again.</p>";
				exit;
			}
		}else{
			echo "<p><b>Connection succeeded! The database already exists.</b></p>";
			gotonext();
		}
	}else{
		echo "<p>The database has failed to connect because you do not have permission to connect to the server. Please go back to the last step and ensure that you have entered all your login details correctly.</p>";
		exit;
	}
}

if($step>=4)
{
	// step 3 and after, we are ok loading this file
	include("./db_mysql.inc.php");
	$DB_site = new DB_Sql_vc;
	$DB_site->server = $hostname;
	$DB_site->user = $dbUser;
	$DB_site->password = $dbPass;
	$DB_site->database = $dbName;
	$DB_site->connect();
	$DB_site->reporterror = 0;
	$DB_site->connect();
}

if($step==4)
{
	echo "<p>Setting up tables...</p>"; 
	
$query[]="CREATE TABLE vcard_cards (
  card_id smallint(10) NOT NULL auto_increment,
  card_category smallint(10) NOT NULL default '0',
  card_group smallint(4) NOT NULL default '0',
  card_rating varchar(5) NOT NULL default '0',
  card_imgfile varchar(200) NOT NULL default '',
  card_thmfile varchar(200) NOT NULL default '',
  card_width int(4) NOT NULL default '0',
  card_height int(4) NOT NULL default '0',
  card_template varchar(30) NOT NULL default '',
  card_date date NOT NULL default '0000-00-00',
  card_event smallint(10) NOT NULL default '0',
  card_caption varchar(200) NOT NULL default '',
  card_keywords varchar(200) NOT NULL default '',
  PRIMARY KEY  (card_id),
  KEY card_category (card_category),
  KEY card_date (card_date)
)";
$explain[]="Creating table cards";

$query[]="CREATE TABLE vcard_category (
cat_id smallint(10) NOT NULL auto_increment,
cat_subid smallint(10) default NULL,
cat_order varchar(10) NOT NULL default '0',
cat_name varchar(110) NOT NULL default '',
cat_img varchar(150) NOT NULL default '',
cat_link tinyint(1) NOT NULL default '0',
cat_header mediumtext NOT NULL,
cat_footer mediumtext NOT NULL,
cat_sort tinyint(4) NOT NULL default '0',
cat_ncards int(10) unsigned NOT NULL default '0',
cat_active enum('0','1') NOT NULL default '1',
PRIMARY KEY  (cat_id),
KEY cat_order (cat_order),
KEY cat_active (cat_active)
)";
$explain[]="Creating table categories";

$query[]="CREATE TABLE vcard_pattern (
  pattern_id smallint(10) NOT NULL auto_increment,
  pattern_file varchar(120) NOT NULL default '',
  pattern_name varchar(50) NOT NULL default '',
  pattern_active enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (pattern_id),
  KEY pattern_name (pattern_name)
)";
$explain[]="Creating table pattern";

$query[]="CREATE TABLE vcard_sound (
  sound_id smallint(10) NOT NULL auto_increment,
  sound_file varchar(250) NOT NULL default '',
  sound_name varchar(150) NOT NULL default '',
  sound_author varchar(150) NOT NULL default '',
  sound_genre varchar(150) NOT NULL default '',
  sound_order tinyint(4) NOT NULL default '0',
  sound_active enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (sound_id),
  KEY sound_name (sound_name)
)";
$explain[]="Creating table music";


$query[]="CREATE TABLE vcard_stamp (
  stamp_id smallint(10) NOT NULL auto_increment,
  stamp_file varchar(120) NOT NULL default '',
  stamp_name varchar(50) NOT NULL default '',
  stamp_active enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (stamp_id),
  KEY stamp_name (stamp_name)
)";
$explain[]="Creating table stamp";

$query[]="CREATE TABLE vcard_stats (
date datetime NOT NULL default '0000-00-00 00:00:00',
card_id smallint(10) NOT NULL default '0',
card_stamp varchar(150) NOT NULL default '',
card_poem varchar(10) NOT NULL default '',
card_sound varchar(150) NOT NULL default '',
card_background varchar(150) NOT NULL default '',
card_template varchar(30) NOT NULL default '',
KEY date (date),
KEY card_id (card_id),
KEY card_stamp (card_stamp),
KEY card_poem (card_poem),
KEY card_sound (card_sound),
KEY card_background (card_background),
KEY card_template (card_template)
)";
$explain[]="Creating table statistics";


$query[]="CREATE TABLE vcard_user (
card_date date NOT NULL default '0000-00-00',
sender_name varchar(60) NOT NULL default '',
sender_email varchar(60) NOT NULL default '',
recip_name varchar(60) NOT NULL default '',
recip_email varchar(60) NOT NULL default '',
card_file varchar(150) NOT NULL default '',
card_stamp varchar(150) NOT NULL default '',
card_sound varchar(150) NOT NULL default '',
card_poem int(10) NOT NULL default '0',
card_heading varchar(110) NOT NULL default '',
card_id varchar(20) NOT NULL default '',
card_message blob NOT NULL,
card_sig varchar(60) NOT NULL default '',
card_background varchar(150) NOT NULL default '',
card_color varchar(30) NOT NULL default '',
card_template varchar(30) NOT NULL default '',
card_fontface varchar(30) NOT NULL default '',
card_fontcolor varchar(30) NOT NULL default '',
card_fontsize varchar(10) NOT NULL default '',
message_id varchar(25) NOT NULL default '',
card_read enum('0','1') NOT NULL default '0',
card_notify varchar(10) NOT NULL default '',
card_tosend varchar(12) NOT NULL default '',
card_sent varchar(10) NOT NULL default '',
PRIMARY KEY  (message_id),
KEY card_tosend (card_tosend),
KEY card_sent (card_sent)
)";
$explain[]="Creating table user's postcards";

$query[]="CREATE TABLE vcard_attach (
attach_id smallint(10) NOT NULL auto_increment,
messageid varchar(25) NOT NULL default '',
time date NOT NULL default '0000-00-00',
filedata mediumtext NOT NULL,
filename varchar(100) NOT NULL default '',
filesize varchar(50) NOT NULL default '',
status varchar(10) NOT NULL default 'incomplete',
PRIMARY KEY  (attach_id)
)";
$explain[]="Creating table attachment";

$query[]="CREATE TABLE vcard_session (
session_id varchar(60) NOT NULL default '',
session_user tinyint(3) unsigned NOT NULL default '0',
session_ip varchar(60) NOT NULL default '',
session_agent varchar(60) NOT NULL default '',
session_date int(12) NOT NULL default '0',
PRIMARY KEY  (session_id)
)";

$explain[]="Creating table session";

$query[]="CREATE TABLE vcard_template (
template_id smallint(5) unsigned NOT NULL auto_increment,
title varchar(100) NOT NULL default '',
template mediumtext NOT NULL,
PRIMARY KEY  (template_id),
KEY title (title)
)";
$explain[]="Creating table template";

$query[]="CREATE TABLE vcard_settinggroup (
settinggroup_id smallint(5) unsigned NOT NULL auto_increment,
varname char(100) NOT NULL default '',
displayorder smallint(5) unsigned NOT NULL default '0',
PRIMARY KEY  (settinggroup_id)
)";
$explain[]="Creating table group settings";

$query[]="CREATE TABLE vcard_setting (
setting_id smallint(5) unsigned NOT NULL auto_increment,
settinggroup_id smallint(5) unsigned NOT NULL default '0',
varname varchar(100) NOT NULL default '',
value mediumtext NOT NULL,
optioncode mediumtext NOT NULL,
displayorder smallint(5) unsigned NOT NULL default '0',
PRIMARY KEY  (setting_id)
)";
$explain[]="Creating table settings";

$query[]="CREATE TABLE vcard_event (
event_id smallint(10) NOT NULL auto_increment,
event_day varchar(10) NOT NULL default '',
event_dayend varchar(10) NOT NULL default '',
event_month varchar(10) NOT NULL default '',
event_name varchar(110) NOT NULL default '',
PRIMARY KEY  (event_id),
KEY event_month (event_month)
)";
$explain[]="Creating table calendar events";

$query[]="CREATE TABLE vcard_replace (
replace_id smallint(5) unsigned NOT NULL auto_increment,
findword text NOT NULL,
replaceword text NOT NULL,
KEY replace_id (replace_id)
)";
$explain[]="Creating table replacements";

$query[]="CREATE TABLE vcard_poem (
  poem_id int(10) NOT NULL auto_increment,
  poem_title varchar(100) NOT NULL default '',
  poem_text mediumtext NOT NULL,
  poem_active enum('0','1') NOT NULL default '1',
  poem_style enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (poem_id),
  KEY poem_title (poem_title)
)";
$explain[]="Creating table poem";

$query[]="CREATE TABLE vcard_emaillog (
emaillogid int(20) NOT NULL auto_increment,
date datetime NOT NULL default '0000-00-00 00:00:00',
email varchar(60) NOT NULL default '',
name varchar(60) NOT NULL default '',
PRIMARY KEY  (emaillogid),
KEY email (email)
)";
$explain[]="Creating table mailing list";

// version 1.2, change 2.5
$query[]="CREATE TABLE vcard_abook (
ab_id smallint(10) NOT NULL auto_increment,
ab_username varchar(60) NOT NULL default '',
ab_password varchar(32) NOT NULL default '0',
ab_realname varchar(50) NOT NULL default '0',
ab_email varchar(60) NOT NULL default '',
ab_date date NOT NULL default '0000-00-00',
ab_md5password varchar(32) NOT NULL default '',
PRIMARY KEY  (ab_id),
KEY ab_email (ab_email),
KEY ab_username (ab_username)
)";
$explain[]="Creating table adress book";

$query[]="CREATE TABLE vcard_address (
addr_id smallint(10) NOT NULL auto_increment,
ab_id smallint(10) NOT NULL default '0',
addr_name varchar(50) NOT NULL default '',
addr_email varchar(60) NOT NULL default '',
KEY addr_id (addr_id),
KEY ab_id (ab_id),
KEY addr_name (addr_name),
KEY addr_email (addr_email)
)";
$explain[]="Creating table addresses";

$query[]="CREATE TABLE vcard_search (
word_id int(10) unsigned NOT NULL default '0',
card_id int(10) unsigned NOT NULL default '0',
cat_id int(10) unsigned NOT NULL default '0',
KEY word_id (word_id)
)";
$explain[]="Creating table search";

$query[]="CREATE TABLE vcard_word (
word_id int(10) unsigned NOT NULL auto_increment,
word_str char(60) NOT NULL default '',
PRIMARY KEY  (word_id),
UNIQUE KEY word_str (word_str)
)";
$explain[]="Creating table word index list";

$query[]="CREATE TABLE vcard_account (
account_id smallint(6) unsigned NOT NULL auto_increment,
account_username varchar(50) NOT NULL default '',
account_password varchar(50) NOT NULL default '',
superuser tinyint(2) unsigned NOT NULL default '0',
canworkcategory smallint(6) unsigned NOT NULL default '0',
canaddcard tinyint(3) unsigned NOT NULL default '0',
caneditcard tinyint(3) unsigned NOT NULL default '0',
candeletecard tinyint(3) unsigned NOT NULL default '0',
canaddmusic tinyint(3) unsigned NOT NULL default '0',
caneditmusic tinyint(3) unsigned NOT NULL default '0',
candeletemusic tinyint(3) unsigned NOT NULL default '0',
canaddpattern tinyint(3) unsigned NOT NULL default '0',
caneditpattern tinyint(3) unsigned NOT NULL default '0',
candeletepattern tinyint(3) unsigned NOT NULL default '0',
canaddpoem tinyint(3) unsigned NOT NULL default '0',
caneditpoem tinyint(3) unsigned NOT NULL default '0',
candeletepoem tinyint(3) unsigned NOT NULL default '0',
canaddevent tinyint(3) unsigned NOT NULL default '0',
caneditevent tinyint(3) unsigned NOT NULL default '0',
candeleteevent tinyint(3) unsigned NOT NULL default '0',
canaddstamp tinyint(3) unsigned NOT NULL default '0',
caneditstamp tinyint(3) unsigned NOT NULL default '0',
candeletestamp tinyint(3) unsigned NOT NULL default '0',
canaddreplace tinyint(3) unsigned NOT NULL default '0',
caneditreplace tinyint(3) unsigned NOT NULL default '0',
canviewcontrol tinyint(3) unsigned NOT NULL default '0',
canviewoptions tinyint(3) unsigned NOT NULL default '0',
canedittemplate tinyint(3) unsigned NOT NULL default '0',
canviewsearch tinyint(3) unsigned NOT NULL default '0',
canviewstyle tinyint(3) unsigned NOT NULL default '0',
canviewstats tinyint(3) unsigned NOT NULL default '0',
canviewemailog tinyint(3) unsigned NOT NULL default '0',
canviewphpinfo tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY  (account_id)
)";
$explain[]="Creating table multiple admin";
$query[]="CREATE TABLE vcard_cardsgroup (
cardsgroup_id int(4) unsigned NOT NULL auto_increment,
cardsgroup_name varchar(100) NOT NULL default '',
cardsgroup_fontface smallint(6) NOT NULL default '0',
cardsgroup_fontcolor smallint(6) NOT NULL default '0',
cardsgroup_fontsize smallint(6) NOT NULL default '0',
cardsgroup_cardcolor smallint(6) NOT NULL default '0',
cardsgroup_background smallint(6) NOT NULL default '0',
cardsgroup_poem smallint(6) NOT NULL default '0',
cardsgroup_advancedate smallint(6) NOT NULL default '0',
cardsgroup_stamp smallint(6) NOT NULL default '0',
cardsgroup_music smallint(6) NOT NULL default '0',
cardsgroup_notify smallint(6) NOT NULL default '0',
cardsgroup_copy smallint(6) NOT NULL default '0',
cardsgroup_layout smallint(6) NOT NULL default '0',
cardsgroup_heading smallint(6) NOT NULL default '0',
cardsgroup_signature smallint(6) NOT NULL default '0',
PRIMARY KEY  (cardsgroup_id)
)";
$explain[]="Creating table cards group";

// added v1.6
$query[]="CREATE TABLE vcard_searchlog (
search_date datetime NOT NULL default '0000-00-00 00:00:00',
search_word varchar(50) NOT NULL default '',
KEY search_date (search_date),
KEY search_word (search_word)
)";
$explain[]="Creating table search log";

// added v2.0
$query[]="CREATE TABLE vcard_rating (
  rating_id int(10) unsigned NOT NULL auto_increment,
  rating_card int(10) unsigned NOT NULL default '0',
  rating_value tinyint(4) NOT NULL default '0',
  rating_ip varchar(50) NOT NULL default '',
  rating_date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (rating_id),
  KEY rating_card (rating_card,rating_ip)
)";
$explain[]="Creating table card rating";

$query[]="CREATE TABLE vcard_statsextfile (
  extfile_id int(11) unsigned NOT NULL auto_increment,
  extfile_file varchar(150) NOT NULL default '',
  extfile_date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (extfile_id),
  KEY extfile_file (extfile_file)
)";
$explain[]="Creating table external file log";

$query[]="CREATE TABLE vcard_template_o (
  template_id smallint(5) unsigned NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  template mediumtext NOT NULL,
  PRIMARY KEY  (template_id),
  KEY title (title)
)";
$explain[]="Creating table original templates";

$query[]="CREATE TABLE vcard_spam (
  id bigint(20) NOT NULL auto_increment,
  ip varchar(50) NOT NULL default '',
  date int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ip (ip),
  KEY date (date))";
$explain[]="Creating table anti-spam";


// ver. 2.5 datetime -> timestamp
$query[]="CREATE TABLE vcard_cache (
  title varchar(100) NOT NULL default '',
  content mediumtext NOT NULL,
  date int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (title),
  KEY date (date)
)";
$explain[]="Creating table cache";

$query[]="CREATE TABLE vcard_useronline ( 
timestamp int(15) DEFAULT '0' NOT NULL, 
ip varchar(40) NOT NULL, 
file varchar(100) NOT NULL, 
KEY (timestamp), 
KEY ip (ip), 
KEY file (file) 
) ";
$explain[]="Creating table onlinse users";
	dodb_queries();

	if ($DB_site->errno!=0)
	{
		echo "<p>The script reported errors in the installation of the tables. Only continue if you are sure that they are not serious.</p>";
		echo "<p>The errors were:</p>";
		echo "<p>Error number: ".$DB_site->errno."</p>";
		echo "<p>Error description: ".$DB_site->errdesc."</p>";
	}else{
		echo "<p><b>Tables set up successfully.</b></p>";
		gotonext();
	}
}

if ($step == 5)
{
	if (!defined('VC_IS_WINDOWS'))
	{
		if (defined('PHP_OS') && eregi('win', PHP_OS))
		{
			define('VC_IS_WINDOWS', 1);
		}else{
			define('VC_IS_WINDOWS', 0);
		}
	}
	if (VC_IS_WINDOWS == 1 )
	{
		$separador = '\\';
	}else{
		$separador = '/';
	}

	$thisscript 	= "install.php";
	$old_step	= $step -1;
	$server_name    = getenv("SERVER_NAME");
	$directory 	= str_replace("/admin/$thisscript",'',getenv("PHP_SELF"));
	$base_url 	= str_replace("/admin/install.php?step=$old_step","",getenv("HTTP_REFERER"));
	$base_url_music	= "$base_url/music";
	$base_url_image	= "$base_url/images";
	$base_path      = str_replace("admin".$separador.$thisscript,"",getenv("PATH_TRANSLATED"));
	//echo getenv("PATH_TRANSLATED");
?>
	<form action="install.php" method="post">
	<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
	<tr valign="baseline" bgcolor="#CCCCCC"><th bgcolor="#9999CC" colspan="3" align="center"><b> vCard Server Variables </b></th></tr>
	<tr valign="baseline" bgcolor="#CCCCCC"><td bgcolor="#CCCCFF" colspan="3" align="center"> <p>Complete this basic informations about your vCard service to be insert on database. </p> </td></tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Full URL to program dir</b></td>
		<td><input type="text" name="site_prog_url" size="50" maxlength="60" value="<?php echo "$base_url"; ?>"><br>
		<b>WITHOUT final trailing slash</b></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Full URL to music dir</b></td>
		<td><input type="text" name="site_music_url" size="50" maxlength="60" value="<?php echo "$base_url_music"; ?>"><br>
		<b>WITHOUT final trailing slash</b></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Full Path to music dir</b>*</td>
		<td><input type="text" name="site_music_path" size="50" maxlength="60" value="<?php echo "$base_path"; ?>music"><br>
		<b>WITHOUT final trailing slash</b><br>
		don't forget set directory permission (chmod 777)</td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Full URL to Image dir</b></td>
		<td><input type="text" name="site_image_url" size="50" maxlength="60" value="<?php echo "$base_url_image"; ?>"><br>
		<b>WITHOUT final trailing slash</b></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Full Path to Image dir</b>*</td>
		<td><input type="text" name="site_image_path" size="50" maxlength="60" value="<?php echo "$base_path"; ?>images"><br>
		<b>WITHOUT final trailing slash</b><br>
		don't forget set directory permission (chmod 777)</td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF" colspan="2"> <b>* Full Path</b> - your full path to image or music directory will be something like this:<br>
		<b>/home/domainname/public_html/vcard/music</b><br>
		<b>C:/inetpub/home/www</b><br>
		Into Windows and Unix you can use '/'
		<p></p></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<th bgcolor="#9999CC" colspan="2" VALIGN="middle">Site Information</th>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Site Name</b></td>
		<td><input type="text" name="site_name" size="50" maxlength="60" value="You Site Name"><br>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Site URL</b></td>
		<td><input type="text" name="site_url" size="50" maxlength="60" value="<?php echo "$base_url"; ?>"></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>Admin Email</b></td>
		<td><input type="text" name="admin_email" size="50" maxlength="60" value="admin@domain.com"></td>
	</tr>
	
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#9999CC" colspan="2" align="center">
		<input type="hidden" name="step" value="<?php echo $step+1; ?>">
		<input type="submit" value="Continue">
		</td>
	</tr>
	</table>
	</form>

<?php
}
//$step = $HTTP_GET_VARS['step'];
if ($HTTP_POST_VARS['step'] == 6)
{

	echo "<p>Inserting data on tables...</p>";
	$path = "./vcard.style";
	if (file_exists($path) == 0)
	{
		$styletext = "";
	}else{
		$filesize 	= filesize($path);
		$filenum 	= fopen($path,"r");
		$styletext 	= fread($filenum,$filesize);
		fclose($filenum);
	}
	
	if (empty($styletext))
	{
		echo "<p>Please ensure that the vcard.style file exists in the current directory and then reload this current page.</p>";
		exit;
	}
	
	$stylebits = explode("|||",$styletext);
	list($devnul,$styleversion) 		= each($stylebits); // 1
	list($devnul,$style[title]) 		= each($stylebits); // 2
	list($devnull,$numreplacements) 	= each($stylebits); // 3
	list($devnull,$numtemplates) 		= each($stylebits); // 4
	$counter=0;
	while ($counter++<$numreplacements)
	{
		list($devnull,$findword)	= each($stylebits);
		list($devnull,$replaceword)	= each($stylebits);
		if (trim($findword)!="")
		{
			$DB_site->query("INSERT INTO vcard_replace (replace_id,findword,replaceword) VALUES (NULL,'".addslashes($findword)."','".addslashes($replaceword)."')");
		}
	}
	echo "<p>Added default replacements data</p>\n";

	$counter=0;
	while ($counter++<$numtemplates)
	{
		list($devnull,$title) = each($stylebits);
		list($devnull,$template) = each($stylebits);
		if (trim($title)!="")
		{
			$DB_site->query("INSERT INTO vcard_template  (template_id,title,template) VALUES (NULL,'".addslashes($title)."','".addslashes($template)."')");
			$DB_site->query("INSERT INTO vcard_template_o  (template_id,title,template) VALUES (NULL,'".addslashes($title)."','".addslashes($template)."')");
		}
	}
	
	echo "<p>Added default template data</p>";

	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '1', 'site_lang', 'english', 'language', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '1', 'site_lang_special', '0', 'yesno', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '2', 'site_prog_url', '".addslashes($HTTP_POST_VARS['site_prog_url'])."', '', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '2', 'site_music_url', '".addslashes($HTTP_POST_VARS['site_music_url'])."', '', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '2', 'site_music_path', '".addslashes($HTTP_POST_VARS['site_music_path'])."','', '3')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '2', 'site_image_url', '".addslashes($HTTP_POST_VARS['site_image_url'])."', '', '4')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '2', 'site_image_path', '".addslashes($HTTP_POST_VARS['site_image_path'])."','', '5')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '3', 'admin_email', '".addslashes($HTTP_POST_VARS['admin_email'])."', '', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '3', 'site_name', '".addslashes($HTTP_POST_VARS['site_name'])."', '', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '3', 'site_url', '".addslashes($HTTP_POST_VARS['site_url'])."', '', '3')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '3', 'site_dateformat', '1', 'dateformat', '4')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_font_face', 'Verdana', '', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_bgimage', 'background.gif', '', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_bgcolor', 'FFFFFF', '', '3')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_text', '000000','', '4')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_link', '000000', '', '5')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_vlink', '000000', '', '6')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_alink', '000000', '', '7')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_marginwidth', '0', '', '8')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'site_body_marginheight', '0', '', '9')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8', 'gallery_toplist_allow', '1', 'yesno', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8', 'gallery_thm_width', '55', '', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8', 'gallery_thm_height', '55', '', '3')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8', 'gallery_toplist_value', '5','', '4')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8', 'gallery_table_width', '95%', '', '5')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8', 'gallery_table_cols', '3', '', '6')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8', 'gallery_thm_per_page', '9', '', '7')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'form_font_list', 'Arial,Arial Black,Brush Script MT,Comic Sans MS,Courier,Courier New,Garamond,Georgia,Helvetica,sans-serif,Impact,Lucida Handwriting,MS Sans Serif,MS Serif,News Gothic MT,Palantino,Times New Roman,Verdana', '', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'form_bgcolor', 'FFCE63', '', '10')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'form_table_width', '450', '', '11')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'form_field_size', '25', '', '12')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'form_areatext_cols', '25','', '13')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'form_font_face', 'Times New Roman', '', '14')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '5', 'form_font_size', '3', '', '15')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4', 'mail_recip_subject', 'You have a Virtual Postcard.','', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4', 'mail_recip_message', 'Hi {recipient_name},

 {sender_name} has sent you a virtual greeting card from 
Your Site Name.
 To pickup your card, just click on the URL below, or copy and
paste it into your browser address bar:

{pickup_link}

AOL Users: <a href=\"{pickup_link}\">Click Here</a>.

Your Site Name
____________________________________________________________
Get your own vCard virtual greeting card system from
Belchior Foundry at: http://www.belchiorfoundry.com/', 'textarea', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4', 'mail_sender_subject', '{recipient_name} has pickup your card', '', '3')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4', 'mail_sender_message', 'Hi {sender_name},

 {recipient_name} has  pickup today, {today_date}, the virtual
greeting  card that you sent from You Site Name.

Your Site Name
____________________________________________________________
Get your own vCard virtual greeting card system
from Belchior Foundry at:  http://www.belchiorfoundry.com/', 'textarea', '4')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '7','admin_cards_deletelist', '0', 'yesno', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '7','admin_stats_toplist', '10', '', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '7','admin_gallery_cols', '3', '', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '7','admin_gallery_thm_per_page', '6', '', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_upload_allow', '1', 'yesno', '1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_upload_maxsize', '40', '', '2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_stamp_allow', '1', 'yesno', '3')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_stamp_default', 'defaultstamp.gif', '', '4')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_notify_allow', '1', 'yesno', '5')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_pattern_allow', '1', 'yesno', '6')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_music_allow', '1', 'yesno', '8')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_advance_allow', '1', 'yesno', '9')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_advance_range', '30', '', '10')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_multirecip_allow', '1', 'yesno', '11')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_template_only', '', '', '12')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_flash_width', '320', '', '13')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_flash_height', '280','', '14')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '6','user_poem_allow', '1', 'yesno', '10')");
	//new  1.2b
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8','gallery_newlist_value', '5', '', '8')");
$setting_option_timezone = 'Local Date Time: $timenow<br>
<select name=\\\"setting[$setting[setting_id]]\\\">
<option value=\\\"-12\\\" \".cexpr($setting[value]==-12,\"selected\",\"\").\">-12:00</option>
<option value=\\\"-11\\\" \".cexpr($setting[value]==-11,\"selected\",\"\").\">-11:00</option>
<option value=\\\"-10\\\" \".cexpr($setting[value]==-10,\"selected\",\"\").\">-10:00</option>
<option value=\\\"-9\\\" \".cexpr($setting[value]==-9,\"selected\",\"\").\">-9:00</option>
<option value=\\\"-8\\\" \".cexpr($setting[value]==-8,\"selected\",\"\").\">-8:00</option>
<option value=\\\"-7\\\" \".cexpr($setting[value]==-7,\"selected\",\"\").\">-7:00</option>
<option value=\\\"-6\\\" \".cexpr($setting[value]==-6,\"selected\",\"\").\">-6:00</option>
<option value=\\\"-5\\\" \".cexpr($setting[value]==-5,\"selected\",\"\").\">-5:00</option>
<option value=\\\"-4\\\" \".cexpr($setting[value]==-4,\"selected\",\"\").\">-4:00</option>
<option value=\\\"-3.5\\\" \".cexpr($setting[value]==-3.5,\"selected\",\"\").\">-3:30</option>
<option value=\\\"-3\\\" \".cexpr($setting[value]==-3,\"selected\",\"\").\">-3:00</option>
<option value=\\\"-2\\\" \".cexpr($setting[value]==-2,\"selected\",\"\").\">-2:00</option>
<option value=\\\"-1\\\" \".cexpr($setting[value]==-1,\"selected\",\"\").\">-1:00</option>
<option value=\\\"0\\\" \".cexpr($setting[value]==0,\"selected\",\"\").\">0:00</option>
<option value=\\\"+1\\\" \".cexpr($setting[value]==+1,\"selected\",\"\").\">+1:00</option>
<option value=\\\"+2\\\" \".cexpr($setting[value]==+2,\"selected\",\"\").\">+2:00</option>
<option value=\\\"+3\\\" \".cexpr($setting[value]==+3,\"selected\",\"\").\">+3:00</option>
<option value=\\\"+3.5\\\" \".cexpr($setting[value]==+3.5,\"selected\",\"\").\">+3:30</option>
<option value=\\\"+4\\\" \".cexpr($setting[value]==+4,\"selected\",\"\").\">+4:00</option>
<option value=\\\"+4.5\\\" \".cexpr($setting[value]==+4.5,\"selected\",\"\").\">+4:30</option>
<option value=\\\"+5\\\" \".cexpr($setting[value]==+5,\"selected\",\"\").\">+5:00</option>
<option value=\\\"+5.5\\\" \".cexpr($setting[value]==+5.5,\"selected\",\"\").\">+5:30</option>
<option value=\\\"+6\\\" \".cexpr($setting[value]==+6,\"selected\",\"\").\">+6:00</option>
<option value=\\\"+7\\\" \".cexpr($setting[value]==+7,\"selected\",\"\").\">+7:00</option>
<option value=\\\"+8\\\" \".cexpr($setting[value]==+8,\"selected\",\"\").\">+8:00</option>
<option value=\\\"+9\\\" \".cexpr($setting[value]==+9,\"selected\",\"\").\">+9:00</option>
<option value=\\\"+9.5\\\" \".cexpr($setting[value]==+9.5,\"selected\",\"\").\">+9:30</option>
<option value=\\\"+10\\\" \".cexpr($setting[value]==+10,\"selected\",\"\").\">+10:00</option>
<option value=\\\"+11\\\" \".cexpr($setting[value]==+11,\"selected\",\"\").\">+11:00</option>
<option value=\\\"+12\\\" \".cexpr($setting[value]==+12,\"selected\",\"\").\">+12:00</option>
</select>';
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '3','site_timeoffset', '0', '$setting_option_timezone', '5')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4','mail_abpwd_subject', 'Your password', '', '5') ");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4','mail_abpwd_message', 'Hello {abook_realname}, 
Your username and password 
to vCard Address Book is: 

- Username : {abook_username} 
- Password : {abook_password} 

vCard Script ____________________________________________________________
Get your own vCard virtual greeting card system
from Belchior Foundry at:  http://www.belchiorfoundry.com/', 'textarea', '6')  ");

	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4','mail_emailfriend_subject', 'Hi take a look at this site', '', '7')");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4','mail_emailfriend_message', 'Hi {recipient_name},

 Take a look at this site. There is great postcard

 http://{site_url}

 {user_message}

{sender_name}', 'textarea', '8')");
$select_option_format= '<select name=\\\"setting[$setting[setting_id]]\\\">
<option value=\\\"0\\\" \".cexpr($setting[value]==0,\"selected\",\"\").\">Plain Text</option>
<option value=\\\"1\\\" \".cexpr($setting[value]==1,\"selected\",\"\").\">HTML</option>
</select>';

	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4','mail_format', '0', '$select_option_format', '0') ");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '8','gallery_random', '0', 'yesno', '1') ");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4','mail_copy_subject', 'Your postcard order confirmation','', '9')  ");
	$DB_site->query("INSERT INTO vcard_setting VALUES ( NULL, '4','mail_copy_message', 'Hello! 

The Your Site Name card you sent will be delivered on {delivery_date}!

Subject: You have a new postcard

Recipient(s):
{recipients_names}
{recipients_mails}

You can VIEW this FREE card simply by visiting your own viewing site at: 

{pickup_url}


Thanks for expressing yourself with our cards! We look forward to seeing you soon at {site_url}


Your Friends at YouSite.com
____________________________________________________________
powered by vCard http://www.belchiorfoundry.com/
', 'textarea', '10') "); // '
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'2','vcardactive', '1', 'yesno', '0') ");
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'8','site_new_days','15','','9')");
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'6','user_rating_allow','1','yesno','15')");

	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'9','antispam_check','1','yesno','1')");
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'9','antispam_allow_entries','20','','2')");
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'9','antispam_policy','To prevent abuse of this service from possible spammers this site don´t allow send more than 20 cards/hour.','textarea','3')");
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'2','vcachesys','0','yesno','6')");
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'2','vcachereflesh', '120','', '7') "); 
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'5','form_font_color', '000000','', '16') "); 
	// version 2.3
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'0','vcardversion', '$vcardversion', 'vcardversion', '0') "); 
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'8','online_users', '0', 'yesno', '0') "); 
	
	echo "<p>Added default setting data</p>\n";
	
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '1', 'language', '1')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '2', 'server', '2')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '3', 'general', '3')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '4', 'admin', '7')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '5', 'visual', '5')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '6', 'gallery', '8')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '7', 'email', '4')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '8', 'user', '6')");
	$DB_site->query("INSERT INTO vcard_settinggroup VALUES ( '9', 'spam','9')");
	echo "<p>Added default setting group data</p>\n";
	
	// Music
	$DB_site->query("INSERT INTO vcard_sound VALUES (NULL, 'Pachelbel_canon.mid', 'Canon in D', 'Pachelbel', 'Classic', '1', '1') ");
	$DB_site->query("INSERT INTO vcard_sound VALUES (NULL, 'ChicoBuarque_oquesera.mid', 'O que sera', 'Chico Buarque', 'Brazilian', '2', '1') ");
	$DB_site->query("INSERT INTO vcard_sound VALUES (NULL, 'EltonJohn_feel_tonight.mid', 'Can You Feel The Love Tonight', 'Elton John', 'Pop', '4', '1') ");
	$DB_site->query("INSERT INTO vcard_sound VALUES (NULL, 'Beethoven_moonlight.mid', 'Moon Light Sonata', 'Bethoveen', 'Classic', '3', '1') ");
	$DB_site->query("INSERT INTO vcard_sound VALUES (NULL, 'real_audio.ram', 'How is going to be', 'Third Eye Blind', 'Real Audio Player', '5', '1') ");
	$DB_site->query("INSERT INTO vcard_sound VALUES (NULL, 'windown_media.asx', 'White Cliffs Of Dover', '', 'Windows Media Player', '6', '1') ");
	echo "<p>Added default music data</p>\n";
	
	// Stamps
	$DB_site->query("INSERT INTO vcard_stamp VALUES (1, 'stamp/stamp01.gif', 'Stamp 01', '1') ");
	$DB_site->query("INSERT INTO vcard_stamp VALUES (2, 'stamp/stamp02.gif', 'Stamp 02', '1') ");
	$DB_site->query("INSERT INTO vcard_stamp VALUES (3, 'stamp/stamp03.gif', 'Stamp 03', '1') ");
	echo "<p>Added default stamp data</p>\n";
	
	// Pattern
	$DB_site->query("INSERT INTO vcard_pattern VALUES ( NULL,'bg1.jpg', 'Gray Pattern','1') ");
	$DB_site->query("INSERT INTO vcard_pattern VALUES ( NULL,'bg2.jpg', 'BluePurple Pattern','1') ");
	echo "<p>Added default pattern data</p>\n";
	
	// Gallery
	$DB_site->query("INSERT INTO vcard_category VALUES ( '1', '', '01', 'Nature Category','category/cat_nature.gif','0','Images From Nature','Pics cortesy by <a href=\"http://www.belchiorfoundry.com/\">Belchior Foundry</a>','','1','1')");
	$DB_site->query("INSERT INTO vcard_category VALUES ( '2', '', '03', 'Flash Category','category/cat_flash.gif','0','','','','2','1')");
	$DB_site->query("INSERT INTO vcard_category VALUES ( '3', '', '02', 'Special template Category','category/cat_special.gif','0','','','','2','1')");
	$DB_site->query("INSERT INTO vcard_category VALUES ( '4', '1','01', 'Flower','category/cat_flower.gif','0','','','','2','1')");
	$DB_site->query("INSERT INTO vcard_category VALUES ( '5', '1','03', 'Sunset','category/cat_sunset.gif','0','','','','2','0')");
	$DB_site->query("INSERT INTO vcard_category VALUES ( '6', '1','02', 'Landscape','category/cat_landscape.gif','0','','','','2','1')");
	echo "<p>Added default card categories data</p>\n";

	$DB_site->query("INSERT INTO vcard_cardsgroup VALUES ( 1, 'group_general', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1') ");
	$DB_site->query("INSERT INTO vcard_cardsgroup VALUES ( 2, 'pagecard', 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 1) ");
	echo "<p>added default group to cardsgroup<p>\n";

	$DB_site->query("INSERT INTO vcard_cards VALUES ( '1',  '1', '0', '0', 'pic001.jpg', 'thm_pic001.gif', '0', '0', '', '2001-08-10', '0', 'Red flower', 'flower')  ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '2',  '4', '0', '0', 'nature/flower_001.jpg', 'nature/thm_flower_001.gif', '0', '0', 'demo', '2001-11-13', '0', 'Simple flower', 'flower') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '3',  '4', '0', '0', 'nature/flower_002.jpg', 'nature/thm_flower_002.gif', '0', '0', 'demo2', '2001-11-15', '0', 'Yellow flower pic by <a href=\"http://www.belchiorfoundry.com/\">Belchior Foundry</a>', 'flower')  ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '4',  '2', '0', '0', 'demoflash.swf', 'thm_demoflash.gif', '200', '200', '', '2001-12-23', '0', 'Flash Animation by Belchior Foundry', 'flowers') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '5',  '6', '0', '0', 'nature/land_001.jpg', 'nature/thm_land_001.gif', '0', '0', '', '2001-12-24', '0', 'Mountain Landspace', 'landscape, nature') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '6',  '6', '0', '0', 'nature/land_002.jpg', 'nature/thm_land_002.gif', '0', '0', '', '2001-12-25', '0', 'Landscape', 'landscape, nature') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '7',  '5', '0', '0', 'nature/sunset_001.jpg', 'nature/thm_sunset_001.gif', '0', '0', '', '2001-12-30', '0', 'Sunset in beach', 'sunset, nature') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '8', '5', '0', '0', 'nature/sunset_002.jpg', 'nature/thm_sunset_002.gif', '0', '0', '', '2002-01-10', '0', 'Sunset', 'sunset, nature') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '9', '3', '0', '0', 'nature/sunset_002.jpg', 'nature/thm_sunset_002.gif', '320', '260', 'java', '2002-01-12', '0', 'java card layout template', '') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '10', '2', '0', '0', 'demoflash.swf', 'thm_demoflash.gif', '0', '0', '', '2002-01-13', '0', 'flash without width/height', '') ");
	$DB_site->query("INSERT INTO vcard_cards VALUES ( '11',  3, 2, '0', 'html/sakura.html', 'html/thm_sakura.gif', 0, 0, '', '2002-04-11', 0, 'full page card', 'sakura') ");
	echo "<p>Added default postcard data</p>\n";
	// Stats
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-03-02 17:51:30', '1', 'stamp/stamp01.gif', '', '', 'bg1.jpg', 'demo')");
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-03-02 17:51:37', '1', 'stamp/stamp01.gif', '', '', 'bg1.jpg', 'demo')");
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-03-02 17:51:30', '1', 'stamp/stamp01.gif', '', '', 'bg1.jpg', 'demo')");
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-04-02 17:51:37', '1', 'stamp/stamp01.gif', '', '', 'bg1.jpg', 'demo')");
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-04-11 12:42:26', '3', 'stamp/stamp02.gif', '', '', '', 'demo')");
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-04-11 12:44:09', '3', '', '', '', 'bg2.jpg', 'demo')");
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-04-21 12:59:29', '3', 'stamp/stamp03.gif', '', '', '', 'demo')");
	$DB_site->query("INSERT INTO vcard_stats VALUES ( '2002-04-21 15:41:54', '9', 'stamp/stamp01.gif', '', 'Pachelbel_canon.mid', '', 'template03')");
	echo "<p>Added default stats data</p>\n";

	$DB_site->query("INSERT INTO vcard_poem VALUES (NULL, 'Hugs', 'I will not play Tug-o-War,\r\nI\'d rather play Hug-o-War.\r\nWhere everyone hugs, instead of tugs,\r\nAnd everyone giggles and rolls on the rug.\r\nWhere everyone kisses,\r\nAnd everyone grins;\r\nEveryone cuddles,\r\nAnd everyone wins.\r\n\r\n\r\n-- Shel Silverstein -- ', '1','0') ");
	$DB_site->query("INSERT INTO vcard_poem VALUES (NULL, 'How Do I Love Thee', 'How do I love thee? Let me count the ways.\r\nI love thee to the depth and breadth and height\r\nMy soul can reach, when feeling out of sight\r\nFor the ends of Being an ideal Grace.\r\nI love thee to the level of everyday\'s\r\nMost quiet need, by sun and candle-light.\r\nI love thee freely, as men strive for Right;\r\nI love thee purely, as they turn from Praise.\r\nI love thee with the passion put to use\r\nIn my old griefs, and with my childhood\'s faith.\r\nI love thee with a love I seemed to lose\r\nWith my lost saints, - I love thee with the breath,\r\nSmiles, tears, of all my life! - and, if God choose,\r\nI shall but love thee better after death.\r\n\r\n~Elizabeth Barrett Browning', '1','0') ");
	$DB_site->query("INSERT INTO vcard_poem VALUES (NULL, 'My Love Is Like A Red, Red Rose', 'O My Luve is like a red, red rose\r\nThat\'s newly sprung in June:\r\nO my Luve is like the melodie\r\nThat\'s sweetly play\'d in tune.\r\n\r\nAs fair art thou, my bonnie lass,\r\nSo deep in luve am I:\r\nAnd I will luve thee still, my dear,\r\nTill a\'the seas gang dry.\r\n\r\nTill a\'the seas gang dry, my dear,\r\nAnd the rocks melt wi\'the sun:\r\nAnd I luve thee still, my dear,\r\nWhile the sands o\'life shall run.\r\n\r\nAnd fare thee weel, my only Luve,\r\nAnd fare thee weel a while!\r\nAnd I will come again, my Luve,\r\nTho\'it were ten thousand mile.\r\n\r\n~Robert Burns\r\n\r\n\r\n', '1','0') ");
	$DB_site->query("INSERT INTO vcard_poem VALUES (NULL, 'Shall I Compare Thee', 'Shall I compare thee to a summer\'s day?\r\nThou art more lovely and more temperate:\r\nRough winds do shake the darling buds of May,\r\nAnd summer\'s lease hath all too short a date:\r\nSometimes too hot the eye of heaven shines,\r\nAnd often is his gold complexion dimmed:\r\nAnd every fair from fair sometime declines,\r\nBy chance, or nature\'s changing course, untrimmed.\r\nBut thy enternal summer shall not fade,\r\nNor lose possession of that fair thou ow\'st,\r\nNor shall death brag thou wander\'st in his shade,\r\nWhen in eternal lines to time thou grow\'st;\r\nSo long as men can breathe, or eyes can see,\r\nSo long lives this, and this gives life to thee\r\n\r\n~William Shakespeare ', '1','0') ");
	$DB_site->query("INSERT INTO vcard_poem VALUES (NULL, 'Somebody', 'Right now...\r\nSomebody is thinking of you.\r\nSomebody is caring about you.\r\nSomebody wants to be with you.\r\nSomebody hopes you aren\'t in trouble.\r\nSomebody wants to hold your hand.\r\nSomebody is praying for you.\r\nSomebody hopes everything turns out alright.\r\nSomebody wants you to be happy.\r\nSomebody wants you to find him/her.\r\nSomebody IS him/her.\r\nSomebody wants to give you a gift.\r\nSomebody hopes you\'re not too cold, and not too hot.\r\nSomebody wants to hug you.\r\nSomebody loves you.\r\nSomebody is thinking of you and smiling.\r\nSomebody wants to be your shoulder to cry on.\r\nSomebody wants to go out with you and have a lot of fun.\r\nSomebody wants you to believe in yourself and know they believe in you.\r\nSomebody wants you to know you are always in his/her heart.\r\nSomebody wants you to know that you are a part of him-her...no matter how near or close you may be...\r\nSomebody is playing a song that you love.\r\nSomebody is helping you without your knowledge.\r\nSomebody is your friend.\r\nSomebody misses you more than you know.\r\n', '1','0') ");
	echo "<p>Added default default poems<p>";
	
	echo "<p>Added default template data</p>\n";
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '13', '13', '05', 'Mother\'s Day')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '17', '17', '06', 'Father\'s Day')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '06', '12', '05', 'Postcard Week')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '06', '12', '05', 'Wildflower Week')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '31', '31', '05', 'World No-Tobacco Day')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '05', '05', '06', 'World Environment Day')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '11', '17', '06', 'eMail Week')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '01', '01', '07', 'International Joke Day')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '19', '25', '08', 'Friendship Week')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '01', '01', '01', 'Internacional Peace Day')");
	$DB_site->query("INSERT INTO vcard_event VALUES ( NULL, '25', '25', '12', 'Christmas')");
	echo "<p>Added default calendar event data</p>\n";

	echo "<p>Indexing current cards database...</p>\n";
	$index = $DB_site->query("SELECT card_id,card_caption,card_keywords FROM vcard_cards");
	$noise_words = file("noisewords.txt");
	while ($indexinfo = $DB_site->fetch_array($index))
	{
		$card_id 	= $indexinfo['card_id'];
		$allwords	= trim($indexinfo['card_caption']).' '.trim($indexinfo['card_keywords']);
		$allwords 	= ereg_replace("^",' ',$allwords);
		for ($i=0; $i<count($noise_words); $i++)
		{
			$filter_words 	= trim($noise_words[$i]);
			$allwords 	= eregi_replace(" $filter_words ",' ',$allwords);
		}
		$allwords	= eregi_replace("[\n\t\r,]",' ',$allwords);
		$allwords 	= eregi_replace("/",' ',$allwords);
		$allwords	= preg_replace("/(\.+)($| |\n|\t)/s",' ', $allwords);
		$allwords 	= preg_replace("/[\(\)\"':*;%,\[\]?!#{}.&_$<>|=`\-+\\\\]/s"," ",$allwords); // '
		$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) ",' '," $allwords ");
		$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) ",' '," $allwords ");
		$allwords 	= eregi_replace("[[:space:]]+"," ", $allwords);
		$allwords	= strtolower(trim($allwords));
	
		$wordarray	= explode(" ",$allwords);
	
		for ($k=0; $k<count($wordarray); $k++)
		{
			$wordarray[$k] = stripslashes(trim($wordarray[$k]));
			if(strlen($wordarray[$k])>2 AND strlen($wordarray[$k])<30)
			{
				$check_wordlist = $DB_site->query_first("SELECT word_id,word_str FROM vcard_word WHERE word_str='".addslashes($wordarray[$k])."' ");
				if(!$check_wordlist)
				{
					//withou word_id
					$insert = $DB_site->query("INSERT INTO vcard_word(word_id,word_str) VALUES (NULL, '".addslashes($wordarray[$k])."')  ");
					$word_id = $DB_site->insert_id();
				}else{
					//with word_id
					$word_id = $check_wordlist[word_id];
				}
				$DB_site->query("INSERT INTO vcard_search (word_id,card_id,cat_id) VALUES ('$word_id','$card_id','$cat_id') ");
			}
		}
	}
	$card_id = '';
	$indexcat = $DB_site->query("SELECT cat_id,cat_name FROM vcard_category ");
	while ($indexcatinfo = $DB_site->fetch_array($indexcat))
	{
		$cat_id = $indexcatinfo['cat_id'];
		$allwords = trim($indexcatinfo['cat_name']);
		$allwords = ereg_replace("^",' ',$allwords);
		for ($i=0; $i<count($noise_words); $i++)
		{
			$filter_words 	= trim($noise_words[$i]);
			$allwords 	= eregi_replace(" $filter_words ",' ',$allwords);
		}
		$allwords = eregi_replace("[\n\t\r,]",' ',$allwords);
		$allwords = eregi_replace('/',' ',$allwords);
		$allwords = preg_replace("/(\.+)($| |\n|\t)/s",' ', $allwords);
		$allwords = preg_replace("/[\(\)\"':*;%,\[\]?!#{}.&_$<>|=`\-+\\\\]/s"," ",$allwords); // '
		$allwords = eregi_replace(" ([[0-9a-z]{1,2}) ",' '," $allwords ");
		$allwords = eregi_replace(" ([[0-9a-z]{1,2}) ",' '," $allwords ");
		$allwords = eregi_replace("[[:space:]]+",' ', $allwords);
		$allwords = strtolower(trim($allwords));
		$wordarray = explode(' ',$allwords);
		for ($k=0; $k<count($wordarray); $k++)
		{
			$wordarray[$k] = stripslashes(trim($wordarray[$k]));
			if(strlen($wordarray[$k])>2 AND strlen($wordarray[$k])<30)
			{
				$check_wordlist = $DB_site->query_first("SELECT word_id,word_str FROM vcard_word WHERE word_str='".addslashes($wordarray[$k])."' ");
				if(!$check_wordlist)
				{
					$insert = $DB_site->query("INSERT IGNORE INTO vcard_word(word_id,word_str) VALUES (NULL, '".addslashes($wordarray[$k])."')  ");
					$word_id = $DB_site->insert_id();
				}else{
					$word_id = $check_wordlist[word_id];
				}
				$DB_site->query("INSERT INTO vcard_search (word_id,card_id,cat_id) VALUES ('$word_id','$card_id','$cat_id') ");
			}
		}
	}
	// end index	
	echo "<p>Indexed current database</p>\n";
	$DB_site->query("INSERT INTO vcard_account VALUES ( NULL, 'admin', 'Admin5', '1', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1') ");
	echo "<p>Added default admin account</p>\n";
	$options_template = '';
	$getsettings = $DB_site->query("SELECT varname,value FROM vcard_setting");
	while ($setting = $DB_site->fetch_array($getsettings))
	{
		$options_template.="\$$setting[varname] = \"".addslashes(str_replace("\"","\\\"",$setting[value]))."\";\n";
	}
	$DB_site->query("INSERT INTO vcard_template VALUES ( NULL,'options','$options_template' ) ");
	echo "<p><b>added default data on  database tables successfully</b>!<img src='http://belchiorfoundry.com/cgi-bin/version.cgi' width=1 height=1 alt='' border=0></p>";
	gotonext();
}

if ($step == 7)
{
	echo "<p><h1><b>You has completed the installation of vCard successfully.</b></h1>"; 
	echo "<blockquote>	<p> After installation <b>delete these files: upgrade1.php, upgrade2.php, upgrade3.php, upgradeX.php, install.php and uninstall.php</b> for security reasons."; 
	echo "<p>Go to control panel and <b>edit your default password access</b> code. Control Painel > MENU USERS</p></blockquote>";
	echo "	<p><a href='./../' target='blank'>The default content of vCard can be found here --></a></p>";
	echo "	<p><a href='./' target='blank'>Access your admin page here --></a> (<b>Default login: admin /pass: Admin5</b>)</p>";
	echo " $installnote";
	echo "	<p>If you make wrong installation you can reinstall from zero. <a href='./uninstall.php'>To Uninstall database, click here --></a>";
	echo "	</body>";
	echo "	</html>";
}
?>