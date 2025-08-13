<?php
/* configure.php
 CONSTANT declarations - DO NOT CHANGE UPPERCASE CONSTANTS.
 Change lowercase values ONLY
 Values you may change are marked with the comment " - modify".
 If it says "DO NOT CHANGE" then don't - or your implementation may not work
 */
$todaysTextDate = date("l F j, Y");  /* eg. Wednesday June 27, 2001 see http://www.php.net/date */
$todaysDate = date("Y-m-d");  /* DO NOT CHANGE eg. 2001-06-01  year month day - DO NOT CHANGE */
// Database, connectivity and paths
define("DBNAME", "yourdatabasename" ); // your mySQL database name - modify
define("DBPASSWORD", "" ); // the password to get into your mysql database - modify
define("DBUSERNAME", "root" ); // your mySQL username - modify
define("DBSERVERHOST", "localhost" ); // the name of your database host server - you can ask your isp what this value should be - modify
/* Database table and command line */
define("TABLECUSTOMER", "pcocustomer" ); /* a single word name only */
define("TABLEITEMS", "pcoitems" ); /* a single word name only */
define("TABLECREDITCARD", "pcocreditcard" ); /* a single word name only */
define("TABLEPURCHASE", "pcopurchase" ); /* a single word name only */
define("TABLEUSAGE", "pcoadminuse" ); /* a single word name only */
define("TABLESURVEY", "pcosurvey" ); /* a single word name only */
define("SQLPROFICIENT", "yes" ); // Are you proficient in SQL? Determines if a link to EasySQL is shown in Administration - good sql knowledge recommended - a value of "yes" will make the link show, anything else will not - modify
define("THRESHOLDMODIFIER", "0" ); // DO NOT CHANGE ( 5 )
/* Paths */
define("INSTALLPATH", "http://www.yourdomain.com/phpcheckout/"); // example: http://www.yourdomain.com/phpcheckout/ The full path to and including your install directory - be sure to include a trailing slash like "/" - modify
define("INDEXPAGE", "index.php"); // your start page
define("DOWNLOADFOLDER", "secret"); // create a folder named 'secret'. the pathname relative to the phpcheckout folder where you store your downloadable files: Example: myfiles
/* Administration */
define("ORGANIZATION", "Your Company" ); /* The name of your organization - 'doing business as' */
define("ADMINHOME", "admin.php"); /* DO NOT CHANGE - your administration page name */
define("ADMINUSER", "admin"); /* your administration login name - modify - you make it up */
define("ADMINPASSWORD", "admin"); /* your administration password - modify - you make it up */
/* Email Contact Data */
	// Public Email - viewable by the public only AFTER the system sends a note with a return address
	define("TO", "stealthEmail"); // the default "to" portion of your email address used in email.php
	define("DOMAIN", "yourdomain.com"); // the default domain portion of your email address used in email.php, example: domain.com
	// System email - generated automatically and used by the system to contact customers
	define("SYSTEMEMAIL", "systememail@yourdomain.com" ); /* system email address used for FROM in sending email notes - modify */
	define("TECHNICALSUPPORT", "webmaster@yourdomain.com" ); /* technical support email address - modify */
/* Important Pages */
define("HOMEPAGE", "http://www.yourdomain.com"); /* indicates your homepage, also used to include DreamRiver specific code, and as webpage to offer as a link to exit payment - modify */
define("PHPCHECKOUTPAGE", "index.php"); /* start page, the page that will load first when phpcheckout is run */
define("CONTACTPAGE", "contact.php" ); /* example: http://www.dreamriver.com/company/index.php - the complete path and filename of your page containing contact information - this is also used in the email invoice and must contain an ABSOLUTE path - modify */
/* How your implementation works */
define("USESERVERNAME", "no"); /* modify - 'yes' or 'no', set it to 'no' for testing, the default : effect is to require posts from this server only for file download and payment transaction processing */
	define("SERVERNAME", "localhost"); /* modify - the servername of your server, do NOT include 'http'. Servername is required for your DreamRiver software license and is used to enhance your own payment and download security */
define("FPSTATUS", "Online"); /* takes the system online or offline - a value of 'Offline' shows offline.php rather than INDEXPAGE. Also, a value of 'Offline' will cause the navControls.php links to dissappear and be replaced with the word OFFLINE - possible values are 'Online' or 'Offline' - modify */
define("OFFERNEWSLETTER", "yes"); /* modify - will you offer visitors newsletter links in various places - "yes" or "no" */
define("OFFERSURVEY", "yes"); /* modify - will you offer visitors your survey in various places - "yes" or "no" */
	define("SURVEYNAME", "short" ); /* choose either a "short" or "user" detailed survey - possible values are either "short" or "user" - modify */
	define("MINIMUMSURVEYANSWERS", "4"); /* if the optional "user" survey is shown and completed, this is the minimum number of completed questions required before the answers are saved to the database, example numericr values: '1',"2","3","4","5","6" or "7" - modify */
define("HITSTHISPRODUCT", "on"); /* modify - 'on' or 'off' - the hit counter page views per product as displayed in the 'Hits This Product' box shown on Showcase and other pages. A value of 'off' will cause the box to NOT show - modify */
define("POPUPTIMEOUT", "7000"); /* modify - the time in milliseconds before your administration adminresult.php popup window will close automatically Example: "10000" milliseconds = 10 seconds */
define("MAX_FILE_SIZE", "52428800"); /* modify - the maximum allowed size in bytes for any downloadable file Example: "30000" which is 30 Kilobytes Example: "5000000" which is about 5 megabytes */
define("FEATURERESOURCE", "1"); /* the unique database product number for your feature resource. Find this number at the bottom of the resource showcase page */
/* Cosmetic - affects appearance only */
define("IMPLEMENTATIONNAME", "your Checkout"); /* the name you call your implementation, used in html page titles, table headings and email - modify */
define("SLOGAN", "Place your slogan here"); /* slogan appears on every footer.php - modify */
define("BENEFIT", "Place your benefit statement here" ); /* modify - the benefit of using your phpcheckout, shown on header - modify */
define("CURRENCYSYMBOL", "$"); /* modify - the character used to represent a unit of currency, example: $ */
/* Who you are - mostly used in email invoice text and payment instructions */
define("LEGALNAME", "Your Legal Name"); /* legal name for payment To: */
define("DOINGBUSINESSAS", "DBA"); /* the name you actually do business as - modify */
define("EXECNAME", "Firstname Lastname" ); /* your top executive, where does the buck stop? - modify */
define("EXECTITLE", "Owner" ); /*  - executive title - modify */
define("ADDRESS", "123 address, City, State, Country 12345" ); /* snail mail address - modify */
define("PHONE", "1.555.555.5555" ); /*  your sales phone number - modify */
define("FAX", "1.555.555.5555" ); /*  your fax number - modify */
/* Web enabled version checking */
define("PRODUCTNAME", "phpcheckout"); /* DO NOT CHANGE. */
define("INSTALLVERSION", "1.0"); /* DO NOT CHANGE. */
/* DO NOT CHANGE BELOW THIS LINE */
/* DO NOT CHANGE BELOW THIS LINE */
/* DO NOT CHANGE BELOW THIS LINE */
function buildProductList($dataset) {
// Build a select list of ITEMS
switch( $dataset) {
	case "all":
		$query = "SELECT productnumber, productname, availability, baseprice, resource FROM " . TABLEITEMS . " ORDER BY lastupdate DESC";
		break;
	case "Online":
		$query = "SELECT productnumber, productname, availability, baseprice, resource FROM " . TABLEITEMS . " WHERE status = 'Online' ORDER BY productname ASC";
		break;
	default:
		echo"No dataset in buildProductList().";
		exit;
}
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
	  	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
		$rowCount = mysql_num_rows ($queryResultHandle); 
		if ( $rowCount > 0 ) { // if there are rows then process them
			echo"<select name=\"productnumber\">\n"; 
    		while ($row = mysql_fetch_array($queryResultHandle)){
        		$rowValue = $row["productnumber"]; 
    	    	$rowVisible = $row["productname"];
				$availability = $row["availability"];
				$baseprice = $row["baseprice"];
				$resource = $row["resource"];
				// show the price if item is for sale
				if($availability=="Retail"){$availability = $availability . " - $" . $baseprice;}
     	    		print("<option value=\"$rowValue\">$rowVisible - $resource - $availability</option>\n"); 
	    		} 
		    	print("</select>\n");
			}else{
				echo"<p>No products were found.</p>\n";
			}
		}else{ // select
   			echo mysql_error();
		} // select
	}else{ //pconnect
		echo mysql_error();
	} //pconnect	
return $dataset;		
} // this completes the building of the dynamic select list
?>