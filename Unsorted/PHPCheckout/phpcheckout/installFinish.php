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
		<td colspan=2 align="center" width="100%" style="color:white;background-color:darkseagreen;">100%</td>
	</tr>
</table>
<!-- end of shows % installation completed -->



<form name="anyForm" method="post" action="installSecurity.php">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white">
		<h4>Finish - Congratulations!</h4>
		<p>
			The Install Helper has finished. 
		</p>


<ul>
<li>to proceed click &quot;Go to Index Page&quot; or &quot;Go to Administration&quot;</li>
</ul>


	</td>
	<td bgcolor="ivory" align="center">
		<img src="installPlumeris.jpg" width="230" height="160" border=0>
	</td>
</tr>
<tr>
	<td align="right" colspan=2>
		<hr>
		<?php $failure = isset($failure)?$HTTP_POST_VARS['failure']:NULL;
				if($failure != "true"):?>
			<input class="submit" type="button" name="goIndexButton" value="Go to Index Page" onClick='location.href="index.php"'>
			<input class="submit" type="button" name="goAdminButton" value="Go to Administration" onClick='location.href="<?php echo ADMINHOME;?>"'>

		<?php endif;?>
	</td>
</tr>
</table>
</form>


</body>
</html>