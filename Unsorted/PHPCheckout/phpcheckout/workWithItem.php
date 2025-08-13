<?php require_once("adminOnly.php");?>
<?php require_once("functions.php");?>
<?php // initialize or capture
$systemMessage = NULL; // any message will be below this point and NOT posted
$formToInclude = NULL; // any form inclusion will be below this point and NOT posted
$merchant = !isset($_POST["merchant"])? NULL: $_POST["merchant"]; // future use only
$badData = NULL; // initialize flag for problems
//$isCustomerFile = $_FILES["customerFile"];
	//$customerFile = !isset($_FILES["customerFile"])? NULL: $_FILES['customerFile'];
// direct from the itemForm.php
$productnumber = !isset($_POST["productnumber"])? NULL: $_POST["productnumber"];
$task = !isset($_POST["task"])? NULL: $_POST['task'];
$status = !isset($_POST["status"])? "Offline": $_POST["status"];
$productname = !isset($_POST["productname"])? NULL: $_POST["productname"];
$resource = !isset($_POST["resource"])? "none": $_POST["resource"];
$shortname = !isset($_POST["shortname"])? NULL: $_POST["shortname"];
$version = !isset($_POST["version"])? NULL: $_POST["version"];
$released = !isset($_POST["released"])? $todaysDate: $_POST["released"]; // $todaysDate is from configure.php
$availability = !isset($_POST["availability"])? "Retail": $_POST["availability"];
$baseprice = !isset($_POST["baseprice"])? NULL: $_POST["baseprice"];
$license = !isset($_POST["license"])? NULL: $_POST["license"];
$os = !isset($_POST["os"])? NULL: $_POST["os"];
$language = !isset($_POST["language"])? NULL: $_POST["language"];
$benefit = !isset($_POST["benefit"])? NULL: $_POST["benefit"];
$overview = !isset($_POST["overview"])? NULL: $_POST["overview"];
$description = !isset($_POST["description"])? NULL: $_POST["description"];
$filesize = !isset($_POST["filesize"])? NULL: $_POST["filesize"];
$logo = !isset($_POST["logo"])? NULL: $_POST["logo"];
$logourl = !isset($_POST["logourl"])? NULL: $_POST["logourl"];
$author = !isset($_POST["author"])? NULL: $_POST["author"];
$companyurl = !isset($_POST["companyurl"])? NULL: $_POST["companyurl"];
$companyemail = !isset($_POST["companyemail"])? NULL: $_POST["companyemail"];
$category = !isset($_POST["category"])? NULL: $_POST["category"];
$hits = !isset($_POST["hits"])? 0: $_POST["hits"];
$position = !isset($_POST["position"])? NULL: $_POST["position"];
$endorsement = !isset($_POST["endorsement"])? NULL: $_POST["endorsement"];
$via = !isset($_POST["via"])? "HTTP": $_POST["via"];
$special = !isset($_POST["special"])? NULL: $_POST["special"];
$url = !isset($_POST["url"])? NULL: $_POST["url"];
$requirements = !isset($_POST["requirements"])? "Requires ": $_POST["requirements"];
/*
$ = !isset($_POST[""])? NULL: $_POST[""];
*/
?>


<script language="JavaScript">
<!-- hide 
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
 // initialize
if(!empty($task)){
	switch( $task ) {
		case "Reset Hits":
			$formToInclude = "itemHitReset.php";
			break;




		case "Show Hits":
			$formToInclude = "hits.php";
			break;




		case "Delete":
			$systemMessage .= "<h2>Warning! Delete is Permanent!</h2>";
			$systemMessage .= "Delete is not desirable. Deleting records may orphan your other data, for example purchase information. ";
			$systemMessage .= "Instead of deleting, change the status of the item to 'Offline'. <br><br>If you REALLY want to remove the record then open the item in edit mode, and overwrite it using information for a brand new item.";
			$systemMessage .= "To abort this delete just click any button on the Task Panel<br>";
			$formToInclude = "itemDeleteList.php";
			break;





		case "Delete this Item":
			$query = "DELETE FROM " . TABLEITEMS . " WHERE productnumber=$productnumber";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_affected_rows($link_identifier); // get # of rows
					if ( $rows == 1 ) {
						$systemMessage .= "<h2>Item #$productnumber deleted</h2>";
					}elseif($rows == 0 ) {
						$systemMessage .= "<br>No data change resulted";
					}elseif( $rows > 1 ) {
						$systemMessage .= "<br>Problem! $rows rows affected.";
					}
				}else{ // select
					echo mysql_error();
				}
			}else{ //pconnect
				echo mysql_error();
			}		
		break;





		case "Insert this Item": // add an item to the database item table
			/////////////////////////////////////////////////////////////////////////
			/////////////////////    start of itemValidation.php    ////////////////
			/////////////////////////////////////////////////////////////////////////
			include_once("itemValidation.php");
			///////////////////////////////////////////////////////////////////////////////
			/////////////////////     end of itemValidation.php      //////////////////////
			///////////////////////////////////////////////////////////////////////////////
		$query = "INSERT INTO " . TABLEITEMS . " (shortname,productname,version,released,availability,baseprice,merchant,license,status,os,language,benefit,overview,description,requirements,filesize,logo,author,companyurl,companyemail,logourl,category,resource,hits,position,url,via,special,endorsement) VALUES('$shortname','$productname','$version','$released','$availability','$baseprice','$merchant','$license','$status','$os','$language','$benefit','$overview','$description','$requirements','$filesize','$logo','$author','$companyurl','$companyemail','$logourl','$category','$resource','0','$position','$url','$via','$special','$endorsement' )";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier); // get # of rows
				if ( $rows == 1 ) {
					$query = "SELECT LAST_INSERT_ID()";
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$productnumber = mysql_result($queryResultHandle,0);
					$systemMessage .= "<br>New item called &quot;<b>$productname</b>&quot; inserted into the database item table.";
				}elseif($rows == 0 ) {
					$systemMessage .= "<br>No data change.";
					showMessage($systemMessage);
					include("admin.php");
					exit;
				}elseif( $rows > 1 ) {
					$systemMessage .= "<br>$rows rows affected.";
				}
// *****************************************************************************
//         START of FILE UPLOAD for insert and update modules PHP 4.2.0
// *****************************************************************************
				include("fileUpload.php");
// *****************************************************************************
//          END of FILE UPLOAD for insert and update modules PHP 4.2.0
// *****************************************************************************
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}		
		break;




		case "Update this item":
			/*
				Yeah, well all we really want to do here is receive the post from itemForm.php 
		      and stuff it into the database. Easy eh? Hundreds of lines follow...because 
				we need to check the data that was submitted...
			*/
			// addslashes
			// direct from the itemForm.php
			$productnumber = addslashes($productnumber);
			$task = addslashes($task);
			$status = addslashes($status);
			$productname = addslashes($productname);
			$resource = addslashes($resource);
			$shortname = addslashes($shortname);
			$version = addslashes($version);
			$released = addslashes($released);
			$availability = addslashes($availability);
			$baseprice = addslashes($baseprice);
			$license = addslashes($license);
			$os = addslashes($os);
			$language = addslashes($language);
			$benefit = addslashes($benefit);
			$overview = addslashes($overview);
			$description = addslashes($description);
			$filesize = addslashes($filesize);
			$logo = addslashes($logo);
			$logourl = addslashes($logourl);
			$author = addslashes($author);
			$companyurl = addslashes($companyurl);
			$companyemail = addslashes($companyemail);
			$category = addslashes($category);
			$hits = addslashes($hits);
			$position = addslashes($position);
			$endorsement = addslashes($endorsement);
			$via = addslashes($via);
			$special = addslashes($special);
			$url = addslashes($url);
			$requirements = addslashes($requirements);
			// end of addslashes()
			/////////////////////////////////////////////////////////////////////////
			/////////////////////    start of itemValidation.php    ////////////////
			/////////////////////////////////////////////////////////////////////////
			include_once("itemValidation.php");
			///////////////////////////////////////////////////////////////////////////////
			/////////////////////     end of itemValidation.php      //////////////////////
			///////////////////////////////////////////////////////////////////////////////

			/* A WORD ABOUT HOW THE DOWNLOAD WORKS
				The initial (hidden) filename is determined exclusively by file upload. 
				The working filename is the productname expressed as productnumber.extension: 
				Example 23.zip
				This real file resides in /DOWNLOADFOLDER and the name is randomized
				and copied with a temporary name to the /temp temporary user download area.
				By using this method the visitor cannot predetermine the name of the download file.
				So, the database field called 'url' is not needed and not updated.
			*/

			// set the query
			$query = "UPDATE " . TABLEITEMS . " SET productname='$productname', shortname='$shortname', version='$version', released='$released', availability='$availability', baseprice='$baseprice', license='$license', status ='$status ', os='$os',language='$language',benefit='$benefit', overview='$overview', description='$description', requirements='$requirements', filesize='$filesize',logo='$logo',author='$author',companyurl='$companyurl',companyemail='$companyemail',logourl='$logourl',category='$category',resource='$resource', hits='$hits', position='$position',via='$via',special='$special',endorsement='$endorsement' WHERE productnumber='$productnumber' ";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_affected_rows($link_identifier); // get # of rows
					if ( $rows == 1 ) {
						$systemMessage .= "<br>Item '$productname' updated in item table.";
					}elseif($rows == 0 ) {
						$systemMessage .= "<br>No data change resulted from the first query";
					}elseif( $rows > 1 ) {
						$systemMessage .= "<br>$rows rows affected.";
					}
// *****************************************************************************
//         START of FILE UPLOAD for insert and update modules PHP 4.2.0
// *****************************************************************************
				include("fileUpload.php");
// *****************************************************************************
//          END of FILE UPLOAD for insert and update modules PHP 4.2.0
// *****************************************************************************
				}else{ // select
					echo mysql_error();
				}
			}else{ //pconnect
				echo mysql_error();
			}		
			break;





		case "Retrieve Item":
			// we're just grabbing the data from the db. Then we'll put it into a form.
			$query = "SELECT * FROM " . TABLEITEMS . " WHERE productnumber='$productnumber'";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
					// run the query
					$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_num_rows($queryResultHandle); // get # of rows
					if ( $rows == 1 ) {
						// shortname,productname,version,released,availability,baseprice,merchant,offeredas,
						//status,os,language,benefit,overview,description,requirements,
						// filesize,logo,author,companyurl,companyemail,logourl,category,resource,
						// hits,position,url,via,special,endorsement
						$data = mysql_fetch_array($queryResultHandle);
						$_POST['productnumber'] = stripslashes($data["productnumber"]);
						$_POST['shortname'] = stripslashes($data["shortname"]);
						$_POST['productname'] = stripslashes($data["productname"]);
						$_POST['version'] = stripslashes($data["version"]);
						$_POST['released'] = stripslashes($data["released"]);
						$_POST['availability'] = stripslashes($data["availability"]);
						$_POST['baseprice'] = stripslashes($data["baseprice"]);
						$_POST['merchant'] = stripslashes($data["merchant"]);
						$_POST['license'] = stripslashes($data["license"]);
						$_POST['status'] = stripslashes($data["status"]);	
						$_POST['os'] = stripslashes($data["os"]);
						$_POST['language'] = stripslashes($data["language"]);
						$_POST['benefit'] = stripslashes($data["benefit"]);
						$_POST['overview'] = stripslashes($data["overview"]);
						$_POST['description'] = stripslashes($data["description"]);
						$_POST['requirements'] = stripslashes($data["requirements"]);
						$_POST['filesize'] = stripslashes($data["filesize"]);
						$_POST['logo'] = stripslashes($data["logo"]);
						$_POST['author'] = stripslashes($data["author"]);
						$_POST['companyurl'] = stripslashes(urldecode($data["companyurl"]));
						$_POST['companyemail'] = stripslashes($data["companyemail"]);										
						$_POST['logourl'] = stripslashes(urldecode($data["logourl"]));
						$_POST['category'] = stripslashes($data["category"]);					
						$_POST['resource'] = stripslashes($data["resource"]);	
						$_POST['hits'] = stripslashes($data["hits"]);	
						$_POST['position'] = stripslashes($data["position"]);
						$_POST['url'] = stripslashes($data["url"]);
						$_POST['via'] = stripslashes($data["via"]);
						$_POST['special'] = stripslashes($data["special"]);
						$_POST['attachment'] = stripslashes($data["attachment"]);					
						$_POST['endorsement'] = stripslashes($data["endorsement"]);
						$_POST['task'] = "Update this item";
						$formToInclude = "itemForm.php";
					}else{
						echo mysql_error();
					}
				}else{ // select
					echo mysql_error();
				}
			}else{ //pconnect
				echo mysql_error();
			}
			break;





		case "Build an Item List":
			$formToInclude = "itemEditList.php";
			break;
	
		
		
		
		
		case "Add An Item": // offer the blank form template
			$_POST["task"] = "Insert this Item";
       	$formToInclude = "itemForm.php";
			break;
	
		
		
		





		case"Clear":
		default:
	}// end switch $task
}else{ // if(!empty($task))
	//echo"No goal is present this program cannot continue.";
	//exit;
}?>



<!-- this component should be last on the page - the visual results -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Work With Item -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, contact, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="Contact DreamRiver.com about PHPCHECKOUT by visiting this page. Select one of many communication methods including phone, stealth email or snail mail options. Profit from your own digital downloads with PHPCHECKOUT.">
</HEAD> 

<body>
<!-- START of body -->

<?php include("navAllAdmin.php");?>


<h1>Work With Item</h1>


<table border=0>
<tr>
	<!-- Task Panel -->
	<td width="15%" valign="top" class="favcolor2"><h3>Task Panel</h3>
		<?php include("navItem.php");?>
	</td>
	
	<!-- Reporting Area -->
	<td width="600" valign="top"><h3>Reporting Area</h3>
	
		<?php echo $systemMessage;?>

		<?php if(!empty($formToInclude)) {include("$formToInclude");}?>

	</td>
</tr>
</table>