<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";

echo "<br>";


$wyn = "SELECT * FROM toplista_news ORDER BY id DESC";
$wykonaj = mysql_query($wyn);
$znaleziono = mysql_num_rows($wykonaj);

// new news layout by Ferran Marti. :)

while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
echo "<div align=center>
<center>
<table border=1 cellpadding=3 cellspacing=0 border-collapse: collapse bordercolor=#cccccc width=600><tr><td>";

 print "<br>".$tab[0]."<p>&nbsp;</p></td></tr><tr><td><p align=right>" . $lang['posted'] . ": ".$tab[1]." (".$tab[2].")<br>";
echo "</td></tr></table><br></center></div>";
}
include "footer.php";
?>
