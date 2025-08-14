<?


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

include(directory . "var.php");

if ($ratio == '10') {
	$ratio = "1.0";
} else {
	$ratio = "0.".$ratio;
}

if (!empty($$cookiename)) {
	echo "function popup() {}\n";
	exit;
}

$time = time();
if (empty($account)) {
	error("No account given");
} else {
	$sql = "UPDATE $pop_tbl SET ins=ins+1,lastuse='$time' WHERE account='$account'";
	$result = $s24_sql->query($sql);
}
$sql2 = "SELECT id,url,credits FROM $pop_tbl WHERE (out-(credits*10))<((ins*$ratio)+(credits*10)) AND account!='$account' AND status='Approved' AND active='1'";
$result2 = $s24_sql->query($sql2);
$num = $s24_sql->num_rows($result2);
while($row = $s24_sql->fetch_row($result2)) {
	$x++;
	if ($x != $num) {
		$ids .= "$row[0]]-_-[$row[1]]-_-[$pop_tbl]-_-[$row[2]|,,|";
	} else {
		$ids .= "$row[0]]-_-[$row[1]]-_-[$pop_tbl]-_-[$row[2]";
	}
}
if (empty($ids)) {
	$sql = "SELECT id,url FROM $site_tbl";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	while($row = $s24_sql->fetch_row($result)) {
		$x++;
		if ($x != $num) {
			$ids .= "$row[0]]-_-[$row[1]]-_-[$site_tbl]-_-[0|,,|";
		} else {
			$ids .= "$row[0]]-_-[$row[1]]-_-[$site_tbl]-_-[0";
		}
	}
}

$ids = explode("|,,|",$ids);
$count = count($ids);
srand ((double)microtime()*1000000);
if ($count > 0) { $id = rand(0,($count-1)); } else { $id = 0; }

$x = explode("]-_-[",$ids[$id]);
if ($x[3] > 0) {
	$sql = "UPDATE $x[2] SET out=out+1, credits=credits-1 WHERE id='$x[0]'";
} else {
	$sql = "UPDATE $x[2] SET out=out+1 WHERE id='$x[0]'";
}
$result = $s24_sql->query($sql);
$url = $x[1];

$content = "URL: $url";
setcookie($cookiename, $content, time()+($hours*60*60));

if (empty($url)) {
	exit;
}

echo "function popup() {\n";
echo "  window.open('$url','popup','scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no');\n";
echo "  self.focus();\n";
echo "}\n";
?>
