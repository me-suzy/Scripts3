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
   require('../includes/status.inc.php');


	#//
	$ERR = "&nbsp;";
	
	if($HTTP_POST_VARS[action] == "insert")
	{
		if(empty($HTTP_POST_VARS[username]) ||
		   empty($HTTP_POST_VARS[password]) ||
		   empty($HTTP_POST_VARS[repeatpassword])
		  )
		{
			$ERR = $ERR_047;
		}
		elseif((!empty($HTTP_POST_VARS[password]) && empty($HTTP_POST_VARS[repeatpassword])) ||
		   (empty($HTTP_POST_VARS[password]) && !empty($HTTP_POST_VARS[repeatpassword])))
		{
			$ERR = $ERR_054;
		}
		elseif($HTTP_POST_VARS[password] != $HTTP_POST_VARS[repeatpassword])
		{
			$ERR = $ERR_006;
		}
		else
		{
			#// Check if "username" already exists in the database
			$query = "select id from PHPAUCTIONPROPLUS_adminusers where username='$HTTP_POST_VARS[username]'";
			$r = @mysql_query($query);
			if(!$r)
			{
				print "Error: $query<BR>".mysql_error();
				exit;
			}
			elseif(mysql_num_rows($r) > 0)
			{
				$ERR = $ERR_055;
			}
			else
			{
				$TODAY = date("Ymd");
				$PASS = md5($MD5_PREFIX.$HTTP_POST_VARS[password]);
				#// Update
				$query = "INSERT INTO PHPAUCTIONPROPLUS_adminusers VALUES (NULL,
												 '".addslashes($HTTP_POST_VARS[username])."',
												 '$PASS',
												 '$TODAY',
												 '0',
												 ".intval($HTTP_POST_VARS[status]).")";
				$res = @mysql_query($query);
				if(!$res)
				{
					print "Error: $query<BR>".mysql_error();
					exit;
				}
				else
				{
					Header("Location: adminusers.php");
					exit;
				}
			}
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
					<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
						<TR> 
							<TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
								<? print $MSG_367; ?>
								</B></FONT></TD>
						</TR>
						<TR> 
							<TD> 
								<TABLE WIDTH=100% CELLPADDING=2 ALIGN="CENTER" BGCOLOR="#FFFFFF">
									<TR> 
										<TD COLSPAN="2" ALIGN=CENTER><B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FF0000"> 
											<? print $ERR; ?>
											</FONT></B></TD>
									</TR>
									<TR> 
										<TD WIDTH="129"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_557; ?>
											</FONT></TD>
										<TD WIDTH="405"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<B> 
											<INPUT TYPE="text" NAME="username" SIZE="25" MAXLENGTH="32" VALUE="<?=$HTTP_POST_VARS[username]?>">
											</B> </FONT></TD>
									</TR>
									<TR> 
										<TD WIDTH="129"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_004; ?>
											</FONT></TD>
										<TD WIDTH="405"> 
											<INPUT TYPE="PASSWORD" NAME="password" SIZE="25">
										</TD>
									</TR>
									<TR> 
										<TD WIDTH="129"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_564; ?>
											</FONT></TD>
										<TD WIDTH="405"> 
											<INPUT TYPE="PASSWORD" NAME="repeatpassword" SIZE="25">
										</TD>
									</TR>
									<TR> 
										<TD WIDTH="129"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_565; ?>
											</FONT></TD>
										<TD WIDTH="405"> 
											<INPUT TYPE="radio" NAME="status" VALUE="1"
								<? if($HTTP_POST_VARS[status] == 1 || empty($HTTP_POST_VARS[status])) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_566; ?>
											</FONT> 
											<INPUT TYPE="radio" NAME="status" VALUE="2"
								<? if($HTTP_POST_VARS[status] == 2) print " CHECKED";?>
								>
											<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_567; ?>
											</FONT> </TD>
									</TR>
									<TR> 
										<TD WIDTH=129>&nbsp;</TD>
										<TD WIDTH="405">&nbsp;</TD>
									</TR>
									<TR> 
										<TD WIDTH=129> 
											<INPUT TYPE="hidden" NAME="action" VALUE="insert">
										</TD>
										<TD WIDTH="405"> 
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_569; ?>">
										</TD>
									</TR>
									<TR> 
										<TD WIDTH=129></TD>
										<TD WIDTH="405"> </TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
					</FORM>
	<BR><BR>
				<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
				<A HREF="admin.php" CLASS="links">Admin Home</A> | <A HREF="adminusers.php"  CLASS="links">Admin 
				users</A></FONT> 
			</CENTER>
	<BR><BR>

</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
<? require("./footer.php"); ?>
</BODY>
</HTML>
