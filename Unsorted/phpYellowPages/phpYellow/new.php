<?php require("util.php");?>
<?php 
function documentStart($goal){
	include("documentStart.php");
	return $goal;
}
documentStart("New!");?>


<H2>NEW and Newly Updated Listings!</H2>

<div class="yellow">
<?php 
$query = "SELECT * FROM contact, category WHERE yps=ypsid AND status='approved' AND expires >'$todaysDate' ORDER BY category.lastupdate DESC, rank DESC, ckey DESC LIMIT " . RECORDSPERPAGE;
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
		// RUN THE QUERY TO RETREIVE EACH FOUND RECORD
	   $queryResultHandle = mysql_query($query, $link_identifier) or die ( mysql_error()); 
		// make sure that we recieved some data from our query 
		$rows = mysql_num_rows ($queryResultHandle);
		if ( $rows > 0 ) {
			while ($data = mysql_fetch_array ($queryResultHandle)) {
				// retreive the results
            $yfps = stripslashes($data["yps"]);  // populate the variables with the array element values
           	$yfpassword = stripslashes($data["ypassword"]); // stripslashes reverses addslashes
        		$yfemail = stripslashes($data["yemail"]);
		      $yfcompany = stripslashes($data["ycompany"]);
	   	   $yffirstname = stripslashes($data["yfirstname"]);
				$yflastname = stripslashes($data["ylastname"]);
  			   $yfaddress = stripslashes($data["yaddress"]);
        		$yfcity = stripslashes($data["ycity"]);
		      $yfstateprov = stripslashes($data["ystateprov"]);
   	    	$yfcountry = stripslashes($data["ycountry"]); 
       		$yfpostalcode = stripslashes($data["ypostalcode"]);
				$yfareacode = stripslashes($data["yareacode"]);
           	$yfphone = stripslashes($data["yphone"]);
        		$yffax = stripslashes($data["yfax"]);
        		$yfcell = stripslashes($data["ycell"]);
   	    	$yfurl = stripslashes($data["yurl"]);
				$yflogo = stripslashes($data["ylogo"]);
				$yflastupdate = $data["lastupdate"];
				// and also get the category fields as a result of the join
				// ckey ypsid category description rank paymentrequired status expires lastupdate
				$yfckey = stripslashes($data["ckey"]);
				$yfypsid = stripslashes($data["ypsid"]);
				$yfcategory = stripslashes($data["category"]);
				$yfdescription = stripslashes($data["description"]);
				$yfrank = stripslashes($data["rank"]);
				$yfpaymentrequired = stripslashes($data["paymentrequired"]);
				$yfstatus = stripslashes($data["status"]);
				$yfexpires = stripslashes($data["expires"]);
				$yflastupdate = stripslashes($data["lastupdate"]);
            include("showOneRecord.php");  // display the row values in a template
			} // while
			mysql_free_result ($queryResultHandle);
		} // if rows
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
} 	
?>

</div>


<p></p>



<table width="100%" class="yellow">
	<tr>
		<!-- optionally show the premiumFeatureListing.php file  -->
			<?php 
			if(defined("USEFEATURELISTING")) {
				if(USEFEATURELISTING == "yes") {
					if(defined("FEATURELISTINGNUMBER")) {
						if(FEATURELISTINGNUMBER != NULL) {
							echo"<td valign=\"top\">";
							include("premiumFeatureListing.php"); // show feature listing
						} // !null
					} // defined
				} // use featurelisting
			}else{
				echo"<td>&nbsp;"; // for the Standard Edition
			}?>
		</td>
	</tr>




	<tr width="100%">
		<!-- display the index of listings -->
		<td width="100%" align="left" valign="top">
			<?php include("indexDynamicListings.php"); // creates a dynamic index of listings ?>
		</td>
	</tr>



	
	<tr>
		<!-- put the search form in a table row -->
		<td valign="middle">
			<?php 
				$category = "*";
				if(defined("SETRANK")) {
					if (file_exists("premiumSearchForm.php")) {
						include("premiumSearchForm.php");
					}
				}else{
					include("searchForm.php");
				}
			?>
		</td>
	</tr>




	<!-- put the footer in a table row -->
	<tr>
		<td>
			<?php include("footer.php");?>
		</td>
	</tr>
</table>
