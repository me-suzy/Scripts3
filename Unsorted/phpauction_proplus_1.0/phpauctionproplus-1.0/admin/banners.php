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

	#//
	if($HTTP_POST_VARS[action] == "update")
	{
		#// Update database
		$query = "update PHPAUCTIONPROPLUS_settings set banners='".addslashes($HTTP_POST_VARS[banners])."'";
		$res = @mysql_query($query);
		if(!$res)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		else
		{
			$ERR = $MSG_600;
			$SETTINGS = $HTTP_POST_VARS;
		}
	}
	else
	{
		#//
		$query = "SELECT * FROM PHPAUCTIONPROPLUS_settings";
		$res = @mysql_query($query);
		if(!$res)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		elseif(mysql_num_rows($res) > 0)
		{
			$SETTINGS = mysql_fetch_array($res);
		}
	}
	
			
	require("./header.php");
	require('../includes/styles.inc.php'); 


?>
  
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>
<CENTER>
				<BR>
				<FORM NAME=conf ACTION=<?=basename($PHP_SELF)?> METHOD=POST>
					<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB">
						<TR> 
							<TD ALIGN=CENTER><FONT COLOR=#FFFFFF FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
								<? print $MSG_599; ?>
								</B></FONT></TD>
						</TR>
						<TR> 
							<TD> 
								<TABLE WIDTH=100% CELLPADDING=2 ALIGN="CENTER" BGCOLOR="#FFFFFF">
									<TR> 
										<TD COLSPAN="2" ALIGN=CENTER><B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FF0000"></FONT></B> 
											<B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FF0000"> 
											<?=$ERR?>
											</FONT> </B></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=169>&nbsp;</TD>
										<TD WIDTH="365"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_598; ?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=169 HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">		<? print $MSG_597; ?>
										</FONT></TD>
										<TD WIDTH="365" HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="banners" VALUE="1"
								<? if($SETTINGS[banners] == 1) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
											<? print $MSG_030; ?>
										</FONT> 
											<INPUT TYPE="radio" NAME="banners" VALUE="2"
								<? if($SETTINGS[banners] == 2) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
											<? print $MSG_029; ?>
										</FONT> </FONT></TD>
									</TR>
									<TR> 
										<TD WIDTH=116> 
											<INPUT TYPE="hidden" NAME="action" VALUE="update">
											<INPUT TYPE="hidden" NAME="id" VALUE="<?=$id?>">
										</TD>
										<TD WIDTH="368"> 
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
										</TD>
									</TR>
									<TR> 
										<TD WIDTH=116>&nbsp;</TD>
										<TD WIDTH="368"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<BR>
											<A HREF="../phpAdsNew/admin/"> 
											<? print $MSG_601; ?>
											</A> </FONT></TD>
									</TR>
									<TR> 
										<TD WIDTH=116></TD>
										<TD WIDTH="368"> </TD>
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
<? require("./footer.php"); ?>
</BODY>
</HTML>
