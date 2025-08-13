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
<P></P>
<P align=left><FONT face=Verdana>This software requires 
<STRONG>PHP 4.0.*</STRONG> or higher &amp;&nbsp;<STRONG>MySQL 3.23.*</STRONG> or 
higher.&nbsp; The software may work on older versions of this required software, 
however, such support has not been tested.</FONT></P>
<P align=left><FONT face=Verdana>This software is distributed under the Shedd 
Technologies International End-User License Agreement, which is reproduced 
below.&nbsp; By installing and/or using this software you agree to the terms of 
this license.</FONT></P>
<P align=left><FONT face=Verdana>Thank you choosing a Shedd Technologies 
International product.</FONT></P>
<P align=left><EM><FONT face=Verdana>The Sheddtech Team</FONT></EM></P>
<HR width="90%" color=black SIZE=1>
<pre><?php require("License.txt"); ?></pre>
<HR width="90%" color=black SIZE=1>
<br>
<table align="center" cellspacing="0" cellpadding="2" border="1" bordercolor="Black">
<tr>
    <td bgcolor="#eaeaea"><?php
		$fd=fopen("http://scripts.sheddtech.com/vtrack/index.php?product=1&version=$ssm_sys_version",'r');
		$contents=fread($fd,10000000);
		fclose($fd);
		print $contents;
	?>
</td></tr></table>
<br>
<HR width="90%" color=black SIZE=1>
<form action="setup_2.php" method="post">
<input type="hidden" name="stage" value="2">
<center><input type="submit" value="Begin"></center>
</form>
</body>
</html>
