<?php require_once("adminOnly.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<script language="Javascript" src="yellow.js"></script>
		<script language="Javascript">loadCSSFile();</script>
   <script language="JavaScript">
<!--
/*
This function launches a temporary popup window which displays the administrative results. 
The window is a reporting tool. The messages it shows are temporary. Therefore, the popup 
has a timeout which you may reset in util.php to force the popup to close. No point in having 
all these windows open. Choose to close them all after POPUPTIMEOUT milliseconds in util.php
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


    <?php echo "<TITLE>Administration - " . YOURPHPYELLOWNAME . " - " . COMPANY . " - A Dreamriver.com Software Product - Easily create classified ads or Yellow Page directories! phpYellow Pages from Dreamriver.com - php software mysql database software download apps</TITLE>"; ?>

	<META NAME="keywords" CONTENT="phpYellow, php, Yellow, Search, for, add, change, or, delete, your, product, or, service, listing(s), on, our, Yellow, Pages, It's, fast, free.">
	<META NAME="description" CONTENT="This Administration module lets you easily add, edit, or remove listings with point and click controls. The many advanced features in the Premium version increase your capability to create a successful website.">
	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
	<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">
	<meta name="robots" content="noindex,nofollow">



</HEAD>


	<BODY>


<?php $pageLoaded = "true";?>

<?php include("header.php");?> <br>



<!-- Start message insert here -->

<!-- end message insert here -->



<br><a href="#photoTop">Photo Top</a>

<?php echo "<h1>" . YOURPHPYELLOWNAME . " Administration" . "</h1>";?>


<table>
<tr><td colspan=4 align="center">
<a name="photoTop">
<span style="color:white;">&nbsp;</span>
</a>
<img src="appimage/phpYellowAbstract.jpg" width="502" height="112" border=0 alt="Digital image from Vancouver Island, British Columbia, Canada, looking south across the Strait of Juan de Fuca towards the Olympic Mountains with Cape Flattery to the right and west in Washington State, U.S.A."><br>
</td></tr>


<tr><th>Main</th><th>Misc.</th><th>Setup</th><th>Support: Version <?php print(INSTALLVERSION);?></th></tr>

<tr>
<td valign='top'>
<a href="#manage">Manage-Listings</a><br>
<a href="#instantdestroy">Instant Destroy</a><br>
<?php if(defined("PAIDLISTINGS")){echo"<a href=\"#setckey\">Set CKEY parameters</a><br>";}?>
<a href="#manageQueue">Manage Queued Listings</a><br>
<a href="#findrecordlike">Find Record Like</a><br>
<a href="password.php" target="_blank">Password Look Up</a><br>
<a href="#easysql">EasySQL</a><br>
<a href="index.php" target="_blank">Home</a><br>
<?php if(defined("SETRANK")):?>
<a href="#backup">Back Up Data</a><br>
 <a href="#exportEmail">Export Email List</a><br>
<?php endif;?>
</td>

<td valign='top'>
<?php if (!defined("SETRANK")):?>
<a href="http://www.dreamriver.com/fatpipe/startBuy.php" target="_blank">Buy the Premium Version</a><br>
<?php endif;?>
<a href="http://www.dreamriver.com/phpYellow/" target="_blank">Sample Premium Website</a><br>
<a href="dev-mess.php" target="_blank">Developer's Message</a><br>
</td>

<td valign='top'>
<a href="docs/DOCSindex.html" target="_blank"><b>Developer's Guide</b></a>
<ul>
<li><a href="docs/DOCSreadme.html" target="_blank">README</a></li>
<li><a href="docs/DOCSinstallNotes.html" target="_blank">Install Notes</a></li>
<li><a href="docs/DOCSfaq.html" target="_blank">FAQ</a></li>
<li><a href="docs/DOCSsecurity.html" target="_blank">Security</a></li>
<li><a href="docs/DOCSlicense.html" target="_blank">License</a></li>
<li><a href="docs/DOCScustomize.html" target="_blank">Customize</a></li>
<li><a href="docs/DOCSpaidListings.html" target="_blank">Paid Listings</a></li>
</ul>
<a href="phpinfo.php?formuser=<?php echo ADMINUSER;?>&formpassword=<?php echo ADMINPASSWORD;?>" target="_blank">phpinfo()</a><br>
<a href="http://www.dreamriver.com/resources/submit.php" target="_blank">Submit your URL</a>
</td>

<td valign='top'>
<A HREF="http://www.dreamriver.com/software/checkversion.php?installversion=<?php print( INSTALLVERSION . "&productname=" . PRODUCTNAME );?>" target="_blank">Check Version?</A><br>
<a href="http://www.dreamriver.com/phpYellow/docs/DOCSchangeLog.html" target="_blank">Review the ChangeLog</a><br>
<a href="mailto:info@dreamriver.com" target="_blank">Get answers from DreamRiver</a><br>
<A HREF="http://www.dreamriver.com/fatpipe/index.php" target="_blank">Go to Software Archive</A><br>
</td></tr></table>


<?php if(!defined("SETRANK")):?>
<p><br></p>

<h2>Edition Differences</h2>

<form>
	<input type="button" value="Click here to see a chart comparing the free and Premium editions" onclick='location.href="http://www.dreamriver.com/fatpipe/showcase.php?productnumber=2"'>
</form>
<p><br></p>


<form>
	<input type="button" value="Click here to buy the Premium edition" onclick='location.href="http://www.dreamriver.com/fatpipe/startBuy.php"'>
</form>
<?php endif;?>



<p><br></p>


<h2><a name="manage">Manage Listings</a></h2>
<p>
Browse, update, or delete any Yellow Page listing. 
The newest listings are shown first. Delete is permanent. 
</p>
<?php 
$ycategory = "*";
include("manageForm.php");?>



<p><br></p>

<h2><a name="instantdestroy">Instant Destroy</a></h2>
<h3>Destroy a Category and Contact Listing and all other related Category Listing(s)</h3>

<form name="instantdestroyForm" method="post" action="adminresult.php" onsubmit="popup()" target="popup">
<input type="hidden" name="goal" value="Instant Destroy">
<input type="hidden" name="formuser" value="<?php echo ADMINUSER;?>">
<input type="hidden" name="formpassword" value="<?php echo ADMINPASSWORD;?>">
CKEY to destroy <input type="text" name="ckey">
	<input style="color:white;background-color:red;" type="submit" value="Instant Destroy">
</form>


<p><br></p>




<?php if(defined("PAIDLISTINGS")){include("premiumSetCKEY.php");}?>


<p><br></p>



<table class="form" width="50%" >
<tr>
	<td>
		<?php include("adminViewQueueForm.php");?>	
	</td>
</tr>
</table>



<p><br></p>



<?php include("findRecordLikeForm.php");?>



<?php if (defined("SETRANK")):?>



<p class="x-small">
<i>Note: Export Elist, Backup Data and Run EasySQL are very convenient features to 
have here - customers have asked for them. These features are intended 
for lightweight usage. For heavier needs use a standalone, dedicated solution from a reliable 
third party software provider. Example: for Back Up Data use <a href="http://www.phpwizard.net">
phpMyAdmin from phpwizard.net</a></i>
</p>


<h2><a name="exportEmail">Export Email List</a></h2>
<p>
Export the email addresses from your database to a text file. If the exported 
file is relatively small, you may copy it and paste it into the BCC 
(blind carbon copy) field of an email note. 
By doing so you may reach your listers with messages about the expiration 
of their listing, or any other subject. 
</p>
<p>


<h3>The Process of Sending Email to your Listers</h3>
<ol>
	<li>remove invalid email addresses first with the Manage-Listings tool, or EasySQL</li>
	<li>create an email message</li>
	<li>send a test of this email message to yourself and make corrections as needed</li>
	<li>export the list you want to use using the button below</li>
	<li>copy the list, paste it into the BCC field of your email note and send</li>
	<li>for your own security remove your newly created email list text file from the /backup folder</li>
</ol>

<p>
<form name="goElist" method="post" action="premiumExportEmailList.php">
<input type="hidden" name="formuser" value="<?php echo ADMINUSER;?>">
<input type="hidden" name="formpassword" value="<?php echo ADMINPASSWORD;?>">
<input class="input" type="submit" name="submit" value="Export Email List">
</form>
</p>





<p><br></p>

<h2><a name="backup">Backup Data for Contact &amp; Category Database Tables</a></h2>
<p>
Use this tool to backup your data. Save the resulting file in a 
secure, physically different location than your database server. 
For example, save to your local computer.
</p>



<form name="goBackup" method="post" action="premiumBackup.php">
<input type="hidden" name="formuser" value="<?php echo ADMINUSER;?>">
<input type="hidden" name="formpassword" value="<?php echo ADMINPASSWORD;?>">
<input class="input" type="submit" name="submit" value="Backup Data">
</form>


<?php endif;?>



<p><br></p>




<h2><a name="easysql">Run EasySQL Database Command Line Tool</a></h2>
<p>
Use this tool to send Structured Query Language (SQL) commands to 
your mySQL database. WARNING! Drop and delete commands are irreversible. If in doubt, 
don't do it, check the mySQL manual, and back up your data first.
</p>




<form name="goeasysql" method="post" action="easysql.php">
<input type="hidden" name="formuser" value="<?php echo ADMINUSER;?>">
<input type="hidden" name="formpassword" value="<?php echo ADMINPASSWORD;?>">
<input class="input" type="submit" name="submit" value="Run EasySQL">
</form>


<p><br></p>



<h2>Rate phpYellow Pages on Hotscripts.com</h2>
<!------- Start of HTML Code ------->
<form action="http://www.hotscripts.com/cgi-bin/rate.cgi" method="POST" target="_blank">
<input type="hidden" name="ID" value="7536">
<table BORDER="0" CELLSPACING="0" bgcolor="#E2E2E2">
<tr><td align="center">
<font face="arial, verdana" size="2">
<b>Rate phpYellow Pages</b><br><a href="http://www.hotscripts.com" target="_blank">
<small>@ HotScripts.com</small></a></font>
</td></tr>

<tr><td align="center"><table border="0" cellspacing="0" width="100%" bgcolor="#FFFFEA">

<tr><td><input type="radio" value="5" name="ex_rate"></td>
<td><font face="arial, verdana" size="2">Excellent!</font></td></tr>
<tr><td><input type="radio" value="4" name="ex_rate"></td><td><font face="arial, verdana" size="2">Very Good</font></td></tr><tr><td><input type="radio" value="3" name="ex_rate"></td><td><font face="arial, verdana" size="2">Good</font></td></tr><tr><td><input type="radio" value="2" name="ex_rate"></td><td><font face="arial, verdana" size="2">Fair</font>
</td></tr><tr><td>
<input type="radio" value="1" name="ex_rate">
</td><td><font face="arial, verdana" size="2">Poor</font>
</td></tr></table>
</td></tr><tr><td align="center">
<input class="input" type="submit" value="Cast My Vote!">
</td></tr></table>
</form>
<!------- End of HTML Code ------->
</p>



<BR><a href="#photoTop">Photo Top</a>

 

<?php include("footer.php")?>