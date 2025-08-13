<?

include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";

if($action == ""){
echo "<center><form method=post action=rateout.php?action=rate&id=$id&cat=$cat&url=$url>";
echo $lang['rateit'] . " on " . $option['nameoflist'] . ": <select name=rating><option value=5>5</option><option value=4>4</option><option value=3>3</option><option value=2>2</option><option value=1>1</option></select><input type=submit value=" . $lang['rate'] . "> - <a href=index.php?cat=$cat target=_top>" . $lang['backto'] . " " . $option['nameoflist'] . "</a> - <a href=$url target=_top>" . $lang['woframes'] . "</a></form>";
}

if($action == "rate"){
$c = "UPDATE toplista SET rating=rating+$rating WHERE id='$id'";
$cc = mysql_query($c);
$c = "UPDATE toplista SET votes=votes+1 WHERE id='$id'";
$cc = mysql_query($c);
echo "<center>Thx! :) - <a href=index.php?cat=$cat target=_top>" . $lang['backto'] . " " . $option['nameoflist'] . "</a> - <a href=$url target=_top>" . $lang['woframes'] . "</a>";
}
?>