<?

$header = "<html>
<head>
<title>RPG battle system Installation</title>
		<STYLE TYPE=\"TEXT/CSS\">
			BODY { SCROLLBAR-BASE-COLOR: #646464; SCROLLBAR-ARROW-COLOR: #FEC254; } 
			SELECT { FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 11px; COLOR: #000000; BACKGROUND-COLOR: #CFCFCF } 
			TEXTAREA, .input { FONT-SIZE: 12px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; COLOR: #000000; BACKGROUND-COLOR: #CFCFCF } 
			#bg A:link, #bg A:visited, #bg A:active { COLOR: #000000; TEXT-DECORATION: underline; } #bg A:hover { COLOR: #000000; TEXT-DECORATION: none; } 
			#cat A:link, #cat A:visited, #cat A:active { COLOR: #FEC254; TEXT-DECORATION: none; } #cat A:hover { COLOR: #FEC254; TEXT-DECORATION: underline; } 
			#title A:link, #title A:visited, #title A:active { COLOR: #FEC254; TEXT-DECORATION: none; } #title A:hover { COLOR: #FEC254; TEXT-DECORATION: underline; }  
		</STYLE>
	
</head>

<body bgcolor=\"#808080\" text=\"#000000\" bgproperties=fixed id=\"bg\">
<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=2 color=\"#000000\">";


# - functions -
require "data.inc.php";
require "db_connect.php";

$tables = array(
"db_bbcode",
"db_configuration",
"db_groups",
"db_object2user",
"db_style",
"db_users",
"db_battle_table",
"db_useronline"
); 

$db_connect = new db_connect;

$db_connect->appname="Character battle system";
$db_connect->database=$mysqldb;
$db_connect->server=$mysqlhost;
$db_connect->user=$mysqluser;
$db_connect->password=$mysqlpassword;

$db_connect->connect();

function gettemplate($template) {
        $file = file("templates/".$template.".htm");
        $template = implode("",$file);
        $template = str_replace("\"","\\\"",$template);
        return $template;
}

function dooutput($template) {
        echo $template;
}

# - steps -
if(!$step) {
echo $header;
?>
<p>
<font site=3><b>Welcome to RPG battle system Installation!</b></font> 
</p>
<p>
<a href="install.php?step=1">Click here to start the installation!</a> 
</p>
<?
}

if($step==1) {
	$result = mysql_list_tables($mysqldb); 
	if(!$db_connect->num_rows($result)) header("Location: install.php?step=4");
	else { 
	echo $header;
	?>
		<p>
		<b>The database <? echo "$mysqldb"; ?> is not empty!</b> 
		</p>
		<p>
		<a href="install.php?step=3">Click here to continue!</a> ( Leave database as it is )
		</p>
		<p>
		<a href="install.php?step=2">Click here to empty the database!</a> ( <b>Note:  All the content of the database is deleted!</b> )
		</p>
	<?
	}
}

if($step==2) {
	$result = mysql_list_tables ($mysqldb);
	for($i = 0; $i < mysql_num_rows ($result); $i++) $db_connect->query("DROP TABLE ".mysql_tablename($result, $i));
	echo $header;
	?>
		<p>
		<b>Database <? echo "$mysqldb"; ?> successfully emptied!</b> 
		</p>
		<p>
		<a href="install.php?step=4">Click here to continue!</a> 
		</p>
	<?
}

if($step==3) {
	$result = mysql_list_tables ($mysqldb);
	for($i = 0; $i < mysql_num_rows ($result); $i++) {
		if(in_array(mysql_tablename($result, $i),$tables)) {
			$check = 1;
			break;
		}
	}
	if(!$check) header("Location: install.php?step=4");
	else {
	echo $header;
	?>
		<p>
		<b>There are already tables with the same name, if you continue the installation, it will overwrite them. <br>
		However, you can change the board number in data.inc.php and it will create different tables and leave your present ones intact!</b> 
		</p>
		<p>
		<a href="install.php?step=4">Click here to continue!</a> 
		</p>
	<?
	}
}

if($step==4) {
	$result = mysql_list_tables ($mysqldb);
	for($i = 0; $i < mysql_num_rows ($result); $i++) if(in_array(mysql_tablename($result, $i),$tables)) $db_connect->query("DROP TABLE ".mysql_tablename($result, $i));
	echo $header;
	echo "<p>\n";
	
	$db_connect->query("CREATE TABLE db_bbcode (
   	id int(11) NOT NULL auto_increment,
   	bbcodetag varchar(250) NOT NULL,
   	bbcodereplace varchar(250) NOT NULL,
   	params int(1) DEFAULT '1' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_bbcode table created<br>";
	
	$db_connect->query("CREATE TABLE db_configuration (
   	php_path varchar(200) NOT NULL,
   	master_board_name varchar(50) NOT NULL,
   	master_email varchar(70) NOT NULL,
   	html int(1) DEFAULT '0' NOT NULL,
   	smilies int(1) DEFAULT '0' NOT NULL,
   	bbcode int(1) DEFAULT '0' NOT NULL,
   	maximage int(2) DEFAULT '0' NOT NULL,
   	polls int(1) DEFAULT '0' NOT NULL,
   	image int(1) DEFAULT '0' NOT NULL,
   	image_ext text NOT NULL,
   	tproseite int(2) DEFAULT '0' NOT NULL,
   	eproseite int(2) DEFAULT '0' NOT NULL,
   	timeoffset int(3) DEFAULT '0' NOT NULL,
   	rekord int(7) DEFAULT '0' NOT NULL,
   	rekordtime int(11) DEFAULT '0' NOT NULL,
   	timeout int(2) DEFAULT '0' NOT NULL,
   	default_daysprune int(4) DEFAULT '0' NOT NULL,
   	hotthread_reply int(3) DEFAULT '0' NOT NULL,
   	hotthread_view int(5) DEFAULT '0' NOT NULL,
   	show_subboards int(1) DEFAULT '0' NOT NULL,
   	anzahl_smilies int(3) DEFAULT '0' NOT NULL,
   	cover char(1) NOT NULL,
   	badwords text NOT NULL,
   	ch_parseurl int(1) DEFAULT '0' NOT NULL,
   	ch_email int(1) DEFAULT '0' NOT NULL,
   	ch_disablesmilies int(1) DEFAULT '0' NOT NULL,
   	ch_signature int(1) DEFAULT '0' NOT NULL,
   	boardoff int(1) DEFAULT '0' NOT NULL,
   	boardoff_text text NOT NULL,
   	regdateformat varchar(30) NOT NULL,
   	shortdateformat varchar(30) NOT NULL,
   	longdateformat varchar(30) NOT NULL,
   	today varchar(30) NOT NULL,
   	timetype int(1) DEFAULT '0' NOT NULL,
   	postorder int(1) DEFAULT '0' NOT NULL,
   	register int(1) DEFAULT '0' NOT NULL,
   	act_code int(1) DEFAULT '0' NOT NULL,
   	act_permail int(1) DEFAULT '0' NOT NULL,
   	regnotify int(1) DEFAULT '0' NOT NULL,
   	multi_email int(1) DEFAULT '0' NOT NULL,
   	banname text NOT NULL,
   	banemail text NOT NULL,
   	sigsmilies int(1) DEFAULT '0' NOT NULL,
   	sigbbcode int(1) DEFAULT '0' NOT NULL,
   	sightml int(1) DEFAULT '0' NOT NULL,
   	sigimage int(1) DEFAULT '0' NOT NULL,
   	sigmaximage int(2) DEFAULT '0' NOT NULL,
   	sigimage_ext text NOT NULL,
   	siglength int(5) DEFAULT '0' NOT NULL,
   	avatars int(1) DEFAULT '0' NOT NULL,
   	avatarimage_ext text NOT NULL,
   	avatar_width int(3) DEFAULT '0' NOT NULL,
   	avatar_height int(3) DEFAULT '0' NOT NULL,
   	avatar_size int(6) DEFAULT '0' NOT NULL,
   	usertextlength int(4) DEFAULT '0' NOT NULL,
   	favboards int(2) DEFAULT '0' NOT NULL,
   	favthreads int(2) DEFAULT '0' NOT NULL,
   	pms int(1) DEFAULT '0' NOT NULL,
   	maxpms int(5) DEFAULT '0' NOT NULL,
   	maxfolder int(2) DEFAULT '0' NOT NULL,
   	forumid varchar(32) NOT NULL
	)");
	echo "db_configuration table created<br>";
	
	$db_connect->query("CREATE TABLE db_groups (
   	id int(11) NOT NULL auto_increment,
   	title varchar(30) NOT NULL,
   	canviewboard int(1) DEFAULT '0' NOT NULL,
   	canviewoffboard int(1) DEFAULT '0' NOT NULL,
   	canusesearch int(1) DEFAULT '0' NOT NULL,
   	canusepms int(1) DEFAULT '0' NOT NULL,
   	canstarttopic int(1) DEFAULT '0' NOT NULL,
   	canreplyowntopic int(1) DEFAULT '0' NOT NULL,
   	canreplytopic int(1) DEFAULT '0' NOT NULL,
   	caneditownpost int(1) DEFAULT '0' NOT NULL,
   	candelownpost int(1) DEFAULT '0' NOT NULL,
   	cancloseowntopic int(1) DEFAULT '0' NOT NULL,
   	candelowntopic int(1) DEFAULT '0' NOT NULL,
   	canpostpoll int(1) DEFAULT '0' NOT NULL,
   	canvotepoll int(1) DEFAULT '0' NOT NULL,
   	canuploadavatar int(1) DEFAULT '0' NOT NULL,
   	appendeditnote int(1) DEFAULT '0' NOT NULL,
   	avoidfc int(1) DEFAULT '0' NOT NULL,
   	ismod int(1) DEFAULT '0' NOT NULL,
   	issupermod int(1) DEFAULT '0' NOT NULL,
   	canuseacp int(1) DEFAULT '0' NOT NULL,
   	default_group int(1) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_groups table created<br>";

	$db_connect->query("CREATE TABLE db_object2user (
   	userid int(11) DEFAULT '0' NOT NULL,
   	objectid int(11) DEFAULT '0' NOT NULL,
   	buddylist int(1) DEFAULT '0' NOT NULL,
   	ignorelist int(1) DEFAULT '0' NOT NULL,
   	pmsend int(1) DEFAULT '0' NOT NULL
	)");
	echo "db_object2user table created<br>";

	$db_connect->query("CREATE TABLE db_style (
   	styleid int(11) NOT NULL auto_increment,
   	stylename varchar(50) NOT NULL,
   	templatefolder varchar(200) NOT NULL,
   	font varchar(100) NOT NULL,
   	fontcolor varchar(7) NOT NULL,
   	fontcolorsec varchar(7) NOT NULL,
   	fontcolorthi varchar(7) NOT NULL,
   	fontcolorfour varchar(7) NOT NULL,
   	bgcolor varchar(7) NOT NULL,
   	tablebg varchar(7) NOT NULL,
   	tablea varchar(7) NOT NULL,
   	tableb varchar(7) NOT NULL,
   	tablec varchar(7) NOT NULL,
   	tabled varchar(7) NOT NULL,
   	imageurl varchar(250) NOT NULL,
   	css text NOT NULL,
   	bgimage varchar(250) NOT NULL,
   	bgfixed int(1) DEFAULT '0' NOT NULL,
   	default_style int(1) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (styleid)
	)");
	echo "db_style table created<br>";
	
	$db_connect->query("CREATE TABLE db_users (
  	userid int(11) NOT NULL auto_increment,
   	username varchar(30) NOT NULL,
   	userpassword varchar(50) NOT NULL,
   	useremail varchar(150) NOT NULL,
   	regemail varchar(150) NOT NULL,
   	userposts int(11) DEFAULT '0' NOT NULL,
   	groupid int(7) DEFAULT '0' NOT NULL,
   	statusextra varchar(25),
   	regdate int(11) DEFAULT '0' NOT NULL,
   	lastvisit int(11) DEFAULT '0' NOT NULL,
   	lastactivity int(11) DEFAULT '0' NOT NULL,
   	session_link int(1) DEFAULT '1' NOT NULL,
   	signatur text NOT NULL,
   	usericq varchar(30) NOT NULL,
   	aim varchar(30) NOT NULL,
   	yim varchar(30) NOT NULL,
   	userhp varchar(200) NOT NULL,
   	age_m varchar(10) NOT NULL,
   	age_d int(2) DEFAULT '0' NOT NULL,
   	age_y int(4) DEFAULT '0' NOT NULL,
   	avatarid int(11) DEFAULT '0' NOT NULL,
   	interests varchar(250) NOT NULL,
   	location varchar(250) NOT NULL,
   	work varchar(250) NOT NULL,
   	gender int(1) DEFAULT '0' NOT NULL,
   	usertext text NOT NULL,
   	show_email_global int(1) DEFAULT '0' NOT NULL,
   	mods_may_email int(1) DEFAULT '1' NOT NULL,
   	users_may_email int(1) DEFAULT '1' NOT NULL,
   	invisible int(1) DEFAULT '0' NOT NULL,
   	hide_signature int(1) DEFAULT '0' NOT NULL,
   	hide_userpic int(1) DEFAULT '0' NOT NULL,
   	prunedays int(4) DEFAULT '0' NOT NULL,
   	umaxposts int(2) DEFAULT '0' NOT NULL,
   	bbcode int(1) DEFAULT '1' NOT NULL,
   	style_set int(11) DEFAULT '0' NOT NULL,
                userclass varchar(30) NOT NULL,
                useralignment varchar(20) NOT NULL,
                userlevel int(5) DEFAULT '1' NOT NULL,
                userexp int(9) NOT NULL,
                userhitpoint int(30) DEFAULT '60' NOT NULL,
                usermagicpoint tinyint(6) NOT NULL,
                userdefense tinyint(6) NOT NULL,
                userattack tinyint(3) NOT NULL,
                usermagic tinyint(3) NOT NULL,
                userspeed tinyint(3) NOT NULL,
                usermoney int(15) DEFAULT '500' NOT NULL,
                userbackground varchar(255) NOT NULL,
                useritem1 varchar(30) NOT NULL,
                useritem2 varchar(30) NOT NULL,
                useritem3 varchar(30) NOT NULL,
                useritem4 varchar(30) NOT NULL,
                useritem5 varchar(30) NOT NULL,
                useritem6 varchar(30) NOT NULL,
                useritem7 varchar(30) NOT NULL,
                useritem8 varchar(30) NOT NULL,
                userweapon varchar(30) NOT NULL,
                usershield varchar(30) NOT NULL,
                userarmour varchar(30) NOT NULL,
                usergloves varchar(30) NOT NULL,
                userhelmet varchar(30) NOT NULL,
                userboots varchar(30) NOT NULL,
                userextra1 varchar(30) NOT NULL,
                userextra2 varchar(11) NOT NULL,
                battlemusic varchar(15) NOT NULL,
                canusetheshop varchar(6) DEFAULT 'yes' NOT NULL,
                userroom varchar(12) DEFAULT 'yes' NOT NULL,
                useropponent varchar(30) NOT NULL,
                hpmax int(30) DEFAULT '60' NOT NULL,
                mypic varchar(255) NOT NULL,
                tempexppoints int(11) DEFAULT '0' NOT NULL,
                totalexppoints int(11) DEFAULT '0' NOT NULL,
   	activation int(10) DEFAULT '0' NOT NULL,
   	blocked int(1) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (userid)
	)");
	echo "db_users table created<br>";
	
	$db_connect->query("CREATE TABLE db_useronline (
   	zeit int(11) DEFAULT '0' NOT NULL,
   	ip varchar(15) DEFAULT '0' NOT NULL,
   	userid int(11) DEFAULT '0' NOT NULL
	)");
	echo "db_useronline table created<br>";

               $db_connect->query("CREATE TABLE db_shop_swords (
	id int(11) NOT NULL auto_increment,
	swordname varchar(250) NOT NULL,
                damage int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_swords table created<br>";

               $db_connect->query("CREATE TABLE db_shop_shields (
	id int(11) NOT NULL auto_increment,
	shieldname varchar(250) NOT NULL,
                deffense int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_shields table created<br>";

               $db_connect->query("CREATE TABLE db_shop_armour (
	id int(11) NOT NULL auto_increment,
	armourname varchar(250) NOT NULL,
                deffense int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_armour table created<br>";

               $db_connect->query("CREATE TABLE db_shop_gloves (
	id int(11) NOT NULL auto_increment,
	glovename varchar(250) NOT NULL,
                deffense int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_gloves table created<br>";

               $db_connect->query("CREATE TABLE db_shop_helmet (
	id int(11) NOT NULL auto_increment,
	helmetname varchar(250) NOT NULL,
                deffense int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_helmet table created<br>";

               $db_connect->query("CREATE TABLE db_shop_boots (
	id int(11) NOT NULL auto_increment,
	bootname varchar(250) NOT NULL,
                deffense int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_boots table created<br>";

               $db_connect->query("CREATE TABLE db_shop_smallitems (
	id int(11) NOT NULL auto_increment,
	smallitemname varchar(250) NOT NULL,
                hpaddon int(30) NOT NULL,
                mpaddon int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_smallitems table created<br>";

               $db_connect->query("CREATE TABLE db_shop_specialitem (
	id int(11) NOT NULL auto_increment,
	specialitemname varchar(250) NOT NULL,
                damage int(30) NOT NULL,
   	money int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_specialitem table created<br>";

               $db_connect->query("CREATE TABLE db_arena1 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena1 table created<br>";

               $db_connect->query("CREATE TABLE db_arena2 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena2 table created<br>";

               $db_connect->query("CREATE TABLE db_arena3 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena3 table created<br>";

               $db_connect->query("CREATE TABLE db_arena4 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena4 table created<br>";

               $db_connect->query("CREATE TABLE db_arena5 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena5 table created<br>";

               $db_connect->query("CREATE TABLE db_arena6 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena6 table created<br>";

               $db_connect->query("CREATE TABLE db_arena7 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena7 table created<br>";

               $db_connect->query("CREATE TABLE db_arena8 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena8 table created<br>";

               $db_connect->query("CREATE TABLE db_arena9 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena9 table created<br>";

               $db_connect->query("CREATE TABLE db_arena10 (
	id int(11) NOT NULL,
	whosturn varchar(20) NOT NULL,
                status varchar(60) NOT NULL,
                winner int(11) NOT NULL,
                me varchar(255) NOT NULL,
                challenger varchar(255) NOT NULL,
                battlemusic varchar(255) NOT NULL,
                accepted varchar(255) DEFAULT 'wait' NOT NULL,
                messages text NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_arena10 table created<br>";

               $db_connect->query("CREATE TABLE db_battle_classes (
	id int(11) NOT NULL auto_increment,
	classname varchar(250) NOT NULL,
   	attackvalue int(15) DEFAULT '0' NOT NULL,
   	magicvalue int(15) DEFAULT '0' NOT NULL,
   	speedvalue int(15) DEFAULT '0' NOT NULL,
   	defensevalue int(15) DEFAULT '0' NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_shop_gloves table created<br>";

               $db_connect->query("CREATE TABLE db_lobby_table (
               id int(11) default NULL,
               postid int(11) NOT NULL auto_increment,
               fromwho int(11) default NULL,
               posttime int(11) default NULL,
               message text,
               PRIMARY KEY (postid)
	)");
	echo "db_shop_gloves table created<br>";

               $db_connect->query("CREATE TABLE db_levels (
	level int(11) NOT NULL,
                expneeded int(11) NOT NULL,
   	PRIMARY KEY (level)
	)");
	echo "db_levels table created<br>";

               $db_connect->query("CREATE TABLE db_deals (
   	id int(11) NOT NULL auto_increment,
	dealname varchar(60) NOT NULL,
                dealdescription varchar(255) NOT NULL,
                amount int(11) NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_levels table created<br>";

	$db_connect->query("CREATE TABLE db_announcements (
   	id int(11) NOT NULL auto_increment,
   	adminname varchar(250) NOT NULL,
   	message varchar(250) NOT NULL,
   	PRIMARY KEY (id)
	)");
	echo "db_announcements table created<br>";
	?>
		</p>
		<p>
		<b>Tables were successfully created!</b> 
		</p>
		<p>
		<a href="install.php?step=5">Click here to continue!</a> 
		</p>
	<?
}

if($step==5) {
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '1', 'b', '<b>\\\\1</b>', '1');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '2', 'i', '<i>\\\\1</i>', '1');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '3', 'email', '<a href=\"mailto:\\\\1\">\\\\1</a>', '1');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '4', 'email', '<a href=\"mailto:\\\\2\">\\\\3</a>', '2');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '5', 'size', '<font size=\"\\\\2\">\\\\3</font>', '2');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '6', 'quote', '<blockquote><font size=1>quote:</font><hr>\\\\1<hr></blockquote>', '1');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '7', 'u', '<u>\\\\1</u>', '1');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '8', 'color', '<font color=\"\\\\2\">\\\\3</font>', '2');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '9', 'font', '<font face=\"\\\\2\">\\\\3</font>', '2');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '10', 'align', '<div align=\"\\\\2\">\\\\3</div>', '2');");
	$db_connect->query("INSERT INTO db_bbcode VALUES ( '11', 'mark', '<span style=\"background-color: \\\\2\">\\\\3</span>', '2');");
	
	$db_connect->query("INSERT INTO db_groups VALUES ( '1', 'Administrator', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '1', '1', '1', '0');");
	$db_connect->query("INSERT INTO db_groups VALUES ( '2', 'Moderator', '1', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '0');");
	$db_connect->query("INSERT INTO db_groups VALUES ( '3', 'User', '1', '0', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '1', '0', '1', '0', '0', '0', '0', '2');");
	$db_connect->query("INSERT INTO db_groups VALUES ( '4', 'Guest', '1', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1');");
	$db_connect->query("INSERT INTO db_groups VALUES ( '5', 'Super Moderator', '1', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '0', '0');");
	
	$db_connect->query("INSERT INTO db_style VALUES ( '1', 'Standard', 'templates', 'Verdana, Arial, Helvetica, sans-serif', '#000000', '#FEC254', '#000000', '#444444', '#808080', '#000000', '#646464', '#EFEFEF', '#DEDEDE', '#646464', 'images/bblogo.gif', 'BODY { SCROLLBAR-BASE-COLOR: #646464; SCROLLBAR-ARROW-COLOR: #FEC254; } SELECT { FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 11px; COLOR: #000000; BACKGROUND-COLOR: #CFCFCF } TEXTAREA, .input { FONT-SIZE: 12px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; COLOR: #000000; BACKGROUND-COLOR: #CFCFCF } #bg A:link, #bg A:visited, #bg A:active { COLOR: #000000; TEXT-DECORATION: underline; } #bg A:hover { COLOR: #000000; TEXT-DECORATION: none; } #cat A:link, #cat A:visited, #cat A:active { COLOR: #FEC254; TEXT-DECORATION: none; } #cat A:hover { COLOR: #FEC254; TEXT-DECORATION: underline; } #title A:link, #title A:visited, #title A:active { COLOR: #FEC254; TEXT-DECORATION: none; } #title A:hover { COLOR: #FEC254; TEXT-DECORATION: underline; }  ', '', '1', '1');");

               $db_connect->query("INSERT INTO db_configuration VALUES ( 'http://www.domain.com/board/rpg', 'RPG Battle System', 'you@www.you.com', '0', '1', '1', '10', '1', '1', 'gif
jpeg
jpg
', '30', '20', '0', '0', '0', '5', '1000', '15', '150', '1', '15', '*', '{badword=goodword}', '1', '0', '0', '1', '0', '', 'MN YYYY', 'DD.MM.YYYY', 'DD.MM.YYYY, HH:II', 'DD.MM.YYYY=<b>Today</b>', '0', '0', '1', '1', '1', '0', '0', '', '', '1', '1', '0', '1', '1', 'gif
jpeg
jpg', '300', '1', 'jpg
gif
jpeg', '90', '90', '10000', '50', '5', '20', '1', '100', '5','".md5(uniqid(microtime()))."')");

 $db_connect->query("INSERT INTO db_shop_swords VALUES ( '1', 'yourchoice', '5', '50');"); 

 $db_connect->query("INSERT INTO db_shop_shields VALUES ( '1', 'yourchoice', '5', '50');"); 

 $db_connect->query("INSERT INTO db_shop_armour VALUES ( '1', 'yourchoice', '5', '50');"); 

 $db_connect->query("INSERT INTO db_shop_gloves VALUES ( '1', 'yourchoice', '5', '50');"); 

 $db_connect->query("INSERT INTO db_shop_helmet VALUES ( '1', 'yourchoice', '5', '50');"); 

 $db_connect->query("INSERT INTO db_shop_boots VALUES ( '1', 'yourchoice', '5', '50');"); 

 $db_connect->query("INSERT INTO db_shop_smallitems VALUES ( '1', 'yourchoice', '20', '20', '50');"); 

 $db_connect->query("INSERT INTO db_shop_specialitem VALUES ( '1', 'yourchoice', '100', '50');"); 

 $db_connect->query("INSERT INTO db_arena1 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena2 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena3 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena4 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena5 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena6 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena7 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena8 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena9 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 
 $db_connect->query("INSERT INTO db_arena10 VALUES ( '1', 'none', 'Empty','none','none','none','none','wait','none');"); 

 $db_connect->query("INSERT INTO db_levels VALUES ( '1', '100');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '2', '200');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '3', '300');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '4', '400');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '5', '500');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '6', '700');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '7', '1000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '8', '2000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '9', '3000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '10', '4000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '11', '5000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '12', '7000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '13', '10000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '14', '20000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '15', '30000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '16', '40000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '17', '50000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '18', '70000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '19', '100000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '20', '200000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '21', '300000');");  
 $db_connect->query("INSERT INTO db_levels VALUES ( '22', '400000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '23', '500000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '24', '700000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '25', '1000000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '26', '2000000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '27', '3000000');");  
 $db_connect->query("INSERT INTO db_levels VALUES ( '28', '4000000');");  
 $db_connect->query("INSERT INTO db_levels VALUES ( '29', '5000000');"); 
 $db_connect->query("INSERT INTO db_levels VALUES ( '30', '7000000');");  
header("Location: install.php?step=6");	
}

if($step==6) {
	if($send == "send") {
		if(!$username || !$useremail || !$userpassword) $error = "Not all fields were filled out.";
		else {
			$time = time();
			$db_connect->query("INSERT INTO db_users (username,userpassword,useremail,regemail,groupid,regdate,lastvisit,lastactivity,activation,useritem1,useritem2,useritem3,useritem4,useritem5,useritem6,useritem7,useritem8,userweapon,usershield,userarmour,usergloves,userhelmet,userboots,userextra1,userextra2,userroom) VALUES ('$username','".md5($userpassword)."','$useremail','$useremail','1','$time','$time','$time','1','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','useritem1','lobby')");
			header("Location: install.php?step=7");
			exit;
		}
	}
echo $header;
?>
<p><form method=post action="install.php">
<input type="hidden" name="step" value="6">
<input type="hidden" name="send" value="send">
Register the Admin:<br>
Name: <input class="input" name="username" value="<? echo $username; ?>" maxlength=30><br>
Email: <input class="input" name="useremail" value="<? echo $useremail; ?>" maxlength=150><br>
Password: <input class="input" name="userpassword" value="<? echo $userpassword; ?>" maxlength=20><br>
<input type="submit" value="Submit"></form> 
</p>
<p><? echo $error; ?></p>
<?
}

if($step==7) {
echo $header;
?>
<p>
<b>Installation successful!</b>
</p>
<p>
<b>IMPORTANT: Now delete install.php!</b>
</p>
<p>

</p>
<?
}

?>
</font></body>
</html>