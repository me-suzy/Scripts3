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


	
		
		
		
		case "Change": // change the survey
//$_POST["task"] = "Insert this Item";
       	$formToInclude = "surveyChange.php";
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


<h1>Work With Survey</h1>


<table border=0>
<tr>
	<!-- Task Panel -->
	<td width="15%" valign="top" class="favcolor2"><h3>Task Panel</h3>
		<?php include("navSurvey.php");?>
	</td>
	
	<!-- Reporting Area -->
	<td width="600" valign="top"><h3>Reporting Area</h3>
	
		<?php echo $systemMessage;?>

		<?php if(!empty($formToInclude)) {include("$formToInclude");}?>

	</td>
</tr>
</table>