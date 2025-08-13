<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.0                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Tested by            : WTN Team                                         //
// Packaged by          : WTN Team                                         //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////
$l69 = "The program does not have permission to write in the file header.inc. This file needs to be chmod'ed 666.";
$l75 = "Access denied";
$l61 = "Settings";
require("admin_1.php");
function auth() {
	global $l61, $l75;

	header("WWW-Authenticate: Basic realm=\"$l61\"");
	header("HTTP/1.0 401 Unauthorized");

	echo $l75;
	exit;
}
if ($PHP_AUTH_USER != $adminUsername || $PHP_AUTH_PW != $adminPassword) auth();

require("hf.php");
pageHeader();
if (!is_writable("header.inc")) trigger_error($l69);

if ($submit) {
	if ($adminPasswordNew != $adminPasswordNew2) trigger_error();

	$content .= stripslashes($head_html);

	$filePointer = fopen("header.inc", "w");
	fwrite($filePointer, $content);
	fclose($filePointer);

	echo "<font size='2' face='Verdana'>Setting updated!</font><br/><br/><a href='main.php'><font size='2' face='Verdana'>Back</font></a>\n";

	pageFooter();
	exit;
}

echo "<p align=center><font size=2 face=Verdana><b>Header Setup!</b></font></div>\n";
echo "<form action='header.php' method='post'>\n";
echo "<div align='center'>\n";
echo "<center>\n";
echo "<table border='0' cellpadding='0' cellspacing='8' width='55%'>\n";
echo "<tr>\n";
echo "<td width='135'><font size='2' face='Verdana'>Header HTML:</font></td>\n";
echo "<td width='228'><p><textarea rows='9' name='head_html' cols='55'>";
include("header.inc");
echo "</textarea></p></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='100%' colspan='2'>\n";
echo "<p align='center'><input type='submit' name='submit' value='Save the settings'></p>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "</div>\n";
echo "</form>\n";
echo "<p align=center><font face=Verdana size=2>Proceed to Main Menu? <a href=\"main.php\">Click Here</a></font></p>\n";
?>
<?pageFooter();?>