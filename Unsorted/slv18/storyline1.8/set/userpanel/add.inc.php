<?
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

$cl = new TheCleaner;

// ########## BEGIN ADD CHAPTER SCREEN ########## //

if ($_POST["nextsubmit"]) {

	if (!$_POST["addtitle"])
		print "Please provide a story title";
	elseif (!$_POST["adddesc"]) 
		print "Please provide a short description";
	elseif ($_POST["addcat"] == 0)
		print "Please choose a category";
	elseif ($_POST["addsubcat"] == 0)	
		print "Please choose a subcategory";

	else {

		if($_POST["addtype"] == "fu") {

			print "<form action='userpanel.php?add' method='post' enctype='multipart/form-data'>\n";
			print "<table border='0' width='100%'>\n";
				print "<tr>\n";
					print "<td class='menu' width='20%'>First Chapter</td>\n";
					print "<td><input type='file' name='upchap' class='input'></td>\n";
				print "</tr><tr>\n";
			
		 } else { 
			print "<form action='userpanel.php?add' method='post'>\n";
			print "<table border='0' width='100%'>\n";
				print "<tr>\n";
					print "<td class='menu' width='20%' valign='top'><br>First Chapter</td>\n";
					print "<td><textarea name='upchap' style='height:300px; width:100%'></textarea></td>\n";
				print "</tr><tr>\n";
		 } 

					print "<td colspan='2'><input type='submit' name='previewsubmit' value='Preview Story' class='button'></td>\n";
				print "</tr>\n";
		print "</table>\n";
		
		print "<input type='hidden' value='" . $cl->trans($_POST["addtitle"]) . "' name='addtitle'>\n";
		print "<input type='hidden' value='" . $cl->trans($_POST["adddesc"]) . "' name='adddesc'>\n";
		print "<input type='hidden' value='" . $_POST["addcat"] . "' name='addcat'>\n";
		print "<input type='hidden' value='" . $_POST["addsubcat"] . "' name='addsubcat'>\n";
		print "<input type='hidden' value='" . $_POST["addrating"] . "' name='addrating'>\n";
		print "<input type='hidden' value='" . $_POST["addalad"] . "' name='addalad'>\n";
		print "</form>\n";

}	
// ########## END ADD CHAPTER SCREEN ########## //



// ########## BEGIN PREVIEW SCREEN ########## //
} elseif ( $_POST["previewsubmit"] ) {

	$table = $dl->select("cg.*, sg.*", 
		"sl18_subcategory sg
			LEFT JOIN sl18_category cg ON cg.caid=sg.subcatid",
		array('sg.subid'=>$_POST["addsubcat"])) or die($dl->getError());

	if ($_FILES["upchap"]["type"] == "application/octet-stream" && !$_POST["upchap"] || !$_FILES["upchap"]["type"] && !$_POST["upchap"]) {			
		print "Please provide a first chapter <br>";
		print $_FILES["upchap"]["type"];
	
	} else {
		
		 print "<form method='post' action='userpanel.php?add'>\n";
		 print "<table border='0' width='100%'>\n";
			print "<tr>\n";
				print "<td class='menu' width='20%'>Title</td><td>" . $cl->preva($_POST["addtitle"]) . "</td>\n";
			print "</tr><tr>\n";
				print "<td class='menu' width='20%'>Description</td><td>" . $cl->preva($_POST["adddesc"]) . "</td>\n";
			print "</tr><tr>\n";
				print "<td class='menu' width='20%'>Category</td><td>" . $cl->preva($table[0]["caname"]) . "</td>\n";
			print "</tr><tr>\n";
				print "<td class='menu' width='20%'>Subcategory</td><td>" . $cl->preva($table[0]["subname"]) . "</td>\n";
			print "</tr><tr>\n";
				print "<td class='menu' width='20%'>Rating</td><td>\n";
				print rating($_POST["addrating"]);
				print "</td>\n";
			print "</tr><tr>\n";

		switch ($_POST["addalad"]) {
			case 1: 
				$w = "No";
				break;
			case 0:
				$w = "Yes";
				break;
		}

				print "<td class='menu' width='20%'>Allow Additions</td><td>" . $w . "</td>\n";
			print "</tr><tr>\n";

		$_POST["upchap"] = $cl->stripper($_POST["upchap"]);


		if ( !$_FILES["upchap"]["type"] ) { 

				print "<td class='menu' width='20%' valign='top'>First Chapter</td><td>" . $cl->prevb($_POST["upchap"]) . "</td>\n";
			print "</tr><tr>\n";


		 
		} else { 				
			if ($_FILES["upchap"]["type"] == "text/plain" || $_FILES["upchap"]["type"] == "text/html") {	
				$_POST["upchap"] = implode("",file($_FILES["upchap"]["tmp_name"]));
				$_POST["upchap"] = $cl->stripper($_POST["upchap"]);
				print "<td class='menu' width='20%' valign='top'>First Chapter</td><td>" . $cl->prevb($_POST["upchap"]) . "\n";
				print "<p>size: " . $_FILES["upchap"]["size"] . "</td>\n";
	
			} else {
				print "<td class='menu' width='20%' valign='top'>First Chapter</td>\n";
				print "<td>Error: You must load a valid file type, you attemped to upload \n";
				print $_FILES["upchap"]["type"] . "</td>\n";
				$auth = "x";			
			}
			print "</tr><tr>\n";
		}
		
		if ($auth != "x") {

				print "<input type='hidden' value='" . $cl->trans($_POST["addtitle"]) . "' name='sname'>\n";
				print "<input type='hidden' value='" . $cl->trans($_POST["adddesc"]) . "' name='sdescrip'>\n";
				print "<input type='hidden' value='" . $_POST["addcat"] . "' name='scid'>\n";
				print "<input type='hidden' value='" . $_POST["addsubcat"] . "' name='ssubid'>\n";
				print "<input type='hidden' value='" . $_POST["addalad"] . "' name='sadd'>\n";
				print "<input type='hidden' value='" . $_POST["addrating"] . "' name='srating'>\n";
				print "<input type='hidden' value='" . $cl->trans($_POST["upchap"]) . "' name='cbody'>\n";
				print "<td colspan='2'><input type='submit' name='finishsubmit' value='Publish' class='sub'></td>\n";
			print "</tr>\n";

		 } 

		print "</table>\n";
		print "</form>\n";
	 } 

// ########## END PREVIEW SCREEN ########## //




// ########## BEGIN FINISH SCREEN ########## //

} elseif ($_POST["finishsubmit"]) {

	$_POST["sname"] = $cl->subm($_POST["sname"]);

	$table = $dl->select("cg.*, sg.*", 
			"sl18_subcategory sg LEFT JOIN sl18_category cg ON cg.caid=sg.subcatid",
		array('sg.subid'=>$_POST["ssubid"])) or die($dl->getError());

	foreach($_POST as $field=>$key) {
		if ($field[0] == "s") {
			$story["sdate"] = date("Y-m-d");
			$story["scdate"] = date("Y-m-d");
			$story["suid"] = $_SESSION["uid"];
			$story["stime"] = date("YmdHis");
			$story[$field] = $key;
		} 
	}

	$dl->insert("sl18_stories",$story) or die($dl->getError());
	$csid = mysql_insert_id();

	foreach($_POST as $field=>$key) {
		if ($field[0] == "c") {
			$chapter["cname"] = $_POST["sname"];
			$chapter["cuid"] = $_SESSION["uid"];
			$chapter["cdate"] = date("Y-m-d");
			$chapter["csid"] = $csid;
			$chapter[$field] = $key;
		} 
	}

	$dl->insert("sl18_chapters",$chapter) or die($dl->getError());

		print "Your story has been published.<br>\n";
		print "<a href='main.php?cat=" . $_POST["scid"] . "'>" . $cl->preva($table[0]["caname"]) . "</a> > \n";
		print "<a href='main.php?list=" . $_POST["ssubid"] . "'>" . $cl->preva($table[0]["subname"]) . "</a> > \n";
		print "<a href='story.php?no=" . $csid . "'>" . $cl->preva($_POST["sname"]) . "</a>\n";

// ########## END FINISH SCREEN ########## //




// ########## BEGIN START SCREEN ########## //

} else {
	include(SL_ROOT_PATH."/base/catsubcat.inc.php");
?>
	<form method='post' action='userpanel.php?add'>
	<table border='0' width='100%'>
		<tr>
			<td class='menu' width='20%'>Title</td><td><input type='text' name='addtitle' class='input' maxlength='50'></td>
		</tr></tr>
			<td class='menu' width='20%'>Description</td><td><input type='text' name='adddesc' class='input' maxlength='255'> 
			<font class='small'>[Can be no longer than 255 characters]</font></td>
		</tr></tr>
			<td class='menu' width='20%'>Area</td>
			<td>
		<select name='addcat' onChange='fillSelectFromArray(this.form.addsubcat, ((this.selectedIndex == -1) ? null : cat[this.selectedIndex-1]));' class='select'>
				<option value=''>-- Choose Category --</option>
<?
				$table = $dl->select("*","sl18_category"," ","ORDER BY caname ASC");
				foreach($table as $row) {
					print "<option value='".$row["caid"]."'>".$row["caname"]."</option>";
				}
?>
				</select>
				<select name='addsubcat' class='select'>
				<option value=''>-- Choose SubCategory --</option>
				</select>
			</td>
		</tr><tr>
			<td width='20%'>Rating</td>
			<td>
				<select name='addrating'>
				<option value='1'><?=rating(1) ?></option>
				<option value='2'><?=rating(2) ?></option>
				<option value='3'><?=rating(3) ?></option>
				<option value='4'><?=rating(4) ?></option>
				<option value='5'><?=rating(5) ?></option>
				</select>
			</td>
		</tr><tr>
			<td width='20%'>Allow Additions</td>
			<td>
				<select name='addalad'>
				<option value='1'>No</option>
				<option value='0'>Yes</option>
				</select>
			</td>
		</tr><tr>
			<td class='menu' width='20%'>Add Chapter By</td>
			<td><select name='addtype' class='select'>
			<option value=''>-- Choose Upload Type --</option>
			<option value='fu'>File Upload [.htm / .html / .txt only]</option>
			<option value='ta'>Text Area</option>
			</select></td>
		</tr><tr>
			<td colspan='2'><input type='submit' value='next ...' class='button' name='nextsubmit'></td>
		</tr>
	</table>
	</form>
<?
}

// ########## END START SCREEN ########## //
?>