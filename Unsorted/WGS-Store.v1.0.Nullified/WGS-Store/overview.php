<?
include ('vars.php');

if (isset($id)) {

//TAKE INFO FROM DB
	$query = "SELECT * FROM products where id = '$id'";
	$result = mysql_query($query);
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

$name = $n[3];
$image = "<img src='images/".$n[5]."'>";
$descr = $n[4];
$orpr = $n[7];
$quantity = $n[0];
if ($n[10] == "on" ) {
	$price = "<B><a href='#' class='buton' OnClick=\"go('offer.php?work=hidden&product=$n[3]')\")'>HIDDEN PRICE</a></B>";
} else {
	$price = "$n[8]$";
}
$htmldescr = $n[6];

//WORK WITH TEAMPLATE
$table = file_reader("html/overview.html");
$table = str_replace("%name%", "$name", $table);
$table = str_replace("%descr%", "$descr", $table);
$table = str_replace("%orpr%", "$orpr", $table);
$table = str_replace("%quantity%", "$quantity", $table);
$table = str_replace("%price%", "$price", $table);
$table = str_replace("%htmldescr%", "$htmldescr", $table);
$table = str_replace("%image%", "$image", $table);

}
echo$table;
?>