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
// UPGRADE vCard 2.2.x -> 2.3
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


$oldversion = '2.2.x';
$newversion = '2.3';
$scriptnumber = 10;
$thisscript = "upgrade10.php";
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
<p><!--CyKuH [WTN]-->READ upgrade documentation before!!!</p>
	<br>
	<li><b>Turn OFF your website to upgrading</b></li>
	<ul><b>DOUBLE check if you uploaded these files:</b>
		<li>all files from upload/admin directory</li>
		<li>upload/language/eng.lang.inc</li>
		<li><font color="Red">upload/templates/*.ihtml</font> (the old tags won´t work in next releases)</li>
	</ul>
<?php
	$step = 1;
	gotonext();
}

if ($step == 2)
{
	$DB_site->query("DELETE FROM vcard_template_o ");
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'0','vcard version', 'vcardversion', '2.3', 'vcard version', 'vcardversion', '0') "); 
	$DB_site->query("INSERT INTO vcard_setting VALUES (NULL,'8','Online Users', 'online_users', '0', 'Do you want know online users feature?', 'yesno', '0') "); 
	$DB_site->query(" CREATE TABLE vcard_useronline ( 
	timestamp int(15) DEFAULT '0' NOT NULL, 
	ip varchar(40) NOT NULL, 
	file varchar(100) NOT NULL, 
	KEY (timestamp), 
	KEY ip (ip), 
	KEY file (file) 
	) ");
	$DB_site->query(" ALTER TABLE vcard_user CHANGE `message_id` `message_id` VARCHAR(25) NOT NULL ");

	gotonext();
}

if ($step == 3)
{
	// insert default template data
	echo "<p>Inserting data on tables...</p>";
	$path="./vcard.style";

	if (file_exists($path) == 0)
	{
		$styletext="";
	}
	else
	{
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
	echo "<p>Inserted data on tables.</p>";	
	gotonext();
}

if ($step == 4)
{
	echo "<p>Updating final vcard settings...<p>";
	$optionstemplate = generateoptions();
	$result = $DB_site->query("UPDATE vcard_template SET template='$optionstemplate' WHERE title='options'");
	echo "<p>Updated final vcard settings<p>";
	gotonext();
}

if ($step == 5)
{
?>

	<p><b>Upgrade complete!</b>
	<p>
	<ul><b>Change Log: <font color="Red"><b>Read carefully</b></font></b>
		<li>added- show online users into page</li>
		<li>added- new card style : full page card</li>
		<li>added- new card style : code piece card</li>
	</ul>
	<h4><!--CyKuH [WTN]-->If you created or customez the card template (*.ihtml file) YOU NEED UPDATE your TAGS in *.ihtml files because these old tags won´t work in next release</h4>
	</p>
	
	<p>Please delete upgrade1.php, upgrade2.php, upgrade3.php, upgrade4.php, upgrade5.php, upgrade6.php, upgrade7.php, upgrade8.php, upgrade9.php, upgrade10.php, install.php and uninstall.php files! Leaving them are a security risk! <!--CyKuH [WTN]-->

<?php
	echo "	<p><a href=\"./\" target=\"blank\">Access your admin page here --></a><br></p>";
}
?>