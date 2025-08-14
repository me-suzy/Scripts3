<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	require('./includes/config.inc.php');

	require('./includes/messages.inc.php');

	require("./header.php"); 
?>

<html>

<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">


<body bgcolor="#FFFFFF" link="#000000" vlink="#000000"
alink="#000000">
<div align="left">



<table border="0" bgcolor="#FFFFFF" width="100%">
                    <tr>
                        <td valign="top" colspan="3"><div
                        align="center"><center>

<B>

        <? print $tlt_font."Anmeldung Schritt 1 von 2" ?>

        </FONT>

</B>
                        </center></div>

<center><P><font size="2" face="Arial">
<B>Mit der Anmeldung werden folgende Nutzungsbedingungen akzeptiert:</B><BR></font>

<form>
<TEXTAREA cols=70 readOnly rows=10 wrap=physical>

1. Durch die Anmeldung kann an der Online-Auktion teilgenommen werden. Die Teilnahme ist für Käufer und Verkäufer kostenlos. Alle Benutzer müssen volljährig sein, um rechtsverbindliche Verträge abzuschliessen. 

2. Die Käufer und Verkäufer tragen die alleinige Verantwortung für die Abwicklung der Online-Auktionen. Diese Webseite stellt lediglich einen Marktplatz für Käufer und Verkäufer dar.

3. Der Verkäufer verpflichtet sich, die Waren an den Höchstbieter zu verkaufen. Über angebotenen Artikel hat er vollständig und wahrheitsgemäß zu informieren. Für eigene Artikel darf der Verkäufer nicht selbst bieten oder bieten lassen. Alle Angaben müssen der Wahrheit entsprechen.

4. Der Verkauf von illegalen Artikeln auf dieser Seite ist strengstens untersagt. Auktionen mit dem Ziel der Werbung anderer Webseiten insbesondere durch Links sind verboten und werden gelöscht.

5. Nach dem Ablauf der Verkaufsveranstaltung kommt zwischen dem Verkäufer und dem Bieter mit dem höchsten Gebot ein Kaufvertrag zustande. Käufer und Verkäufer regeln die Durchführung des Transportes und der Bezahlung untereinander. Beide erhalten von uns per eMail die jeweilige Anschrift.

6. Die Online-Auktion übernimmt keine Haftung für Schäden, die Mitgliedern oder Dritten entstehen durch die Nichtverfügbarkeit der Webseiten. Wir übernehmen keine Verantwortung wenn Angebote oder Gebote aufgrund technischer Probleme nicht verarbeitet werden können.

7. Die Online-Auktion ist nach eigenem Ermessen berechtigt, Auktionen und Benutzer z.B. bei Verstoss gegen die Nutzungsbedingugen zu löschen.

</TEXTAREA></form></center>

<P>

<table border="0" cellpadding="5" cellspacing="0" width="100%" bgcolor="#33CCFF">
<tr><td>
<form action="register.php" method="GET"><center>
<input type="submit" value="Akzeptieren und zu Schritt 2 ..."></center>
</td>
</tr>
</table></form>

</td>
</tr>
</table>
                        

<?
require('./footer.php');

?>
