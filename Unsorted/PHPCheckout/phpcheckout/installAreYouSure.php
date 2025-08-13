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


<form name="anyForm" method="post" action="installFinish.php">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Are You Sure?</h4>
		<p>
		Removing this software will:
		<ol>
			<li>delete all files in the current folder 
			<li>delete all files and folders created for this application
			<li>destroy all database tables created by this application
			<li>destroy all data contained within the database tables
		</ol>

	
	</td>
	<td bgcolor="ivory" align="center">
		<img src="wf230x160B.jpg" width="230" height="160" border=0>
	</td>
</tr>
<tr>
	<td align="right" colspan=2>
		<hr>
		<input type="button" name="back" value="Back" onclick="history.back(1)">
		<?php if($adminUser):?>
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