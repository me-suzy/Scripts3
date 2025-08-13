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
   require('../includes/time.inc.php');

	#//
	if($HTTP_POST_VARS[action] == "update")
	{
		if(!is_numeric($HTTP_POST_VARS[archiveafter]))
		{
			$ERR = "Incorect field format: must be numeric";
			$SETTINGS = $HTTP_POST_VARS;
		}
		else
		{
			#// Update database
			$query = "update PHPAUCTIONPROPLUS_settings set 
					  cron=$HTTP_POST_VARS[cron],
					  archiveafter=".intval($HTTP_POST_VARS[archiveafter]);;
			$res = @mysql_query($query);
			if(!$res)
			{
				print "Error: $query<BR>".mysql_error();
				exit;
			}
			else
			{
				$ERR = $MSG_378;
				$SETTINGS = $HTTP_POST_VARS;
			}
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
								<? print $MSG_348; ?>
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
										<TD WIDTH=109>&nbsp;</TD>
										<TD WIDTH="375"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_371; ?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=109 HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_372; ?>
											</FONT></TD>
										<TD WIDTH="375" HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="cron" VALUE="1" <?if($SETTINGS[cron] == "1") print " CHECKED"?>>
											<? print $MSG_373; ?>
											<INPUT TYPE="radio" NAME="cron" VALUE="2" <?if($SETTINGS[cron] == "2") print " CHECKED"?>>
											<? print $MSG_374; ?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=109>&nbsp;</TD>
										<TD WIDTH="375">&nbsp;</TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=109>&nbsp;</TD>
										<TD WIDTH="375"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_375; ?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=109 HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_376; ?>
											</FONT></TD>
										<TD WIDTH="375" HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="text" NAME="archiveafter" SIZE="4" MAXLENGTH="4" VALUE="<?=$SETTINGS[archiveafter]?>">
											<? print $MSG_377; ?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=109 HEIGHT="22">&nbsp;</TD>
										<TD WIDTH="375" HEIGHT="22">&nbsp;</TD>
									</TR>
									<TR> 
										<TD WIDTH=109> 
											<INPUT TYPE="hidden" NAME="action" VALUE="update">
										</TD>
										<TD WIDTH="375"> 
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
										</TD>
									</TR>
									<TR> 
										<TD WIDTH=109></TD>
										<TD WIDTH="375"> </TD>
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
