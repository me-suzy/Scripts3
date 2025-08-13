<?php
// install.php - part of ssLinks v1.22
// ===================================
// This script will install v1.22 of ssLinks

if (!$submit)
{
	// Form not submitted - display form.
?><html>
<head>
<title>ssLinks v1.22 Installation Script</title>
<style type="text/css">
.standard {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 9 pt}
.small {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 7 pt}
</style></head>
<body><center>
<table width=80% border=1 cellpadding=3 cellspacing=1>
<td class="standard"><b>ssLinks v1.22 Installation Script</b><p>

This script will create the database tables needed by ssLinks v1.22.<p>
   
<b>mySQL Database Details:</b><p>

<form action=install.php method=post>
Database Host: <input type=text name=db_host size=20 value="localhost"><p>

Database Username: <input type=text name=db_user size=20 value="Username"><p>

Database Password: <input type=text name=db_pass size=20 value="Password"><p>

Database Name: <input type=text name=db_name size=20><p>

<input type=submit name=submit value="Go!">
</td></table></center></body></html>
<?php
exit;
}

// If script gets this far they have submitted

if ((!$db_host) || (!$db_user) || (!$db_pass) || (!$db_name))
{
	header("Location: install.php");
	exit;
}

$sql1 = "CREATE TABLE sslinkcats (
   lcat_id int(11) NOT NULL auto_increment,
   lcat_cat int(11) DEFAULT '0' NOT NULL,
   lcat_name varchar(100) NOT NULL,
   lcat_header text,
   lcat_ranking int(11),
   lcat_numlinks int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (lcat_id)
)";

$sql2 = "CREATE TABLE sslinks (
   link_id int(11) NOT NULL auto_increment,
   link_cat int(11) DEFAULT '0' NOT NULL,
   link_name varchar(100) NOT NULL,
   link_url varchar(255) NOT NULL,
   link_desc text,
   link_hits int(11) DEFAULT '0' NOT NULL,
   link_totalrate int(11),
   link_numvotes int(11),
   link_dateadd int(11),
   link_addemail varchar(255),
   link_addname varchar(100),
   link_validated char(3),
   link_recommended char(3) DEFAULT 'no' NOT NULL,
   PRIMARY KEY (link_id)
)";

$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or die("Unable to connect to database server.");
mysql_select_db($db_name, $cnx)
		or die("Unable to select database.");
$result = (mysql_query($sql1) && mysql_query($sql2));

if (!$result)
	die("The install failed for some reason.  Are you sure the database exists and your connection details are correct?");

?><html>
<head>
<title>ssLinks Installed Sucessfully</title>
<style type="text/css">
.standard {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 9 pt}
.small {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 7 pt}
</style></head>
<body><center>
<table width=80% border=1 cellpadding=3 cellspacing=1>
<td class="standard"><b>Intallation Complete!</b><p>

The database tables have been created, and ssLinks should be ready to run.  Don't forget to
edit the values in your global.inc.php file first...<p>

Now go <a href="links.php">Here</a>.
</td></table></center></body></html>