<?
$sql = mysql_query("SELECT * FROM toplista_menu");
$row = mysql_fetch_array($sql);
echo "<p align=center>Menu:</p>";
if($row['menu_addsite'] == "1"){
echo "<p align=center><a href=add.php>" . $lang['addsite'] . "</a>";
}
if($row['menu_editsite'] == "1"){
echo " | <a href=edit.php>" . $lang['editsite'] . "</a>";
}
if($row['menu_lostpass'] == "1"){
echo " | <a href=lostpass.php>" . $lang['lp'] . "</a>";
}
if($row['menu_contact'] == "1"){
echo " | <a href=mailto:" . $option['adminmail'] . "?Subject=TopList>" . $lang['contact'] . "</a>";
}
if($row['menu_taf'] == "1"){
echo " | <a href=tell.php>" . $lang['taf'] . "</a>";
}
if($row['menu_rules'] == "1"){
echo " | <a href=\"javascript:new_window('rules.php')\">" . $lang['rules'] . "</a><br>";
}
if($row['menu_losthtml'] == "1"){
echo "<a href=lostcode.php>" . $lang['lostcode'] . "</a>";
}
if($row['menu_bannerhosting'] == "1"){
echo " | <a href=banner-upload.php>" . $lang['bannerhosting'] . "</a>";
}
if($row['menu_news'] == "1"){
echo " | <a href=news.php>" . $lang['news'] . "</a>";
}
if($row['menu_viewstats'] == "1"){
echo " | <a href=viewstats.php>" . $lang['viewstats'] . "</a>";
}
if($row['menu_home'] == "1"){
echo " | <a href=index.php>" . $lang['home'] . "</a>";
}
echo "<br><a href=\"javascript:window.external.AddFavorite('http://" . $option['siteurl'] . "')\">" . $lang['bookmark'] . "</a> | <a href=\"#\" onclick=\"if (this.style) { this.style.behavior='url(#default#homepage)'; this.setHomePage('http://" . $option['siteurl'] . "') }\">" . $lang['homepage'] . "</a> | <a href=admin.php>Administration menu</a></p>";

if($function['szukaj'] == "1"){
echo "<p align=center>" . $lang['search'] . ":</p>
<center><form method=post action=szukaj.php><input type=input name=szukaj><br><input type=radio name=gdzie value=opis checked> " . $lang['indesc'] . "<br><input type=radio name=gdzie value=nazwa> " . $lang['intitle'] . "<br><br><input type=submit value=" . $lang['search'] . "></form>";
}
echo "<br><br><script language=\"JavaScript\">

function new_window(url) {

link = window.open(url,\"TopList\",\"toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=200,height=300,left=80,top=180\");

}
</script>
<br><br>
<p align=center>Copyright (c) 2002 - <a href=" . $homepageurl . " target=_blank>" . $homepagename . "</a><br><br><font size=1>Powered by <a href=http://besttoplist.sourceforge.net/html>Best Top List</a> by Szymon Kosok v. $version</font><br><br>";
?>