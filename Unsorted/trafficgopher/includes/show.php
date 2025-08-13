<?php
 include ("config.php");
 include ("db_inc.php");
 $query=stripslashes("$query");
?>
<html>
<body>
<form name='frm' action='show.php'>
<br>
<font face='arial' size=2>
Enter table Name :&nbsp;
<input type='text' name='tbl' value="<?php echo $tbl;?>">&nbsp;&nbsp;
Query :&nbsp;
<input type='text' name='query' size=40 value="<?php echo $query;?>">
<input type='submit' name='show' value="Show Table">
<br><br>
</font>

</form>
</body>
</html>

<?php
if(!empty($tbl))
{
	display_table($db_name,"$tbl","$query");
}
?>

<?php
// Display table function
function display_table($db_name,$tablename,$query)
{
	$a=mysql_query("SELECT * FROM $tablename $query") or die ("could not execute query".mysql_error());
	print "<h3>Table Name: <font color=\"Blue\">$tablename</font></h3>";
	print "<table border=\"1\">";

	// Display the column name
	$fields = mysql_list_fields($db_name, "$tablename");
	$columns = mysql_num_fields($fields) or die(mysql_error());
	print "<tr bgcolor='silver'>\n";
	for ($i = 0; $i < $columns; $i++)
	{

	  	print "\t<td><b>".mysql_field_name($fields, $i) ."</b></td>\n";
	}

	// Display the data of the table
	while ($a_row=mysql_fetch_row($a))
	{
		print "<tr>\n";
		foreach($a_row as $field)
		{
			print "\t<td>$field</td>\n";
		}
		print "</tr>\n";
	}
	print "</table>\n";
}
?>