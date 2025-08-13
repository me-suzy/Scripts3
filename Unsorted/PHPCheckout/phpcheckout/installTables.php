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
		<td align="center" width="70%" style="color:white;background-color:darkseagreen;">70%</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- end of shows % installation completed -->





<form name="anyForm" method="post" action="installInsertTest.php">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Install Tables</h4>
		<p>
			The Install Helper has already tried to create the necessary database tables:
		</p>

		<blockquote>
<?php 
$failure = "false";
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
/********************************* CUSTOMER *********************************/
		// create the customer table
		$query = "CREATE TABLE " . TABLECUSTOMER . " (
  customerid mediumint(10) unsigned NOT NULL auto_increment,
  password varchar(15) default NULL,
  email varchar(80) default NULL,
  organization varchar(45) default NULL,
  firstname varchar(35) default NULL,
  lastname varchar(45) default NULL,
  address varchar(70) default NULL,
  city varchar(25) default NULL,
  stateprov varchar(25) default NULL,
  country varchar(40) default NULL,
  postalcode varchar(10) default NULL,
  areacode varchar(7) default NULL,
  phone varchar(20) default NULL,
  fax varchar(20) default NULL,
  cellphone varchar(20) default NULL,
  website varchar(140) default NULL,
  customersince date default NULL,
  visits int(4) unsigned default NULL,
  role varchar(30) default NULL,
  privacy enum('low','medium','high') default 'medium',
  news enum('yes','no') NOT NULL default 'no',
  lastupdate timestamp(14) NOT NULL,
  PRIMARY KEY (customerid),
  KEY multidx(firstname,lastname,city,stateprov)
) TYPE=MyISAM";
		if($queryResultHandle = mysql_query($query, $link_identifier)) { 
			echo"<br>Created table: " . TABLECUSTOMER;
		}else{
			echo "<p style=\"color:red;font-weight:bold;\">" . mysql_error() . "</p>";
		}
/********************************* PRODUCTS *********************************/
		// create the products table
		$query = "CREATE TABLE " . TABLEITEMS . "(
  productnumber int(10) NOT NULL auto_increment,
  productname varchar(80) default NULL,
  shortname varchar(20) default NULL,
  resource varchar(80) default NULL,
  status enum('Online','Offline','Unused') NOT NULL default 'Offline',
  version varchar(20) default NULL,
  released date default NULL,
  availability enum('Retail','Free','Other') NOT NULL default 'Retail',
  baseprice decimal(5,2) default NULL,
  merchant int(12) unsigned default NULL,
  license varchar(80) default NULL,
  os varchar(40) default NULL,
  language varchar(30) default NULL,
  category varchar(80) default NULL,
  benefit varchar(60) default NULL,
  overview varchar(255) default NULL,
  description blob,
  requirements varchar(255) default NULL,
  filesize varchar(50) default NULL,
  logo enum('yes','no') NOT NULL default 'no',
  logourl varchar(130) default NULL,
  author varchar(80) default NULL,
  companyurl varchar(120) default NULL,
  companyemail varchar(120) default NULL,
  hits int(11) unsigned default NULL,
  position int(1) unsigned default NULL,
  via enum('FTP','HTTP','SMTP Body','SMTP Attachment','CRT','Special') NOT NULL default 'HTTP',
  url varchar(130) default NULL,
  special blob,
  attachment varchar(130) default NULL,
  endorsement blob,
  lastupdate timestamp(14) NOT NULL,
  PRIMARY KEY (productnumber),
  UNIQUE KEY shortname(shortname),
  KEY shortidx(shortname)
) TYPE=MyISAM";

		if($queryResultHandle = mysql_query($query, $link_identifier)) { 
			echo"<br>Created table: " . TABLEITEMS;
		}else{
			echo "<p style=\"color:red;font-weight:bold;\">" . mysql_error() . "</p>";
		}
/********************************* PURCHASES *********************************/
		// create a table to hold the purchase data
		$query = "CREATE TABLE " . TABLEPURCHASE . " (
  transaction mediumint(9) unsigned NOT NULL auto_increment,
  customerid mediumint(9) unsigned NOT NULL default '0',
  item varchar(20) default NULL,
  pnum int(10) default NULL,
  pricepaid decimal(5,2) default NULL,
  purchasedate date default NULL,
  PRIMARY KEY (transaction)
) TYPE=MyISAM";
		if($queryResultHandle = mysql_query($query, $link_identifier)) { 
			echo"<br>Created table: " . TABLEPURCHASE;
		}else{
			echo "<p style=\"color:red;font-weight:bold;\">" . mysql_error() . "</p>";
		}

/********************************* ADMIN USAGE *********************************/
		// create admin usage monitor table
		$query = "CREATE TABLE " . TABLEUSAGE . " (
  transaction int(10) NOT NULL auto_increment,
  admin_access date default NULL,
  t timestamp(14) NOT NULL,
  ip varchar(15) default NULL,
  port varchar(12) default NULL,
  PRIMARY KEY (transaction)
) TYPE=MyISAM";
		if($queryResultHandle = mysql_query($query, $link_identifier)) { 
			echo"<br>Created table: " . TABLEUSAGE;
		}else{
			echo "<p style=\"color:red;font-weight:bold;\">" . mysql_error() . "</p>";
		}
/********************************* SURVEY *********************************/
		// create a table to hold the survey data
		$query = "CREATE TABLE " . TABLESURVEY . " (
  transaction int(12) unsigned NOT NULL auto_increment,
  role varchar(40) default NULL,
  numdevelopers varchar(20) default NULL,
  dbtype varchar(40) default NULL,
  webserver varchar(40) default NULL,
  os varchar(40) default NULL,
  language varchar(30) default NULL,
  hearabout varchar(50) default NULL,
  surveyid int(12) default NULL,
  lastupdate timestamp(14) NOT NULL,
  response enum('yes','no','unused') default 'unused',
  PRIMARY KEY (transaction)
) TYPE=MyISAM";
  		if($queryResultHandle = mysql_query($query, $link_identifier)) { 
			echo"<br>Created table: " . TABLESURVEY;
		}else{
			echo "<p style=\"color:red;font-weight:bold;\">" . mysql_error() . "</p>";
		}
/********************************* CREDIT CARD *********************************/
		// create a credit card table for future use only
		$query = "CREATE TABLE " . TABLECREDITCARD . "(
  cccounter int(7) unsigned NOT NULL auto_increment,
  ccid int(7) unsigned NOT NULL default '0',
  ccamount decimal(4,2) NOT NULL default '0.00',
  cctype varchar(20) NOT NULL default '',
  ccnumber varchar(16) NOT NULL default '',
  ccexpiry varchar(15) NOT NULL default '',
  ccnameoncard varchar(40) NOT NULL default '',
  cclastupdate timestamp(14) NOT NULL,
  PRIMARY KEY  (cccounter)
)";
		if($queryResultHandle = mysql_query($query, $link_identifier)) { 
			echo"<br>Created table: " . TABLECREDITCARD;
		}else{
			echo "<p style=\"color:red;font-weight:bold;\">" . mysql_error() . "</p>";
		}
	}else{ // select
		$failure = "true";
		echo mysql_error();
	}
}else{ //pconnect
	$failure = "true";
	echo mysql_error();
}
if($failure == "true") {
	echo"<p><b>Open the file called <i>configure.php</i> and double check your /* Database connectivity */  <span style=\"color:green;font-weight:bold;\">values</span>.</b></p>";
}else{
	echo"<p>To continue, please click on the &quot;Next&quot; button.</p>";
}
?>
		</blockquote>
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