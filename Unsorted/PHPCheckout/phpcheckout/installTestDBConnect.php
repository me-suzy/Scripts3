<?php require_once("configure.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>

		<script language="Javascript" src="appinstaller.js"></script>
		<script language="Javascript">loadCSSFile();</script>
		<title>Install Helper - a DreamRiver Software Product - Easily install DreamRiver Software - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download</title>
	</head> 

<body>
<!-- START of body -->

<h1>DreamRiver Install Helper</h1>

<H2>phpcheckout<sup>TM</sup></H2>




<!-- start of shows % installation completed -->
<table width="60%" border=5 bgcolor="silver">
	<tr>
		<td align="center" width="60%" style="color:white;background-color:darkseagreen;">60%</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- end of shows % installation completed -->




<form name="anyForm" method="post" action="installTables.php">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Test Database Connection and Selection</h4>
		<p>
		The Install Helper has already checked the connection to your database and 
		tried to select your database. This is what was found:
</p>

<?php 
$failure = "false";
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	echo("<h3>Connection Successful</h3>");	
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		echo"<h3>Database Selected</h3>";
	}else{ // select
		echo"<p><font color=\"red\"><b>Failed to select database name.</b></font></p>";
		$failure = "true";
		echo mysql_error();
	}
}else{ //pconnect
	echo"<p><font color=\"red\"><b>Failed to establish a database connection.</b></font></p>";
	$failure = "true";
	echo mysql_error();
}
if($failure == "true") {
	echo"<p><b>Open the file called <i>mysetup.php</i> and double check your /* Database connectivity */  <span style=\"color:green;font-weight:bold;\">values</span>.</b></p>";
}else{
	echo"<p>To continue, please click on the &quot;Next&quot; button.</p>";
}
?>
		
	

	</td>
	<td bgcolor="ivory">
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