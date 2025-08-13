<html>
<head>
<title>Install phpYellow Pages - Dreamriver.com - Fine Internet Applications Since 1995</title>
<?php require_once("util.php");?>
</head>

<body bgcolor="navy" text="white">


<blockquote>

<table border=10 width=600 align="center" bgcolor="gray" cellpadding=0 cellspacing=0><tr>
<td bgcolor="yellow" align="center"><font color="blue">50%</font></td>
<td bgcolor="black" width=300>&nbsp;</td>
</tr></table>



<h1>Install - Test Database</h1>


<p>
This step automatically tests to see if a connection to your database can be established.
<br>This step also automatically tries to select your specific database.
<br>If all is well you will see &quot;Connection Successful&quot; and &quot;Database Selected&quot; reported below:
</p>



<table bgcolor="ivory" cellpadding=5 width="60%" border=1 bordercolor="midnightblue">
<tr>
<td>


<?php 
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	echo("<h2><font color=\"green\">Connection Successful</font></h2>");	
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		echo"<h2><font color=\"green\">Database Selected</font></h2>";
	}else{ // select
		echo"<p><font color=\"red\"><b>Failed to select database name.</b></p>";
		$installFailure = "true";
		echo mysql_error();
	}
}else{ //pconnect
	echo"<p><font color=\"red\"><b>Failed to establish a database connection.</b></p>";
	$installFailure = "true";
	echo mysql_error();
}
?>
</td></tr>



<?php 
if(!isset($installFailure)){$installFailure = NULL;}
if($installFailure == "true"): // only display trouble shooting if there was actually trouble ?>
<tr bgcolor="black">
<td>
<h3><font color="red">Trouble Shooting?</font></h3>
<font color="ivory">
<ul>
<li>if no connection, then possible problems are that no database server is running, or perhaps there is an incorrect util.php /* Database Connectivity */ value
<li>if no select try opening util.php and verify the correct database name
<li>User configuration problems frequently show up here. They can normally be fixed by opening util.php and carefully checking 
the /* Database Connectivity */ values. Click here to <a href="installConfigure.html" target="_blank">review the previous installation page</a> for this.
</ul>
</font>
</td></tr>
<?php endif;?>


</table>
		



<div style="color:yellow;font-weight:bold;">
<ul>
<li>to proceed click &quot;Next Step&quot;</li>
<li>to stop click &quot;Cancel&quot;</li>
</ul>
</div>



<form name="installForm">
<input type="button" name="nextStepButton" value="Next Step" onClick='location.href="installCreateTables.php"'>
<input type="button" name="cancel" value="Cancel" onClick='location.href="installCancel.html"'>
</form>


</blockquote>


</body>
</html>


	