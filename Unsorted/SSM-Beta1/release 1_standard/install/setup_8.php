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
//Stage 6: Provide basic usage information, support & documentation info
if($stage=="6"){
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
	<P align=left><FONT face=Verdana>Support 
Services Manager has now been installed on your system.&nbsp; We hope that you 
enjoy using our product.&nbsp; Should you experience any problems during the use 
of our product, please visit <A 
href="http://scripts.sheddtech.com/">http://scripts.sheddtech.com/</A>    to obtain 
	technical support.</FONT></P>
<P align=left><FONT face=Verdana>Should you find our product useful, we would 
appreciate you posting comments in our online forums and rating our&nbsp;product 
at <A 
href="http://scripts.sheddtech.com/">http://scripts.sheddtech.com/</A>.</FONT></P>
<P align=left><FONT face=Verdana>Thank you choosing a Shedd Technologies 
International product.&nbsp; Enjoy!</FONT></P>
	<P align=left><EM><FONT face=Verdana>The Sheddtech Team</FONT></EM>
	<br>
	<br>
	<HR width="90%" color=black SIZE=1></P>
	<form action="../index.php" method="post">
	<center><input type="submit" value="Start Using SSM"></center>
	</form>
	</body>
	</html>
	<?php
}//end Stage 6
?>
