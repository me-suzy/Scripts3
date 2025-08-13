<?php require_once("adminOnly.php");?>
<?php require_once("functions.php");?>
<?php
function showMessage($systemMessage){
	echo"<table width=\"100%\" class=\"systemMessage\"><tr><td align=\"right\">";
	echo"<img src=\"appimage/systemMessage.gif\" width=\"32\" height=\"32\" border=0 alt=\"System Message!\">";
	echo"</td><td align=\"left\">";
	echo $systemMessage;
	$badData = !isset($_GET['badData'])?"false":$_GET['badData'];
	if($badData == "true"){
		echo"<br><blink style='color:red;font-weight:bold;'>Please correct before proceeding</blink>";
	}
	echo"</td></tr></table>\n";
	return true;
}?>
<script language="JavaScript">
<!--
/*
This function launches a temporary popup window which displays the administrative results. 
The window is a reporting tool. The messages it shows are temporary. Therefore, the popup 
has a timeout which you may reset in configure.php to force the popup to close. No point in having 
all these windows open. Choose to close them all after POPUPTIMEOUT milliseconds in configure.php
*/
function popup() {
	var popuptimeout = <?php echo POPUPTIMEOUT;?>;
    newWindow = window.open('','popup','width=420,height=360');
    setTimeout('closeWin(newWindow)', popuptimeout ); // delay POPUPTIMEOUT seconds before closing (set POPUPTIMEOUT in util.php)
}
function closeWin(newWindow) {
	newWindow.close(); // close popup
}	
//-->
</script>
<?php
/* 
Some modules send the goal as a get method query string.
This may break because the client browser link may not handle the spaces correctly. 
To prevent such a problem the goal is urlencoded. Right here - where the goal 
variable is used, the $goal variable is decoded.
*/
$goal = !isset($_REQUEST['goal'])?NULL:$_REQUEST["goal"]; // PHP 4.1.0 Super globals
$productnumber = !isset($_POST["productnumber"])? NULL: $_POST["productnumber"];
# attain the goal
switch ($goal) {
	case "Line_Count":
				if ($handle = opendir('.')) {
					$linecnt = NULL; // initialize
					while (false !== ($file = readdir($handle))) {
						if (substr($file, -3) == "php") {
							$rawfile = file($file);
							$linecnt = $linecnt + count($rawfile);
						}
					}
					closedir($handle);
					echo"<br><p style=\"font-size:x-small;font-style:italic;\">phpCheckout<sup>TM</sup> &gt; $linecnt lines</p>";
				}
		break;
	case "Collect the Garbage":
function delete($file) {
 @chmod($file,0777); // the delete may be a success, even if the chmod fails, thus the @ to suppress warnings
 if (is_dir($file)) {
  $handle = opendir($file); 
  while($filename = readdir($handle)) {
   if ($filename != "." && $filename != "..")
{
    delete($file."/".$filename);
   }
  }
  closedir($handle);
  rmdir($file);
 } else {
  unlink($file);
 }
} // end of function
delete("temp");
drawheader();
echo"<blockquote>";
if (!is_dir("temp")) {
	echo"<h3>Garbage Collected</h3>";
}else{
	echo"<h3>Problem Collecting Garbage!</h3>";
}
echo"</blockquote>";
		break;



	case "Confirm Access":
		if($task != "Proceed" ) {
			drawheader();
			// review the login data	
			$query = "SELECT transaction, ip, port, admin_access FROM " . TABLEUSAGE . " ORDER BY transaction DESC LIMIT 100";
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ($select = mysql_select_db(DBNAME, $link_identifier)) {
	   			$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
					$rowCount = mysql_num_rows ($queryResultHandle); 
					if ( $rowCount > 0 ) { // if there are rows then process them
						echo"<h1><a name=\"top\">Review Last 100 Logins</a></h1>";
						echo"<p>Here are attempts to access your admin page. This data shows internet protocol (IP) number, port and date of access. Hits on the admin page by refresh - from admin - are not counted. Results are limited to the most recent 100 accesses.</p>";
						echo"<p><code>IP = IP number of the visitor machine<br>";
						echo"<br>Port = Port number on the visitor machine</code></p>";
						// put results in a nice table
						echo"<table border=1 align=\"center\">";
						echo"<tr><th>Transaction</th><th>IP</th><th>Port</th><th>Admin Accesses</th></tr>";
    					while ($row = mysql_fetch_array($queryResultHandle)){
							echo"<tr>\n";
			  				$transaction = $row["transaction"]; 
							$ip = $row["ip"];
							$port = $row["port"];
    	    				$admin_access = $row["admin_access"];
							echo"<td>$transaction</td><td>$ip</td><td>$port</td><td>$admin_access</td>";
							echo"</tr>\n";
						}
						echo"<tr><td colspan=4 align=center><a href=\"#top\"><b>Back to Top</b></a></td></tr>";
						echo"</table><br>\n";
						?>
	<form method="post" name="adminConfirmAccessOKForm" action="http://www.dreamriver.com/license/admin.php">
		<input type="hidden" name="sessionloginAttempts" value="<?php echo $sessionloginAttempts;?>">
		<input type="hidden" name="uses" value="<?php echo $uses;?>">
		<input type="hidden" name="goal" value="Confirm Access">
		<input type="hidden" name="task" value="Proceed">
		<input type="hidden" name="serveraddress" value="<?php echo $_SERVER["SERVER_ADDR"];?>">
		<input type="hidden" name="servername" value="<?php echo $_SERVER["SERVER_NAME"];?>">
		<input type="hidden" name="product" value="<?php echo PRODUCTNAME;?>">
		<input type="hidden" name="version" value="<?php echo INSTALLVERSION;?>">
		<input type="hidden" name="email" value="<?php echo SYSTEMEMAIL;?>">
		<input type="hidden" name="adminhome" value="<?php echo ADMINHOME;?>">
		<input type="hidden" name="installpath" value="<?php echo INSTALLPATH;?>">
		<input type="hidden" name="preAdminConfirmAccess" value="<?php echo $HTTP_REFERER;?>">
		<input type="submit" name="submit" value="To Admin" class="submit">
	</form>
				<?php
					}else{
						exit;
					}
				}else{ // select
					exit;
				} // select
			}else{ //pconnect
				exit;
			} //pconnect
		}
		break;
   




  	case "Reset Hits":
		// reset hits
		if ( $newHitValue != NULL ) {
			$query = "UPDATE " . TABLEITEMS . " SET hits=$newHitValue";
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
					$rowCount = mysql_affected_rows (); 
					echo"<h1>$goal</h1>";
					echo"<h2>Result $rowCount records reset to the value of $newHitValue</h2>";
				}else{ // select
	   			echo mysql_error();
				} // select
			}else{ //pconnect
				echo mysql_error();
			} //pconnect
		}else{ // not NULL
			echo"<h1>$goal</h1>";
			echo"<h2>Reset to NULL not allowed</h2>";
			echo"<p>To change hits to zero ( 0 ) press the zero character. Example: <b>0</b></p>";
		}
		break;




  	case "Reset Short Survey Results to ZERO":
		// reset short survey results
		$query = "DELETE FROM " . TABLESURVEY . " WHERE response != 'unused'";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   $queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rowCount = mysql_affected_rows (); 
				echo"<h1>$goal</h1>";
				echo"<h2>Result $rowCount Short Survey records deleted.</h2>";
			}else{ // select
	   			echo mysql_error();
			} // select
		}else{ //pconnect
			echo mysql_error();
		} //pconnect
		break;



  	case "See Survey Results":
		//drawheader();
      if( SURVEYNAME == "user" ) { // show the user survey results
		/* This module gathers and presents all of the user survey data */
		$query = "SELECT count(transaction) AS 'Respondents' FROM " . TABLESURVEY . " WHERE response != 'unused'"; // WHERE = do not include the short survey results ...
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows > 0 ) {
					$data = mysql_fetch_array($queryResultHandle);
					$respondents = $data["Respondents"];
					echo"<h1>See Survey Results</h1>";
					echo"<h2>Total Accepted Survey Respondents: $respondents</h2>";
					echo"<blockquote>";

					// run the next report here
					// You found us by?
					$query = "SELECT hearabout, COUNT(hearabout) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY hearabout ORDER BY hearabout ASC";					// run the query
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					echo"<h3>You found us by?</h3>";
					echo "<table>\n";
					echo"<tr><th>Source</th><th>Subtotal</th></tr>";
					while( $record = mysql_fetch_row ($queryResultHandle)) {
						$cellOne = $record[0];
						if(empty($cellOne)){$cellOne = "No Answer";}
						$cellTwo = $record[1];
						echo"<tr><td>$cellOne</td><td align=\"center\">$cellTwo</td></tr>\n";
					}
					echo"</table>";

					// run the next report here
					// Your role is?
					$query = "SELECT role, COUNT(role) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY role HAVING Subtotal ORDER BY role ASC";
					// run the query
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					echo"<h3>Your role is?</h3>";
					echo "<table>\n";
					echo"<tr><th>Role</th><th>Subtotal</th></tr>";
					while( $record = mysql_fetch_row ($queryResultHandle)) {
						$cellOne = $record[0];
						if(empty($cellOne)){$cellOne = "No Answer";}
						$cellTwo = $record[1];
						echo"<tr><td>$cellOne</td><td align=\"center\">$cellTwo</td></tr>\n";
					}
					echo"</table>";				
					// run the next report here
					// Number of developers?
					$query = "SELECT numdevelopers, COUNT(numdevelopers) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY numdevelopers ORDER BY numdevelopers ASC";
					// run the query
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					echo"<h3>Developers on team?</h3>";
					echo "<table>\n";
					echo"<tr><th>Developers</th><th>Subtotal</th></tr>";
					while( $record = mysql_fetch_row ($queryResultHandle)) {
						$cellOne = $record[0];
						if(empty($cellOne)){$cellOne = "No Answer";}
						$cellTwo = $record[1];
						echo"<tr><td>$cellOne</td><td align=\"center\">$cellTwo</td></tr>\n";
					}
					echo"</table>";				
					// run the next report here
					// Primary database?
					$query = "SELECT dbtype, COUNT(dbtype) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY dbtype ORDER BY dbtype ASC";
					// run the query
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					echo"<h3>Primary database?</h3>";
					echo "<table>\n";
					echo"<tr><th>Database</th><th>Subtotal</th></tr>";
					while( $record = mysql_fetch_row ($queryResultHandle)) {
						$cellOne = $record[0];
						if(empty($cellOne)){$cellOne = "No Answer";}
						$cellTwo = $record[1];
						echo"<tr><td>$cellOne</td><td align=\"center\">$cellTwo</td></tr>\n";
					}
					echo"</table>";

					// run the next report here
					// Primary operating system?
					$query = "SELECT os, COUNT(os) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY os ORDER BY os ASC";					// run the query
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					echo"<h3>Primary operating system?</h3>";
					echo "<table>\n";
					echo"<tr><th>Operating System</th><th>Subtotal</th></tr>";
					while( $record = mysql_fetch_row ($queryResultHandle)) {
						$cellOne = $record[0];
						if(empty($cellOne)){$cellOne = "No Answer";}
						$cellTwo = $record[1];
						echo"<tr><td>$cellOne</td><td align=\"center\">$cellTwo</td></tr>\n";
					}
					echo"</table>";

					// run the next report here
					// Primary web server?
					$query = "SELECT webserver, COUNT(webserver) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY webserver ORDER BY webserver ASC";					// run the query
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					echo"<h3>Primary web server?</h3>";
					echo "<table>\n";
					echo"<tr><th>Web Server</th><th>Subtotal</th></tr>";
					while( $record = mysql_fetch_row ($queryResultHandle)) {
						$cellOne = $record[0];
						if(empty($cellOne)){$cellOne = "No Answer";}
						$cellTwo = $record[1];
						echo"<tr><td>$cellOne</td><td align=\"center\">$cellTwo</td></tr>\n";
					}
					echo"</table>";

					// run the next report here
					// Primary programming language? 
					$query = "SELECT language, COUNT(language) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY language ORDER BY language ASC"; // run the query
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					echo"<h3>Primary programming language?</h3>";
					echo "<table>\n";
					echo"<tr><th>Language</th><th>Subtotal</th></tr>";
					while( $record = mysql_fetch_row ($queryResultHandle)) {
						$cellOne = $record[0];
						if(empty($cellOne)){$cellOne = "No Answer";}
						$cellTwo = $record[1];
						echo"<tr><td>$cellOne</td><td align=\"center\">$cellTwo</td></tr>\n";
					}
					echo"</table>";

					// end of all survey reports
					echo"<p style=\"color:gray;\">End of Document</p>";
					echo"</blockquote>";				
				}else{
					echo"No rows found in survey data.";
				}
			}else{ // select
				echo mysql_error();
			} // select
		}else{ //pconnect
			echo mysql_error();
		} // pconnect



      }else{ // show the short survey results
		   $query = "SELECT count(response) AS 'Respondents' FROM " . TABLESURVEY . " WHERE response != 'unused'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows > 0 ) {
					$data = mysql_fetch_array($queryResultHandle);
					$respondents = $data["Respondents"];
					echo"<blockquote>";
					echo"<h1>See Survey Results</h1>";
               echo"The current question is:<br><br>";
               include("surveyQuestion.php");
					echo"<h2>Total Short Survey Respondents: $respondents</h2>";
               // how many yes?
               $query = "SELECT count(response) AS 'yesvotes' FROM " . TABLESURVEY . " WHERE response = 'yes'";
				   // run the query
		   	   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				   $rows = mysql_num_rows($queryResultHandle); // get # of rows
				   $data = mysql_fetch_array($queryResultHandle);
				   $yesvotes = $data["yesvotes"];
               echo"<h3>&quot;yes&quot; &nbsp;&nbsp;&nbsp; $yesvotes</h3>";

               // how many no?
               $query = "SELECT count(response) AS 'novotes' FROM " . TABLESURVEY . " WHERE response = 'no'";
				   // run the query
		   	   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				   $rows = mysql_num_rows($queryResultHandle); // get # of rows
				   $data = mysql_fetch_array($queryResultHandle);
				   $novotes = $data["novotes"];
               echo"<h3>&quot;no&quot; &nbsp;&nbsp;&nbsp; $novotes</h3>";

					// end of all survey reports
					// provide a back to admin button
					echo"<br><br>\n";
					include("adminBackControl.php");
					echo"</blockquote>";				
				}else{
					echo"No rows found in survey data.";
				}
			}else{ // select
				echo mysql_error();
			} // select
		}else{ //pconnect
			echo mysql_error();
		} // pconnect


      } // else show short survey
		break;















   case "Find Needle in Haystack . . .":
		// initialize or capture
		$ao = $_POST['ao'];
		$needle = $_POST['needle'];
		$haystack = $_POST['haystack'];
		$highlight = $_POST['highlight'];
		drawHeader();
		echo"<blockquote>";
		switch($ao) { // set the ascendancy order ($ao) ASCENDING OR DESCENDING modifier
			case "ASC":
				$ao = "DESC";
				$aoText = "DESCENDING";
				break;
			case "DESC":
				$ao = "ASC";
				$aoText = "ASCENDING";
				break;
			default:
				$ao = "DESC";
				$aoText = "DESCENDING";
		}
		if(empty($fieldToOrderBy)){$fieldToOrderBy = "customerid";} // set the default sql ORDER BY
		if(empty($myLimit)){$myLimit=20;}// set the default sql LIMIT
		$queryOfTotalRows = "SELECT count(customerid) FROM " . TABLECUSTOMER . " WHERE $haystack LIKE '%$needle%'";
		$query = "SELECT * FROM " .  TABLECUSTOMER . " WHERE $haystack LIKE '%$needle%' ORDER BY $fieldToOrderBy $ao LIMIT $myLimit";
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
		$result = mysql_query($queryOfTotalRows, $link_identifier) or die(mysql_error());
		$totalRows = mysql_result($result, 0);
		//$totalRows = mysql_num_rows($result);
		if ($totalRows >= 1){ // if there are rows then show them
			$result = mysql_query($query, $link_identifier) or die(mysql_error());
			echo"<h1>$goal</h1>";
			if($totalRows == 1) {
				echo"<h3>$totalRows haystack straw contains the Needle &quot;<span class=\"needleFound\">$needle</span>&quot;!</h3>";
			}else{
				echo"<h3>$totalRows haystack straws contain the Needle &quot;<span class=\"needleFound\">$needle</span>&quot;!</h3>";
			}
			echo"<p><code>$query</code></p>";
			echo"<b>ORDER BY $fieldToOrderBy $ao</b><br>";
			echo"<font size=2>Click a column name to order results by.<br>Click same column name again to reverse the display.</font><br><br>";

         $myCounter = 0; // initialize
			echo"<TABLE BORDER=0><TR>\n";
	      for ($i = 0; $i < mysql_num_fields($result); $i++) {
     			// echo("<TH>" . mysql_field_name($result,$i) . "</TH>");
				include("orderByFormNeedleInHaystack.php");
			}
			echo"</TR>\n";
     		for ($i = 0; $i < mysql_num_rows($result); $i++) {
				if($myCounter == 0){
         	   echo("<TR class=\"tableRowOne\">\n");
               ++$myCounter; // increment
            }else{
         	   echo("<TR class=\"tableRowTwo\">\n");
               --$myCounter; // decrement
            }
            $row_array = mysql_fetch_array($result);
     			for ($j = 0; $j < mysql_num_fields($result); $j++) {
					$currentValue = $row_array[$j];
		
					if(empty($currentValue)){$currentValue = "&nbsp;";}
					switch($highlight) { // puts a green filter highlight on query matches
						case"fuzzy":
							if(stristr( $currentValue, $needle )) {
		  	   				echo("<TD class=\"needleFound\">" . $currentValue . "</font></TD>\n");						
							}else{
  								echo("<TD>" . $currentValue . "</TD>\n");
							}
							break;
						case"near":
							$currentColumn = mysql_field_name($result,$j);
							if((stristr( $currentValue, $needle ))&&($currentColumn==$haystack)) {
		  	   				echo("<TD class=\"needleFound\">" . $currentValue . "</TD>\n");						
							}else{
  								echo("<TD>" . $currentValue . "</TD>\n");
							}
							break;
						case"exact":
							$currentColumn = mysql_field_name($result,$j);
							if((stristr( $currentValue, $needle ))&&($currentColumn==$haystack)) {
								//highlight every $needle within the straw called $currentValue
								$highlightCurrentValue = highlight( $needle, $currentValue );
		  	   				echo("<TD>" . $highlightCurrentValue . "</TD>\n");						
							}else{
  								echo("<TD>" . $currentValue . "</TD>\n");
							}
							break;
						default: // highlight is off
							if($j == 0 ) { // add a link to the edit page
								$customerid = $currentValue; $task = urlencode("Retrieve Customer Record"); 
								echo"<td><a href=\"workWithCustomer.php?task=$task&customerid=$customerid\">Edit $customerid</a></td>\n";
							}else{
								echo("<TD>" . $currentValue . "</TD>\n");				
							}
					}
	  			}
   	     	echo("</TR>\n");
     		} // for ... as long as there are rows
				echo"</TABLE>";
		}else{
				echo"<h1>No Matches</h1>";
				echo"<p>No results were found. Try again?</p>";
		}
	} // select
}
		include("findNeedleInHaystackForm.php");
		include("adminBackControl.php");
		echo"</blockquote>\n";
		break;		



     default:
         echo "<h1>Oops!</h1>";
         print("Please contact " . TECHNICALSUPPORT); 
         break;       
  }
?>

