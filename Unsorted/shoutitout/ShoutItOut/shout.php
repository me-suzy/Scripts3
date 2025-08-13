<?
if ($name_id_c){
setcookie ("name_id_c", $name_id_c, time()+86400);
}
if ($link_c){
setcookie ("link_c", $link_c, time()+86400);
}
// 2002 - Andy Sørensen - andy@nospam.andys.dk (remove nospam)
// Visit http://www.andys.dk
// Shout It Out v1.0
?>
<link rel="stylesheet" href="stylesheet.css" type="text/css">
<body onload="window.scrollTo(0,99999);">
<?
include("connect.php");
$result = mysql_query("SELECT * FROM shout");
$all_rows = mysql_num_rows($result);
if ($all == "true"){
	$start = "0";
	$posts = "999";
} else {
	if ($all_rows < 15){
			$start = "0";
		} else {
			$start = $all_rows - 15;
		}
		$posts = "15";
}
$result = mysql_query("SELECT * FROM shout ORDER BY time ASC LIMIT $start,$posts");
$rows = mysql_num_rows($result);
if ($rows > 0) {
while($row = mysql_fetch_array($result)){
// Bad word check
$badwords = mysql_query("SELECT * FROM badwords");
$rowords = mysql_fetch_array($badwords);
$words = explode(",", $rowords[words]);
$bad = "*********************";
$i = "0";
while ($words[$i]){
	$length = strlen($words[$i])-2;
	$bad = substr($words[$i], 0, 2) . substr($bad, -$length);
	$row[text] = ereg_replace($words[$i],$bad, $row[text]);
	$i++;
}
// End of bad word check
$time = date("d. F Y H:i",$row[time]);
// Change emotions
$smile = "<img src='images/shout/happy.gif' align='absmiddle'>";
$ironic = "<img src='images/shout/ironic.gif' align='absmiddle'>";
$nothappy = "<img src='images/shout/mad.gif' align='absmiddle'>";
$very_happy = "<img src='images/shout/very_happy.gif' align='absmiddle'>";
$tongue = "<img src='images/shout/tongue.gif' align='absmiddle'>";
$nothing = "<img src='images/shout/nothing.gif' align='absmiddle'>";
$vain = "<img src='images/shout/vain.gif' align='absmiddle'>";
$row[text] = ereg_replace(":-\/",$vain, $row[text]);
$row[text] = ereg_replace(":\|",$nothing, $row[text]);
$row[text] = ereg_replace(":-\|",$nothing, $row[text]);
$row[text] = ereg_replace(":-P",$tongue, $row[text]);
$row[text] = ereg_replace(":P",$tongue, $row[text]);
$row[text] = ereg_replace(":p",$tongue, $row[text]);
$row[text] = ereg_replace(":-p",$tongue, $row[text]);
$row[text] = ereg_replace(":)",$smile, $row[text]);
$row[text] = ereg_replace(":-)",$smile, $row[text]);
$row[text] = ereg_replace(":D",$very_happy, $row[text]);
$row[text] = ereg_replace(":-D",$very_happy, $row[text]);
$row[text] = ereg_replace(":\(",$nothappy, $row[text]);
$row[text] = ereg_replace(":-\(",$nothappy, $row[text]);
$row[text] = ereg_replace(";)",$ironic, $row[text]);
$row[text] = ereg_replace(";-)",$ironic, $row[text]);
	if ($row[ip] != "") {
		$logged = "Yes!";
	} else {
		$logged = "No";
	}
	echo "<table cellpadding='2' cellspacing='0' width='100%'><tr><td align='left'>";
	$amounts = mysql_query("SELECT * FROM shout WHERE ip = '$row[ip]'");
	$entries = mysql_num_rows($amounts);
	if ($row[link] != ""){
		echo "<a target='_blank' href='http://$row[link]' title='$time - Logged:$logged Shouts:$entries'>$row[name_id]: </a>";
	} else {
		echo "<b title='$time - Logged:$logged Shouts:$entries'>$row[name_id]: </b></a>";
	}
	echo "$row[text]";
	echo "</td></tr></table>";
}
}
?>
<table width="100%" cellpadding="2" cellspacing="0"><tr><td align="center">
<?
if ($HTTP_COOKIE_VARS[name_id_c]){
	$name_value = $HTTP_COOKIE_VARS[name_id_c];
} else {
	$name_value = "Your name";
}
if ($HTTP_COOKIE_VARS[link_c]){
	$link_value = $HTTP_COOKIE_VARS[link_c];
} else {
	$link_value ="Your link";
}
echo "<form name=\"form1\" method=\"post\" action=\"$php_self\">
          <input class=\"inputtext\" type=\"text\" name=\"name_id\" value=\"$name_value\" onFocus=\"this.value='';\" size=\"20\">
          <input class=\"inputtext\" type=\"text\" name=\"text\" value=\"Your message\" onFocus=\"this.value='';\" size=\"20\">
		  <input class=\"inputtext\" type=\"text\" name=\"link\" value=\"$link_value\" onFocus=\"this.value='';\" size=\"20\">
          <input class=\"button\" type=\"submit\" name=\"add\" value=\"Go\"> <input class=\"button\" type=\"reset\" value=\"Reset\"><br>
		  <a href=\"?all=true\">All posts</a>
       </form>";
?>
</td></tr></table>
<?
if ($add){
	if ($link){
		$link = trim($link);
		$link = ereg_replace("http://", "", $link);
		$s=substr_count($link,"http://");
		$d=substr_count($link,".");
		if ($s==0 && $d>=1){
			$link_ok = "ok";
		} else {
			$link = "";
		}
	}
	$time = time();
	$ip = getenv("REMOTE_ADDR");
	$name_id = strip_tags($name_id);
	$text = strip_tags($text, '<a>');
	if($name_id != "Your name" && $text != "Your message"){
		mysql_query("insert into shout (ip,name_id,link,text,time) values ('$ip', '$name_id', '$link', '$text', '$time')");
		echo "<script>location.href='$PHP_SELF?name_id_c=$name_id&link_c=$link';</script>";
	}
}
mysql_close();
?>
</body>