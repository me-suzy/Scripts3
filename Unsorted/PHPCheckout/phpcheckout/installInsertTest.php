<?php require_once("configure.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>

		<script language="Javascript" src="appinstaller.js"></script>
		<script language="Javascript">loadCSSFile();</script>
		<title>Install Helper - a DreamRiver Software Product - Easily install DreamRiver Software - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download</title>
	</head> 

<body>
<!-- START of body -->

<h1>DreamRiver Install Helper</h1>

<H2>phpcheckout<sup>TM</sup></H2>




<!-- start of shows % installation completed -->
<table width="60%" border=5 bgcolor="silver">
	<tr>
		<td align="center" width="80%" style="color:white;background-color:darkseagreen;">80%</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- end of shows % installation completed -->





<form name="anyForm" method="post" action="installSecurity.php">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Insert Test Records</h4>
		<p>
			The Install Helper has already tried to insert test records:
		</p>
<?php 
$failure = "false"; // initialize variable
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
/********************************* CUSTOMER *********************************/
		echo"<ul>";
		// create the customer table
		$query = "INSERT INTO " . TABLECUSTOMER . " (password, email, organization, firstname, lastname, address, city, stateprov, country, postalcode, areacode, phone, fax, cellphone, website, customersince, visits, role, privacy, news) VALUES ('test', 'test@dreamriver.com', 'DreamRiver', 'Richard', 'Creech', '640 Broadway Avenue', 'Victoria', 'British Columbia', 'CA', '123 456', '250', '744-3350', 'none', '', 'http://www.dreamriver.com', '2002-09-05', 10, 'Champion', 'high', 'no')";
 		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		echo "<li>" . TABLECUSTOMER . " Test Insert Successful";

/********************************* PRODUCTS *********************************/

		// create the products table
		$query = "INSERT INTO " . TABLEITEMS . "(productname, shortname, resource, status, version, released, availability, baseprice, merchant, license, os, language, category, benefit, overview, description, requirements, filesize, logo, logourl, author, companyurl, companyemail, hits, position, via, url, special, attachment, endorsement) VALUES ('Test Product #1', 'testproductname', 'none', 'Online', '', '2002-08-19', 'Free', '0.00', 0, 'Not Applicable', 'Windows X', 'none', 'other', 'Easily test the phpCheckout Product module', 'This is a test overview.', 'This is a test description. To take this product offline just go to admin.php, click on *Work With Items*, click on *Edit Item*, click on *Retrieve Item*, change Status to *Offline* and submit. To add an item use admin.php > Work with Items > Add. ', 'Requires whatever you describe here.', '123 KB', 'no', '', 'Firstname Lastname', 'http%3A%2F%2Fwww.dreamriver.com', 'test@dreamriver.com', 0, 3, 'CRT', '', 'Message is displayed on the screen (CRT).', NULL, '<i>Test Product #1 is in a class all by itself.</i>\r\n<b>The Tester</b>')";
		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		echo "<li>" . TABLEITEMS . " Test Insert Successful";

/********************************* PURCHASES *********************************/

		// create a table to hold the purchase data
		$query = "INSERT INTO " . TABLEPURCHASE . " (customerid, item, pnum, pricepaid, purchasedate) VALUES ('1', '1', '1', '1.00', '2000-01-01')";
		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		echo "<li>" . TABLEPURCHASE . " Test Insert Successful";

/********************************* ADMIN USAGE *********************************/

		// create admin usage monitor table
		$query = "INSERT INTO " . TABLEUSAGE . " (admin_access, ip, port) VALUES ('2000-01-01', '127.0.0.1', '1234')";
		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		echo "<li>" . TABLEUSAGE . " Test Insert Successful";

/********************************* SURVEY *********************************/

		// create a table to hold the survey data
		$query = "INSERT INTO " . TABLESURVEY . " (role, numdevelopers, dbtype, webserver, os, language, hearabout, surveyid, response) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'yes')";
		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		echo "<li>" . TABLESURVEY . " Test Insert Successful";

/********************************* CREDIT CARD *********************************/
		// create a credit card table for future use only
		$query = "INSERT INTO " . TABLECREDITCARD . "(ccid, ccamount, cctype, ccnumber, ccexpiry, ccnameoncard) VALUES (1, '1.00', 'VISA', '1234567890123456', '0101', 'Mr. John Smith')";
		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		echo "<li>" . TABLECREDITCARD . " Test Insert Successful";
		echo"</ul>";
/////////////////////////////////////////
	}else{ // select
		$failure = "true";
		echo mysql_error();
	}
}else{ //pconnect
	$failure = "true";
	echo mysql_error();
}
if($failure == "true") {
	echo"<p><b>An error resulted. Database test insert failed.</b></p>";
}else{
	echo"<p>To continue, please click on the &quot;Next&quot; button.</p>";
}
?>
		
			

	</td>
	<td bgcolor="ivory" align="center">
		<img src="installPlumeris.jpg" width="230" height="160" border=0>
	</td>
</tr>
<tr>
	<td align="right" colspan=2>
		<hr>
		<input type="button" name="back" value="Back" onclick="history.back(1)">
		<?php if($failure != "true"):?>
			<input type="submit" name="submit" value="Next" class="submit">
		<?php endif;?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancel" value="Cancel" onclick='location.href="installCancel.php"'>
	</td>
</tr>
</table>
</form>


</body>
</html>