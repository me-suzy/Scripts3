<?php $show = !isset($_GET['show'])?"no":$_GET['show']; // initialize or capture
if ($show == "yes" ) { show_source(ereg_replace("^.+/", "", $PHP_SELF)); exit;} // needed for show source?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<?php require_once("configure.php");?>
		<script language="Javascript" src="phpcheckout.js"></script>
      <link rel=stylesheet type="text/css" href="themeBright.css">
		<TITLE>Index Page -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="PHPCHECKOUT lets you create your own php checkout. Ideal for digitally downloaded products or services. Profit from your digital downloads - software, audio, video, images, books ... phpcheckout is a front-to-back solution which lets you market, sell and deliver digital products on your website. Easy to use point and click controls ... and much more ... ">
	</head> 
<body>
<!-- START of body -->
<?php include("header.php");?>
<?php if(FPSTATUS == 'Online' ):?>
	<!-- start of primary content FOR PAGE -->
	<!-- PUT YOUR CONTENT BELOW HERE !!! -->
	<table width="100%">
		<tr width="100%">
			<!-- left column -->
			<td class="favcolor" valign="top" width="30%" style="border-style:dotted;">
				<table>
				<tr>
					<td bgcolor="yellow" align="center">
						<!-- start of ad_index_left.php -->
							<?php include("ad_index_left.php");?>
						<!-- end of ad_index_left.php -->
					</td>
				</tr>
				<tr>
					<td>
						<h2>Where to look?</h2>
						<p><a href="index.php">Home</a> - this page</p>
						<p><a href="showcaseIndex.php">Items</a> - browse all items</p>
						<p><a href="services.php">Services</a> - additional services</p>
						<p><a href="members.php">Members</a> - access your account</p>
						<p><a href="company.php">Company</a> - about our firm</p>
						<p><a href="startBuy.php">Purchase</a> - buy online</p>
						<p><a href="featureProduct.php">Feature</a> - special item</p>
						<p><a href="index.php?show=yes">Show Source</a> - source code</p>					
					</td>
				</tr>
				</table>
			</td>
			<!-- end of left help column -->




			<!-- start of middle column -->
			<td valign="top" width="40%" align="center">



				<h1>Info</h1>

<?php 
// show a list of online products and convert them to text links
$query = "SELECT productname, productnumber, resource, availability, baseprice FROM " . TABLEITEMS . " WHERE status = 'Online' ORDER BY productname ASC";
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
    	    	$resource = $row["resource"];
				$availability = $row["availability"];
				$baseprice = $row["baseprice"];
				// show the price if product is for sale
				if($availability=="Retail") {
					$productTextLine = "$productname - $resource - " . CURRENCYSYMBOL . $baseprice;
	    		}else{
					$productTextLine = "$productname - $resource - $availability";			
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

				<h1>Download</h1>
				
				<!-- create a select list of online items and show it -->
				<form name="selectProductForm" method="post" action="runner.php">
				<table class="favcolor">
					<tr>
						<th>Select Item</th>
					</tr>
					<tr>
						<td>
							<?php $dataset="Online";buildProductList($dataset);?>
						</td>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="goal" value="Retrieve Product Data">
							<input type="submit" name="submit" value="Next Step" class="submit">
						</td>
					</tr>
				</table>
				</form>



				<h1>Purchase</h1>

				<a href="startbuy.php">Click here to purchase.</a>
				
				</td>
				<!-- end of middle column -->




				<!-- start of right column -->
				<td class="favcolor2" width="30%" align="center" valign="top" style="border-style:dotted;">
					<!-- start of ad_index_right.php -->
					<?php include("ad_index_right.php");?>
					<!-- end of ad_index_right.php -->
					
					
					<h2>Download Steps</h2>
<table>
<tr>
	<td align="left">
								<b>A. Retail</b>
							<ol>
								<li>select item</li>
								<li>checkout</li>
								<li>confirm purchase</li>
								<li>make the payment</li>
								<li>receive the item</li>
							</ol>

							<b>B. Free or Other</b>
							<ul>
								<li>select item</li>
								<li>optional survey</li>
								<li>receive the item</li>
							</ul>
	</td>
</tr>
</table>


				</td>
			</tr>
	</table>		

	
	
	
	
	<!-- PUT YOUR ADDITIONAL CODE, IF ANY, ABOVE HERE -->
	<?php else:include('offline.php');endif; // on or offline ?>		



	<?php include("footer.php");?>