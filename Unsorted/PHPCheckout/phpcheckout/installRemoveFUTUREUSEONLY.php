<?php require_once("mysetup.php");?>
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

<H2>phpcheckout<sup>TM</sup</H2>


<form name="anyForm" method="post" action="installAreYouSure.php">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Remove</h4>
		<p>
		To remove this software from your web server:
		</p>
		<ol>
			<li>login 
					<input class="submit" type="button" name="loginButton" value="Login as Administrator" onclick='location.href="adminLogin.php"'>
			<li>return to this page
			<li>refresh this page
			<li>click &quot;Next&quot;
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