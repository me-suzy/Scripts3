<font class='catdis'> > Manage Sub-Categories </font><p>

<?

if ( $_POST["action"] == "addsub" ) {

	if( $_POST["addsubmit"] ) {

		$dl->insert("sl18_subcategory",array('subname'=>$_POST["subname"],'subcatid'=>$_POST["subcatid"]));
		print "New sub-category added";

	} else {
?>
		<form method='post' action='main.php?mansub'>
		<table border='0' width='100%'>
			<tr>
				<td width='20%'>Name</td><td><input type='text' name='subname'></td>
			</tr><tr>
				<td width='20%'>Parent Category</td>
				<td>
					<select name='subcatid'>
<?
		$table = $dl->select("*","sl18_category","","ORDER BY caname ASC");
		foreach($table as $row) {
			print "<option value='" . $row["caid"] . "'>" . $cl->preva($row["caname"]) . "</option>";
		}
?>
					</select>
				</td>
			</tr><tr>
				<td colspan='2'>
					<input type='hidden' value='addsub' name='action'>
					<input type='submit' value='add subcategory' name='addsubmit'>
				</td>
			</tr>
		</table>
		</form>
<?	
	}

}

elseif ($_POST["action"] == "editsub" ) {

	if( $_POST["editsubmit"] ) {

		$dl->update("sl18_subcategory",
			array('subname'=>$_POST["subname"],'subcatid'=>$_POST["subcatid"]),
			array('subid'=>$_POST["subid"]));

		$table = $dl->select("*","sl18_stories",
			array('ssubid'=>$_POST["subid"]));

		if (count( $table ) > 0 ) {

			foreach($table as $row) {
				$dl->update("sl18_stories",
					array('ssubid'=>$_POST["subid"],'scid'=>$_POST["subcatid"]),
					array('sid'=>$row["sid"]));
			}
		}

		print "Sub-Category updated";

	} elseif ($_POST["editmid"]) {
	
		$table = $dl->select("*","sl18_subcategory",array('subid'=>$_POST["addsubcat"])) or die ($dl->getError());
?>
		Warning: If the parent category is altered, all stories within will also be moved.
		<p>
		<form method='post' action='main.php?mansub'>
		<table border='0' width='100%'>
			<tr>
				<td width='20%'>Name</td><td><input type='text' name='subname' value='<?=$cl->inputv($table[0]["subname"])?>'></td>
			</tr><tr>
				<td width='20%'>Category</td>
				<td>
					<select name='subcatid'>
<?
		$tableb = $dl->select("*","sl18_category","","ORDER BY caname ASC");
		foreach($tableb as $rowb) {
			if ( $rowb["caid"] == $table[0]["subcatid"] )
				print "<option value='" . $rowb["caid"] . "' selected>" . $cl->preva($rowb["caname"]) . "</option>\n";
			else
				print "<option value='" . $rowb["caid"] . "'>" . $cl->preva($rowb["caname"]) . "</option>\n";
		}
?>
					</select>
				</td>
			</tr><tr>
				<td colspan='2'>
					<input type='hidden' value='<?=$_POST["addsubcat"]?>' name='subid'>
					<input type='hidden' value='editsub' name='action'>
					<input type='submit' value='edit subcategory' name='editsubmit'>
				</td>
			</tr>
		</table>
		</form>		
<?

	} else {
		include(SL_ROOT_PATH."/base/catsubcat.inc.php");
?>
		<form method='post' action='main.php?mansub'>
		<select name='addcat' onChange='fillSelectFromArray(this.form.addsubcat, ((this.selectedIndex == -1) ? null : cat[this.selectedIndex-1]));' class='select'>
		<option value=''>-- Choose Category --</option>
<?
		$table = $dl->select("*","sl18_category"," ","ORDER BY caname ASC");
		foreach($table as $row) {
			print "<option value='".$row["caid"]."'>".$cl->preva($row["caname"])."</option>";
		}
?>
		</select>
		<select name='addsubcat' class='select'>
		<option value=''>-- Choose SubCategory --</option>
		</select>
		<input type='hidden' value='editsub' name='action'>
		<input type='submit' value='select subcategory' name='editmid'>
		</form>
<?	
	}

}

elseif ($_POST["action"] == "deletesub") {

	if( $_POST["deletesubmit"] ) {

		$dl->delete("sl18_subcategory",array('subid'=>$_POST["addsubcat"]));

		$table = $dl->select("*","sl18_stories",array('ssubid'=>$_POST["addsubcat"]));

		if ( count ($table) > 0 ) {
			foreach($table as $row) {
				$dl->delete("sl18_stories",array('sid'=>$row["sid"]));
				$dl->delete("sl18_chapters",array('csid'=>$row["sid"]));
				$dl->delete("sl18_review",array('rsid'=>$row["sid"]));
				$dl->delete("sl18_rate",array('ratsid'=>$row["sid"]));
			}
		}

		print "Sub-Category and all associated with it deleted";

	} else {
		include(SL_ROOT_PATH."/base/catsubcat.inc.php");
?>
		Warning: Deleting a sub-category will result in all stories under it being removed!
		<p>
		<form method='post' action='main.php?mansub'>
		<select name='addcat' onChange='fillSelectFromArray(this.form.addsubcat, ((this.selectedIndex == -1) ? null : cat[this.selectedIndex-1]));' class='select'>
		<option value=''>-- Choose Category --</option>
<?
		$table = $dl->select("*","sl18_category"," ","ORDER BY caname ASC");
		foreach($table as $row) {
			print "<option value='".$row["caid"]."'>".$cl->preva($row["caname"])."</option>";
		}
?>
		</select>
		<select name='addsubcat' class='select'>
		<option value=''>-- Choose SubCategory --</option>
		</select>
		<input type='hidden' value='deletesub' name='action'>
		<input type='submit' value='delete subcategory' name='deletesubmit' onClick='return confirm("Delete this sub-category?")'>
		</form>
<?	
	}

}

else {

?>
	<form method='post' action='main.php?mansub'>
	<select name='action'>
	<option value=''>-- Choose Action --</option>
	<option value='addsub'>Add subcategory</option>
	<option value='editsub'>Edit subcategory</option>
	<option value='deletesub'>Delete subcategory</option>
	</select> 
	<input type='submit' value='go' name='mansubsubmit'>
	</form>
<?

}

?>