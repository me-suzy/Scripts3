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
        if(!empty($HTTP_POST_VARS[maxpictures]) && !ereg("^[0-9]+$",$HTTP_POST_VARS[maxpictures]))
        {
            $ERR = $ERR_706;
            $SETTINGS = $HTTP_POST_VARS;
        }
        elseif($HTTP_POST_VARS[maxpicturesize] == 0)
        {
            $ERR = $ERR_707;
            $SETTINGS = $HTTP_POST_VARS;
        }
        elseif(!empty($HTTP_POST_VARS[maxpicturesize]) && !ereg("^[0-9]+$",$HTTP_POST_VARS[maxpicturesize]))
        {
            $ERR = $ERR_708;
            $SETTINGS = $HTTP_POST_VARS;
        }
        else
        {
          #// Update database
          $query = "update PHPAUCTIONPROPLUS_settings set 
                    picturesgallery=$HTTP_POST_VARS[picturesgallery],
                    maxpictures=$HTTP_POST_VARS[maxpictures],
                    maxpicturesize=$HTTP_POST_VARS[maxpicturesize]
                    ";
          $res = @mysql_query($query);
          if(!$res)
          {
              print "Error: $query<BR>".mysql_error();
              exit;
          }
          else
          {
              $ERR = $MSG_394;
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
					<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
						<TR> 
							<TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
								<? print $MSG_663; ?>
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
										<TD WIDTH=134>&nbsp;</TD>
										<TD WIDTH="350"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_664; ?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=134 HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_665; ?>
											</FONT></TD>
										<TD WIDTH="350" HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="picturesgallery" VALUE="1" <?if($SETTINGS[picturesgallery] == "1") print " CHECKED"?>>
											<? print $MSG_030; ?>
											<INPUT TYPE="radio" NAME="picturesgallery" VALUE="2" <?if($SETTINGS[picturesgallery] == "2") print " CHECKED"?>>
											<? print $MSG_029; ?>
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=134 HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_666; ?>
											</FONT></TD>
										<TD WIDTH="350" HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="text" NAME="maxpictures" SIZE="5" VALUE="<?=$SETTINGS[maxpictures];?>">
											</FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=134 HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_671; ?>
											</FONT></TD>
										<TD WIDTH="350" HEIGHT="22"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="text" NAME="maxpicturesize" SIZE="5" VALUE="<?=$SETTINGS[maxpicturesize];?>">
											&nbsp;<? print $MSG_672; ?></FONT></TD>
									</TR>
									<TR VALIGN="TOP"> 
										<TD WIDTH=134 HEIGHT="22">&nbsp;</TD>
										<TD WIDTH="350" HEIGHT="22">&nbsp;</TD>
									</TR>
									<TR> 
										<TD WIDTH=134> 
											<INPUT TYPE="hidden" NAME="action" VALUE="update">
										</TD>
										<TD WIDTH="350"> 
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
										</TD>
									</TR>
									<TR> 
										<TD WIDTH=134></TD>
										<TD WIDTH="350"> </TD>
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
