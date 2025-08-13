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
		#// Data check
		if(empty($HTTP_POST_VARS[currency]) ||
		   empty($HTTP_POST_VARS[moneyformat]) ||
		   empty($HTTP_POST_VARS[moneysymbol]))
		{
			$ERR = $ERR_047;
			$SETTINGS = $HTTP_POST_VARS;
		}
		elseif(!empty($HTTP_POST_VARS[moneydecimals]) && !ereg("^[0-9]+$",$HTTP_POST_VARS[moneydecimals]))
		{
			$ERR = $ERR_051;
			$SETTINGS = $HTTP_POST_VARS;
		}
		else
		{
			#// Update database
			$query = "update PHPAUCTIONPROPLUS_settings set currency='".addslashes($HTTP_POST_VARS[currency])."',
								          moneyformat=$HTTP_POST_VARS[moneyformat],
								          moneydecimals=".intval($HTTP_POST_VARS[moneydecimals]).",
								          moneysymbol=$HTTP_POST_VARS[moneysymbol]";
			$res = @mysql_query($query);
			if(!$res)
			{
				print "Error: $query<BR>".mysql_error();
				exit;
			}
			else
			{
				$ERR = $MSG_553;
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
				<FORM NAME=conf ACTION=currency.php METHOD=POST>
					<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB">
						<TR> 
							<TD ALIGN=CENTER><FONT color=#ffffff FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
								<? print $MSG_076; ?>
								</B></FONT><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4" COLOR=#FFFFFF><B> 
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
										<TD WIDTH=173 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_552;?>
											</FONT></TD>
										<TD HEIGHT="31" WIDTH="459"> 
											<INPUT TYPE=text NAME=currency VALUE="<?=$SETTINGS[currency]?>" SIZE=5 MAXLENGTH="10">
										</TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD COLSPAN="2" HEIGHT="7"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=173 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_544;?>
											</FONT></TD>
										<TD HEIGHT="31" WIDTH="459"> 
											<INPUT TYPE="radio" NAME="moneyformat" VALUE="1"
								<? if($SETTINGS[moneyformat] == 1) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_545;?>
											</FONT><BR>
											<INPUT TYPE="radio" NAME="moneyformat" VALUE="2"
								<? if($SETTINGS[moneyformat] == 2) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_546;?>
											</FONT> </TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD COLSPAN="2" HEIGHT="4"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=173 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_548;?>
											</FONT></TD>
										<TD HEIGHT="31" WIDTH="459"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_547;?>
											</FONT><BR>
											<INPUT TYPE=text NAME=moneydecimals VALUE="<?=$SETTINGS[moneydecimals]?>" SIZE=5 MAXLENGTH="10">
										</TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD COLSPAN="2" HEIGHT="6"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=173 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_549;?>
											</FONT></TD>
										<TD HEIGHT="31" WIDTH="459"> 
											<INPUT TYPE="radio" NAME="moneysymbol" VALUE="1"
								<? if($SETTINGS[moneysymbol] == 1) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_550;?>
											</FONT><BR>
											<INPUT TYPE="radio" NAME="moneysymbol" VALUE="2"
								<? if($SETTINGS[moneysymbol] == 2) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_551;?>
											</FONT> </TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD COLSPAN="2" HEIGHT="4"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
									</TR>
									<TR> 
										<TD WIDTH=173> 
											<INPUT TYPE="hidden" NAME="action" VALUE="update">
											<INPUT TYPE="hidden" NAME="id" VALUE="<?=$id?>">
										</TD>
										<TD WIDTH="459"> 
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
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
