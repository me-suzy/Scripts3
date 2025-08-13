<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Survey Results -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, contact, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="Contact DreamRiver.com about PHPCHECKOUT by visiting this page. Select one of many communication methods including phone, stealth email or snail mail options. Profit from your own digital downloads with PHPCHECKOUT.">
</HEAD> 

<body>
<!-- START of body -->


<?php include("header.php");?>

<blockquote>

<?php if(FPSTATUS == 'Online' ):?>
<table width="100%">
	<!-- start of primary content FOR PAGE -->
	<tr>



		<!-- start MAIN COLUMN -->
		<td valign="top">
		<!-- PUT CONTENT RIGHT HERE !!! -->
		

			


			<?php if(OFFERSURVEY == "yes"):?>

				<h1>Survey Results</h1>

<?php
// conditionally update the results if a survey was taken
$update_survey_results = !isset($update_survey_results)?NULL:$_POST['update_survey_results']; // initialize or capture
if( $update_survey_results == "true" ) {
	// validate, insert, next step
   if ( SURVEYNAME == "user" ) { // process the user survey
	/*
		$role = !isset($role)?NULL:$_POST['role']; // initialize or capture
		$numdevelopers = !isset($numdevelopers)?NULL:$_POST['numdevelopers']; // initialize or capture
		$dbtype = !isset($dbtype)?NULL:$_POST['dbtype']; // initialize or capture
		$webserver = !isset($webserver)?NULL:$_POST['webserver']; // initialize or capture
		$os = !isset($os)?NULL:$_POST['os']; // initialize or capture
		$script = !isset($script)?NULL:$_POST['script']; // initialize or capture
		$hearabout = !isset($hearabout)?NULL:$_POST['hearabout']; // initialize or capture
	*/
	   $query = "INSERT INTO " . TABLESURVEY . "(role, numdevelopers, dbtype, webserver, os, language, hearabout, surveyid) VALUES('$role', '$numdevelopers', '$dbtype', '$webserver', '$os', '$language', '$hearabout', '$customerid')";
     	$surveycount = 0;
  		if ($role) { ++$surveycount;}		
  		if ($numdevelopers) { ++$surveycount;}
    	if ($dbtype) { ++$surveycount;}
      if ($webserver) { ++$surveycount;}
  		if ($os) { ++$surveycount;}
  		if ($script) { ++$surveycount;}
  		if ($hearabout) { ++$surveycount;}
  		if ( $surveycount >= MINIMUMSURVEYANSWERS ) {	//if the respondent has answered enough questions then insert to db
         // insert data into table, we have carried surveyid as a hidden variable from the survey form
	      $query = "INSERT INTO " . TABLESURVEY . "(role, numdevelopers, dbtype, webserver, os, language, hearabout, surveyid) VALUES('$role', '$numdevelopers', '$dbtype', '$webserver', '$os', '$language', '$hearabout', '$customerid')";
         // pconnect, select and query
	      if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
		      if ( mysql_select_db(DBNAME, $link_identifier)) {
			      // run the query
   			   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
			      $aRows = mysql_affected_rows(); // how many rows affected?
			      if ($aRows != 1) { // if there was a problem, for example NO insert
				      echo"Your request was not processed. Please try again later.";
			      }
		      }else{ // select
			      echo mysql_error();
		      }
	      }else{ //pconnect
		      echo mysql_error();
	      }    
      } // if ( $surveycount >= MINIMUMSURVEYANSWERS      
   }else{ // process the short survey answered with 'yes' or 'no'
		$response = !isset($response)?NULL:$_POST['response']; // initialize or capture
      if (( $response == "yes" ) || ( $response == "no" )) {
         // ok there was some answer so add it to the database
         $query = "INSERT INTO " . TABLESURVEY . "(response) VALUES('$response')";
         // pconnect, select and query
	      if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
		      if ( mysql_select_db(DBNAME, $link_identifier)) {
			      // run the query
   			   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
			      $aRows = mysql_affected_rows(); // how many rows affected?
			      if ($aRows != 1) { // if there was a problem, for example NO insert
				      // echo"Your request was not processed. Please try again later.";
                  // above is nonessential so is commented out - the main thing here is to let the visitor download
			      } // arows
		      }else{ // select
			      echo mysql_error();
		      } // select
	      }else{ //pconnect
		      echo mysql_error();
	      } // pconnect    
      } // if ( $response == "yes" ... or "no" ...
   } // if ( SURVEYNAME == "user" else
} // if( $update_survey_results == "true"


		// show the survey results
      if( SURVEYNAME == "user" ) { // show the user survey results
		/* This module gathers and presents all of the user survey data */
		$query = "SELECT count(transaction) AS 'Respondents' FROM " . TABLESURVEY . " WHERE response = 'unused'"; // we do not include the results from the short survey
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows > 0 ) {
					$data = mysql_fetch_array($queryResultHandle);
					$respondents = $data["Respondents"];
					echo"<h2>Total Accepted Survey Respondents: $respondents</h2>";
					echo"<blockquote>";

					// run the next report here
					// You found us by?
					$query = "SELECT hearabout, COUNT(hearabout) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY hearabout HAVING Subtotal ORDER BY hearabout ASC";					// run the query
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
					$query = "SELECT numdevelopers, COUNT(numdevelopers) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY numdevelopers HAVING Subtotal ORDER BY numdevelopers ASC";
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
					$query = "SELECT dbtype, COUNT(dbtype) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY dbtype HAVING Subtotal ORDER BY dbtype ASC";
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
					$query = "SELECT os, COUNT(os) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY os HAVING Subtotal ORDER BY os ASC";					// run the query
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
					$query = "SELECT webserver, COUNT(webserver) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY webserver HAVING Subtotal ORDER BY webserver ASC";					// run the query
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
					$query = "SELECT language, COUNT(language) AS 'Subtotal' FROM " . TABLESURVEY . " GROUP BY language HAVING Subtotal ORDER BY language ASC"; // run the query
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
				}else{
					echo"No rows found in survey data.";
				}
			}else{ // select
				echo mysql_error();
			} // select
		}else{ //pconnect
			echo mysql_error();
		} // pconnect
   } // else show short survey?>



			<?php else:?>
			
				<P>The survey is not offered at this time.</P>

			<?php endif;?>

		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>

<p><br></p>



</blockquote>	

</body>
</html>