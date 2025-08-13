<?

$dl = new TheDB();	
$dl->connect() or die($dl->getError());
$dl->debug=false;
$cl = new TheCleaner();

// ##### grab the category details and start the display count  ##### //

$table = $dl->select("*","sl18_category"," ","ORDER BY caname ASC");
$count = 1;

?>

<table border='0' width='100%' cellspacing='4'>
	<tr>
<?


// ##### display the categorys and find the subcategories  ##### //

foreach($table as $row) {
	print "<td width='33%' class='cleardis'> \n";

	if ( !empty( $row["capic"] ) )
		print "<img src='" . $row["capic"] . "' align='left'> \n";

	print "<a href='".SL_ROOT_URL."/main.php?cat=".$row["caid"]."'>" . $cl->preva($row["caname"]) . "</a><br> \n";

	if ( !empty( $row["cadescript"] ) )
		print $cl->preva($row["cadescript"]) . "<br>";

	$tableb = $dl->select("*","sl18_subcategory",array(subcatid=>$row["caid"]),"ORDER BY subname ASC LIMIT 0,3");
	foreach($tableb as $rowb) {
		$sub[] = "<a href='".SL_ROOT_URL."/main.php?list=".$rowb["subid"]."' class='small'>".$cl->preva($rowb["subname"])."</a>";
	}	

	if ( !empty( $sub ) ) {
		print implode(", ",$sub) . " ... \n";
		$sub = empty($sub);
	}
	print "</td>";

	$count ++;

	if ($count == 4) {						// if there are 4 columns, start a new row lest the bottom scroll go on forever
		print "</tr><tr> \n";
		$count = 1;
	}
}

?>

	</tr>
</table>