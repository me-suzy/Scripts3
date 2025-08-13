
<!-- start of adminViewQueueForm.php -->
<h2><a name="manageQueue">Manage Queued Listings</a></h2>
<?php 
// show how many listings are pending
$query = "SELECT COUNT(ckey) FROM " . DBTABLE2 . " WHERE status='pending'";
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		// run the query
   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		// make sure that we recieved some data from our query 
		$rows = mysql_result($queryResultHandle,0);
		switch( $rows ) {
			case 0:
				echo"<h3>No records pending</h3>";
				break;
			case 1:
				echo"<h3>$rows record pending</h3>";
				break;
			case 2:
				echo"<h3>$rows records pending</h3>";
				break;
			default:
				echo"<h3>$rows records pending</h3>";
		}
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
}						
?>


</h3>
<p>Preview listings by status. Change listing status. 
Use this tool to retrieve and work with queued records - pending, approved and expired.  
The most recent 100 records are shown.</p>

<form name="viewQueueForm" method="post" action="adminresult.php">
<b>Preview</b><br> 
<input type="radio" name="status" value="pending" CHECKED> Pending 
<input type="radio" name="status" value="approved"> Approved 
<input type="radio" name="status" value="expired"> Expired 
<input type="submit" name="submit" value="Preview Listings by Status" class="input">
<input type="hidden" name="goal" value="Preview Listings by Status">
<input type="hidden" name="formuser" value="<?php print($formuser);?>">
<input type="hidden" name="formpassword" value="<?php print($formpassword);?>">
</form>
<!-- end of adminViewQueueForm.php -->