<?
$cl = new TheCleaner();
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;
?>

Warning: Deleting a story will also delete all chapters, reviews and ratings associated with it!
<P>

<form method='post' action='base/trans.php?deletestory'>
<select name='sid'>
<option value=''> -- Select Story -- </option>

<?
$call = $dl->select("*","sl18_stories",array('suid'=>$_SESSION["uid"]));
foreach($call as $row) {
	print "<option value='" . $row["sid"] . "'>" . $cl->preva($row["sname"]) . "</option>";
}
?>

</select>
<input type='submit' value='delete story' name='submitdeletestory' onClick='return confirm("Delete story?")'>
</form>
