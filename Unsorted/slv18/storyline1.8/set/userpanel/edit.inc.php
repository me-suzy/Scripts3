<?
$cl = new TheCleaner;
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

if ($_POST["action"] == "addchapter" && !empty($_POST["story"])) {
?>
	<form method='post' action='base/trans.php?addchapter' enctype='multipart/form-data'>
	Chapter Title <input type='text' name='cname'>
	<p>
	Upload File<br>
	<input type='file' name='upchapa'>
	<p>
	<u>or</u>
	<p>
	Input text<br>
	<textarea name='upchapb' style='height:300px; width:100%'></textarea>
	<p>
	<input type='hidden' value='<?=$_POST["story"]?>' name='sid'>
	<input type='submit' name='addchaptersubmit' value='add chapter'>
	</form>
<?
} elseif ($_POST["action"] == "editdetails" && !empty($_POST["story"])) {

	include(SL_ROOT_PATH."/base/catsubcat.inc.php");
	$call = $dl->select("s.*,sg.*",
		"sl18_stories s LEFT JOIN sl18_subcategory sg ON sg.subid=s.ssubid",
	array('s.sid'=>$_POST["story"]),"GROUP BY s.sid");
?>
	<form method='post' action='base/trans.php?editstory'>
	<table border='0' width='100%'>
		<tr>
			<td width='20%'>Title</td><td><input type='text' name='sname' value='<?= $cl->inputv($call[0]["sname"]) ?>' maxlength='50'></td>
		</tr><tr>
			<td width='20%'>Description</td><td><input type='text' name='sdescrip' value='<?=$cl->inputv($call[0]["sdescrip"])?>' maxlength='255'></td>
		</tr><tr>
			<td width='20%'>Area</td>
			<td>
		<select name='addcat' onChange='fillSelectFromArray(this.form.addsubcat, ((this.selectedIndex == -1) ? null : cat[this.selectedIndex-1]));' class='select'>
				<option value=''>-- Choose Category --</option>
<?
				$table = $dl->select("*","sl18_category"," ","ORDER BY caname ASC");
				foreach($table as $row) {
					if($row["caid"] == $call[0]["scid"])
						print "<option value='".$row["caid"]."' selected>".$cl->preva($row["caname"])."</option>";
					else
						print "<option value='".$row["caid"]."'>".$cl->preva($row["caname"])."</option>";
				}
?>
				</select>
				<select name='addsubcat' class='select'>
				<option value='<?=$call[0]["ssubid"]?>'><?=$cl->preva($call[0]["subname"])?></option>
				</select>
			</td>
		</tr><tr>
			<td width='20%'>Rating</td>
			<td>
				<select name='srating'>
<?
				for($i=1;$i<6;$i++) {
					if ($i == $call[0]["srating"])
						print "<option value='".$i."' selected>".rating($i)."</option>";
					else
						print "<option value='".$i."'>".rating($i)."</option>";
				}
?>
				</select>
			</td>
		</tr><tr>
			<td width='20%'>Allow Additions</td>
			<td>
			<select name='sadd'>
<?
				if ( empty( $call[0]["sadd"] ) ) {
					print "<option value='0' selected>Yes</option>";
					print "<option value='1'>No</option>";
				} else {
					print "<option value='0'>Yes</option>";
					print "<option value='1' selected>No</option>";
				}
?>
			</select>
			</td>
		</tr><tr>
			<td colspan='2'>
				<input type='hidden' name='sid' value='<?=$call[0]["sid"]?>'>
				<input type='submit' name='editdetailssubmit' value='edit story'>
			</td>
		</tr>
	</table>
	</form>

<?
} elseif ($_POST["action"] == "editchapter" && !empty($_POST["story"])) {

	$call = $dl->select("*","sl18_chapters",array('csid'=>$_POST["story"]),"ORDER BY cid ASC");
?>
	<SCRIPT LANGUAGE="JavaScript">
	<!-- Begin";
	function chapterWrite(sel) {
		var str = sel.options[sel.selectedIndex].value;
		var inArray = str.split("&&&");
		document.form.cid.value = inArray[0];
		document.form.cname.value = inArray[1];
		document.form.cbody.value = inArray[2];
		document.form.csid.value = inArray[3];
	}
	//  End -->
	</script>

	<select name='chapterlist' onChange='chapterWrite(this)'>
	<option value=''> -- Choose Chapter -- </option>
<?
	foreach($call as $is) {
		print "<option value='".$is["cid"]."&&&".$cl->inputv($is["cname"])."&&&".$cl->textv($is["cbody"])."&&&".$_POST["story"]."'>"
		.$cl->inputv($is["cname"])."</option>";
	}
?>
	</select>
	<p>
	<form name='form' action='base/trans.php?editchapter' method='post'>
	Chapter Title: <input name='cname' type='text'>
	<textarea style='height:300px; width:100%' name='cbody'></textarea>
	<input type='hidden' name='cid'>
	<input type='hidden' name='csid'>
	<input type='submit' name='editchaptersubmit' value='edit chapter'>
	</form>
<?
} elseif ($_POST["action"] == "deletechapter" && !empty($_POST["story"])) {

	$call = $dl->select("*","sl18_chapters",array('csid'=>$_POST["story"]),"ORDER BY cid ASC");
	
	if ( count($call) == 1) 
		print "Your chosen story contains only one chapter, you must use the Delete Story option";
	else {
		print "<form name='form' action='base/trans.php?deletechapter' method='post'>";
		print "<select name='cid'>";
		print "<option value=''> -- Select Chapter -- </option>";
			foreach($call as $row) {
				print "<option value='" . $row["cid"] . "'>" . $cl->preva($row["cname"]) . "</option>";
			}
		print "</select>";
		print "<input type='submit' value='delete chapter' name='deletechaptersubmit' onClick='return confirm(\"Delete chapter ?\")''>";
		print "</form>";
	}

} else {
$table = $dl->select("*",sl18_stories,array('suid'=>$_SESSION["uid"]),"ORDER BY sname ASC");
?>
	<form method='post' action='userpanel.php?edit'>
	<select name='story'>
	<option value=''>-- Select Story --</option>
<?
	foreach($table as $row) {
		print "<option value='" . $row["sid"] . "'>" . $cl->preva($row["sname"]) . "</option>";
	}
?>
	</select>

	<select name='action'>
	<option value=''>-- Select Action --</option>
	<option value='' class='heavydis'>Story</option>
	<option value='editdetails'>&#8226; Edit Details</option>
	<option value='' class='heavydis'>Chapters</option>
	<option value='addchapter'>&#8226; Add Chapter</option>
	<option value='editchapter'>&#8226; Edit Chapter</option>
	<option value='deletechapter'>&#8226; Delete Chapter</option>	
	</select>
	<input type='submit' value='edit' name='editsubmit'>
	</form>
<?
}
?>