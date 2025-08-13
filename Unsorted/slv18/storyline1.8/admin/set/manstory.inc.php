<font class='catdis'> > Manage Story </font><p>

<?
if ($_POST["action"] == "editstory" ) {

	if( $_POST["editsubmit"] ) {

		$dl->update("sl18_stories",
			array('scid'=>$_POST["addcat"],'ssubid'=>$_POST["addsubcat"]),
			array('sid'=>$_POST["sid"]));

		print "Story updated";
	} else {
		$call = $dl->select("s.*,sg.*",
			"sl18_stories s
				LEFT JOIN sl18_subcategory sg ON sg.subid=s.ssubid",
			array('sid'=>$_POST["sid"]),"GROUP by s.sid");

		if( count( $call ) > 0 ) {
		include(SL_ROOT_PATH."/base/catsubcat.inc.php");
		print $cl->preva($call[0]["sname"]);

?>
		<form method='post' action='main.php?manstory'>
		<select name='addcat' onChange='fillSelectFromArray(this.form.addsubcat, ((this.selectedIndex == -1) ? null : cat[this.selectedIndex-1]));' class='select'>
		<option value=''>-- Choose Category --</option>
<?
		$callb = $dl->select("*","sl18_category"," ","ORDER BY caname ASC");
		foreach($callb as $rowb) {
			if( $rowb["caid"] == $call[0]["scid"] )
				print "<option value='".$rowb["caid"]."' selected>".$cl->preva($rowb["caname"])."</option>";
			else
				print "<option value='".$rowb["caid"]."'>".$cl->preva($rowb["caname"])."</option>";				
		}
?>
		</select>
		<select name='addsubcat' class='select'>
		<option value='<?=$call[0]["subid"]?>'><?=$cl->preva($call[0]["subname"])?></option>
		</select>
		<input type='hidden' value='<?=$call[0]["sid"]?>' name='sid'>
		<input type='hidden' value='editstory' name='action'>
		<input type='submit' value='alter story' name='editsubmit'>
		</form>
			
<?	
		} else {
			print "The story does not exist";
		}
	}

} elseif ($_POST["action"] == "deletestory") {

	if( $_POST["deletesubmit"] ) {

		$dl->delete("sl18_stories",array('sid'=>$_POST["sid"]));
		$dl->delete("sl18_chapters",array('csid'=>$_POST["sid"]));
		$dl->delete("sl18_review",array('rsid'=>$_POST["sid"]));
		$dl->delete("sl18_rate",array('ratsid'=>$_POST["sid"]));

		print "Story and all associated with it deleted";

	} else {
		$call = $dl->select("*","sl18_stories",array('sid'=>$_POST["sid"]));
		if ($call[0]["sname"]) {
			print $call[0]["sname"] . "<p>";
?>
			This will also delete all chapters, reviews and ratings associated with the story. Are you sure?
			<p>
			<form method='post' action='main.php?manstory'>
			<input type='hidden' name='sid' value='<?=$_POST["sid"]?>'>
			<input type='hidden' value='deletestory' name='action'>
			<input type='submit' value='delete story' name='deletesubmit' onClick='return confirm("Delete this story?")'>
			</form>
<?		
		} else {
			print "This story does not exist";
		}	
	}

} else {

?>
	<form method='post' action='main.php?manstory'>
	Story N<sup><u>o</u></sup> <input type='text' name='sid'> 
	<select name='action'>
	<option value=''>-- Choose Action --</option>
	<option value='editstory'>Edit story location</option>
	<option value='deletestory'>Delete story</option>
	</select> 
	<input type='submit' value='go' name='mansubsubmit'>
	</form>
<?

}

?>