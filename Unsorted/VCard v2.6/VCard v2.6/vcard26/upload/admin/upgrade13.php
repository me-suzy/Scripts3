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
// UPGRADE vCard 2.5 -> 2.6
//adminlog();
/*******************************************
script settings
*******************************************/
set_magic_quotes_runtime(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL);
$step = (!empty($HTTP_GET_VARS['step']))? $HTTP_GET_VARS['step'] : $HTTP_POST_VARS['step'];
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

$oldversion = '2.5';
$newversion = '2.6';
$scriptnumber = 13;
$thisscript = "upgrade13.php";

// localfunctions
function gotonext($extra='') {
	global $step,$thisscript;
	
	$nextstep = $step+1;
	echo "<p><a href='$thisscript?step=$nextstep'><b>Continue with the upgrade --&gt;</b></a> $extra</p>\n";
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
<?php
if (empty($step))
{
	$current_data = $DB_site->query_first("SELECT * FROM vcard_setting WHERE varname='vcardversion' ");
?>
	<p>Upgrade Script : <b>Version <?php echo "$oldversion -- to --> $newversion"; ?></b>.</p>
	<p>Your current version installed version is: [ <b>version <?php echo $current_data['value']; ?></b> ]</p>
	<p><a href="http://www.belchiorfoundry.com/vcard/docs/">READ carefull the upgrade documentation before make a mistake!!!</a></p>
	<p><b>Note</b>: You need have permission access to ALTER MySQL database structury. If you already did a vcard upgrading before then you have the permission to alter MySQL database tables. Only in few website this problem appear.</p>
	<p><i>Please be patient as some parts of this may take quite some time.</i></p>
	<p>
	Steps to upgrade: <b>Pay attetion:</b>
	<ol>
		<li>Turn OFF your website to upgrading</li>
		<br>
		<b>DOUBLE check if you uploaded these files:</b>
		<li>upload all files to upload/admin directory</li>
		<li>upload the <font color="Red"><b>NEW</b></font> language pack system (/language/english/<font color="Red">admin.lang.php</font> & upload/language/english/<font color="Red">user.lang.php</font>)*
		<li>Now continue with upgrading.
	</ul>
	<br>
	<br>
	
<table border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
<tr>
	<td>
		* The new language pack system: The language file was split into 2 files - admin.lang.php & user.lang.php - and these files is located inside a directory. The default language pack is ENGLISH, so the directory name is english.<br>
		If you want create your own language file, you can create a new directory with a new language set (ex. spanish). Inside this directory you copy the both files and translated them to the new tongue.<br>
		You can change the site language pack into control panel later.
	</td>
</tr>
</table>


<?php
	$step = 1;
	gotonext();
}
if ($step == 2)
{
	$_html = '<select name="new_site_lang">';
	$n_dirs = 0;
	$handle = opendir('./../language/');
	while($dirname = readdir($handle))
	{
		if ($dirname !='.' && $dirname!='..')
		{
			if(filetype('./../language/'.$dirname)=='dir')
			{
				$_html .= "<option value='$dirname'>$dirname</option>\n";
				$n_dirs++;
			}
		}
	}
	closedir($handle);
	$_html .= '</select>';
	if($n_dirs < 1)
	{
		echo 'You DIDNT uploaded the new language pack sytem to webserver. There is a new directory named <b>english</b> that must be uploaded.';
		echo 'You can add your own language set create a copy from english directory, rename it with your new language set name. Then translate the files user.lang.php and admin.lang.php.<br> You can translate only the end user file if you want.';
		exit;
	}
	echo "<form action='$thisscript' method='post'>
	Select the language set for your site:<br>
	$_html
	<input type='hidden' name='step' value='3'><p>
	<input type='submit' value='Continue with upgrade'>
	</form>
	";
	exit;

}
if ($step == 3)
{
	if(empty($HTTP_POST_VARS['new_site_lang']))
	{
		echo "There is a problem with your language setting. It is empty! Did you upload the new language directory to webserver?";
		exit;
	}
	$DB_site->query("ALTER TABLE `vcard_category` ADD `cat_ncards` INT UNSIGNED NOT NULL AFTER `cat_sort` ");
	$DB_site->query("ALTER TABLE `vcard_attach` CHANGE `messageid` `messageid` VARCHAR(25) NOT NULL ");
	$DB_site->query("UPDATE vcard_setting SET value='2.6' WHERE varname='vcardversion'  ");
	$DB_site->query("UPDATE vcard_setting SET optioncode='language',value='".$HTTP_POST_VARS['new_site_lang']."'  WHERE varname='site_lang' ");
	$DB_site->query("ALTER TABLE `vcard_setting` DROP `description` ");
	$DB_site->query("ALTER TABLE `vcard_setting` DROP `title` ");
	$DB_site->query("DELETE FROM vcard_cache");
	$DB_site->query("ALTER TABLE `vcard_sound` ADD `sound_order` TINYINT NOT NULL AFTER `sound_genre` ");
	echo 'Updated vcard settings ';
	gotonext();
}

if ($step == 4)
{
	echo 'Counting cards ....<br>';
	echo 'If you have many categories this action may take some time. Be patient!<p>';
	make_recount_ncards_to_cat();
	
	$DB_site->query(" ALTER TABLE `vcard_settinggroup` CHANGE `title` `varname` CHAR(100) NOT NULL ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='language' WHERE displayorder ='1' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='server' WHERE displayorder ='2' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='general' WHERE displayorder ='3' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='email' WHERE displayorder ='4' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='visual' WHERE displayorder ='5' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='user' WHERE displayorder ='6' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='admin' WHERE displayorder ='7' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='gallery' WHERE displayorder ='8' ");
	$DB_site->query(" UPDATE vcard_settinggroup SET varname='spam' WHERE displayorder ='9' ");

	gotonext();
}
if ($step == 5)
{
	echo 'Adding security check to attachment tables ....<br>';
	echo 'If you have many users uploaded image this action may take some time. Be patient!<p>';
	$user_cards = $DB_site->query("SELECT card_file,message_id FROM vcard_user WHERE card_file LIKE 'attachment.php?%' ");
	while($user_card = $DB_site->fetch_array($user_cards))
	{
		$user_attach = get_array_from_url($user_card['card_file'],'attachment.php?');
		$DB_site->query("UPDATE vcard_attach SET messageid='$user_card[message_id]' WHERE attach_id='$user_attach[id]' ");
		unset($attach);
	}
	$DB_site->free_result($cards);
	$DB_site->query(" DELETE FROM vcard_attach WHERE messageid='' ");
	gotonext();
}

if ($step == 6)
{
	echo "<p>Updating final vcard settings...<p>";
	$optionstemplate = generateoptions();
	$result = $DB_site->query("UPDATE vcard_template SET template='$optionstemplate' WHERE title='options'");
	echo "<p>Updated final vcard settings<p>";

	// insert default template data
	echo "<p>Updating original template set...<p>";
	$DB_site->query("DELETE FROM vcard_template_o ");

	$path = './vcard.style';
	if (file_exists($path) == 0)
	{
		$styletext='';
	}else{
		$filesize = filesize($path);
		$filenum = fopen($path,'r');
		$styletext = fread($filenum,$filesize);
		fclose($filenum);
	}
	
	if ($styletext == '')
	{
		echo "<p>Please ensure that the vcard.style file exists in the current directory and then reload this current page.</p>";
		exit;
	}
	
	$stylebits = explode('|||',$styletext);
	list($devnul,$styleversion) = each($stylebits); // 1
	list($devnul,$style['title']) = each($stylebits); // 2
	list($devnull,$numreplacements) = each($stylebits); // 3
	list($devnull,$numtemplates) = each($stylebits); // 4
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
		if (trim($title)!='')
		{
			$DB_site->query("INSERT INTO vcard_template_o (template_id,title,template) VALUES (NULL,'".addslashes($title)."','".addslashes($template)."')");
			/*
			if ($title =='topratedpage') {
				$DB_site->query("INSERT INTO vcard_template (template_id,title,template) VALUES (NULL,'".addslashes($title)."','".addslashes($template)."')"); 
			}
			*/
		}
	}
	echo "<p>Updated original template set.<p>";
	gotonext();
}

if ($step == 7)
{
?>

	<p><b>Upgrade complete!</b>
	<p>
	<ul><b>Change Log:</b>
		<li>updated - cache system</li>
		<li>music - better sorting system</li>
		<li>improved - database use, less database use</li>
	</ul>
	<br>
	<br>
	</p>
	
	<p><b>Note</b>: Please delete upgrade10.php,upgrade11.php, upgrade12.php, upgrade13.php, install.php and uninstall.php files! Leaving them are a security risk! <img src="http://belchiorfoundry.com/cgi-bin/version.cgi" width="1" height="1" alt="" border="0">

<?php
	echo "	<p><a href='./' target='blank'>Access your admin page here --></a><br></p>";
}
?>