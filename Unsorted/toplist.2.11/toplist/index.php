<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";

function numberofcomments($cid){
global $komentarzy;
$wyn = "SELECT * FROM toplista_comments WHERE siteid='$cid'";
$wykonaj = mysql_query($wyn);
$komentarzy = mysql_num_rows($wykonaj);
}

echo "<br><br><script language=\"JavaScript\">

function new_window(url) {

link = window.open(url,\"TopList\",\"toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=300,height=400,left=80,top=180\");

}
</script>";

if($option['updperday'] <> "0"){

        $update = $option['nextupd'] - time();
        if($update < 0){
                $wyn = "SELECT * FROM toplista WHERE active='y'";
                $wykonaj = mysql_query($wyn);
                $znaleziono = mysql_num_rows($wykonaj);
                while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
                $id = $tab[7];
                $upd1 = mysql_query("UPDATE toplista SET wejsica='0' WHERE id='$id'") or die('<font color=red>Error!</font>');
                $upd2 = mysql_query("UPDATE toplista SET wyjscia='0' WHERE id='$id'") or die('<font color=red>Error!</font>');
                $nextupdate = $option['updperday'] * 86400 + time() + $update;
                $upd3 = mysql_query("UPDATE toplista_options SET nextupd='$nextupdate'") or die('<font color=red>Error!</font>');
                }
        }
}

echo "<center><br><br>

</p>
<div align=center>
  <center>
  <table border=1 cellpadding=0 cellspacing=0 style=border-collapse: collapse bordercolor=" . $tableborder . " width=500>
    <tr>
      <td width=100% bgcolor=" . $tableheader . "><center><font color=" . $tableheadfont . "><b>" . $lang['recommended'] . ":</b></font></td>
    </tr>
    <tr>
      <td width=100% bgcolor=" . $tablebody . ">";
      if($cat == ""){ $cat = "1"; }
      if($cat == "all"){ $cat = "%"; }
      $wyn = "SELECT * FROM toplista WHERE active='y' && banner<>'' && category LIKE '$cat' ORDER BY RAND() LIMIT 1";
      $wykonaj = mysql_query($wyn);
      $znaleziono = mysql_num_rows($wykonaj);

      $tab = mysql_fetch_row($wykonaj);
      $urlb = $tab[1];

      print "<center><br><a href=out.php?id=".$tab[7]."&url=".$tab[1]." target=_blank><img src=".$tab[3]." border=0></a><br><br></b></td></tr></table>";

?>
<br><br>

<div align=center>
  <center>
<?php
  echo "<table border=1 cellpadding=3 cellspacing=0 border-collapse: collapse bordercolor=" . $tableborder . " width=600>
    <tr><td width=10% bgcolor=" . $tableheader . " align=center><font color=" . $tableheadfont . ">" . $lang['rank'] . "</font></td>
      <td width=70% bgcolor=" . $tableheader . " align=center><font color=" . $tableheadfont . ">" . $lang['site'] . "</font></td>
      <td width=10% bgcolor=" . $tableheader . " align=center><font color=" . $tableheadfont . ">" . $lang['in'] . "</font></td>
      <td width=10% bgcolor=" . $tableheader . " align=center><font color=" . $tableheadfont . ">" . $lang['out'] . "</font></td>";
?>
    </tr>
<?php

if($sort == ""){ $sort = "wejsica"; }
if($cat == ""){ $cat = "1"; }
if($cat == "all"){ $cat = "%"; }
$wyn = "SELECT * FROM toplista WHERE active='y' AND category LIKE '$cat' ORDER BY $sort DESC";
$wykonaj = mysql_query($wyn);
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$rank = $rank + 1;
if($function['rating'] == "1"){
if($tab[11] == "0"){ $ocena = $lang['notyet']; $glosow = $lang['notyet']; } else { $ocena = $tab[10] / $tab[11]; $ocena = round($ocena, 2); $glosow = $tab[11]; }

        if($tab[3] <> ""){
        $bannerek = "<img src=".$tab[3]." border=0>";
        } else {
        $bannerek = "";
        }
        if($function['comments'] == "1"){
        $id = $tab[7];
        $comments = "<a href=\"javascript:new_window('comments.php?siteid=" . $tab[7] . "')\">" . $lang['comments'] . "</a>" . numberofcomments($id) . " (". $komentarzy . ")";
        } else {
        $comments = "";
        }
        if($option['maxbanners'] >= $rank){
        $podbannerem = "<a href=out.php?id=".$tab[7]."&url=" . urlencode($tab[1]) . "&cat=" . $cat . " target=_blank>" . $bannerek  . "</a><br><br><b><a href=out.php?id=".$tab[7]."&url=" . urlencode($tab[1]) . "&cat=" . $cat . ">".$tab[0]."</a></b><br>".$tab[2]."<p align=right><small>" . $lang['rating'] . ": " . $ocena . " " . $lang['votes'] . ": " . $glosow . " " . $comments . "</small></p>";
        } else {
        $podbannerem = "<a href=out.php?id=".$tab[7]."&url=" . urlencode($tab[1]) . "&cat=" . $cat . " target=_blank>".$tab[0]."</a></b><br>".$tab[2]."<p align=right><small>" . $lang['rating'] . ": " . $ocena . " " . $lang['votes'] . ": " . $glosow . " " . $comments . "</small></p>";
        }

} else {
        if($tab[3] <> ""){
        $bannerek = "<img src=".$tab[3]." border=0>";
        } else {
        $bannerek = "";
        }
        if($function['comments'] == "1"){
        $id = $tab[7];
        $comments = "<a href=\"javascript:new_window('comments.php?siteid=" . $tab[7] . "')\">" . $lang['comments'] . "</a>" . numberofcomments($id) . " (". $komentarzy . ")";
        } else {
        $comments = "";
        }
        if($option['maxbanners'] >= $rank){
        $podbannerem = "<a href=out.php?id=".$tab[7]."&url=".$tab[1] . "&cat=" . $cat . " target=_blank>" . $bannerek  . "</a><br><br><b><a href=out.php?id=".$tab[7]."&url=".$tab[1]."&cat=" . $cat . ">".$tab[0]."</a></b><br>".$tab[2] . "<p align=right><small>" . $comments . "</small></p>";
        } else {
        $podbannerem = "<a href=out.php?id=".$tab[7]."&url=".$tab[1] . "&cat=" . $cat . " target=_blank>".$tab[0]."</a></b><br>" . $tab[2] . "<p align=right><small>" . $comments . "</small></p>";
        }
}
    print "<tr>
      <td width=10% align=center bgcolor=" . $tablebody . ">" . $rank . "</td>
      <td width=70% align=center bgcolor=" . $tablebody . ">" . $podbannerem . "</td>
      <td width=10% align=center bgcolor=" . $tablebody . ">".$tab[5]."</td>
      <td width=10% align=center bgcolor=" . $tablebody . ">".$tab[6]."</td>
    </tr>";
}
echo "</table>
  </center>
</div>";

?>
<?php
echo "<p>" . $lang['sortby'] . ": <a href=index.php?sort=wejsica>" . $lang['in'] . "</a> | <a href=index.php?sort=wyjscia>" . $lang['out'] . "</a> | <a href=index.php?sort=rating>" . $lang['rating'] . "</a> | <a href=index.php?sort=votes>" . $lang['votes'] . "</a></p><br><br>";
if($function['stats'] == "1"){
echo "<p align=center>" . $lang['globalstats'] . ":</p>";

$wyn = "SELECT * FROM toplista WHERE active='y'";
$wykonaj = mysql_query($wyn);
$znaleziono = mysql_num_rows($wykonaj);

$wyn2 = "SELECT * FROM toplista WHERE active='n'";
$wykonaj2 = mysql_query($wyn2);
$znaleziono2 = mysql_num_rows($wykonaj2);

$wyn3 = "SELECT * FROM toplista_stats WHERE inout='in'";
$wykonaj3 = mysql_query($wyn3);
$znaleziono3 = mysql_num_rows($wykonaj3);

$wyn4 = "SELECT * FROM toplista_stats WHERE inout='out'";
$wykonaj4 = mysql_query($wyn4);
$znaleziono4 = mysql_num_rows($wykonaj4);

echo "<p align=center>" . $lang['totalsites'] . ":&nbsp;" . $znaleziono . " | " . $lang['sitesauth'] . ": " . $znaleziono2 . " | " . $lang['alltimein'] . ": " . $znaleziono3 . " | " . $lang['alltimeout'] . ": " . $znaleziono4. "<br><br>";
}

if($function['categories'] == "1"){

$wyn = "SELECT * FROM toplista_categories";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        echo $lang['choosecat'] . ":<br>";
        echo "[ <a href=index.php?cat=all>All</a> ] ";
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo "[ <a href=index.php?cat=" . $tab[1] . ">" . $tab[0] . "</a> ] ";
        }

}

if($option['updperday'] <> 0){
        echo "<br><br>" . $lang['nsr'] . ": ";
                if($update < 3600){
                        $doupdate = $update / 60;
                        echo round($doupdate, 0) . " minute(s)";
                } elseif($update > 3600 * 24){
                        $doupdate = $update / 86400;
                        echo round($doupdate, 0) . " day(s)";
                } elseif($update > 3600){
                        $doupdate = $update / 3600;
                        echo round($doupdate, 0) . " hour(s)";
                }
}
include "footer.php";
?>
</body>

</html>
