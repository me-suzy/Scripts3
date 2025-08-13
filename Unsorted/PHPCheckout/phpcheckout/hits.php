
<h2><a name="hits">Show Hits All Items</a></h2>


<?php 
// show a list of online Items and convert them to text links
$query = "SELECT hits, productname, availability FROM " . TABLEITEMS . " order by hits DESC";
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
	   	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
		$rowCount = mysql_num_rows ($queryResultHandle); 
		if ( $rowCount > 0 ) { // if there are rows then process them
			// put Items in a nice table
			echo"<table border=1>";
			echo"<tr><th>Item</th><th>Availability</th><th><a href=\"help.php#hitsthisproduct\" target=\"_blank\">Hits This Item</a></th></tr>";
    		while ($row = mysql_fetch_array($queryResultHandle)){
				echo"<tr>";
        		$productname = $row["productname"]; 
    	    	$hits = $row["hits"];
				$availability = $row["availability"];
				echo"<td>$productname</td><td>$availability</td><td bgcolor='black' align=\"center\" style=\"color:red;font-weight:bold;\">$hits</td>";
				echo"</tr>";
			}
			echo"</table>";
		}else{
				echo"<p>No product hits were found.</p>\n";
		}
	}else{ // select
   		echo mysql_error();
	} // select
}else{ //pconnect
	echo mysql_error();
} //pconnect
?>




