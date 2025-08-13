<?#//v.1.0.0
	include "loggedin.inc.php";


#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

   	require('../includes/messages.inc.php');
   	require('../includes/config.inc.php');
			
	require("./header.php");
	require('../includes/styles.inc.php'); 


?>
  
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>
			<CENTER>
				<BR>
				<FORM NAME=conf ACTION=checkupdates.php METHOD=POST>
					<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB">
						<TR> 
							<TD ALIGN=CENTER><FONT color=#ffffff FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
								<? print $MSG_655; ?>
								</B></FONT><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4" COLOR=#FFFFFF><B> 
								</B></FONT></TD>
						</TR>
						<TR> 
							<TD>
								<TABLE WIDTH=100% CELLPADDING=2 ALIGN="CENTER" BGCOLOR="#FFFFFF">
									<TR VALIGN="TOP"> 
										<TD COLSPAN=3><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_656;?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD COLSPAN="2" HEIGHT="7"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
									</TR>
									<TR> 
										<TD WIDTH="459" COLSPAN=2 ALIGN=CENTER> 
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_657; ?>">
										</TD>
									</TR>
									<TR> 
										<TD WIDTH=173></TD>
										<TD WIDTH="459"> </TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
					</FORM>
	<BR><BR>

	<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
	<A HREF="admin.php" CLASS="links">Admin Home</A>
	</FONT>
	</CENTER>
	<BR><BR>

</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>

<? require("./footer.php"); ?>
</BODY>
</HTML>
