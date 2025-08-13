<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php include_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		
<TITLE>Purchase -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>

	</HEAD> 

<body>
<!-- START of body -->


<?php include("header.php");?>


<?php if(FPSTATUS == 'Online' ):?>
	<!-- start of primary content FOR PAGE -->
	<!-- PUT YOUR CONTENT BELOW HERE !!! -->
<br>

<!-- if you do not wish table centered then use the image hspace property to horizontally align content -->

		<!-- PUT CONTENT RIGHT HERE !!! -->
		




<table cellpadding=20 cellspacing=10>
<tr>
	<?php if( ORGANIZATION == "DreamRiver" ):?>
	
	
	<!-- left cell -->
	<td bgcolor="honeydew" width=300 align="left">
		
		<p>
			<img src="appimage/paypal.gif" border=0 alt="Join Paypal!">

			<br><b>Send Money</b>
			<br><span style="color:green;font-weight:bold;font-size:14px;">Pay anyone with an email address</span>
			<br><br>
			<b>Request Money</b>
			<br><span style="color:green;font-weight:bold;font-size:14px;">Send a personal or group bill</span>


			<br><br>

			<i style="font-size:x-small;">Easily get started accepting internet payment with Paypal. While it 
			doesn't have all the bells and whistles that complete payment solutions 
			have - it has simplicity and elegance. Easily send or receive money. 
			Rock bottom account startup cost - FREE. No hassles. Proven record. Enjoyed by millions. 
			Sign up today!</i>
			<br><br>
			<a href="https://www.paypal.com/refer/pal=TCH5T7D6FDZ8Y">Click here</a> to sign up with Paypal.
			<br><br>
			<u>Used and enjoyed by DreamRiver.com</u><br>
			<b>Status:</b> International - Verified
			<br><br>
			<!-- Begin PayPal Logo -->
			<table border="0" cellpadding="5" cellspacing="0" align="center"><tr><td align="center"><a href="https://www.paypal.com/affil/pal=paypal%40dreamriver.com" target="_blank"><img src="http://images.paypal.com/images/lgo/logo3.gif" border="0" alt="Pay me securely with your Visa, MasterCard, Discover, or American Express card through PayPal!"></a></td><td><IMG src="http://images.paypal.com/images/logo_cards_150x26.gif" border=0 alt="Visa ,MasterCard, Discover, and American Express"></td></tr></table>
			<!-- End PayPal Logo -->
		</p>
	</td>


<?php endif; // if ORGANIZATION == DreamRiver ?>





	<!-- main cell -->
	<td valign="top">
<h1>Purchase</h1>

<p>Click on Item Name to purchase.</p>

<?php
$query = "SELECT productnumber,productname,baseprice,version,resource FROM " . TABLEITEMS . " WHERE availability='Retail' AND status='Online' ORDER BY productname ASC";
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
	   	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
		$rowCount = mysql_num_rows ($queryResultHandle); 
		if ( $rowCount > 0 ) { // if there are rows then process them
			echo"<table class=\"favcolor\" cellpadding=5 style=\"border-style:dotted;\">\n";
			echo"<th>Item Name</th><th>Price</th><th>Version</th><th>Type</th>\n";
    		while ($row = mysql_fetch_array($queryResultHandle)){
        		$productnumber = $row["productnumber"]; 
    	    	$productname = $row["productname"];
				$baseprice = $row["baseprice"];
				$version = $row["version"];
				$resource = $row["resource"];
				// table formatting
            $goalUrlEncoded = urlencode("Retrieve Product Data");
				echo"<tr><td><a href=\"runner.php?goal=$goalUrlEncoded&productnumber=$productnumber\">$productname</a></td><td>$baseprice</td><td>$version</td><td>$resource</td></tr>\n";
			} // while
				echo"</td></tr></table>\n";
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
	
	</td>


</tr>
</table>

		
		
		
		

<!-- PUT YOUR ADDITIONAL CODE, IF ANY, ABOVE HERE -->

<p><br></p>


	<?php else:include('offline.php');endif; // on or offline ?>		




	<?php include("footer.php");?>

	
