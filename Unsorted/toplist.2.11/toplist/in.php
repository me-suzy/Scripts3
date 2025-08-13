<?
srand((double)microtime()*1000000);
$randval = rand();
include "config.php";
include "db/db.php";
db_options();
setcookie("toplistacookietest",$randval,time()+$option['cookietime'],"/","." . $option['cookiedomain'] . "",0);
if($function['anticheat'] == "0"){
header("Location: zlicz.php?id=" . $id);
} else {
banned(2);
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";

                srand((double)microtime()*1000000);
                $auth1 = rand();

echo "<center><br><br><br><br><form method=post action=zlicz.php?id=$id&auth=$auth1><input type=hidden name=authorize value=$auth1><input type=submit value=\"" . $lang['ifyouwanna'] . "\"><br><br><br>";
if($function['rating'] == "1"){
echo $lang['rateit'] . "<br><select name=rating><option value=0>" . $lang['norate'] . "</option><option value=5>5</option><option value=4>4</option><option value=3>3</option><option value=2>2</option><option value=1>1</option></select>";
}
echo "</form>";
}
?>
