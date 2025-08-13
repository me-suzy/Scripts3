<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php include_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<title>Showcase Product - <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows - showcase of digital products and services - Profit from your own digital downloads with PHPCHECKOUT - php checkout software from DreamRiver.com - easily create your own php checkout for any digital product - Profit from your digital downloads - software, audio, video, images, books ... phpcheckout is a front-to-back solution which lets you market, sell and deliver digital products on your website. Easy to use point and click controls ... and much more ... php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpyellow Pages - EasySQL - phpTellAFriend - mysql php download phpyellow.com dreamriver.com phpcheckout.com</title>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, showcase, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="PHPCHECKOUT lets you create your own php showcase of digital products and services. This showcase page lets you easily display, market and sell digital products right on your own website. Profit from your digital downloads. Buy phpcheckout today and sell your digital products online tomorrow.">
	</HEAD> 

<body>
<!-- START of body -->

<?php include("header.php");?>

<table width="100%">
	<!-- start of primary content FOR PAGE -->
	<tr>
		<!-- start MAIN COLUMN -->
		<td valign="top" width="75%">
		<!-- PUT CONTENT RIGHT HERE !!! -->
		
<?php
// get a product from the database
// initialize the offset number if needed
if (!isset($offset)){$offset = '0';}else{$offset = $offset + 1;} // set the default offset to zero or increment the existing offset

// concatenate the limit for the mySQL select
$comma = ",";
$myNewLimit = " LIMIT " . $offset . ",1";

// set the query
if(isset($productnumber)) {
   $query = "SELECT * FROM " . TABLEITEMS . " WHERE status='Online' AND productnumber = $productnumber";
}else{
   $query = "SELECT * FROM " . TABLEITEMS . " WHERE status='Online' $myNewLimit";
}

if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
	   $queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
      $rows = mysql_num_rows($queryResultHandle);
      if ( $rows > 0 ) {
		// ok we have a valid query so lets get all the data
		// get the data for one product
  		$data = mysql_fetch_array($queryResultHandle);
		$productnumber = stripslashes($data["productnumber"]);
		$shortname = stripslashes($data["shortname"]);
		$productname = stripslashes($data["productname"]);
		$version = stripslashes($data["version"]);
		$released = stripslashes($data["released"]);
		$availability = stripslashes($data["availability"]);
		$baseprice = stripslashes($data["baseprice"]);
		$merchant = stripslashes($data["merchant"]);
		$license= stripslashes($data["license"]);
		$status  = stripslashes($data["status"]);	
		$os = stripslashes($data["os"]);
		$language  = stripslashes($data["language"]);
		$benefit = stripslashes($data["benefit"]);
		$overview = stripslashes($data["overview"]);
		$description = stripslashes($data["description"]);
		$requirements = stripslashes($data["requirements"]);
		$filesize = stripslashes($data["filesize"]);
		$logo = stripslashes($data["logo"]);
		$author = stripslashes($data["author"]);
		$companyurl = stripslashes(urldecode($data["companyurl"]));
		$companyemail = stripslashes($data["companyemail"]);										
		$logourl = stripslashes(urldecode($data["logourl"]));
		$category = stripslashes($data["category"]);					
		$resource = stripslashes($data["resource"]);	
		$hits = stripslashes($data["hits"]);	
		$position = stripslashes($data["position"]);
		$url = stripslashes(urldecode($data["url"]));
		$via = stripslashes($data["via"]);
		$special = stripslashes($data["special"]);
		$attachment = stripslashes(urldecode($data["attachment"]));					
		$endorsement = stripslashes($data["endorsement"]);
	}else{  //       if ( $rows > 0 ) {
?>
		<br>
		<h1>Showcase</h1>
		<p>You have reached the end of the Showcase.</p>
		<form name="goToShowcaseIndexForm" method="post" action="showcaseIndex.php">
			<input type="submit" name="submit" class="submit" value="Go to Showcase Start">
		</form>

		<?php exit;
		}
   
   }else{ // select
	   echo mysql_error();
   } // select
}else{ //pconnect
   echo mysql_error();
} //pconnect
?>	


	
<?php
/* this code is for the 'Hits This Product' HIT COUNTER and is the generic module
   increment the total hit counter for the product			
*/
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		// get the current total and anything else we need
		$query = "SELECT hits FROM " . TABLEITEMS . " WHERE productnumber='$productnumber'";
   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		$rows = mysql_num_rows($queryResultHandle);
		switch($rows) {
			case 1:
				$totalhits = mysql_result($queryResultHandle,0 );
				++$totalhits;
				$query = "UPDATE " . TABLEITEMS . " SET hits = $totalhits WHERE productnumber='$productnumber' ";
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				break;
			case 0:
				echo"No rows found.";
				break;
			default:
				echo"Out of range.";
		}
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
}
?>


		
		<!-- back next control form -->
		<!-- this form is set up to point to the next product -->
		<form name="backNextControlsForm" method="post" action="showcase.php">
      <input type="hidden" name="offset" value="<?php echo $offset;?>">
		<input class="back" type="button" name="previousPage" value=" <== Back " onClick="history.back(1)">
		<input class="submit" type="submit" name="nextResultSetSubmitButton" value=" Next ==>"> 
		<span class="note"> Products Showcase </span>
		</form>
		<!-- end of showcase controls form -->
		



		<div align="center" style="font-size:14px;font-weight:bold;">
      <?php $goalUrlEncoded = urlencode("Retrieve Product Data");?>
		<a href="runner.php?goal=<?php echo $goalUrlEncoded;?>&productnumber=<?php echo $productnumber;?>">>>> <?php if($availability=="Retail"){echo"PURCHASE";}else{echo"DOWNLOAD";}?> <<<</a>
		</div>


		<h1><?php echo $productname;?></h1>


		<h2 class="benefit">&quot;<?php echo $benefit;?>&quot;</h2>


		<?php if(!empty($logourl)):?>
			<div align="center">
			<a href="runner.php?goal=<?php echo $goalUrlEncoded;?>&productnumber=<?php echo $productnumber;?>">
				<img src="<?php echo $logourl;?>" align="absmiddle" border=0 hspace=0 vspace=0 alt="Click Here to <?php if($availability=="Retail"){echo"Purchase";}else{echo"Download";}echo" $productname";?>">
			</a>
			</div>
		<?php endif;?>
				
		
		<h3>Overview</h3> 
		<p>
		<?php echo $overview;?>
		</p>

		
		<?php if(!empty($endorsement)):?>
		<h3>Kudos</h3>
		<p>
		<div class="favcolor2">
		<?php echo $endorsement;?>
		</div>
		</p>
		<?php endif;?>
				

		
		<br>
		<h3>Description</h3>
		<p><?php echo $description;?></p>


		<h3>Intellectual Property</h3>
		<p style="font-weight:bold;">
		Author <?php echo $author;?><br>
		<br>

		
		<?php 
			/*
				Stealth email. The "to" and "domain" portions of the email, 
				between the "@" symbol, are sent to email.php. email.php 
				joins them together and launches a header with the two combined.
				Default values in configure.php are used if no value has been 
				set for "to" or for "domain".
			*/
			$totalLength = strlen($companyemail); // the length of the email address
			$posAt = strrpos ( $companyemail, "@"); // the position of the "@" sign
			$domain = substr( $companyemail, $posAt + 1); // get the "domain"
			$to = substr($companyemail, 0, $posAt); // get the "to"
		?>
		<A HREF="email.php?to=<?php echo $to;?>&domain=<?php echo $domain;?>">Send Stealth Email</a>		
		

	
		<br>
		Website <a href="<?php echo $companyurl;?>"><?php echo $companyurl;?></a><br>
		</p>

		
		<h3>Requirements</h3>
		<table align="center" width="90%" class="favcolor2" border=1 cellpadding=10>
			<tr>
				<td>
					<?php echo $requirements;?>
				</td>
			</tr>
		</table>


		<div align="center">			
			<p><i>&quot;<?php echo $benefit;?>&quot;</i></p>

			<form name="showcaseForm" method="post" action="runner.php">
				<input class="submit" type="submit" name="downloadNow" value="<?php if($availability=="Retail"){echo"Purchase";}else{echo"Download";}?> <?php echo $productname;?>">
				<input type="hidden" name="productnumber" value="<?php echo $productnumber;?>">
				<input type="hidden" name="goal" value="Retrieve Product Data">
			</form>
	

			<!-- back next control form -->
			<!-- this form is set up to point to the next product -->
			<form name="backNextControlsForm" method="post" action="showcase.php">
		      <input type="hidden" name="offset" value="<?php echo $offset;?>">
				<input class="back" type="button" name="previousPage" value=" <== Back " onClick="history.back(1)">
				<input class="submit" type="submit" name="nextResultSetSubmitButton" value=" Next ==>">
			</form>
			<!-- end of showcase controls form -->

		</div>


	</td>


		
		<!-- start of right column -->
		<td class="favcolor" width="30%" valign="top">
		
		<?php if( HITSTHISPRODUCT != "off" ) {include("hitsCounter.php");}?>	
<font face="Arial">		
		<h4>Resource Type</h4>
		<?php echo $resource;?>

		<h4>Availability<?php if($availability=="Retail"){echo" &amp; Price";}?></h4>
		<?php echo $availability;if($availability=="Retail"){echo" $ $baseprice";}?>
				
		<h4>License</h4>
		<?php echo $license;?>
		
		<h4>Platform</h4>
		<?php echo $os;?>

		<h4>Language</h4>
		<?php echo $language;?>		

		<h4>Category</h4>
		<?php echo $category;?>		
		
		<h4>Newest Release Date</h4>
		<?php echo $released;?><br>
		yyyy-mm-dd

		<h4>Version</h4>
		<?php echo $version;?>		
		
		<h4>Filesize</h4>
		<?php echo $filesize;?>		
				
</font>				
					
		</span>
		</td>	
		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>



<?php include("footer.php");?>

	
