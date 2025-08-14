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

	require("./header.php"); 
?>

<?



if ($auction_id){
print "

<html>

<head>
<meta http-equiv=\"Content-Type\"
content=\"text/html; charset=iso-8859-1\">

<title></title>
</head>

<body>


<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=#66CCFF>
<tr>
<td bgcolor=#FFFFFF>

  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
      <td>
        <p align=\"center\"><a href=\"$click\"
        target=\"_blank\"></a></p>
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
          <tr>
            <td>&nbsp; </td>
          </tr>
          <tr>
            <td valign=\"top\"><br>
              <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
                <tr>
                  <td><font face=\"Arial\"><strong>$title zur Beobachtungsliste
                    hinzufügen!</strong></font><font
                        color=\"#000000\" size=\"3\" face=\"Arial\"><br>
                    </font><font color=\"#000000\" size=\"2\"
                        face=\"Arial\">Bitte geben Sie Ihren Benutzernamen und
                    Ihr Passwort an, um sich zu indentifizieren! </font><br>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
<p><br>
</p>

<form action=\"add_views.php\" method=\"POST\">
    <input type=\"hidden\" name=\"action\" value=\"views_list\"><input
    type=\"hidden\" name=\"auction_id\" value=\"$auction_id\"><input
    type=\"hidden\" name=\"title\" value=\"$title\">
        <input type=\"hidden\" name=\"titel\" value=\"$title\">
        <input type=\"hidden\" name=\"action\" value=\"$action\">

        <table border=\"0\"
    width=\"77%\" bgcolor=#FFFFFF>
        <tr>
            <td><div align=\"center\"><center><table border=\"0\"
            cellpadding=\"3\" cellspacing=\"0\" width=\"50%\">
                <tr>
                    <td><font size=\"2\" face=\"Arial\"><strong>Username:</strong></font></td>
                    <td><font size=\"2\" face=\"Arial\"><strong><input
                    type=\"text\" size=\"20\" name=\"TPL_nick\"></strong></font></td>
                </tr>
                <tr>
                    <td><font size=\"2\" face=\"Arial\"><strong>Passwort:</strong></font></td>
                    <td><input type=\"password\" size=\"20\"
                    name=\"TPL_password\"></td>
                </tr>
            </table>
            </center></div><br><br><div align=\"center\"><center><TABLE>
            <TR>
                <TD>
                  <p><font size=\"2\" face=\"Arial\">Sie können bestimmen wann
                    Ihnen, bevor die Auktion für den Artikel endet, automatisch
                    eine \"Erinnerungs\"-eMail zusenden soll. Wenn Sie diese &quot;Erinnerungs&quot;-
                    eMails nicht zugeschickt bekommen möchten, dann können Sie
                    diese Funktion deaktivieren indem Sie \"keine eMail versenden\"
                    wählen.</font></p>
                </TD>
            </TR>
            </TABLE></center></div><br><br>
            <div align=\"center\"><center><table border=\"0\"
            cellpadding=\"3\" cellspacing=\"0\">
                <tr>
                    <td valign=\"top\"><font size=\"2\" face=\"Arial\"><strong>Wann
                    soll die eMail verschickt werden?</strong></font></td>
                    <td><font size=\"2\" face=\"Arial\"><strong><select
                    name=\"time_to_mail\" size=\"4\">
                        <option selected value=\"1\">1 Tag vorher</option>
                        <option value=\"2\">2 Tage vorher</option>
                        <option value=\"3\">3 Tage vorher</option>
                        <option value=\"no_mail\">keine eMail versenden</option>
                    </select></strong></font></td>
                </tr>
                <tr>
                    <td valign=\"top\"><font size=\"2\" face=\"Arial\"><strong>Wollen
                    Sie bei Geboten benachrichtigt werden?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></font></td>
                    <td><select
                    name=\"bids_mail\" size=\"2\">
                        <option selected value=\"0\">Nein</option>
                        <option value=\"1\">Ja</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            </center></div><p align=\"center\"><input type=\"submit\"
            name=\"\" value=\"Hinzufügen!\"></p>
            </td>
        </tr>
    </table>
</form>

<p><br>
</p>

</td>
</tr>
</table>

</body>
</html>

";


}


?>
<?
require('./footer.php');

?>
