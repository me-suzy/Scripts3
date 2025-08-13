<?
$cl = new TheCleaner();
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

print "<SCRIPT LANGUAGE=\"JavaScript\"> \n";
print "cat = new Array( \n";

$table = $dl->select("*","sl18_category"," ","ORDER BY caname ASC");

foreach($table as $row) {
	print "new Array( \n";
	$tableb = $dl->select("*","sl18_subcategory",array('subcatid'=>$row["caid"]),"ORDER BY subname ASC");
	foreach($tableb as $rowb) {
		$menu[] =  "new Array(\"".$cl->preva($rowb["subname"])."\", ".$rowb[subid].")";	
	}	

	if ( !empty ( $menu ) ) {
		print implode(", \n",$menu);
		$menu = empty($menu);
	}
	print "), \n";
}

print "new Array( \n";
print "null \n";
print ") \n";
print ") \n;";

print "function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) { \n";
print "var i, j; \n";
print "var prompt; \n";
print "for (i = selectCtrl.options.length; i >= 0; i--) { \n";
print "selectCtrl.options[i] = null; \n";
print "} \n";
print "prompt = (itemArray != null) ? goodPrompt : badPrompt; \n";
print "if (prompt == null) { \n";
print "j = 0; \n";
print "} else { \n";
print "selectCtrl.options[0] = new Option(prompt); \n";
print "j = 1; \n";
print "} \n";
print "if (itemArray != null) { \n";
print "for (i = 0; i < itemArray.length; i++) { \n";
print "selectCtrl.options[j] = new Option(itemArray[i][0]); \n";
print "if (itemArray[i][1] != null) { ";
print "selectCtrl.options[j].value = itemArray[i][1]; \n";
print "}\n";
print "j++; \n";
print "} \n";
print "selectCtrl.options[0].selected = true;\n";
print "} \n";
print "} \n";
print "</script>";
?>