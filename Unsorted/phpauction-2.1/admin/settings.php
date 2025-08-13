<?
	include "loggedin.inc.php";


/*

   Copyright (c), 1999, 2000, 2001 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/

   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');

   require("./header.php"); 
   require('../includes/styles.inc.php'); 


	#//
	$ERR = "&nbsp;";
	
	#// 
	if($HTTP_POST_VARS[action] == "update")
	{
		#// Data check
		if(empty($HTTP_POST_VARS[sitename]) ||
		   empty($HTTP_POST_VARS[siteurl]) ||
		   empty($HTTP_POST_VARS[adminmail]) ||
		   empty($HTTP_POST_VARS[loginbox]) ||
		   empty($HTTP_POST_VARS[newsbox]) ||
		   empty($HTTP_POST_VARS[showacceptancetext]) 
		   )
		{
			$ERR = $ERR_047;
			$SETTINGS = $HTTP_POST_VARS;
		}
		elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$HTTP_POST_VARS[adminmail]))
		{
			$ERR = $ERR_008;
			$SETTINGS = $HTTP_POST_VARS;
		}
		elseif($HTTP_POST_VARS[showacceptancetext] == 1 && empty($HTTP_POST_VARS[acceptancetext]))
		{
			$ERR = $ERR_050;
			$SETTINGS = $HTTP_POST_VARS;
		}
		elseif($HTTP_POST_VARS[newsbox] == 1 && empty($HTTP_POST_VARS[newstoshow]))
		{
			$ERR = $ERR_052;
			$SETTINGS = $HTTP_POST_VARS;
		}
		elseif($HTTP_POST_VARS[newsbox] == 1 && !ereg("^[0-9]+$",$HTTP_POST_VARS[newstoshow]))
		{
			$ERR = $ERR_053;
			$SETTINGS = $HTTP_POST_VARS;
		}
		else
		{
			#// Handle logo upload
			if($HTTP_POST_FILES[logo][tmp_name] != "none")
			{
				$TARGET = $image_upload_path.$HTTP_POST_FILES[logo][name];
				copy($HTTP_POST_FILES['logo']['tmp_name'],$TARGET);
				
				$LOGOUPLOADED = TRUE;
			}
			#// Update data
			$query = "update PHPAUCTION_settings set sitename='".addslashes($HTTP_POST_VARS[sitename])."',
adminmail='".addslashes($HTTP_POST_VARS[adminmail])."',										  siteurl='".addslashes($HTTP_POST_VARS[siteurl])."',";
			if($LOGOUPLOADED)
			{
				$query .= "logo='".$HTTP_POST_FILES[logo][name]."', ";
			}
			$query .= "loginbox=$HTTP_POST_VARS[loginbox],
										  newsbox=$HTTP_POST_VARS[newsbox],
										  newsletter=$HTTP_POST_VARS[newsletter],
										  newstoshow=$HTTP_POST_VARS[newstoshow],
										  showacceptancetext=$HTTP_POST_VARS[showacceptancetext],
										  acceptancetext='".addslashes($HTTP_POST_VARS[acceptancetext])."'
										  ";
			$res = mysql_query($query);
			if(!$res)
			{
				print "Error: $query<BR>".mysql_error();
				exit;
			}
			else
			{
				$ERR = $MSG_542;
				$SETTINGS = $HTTP_POST_VARS;
			}
		}
	}
	else
	{
		#//
		$query = "SELECT * FROM PHPAUCTION_settings";
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

?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_526; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
			<FORM NAME=conf ACTION=<?=basename($PHP_SELF)?> METHOD=POST  ENCTYPE="multipart/form-data">
				    <TABLE WIDTH=550 CELLPADDING=2 ALIGN="CENTER">
						<TR> 
							<TD COLSPAN="2" ALIGN=CENTER><B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FF0000"> 
								<? print $ERR; ?>
								</FONT></B></TD>
						</TR>
						<TR> 
							<TD COLSPAN="2"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_529; ?>
								</FONT> </TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_527; ?>
								</FONT></TD>
							<TD WIDTH="365"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_535; ?>
								</FONT><BR>
								<INPUT TYPE="text" NAME="sitename" SIZE="45" MAXLENGTH="255" VALUE="<?=$SETTINGS[sitename]?>">
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_528; ?>
								</FONT></TD>
							<TD WIDTH="365"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_536; ?>
								</FONT><BR>
								<INPUT TYPE="text" NAME="siteurl" SIZE="45" MAXLENGTH="255" VALUE="<?=$SETTINGS[siteurl]?>">
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_540; ?>
								</FONT></TD>
							<TD WIDTH="365"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								</FONT> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_541; ?>
								</FONT><BR>
								<INPUT TYPE="text" NAME="adminmail" SIZE="45" MAXLENGTH="100" VALUE="<?=$SETTINGS[adminmail]?>">
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								</FONT> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								</FONT></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_531; ?>
								</FONT></TD>
							<TD WIDTH="365"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_556; ?>
								</FONT><BR>
								<IMG SRC="<?="../$uploaded_path".$SETTINGS[logo];?>"> 
								<BR>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_602; ?>
								</FONT><BR>
								<INPUT TYPE="file" NAME="logo" SIZE="25" MAXLENGTH="100">
								<INPUT TYPE=HIDDEN NAME="MAX_FILE_SIZE" VALUE="51200">
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_532; ?>
								</FONT></TD>
							<TD WIDTH="365"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_537; ?>
								</FONT><BR>
								<INPUT TYPE="radio" NAME="loginbox" VALUE="1"
								<? if($SETTINGS[loginbox] == 1) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_030; ?>
								</FONT> 
								<INPUT TYPE="radio" NAME="loginbox" VALUE="2"
								<? if($SETTINGS[loginbox] == 2) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_029; ?>
								</FONT> </TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169 HEIGHT="61"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_533; ?>
								</FONT></TD>
							<TD WIDTH="365" HEIGHT="61"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_538; ?>
								</FONT><BR>
								<INPUT TYPE="radio" NAME="newsbox" VALUE="1"
								<? if($SETTINGS[newsbox] == 1) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_030; ?>
								</FONT> 
								<INPUT TYPE="radio" NAME="newsbox" VALUE="2"
								<? if($SETTINGS[newsbox] == 2) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_029; ?>
								<BR>
								<? print $MSG_554; ?>
								<BR>
								<INPUT TYPE="text" NAME="newstoshow" SIZE="5" MAXLENGTH="10" VALUE="<?=$SETTINGS[newstoshow]?>">
								</FONT> </TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169 HEIGHT="61"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_603; ?>
								</FONT></TD>
							<TD WIDTH="365" HEIGHT="61"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_604; ?>
								</FONT><BR>
								<INPUT TYPE="radio" NAME="newsletter" VALUE="1"
								<? if($SETTINGS[newsletter] == 1) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_030; ?>
								</FONT> 
								<INPUT TYPE="radio" NAME="newsletter" VALUE="2"
								<? if($SETTINGS[newsletter] == 2) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_029; ?>
								<BR>
								</FONT> </TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_534; ?>
								</FONT></TD>
							<TD WIDTH="365"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_539; ?>
								</FONT><BR>
								<INPUT TYPE="radio" NAME="showacceptancetext" VALUE="1"
								<? if($SETTINGS[showacceptancetext] == 1) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_030; ?>
								</FONT> 
								<INPUT TYPE="radio" NAME="showacceptancetext" VALUE="2"
								<? if($SETTINGS[showacceptancetext] == 2) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_029; ?>
								</FONT> </TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=169></TD>
							<TD WIDTH="365"> 
								<TEXTAREA NAME="acceptancetext" COLS="45" ROWS="10"><?=$SETTINGS[acceptancetext]?></TEXTAREA>
							</TD>
						</TR>
						<TR> 
							<TD WIDTH=169> 
								<INPUT TYPE="hidden" NAME="action" VALUE="update">
							</TD>
							<TD WIDTH="365"> 
								<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
							</TD>
						</TR>
						<TR> 
							<TD WIDTH=169></TD>
							<TD WIDTH="365"> </TD>
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
