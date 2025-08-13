<?
$cl = new TheCleaner();
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;
?>

<form method='post' action='base/trans.php?revs'>
<select name='sid'>
<option value=''>-- Choose Story --</option>";
<?
$call = $dl->select("*","sl18_stories",array('suid'=>$_SESSION["uid"]));
foreach($call as $is) {
	print "<option value='" . $is["sid"] . "'>" . $cl->preva($is["sname"]) . "</option>";
}
?>
</select> 
Review id#</sup> <input type='text' name='rid'>
<p>
<input type='submit' value='remove review' name='submitrevs'>
</form>
