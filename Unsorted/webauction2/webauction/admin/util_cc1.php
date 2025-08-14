<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";

	$result = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE parent_id='0' and deleted=0 ORDER BY cat_name" );
	$output = "<SELECT NAME=\"id\">\n";
	$output.= "	<OPTION VALUE=\"0\"></OPTION>\n";

		if ($result)
			$num_rows = mysql_num_rows($result);
		else
			$num_rows = 0;

		$i = 0;
		while($i < $num_rows){
			$category_id = mysql_result($result,$i,"cat_id");
			$cat_name = mysql_result($result,$i,"cat_name");
			$output .= "	<OPTION VALUE=\"$category_id\">$cat_name</OPTION>\n";
			$i++;
		}

	$output.= "	<OPTION VALUE=\"\"></OPTION>\n";
	$output.= "	<OPTION VALUE=\"0\">$MSG_292</OPTION>\n";
	$output.= "</SELECT>\n";

	$handle = fopen ( "../includes/categories_select_box.inc.php" , "w" );
	fputs ( $handle, $output );
	fclose ($handle);
	
	Header("Location: ./util_cc2.php?parent=$parent&name=$name");
	exit;
?>
