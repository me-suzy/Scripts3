<?php require_once('adminOnly.php');
/* 

AUTHOR: DreamRiver.com (Richard Creech), http://www.DreamRiver.com
TITLE: EasySQL
VERSION: 1.5
DATE RELEASED ON: June 10, 2002
LICENSE FOR EASYSQL: Freeware
DOWNLOAD FROM: http://www.dreamriver.com
MINIMUM REQUIREMENTS: PHP version 4.04 or higher
DESCRIPTION:

EasySQL lets you run SQL commands on your mySQL database 
using just your web browser. Set up EasySQL in three (3) easy steps:

1. Replace the lowercase CONSTANT values below [between the quotes] for 
   DBNAME, DBSERVERHOST, DBUSERNAME and DBPASSWORD
   Example: replace "yourdatabasename" with the real name

2. Upload to your server and surf to the url

3. Optionally download and install admin-Login-Only from 
   http://www.dreamriver.com to help safeguard your data and easysql.php

Warranties and Disclaimers
THIS PUBLICATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE 
IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR 
PURPOSE, OR NON-INFRINGEMENT. DREAMRIVER ASSUMES NO 
RESPONSIBILITY FOR ERRORS OR OMISSIONS IN THIS PUBLICATION OR OTHER 
DOCUMENTS WHICH ARE REFERENCED BY OR LINKED TO THIS PUBLICATION. 	

*/
if(file_exists("util.php")) {
	require_once("util.php");
}elseif (file_exists("configure.php")) {
	require_once("configure.php");
}elseif (file_exists("mysetup.php")) {
	require_once("mysetup.php");
}else{
	define("DBNAME", "yourdatabasename");
	define("DBSERVERHOST", "localhost");
	define("DBUSERNAME", "root");
	define("DBPASSWORD", "");
}
$modifiedColor = "orange";
// initialize or capture variable values
$currentPHPversion = phpversion();
$currentPHPversion = str_replace  ( ".", "", $currentPHPversion); // remove period characters example 4.1.2
$currentPHPversion = substr($currentPHPversion, 0, 2); // get the first 2 characters
// do the same data handling again for the benchmark php version of PHP 4.1.2
$appPHP = 41; // actually 4.1.2 but we chop the 2 anyway 
if($currentPHPversion > $appPHP) { // is current php version greater or less than the minimum needed 4.1.2?
	$query = !isset($_POST['query'])?NULL:$_POST['query'];
	$showLastQuery = !isset($_POST['showLastQuery'])?NULL:$_POST['showLastQuery'];
	$showSyntax = !isset($_POST['showSyntax'])?NULL:$_POST['showSyntax'];
	$myStatus = !isset($_REQUEST['myStatus'])?NULL:$_REQUEST['myStatus'];
}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>EasySQL by DreamRiver - Easily connect with a mySQL database using one php file and three (3) easy steps! - A Dreamriver Software Product - http://www.dreamriver.com - also available: phpYellow Pages, Give - Fundraising Software for the Web, Admin-Login-Only and other fine internet business to business software products</TITLE>
<!-- START OF JAVASCRIPT FUNCTIONS FOR ALL easysql.php RELEASES -->
<script language="JavaScript" type="text/javascript">
<!-- 
/* paste text into the query box */
function pasteCode(choice){
	var writingblock = document.myQueryForm.query;
	switch (choice) {
		case "showTables":
			writingblock.value="SHOW TABLES";
			break;
		default:
			writingblock.value="SHOW TABLES";					
	}
}	
/*
This function tests to see if something has been entered in the query. 
If empty then an alert box appears and the form submission is halted 
*/
function isQuery() {
	var myForm = document.myQueryForm;
	if ( myForm.query.value == 0 ) {
		alert("There is no query.\nPlease enter a query.");
		myForm.query.focus();
		return false;
	}
	return true;
}
// -->
</script>
<!-- END OF JAVASCRIPT FUNCTIONS FOR ALL easysql.php RELEASES -->
<META NAME="Author" CONTENT="Richard Creech, DreamRiver.com Web: http://www.dreamriver.com">
<meta name="Keywords" content="easySQL, easy, SQL, mySQL, connect, database, php, software, db, phpYellow Pages, Give - Fundraising Software for the Web, Admin-Login-Only, Refer and Notify, TellAFriend, fine, internet, business to business, products</TITLE>">
<meta name="Description" content="Easily connect with a mySQL database using just one php file and three (3) easy steps! Also available: phpYellow Pages, Give - Fundraising Software for the Web, Admin-Login-Only and other fine business to business internet software products">
<META name="ROBOTS" content="INDEX,FOLLOW">
</HEAD>
<BODY>
<blockquote>
<?php
$hostname = DBSERVERHOST;
if (!empty($hostname)) {
	$rowsModified = 0;
	$rowsSelected = 0;
	// pconnect, select and query
	if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
		if ( mysql_select_db(DBNAME, $link_identifier)) {
			if((!isset($query)) || empty($query)) {
				echo"<br><b>EasySQL</b> &nbsp;&nbsp;Enter your query into the textarea box then click on <b>RUN MY QUERY</b><br>";
			}else{
				$query = stripslashes($query);
				$result = mysql_query($query, $link_identifier);
				$partialCommand = ltrim($query); // strip leading characters eg. spaces
				$partialCommand = substr ( $partialCommand, 0, 4); /* get first 4 characters for switch()*/
				$partialCommand = strtolower($partialCommand);
				switch($partialCommand) { /* the first 4 letters from each sql statement are used for this switch  */
					case "alte": // zero to nn records are affected with Alter table
					case "dele": // zero to nn records are affected with Delete
					case "inse": // zero to 1 record affected with Insert
					case "upda": // zero to nn records are affected with Update
						if ($result != 0) {
							$rowsModified = mysql_affected_rows($link_identifier);
							$myStatus = "Command valid";
						}else{
							echo("Error " . mysql_errno() . ": " . mysql_error() . "<br>");
						}
						break;
					case "sele": // zero to nn rows returned
						if ($result != 0) {
							$rowsSelected = mysql_num_rows($result);
							$myStatus = "Command valid";
						}else{
							echo("Error " . mysql_errno() . ": " . mysql_error() . "<br>");
						}
						break;
					/* no affected records */
					case "crea": // no records affected
					case "desc":
					case "drop":
					case "load":
					case "show":
						$rowsModified = 0;
						$myStatus = "Command valid";
						break;
					default:
						$myStatus = "ALTER, DELETE, UPDATE, INSERT, CREATE, DESCRIBE, DROP, LOAD, SHOW, &amp; SELECT are the SQL commands currently supported in EasySQL.<br>\n";
				} // switch($partialCommand)
				// display results of the submitted query
				echo"<b>Query:</b> $query<br>\n";
				echo"<b>Result:</b> $myStatus<br>\n";
				echo"<b>Rows: </b> ";
				if ($partialCommand == "sele") {
					echo"$rowsSelected selected<p></p>";
				}else{
					echo "<b><font color=\"$modifiedColor\">$rowsModified modified</font></b><p></p>\n";
				}
				if (@mysql_num_rows($result) != 0){ // if there are rows then show them
					echo"<TABLE BORDER=1><TR>\n";
		           	for ($i = 0; $i < mysql_num_fields($result); $i++) {
    		       		echo("<TH>" . mysql_field_name($result,$i) . "</TH>");
					}
					echo"</TR>\n";
        		   	for ($i = 0; $i < mysql_num_rows($result); $i++) {
			           	echo("<TR>\n");
    			       	$row_array = mysql_fetch_row($result);
            			for ($j = 0; $j < mysql_num_fields($result); $j++) {
       		    	   		echo("<TD>" . $row_array[$j] . "</TD>\n");
           				}
     		      		echo("</TR>\n");
        			}
					echo"</TABLE>";
				}else{
					//echo"No matches.";
				}
			} // if(!isset($query)) ?>
			

				<!-- START OF table to encompass the entire SQL Command entry form -->				
				<br>
				<table class="favcolor" cellpadding="5" align="left" border="10">
					<tr>
						<td>
							<form method="post" name="myQueryForm" action="easysql.php" onsubmit="isQuery()">
								<?php $formuser = !isset($formuser)?NULL:$HTTP_POST_VARS['formuser'];?>
								<?php $formpassword = !isset($formpassword)?NULL:$HTTP_POST_VARS['formpassword'];?>
								<input type="hidden" name="formuser" value="<?php echo $formuser;?>">
								<input type="hidden" name="formpassword" value="<?php echo $formpassword;?>">
								<textarea style="background-color:honeydew;" name="query" cols=75 rows=6 wrap="soft"><?php if ($showLastQuery == "reuse"){echo"$query";}?></textarea><br>
								<!-- ******* START OF STANDARD easysql CONTROLS ******* -->
								<!-- START OF table for standard easysql controls -->
								<table width="100%">
									<tr>
										<td align="right">
											<input style="color:white;font-weight:bold;background-color:green;" type="submit" name="submit" value="RUN MY QUERY" class="submit">
										</td>
									</tr>
									<tr>
										<td align="left">
											<input type="button" name="showTables" value="Show Tables" onclick="pasteCode('showTables')">
											<span style="font-size:x-small;">
												<input type="checkbox" name="showLastQuery" value="reuse"<?php if($showLastQuery == 'reuse'){echo " CHECKED";}?>> Reuse Query 
												<input type='checkbox' name='showSyntax'<?php if($showSyntax == 'on'){echo " CHECKED";}?>> Syntax 
												&nbsp;&nbsp;&nbsp;<a href="http://www.dreamriver.com/software/checkversion.php?installversion=1.6&productname=EasySQL">Check V 1.6</a> &nbsp;&nbsp;&nbsp;
												<a href="http://www.mysql.com/documentation/" target="_blank"><b>mySQL Docs</b></a> 
											</span>
											<?php 
											if($showSyntax == 'on'):?>

												<p></p>

												<b>Syntax</b><br>
This is quick reference. For official documentation please <a href="http://www.mysql.com/documentation/" target="_blank"><b>read the manual.</b></a>

<p></p>

<font size="2">
<b>SELECT</b><br>
syntax: SELECT <i>field1, field2</i> FROM <i>yourTableName</i> LIMIT 100<br>
example: SELECT firstName, lastName FROM user LIMIT 100<br>
outcome: displays records, does NOT change them
<p></p>

<b>DELETE</b><br>
syntax: DELETE FROM <i>yourTableName</i> WHERE <i>yourField</i> = <i>yourValue</i><br>
example: DELETE FROM User WHERE userid = 1000000<br>
outcome: permanently removes records
<p></p>

<b>INSERT</b><br>
syntax: INSERT INTO <i>yourTableName</i>(<i>yourField1, yourField2</i>) VALUES(<i>'value1', 'value2'</i>)<br>
example: INSERT INTO user(firstName, lastName) values('John','Lennon')<br>
outcome: adds a new record
<p></p>

<b>UPDATE</b><br>
syntax: UPDATE <i>yourTableName</i> SET <i>yourField</i> = '<i>value</i>' WHERE <i>yourField</i> = '<i>value</i>'<br>
example: UPDATE user SET firstName='Sean' where lastName='Lennon'<br>
outcome: edits an existing record
<p></p>
</font>
<?php endif;?>
<!-- END OF table for standard easysql controls -->
</td></tr></table>
<!-- ******* END OF STANDARD CONTROLS ******* -->



<!-- This is part of the regular distribution package -->
<p align="center">
<i style="font-size:22px;background-color:honeydew;color:silver;">EasySQL by <a href="http://www.dreamriver.com">Dreamriver</a></i>
</p>
</form>
</td></tr></table>
<!-- END OF table to encompass the entire SQL Command entry form -->				

			<?php
			}else{ // in NO select db
		    	echo mysql_error();
			}
		}else{ // if no connect
			echo mysql_error();
		}
	} // if hostname
?>
</blockquote>
</BODY>
</HTML>

