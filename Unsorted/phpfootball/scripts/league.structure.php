<?php 
/*
***************************************************************************
Parameters :

***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>


<table width=85% class=tddd align=center>
<tr>
<td class=tdark colspan=4 align=center><?php echo $dbtable; ?></td>
</tr>
<?php
$urled = "Division,Venue,League,Agegroup";
get_structure($dbtable,$urled);
?>
</table>

<?php
function get_structure($dbtable,$urled){
	$dbfield = substr($dbtable, 0, -1);
	$query = "SELECT * FROM $dbtable";
	$result = mysql_query($query) or die ("Failed read $dbtable<br>Debug info: $query");
	$num_res = mysql_num_rows($result);
	while ($myrow = mysql_fetch_assoc($result)) {
	foreach ($myrow as $row_n=> $row_v){
		if ($row_n == "Name" && $row_v != "No $dbfield"){
		$row_v = mysql_escape_string($row_v); 
		//get the statistics
		$fix_num = mysql_query("SELECT * FROM Fixtures WHERE $dbfield = '$row_v' ");
		$gam_num = mysql_query("SELECT * FROM Games WHERE $dbfield = '$row_v' ");
		$tea_num = mysql_query("SELECT * FROM Teams WHERE $dbfield = '$row_v' ");
		$fix_num = mysql_num_rows($fix_num);
		$gam_num = mysql_num_rows($gam_num);
		$tea_num = mysql_num_rows($tea_num);
		//get the leading team
		$sql = mysql_query("SELECT * FROM Teams WHERE $dbfield = '$row_v' ORDER BY Position");
		$row = mysql_fetch_assoc($sql);
		$tea_nam = $row['Name'];
		//get the last game
		$sql = mysql_query("SELECT * FROM Games WHERE $dbfield = '$row_v' ORDER BY Date DESC");
		$row = mysql_fetch_assoc($sql);
		$gam_nam = $row['Name'];
		//get the oldest fixture
		$sql = mysql_query("SELECT * FROM Fixtures WHERE $dbfield = '$row_v' ORDER BY Date");
		$row = mysql_fetch_assoc($sql);
		$fix_nam = $row['Game'];
		//write the data
		echo "<tr><td class=td align=center rowspan=2>$row_v</td>";
		echo "<td class=input align=center><a href=\"show.php?dbtable=Fixtures&dbfield=$dbfield&dbfieldv=$row_v&urled=$urled\">fixtures</a><br>$fix_num</td>";
		echo "<td class=input align=center><a href=\"show.php?dbtable=Games&dbfield=$dbfield&dbfieldv=$row_v&urled=$urled\">games</a><br>$gam_num</td>";
		echo "<td class=input align=center><a href=\"show.php?dbtable=Teams&dbfield=$dbfield&dbfieldv=$row_v&urled=$urled\">teams</a><br>$tea_num</td>";
		echo "</tr><tr>";
		echo "<td class=td align=center>$fix_nam&nbsp;</td>";
		echo "<td class=td align=center>$gam_nam&nbsp;</td>";
		echo "<td class=td align=center>$tea_nam&nbsp;</td>";
		echo "</tr>";
		}
	}
	}
	unset ($i);
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
