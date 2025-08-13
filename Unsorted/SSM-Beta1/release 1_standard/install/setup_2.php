<?php
//---------------------------------------------------------------------/*
######################################################################
# Support Services Manager											 #
# Copyright 2002 by Shedd Technologies International | sheddtech.com #
# All rights reserved.												 #
######################################################################
# Distribution of this software is strictly prohibited except under  #
# the terms of the STI License Agreement.  Email info@sheddtech.com  #
# for information.  												 #
######################################################################
# Please visit sheddtech.com for technical support.  We ask that you #
# read the enclosed documentation thoroughly before requesting 		 #
# support.															 #
######################################################################*/
//---------------------------------------------------------------------
require("cvar.php");
if($stage=="2"){
	/*
	Gather database information for the db.php file.
	*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Support Services Manager Installation</title>
</head>
<body>
<P align=center><FONT face=Verdana size=4>Support Services Manager&nbsp;<?php print $ver; ?></FONT><br>
<FONT face=Verdana>Installation &amp; Setup</FONT></P>
<P>
<HR width="90%" color=black SIZE=1>
<form action="setup_3.php" method="post">
<table align="center" cellspacing="0" cellpadding="2" border="0">
<tr><FONT face=Verdana>
	Please fill in the below form with your database 
	information (<b>Bold</b> field are required):</FONT></td>
</tr>
<tr>
    <td colspan="2" bgcolor="#b7b7b7"><b>Database Information</b></td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Database Hostname</b>:&nbsp;</P></td>
    <td>&nbsp;<input type="text" name="dbhost" value="localhost" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Database Name</b>:&nbsp;</P></td>
    <td>&nbsp;<input type="text" name="dbselect" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Database Username</b>:&nbsp;</P></td>
    <td>&nbsp;<input type="text" name="dbuser" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Database Password</b>:&nbsp;</P></td>
    <td>&nbsp;<input type="text" name="dbpass" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Table Prefix:&nbsp;</P></td>
    <td>&nbsp;<input type="text" name="table_pre" size="25"></td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>
<tr>
    <td colspan="2" bgcolor="#b7b7b7">
      <P align=center><input type="submit" value="Continue"></P></td>
</tr>
</table>
<p>Please note that in the installation process, a table called `profiles` will be created.  This table DOES NOT use a prefix due to software compatibility issue.  Please make sure that the database that you choose does not contain a table named `profiles` or tables with the same table prefix as you chose to aviod software conflicts.</p>
<HR width="90%" color=black SIZE=1>
<input type="hidden" name="stage" value="2A">
</form>
</body>
</html>
<?php
}//end stage 2
?>
