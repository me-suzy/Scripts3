<?

$cl = new TheCleaner();

$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

// ##### grab the subcategory and story count details  ##### //

$table = $dl->select("COUNT(st.sid) AS stocount, st.*, su.*",
		"sl18_subcategory su LEFT JOIN sl18_stories st ON st.ssubid=su.subid",array('su.subcatid'=>$_GET["cat"]),
		"GROUP BY su.subid ORDER BY su.subname ASC");

?>

<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis' width='34%' valign='top'>

<?

if ( count( $table ) == 0 ) {

	print "There are no sub-categories contained in this category";

} else {

	$count = 1;
	$limit = ceil( count( $table ) / 3 );

// ##### print it all out in 3 columns  ##### //

	foreach ($table as $row) {
		
		print "<a href='main.php?list=" . $row["subid"] . "'>" . $cl->preva($row["subname"]) . "</a> ";
		print "<font class='small'>[Fics: " . $row["stocount"] . "]</font><br> \n";
		$count ++;
	
		if ($count > $limit) {
			print "</td>\n<td class='cleardis' width='33%' valign='top'>\n";
			$count = 1;
		}
	}
}

?>
		</td>
	</tr>
</table>
