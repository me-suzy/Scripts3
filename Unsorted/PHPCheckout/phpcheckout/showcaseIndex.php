<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<title>Showcase Index of Products and Services - <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows -Browse All Products & Services - 3 ways to browse - check out your fav digital product - Profit from your own digital downloads with phpCheckout - software from DreamRiver.com - easily create your own php checkout for any digital product - Profit from your digital downloads - software, audio, video, images, books ... phpcheckout is a front-to-back solution which lets you market, sell and deliver digital products on your website. Easy to use point and click controls ... and much more ... php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpyellow Pages - EasySQL - phpTellAFriend - mysql php download phpyellow.com dreamriver.com phpcheckout.com</title>

		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="phpCheckout, checkout, php, check, out, php checkout, products page, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="phpCheckout lets you create your own php checkout. Ideal for digitally downloaded products or services. This page is the start page for browsing product and service information. Profit from your digital downloads - software, audio, video, images, books ... phpcheckout is a front-to-back solution which lets you market, sell and deliver digital products on your website. Easy to use point and click controls ... and much more ... ">
	</HEAD> 

<body>
<!-- START of body -->


<?php include("header.php");?>

	<?php if(FPSTATUS == 'Online' ):?>

					<!-- PUT CONTENT RIGHT HERE !!! -->
					<blockquote>
					



									<form name="startShowcaseBrowseForm" method="post" action="showcase.php">
										<h1>3 ways to browse items</h1>




										<!-- start of 3 ways to browse items -->
										<table width="85%" cellspacing=0 cellpadding=7>
											<tr bgcolor="#EDEDED">
												<td style="font-size:24px;font-weight:bold;">1</td>
												<td valign="middle">
													<input class="submit" type="submit" name="nextProduct" value=" Next ==>">
									</form>
												</td>
											</tr>

							<tr class="favcolor2">
								<td style="font-size:24px;font-weight:bold;">2</td>
								<td>
									<form name="goToProductNumberForm" method="post" action="showcase.php">
										<?php buildProductList("Online");?>
										<input class="submit" type="submit" name="submit" value="Browse">
									</form>
								</td>
							</tr>


							<tr bgcolor="#EDEDED">
								<td valign="middle" style="font-size:24px;font-weight:bold;">3</td>
								<td>
<?php 
// show a list of online products and convert them to text links
$query = "SELECT productname, productnumber, availability, baseprice, benefit FROM " . TABLEITEMS . " WHERE status = 'Online' ORDER BY productname ASC";
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
	   	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
		$rowCount = mysql_num_rows ($queryResultHandle); 
		if ( $rowCount > 0 ) { // if there are rows then process them
			// put products in a nice table
			echo"<table style=\"border-style:dotted;\">";
			echo"<tr><th>Products &amp; Services</th></tr>";
			echo"<tr><td>";
    		while ($row = mysql_fetch_array($queryResultHandle)){
        		$productname = $row["productname"]; 
    	    	$productnumber = $row["productnumber"];
				$availability = $row["availability"];
				$baseprice = $row["baseprice"];
				$benefit = $row["benefit"];
				// show the price if product is for sale
				if($availability=="Retail") {
					$productTextLine = $productname . " - " . $availability . " - '$benefit' - $" . $baseprice;
	    		}else{
					$productTextLine = $productname . " - " . $availability . " - '$benefit'";			
				} 
   	    		echo "<a href=\"showcase.php?productnumber=$productnumber\">$productTextLine</a><br>";
			}
			echo"</td></tr></table>";
		}else{
				echo"<p>No products were found.</p>\n";
		}
	}else{ // select
   		echo mysql_error();
	} // select
}else{ //pconnect
	echo mysql_error();
} //pconnect
?>		

							</form>
								</td>
							</tr>
						</table>
						<!-- end of 3 ways to browse products -->

			

	
		</blockquote>	

	<!-- PUT YOUR ADDITIONAL CODE, IF ANY, ABOVE HERE -->
	<?php else:include('offline.php');endif; // on or offline ?>		

<?php include("footer.php");?>

	
