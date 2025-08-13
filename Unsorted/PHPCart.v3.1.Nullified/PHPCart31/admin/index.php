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

$l69 = "The program does not have permission to write in the file admin_1.php. This file needs to be chmod'ed 666.";
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
if (!is_writable("admin_1.php")) trigger_error($l69);

if ($submit) {

	if ($PHP_AUTH_USER=="demo") {
	echo "<BR><BR><center>\n";
	echo "<font color=\"#F26522\"><B>DEMO:</B></font> No Settings Updated!<br><br>\n";
	echo "Redirecting...\n";
	echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>\n";
	echo "</center><BR><BR>\n";
	} else {

	$content  = "<?\n";
	$content .= "\$adminUsername	= \"".addslashes($adminUsernameNew)."\";\n";
	$content .= "\$adminPassword	= \"".addslashes($adminPasswordNew)."\";\n";
	$content .= "\$adminPassword2	= \"".addslashes($adminPasswordNew2)."\";\n";
	$content .= "?>";

	$filePointer = fopen("admin_1.php", "w");
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
echo "<form action='index.php' method='post'>\n";
echo "<div align='center'>\n";
echo "<center>\n";
echo "<table border='0' cellpadding='0' cellspacing='8' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Set Admin Username:</font></td>\n";
echo "<td width='41%'><input type='text' name='adminUsernameNew' size='20' value='$adminUsername'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Password:</font></td>\n";
echo "<td width='41%'><input type='text' name='adminPasswordNew' size='20' value='$adminPassword'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Repeat Password:</font></td>\n";
echo "<td width='41%'><input type='text' name='adminPasswordNew2' size='20' value='$adminPassword2'></td>\n";
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
?>
<?pageFooter();
}
?>