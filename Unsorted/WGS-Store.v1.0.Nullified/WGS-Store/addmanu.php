<link rel="stylesheet" href="style.css" type="text/css">
<?
if (isset($work)) {

// ADD NEW MANUFACTURER TO DB
	// $query = "INSERT INTO manufacturer (manufacturer) VALUES('', '$manufacturer')";
	$query = "INSERT INTO manufacturer VALUES('','$manufacturer')";
	$result = mysql_query($query);
	if($result)
	echo "manufacturer was added";
	else 
		echo "did not add $manufacturer";		

} else {
?>

<FORM ACTION="admin.php" METHOD=POST class="bigfont">
  Manufacturer - 
  <input type=text name=manufacturer>
<input type="hidden" name="oper" value="addmanu">
<input type="submit" name="work" value="add">
</form>

<?
}
?>