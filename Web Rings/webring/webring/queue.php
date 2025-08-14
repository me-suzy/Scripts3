<?
require("config.php");

$sql = "SELECT * FROM $db_table WHERE queue = '0' ORDER BY id DESC";
$result = mysql_query ($sql);

$num_rows = mysql_num_rows($result);
echo "<b>sites in queue</b> ($num_rows)<br><br>";

while ($row = mysql_fetch_array($result))
	{
		$added = date("m-d-y",$row["added"]);
		echo '<a href="'.$row["url"].'" target="_blank">'.$row["site_name"].'</a> id: '.$row["id"].'<br> submitted on '.$added.'<br><br>';
	}

mysql_close();
?>
