<?php
error_reporting(7);
require("./global.php");

// script variables and functions
// -----------------------------------------
$script = 'vbhl34up.php';
$version = '3.4';
$versionold = '3.3';
$threadlink = 'http://www.vbulletin.org/forum/showthread.php?s=&threadid=36756';

// check if the field exists
// by Freddie Bingham
function dofields($field, $table) {
  global $DB_site;

  $DB_site->reporterror = 0;
  $DB_site->query("SELECT COUNT(" . $field . ") AS count FROM " . $table);
  $errno = $DB_site->errno;
  if (!$errno) {
    $errno = 0;
  }
  $DB_site->reporterror = 1;
  if ($errno) {
    return 0;
  } else {
    return 1;
  }
}

// do the templates
function dotemplate($title, $template, $templatesetid=-1) {
  global $DB_site,$step;

  if ($step != 2) {
    $DB_site->reporterror = 1;
  }
  $DB_site->query("INSERT INTO template (templateid,templatesetid,title,template) VALUES (NULL,'$templatesetid','" . addslashes($title) . "','" . addslashes($template) . "')");
  echo "Installing <font color='#006699'>$title</font> template... Done.<br>\n";
}

// do the queries
function doqueries() {
  global $DB_site,$step,$query,$explain;

  if ($step != 2) {
    $DB_site->reporterror = 1;
  }

  while (list($key,$val) = each($query)) {
    echo "$explain[$key]<br>\n";
    echo "<"."!-- ".htmlspecialchars($val)." --".">\n\n";
    $DB_site->query($val);
  }
  unset ($query);
  unset ($explain);
}

// templates code
// -----------------------------------------
$tplname01 = 'home_articlelink';
$tplcode01 = '<smallfont> <nobr>... <a href="$bburl/showthread.php?s=$session[sessionhash]&threadid=$article[threadid]">read more</a></nobr></smallfont>';
$tplname02 = 'home_avatar';
$tplcode02 = '<a href="$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$article[postuserid]"><img border="0" src="$avatarurl" align="right" hspace="10" vspace="10" alt="View $article[postusername]\'s Profile"></a>';

cpheader("<title>vbHome (lite) v".($version)." Upgrade Script - by TECK</title>");

echo "<table cellpadding='2' cellspacing='0' border='0' align='center' width='90%' class='tblborder'>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table bgcolor='#524A5A' cellpadding='3' cellspacing='0' border='0' width='100%'>\n";
echo "<tr>\n";
echo "<td width='200'><a href='$threadlink' target='_blank'><img src='cp_logo.gif' width='160' height='49' border='0' alt='Click here for support...'></a></td>\n";
echo "<td><font size='2' color='#F7DE00'><b>vbHome (lite) v$version Upgrade Script (by TECK)</b></font><br>\n";
echo "<font size='1' color='#F7DE00'>(For support click <a href='$threadlink' target='_blank'><font color='#F7DE00'>here</font></a>)</font></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table cellpadding='1' cellspacing='0' border='0' align='center' width='90%' class='tblborder'>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table cellpadding='4' cellspacing='0' border='0' width='100%' class='secondalt'>\n";


// UPGRADE SCRIPT
// --------------------------------------------------------------------
if ($step == '') {
  $step = 1;
}

if ($step == 1) {
// upgrade information
// -----------------------------------------
maketableheader("Introduction");
echo "<tr>\n";
echo "<td>Welcome to <b>vbHome (lite)</b> version $version!<br>\n";
echo "Running this script will perform an upgrade from version $versionold to version $version.<p>\n";
echo "Once the upgrade completed, you will need to reupload all vbHome (lite) files onto your server and set again your vbHome Options, since they are reset to default values.<br>\n";
echo "Also, double-check the new steps you MUST perform, in <font color='#006699'><b>readmefirst.htm</b></font> file.<p>\n";
echo "<font color='#006699'>THE SCRIPT WILL COMPLETE THE FOLLOWING STEPS:</font>\n";
echo "<ul type='circle'>\n";
echo "<li><font size='1'>tables and fields database verification</font></li>\n";
echo "<li><font size='1'>install needed templates</font></li>\n";
echo "<li><font size='1'>install new vbHome (lite) options</font></li>\n";
echo "</ul>\n";
echo "IMPORTANT: Make sure you read first the <font color='#006699'><b>readmefirst.htm</b></font> file!<p></td>\n";
echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</b></a></font></td>\n";
echo "</tr>\n";

}  // end step 1

if ($step >= 2) {
  include("./config.php");
}

if ($step == 2) {
// check if vbHome (lite) is installed
// -----------------------------------------
maketableheader("Database verification");
echo "<tr>\n";
echo "<td>Checking to see if vbHome (lite) is already installed onto your server...<p>\n";

  if (dofields("articleid", "thread")) {
    echo "Success! The database does contain any vbHome (lite) fields.<p></td>\n";
    echo "</tr>\n";
    echo "<tr class='" . getrowbg() . "'>\n";
    echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</b></a></td>\n";
    echo "</tr>\n";
  } else {
    echo "<font color='#FF0000'><b>ERROR:</b></font> <font color='#006699'>vbHome (lite)</font> is not installed!<br>\n";
    echo "Some of the fields this script attempts to verify are not present in your database.<p></td>\n";
    echo "<tr class='" . getrowbg()  ."'>\n";
    echo "<td><a href='index.php?s=$session[sessionhash]'><b>Control Panel »</b></a></td>\n";
    echo "</tr>\n";
  }

}  // end step 2

if ($step == 3) {
// install templates
// -----------------------------------------
maketableheader("Templates set installation");
echo "<tr>\n";
echo "<td>\n";

  $DB_site->reporterror = 1;

  dotemplate($tplname01, $tplcode01);
  dotemplate($tplname02, $tplcode02);

  if ($DB_site->errno != 0) {
    echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors while modifying the database. Continue only if you are sure that they are not serious.</p>\n";
    echo "Error number: " . $DB_site->errno . "<br>\n";
    echo "Error description: " . $DB_site->errdesc . "</p><p></td>";
  } else {
    echo "<p>Templates installed successfully.</p><p></td>\n";
  }

echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</b></a></td>\n";
echo "</tr>\n";

}  // end step 3


if ($step == 4) {
// set the options
// -----------------------------------------
maketableheader("Setting the new vbHome (lite) options");
echo "<tr>\n";
echo "<td>\n";

  $query[] = "DELETE FROM settinggroup WHERE settinggroupid=99";
  $explain[] = "Removing old options group... Done.";

  $query[] = "DELETE FROM setting WHERE settinggroupid=99";
  $explain[] = "Removing old options... Done.<br>";

  $query[] = "INSERT INTO settinggroup VALUES (99,'vbHome Page','99')";
  $explain[] = "Adding new options group... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Articles Forum','articleforum','2','The forum ID assigned to your articles home page.','','1')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Journalists Usergroup','articlegroup','8','The usergroup ID assigned to your journalists.','','2')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Number of Articles displayed','articlemax','10','The number of articles listed on your home page. Set this to 0 to disable it.','','3')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Maximum Characters per article','articlemaxchars','5000','The maximum number of characters you want to allow per article. Set this to 0 to disable it.','','4')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Html Code in article?','articlehtml','0','Enable or disable the Html Code option. You must enable it ONLY if the Articles Forum have that option On.','yesno','5')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Icon in article?','articleicon','0','Enable or disable the Icon option.','yesno','6')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Avatar in article?','articleavatar','0','Enable or disable the Avatar option.','yesno','7')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Smilies in article?','articlesmilies','0','Enable or disable the Smilies option.','yesno','8')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Maximum Number of latest threads','threadmax','10','The maximum number of your latest forum threads. Set this to 0 to disable it.','','9')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Latest Threads title length','threadmaxchars','32','The maximum number of characters for your latest threads title. Set this to 0 to disable it.','','10')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Web Site Search?','activesearch','0','Enable or disable the Web Site Search option.','yesno','11')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Private Messages?','activepm','0','Enable or disable the Private Messages option.','yesno','12')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Advertisements?','activeadvertise','0','Enable or disable the Advertisement option.','yesno','13')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Affiliate Links?','activelinks','0','Enable or disable the Affiliate Links option.','yesno','14')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Poll?','activepoll','0','Enable or disable the Poll option.','yesno','15')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Allowed Users to post a poll','activepollusers','1,3','If you would like to allow more then one user to post polls then enter the User ID\'s separated by commas.','','16')";
  $explain[] = "Adding option... Done.";

  doqueries();

  if ($DB_site->errno != 0) {
    echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors while setting the options.</p>\n";
    echo "Error number: " . $DB_site->errno . "<br>\n";
    echo "Error description: " . $DB_site->errdesc . "</p><p></td>";
  } else {
    echo "<p>Options set successfully.</p><p></td>\n";
  }

echo "<tr>\n";
echo "<td colspan='2'>Upgrade completed!<br>\n";
echo "The file the you need to delete: <font color='#006699'>$script</font><p></td>\n";
echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='index.php?s=$session[sessionhash]'><b>Control Panel »</b></a></td>\n";
echo "</tr>\n";

}  // end step 4


echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

cpfooter();

?>