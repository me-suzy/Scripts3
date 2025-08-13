<?#//v.1.0.1
session_start();
?>
<html>
<head>
<title>Phpauction Pro Plus Upgrade Script</title>
</head>
<body BGCOLOR=brown1 TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<TABLE ALIGN=CENTER CELLPADDING=1 CELLSPACING=0 BORDER=0 BGCOLOR=white WIDTH=700>
  <TR>
	   <TD>
		    <TABLE WIDTH=100% CELLPADDING=4 CELLSPACING=0 BORDER=0 BGCOLOR=white>
		      <TR>
			       <TD>
			         <IMG SRC="images/logo.gif">
			         &nbsp;&nbsp;
			         <H1>Phpauction Pro Plus Upgrade Script</H1>
			         <BR>
			         <BR>
			         <FONT FACE="Helvetica" SIZE="3">
			         The present upgrade script will upgrade your Phpauction installation from version 2.1 to Pro Plus 1.0.
			         <BR><BR>
			         Before using the upgrade script, unpack the Phpauction Pro Plus 1.0 distribution file you downloaded in
			         a directory on your server under the www root (NOTE: do not overwrite you current Phpauction 2.1
			         installaion).
			         <BR>
			         <BR>
			         Be sure to give <FONT FACE="Courier">read</FONT> permissions to the Phpauction Pro directory and <FONT FACE="Courier">write</FONT>
			         permissions to the following files:
			         <UL>
			         <LI><FONT FACE="Courier">includes/passwd.inc.php</FONT>
			         <LI><FONT FACE="Courier">includes/config.inc.php</FONT>
			         </UL>
			         <BR>
			         <B>Note:</B> The upgrade script will populate the new database with the content of your Phpauction 2.1 installation.
			         <BR>However, the script will not upgrade your PhpAdsNew tables.
			         <BR><BR>
			         If you don't have Phpauction 2.1 installed on your server, use the <A HREF="install.php">installation script</A>.
			  
			         <FORM ACTION="upgrade21_proplus10_1.php">
			         <center>
			          <BR><BR>
			          <INPUT TYPE=submit VALUE="Start Upgrade Script >>"><BR>
			          <BR>
			          Copyright &copy; 2002, Phpauction.org
			         </center>
			         </FORM>
			       </TD>
		      </TR>
		    </TABLE>
	   </TD>
  </TR>
</TABLE>
</body>
</html>
