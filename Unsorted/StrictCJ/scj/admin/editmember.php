<?php
include("variables.inc.php");
include($dbconnect);
$sql = "SELECT * FROM $tablename WHERE NOUSER='".$_SERVER['QUERY_STRING']."'";
$result = mysql_query($sql);
$pointer = mysql_fetch_array($result);
$user = $pointer["NOUSER"];
$domain = $pointer["DOMAIN"];
$url = $pointer["URL"];
$uniquein = $pointer["UNIQUEIN"];
$rawin = $pointer["RAWIN"];
$hitsout = $pointer["HITSOUT"];
$forcedhits = $pointer["FORCEDHITS"];
$pcreturn = $pointer["PCRETURN"];
$pcunique = $pointer["PCUNIQUE"];
$hitsgen = $pointer["HITSGEN"];
$pcprod = $pointer["PCPROD"];
$ratio = $pointer["RATIO"];
$icq = $pointer["ICQ"];
$email = $pointer["EMAIL"];
$nick = $pointer["NICK"];
$min = $pointer["MIN"];
$title = $pointer["TITLE"];

mysql_free_result($result);
if ($linkfromid == "yes") {
  $selected = "<option selected value=\"yes\">Yes</option><option value=\"no\">No</option>\n";
} else {
  $selected = "<option selected value=\"no\">No</option><option value=\"yes\">Yes</option>\n";
}
?>
<html>
<head><title>Strict-CJ - Editing site</title>
<base target="_self">
</head>
<?php
echo "
<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#CCBBFF\" vlink=\"#CCBBFF\" alink=\"#CCBBFF\">
<form method=\"POST\" action=\"updatesite.php\" name=\"edit\">
<input type=\"hidden\" name=\"username\" value=\"$user\">
<center>
<table width=\"650\" cellspacing=\"1\" bgcolor=\"#221144\" border=\"0\" cellpadding=\"1\">
  <tr>
    <td bgcolor=\"#FFFFFF\" colspan=\"4\" align=\"center\"><font size=\"3\" face=\"Verdana\"><strong><u>Editing partner</u> - $user</strong></font><BR><font size=\"1\" face=\"Verdana\">Editing users is a usefull feature wich allows you to modify all <strong>unique</strong> user infos.</font>
    </td>
  </tr> 
  <tr>
    <td colspan=\"2\" bgcolor=\"#F1F1F1\" align=\"left\">Site Title</td>
    <td colspan=\"2\" bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"></td>
  </tr>
  <tr>
    <td colspan=\"2\" bgcolor=\"#F1F1F1\" align=\"left\">Domain name</td>
    <td colspan=\"2\" bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"domain\" size=\"30\" value=\"$domain\"></td>
  </tr>
  <tr>
    <td colspan=\"2\" bgcolor=\"#F1F1F1\" align=\"left\">Url</td>
    <td colspan=\"2\" bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"url\" size=\"30\" value=\"$url\"></td>
  </tr>
  <tr>
    <td colspan=\"2\" bgcolor=\"#F1F1F1\" align=\"left\">E-Mail</td>
    <td colspan=\"2\" bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"email\" size=\"30\" value=\"$email\"></td>
  </tr>
  <tr>
    <td colspan=\"2\" bgcolor=\"#F1F1F1\" align=\"left\">ICQ (UIN)</td>
    <td colspan=\"2\" bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"icq\" size=\"10\" value=\"$icq\"></td>
  </tr>
  <tr>
    <td colspan=\"2\" bgcolor=\"#F1F1F1\" align=\"left\">Nick</td>
    <td colspan=\"2\" bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"nick\" size=\"10\" value=\"$nick\"></td>
  </tr>
  <tr>
    <td bgcolor=\"#FFFFFF\" colspan=\"4\" align=\"center\" valign=\"top\"><font size=\"3\" face=\"verdana\"><strong><u>Trade Tweaks</u></strong></font><BR><font size=\"1\" face=\"verdana\">The following settings are ONLY recommended by Strict-CJ conceptor. Feel free to try new combinations!</font></td>
  </tr>
  <tr>
    <td bgcolor=\"#FFFFFF\" align=\"center\" colspan=\"2\" rowspan=\"2\" width=\"30%\"><font size=\"3\" face=\"verdana\"><strong><u>Strict-CJ Presets</u></strong></font></td>
	<td bgcolor=\"#F1F1F1\" align=\"left\">Ratio %</td>
    <td bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"ratio\" size=\"4\" value=\"$ratio\"></td>
  </tr>
  <tr>
    <td bgcolor=\"#F1F1F1\" align=\"left\">Min</td>
    <td bgcolor=\"#C1C1C1\" align=\"left\"><input type=\"text\" name=\"min\" size=\"4\" value=\"$min\"></td>
  </tr>
  <tr>
    <td bgcolor=\"#FFFFFF\" colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"<---------------Update --------------->\" name=\"Add\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" value=\"<---------------Delete--------------->\" name=\"delete\"
      onclick=\"window.location='deletesite.php?$user'\"></font></td>
  </tr>
</table>
</center>
</div>
</form>
</body>
</html>
";
?>