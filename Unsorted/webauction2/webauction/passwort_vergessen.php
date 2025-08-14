<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
require('includes/config.inc.php');
require('includes/messages.inc.php');
include "./header.php";
if(!isset($senden)) {
?>
<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
<TR>
<TD><center>
<form action="passwort_vergessen.php" method="post">
<font face="Verdana" size=1>Sie haben ihr Passwort vergessen? Kein Problem!<br> Tragen Sie ihre Email-Adresse oder Usernamen ein und wir senden es Ihnen erneut per Mail zu!<br><br>
Username: <input type="text" name="username"><br>
Email-Adresse: <input type="text" name="mailadresse"><br>
<input type="submit" name="senden" value="Erneut zusenden!">
</form> </center> </TD>
  </TR>
</TABLE>
<?
include "./footer.php";
exit;
}
else {
mysql_connect($DbHost, $DbUser, $DbPassword);
mysql_selectdb($DbDatabase);
$r=mysql_query("SELECT *FROM ".$dbfix."_users WHERE nick = \"$username\" OR email = \"mailadresse\"");
if(mysql_num_rows($r)==0) {
?><TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
<TR>
<TD><center>
<font face="Verdana">Fehler: Username existiert nicht oder email-adresse ist falsch!  </center></TD>
  </TR>
</TABLE>
<?
include "./footer.php";
exit;
}
$row=mysql_fetch_row($r);
$text="Hallo, \n";
$text.="Du oder jemand anderes hat die erneute Zusendung des Passwortes \n beantragt: \n Ihr Passwort: \n\n";
$text.=$row["password"]."\n\n Mfg \nIhr Auktions Team";
mail($row["email"], "Passwort-Zusendung", "$text", "From: webmaster@auktion.de\n");
?><TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
<TR>
<TD><center>
<font face="Verdana"> Userdaten erfolgreich zu Ihrer Email-Adresse gesendet!  </center></TD>
  </TR>
</TABLE>
<?
include "./footer.php";
}
?>
