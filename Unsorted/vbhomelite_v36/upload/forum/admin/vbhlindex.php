<?php
error_reporting(7);
require("./global.php");

// script variables and functions
// -----------------------------------------
$script = 'vbhlindex.php';
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

cpheader("<title>vbHome (lite) Article Indexer - by TECK</title>");

echo "<table cellpadding='2' cellspacing='0' border='0' align='center' width='90%' class='tblborder'>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table bgcolor='#524A5A' cellpadding='3' cellspacing='0' border='0' width='100%'>\n";
echo "<tr>\n";
echo "<td width='200'><a href='$threadlink' target='_blank'><img src='cp_logo.gif' width='160' height='49' border='0' alt='Click here for support...'></a></td>\n";
echo "<td><font size='2' color='#F7DE00'><b>vbHome (lite) Article Indexer (by TECK)</b></font><br>\n";
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

// INDEX SCRIPT
// --------------------------------------------------------------------
if ($step == '') {
  $step = 1;
}

if ($step == 1) {
// install information
// -----------------------------------------
maketableheader("Introduction");
echo "<tr>\n";
echo "<td>You are using the <b>vbHome (lite)</b> Indexer!<br>\n";
echo "Running this script will re-index your articles, if you performed an uninstall-install process.<p>\n";
echo "<font color='#006699'>THE SCRIPT WILL COMPLETE THE FOLLOWING STEPS:</font>\n";
echo "<ul type='circle'>\n";
echo "<li><font size='1'>tables and fields database verification</font></li>\n";
echo "<li><font size='1'>re-index old articles</font></li>\n";
echo "</ul>\n";
echo "IMPORTANT: Make sure you read first the <font color='#006699'><b>readmefirst.htm</b></font> file!<p></td>\n";
echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</a></font></td>\n";
echo "</tr>\n";

}  // end step 1

if ($step == 2) {
// check if vbHome (lite) is installed
// -----------------------------------------
maketableheader("Database verification");
echo "<tr>\n";
echo "<td>Checking to see if vbHome (lite) is already installed onto your server...<p>\n";

  if (dofields("articleid", "thread")) {
    echo "Success! The database does contain the vbHome (lite) fields.<p></td>\n";
    echo "</tr>\n";
    echo "<tr class='" . getrowbg() . "'>\n";
    echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</a></td>\n";
    echo "</tr>\n";
  } else {
    echo "<font color='#FF0000'><b>ERROR:</b></font> <font color='#006699'>vbHome (lite)</font> is not installed!<br>\n";
    echo "You need to have installed this script onto your server. Some of the fields this script attempts to modify are not present in your database.<p></td>\n";
    echo "<tr class='" . getrowbg()  ."'>\n";
    echo "<td><a href='index.php?s=$session[sessionhash]'><b>Control Panel »</b></a></td>\n";
    echo "</tr>\n";
  }

}  // end step 2

if ($step == 3) {
// create or alter tables
// -----------------------------------------
maketableheader("Re-indexing the articles");
echo "<tr>\n";
echo "<td>\n";

  $DB_site->reporterror = 1;

  $query[] = "ALTER TABLE thread DROP articleid";
  $explain[] = "Removing the <font color='#FF3300'>articleid</font> field... Done.";

  $query[] = "ALTER TABLE thread ADD articleid int(10) unsigned DEFAULT '0' NOT NULL AFTER pollid";
  $explain[] = "Adding a new <font color='#006699'>articleid</font> field... Done.<br>";

  doqueries();

  $threads=$DB_site->query("SELECT threadid FROM thread WHERE forumid='$articleforum' AND articleid=''");
  while ($thread=$DB_site->fetch_array($threads)) {
    $threadid = $thread['threadid'];

    $firstpostids=$DB_site->query("SELECT postid FROM post WHERE threadid='$threadid' ORDER BY postid LIMIT 1");
    while ($firstpostid=$DB_site->fetch_array($firstpostids)) {
      $articleid = $firstpostid['postid'];

      $query[] = "UPDATE thread SET articleid='$articleid' WHERE threadid='$threadid'";
      $explain[] = "Re-indexing  <font color='#006699'>article $articleid</font>... Done.";

      doqueries();
    }
  }

  if ($DB_site->errno != 0) {
    echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors during the setup.</p>\n";
    echo "Error number: " . $DB_site->errno . "<br>\n";
    echo "Error description: " . $DB_site->errdesc . "</p><p>";
  } else {
    echo "<p>Articles re-indexed successfully.</p><p>\n";
  }

echo "<tr>\n";
echo "<td colspan='2'>Update completed!<br>\n";
echo "The file the you need to delete: <font color='#006699'>$script</font><p></td>\n";
echo "<tr class='".getrowbg()."'>\n";
echo "<td><a href='index.php?s=$session[sessionhash]'><b>Control Panel »</b></a></td>\n";
echo "</tr>\n";

}  // end step 11

echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

cpfooter();

?>