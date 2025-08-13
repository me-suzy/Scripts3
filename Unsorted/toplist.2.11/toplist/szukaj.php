<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";


      $wyn = "SELECT * FROM toplista WHERE $gdzie LIKE '%$szukaj%'";
      $wykonaj = mysql_query($wyn);
      $znaleziono = mysql_num_rows($wykonaj);
      echo "<center>" . $lang['searchresults'] . ":<br><br>";
      while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){

      print "<p align=center><a href=out.php?id=".$tab[7]."&url=".$tab[1]."><img src=".$tab[3]." border=0></a><br><br><b><a href=out.php?id=".$tab[7]."&url=".$tab[1].">".$tab[0]."</a></b><br>".$tab[2]."<br><br><hr>";
}
include "footer.php";
?>
