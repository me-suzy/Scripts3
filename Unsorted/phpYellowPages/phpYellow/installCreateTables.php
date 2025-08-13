<html>
<head>
<?php include("util.php");?>
<title>Install phpYellow Pages - Dreamriver.com - Fine Internet Applications Since 1995</title>
</head>

<body bgcolor="navy" text="white">


<blockquote>


<table border=10 width=600 align="center" bgcolor="gray" cellpadding=0 cellspacing=0><tr>
<td bgcolor="yellow" align="center"><font color="blue">62%</font></td>
<td bgcolor="black" width=225>&nbsp;</td>
</tr></table>


<h1>Install - Create Database Tables</h1>


<p>
This step has already automatically tried to create the database tables needed by phpYellow Pages. 
If the tables already exist then the attempt to create tables will be aborted and the installation 
will continue normally. The report below fetches data from your existing table structure.

</p>


<table bgcolor="ivory" cellpadding=5 width="60%" border=1 bordercolor="midnightblue">
<tr>
<td>
<font color="black">
<?php 
$contactTable = DBTABLE;
$query="create table $contactTable ( 
yps mediumint(9) unsigned not null auto_increment PRIMARY KEY,
ypassword varchar(15) not null default 'password',
yemail varchar(80),
ycompany varchar(45),
yfirstname varchar(35),
ylastname varchar(45),
yaddress varchar(70),
ycity varchar(25),
ystateprov varchar(25) not null,
ycountry varchar(40) not null default 'United States',
ypostalcode varchar(10),
yareacode varchar(7),
yphone varchar(20),
yfax varchar(20),
ycell varchar(20),
yurl varchar(140),
ylogo varchar(120),
lastupdate timestamp(14), 
index stateprovidx (ystateprov(15)),
index countryidx (ycountry(20))
)";

if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
		if ( $queryResultHandle = mysql_query($query, $link_identifier)) {
			echo"<h2><font color=\"green\">Created &quot;$contactTable&quot; Table</font></h2>";
			// CREATE the category table
			$categoryTable = DBTABLE2;
			$query="CREATE TABLE $categoryTable(ckey int(9) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,ypsid mediumint(9) UNSIGNED NOT NULL,category varchar(80) NOT NULL DEFAULT 'other',description varchar(255) NOT NULL DEFAULT 'No description',rank tinyint(1) unsigned not null default '0',paymentrequired enum('yes', 'no') default 'no',status enum('pending', 'approved', 'expired') default 'approved',expires date,lastupdate timestamp(14),index catidx (category(15)),index descidx (description(15)))";
			$queryResultHandle = mysql_query($query, $link_identifier) or die ('<h2>Category Table Creation Failed</h2>' . mysql_error() ); 
			echo"<h2><font color=\"green\">Created &quot;$categoryTable&quot; Table</font></h2>";
		} // if

		// show the contact table field information
		$query = "SHOW FIELDS FROM $contactTable";
		$result = mysql_query($query, $link_identifier);
		$rows = mysql_num_rows($result);
				if (mysql_num_rows($result) >= 1){ // if there are rows then show them
					echo"<h2>$contactTable Table</h2>";
					echo"<TABLE BORDER=1><TR>\n";
		           	for ($i = 0; $i < mysql_num_fields($result); $i++) {
    		       		echo("<TH><font color=\"black\">" . mysql_field_name($result,$i) . "</font></TH>");
					}
					echo"</TR>\n";
        		   	for ($i = 0; $i < mysql_num_rows($result); $i++) {
			           	echo("<TR>\n");
    			       	$row_array = mysql_fetch_row($result);
            			for ($j = 0; $j < mysql_num_fields($result); $j++) {
       		    	   		echo("<TD><font color=\"black\">" . $row_array[$j] . "</font></TD>\n");
           				}
     		      		echo("</TR>\n");
        			}
					echo"</TABLE>";
				}
		// show the category table field information
		unset($query);
		unset($result);
		unset($rows);
		$categoryTable = DBTABLE2;
		$query = "SHOW FIELDS FROM $categoryTable";
// print $query;
		$result = mysql_query($query);
		$rows = mysql_num_rows($result);
				if (mysql_num_rows($result) >= 1){ // if there are rows then show them
					echo"<h2>$categoryTable Table</h2>";
					echo"<TABLE BORDER=1><TR>\n";
		           	for ($i = 0; $i < mysql_num_fields($result); $i++) {
    		       		echo("<TH><font color=\"black\">" . mysql_field_name($result,$i) . "</font></TH>");
					}
					echo"</TR>\n";
        		   	for ($i = 0; $i < mysql_num_rows($result); $i++) {
			           	echo("<TR>\n");
    			       	$row_array = mysql_fetch_row($result);
            			for ($j = 0; $j < mysql_num_fields($result); $j++) {
       		    	   		echo("<TD><font color=\"black\">" . $row_array[$j] . "</font></TD>\n");
           				}
     		      		echo("</TR>\n");
        			}
					echo"</TABLE>";
				}
			echo"<h3><font color=\"green\">Two (2) phpYellow Tables Are Installed</font></h3>";


	}else{ // select
			echo"<font color=\"red\">Did not select the database. Open util.php and check your database connectivity settings.";
			echo"<h2>Error</h2></font>";
	   		echo mysql_error();
	} // select
}else{ // pconnect
	echo"<font color=\"red\">Did not make a connection. Open util.php and check your database connectivity settings.";
	echo"<h2>Error</h2>";
	echo mysql_error();
	echo"<p>When ready, click on &quot;Refresh&quot; on your browser.</p></font>";
} // pconnect
?>


</td></tr>


<tr bgcolor="black">
<td>
<h3><font color="red">Trouble Shooting?</font></h3>
<font color="ivory">
<ul>
<li>you may <a href="admin.php">go to Administration</a>, (your username and password are in util.php) run EasySQL and click the Setup buttons to create phpYellow tables
<li>you may also use the EasySQL command line to create the tables - if you know SQL
<li>you may also check with your database administrator to solve problems
</ul>
</font>
</td></tr></table>


		
<div style="color:yellow;font-weight:bold;">
<ul>
<li>to proceed click &quot;Next Step&quot;</li>
<li>to stop click &quot;Cancel&quot;</li>
</ul>
</div>



<form name="installForm">
<input type="button" name="nextStepButton" value="Next Step" onClick='location.href="installInsert.php"'>
<input type="button" name="cancel" value="Cancel" onClick='location.href="installCancel.html"'>
</form>


</blockquote>


</body>
</html>


	