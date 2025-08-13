<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	$result = mysql_query ( "SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE parent_id='0' and deleted=0 ORDER BY cat_name" );
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
?>