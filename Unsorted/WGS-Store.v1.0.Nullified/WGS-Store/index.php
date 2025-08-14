<?
include ('vars.php');

// SHOW MANUFACTURES
$mainpage = "html/index.html";
$query = "SELECT * FROM manufacturer";
$result = mysql_query($query);
if($result != "")
{
$number = mysql_num_rows($result);

for ($i=0;$i<$number;$i++) {
	$n[0] = mysql_result($result,$i,'manufacturer');
	$list = $list."<br><img src=images/arrow.gif width=13 height=5 border=0 align=absmiddle> <a href='index.php?manufacturer=$n[0]'>$n[0]</a><br><img src='images/small_line.gif' width='130' height='5'>";
}
}








// CREATE QUERY
if (isset($manufacturer)) {

	$mainpage = "html/product.html";
	if ($manufacturer == 'all') {
		$query = "SELECT * FROM products ORDER BY litdescr ASC";
	} else {
		$query = "SELECT * FROM products WHERE manufacturer = '$manufacturer' ORDER BY litdescr ASC";
	}
// COUNT PAGES AND SHOW NUBERS OF PAGES
$result = mysql_query($query);
if($result != "")
	{
$number = mysql_numrows($result);
	}
if (!isset($page)) {
	$page=1;
}
$cikl = ceil($number / 20);
$pages = "<div align=left><img src='images/arrow.gif' width='13' height='5' border='0' align='absmiddle'> <B>$number</B> products found<br><img src='images/bigline2.gif' width='563'><br><img src='images/arrow.gif' width='13' height='5' border='0' align='absmiddle'> Pages:";
for ($i=1;$i<=$cikl;$i++) {
	if ($page == $i) {
		$pages = $pages." <b>$i</b>";
	} else {
		$pages = $pages."<a href= 'index.php?manufacturer=$manufacturer&page=$i'>$i</a>";
	}
}
$pages = $pages."</div>";

// ASK MySQL FOR 20 PRODUCTS
$page = ($page-1)*20;
$query .= " LIMIT $page,20";
$result = mysql_query($query);
if ($result !="")
	{
$number = mysql_numrows($result);
	}
for ($i=0;$i<$number;$i++) {
	$n[0] = mysql_result($result,$i,'available');
	$n[1] = mysql_result($result,$i,'manufacturer');
	$n[2] = mysql_result($result,$i,'modelnumber');
	$n[3] = mysql_result($result,$i,'litdescr');
	$n[4] = mysql_result($result,$i,'bigdescr');
	$n[5] = mysql_result($result,$i,'picture');
	$n[6] = mysql_result($result,$i,'html');
	$n[7] = mysql_result($result,$i,'orprice');
	$n[8] = mysql_result($result,$i,'ouprice');
	$n[9] = mysql_result($result,$i,'makeopt');
	$n[10] = mysql_result($result,$i,'hiddenopt');
	$n[11] = mysql_result($result,$i,'id');
$name = "<a href='overview.php?id=".$n[11]."'>".$n[3]."</a>";
$image = "<img src='images/".$n[5]."' width=100 height=70>";
$descr = $n[4];
$overview = "<a class='buton normalfont' href='overview.php?id=".$n[11]."'>DETAILS...</a>";
$orpr = $n[7];
$quantity = $n[0];
if ($n[10] == "on" ) {
	$price = "<B><a class='buton normalfont' href='#' OnClick=\"go('offer.php?work=hidden&product=$n[3]')\")'>HIDDEN PRICE</a></B>";
} else {
	$price = "$n[8]$";
}
if ($n[9] == "on") {

	$offer = "<B><a class='buton normalfont' href='#' OnClick=\"go('offer.php?work=offer&product=$n[3]')\")'>OFFER</a></B>";
}

// WORK WITH TEAMPLATE
$table = file_reader("html/see.html");
$table = str_replace("%name%", "$name", $table);
$table = str_replace("%descr%", "$descr", $table);
$table = str_replace("%orpr%", "$orpr", $table);
$table = str_replace("%quantity%", "$quantity", $table);
$table = str_replace("%price%", "$price", $table);
$table = str_replace("%offer%", "$offer", $table);
$table = str_replace("%overview%", "$overview", $table);
$table = str_replace("%image%", "$image", $table);
$tables = $tables.$table;
}


}

$index=file_reader("$mainpage");
$index = str_replace("%list%", $list, $index);
$index = str_replace("%pages%", $pages, $index);
$index = str_replace("%tables%", $tables, $index);

echo $index;
?>