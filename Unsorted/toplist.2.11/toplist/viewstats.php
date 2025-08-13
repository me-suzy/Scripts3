<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
if($step == ""){
include "style/" . $option['style'] . ".php";
include "header.php";
echo "<center><br><br>Please login:<br><br><form method=POST action=viewstats.php?step=2&recordsin=10&recordsout=10>
  <table border=0 cellpadding=5 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=350>
    <tr>
      <td align=right width=183>" . $lang['user'] . ":</td>
      <td width=167><input type=text name=user size=20></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['password'] . ":</td>
      <td width=167><input type=password name=password size=20></td>
    </tr>
        <tr>
      <td align=right width=350 colspan=2>
      <p align=center><input type=submit value=" . $lang['submit'] . " name=B1></td>
    </tr>
</table></form>";
}
if($step == "2"){
$wyn = "SELECT * FROM toplista WHERE user='$user'";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);
$znaleziono = mysql_num_rows($wykonaj);
if($znaleziono == "0"){
    echo "<center>" . $lang['baduser'] . "";
};
if($mdpass == $tab[9]){
include "style/" . $option['style'] . ".php";
include "header.php";
$id = $tab[7];
$stats = "SELECT * FROM toplista_stats WHERE siteid='$id' AND inout='in' ORDER BY id ASC LIMIT 0,$recordsin";
$statsgo = mysql_query($stats) or die('Database error');
$znaleziono = mysql_num_rows($statsgo);
        echo "<center><br><br>" . $lang['instats'] . ":<br><br><table border=1 cellpadding=3 cellspacing=0 border-collapse: collapse bordercolor=#111111>
  <tr>
    <td>IP</td>
    <td>Date:</td>
  </tr>";
        while($row = mysql_fetch_row($statsgo)) for($i=0;$i<count($znaleziono);$i++){
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
  echo "</table>";

  $wyn = "SELECT * FROM toplista_stats WHERE siteid='$id' AND inout='out' ORDER BY id ASC LIMIT 0,$recordsout";
  $wykonaj = mysql_query($wyn) or die('Database error');
  $znaleziono = mysql_num_rows($wykonaj);
        echo "<center><br><br>" . $lang['outstats'] . ":<br><br><table border=1 cellpadding=3 cellspacing=0 border-collapse: collapse bordercolor=#111111>
  <tr>
    <td>IP</td>
    <td>Date:</td>
  </tr>";
        while($row = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
  echo "</table>";

  $wyn = "SELECT * FROM toplista_stats WHERE siteid='$id' AND inout='in'";
  $wykonaj = mysql_query($wyn) or die('Database error');
  $znaleziono = mysql_num_rows($wykonaj);

  $wyn = "SELECT * FROM toplista_stats WHERE siteid='$id' AND inout='out'";
  $wykonaj = mysql_query($wyn) or die('Database error');
  $znaleziono2 = mysql_num_rows($wykonaj);

  echo "<br><br>" . $lang['actualin'] . ": " . $tab[5] . " " . $lang['actualout'] . ": " . $tab[6] . " " . $lang['alltimein'] . ": $znaleziono - " . $lang['alltimeout'] . ": $znaleziono2";
  echo "<br><br><form method=post action=viewstats.php?step=2&user=$user&mdpass=$mdpass>" . $lang['records'] . "<input type=text name=recordsin size=2>" . $lang['records1'] . "<input type=text name=recordsout size=2>" . $lang['records2'] . "<br><br><input type=submit value=" . $lang['submit'] . "></form>";
  } else {
  echo "<center>" . $lang['badpass'];
  }

}
?>
