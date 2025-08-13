<?php require_once("configure.php");?>
<?php 
$installorremove = !isset($installorremove)?NULL:$HTTP_POST_VARS['installorremove'];
switch($installorremove){
case "install":
	header("Location:installLicenseAgreement.php");
	break;
case "upgrade":
	header("Location:installUpgrade.php");
	break;
case "remove":
	header("Location:installRemove.php");
	break;
default:
}?>
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


<form name="anyForm" method="post" action="<?php echo $PHP_SELF;?>">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Install, Upgrade or Remove</h4>
		<p>
			Choose one:
		</p>

		<p>
		<input type="radio" name="installorremove" value="install" CHECKED> Install<br>
		<input type="radio" name="installorremove" value="upgrade"> Upgrade<br>
		<input type="radio" name="installorremove" value="remove"> Remove
		</p>
		<p></p>
	</td>
	<td bgcolor="ivory" align="right" width="230">
		<img src="wf230x160B.jpg" width="230" height="160" border=0>
	</td>
</tr>
<tr>
	<td align="right" colspan=2>
		<hr>
		<input type="button" name="back" value="Back" onclick="history.back(1)">
		<input type="submit" name="submit" value="Next" class="submit">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancel" value="Cancel" onclick='location.href="installCancel.php"'>
	</td>
</tr>
</table>
</form>


</body>
</html>