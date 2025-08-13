<?
include "config.php";
include "db/db.php";

$url = urldecode($url);
$wyn = "SELECT * FROM toplista WHERE id='$id'";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);

$wyjscia = $tab[6];
$wyjscia = $wyjscia + 1;

$ip = $REMOTE_ADDR;
$date = date("Y-m-d H:i:s");

$c = "UPDATE toplista SET wyjscia='$wyjscia' WHERE id='$id'";
$cc = mysql_query($c);
$stats = "INSERT INTO toplista_stats VALUES('$ip', '$date', 'out', '$id', '')";
$statsgo = mysql_query($stats);

if($function['rating'] == "1"){


echo "<FRAMESET ROWS=\"40,*\">

<FRAME SCROLLING=\"auto\" NAME=\"rate\" SRC=\"rateout.php?id=$id&url=$url&cat=$cat\">

<FRAME SCROLLING=\"yes\" NAME=\"strona\" SRC=\"$url\">

<NOFRAMES>

<BODY>

<meta http-equiv=\"refresh\" content=\"1; url=$url\">

</BODY>

</NOFRAMES>

</FRAMESET>";

} else {

echo "<meta http-equiv=\"refresh\" content=\"1; url=$url\">";

}


?>
