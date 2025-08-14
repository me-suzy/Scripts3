<a name="list"></a>
<?
require("config.php");

$sql = "SELECT * FROM $db_table WHERE queue = '1' ORDER BY id DESC";
$result = mysql_query ($sql);

$num_rows = mysql_num_rows($result);
echo "<b>sites in ring</b> ($num_rows)<br><br>";

while ($row = mysql_fetch_array($result))
	{
		echo '<a href="'.$row["url"].'" target="_blank">'.$row["site_name"].'</a> id: '.$row["id"].'<br>'.$row["description"].'<br><br>';
	}

mysql_close();
?>
