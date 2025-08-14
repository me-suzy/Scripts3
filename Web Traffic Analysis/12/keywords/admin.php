<?php
$PHP_SELF = $_SERVER['PHP_SELF'];
require('setup.php');
$db = mysql_connect($my_host,$my_user,$my_pass); mysql_select_db($my_db, $db);

if(isset($HTTP_GET_VARS['data']) && $HTTP_GET_VARS['data']=="lmo") {
	setcookie("Username",""); setcookie("Password",""); $HTTP_COOKIE_VARS['Username']=""; $HTTP_COOKIE_VARS['Password']=""; }
if(isset($HTTP_POST_VARS['usern']) && isset($HTTP_POST_VARS['passw'])) {
	if($HTTP_POST_VARS['usern']==$username && $HTTP_POST_VARS['passw']==$password) {
		setcookie("Username", $HTTP_POST_VARS['usern']); $HTTP_COOKIE_VARS['Username'] = $HTTP_POST_VARS['usern'];
		setcookie("Password", $HTTP_POST_VARS['passw']); $HTTP_COOKIE_VARS['Password'] = $HTTP_POST_VARS['passw'];
}	}
?>
<html>
<head>
<title> Administration </title>
</head>
<body bgcolor="#FFFFFF">
<style type="text/css">
.siIn { border-width:1px; border-left-color:border-top-color:#AAAAAA; border-bottom-color:border-right-color:#EEEEEE; font-family:Verdana,Helvetica,Arial,sans-serif; font-size:12px; font-weight:normal; width:150px; background-color:#FCFFFF; color:#000000; }
.siSu { border-width:1px; border-left-color:border-top-color:#EEEEEE; border-bottom-color:border-right-color:#AAAAAA; font-family:Verdana,Helvetica,Arial,sans-serif; font-size:12px; font-weight:bold; width:100px; background-color:#EBEEEE; color:#000000; }
a:link,a:visited { font-family:Verdana,Helvetica,Arial,sans-serif; color:#114444; text-decoration:none; font-size:12px; }
a:hover,a:active { font-family:Verdana,Helvetica,Arial,sans-serif; color:#336666; text-decoration:underline; font-size:12px; }
.siTb { font-family:Verdana,Helvetica,Arial,sans-serif; font-size:12px; color:#222222; }
</style>

<?php
if(!isset($HTTP_COOKIE_VARS['Username']) || !isset($HTTP_COOKIE_VARS['Password']) || $HTTP_COOKIE_VARS['Username']!=$username || $HTTP_COOKIE_VARS['Password']!=$password) {
	echo "<form method=\"POST\" action=\"$PHP_SELF\">\n";
	echo "<table border=\"0\" cellpadding=\"1px\" cellspacing=\"0\" bgcolor=\"#EEEEEE\" width=\"300px\" class=\"siTb\">\n";
	echo "<tr><td colspan=\"2\"><p><b>Administrator login</b> Login to keywords!</p></td></tr>\n";
	echo "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
	echo "<tr><td>Username</td><td align=\"right\"><input type=\"text\" class=\"siIn\" name=\"usern\" />&nbsp;</td></tr>\n";
	echo "<tr><td>Password</td><td align=\"right\"><input type=\"password\" class=\"siIn\" name=\"passw\" />&nbsp;</td></tr>\n";
	echo "<tr><td></td><td align=\"right\"><input type=\"submit\" value=\"Login\" class=\"siIn\" />&nbsp;</td></tr>\n";
	echo "</table>\n</form>\n</body>\n</html>";
	die();
}
?>

<table border="0" cellpadding="1px" cellspacing="0" width="500px" class="siTb">
<tr><td colspan="3" align="center"><a href="<?php echo $PHP_SELF; ?>?data=lmo"><b>[ Logout ]</b></a></td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td colspan="3" align="center"><b>Top ten...</b></td></tr>
<tr><td colspan="3" align="center"><p><a href="<?php echo $PHP_SELF; ?>?data=lu&show=10">...most recently used</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=ou&show=10">Last ten most recently used</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=kw&show=10">...ordered alphabetically</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=nh&show=10">...most commonly used</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=lh&show=10">...least commonly used</a></p></td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td colspan="3" align="center"><b>All...</b></td></tr>
<tr><td colspan="3" align="center"><p><a href="<?php echo $PHP_SELF; ?>?data=lu">...ordered by most recently used</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=ou">...ordered by last most recently used</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=kw">...ordered alphabetically</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=nh">...ordered by most commonly used</a> - 
<a href="<?php echo $PHP_SELF; ?>?data=lh">...ordered by least commonly used</a></p></td></tr>
<?php
if(isset($HTTP_GET_VARS['data'])) {
	if($HTTP_GET_VARS['data']=="lu") { $order = "ORDER BY lastuse DESC"; $data="lu"; }
	else if($HTTP_GET_VARS['data']=="ou") { $order = "ORDER BY lastuse ASC"; $data="ou"; }
	else if($HTTP_GET_VARS['data']=="kw") { $order = "ORDER BY keyword ASC"; $data="kw"; }
	else if($HTTP_GET_VARS['data']=="nh") { $order = "ORDER BY wordhits DESC"; $data="nh"; }
	else if($HTTP_GET_VARS['data']=="lh") { $order = "ORDER BY wordhits ASC"; $data="lh"; }
	else { $order = "ORDER BY wordhits DESC"; $data="nh"; }
} else { $order = "ORDER BY wordhits DESC"; $data="nh"; }

$sql = "SELECT * FROM keywords WHERE wordhits!='0' $order";
$result = mysql_query($sql,$db);
$count = mysql_num_rows($result);
if(!$count) { echo "<tr><td align=\"center\" colspan=\"3\"><p>(No keywords found!)</p></td></tr>\n"; }
else {
	if(isset($HTTP_GET_VARS['show'])){if(is_numeric($HTTP_GET_VARS['show'])){$show=$HTTP_GET_VARS['show'];}} else{ $show=$count; }
	if(isset($HTTP_GET_VARS['view'])){if(is_numeric($HTTP_GET_VARS['view'])){$view=$HTTP_GET_VARS['view'];}} else{ $view=0; }

	$result = mysql_query($sql." LIMIT $view,$show", $db);
	echo "<tr bgcolor=\"#E2E2E2\"><td align=\"center\" colspan=\"3\"><p>Showing keywords ".($view?$view:1)." to ".(($view+$show)>$count?$count:($view+$show))." of $count.</p></td></tr>\n";
	echo "<tr><td><b>Keyword</b></td><td><b>Uses</b></td><td><b>Last used</b></td></tr>\n";

	while($retval = mysql_fetch_array($result)) {
		echo "<tr><td>".$retval['keyword']."</td><td>".$retval['wordhits']."</td><td>";
		echo date('d/m/Y h:i:sa',$retval['lastuse'])."</td></tr>\n";
	}
	echo "<tr><td align=\"center\" colspan=\"3\">";
	if($view) { echo "<a href=\"$PHP_SELF?data=$data&view=".(($view-$show)<1?0:($view-$show))."&show=$show\">&lt;-- Back</a> "; }
	if(($view+$show)<$count) { echo "<a href=\"$PHP_SELF?data=$data&view=".($view+$show)."&show=$show\">Forward --&gt;</a> "; }
	echo "</td></tr>\n";
}
?>
</table>

</body>
</html>