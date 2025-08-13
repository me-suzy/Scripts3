<font class='catdis'> > Manage Categories </font><p>

<?

if ( $_POST["action"] == "addcat" ) {

	if( $_POST["addsubmit"] ) {

		$dl->insert("sl18_category",array('caname'=>$_POST["caname"],'capic'=>$_POST["capic"],'cadescript'=>$_POST["cadescript"]));
		print $cl->preva($_POST["caname"]) . "<br>";
		print $cl->preva($_POST["cadescript"]) . "<br>";
		if ($_POST["capic"])
			print "<img src='" . $_POST["capic"] . "'><p>";

		print "New category added";

	} else {
?>
		<form method='post' action='main.php?mancat'>
		<table border='0' width='100%'>
			<tr>
				<td width='20%'>Name</td><td><input type='text' name='caname'></td>
			</tr><tr>
				<td width='20%'>Description</td><td><input type='text' name='cadescript'></td>
			</tr><tr>
				<td width='20%'>Image</td>
				<td class='small'>
					<input type='text' name='capic' value='<?=SL_ROOT_URL?>/base/html/Default/images/default.gif'> [full url required]
				</td>
			</tr><tr>
				<td colspan='2'>
					<input type='hidden' value='addcat' name='action'>
					<input type='submit' value='add category' name='addsubmit'>
				</td>
			</tr>
		</table>
		</form>
<?	
	}

}

elseif ($_POST["action"] == "editcat" ) {

	if( $_POST["editsubmit"] ) {

		$dl->update("sl18_category",
			array('caname'=>$_POST["caname"],'capic'=>$_POST["capic"],'cadescript'=>$_POST["cadescript"]),
			array('caid'=>$_POST["caid"])) or die ($dl->getError());
		print $cl->preva($_POST["caname"]) . "<br>";
		print $cl->preva($_POST["cadescript"]) . "<br>";
		if ($_POST["capic"])
			print "<img src='" . $_POST["capic"] . "'><p>";

		print "Category updated";

	} elseif ($_POST["editmid"]) {
	
		$table = $dl->select("*","sl18_category",array('caid'=>$_POST["caid"])) or die ($dl->getError());
?>
		<form method='post' action='main.php?mancat'>
		<table border='0' width='100%'>
			<tr>
				<td width='20%'>Name</td><td><input type='text' name='caname' value='<?=$cl->inputv($table[0]["caname"])?>'></td>
			</tr><tr>
				<td width='20%'>Description</td><td><input type='text' name='cadescript' value='<?=$cl->inputv($table[0]["cadescript"])?>'></td>
			</tr><tr>
				<td width='20%'>Image</td>
				<td class='small'>
					<input type='text' name='capic' value='<?=$cl->inputv($table[0]["capic"])?>'>
				</td>
			</tr><tr>
				<td colspan='2'>
					<input type='hidden' value='<?=$_POST["caid"]?>' name='caid'>
					<input type='hidden' value='editcat' name='action'>
					<input type='submit' value='edit category' name='editsubmit'>
				</td>
			</tr>
		</table>
		</form>		
<?

	} else {
?>
		<form method='post' action='main.php?mancat'>
		<select name='caid'>
		<option value=''>-- Choose Category -- </option>
<?
		$table = $dl->select("*","sl18_category","","ORDER BY caname ASC");
		foreach($table as $row) {
			print "<option value='" . $row["caid"] . "'>" . $cl->preva($row["caname"]) . "</option>";
		}
?>
		</select>
		<input type='hidden' value='editcat' name='action'>
		<input type='submit' value='select category' name='editmid'>
		</form>
<?	
	}

}

elseif ($_POST["action"] == "deletecat") {

	if( $_POST["deletesubmit"] ) {

		$dl->delete("sl18_category",array('caid'=>$_POST["caid"]));
		$dl->delete("sl18_subcategory",array('subcatid'=>$_POST["caid"]));

		$table = $dl->select("*","sl18_stories",array('scid'=>$_POST["caid"]));
		
		if( count( $table ) > 0 ) { 
			foreach($table as $row) {
				$dl->delete("sl18_stories",array('sid'=>$row["sid"]));
				$dl->delete("sl18_chapters",array('csid'=>$row["sid"]));
				$dl->delete("sl18_review",array('rsid'=>$row["sid"]));
				$dl->delete("sl18_rate",array('ratsid'=>$row["sid"]));
			}
		}

		print "Category and all associated with it deleted";

	} else {
?>
		Warning: Deleting a category will result in all subcategories and stories under it being removed!
		<p>
		<form method='post' action='main.php?mancat'>
		<select name='caid'>
		<option value=''>-- Choose Category -- </option>
<?
		$table = $dl->select("*","sl18_category","","ORDER BY caname ASC");
		foreach($table as $row) {
			print "<option value='" . $row["caid"] . "'>" . $cl->preva($row["caname"]) . "</option>";
		}
?>
		</select>
		<input type='hidden' value='deletecat' name='action'>
		<input type='submit' value='delete category' name='deletesubmit' onClick='return confirm("Delete this category?")'>

		</form>
<?	
	}

}

else {

?>
	<form method='post' action='main.php?mancat'>
	<select name='action'>
	<option value=''>-- Choose Action --</option>
	<option value='addcat'>Add category</option>
	<option value='editcat'>Edit category</option>
	<option value='deletecat'>Delete category</option>
	</select> 
	<input type='submit' value='go' name='mancatsubmit'>
	</form>
<?

}

?>