<link rel="stylesheet" href="style.css" type="text/css">
<?
if (isset($edit)) {

	if (isset($work)) {


// UPDATE DATABESE (CHANGE MANUFACTURER'S NAME)
		$query = "UPDATE manufacturer SET manufacturer = '$manufacturer' WHERE id = '$id'";
		$result = mysql_query($query);
		$query = "SELECT * FROM products WHERE manufacturer = '$oldmanu'";
		$result = mysql_query($query);
		if ($result !="")
		{
		$number = mysql_numrows($result);
		}
		for ($i=0;$i<$number;$i++) {

// UPDATE DATABESE (CHANGE PRUDICT'S MANUFACTURER'S NAME) 
			$n[0] = mysql_result($result,$i,'id');
			$query = "UPDATE products SET manufacturer = '$manufacturer' WHERE id = '$n[0]'";
			$result2 = mysql_query($query);
		}
		echo "it was renamed";
	} else {





// SELECT CHOUSEN MANUFACTURER
$query = "SELECT * FROM manufacturer WHERE id = '$id'";
$result = mysql_query($query);
$n[0] = mysql_result($result,$i,'id');
$n[1] = mysql_result($result,$i,'manufacturer');
?>
<form action="admin.php" method=post>
<font class=bigfont>Manufacturer</font>
<input type=text name=manufacturer value='<?=$n[1];?>'><br>
<input type=hidden name=oldmanu value='<?=$n[1];?>'>
<input type=hidden name=oper value=manu>
<input type=hidden name=edit value=edit>
<input type=hidden name=id value=<?=$n[0];?>>
<input type="submit" name="work" value="modify">
</form>











<?
	}
} elseif (isset($delete)) {

//DELETE CHOUSEN MANUFACTURER
	$query = "DELETE FROM manufacturer WHERE id = '$id'";
	$result = mysql_query($query);
	echo "it was deleted";
}

echo "<hr height=1 size=1 width='100%'>";

//SHOW MANUFACTURERS TO SELECT FROM THEM
$query = "SELECT * FROM manufacturer";
$result = mysql_query($query);
if ($result !="")
		{
		$number = mysql_numrows($result);
		}

echo "<form action='admin.php' method=post>";
for ($i=0;$i<$number;$i++) {
	$n[0] =mysql_result($result,$i,'id');
	$n[1] = mysql_result($result,$i,'manufacturer');
	echo "<input name=id type=radio value='$n[0]'><b><font class=bigfont> $n[1]</font></b><br>";
}
echo "<br><br><input type=hidden name=oper value=manu><input type=submit name=delete value=delete><input type=submit name=edit value=edit></form>";
?>


















