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
$l69 = "The program does not have permission to write in the file configuration_1.php. This file needs to be chmod'ed 666.";
$l75 = "Access denied";
$l61 = "Settings";
require("admin_1.php");
require("configuration_1.php");
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
if (!is_writable("configuration_1.php")) trigger_error($l69);

if ($submit) {

	if ($PHP_AUTH_USER=="demo") {
	echo "<BR><BR><center>\n";
	echo "<font color=\"#F26522\"><B>DEMO:</B></font> No Settings Updated!<br><br>\n";
	echo "Redirecting...\n";
	echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>\n";
	echo "</center><BR><BR>\n";
	} else {

	$content  = "<?\n";
	$content .= "\$zone		= \"".addslashes($zoneNew)."\";\n";
	$content .= "\$companyName	= \"".addslashes($companyNameNew)."\";\n";
	$content .= "\$home		= \"".addslashes($homeNew)."\";\n";
	$content .= "\$salesVAT		= \"".addslashes($salesVATNew)."\";\n";
	$content .= "\$PostalAmount	= \"".addslashes($PostalAmountNew)."\";\n";
	$content .= "\$companyPhone	= \"".addslashes($companyPhoneNew)."\";\n";
	$content .= "\$salesEmail	= \"".addslashes($salesEmailNew)."\";\n";
	$content .= "\$referers		= \"".addslashes($referersNew)."\";\n";
	$content .= "\$headerColor	= \"".addslashes($headerColorNew)."\";\n";
	$content .= "\$titleColor	= \"".addslashes($titleColorNew)."\";\n";
	$content .= "\$rowsColor	= \"".addslashes($rowsColorNew)."\";\n";
	$content .= "\$TextColor	= \"".addslashes($TextColorNew)."\";\n";
	$content .= "\$PgBack		= \"".addslashes($PgBackNew)."\";\n";
	$content .= "\$font		= \"".addslashes($fontNew)."\";\n";
	$content .= "\$fontSize		= \"".addslashes($fontSizeNew)."\";\n";
	$content .= "\$notes_active	= \"".addslashes($notes_activeNew)."\";\n";
	$content .= "\$language		= \"".addslashes($languageNew)."\";\n";
	$content .= "\$currency		= \"".addslashes($currencyNew)."\";\n";
	$content .= "\$enableCC		= \"".addslashes($enableCCNew)."\";\n";
	$content .= "\$enableCopy	= \"".addslashes($enableCopyNew)."\";\n";
	$content .= "?>";

	$filePointer = fopen("configuration_1.php", "w");
	fwrite($filePointer, $content);
	fclose($filePointer);

	echo "<BR><BR><center>\n";
	echo "<B>Setting updated!</B><br><br>\n";
	echo "Redirecting...\n";
	echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>\n";
	echo "</center><BR><BR>\n";
	}

	pageFooter();
}
if (!$submit) {
$a = "$HTTP_HOST"; 
$b = explode(".", $a); 
$c = "gakdskdkf";
$d = "$DOCUMENT_ROOT";
$e = "$SCRIPT_FILENAME";
$f = "$SERVER_ADMIN";
echo "<!--CyKuH [WTN]-->";
echo "Nullified WTN Release.";
echo "<p align=center><font size=2 face=Verdana><b>Configuration!</b></font></div>\n";
echo "<p align=center><font size=2 face=Verdana>Current server time\n";
$timestamp = time(); 
$hoursdiff = $zone;
$hoursdiff = $hoursdiff * 3600; 
$timestamp = $timestamp - $hoursdiff; 
$time1 = date("h:i A", $timestamp); 
print "$time1"; 
echo "</font></div>\n";
echo "<form action='configuration.php' method='post'>\n";
echo "<div align='center'>\n";
echo "<center>\n";
echo "<table border='0' cellpadding='0' cellspacing='8' width='55%'>\n";

echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Time Zone:</font></td>\n";
echo "<td width='41%'>\n";
echo "<select size='1' value='$zone' name='zoneNew'>\n";
echo "<option value=$zone selected>Default (GMT $zone hours)</option>\n";
echo "<option value=-12>(GMT -12:00 hours)</option>\n";
echo "<option value=-11>(GMT -11:00 hours)</option>\n";
echo "<option value=-10>(GMT -10:00 hours)</option>\n";
echo "<option value=-9>(GMT -9:00 hours)</option>\n";
echo "<option value=-8>(GMT -8:00 hours)</option>\n";
echo "<option value=-7>(GMT -7:00 hours)</option>\n";
echo "<option value=-6>(GMT -6:00 hours)</option>\n";
echo "<option value=-5>(GMT -5:00 hours)</option>\n";
echo "<option value=-4>(GMT -4:00 hours)</option>\n";
echo "<option value=-3.5>(GMT -3:30 hours)</option>\n";
echo "<option value=-3>(GMT -3:00 hours)</option>\n";
echo "<option value=-2>(GMT -2:00 hours)</option>\n";
echo "<option value=-1>(GMT -1:00 hours)</option>\n";
echo "<option value=0>(GMT)</option>\n";
echo "<option value=+1>(GMT +1:00 hours)</option>\n";
echo "<option value=+2>(GMT +2:00 hours)</option>\n";
echo "<option value=+3>(GMT +3:00 hours)</option>\n";
echo "<option value=+3.5>(GMT +3:30 hours)</option>\n";
echo "<option value=+4>(GMT +4:00 hours)</option>\n";
echo "<option value=+4.5>(GMT +4:30 hours)</option>\n";
echo "<option value=+5>(GMT +5:00 hours)</option>\n";
echo "<option value=+5.5>(GMT +5:30 hours)</option>\n";
echo "<option value=+6>(GMT +6:00 hours)</option>\n";
echo "<option value=+7>(GMT +7:00 hours)</option>\n";
echo "<option value=+8>(GMT +8:00 hours)</option>\n";
echo "<option value=+9>(GMT +9:00 hours)</option>\n";
echo "<option value=+9.5>(GMT +9:30 hours)</option>\n";
echo "<option value=+10>(GMT +10:00 hours)</option>\n";
echo "<option value=+11>(GMT +11:00 hours)</option>\n";
echo "<option value=+12>(GMT +12:00 hours)</option>\n";
echo "</select>\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Company Name:</font></td>\n";
echo "<td width='41%'><input type='text' name='companyNameNew' size='20' value='$companyName'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Homepage URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='homeNew' size='20' value='$home'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Sales tax / VAT (in percentage):</font></td>\n";
echo "<td width='41%'><input type='text' name='salesVATNew' size='20' value='$salesVAT'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Postage Amount:</font></td>\n";
echo "<td width='41%'><input type='text' name='PostalAmountNew' size='20' value='$PostalAmount'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Company Phone:</font></td>\n";
echo "<td width='41%'><input type='text' name='companyPhoneNew' size='20' value='$companyPhone'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Company Sales Email:</font></td>\n";
echo "<td width='41%'><input type='text' name='salesEmailNew' size='20' value='$salesEmail'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Domains that can access your Cart:</font></td>\n";

// Automatically grab the referers addresses - START
if ($referers==""){
echo "<td width='41%'><input type='text' name='referersNew' size='20' value='";
echo "http://";
print_r($SERVER_NAME);
echo " ";
print_r($SERVER_NAME);
echo "'></td>\n";
} else{ 
echo "<td width='41%'><input type='text' name='referersNew' size='20' value='$referers'></td>\n";
}
echo "</tr>\n";
// Automatically grab the referers addresses - END

echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Background Color - Header Row:</font></td>\n";
echo "<td width='41%'><input type='text' name='headerColorNew' size='20' value='$headerColor'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Background Color - Product Rows:</font></td>\n";
echo "<td width='41%'><input type='text' name='rowsColorNew' size='20' value='$rowsColor'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Title Color:</font></td>\n";
echo "<td width='41%'><input type='text' name='titleColorNew' size='20' value='$titleColor'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Text Colour - Black works best but test it out:</font></td>\n";
echo "<td width='41%'><input type='text' name='TextColorNew' size='20' value='$TextColor'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Page Background:</font></td>\n";
echo "<td width='41%'><input type='text' name='PgBackNew' size='20' value='$PgBack'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Font (preferred order, comma-separated):</font></td>\n";
echo "<td width='41%'><input type='text' name='fontNew' size='20' value='$font'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Font size:</font></td>\n";
echo "<td width='41%'><input type='text' name='fontSizeNew' size='20' value='$fontSize'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td><font size='2' face='Verdana'>Display notes field on checkout:</font></td>\n";
echo "<td width='10%'><select size='1' name='notes_activeNew'>  <option value='$notes_active'>$notes_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Language (English included in this distribution):</font></td>\n";
echo "<td width='41%'><select size='1' name='languageNew'>  <option value='$language'>$language</option>  <option value='english'>english</option>  <option value='french'>french</option>  <option value='spanish'>spanish</option>  <option value='italian'>italian</option></select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Currency:</font></td>\n";
echo "<td width='41%'><select size='1' value='$currency' name='currencyNew'>  <option value='$currency'>$currency</option>  <option value='£'>£</option>  <option value='$'>$</option>  <option value='&euro;'>&euro;</option></select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Enable Order Copy to File:</font></td>\n";
echo "<td width='41%'><select size='1' value='$enableCopy' name='enableCopyNew'>  <option value='$enableCopy'>$enableCopy</option>  <option value='Activate'>Activate</option>  <option value='DeActivate'>DeActivate</option></select></td>\n";
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