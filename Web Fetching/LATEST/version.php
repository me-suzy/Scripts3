<?

/*
 * Latest Version Checker
 * Checks Apache, PHP, MySQL and phpMyAdmin Versions
 *
 *  Author :   JMan
 *  HomePage : www.bedpan.ca
 *  Email :    jman@bedpan.ca
 */

$phpMyAdmin_dir = "./phpMyAdmin"; //Relative phpMyAdmin directory; no trailing slash
$server = "localhost"; 
$db_user = "root"; 
$db_pass = ""; 
$link = mysql_connect($server, $db_user, $db_pass);

function phpMyAdmin_version(){
	global $phpMyAdmin_dir;
	$PMA = file_get_contents($phpMyAdmin_dir . "/libraries/defines.lib.php");
	$PMA_start = strpos($PMA, "define('PMA_VERSION', '2.6.4-pl4');");
	$PMA = substr($PMA, $PMA_start, strpos($PMA, "')", $PMA_start) - $PMA_start);
	return substr($PMA, strrpos($PMA, "'") + 1);
}

function Search($Haystack, $Needle, $Stopper){
	$Found = substr($Haystack, strpos($Haystack, $Needle) + strlen($Needle), strpos($Haystack, $Stopper, strpos($Haystack, $Needle)) - strpos($Haystack, $Needle) - strlen($Needle));
	return $Found;
}

function Fetch($URL, $Start, $Stop){
	$fetch = file_get_contents($URL);
	$fetch = trim(Search($fetch, $Start, $Stop));
	return substr($fetch, strrpos($fetch, " ") + 1);
}

$apache = Search(apache_get_version(), "/", " ");
$php = phpversion();
$mysql = str_replace("-nt", "", mysql_get_server_info());
$phpmyadmin = phpMyAdmin_version();

$latest_apache = Fetch("http://httpd.apache.org/download.cgi", "<a name=\"apache20\">", "\n");
$latest_php = Fetch("http://www.php.net/downloads.php", "<a name=\"v5\"></a>\n<h1>", "</h1>");
$latest_mysql = Fetch("http://dev.mysql.com/", "<li style=\"border: 1px solid #ccc; padding: 4px;\">", "</a></li>");
$latest_phpmyadmin = Fetch("http://www.phpmyadmin.net/home_page/index.php", "Latest stable version:", "</span>");

if (version_compare($apache, $latest_apache) < 0) $apache_u2d = " style=\"background-color:#FF7777;\"";
if (version_compare($php, $latest_php) < 0) $php_u2d = " style=\"background-color:#FF7777;\"";
if (version_compare($mysql, $latest_mysql) < 0) $mysql_u2d = " style=\"background-color:#FF7777;\"";
if (version_compare($phpmyadmin, $latest_phpmyadmin) < 0) $phpmyadmin_u2d = " style=\"background-color:#FF7777;\"";

$version = "<table border=\"1\" cellpadding=\"2\" cellspacing=\"0\" style=\"border-color:#000000; border-collapse: collapse;\">
<tr>
       <td></td>
       <td><b>Installed Version</b></td>
       <td><b>Latest Version</b></td>
</tr>";
if ($latest_apache) {
$version .= "
<tr$apache_u2d>
       <td><b>Apache</b></td>
       <td><center>$apache</center></td>
       <td><center>$latest_apache</center></td>
</tr>";
}
if ($latest_php) {
$version .= "
<tr$php_u2d>
       <td><b>PHP</b></td>
       <td><center>$php</center></td>
       <td><center>$latest_php</center></td>
</tr>";
}
if ($latest_mysql) {
$version .= "
<tr$mysql_u2d>
       <td><b>MySQL</b></td>
       <td><center>$mysql</center></td>
       <td><center>$latest_mysql</center></td>
</tr>";
}
if ($latest_phpmyadmin) {
$version .= "
<tr$phpmyadmin_u2d>
       <td><b>phpMyAdmin</b></td>
       <td><center>$phpmyadmin</center></td>
       <td><center>$latest_phpmyadmin</center></td>
</tr>";
}
$version .= "
</table>";

mysql_close($link);

echo $version;
?>