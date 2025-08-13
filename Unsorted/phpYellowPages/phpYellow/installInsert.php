
<html>
<head>
<?php include("util.php");?>
<title>Install phpYellow Pages - Dreamriver.com - Fine Internet Applications Since 1995</title>
</head>

<body bgcolor="navy" text="white">


<blockquote>

<table border=10 width=600 align="center" bgcolor="gray" cellpadding=0 cellspacing=0><tr>
<td bgcolor="yellow" align="center"><font color="blue">75%</font></td>
<td bgcolor="black" width=150>&nbsp;</td>
</tr></table>




<h1>Install - Insert Test Data</h1>


<p>
This step automatically inserts test data into the phpYellow tables. 
It tests to see if tables are set up correctly and prepares a new installation to 
display test data when this installation is finished.
</p>


<table bgcolor="ivory" cellpadding=5 width="60%" border=1 bordercolor="midnightblue">
<tr>
<td>
<font color="black">

<?php
	$contactTable = DBTABLE;
	$categoryTable = DBTABLE2;		
		$query = "INSERT INTO " . DBTABLE . " ( ypassword, yemail, ycompany, yfirstname, ylastname, yaddress, ycity, ystateprov, ycountry, ypostalcode, yareacode, yphone, ycell, yfax, yurl, ylogo ) VALUES('info', 'info@dreamriver.com', 'DreamRiver', 'Robin', 'Creech', '640 Broadway Avenue', 'Victoria', 'British Columbia', 'Canada', 'V8Z 2G4', '250', '744-3350', '', '', 'http://www.dreamriver.com', '1.jpg')";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
    			$queryResultHandle = mysql_query($query, $link_identifier);
				$rows = mysql_affected_rows($link_identifier);
				if ( $rows == 1 ) {
					echo"<h2>Testing ...</h2>";
					echo"Successfully added one (1) test contact listing record into the $contactTable table.<br>";
					$insertOK = "true";
					// get the unique id number in order to make the relational join
					$query = "SELECT LAST_INSERT_ID()";
	    				$queryResultHandle = mysql_query($query, $link_identifier);
					$ypsid = mysql_result($queryResultHandle,0);
					//echo"<h3>Retrieving Record Key</h3>";
					//echo"Query: $query";
					//echo"<h3>Key ypsid is $ypsid</h3>";
					// now add a corresponding record into the category table
					$query = "INSERT INTO " . DBTABLE2 . " ( ypsid,category,description,rank,paymentrequired,status,expires ) VALUES('$ypsid','Internet-Products','DreamRiver - Software Powers the Net - since 1996. Enjoy phpYellow Pages, Give - Fundraising Software for the Web, and other fine internet software.', '0','no','approved','3000-01-02')";
	    			//echo"<h3>Inserting Category record</h3>";
					//echo"Query: $query";
					$queryResultHandle = mysql_query($query, $link_identifier);
					$rows = mysql_affected_rows($link_identifier);
					if ( $rows == 1 ) {
						//echo"<h2>Test of $categoryTable Table</h2>";
						echo"Successfully added one (1) test category listing record into the $categoryTable table.<br>";
						if( $insertOK == "true" ) {
							echo"<h2><font color=\"green\">Insert Test Data OK</font></h2>";
						}
					}else{
						echo"<br>Category table insert anomaly. 1 Test record NOT inserted.";
						echo mysql_error();						
					}
				}else{
					echo"Contact table insert anomaly. 1 Test record NOT inserted.";
				}
			}else{ // select
				echo"Did not select the database. Open util.php and check your database connectivity settings.<br>";
				echo"<h2>Error</h2>";
		   		echo mysql_error();
			} 
		}else{ //pconnect
			echo"Did not make a connection. Open util.php and check your database connectivity settings.<br>";
			echo"<h2>Error</h2>";
			echo mysql_error();
		}
		?>


</td>
</tr>
</table>



		
<div style="color:yellow;font-weight:bold;">
<ul>
<li>to proceed click &quot;Next Step&quot;</li>
<li>to stop click &quot;Cancel&quot;</li>
</ul>
</div>



<form name="installForm">
<input type="button" name="nextStepButton" value="Next Step" onClick='location.href="installSecurity.php"'>
<input type="button" name="cancel" value="Cancel" onClick='location.href="installCancel.html"'>
</form>


</blockquote>


</body>
</html>


	