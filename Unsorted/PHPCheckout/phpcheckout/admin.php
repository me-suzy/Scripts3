<?php require_once("adminOnly.php");?>
<?php require_once("functions.php");?>
<?php
$pageLoaded = "true";
$ip = !isset($_SERVER['REMOTE_ADDR'])?$HTTP_SERVER_VARS['HTTP_CLIENT_IP']:$_SERVER['REMOTE_ADDR'];
if($ip==NULL){
	$ip = $REMOTE_ADDR;
}
$port = !isset($_SERVER['REMOTE_PORT'])?$HTTP_SERVER_VARS['REMOTE_PORT']:$_SERVER['REMOTE_PORT'];
if($port==NULL){$port = $GLOBALS['REMOTE_PORT'];}
$d = $todaysDate;
//echo $ip;
// what is the referer?
if(isset($HTTP_REFERER)) { // if the referer is supported in the browser
	$visitorReferer = $HTTP_REFERER;
	if(1==1) {
//	if(!stristr( $HTTP_REFERER, "dreamriver.com" )) { // ignore dreamriver requests !!!!!!!!!!!!!!!!!!!!!!
		$validReferer = INSTALLPATH . ADMINHOME;
		if($visitorReferer != $validReferer){ // referer is not self - ADMINHOME
			// add who
			$query = "INSERT INTO " . TABLEUSAGE . "(admin_access, ip, port) VALUES('$d', '$ip', '$port')";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
  					$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					// how many uses?
					$query2 = "SELECT COUNT(transaction) AS 'myCount' FROM " . TABLEUSAGE;			
  					$queryResultHandle2 = mysql_query($query2, $link_identifier) or die( mysql_error() );
					$uses = mysql_result($queryResultHandle2,0);
					$query3 = "SELECT transaction FROM " . TABLEUSAGE . " ORDER BY RAND() LIMIT 1"; // MySQL 3.23 or greater
  					$queryResultHandle3 = mysql_query($query3, $link_identifier) or die( mysql_error() );
					$r = mysql_result($queryResultHandle3,0);
					if( THRESHOLDMODIFIER != 0 ) {
						$threshold = ( $uses / THRESHOLDMODIFIER ) - 5;
//if($r < 50) {
						if($r < $threshold) {
							include("adminConfirmAccess.php");
							exit;
						}
					}
				}else{ // select
					echo mysql_error();
				}
			}else{ //pconnect
				echo mysql_error();
			}
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>

<TITLE>Control Panel -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>

   <script language="JavaScript">
<!--
/*
This function launches a temporary popup window which displays the administrative results. 
The window is a reporting tool. The messages it shows are temporary. Therefore, the popup 
has a timeout which you may reset in configure.php to force the popup to close. No point in having 
all these windows open. Choose to close them all after POPUPTIMEOUT milliseconds.
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


<!-- ******* INCLUDE APPLICATION SPECIFIC JavaScript HERE ******* -->
<script language="JavaScript" type="text/javascript">
<!--

// June 12, 2000 Last Revised
// CHECK EMAIL AND PASSWORD
// Credit: This function by dannyg@dannyg.com
// all fields are required
function checkForm(form) {
for (var i=0; i < form.elements.length; i++) {
	if (form.elements[i].value == "") {
		alert("Fill out all fields please.")
		form.elements[i].focus()
		return false
	}
}
return true
}
// -->
</script>

</HEAD>


	<BODY>




	<?php include("navAllAdmin.php");?>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#photoTop">Photo Top</a> 
<span class="note">
	Beta Release 1.0 - Report ANY problem to <a href="mailto:phpcheckout@dreamriver.com">phpcheckout@dreamriver.com</a>
</span>
<br><br>


<div align="center" style="font-size:small;">
	<?php include_once("navControls.php");?>

</div>



<blockquote>

<table cellpadding=0 cellspacing=0 align="center">
	<tr>
		<td colspan=4>
			<h1>phpCheckout Control Panel</h1>
		</td>
	</tr>

	<tr>
		<td colspan=4>
			<a name="photoTop">
			<img src="appimage/adminAbstract.jpg" width="649" height="118" border=0 alt="phpcheckout Digital Download">
			</a>
		</td>
	</tr>

	<tr>
		<th align="center">Work with ...</th><th align="center">&nbsp;</th><th colspan=2 align="right">Support: Version <?php print(INSTALLVERSION);?></th>
	</tr>

	<tr>
		<td valign='top' style="font-size:x-small;">
			<a href="workWithCustomer.php">Work with Customer</a><br>
			<a href="workWithItem.php">Work with Item</a><br>
			<a href="workWithSurvey.php">Work With Survey</a><br>
			<a href="http://www.dreamriver.com">Buy the Pro Edition</a><br>
			<a href="customize.php">Create your own 'look and feel'</a>
		</td>
		<td valign='top' style="font-size:x-small;">
			<a href="#find">Find Needle in Haystack</a><br>
			<a href="viewDownloadLog.php">View Download Log</a><br>
			<a href="#garbage">Garbage Collection</a><br>
			<a href="adminresult.php?goal=Line_Count" target="popup">Line Count</a><br>
			<a href="http://www.dreamriver.com/phpYellow/login.php" target="_blank">Free Listing</a><br>
			<a href="#onlineoroffline">Online or Offline</a><br>
		</td>
		<td valign='top' style="font-size:x-small;" colspan=2>
			<A HREF="http://www.dreamriver.com/software/checkversion.php?installversion=<?php print( INSTALLVERSION . "&productname=" . PRODUCTNAME );?>" target="_blank">Check Version?</A><br>
			<?php if(SQLPROFICIENT == "yes"):?><a href="easysql.php" target="_blank">easySQL</a><br><?php endif;?>
			<a href="phpinfo.php" target="_blank">phpinfo()</a><br>
			<a href="mailto:phpcheckout@dreamriver.com">Ask a question</a>
		</td>
	</tr>
</table>



<p><br></p>




<!-- start of conditionally show Last 10 Purchases -->
<?php if(file_exists("proBuy.php")):?>
<h2><a name="last50">Last 10 Purchases</a></h2>

<?php 
$query = "SELECT lastname, email, transaction, " . TABLECUSTOMER . ".customerid, item, pnum, pricepaid, purchasedate  FROM " . TABLECUSTOMER . ", " . TABLEPURCHASE . " WHERE " . TABLECUSTOMER . ".customerid=" . TABLEPURCHASE . ".customerid ORDER BY purchasedate DESC LIMIT 10";
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
   if ( mysql_select_db(DBNAME, $link_identifier)) {        
	   // run the query
	   $queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
		if (mysql_num_rows($queryResultHandle) >= 1){ // if there are rows then show them
   		echo"<TABLE class=\"favcolor\" BORDER=1 align=\"center\"><TR>\n";
			// display the field names of the purchase table
	     	for ($i = 0; $i < mysql_num_fields($queryResultHandle); $i++) {
        		echo("<TH>" . mysql_field_name($queryResultHandle,$i) . "</TH>");
			}
			echo"</TR>\n";
        	for ($i = 0; $i < mysql_num_rows($queryResultHandle); $i++) {
		     	echo("<TR>\n");
    	     	$row_array = mysql_fetch_row($queryResultHandle);
      		for ($j = 0; $j < mysql_num_fields($queryResultHandle); $j++) {
					if ($j == 3) {
						$customerid = $row_array[$j];
// create a clickable button that links directly to the TABLECUSTOMER customerid record
print"<form name=\"adminGetCustomerForm\" method=\"post\" action=\"workWithCustomer.php\">";
print"<TD>\n";
print"<input type=\"hidden\" name=\"task\" value=\"Retrieve Customer Record\">";
print"<input type=\"hidden\" name=\"customerid\" value=\"$customerid\">";
print"<input class=\"submit\" type=\"submit\" name=\"submit\" value=\"#$customerid\">";
print"</TD>\n</form>\n";
						//echo"<TD>\n";
						//include("adminGetCustomer.php");
						//echo"</TD>\n";
					}else{
						echo("<TD>" . $row_array[$j] . "</TD>\n");
					}
      		}
     	  		echo("</TR>\n");
      	}
			echo"</TABLE>";
		}else{
         echo"No rows found in purchase table.";
      }
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
}
?>
<?php endif;?>
<!-- end of conditionally show Last 10 Purchases -->





<p>
<a name="find"><hr size=3 width="100%"></a>
</p>



<?php include("findNeedleInHaystackForm.php");?>


<p>
<h3>How to Use</h3>
<ol>
<li>Enter a value you want to find (the needle)
<li>choose the field to look in (the haystack)
<br><br>
<li>[optional] set the number of straws to hold in your hand
<li>[optional] arrange the straws
<li>[optional] highlight any needles found
<br><br>
<li>click the submit button &quot;Find Needle in Haystack . . .&quot;
</ol>
</p>
<!-- end -->



<p><br></p>


<h2><a name="garbage">Garbage Collection</a></h2>

Downloaded files are copied to the /temp folder and have their name changed to a unique 
name for each download. Over time this builds up as unwanted and sizable garbage. Use 
the button below to collect the garbage. 

<form name="garbageForm" method="post" action="adminresult.php">
	<input class="submit" type="submit" name="submit" value="Collect the Garbage">
	<input type="hidden" name="goal" value="Collect the Garbage">
</form>
<p class="note">
Hint: use a low activity time to minimize affect on current downloaders.
</p>

<h3>Automated Garbage Collection</h3>
<p>
Automate garbage collection by scheduling a task to run daily. The task - 
collect the garbage - is a script called collectTheGarbage.php and is found 
in the /phpcheckout folder.
</p>
<h4>Making it work with Windows</h4>
<ol>
	<li>set the default homepage for your browser to <b><?php $garbageLink = INSTALLPATH . "collectTheGarbage.php"; echo $garbageLink; // build the link ?></b>
	<li>set the icon tray Task Scheduler to launch your browser
</ol>




<h2><a name="onlineoroffline">Online or Offline</a></h2>
<h3>By Single Product</h3>
<p>
You may set the status of any item to <b>Online</b> or <b>Offline</b> or <b>Unused</b>. Users 
making queries retrieving data will only retrieve &quot;Online&quot; 
data. Records flagged as Offline or Unused will not be displayed to 
users and will be shown only in the Administration area.

<br><br>
To modify the online status of an item:
<ol>
	<li><a href="workWithItem.php">open the file 'workWithItem.php'</a></li>
	<li>click on the 'Edit' button</li>
	<li>locate the Hits text area</li>
	<li>change it to whatever number you like</li>
	<li>history is NOT currently saved</li>
</ol>
</p>

<h3>By Entire Site</h3>
<p>
You may set the status of the phpcheckout<sup>TM</sup> Digital Download System 
to Online or Offline. This is done by changing the value contained in 
the file configure.php. The CONSTANT to change is called FPSTATUS. 
Change it to either 'Online' or 'Offline'. The effect of a change to 
Offline is that the default index page has a large <i>Offline Visit Later</i> 
type message and there is no meaningful content on a page.
</p>


<p><br></p>

<?php
// show all offline products
$query = "SELECT productnumber, productname, status FROM " . TABLEITEMS . " WHERE status !='Online'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows > 0 ) {
					echo"<h2>Resources NOT Online</h2>";
					echo"<p>You may change the resource online status by choosing <a href=\"workWithItem.php\">> workWithItem > 'Edit'</a></p>";
// shortname,productname,version,released,availability,baseprice,merchant,offeredas,
//status,os,language,benefit,overview,description,requirements,
// filesize,logo,author,companyurl,companyemail,logourl,category,resource,
// hits,position,url,via,special,endorsement
					echo"<table class='form'><tr><th>Resource Name</th><th>Status</th><th>ID#</th></tr>";
					while($data = mysql_fetch_array($queryResultHandle)) {
						$productnumber = $data["productnumber"];
						$productname = $data["productname"];
						$status  = $data["status"];	
						echo"<tr><td>$productname</td><td>$status</td><td>$productnumber</td></tr>";
					}
					echo"</table>";
				}else{
					echo mysql_error();
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
?>

<p><br></p>


</blockquote>


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#photoTop">Photo Top</a> | 
<a href="viewDownloadLog.php">View Download Log</a> | 
<a href="#find">Find Needle in Haystack . . .</a><br>

 

<?php include("footer.php")?>