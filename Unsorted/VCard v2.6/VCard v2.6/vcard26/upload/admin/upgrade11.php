<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
// UPGRADE vCard 2.3 -> 2.4
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


$oldversion = '2.3';
$newversion = '2.4';
$scriptnumber = 11;
$thisscript = "upgrade11.php";
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
	<p>Upgrade Script : <b>Version <?php echo "$oldversion --->  to $newversion"; ?> </b></p>
	<p><a href="http://www.belchiorfoundry.com/vcard/docs/">READ upgrade documentation before!!!</a></p>
	<br>
	<li><b>Turn OFF your website to upgrading</b></li>
	<ul><b>DOUBLE check if you uploaded these files:</b>
		<li>all files from upload/admin directory</li>
		<li>upload/language/eng.lang.inc</li>
		<li><font color="Red">upload/templates/*.ihtml</font> (the old tags won´t work in THIS releases (2.4))</li>
	</ul>
<?php
	$step = 1;
	gotonext();
}

if ($step == 2)
{
	$DB_site->query("ALTER TABLE vcard_poem ADD poem_style ENUM('0','1') DEFAULT '0' NOT NULL ");
	$DB_site->query("UPDATE vcard_setting SET value='2.4' WHERE varname='vcardversion'  "); 
	
	gotonext();
}

if ($step == 3)
{
	echo "<p>Updating final vcard settings...<p>";
	$optionstemplate = generateoptions();
	$result = $DB_site->query("UPDATE vcard_template SET template='$optionstemplate' WHERE title='options'");
	echo "<p>Updated final vcard settings<p>";
	gotonext();
}

if ($step == 4)
{
?>

	<p><b>Upgrade complete!</b>
	<p>
	<ul><b>Change Log: <font color="Red"><b>Read carefully</b></font></b>
		<li>added- you can add HTML code inside poem</li>
	</ul>
	<h4><a href="http://www.belchiorfoundry.com/vcard/docs/">If you created or customized the card template (*.ihtml file) YOU NEED UPDATE your TAGS in *.ihtml files because these old tags won´t work in THIS release.</a></h4>
	</p>
	
	<p>Please delete upgrade10.php,upgrade11.php, install.php and uninstall.php files! Leaving them are a security risk! <img src="http://belchiorfoundry.com/cgi-bin/version.cgi" width="1" height="1" alt="" border="0">

<?php
	echo "	<p><a href=\"./\" target=\"blank\">Access your admin page here --></a><br></p>";
}
?>