<?php require_once("configure.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<script language="Javascript" src="appinstaller.js"></script>
		<script language="Javascript">loadCSSFile();</script>
		<title>Install Helper - a DreamRiver Software Product - Easily install DreamRiver Software - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download</title>
		<script language='JavaScript' type='text/javascript'>
		<!--
		function showSecurityPage() {
			var myWindow = open('', 'securityPage');
			myWindow.location.href="installSecurityInfo.php";				
		}
		function showCustomTips() {
			var myWindow2 = open('', 'customPage');
			myWindow2.location.href="customize.php";				
		}
		//-->
		</script>
	</head> 

<body>
<!-- START of body -->

<h1>DreamRiver Install Helper</h1>

<H2>phpcheckout<sup>TM</sup></H2>



<!-- start of shows % installation completed -->
<table width="60%" border=5 bgcolor="silver">
	<tr>
		<td align="center" width="90%" style="color:white;background-color:darkseagreen;">90%</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- end of shows % installation completed -->


<br><br>


<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Security</h4>
		<p>
			
This step provides security information to help you safeguard your installation.  
Click the button below to launch a security reminder page. Perform the security recommendations within 
this page AFTER you have completed your installation.

<form name="securityInfoForm">
<input type="button" name="securityInfoButton" value="Launch a security reminder page" onClick="showSecurityPage()"> 
<br><br><input type="button" name="customInfoButton" value="Launch Tips on How to Customize" onClick="showCustomTips()">
</form>
</p>
		</p>

<?php 
$failure = "false"; // initialize variable
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {

	}else{ // select
		$failure = "true";
		echo mysql_error();
	}
}else{ //pconnect
	$failure = "true";
	echo mysql_error();
}
if($failure == "true") {
	echo"<p><b>A failure has occurred. This install cannot continue.</b></p>";
}else{
	echo"<p>To continue, please click on the &quot;Next&quot; button.</p>";
}
?>
		
	
		<form name="anyForm" method="post" action="installFinish.php">
	</td>
	<td bgcolor="ivory" align="center">
		<img src="installPlumeris.jpg" width="230" height="160" border=0>
	</td>
</tr>
<tr>
	<td align="right" colspan=2>
		<hr>
		<input type="button" name="back" value="Back" onclick="history.back(1)">
		<?php if($failure != "true"):?>
			<input type="submit" name="submit" value="Next" class="submit">
		<?php endif;?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancel" value="Cancel" onclick='location.href="installCancel.php"'>
	</td>
</tr>
</table>
</form>


</body>
</html>