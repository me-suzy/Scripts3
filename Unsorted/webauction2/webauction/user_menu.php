<SCRIPT Language=PHP>
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/


	// Include messages file	
   require('includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('includes/config.inc.php');
   
   

	
</SCRIPT>

<HTML>
<HEAD>
<TITLE></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#000000" VLINK="#000000" ALINK="#000000">

<SCRIPT Language=PHP>

require("header.php");


print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"4\">";
print "<BR><CENTER><B>$MSG_205</B><BR><BR>";

print "<TABLE BORDER=0 CELLPADDING=3 WIDTH=300>";
print "<TR>";
	print "<TD>";
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
		print "<IMG SRC=\"./images/punto.gif\" BORDER=\"0\"> ";
		print "<A HREF=\"./user_data.php?user=$user\">$MSG_202</A>";
	print "</TD>";
print "</TR>";

print "<TR>";
	print "<TD>";
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
		print "<IMG SRC=\"./images/punto.gif\" BORDER=\"0\"> ";
		print "<A HREF=\"./user_data.php?user=$user\">$MSG_203</A>";
	print "</TD>";
print "</TR>";

print "<TR>";
	print "<TD>";
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
		print "<IMG SRC=\"./images/punto.gif\" BORDER=\"0\"> ";
		print "<A HREF=\"./user_data.php?user=$user\">$MSG_204</A>";
	print "</TD>";
print "</TR>";

print "</TABLE>";
print "</CENTER>";


</SCRIPT>

<? require("./footer.php"); ?>
</BODY>
</HTML>
