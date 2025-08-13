<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis'>

<?
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;
$cl = new TheCleaner();

if (!$_POST["searchsubmit"]) {
?>
			<form method='post' action='search.php'>
			<table border='0' width='100%'>
				<tr>
					<td width='20%'>Keywords</td>
					<td><input type='text' name='for'></td>
				</tr><tr>
					<td width='20%'>In</td>
					<td>
						<select name='in'>
						<option value='sl18_users-upenname'>Authors</option>
						<option value='sl18_stories-sname'>Story Titles</option>
						<option value='sl18_stories-sdescrip'>Story Summery</option>
						<option value='sl18_chapters-cbody'>Full Text</option>
						</select>
					</td>
				</tr><tr>
					<td colspan='2'><input type='submit' name='searchsubmit' value='search'></td>
				</tr>
			</table>
			</form>
<?	
} else {

	$bad[] = " and ";
	$bad[] = " the ";
	$bad[] = " to ";
	$bad[] = " this ";
	$bad[] = " is ";
	$bad[] = " a ";
	$bad[] = " for ";

	foreach($bad as $is) {
		$_POST["for"] = str_replace($is,"",$_POST["for"]);
	}

	$_POST["for"] = trim(strip_tags($_POST["for"]));

	if ( !$_POST["for"] || !$_POST["in"] ) 
		print "Please give key words and an area for your search";

	else {

		$_POST["in"] = str_replace("-",".",$_POST["in"]);

		list($tbl,$fld) = explode(".",$_POST["in"]);

		$in = explode(" ",$_POST["for"]);

		switch ($tbl) {
   			case sl18_users:
        				$get = "uid";
				$rest = "$tbl.*";
        				break;
    			case sl18_stories:
        				$get = "sid";
				$join = "LEFT JOIN sl18_category ON sl18_category.caid=sl18_stories.scid ";
				$join .= "LEFT JOIN sl18_subcategory ON sl18_subcategory.subid=sl18_stories.ssubid ";
				$join .= "LEFT JOIN sl18_users ON sl18_users.uid=sl18_stories.suid";
				$rest = "$tbl.*,sl18_category.*,sl18_subcategory.*,sl18_users.*";
        				break;
    			case sl18_chapters:
        				$get = "cid";
				$join = "LEFT JOIN sl18_stories ON sl18_stories.sid=sl18_chapters.csid ";
				$join .= "LEFT JOIN sl18_category ON sl18_category.caid=sl18_stories.scid ";
				$join .= "LEFT JOIN sl18_subcategory ON sl18_subcategory.subid=sl18_stories.ssubid ";
				$join .= "LEFT JOIN sl18_users ON sl18_users.uid=sl18_chapters.cuid";
				$rest = "$tbl.*,sl18_stories.*,sl18_category.*,sl18_subcategory.*,sl18_users.*";
        				break;
		}
	
		$n = count($in);

		$string = "SUM(IF($tbl.$fld LIKE '%$in[0]%',1,0)) AS thenum0,";

		for($i=1;$i<$n;$i++) {
			$string.= "SUM(IF($tbl.$fld LIKE '%$in[$i]%',1,0)) AS thenum$i,";
		}
	
		$stringb[] = "SUM(IF($tbl.$fld LIKE '%$in[0]%',1,0))";

		for($i=1;$i<$n;$i++) {
			$stringb[]= "SUM(IF($tbl.$fld LIKE '%$in[$i]%',1,0))";
		}

		$stringc = implode("+",$stringb);

		$table = $dl->search("$rest, $string ($stringc)/COUNT($tbl.$fld) AS perc", 		
		$tbl . " " . $join, " ","GROUP BY $tbl.$get ORDER BY perc DESC");

		$count = count($table);
	
		foreach($table as $row) {
			if($row["perc"] != 0.00) {
				if($fld == "upenname") {
					print "<a href='authors.php?no=" . $row["uid"] . "'>" . $row["upenname"] . "</a><p>";
					$auth = 1;
				}
			
				if($fld == "sname") {
					print "<a href='story.php?no=" . $row["sid"] . "'>" . $cl->preva($row["sname"]) . "</a><br>";
					print "<font class='small'>[" . $row["upenname"] . "] " . $cl->preva($row["caname"]) . " > ";
					print $cl->preva($row["subname"]) . "</font><p>";
					$auth = 1;
				}

				if($fld == "sdescrip") {
					print "<a href='story.php?no=" . $row["sid"] . "'>" . $cl->preva($row["sname"]) . "</a><br>";
					print $cl->preva($row["sdescrip"]) . "<br>";
					print "<font class='small'>[" . $row["upenname"] . "] " . $cl->preva($row["caname"]) . " > ";
					print $cl->preva($row["subname"]) . "</font><p>";
					$auth = 1;
				}

				if($fld == "cbody") {
					print "<a href='story.php?no=" . $row["sid"] . "'>" . $cl->preva($row["sname"]) . "</a><br>";
					print $cl->prevb(substr($row["cbody"],0,100)) . "<br>";
					print "<font class='small'>[" . $row["upenname"] . "] " . $cl->preva($row["caname"]) . " > ";
					print $cl->preva($row["subname"]) . "</font><p>";
					$auth = 1;
				}
			}
		}

		if ( !$auth ) {
			print "Your search has not found any results";
		}
	}
}

?>

		</td>
	</tr>
</table>