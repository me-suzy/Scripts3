<?php
error_reporting(7);
require("./global.php");

// script variables and functions
// -----------------------------------------
$script="vbhome_lite.php";
$version="2.0";
$threadlink="http://www.vbulletin.org/forum/showthread.php?s=&threadid=36756";

// check if the field exists
// by Freddie Bingham
function dofields($field,$table) {
	global $DB_site;

	$DB_site->reporterror=0;
	$DB_site->query("SELECT COUNT(".$field.") AS count FROM ".$table);
	$errno=$DB_site->errno;
	if (!$errno) {
		$errno = 0;
	}
	$DB_site->reporterror=1;

	if ($errno) {
		return 0;
	} else {
		return 1;
	}
}

// do the queries
function doqueries() {
  global $DB_site,$step,$query,$explain;

  if ($step!=2) {
    $DB_site->reporterror=1;
  }

  while (list($key,$val)=each($query)) {
    echo "$explain[$key]<br>\n";
    echo "<"."!-- ".htmlspecialchars($val)." --".">\n\n";
    $DB_site->query($val);
  }
  unset ($query);
  unset ($explain);
}

// do the input code
function doinput($title,$name,$value="",$htmlise=1,$size=35) {
	if ($htmlise) {
		$value=htmlspecialchars($value);
	}

  echo "<tr class='".getrowbg()."'>\n";
  echo "<td>$title</td>\n";
  echo "<td><p id='submitrow'><input type='text' size='$size' name='$name' value='$value'></p></td>\n";
  echo "</tr>\n";
}

cpheader("<title>vbHome Lite ".($version)." Install Script - by nakkid</title>");

echo "<table cellpadding='2' cellspacing='0' border='0' align='center' width='90%' class='tblborder'>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table bgcolor='#524A5A' cellpadding='3' cellspacing='0' border='0' width='100%'>\n";
echo "<tr>\n";
echo "<td width='200'><a href='$threadlink' target='_blank'><img src='cp_logo.gif' width='160' height='49' border='0' alt='Click here for support...'></a></td>\n";
echo "<td><font size='2' color='#F7DE00'><b>vbHome Lite v.$version Install Script (by nakkid)</b></font><br>\n";
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

if ($step=="") {
  $step=1;
}

// INSTALL SCRIPT
// --------------------------------------------------------------------
if ($step==1) {
// install information
// -----------------------------------------
maketableheader("Introduction");
echo "<tr>\n";
echo "<td>Welcome to <b>vbHome Lite</b> version $version!<br>\n";
echo "Running this script will install the home(vBulletin powered) page onto your server.<p>\n";
echo "vbHome Lite will turn your home page, from a regular html based page to a vBulletin one. That means you can easily insert\n";
echo "all your vBulletin php code, options, templates, etc. into the index.php home page file, included in this package.<p>\n";
echo "<font color='#006699'>THE SCRIPT WILL COMPLETE THE FOLLOWING STEPS:</font>\n";
echo "<ul type='circle'>\n";
echo "<li><font size='1'>fields and tables database verification</font></li>\n";
echo "<li><font size='1'>alter vB tables</font></li>\n";
echo "</ul>\n";
echo "IMPORTANT: Make sure you read first the <font color='#006699'>install.txt</font> file!<p></td>\n";
echo "<tr class='".getrowbg()."'>\n";
echo "<td><a href='$script?s=$session[sessionhash]&step=".($step+1)."'><b>Continue »</a></td>\n";
echo "</tr>\n";

}  // end step 1

if ($step>=2) {
  include("./config.php");
}

if ($step==2) {
// check if vbHome Lite is installed
// -----------------------------------------
maketableheader("Database verification");
echo "<tr>\n";
echo "<td>Checking to see if vbHome Lite is already installed onto your server...<p>\n";

  if (dofields("articleid","thread")) {
    echo "<font color='#FF0000'><b>ERROR:</b> <font color='#006699'>vbHome</font> is installed!</font><br>\n";
    echo "You already installed this script onto your server. Some of the fields this script attempts to create are present in your database.<p></td>\n";
    echo "<tr class='".getrowbg()."'>\n";
    echo "<td></td>\n";
    echo "</tr>\n";
  } else {
    echo "Success! The database doesn't contain any vbHome Lite fields.<p></td>\n";
    echo "</tr>\n";
    echo "<tr class='".getrowbg()."'>\n";
    echo "<td><a href='$script?s=$session[sessionhash]&step=".($step+1)."'><b>Continue »</a></td>\n";
    echo "</tr>\n";
  }

}  // end step 2

if ($step==3) {
// create or alter tables
// -----------------------------------------
maketableheader("Altering and creating the tables");
echo "<tr>\n";
echo "<td>\n";

$DB_site->reporterror=1;

$query[]="ALTER TABLE thread ADD articleid int(10) unsigned DEFAULT '0' NOT NULL";
$explain[]="Adding to thread table the <font color='#006699'>articleid</font> field... Done.\n";

$query[]="ALTER TABLE thread ADD INDEX(articleid)";
$explain[]="Indexing the <font color='#006699'>articleid</font> field... Done.\n";

doqueries();

if ($DB_site->errno!=0) {
  echo "<p>WARNING: The script reported errors in the installation of the tables. Only continue if you are sure that they are not serious.</p>\n";
  echo "Error number: ".$DB_site->errno."<br>\n";
  echo "Error description: ".$DB_site->errdesc."</p><p></td>";
} else {
  echo "<p>Tables set successfully.</p><p></td>\n";
}

echo "<tr>\n";
echo "<td colspan='2'>Setup completed!<br>\n";
echo "The file the you need to delete: <font color='#006699'>$script</font><p></td>\n";
echo "<tr class='".getrowbg()."'>\n";
echo "<td><a href='index.php?s=$session[sessionhash]'><b>Control Panel »</b></a></td>\n";
echo "</tr>\n";

}  // end step 3

echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

cpfooter();

?>