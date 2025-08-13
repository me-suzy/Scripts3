<?
$cookie = $HTTP_COOKIE_VARS["toplistaid$id"];
$cooktest = $HTTP_COOKIE_VARS["toplistacookietest"];
if(!isset($cooktest)){
echo "Cookies disabled!";
die();
} else {
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
if($cookie <> ""){
include "style/" . $option['style'] . ".php";
echo "<center>" . $lang['doesntcount'] . "</center>";
die();
};
        $ip = $REMOTE_ADDR;
        $wyn = "SELECT * FROM toplista_ip_blocking WHERE ip='$ip' ORDER BY id DESC";
        $wykonaj = mysql_query($wyn);
        $tab = mysql_fetch_array($wykonaj);
        $czas = time() - $option['cookietime'];
        $lastv = $time - $tab['czas'];

        if($lastv > $czas){
        include "style/" . $option['style'] . ".php";
        echo "<center>" . $lang['doesntcount'] . "</center>";
        die();
        }

	if (!isset($cookie)) {
		srand((double)microtime()*1000000);
		$randval = rand();
		setcookie("toplistaid$id",$randval,time()+$option['cookietime'],"/","." . $option['cookiedomain'] . "",0);
        }

include "style/" . $option['style'] . ".php";
if($function['anticheat'] == "1"){
if($HTTP_POST_VARS['authorize'] == ""){

        echo "<center>Vote doesn't count. Vote gate!";
        if($function['cheatlog'] == "1"){
        $plik = fopen("cheat.log", "a+");
        $date = date("Y-m-d H:i:s");
        $content = "\n" . $date . " - " . $REMOTE_ADDR . " - Site Id:" . $id;
        fwrite($plik, $content) or die('File error!');
        }
        die();

} elseif($auth == $HTTP_POST_VARS['authorize']){

$wyn = "SELECT * FROM toplista WHERE id='$id'";
$wykonaj = mysql_query($wyn);
$znaleziono = mysql_num_rows($wykonaj);
if($znaleziono == "0"){
echo "<center>" . $lang['nosite'];
die();
}
$tab = mysql_fetch_row($wykonaj);
$wyjscia = $tab[5];
$wyjscia = $wyjscia + 1;
$c = "UPDATE toplista SET wejsica='$wyjscia' WHERE id='$id'";
$cc = mysql_query($c);
if($rating <> "0"){
$c = "UPDATE toplista SET rating=rating+$rating WHERE id='$id'";
$cc = mysql_query($c);
$c = "UPDATE toplista SET votes=votes+1 WHERE id='$id'";
$cc = mysql_query($c);
}
$ip = $REMOTE_ADDR;
$date = date("Y-m-d H:i:s");
$stats = "INSERT INTO toplista_stats VALUES('$ip', '$date', 'in', '$id', '')";
$statsgo = mysql_query($stats);
$czas = time();
$blockip = "INSERT INTO toplista_blocking_ip VALUES('$ip', '$czas', '$id', '')";
$blockipgo = mysql_query($blockip);
echo "<meta http-equiv=\"refresh\" content=\"1; url=index.php\">";

} else {

        echo "<center>Vote doesn't count. Vote gate!";
        if($function['cheatlog'] == "1"){
        $plik = fopen("cheat.log", "a+");
        $date = date("Y-m-d H:i:s");
        $content = "\n" . $date . " - " . $REMOTE_ADDR . " - Site Id:" . $id;
        fwrite($plik, $content) or die('File error!');
        }
        die();
        }
} else {

$wyn = "SELECT * FROM toplista WHERE id='$id'";
$wykonaj = mysql_query($wyn);
$znaleziono = mysql_num_rows($wykonaj);
if($znaleziono == "0"){
echo "<center>" . $lang['nosite'];
die();
}
$tab = mysql_fetch_row($wykonaj);
$wyjscia = $tab[5];
$wyjscia = $wyjscia + 1;
$c = "UPDATE toplista SET wejsica='$wyjscia' WHERE id='$id'";
$cc = mysql_query($c);
$ip = $REMOTE_ADDR;
$czas = time();
$blockip = "INSERT INTO toplista_ip_blocking VALUES('$ip', '$czas', '$id', '')";
$blockipgo = mysql_query($blockip);
echo "<meta http-equiv=\"refresh\" content=\"1; url=index.php\">";
}
}
?>