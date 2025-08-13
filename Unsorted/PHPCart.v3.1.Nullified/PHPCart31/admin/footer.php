<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.1                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////
$l69 = "The program does not have permission to write in the file footer.inc. This file needs to be chmod'ed 666.";
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
if (!is_writable("footer.inc")) trigger_error($l69);

if ($submit) {

	if ($PHP_AUTH_USER=="demo") {
	echo "<BR><BR><center>\n";
	echo "<font color=\"#F26522\"><B>DEMO:</B></font> No Settings Updated!<br><br>\n";
	echo "Redirecting...\n";
	echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>\n";
	echo "</center><BR><BR>\n";
	} else {

	$content .= stripslashes($foot_html);

	$filePointer = fopen("footer.inc", "w");
	fwrite($filePointer, $content);
	fclose($filePointer);

	echo "<BR><BR><center>\n";
	echo "<B>Setting updated!</B><br><br>\n";
	echo "Redirecting...\n";
	echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>\n";
	echo "</center><BR><BR>\n";

	pageFooter();
	}
}
if (!$submit) {
echo "<p align=center><font size=2 face=Verdana><b>Footer Setup!</b></font></div>\n";
echo "<form action='footer.php' method='post'>\n";
echo "<div align='center'>\n";
echo "<center>\n";
echo "<table border='0' cellpadding='0' cellspacing='8' width='55%'>\n";
echo "<tr>\n";
echo "<td width='135'><font size='2' face='Verdana'>Footer HTML:</font></td>\n";
echo "<td width='228'><p><textarea rows='9' name='foot_html' cols='55'>";
include("footer.inc");
echo "</textarea></p></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='100%' colspan='2'>\n";
echo "<p align='center'><input type='submit' name='submit' value='Save the settings'></p>\n";
	if ($PHP_AUTH_USER=="demo") {
	echo "<div align=\"center\">";
	echo "  <center>";
	echo "  <table border=\"0\" cellpadding=\"5\" cellspacing=\"5\" width=\"80%\" align=\"center\">";
	echo "    <tr>";
	echo "      <td><b><font color=\"#F26522\">NOTE:</font></b><BR> As this is a demo no settings will be saved.</td>";
	echo "    </tr>";
	echo "  </table>";
	echo "  </center>";
	echo "</div>";
	}
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "</div>\n";
echo "</form>\n";
?>
<?pageFooter();
}
?>