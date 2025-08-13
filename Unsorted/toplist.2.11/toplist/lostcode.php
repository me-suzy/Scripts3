<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";

echo "<p align=center>" . $lang['lostcode'] . ":</p>
<center>
<form method=POST action=lostcode.php?step=2>
  <table border=0 cellpadding=5 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=350>
    <tr>
      <td align=right width=183>" . $lang['user'] . ":</td>
      <td width=167><input type=text name=user size=20></td>
    </tr>
    </table><br><input type=submit value=" . $lang['submit'] . ">
    </form><br><br>";
    if($step == "2"){
$wyn = "SELECT * FROM toplista WHERE user='$user'";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);
$znaleziono = mysql_num_rows($wykonaj);
if($znaleziono == "0"){
    echo "<center>" . $lang['nosite'] . "";
} else {
echo "<center>" . $lang['yourhtml'] . ":<br><br>
&lt;a href=http://" . $option['siteurl'] . "/in.php?id=".$tab[7].">&lt;img src=http://" . $option['siteurl'] . "/images/button.jpg border=0>&lt;/a><br><br>";
};
};

include "footer.php";
?>
