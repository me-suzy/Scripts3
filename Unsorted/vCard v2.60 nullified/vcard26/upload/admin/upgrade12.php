<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
// UPGRADE vCard 2.4 -> 2.5
//adminlog();
/*******************************************
script settings
*******************************************/
set_magic_quotes_runtime(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL);
$step = $HTTP_GET_VARS['step'];
/*******************************************
load login/password to dbase
*******************************************/
require("./config.inc.php");

/*******************************************
initiate dbase
*******************************************/
require("./db_mysql.inc.php");
$DB_site = new DB_Sql_vc;
$DB_site->server = $hostname;
$DB_site->user = $dbUser;
$DB_site->password = $dbPass;
$DB_site->database = $dbName;
$DB_site->connect();
$dbPass = "";		// clear database password variable to avoid retrieving
$DB_site->password = "";

/*******************************************
initiate functions load
*******************************************/
require("./functions.inc.php");
require("./adminfunctions.inc.php");

$oldversion = '2.4';
$newversion = '2.5';
$scriptnumber = 11;
$thisscript = "upgrade12.php";

// localfunctions
function gotonext($extra="") {
	global $step,$thisscript;
	
	$nextstep = $step+1;
	echo "<p><a href=\"$thisscript?step=$nextstep\"><b>Continue with the upgrade --&gt;</b></a> $extra</p>\n";
}
?>
<HTML>
	<HEAD>
	<title>vCard Upgrade Script</title>
        <style type="text/css">
        BODY            {background-color: #F0F0F0; color: #3F3849; font-family: Verdana; font-size: 12px;}
        UL              {font-family: Verdana; font-size: 12px;}
        LI              {font-family: Verdana; font-size: 12px;}
        P               {font-family: Verdana; font-size: 12px;}
        TD              {font-family: Verdana; font-size: 12px;}
        TR              {font-family: Verdana; font-size: 12px;}
        H2              {font-family: Verdana; font-size: 14px;}

        A               {font-family: Verdana; text-decoration: none;}
        A:HOVER         {font-family: Verdana; COLOR: #FFAC00;}
        A:ACTIVE        {font-family: Verdana; COLOR: #FFAC00;}

        FORM            {font-family: Verdana; font-size: 10px;}
        SELECT          {font-family: Verdana; font-size: 10px;}
        INPUT           {font-family: Verdana; font-size: 10px;}
        TEXTAREA        {font-family: Verdana; font-size: 10px;}
        .title          {font-family: Verdana; font-size: 25px;}
        </style>
        </HEAD>
<BODY bgcolor="#CCCCCC" text="#3F3849" link="#3F3849" vlink="#3F3849" alink="#3F3849" leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
<p class="title"><b>vCard Upgrade Script</font></b></p><br>
<i><font size="-1">(Note: Please be patient as some parts of this may take quite some time.)</font></i>
<?php
if ($step == "")
{
?>
	<p>Upgrade Script : <b>Version <?php echo "$oldversion -- to --> $newversion"; ?> </b></p>
<p><!--CyKuH [WTN]-->READ upgrade documentation before!!!</p>
	<br>
	<li><b>Turn OFF your website to upgrading</b></li>
	<ul><b>DOUBLE check if you uploaded these files:</b>
		<li>all files from upload/admin directory</li>
		<li>upload/language/eng.lang.inc</li>
	</ul>
	<br>
	<br>
	<p><b>You need have permission access to ALTER MySQL database structury.</b></p>
<?php
	$step = 1;
	gotonext();
}

if ($step == 2)
{
	$DB_site->query("DROP TABLE IF EXISTS vcard_cache");
	$DB_site->query("CREATE TABLE vcard_cache (
  title varchar(100) NOT NULL default '',
  content mediumtext NOT NULL,
  date int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (title),
  KEY date (date)) ");
  	$DB_site->query("INSERT INTO vcard_cache VALUES ('cachedate', '', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('getdropdownlist','', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('gettabletextlist','', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('getevenlist', '', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('getcategorieslist','', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('gettopcardsofweek','', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('gettopcardsofday', '', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('categories_table_cat', '', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('categories_table_upcat','', '1024114218') ");
	$DB_site->query("INSERT INTO vcard_cache VALUES ('getnewcardlist', '', '1024114218') ");
	$DB_site->query("ALTER TABLE `vcard_abook` CHANGE `ab_password` `ab_password` VARCHAR(32) DEFAULT '0' NOT NULL ");
	$DB_site->query("ALTER TABLE `vcard_abook` ADD `ab_md5password` varchar(32) NOT NULL AFTER `ab_date` ");
	$DB_site->query("UPDATE vcard_setting SET value='2.5' WHERE varname='vcardversion'  "); 
	$DB_site->query("ALTER TABLE `vcard_category` ADD `cat_link` TINYINT(1) NOT NULL AFTER `cat_img` ");

$ntemplate ='<font face="$site_font_face" size="3" class="csstablecatname"><a href="gbrowse.php?cat_id=$catinfo[cat_id]">$catinfo[cat_name]</a>
<br>
<font size="1" class="csstablecatnpostcard">$catinfo[totalcards] $MsgPostcards</font></font>
';
	$DB_site->query("INSERT INTO vcard_template VALUES (NULL,'category_textlink','$ntemplate')");
	gotonext();
}

if ($step == 3)
{
	echo 'Updating address book users table<br>';
	echo 'If you have many registered users this action may take some time. Be patient!<p>';

	$users_array = $DB_site->query("SELECT * FROM vcard_abook ");
	while($user_info = $DB_site->fetch_array($users_array))
	{
		$sql = "UPDATE vcard_abook SET ab_md5password='".md5($user_info['ab_password'])."' WHERE ab_id='$user_info[ab_id]' ";
		$DB_site->query($sql);
		echo '. ';
	}
	echo '<p><br>';
	gotonext();

}

if ($step == 4)
{
	echo "<p>Updating final vcard settings...<p>";
	$DB_site->query("DELETE FROM vcard_template_o ");
	$optionstemplate = generateoptions();
	$result = $DB_site->query("UPDATE vcard_template SET template='$optionstemplate' WHERE title='options'");
	echo "<p>Updated final vcard settings<p>";

	// insert default template data
	echo "<p>Updating original template set...<p>";
	$path="./vcard.style";
	if (file_exists($path) == 0)
	{
		$styletext="";
	}else{
		$filesize 	= filesize($path);
		$filenum 	= fopen($path,"r");
		$styletext 	= fread($filenum,$filesize);
		fclose($filenum);
	}
	
	if ($styletext == '')
	{
		echo "<p>Please ensure that the vcard.style file exists in the current directory and then reload this current page.</p>";
		exit;
	}
	
	$stylebits = explode("|||",$styletext);
	list($devnul,$styleversion) 		= each($stylebits); // 1
	list($devnul,$style[title]) 		= each($stylebits); // 2
	list($devnull,$numreplacements) 	= each($stylebits); // 3
	list($devnull,$numtemplates) 		= each($stylebits); // 4
	$counter = 0;
	while ($counter++<$numreplacements)
	{
	
		list($devnull,$findword)	= each($stylebits);
		list($devnull,$replaceword)	= each($stylebits);
	}
	
	$counter = 0;
	while ($counter++<$numtemplates)
	{
		list($devnull,$title) = each($stylebits);
		list($devnull,$template) = each($stylebits);
		if (trim($title) != "")
		{
			$DB_site->query("INSERT INTO vcard_template_o (template_id,title,template) VALUES (NULL,'".addslashes($title)."','".addslashes($template)."')");
			if ($title =='topratedpage') {
				//$DB_site->query("INSERT INTO vcard_template (template_id,title,template) VALUES (NULL,'".addslashes($title)."','".addslashes($template)."')"); 
			}
		}
	}
	echo "<p>Updated original template set.<p>";
	gotonext();
}

if ($step == 5)
{
?>

	<p><b>Upgrade complete!</b>
	<p>
	<ul><b>Change Log:</b>
		<li>updated - cache database structury</li>
		<li>updated - SQL queries</li>
		<li>category - now you can choose the link type (text or image) to category</li>
		<li>template - new template 'category_textlink'</li>
		<li>backup utility (download via web browser or save the database backup copy into webserver)
	</ul>
	<br>
	<br>
	<h4><!--CyKuH [WTN]--><font color="Red">If you created or customized the card template (*.ihtml file) YOU NEED UPDATE your TAGS in *.ihtml files because these old tags won´t work in THIS release.</font></h4>
	</p>
	
	<p>Please delete upgrade10.php,upgrade11.php, upgrade12.php install.php and uninstall.php files! Leaving them are a security risk! <!--CyKuH [WTN]-->

<?php
	echo "	<p><a href=\"./\" target=\"blank\">Access your admin page here --></a><br></p>";
}
?>