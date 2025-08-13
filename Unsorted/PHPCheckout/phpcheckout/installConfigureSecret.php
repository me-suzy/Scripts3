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
		<td align="center" width="40%" style="color:white;background-color:darkseagreen;">40%</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- end of shows % installation completed -->




<form name="anyForm" method="post" action="installTestDBConnect.php">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white" colspan=2>
		<h4><span style="color:red;font-weight:bold;">MANUALLY</span> Configure Secret Database Data</h4>
		<p>
		The Install Helper needs you to <span style="color:red;font-weight:bold;">MANUALLY</span> configure secret database data. 
		Do the following:
		</p>

		<ol>
			<li>find <b>configure-dist-PRO.php</b> OR <b>configure-dist-FREE.php</b> in the /phpcheckout folder
			<li>open it in a non invasive html editor (one that doesn't change the code without you knowing) with wordwrap turned OFF
			<li>find the section called <b>/* Database connectivity */</b>
			<li>change the CONSTANT lowercase <span style="color:green;font-weight:bold;">values</span> to the values for connecting to your mySQL database:
				<ul>
					<li>define(&quot;DBNAME&quot;, &quot;<span style="color:green;font-weight:bold;">yourdatabasename</span>&quot; );
					<li>define(&quot;DBPASSWORD&quot;, &quot;<span style="color:green;font-weight:bold;">yourDBPassword</span>&quot; );
					<li>define(&quot;DBUSERNAME&quot;, &quot;<span style="color:green;font-weight:bold;">yourDBUsername</span>&quot; );
					<li>define(&quot;DBSERVERHOST&quot;, &quot;<span style="color:green;font-weight:bold;">localhost</span>&quot; );
				</ul>
			<li>review all other CONSTANT <span style="color:green;font-weight:bold;">values</span>. Modify where desired.
			<li>change the INSTALLPATH to your actual web URL and add a trailing forward slash &quot; / &quot;:
				<ul>
					<li>define(&quot;INSTALLPATH&quot;, &quot;<span style="color:green;font-weight:bold;">http://www.yourdomain.com/phpcheckout/</span>&quot;);
				</ul>
			<li>save the modified file as <b>configure.php</b> on your server in the /phpcheckout folder
		</ol>

		<p>
		When complete, please click on the &quot;Next&quot; button.
		</p>
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

<P><BR></P>

</body>
</html>