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
   require('../includes/status.inc.php');


	#//
	$ERR = "&nbsp;";
	
	if($HTTP_POST_VARS[action] == "update")
	{
		if((!empty($HTTP_POST_VARS[password]) && empty($HTTP_POST_VARS[repeatpassword])) ||
		   (empty($HTTP_POST_VARS[password]) && !empty($HTTP_POST_VARS[repeatpassword])))
		{
			$ERR = $ERR_054;
			$USER = $HTTP_POST_VARS;
		}
		elseif($HTTP_POST_VARS[password] != $HTTP_POST_VARS[repeatpassword])
		{
			$ERR = $ERR_006;
			$USER = $HTTP_POST_VARS;
		}
		else
		{
			#// Update
			$query = "update PHPAUCTION_adminusers set";
			if(!empty($HTTP_POST_VARS[password]))
			{
				$PASS = md5($MD5_PREFIX.$HTTP_POST_VARS[password]);
				$query .= " password='$PASS', ";
			}
			$query .= " status=".intval($HTTP_POST_VARS[status])."
			          where id=$HTTP_POST_VARS[id]";
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
			          
	#//
	$query = "SELECT * FROM PHPAUCTION_adminusers where id=$id";
	$res = @mysql_query($query);
	if(!$res)
	{
		print "Error: $query<BR>".mysql_error();
		exit;
	}
	$USER = mysql_fetch_array($res);
	$CREATED = substr($USER[created],4,2)."/".
			   substr($USER[created],6,2)."/".
			   substr($USER[created],0,4);
	if($USER[lastlogin] == 0)
	{
		$LASTLOGIN = $MSG_570;
	}
	else
	{
		$LASTLOGIN = substr($USER[lastlogin],4,2)."/".
				   substr($USER[lastlogin],6,2)."/".
				   substr($USER[lastlogin],0,4)." ".
				   substr($USER[lastlogin],8,2).":".
				   substr($USER[lastlogin],10,2).":".
				   substr($USER[lastlogin],12,2);
	}
	

   require("./header.php"); 
   require('../includes/styles.inc.php'); 

?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_562; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
			<FORM NAME=conf ACTION=<?=basename($PHP_SELF)?> METHOD=POST>
				    <TABLE WIDTH=550 CELLPADDING=2 ALIGN="CENTER">
						<TR> 
							<TD COLSPAN="2" ALIGN=CENTER><B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FF0000"> 
								<? print $ERR; ?>
								</FONT></B></TD>
						</TR>
						<TR> 
							<TD WIDTH="123"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_557; ?>
								</FONT></TD>
							<TD WIDTH="411"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<B> 
								<? print $USER[username]; ?>
								</B> </FONT></TD>
						</TR>
						<TR> 
							<TD WIDTH="123"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_558; ?>
								</FONT></TD>
							<TD WIDTH="411"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $CREATED;?>
								</FONT></TD>
						</TR>
						<TR> 
							<TD WIDTH="123"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_559; ?>
								</FONT></TD>
							<TD WIDTH="411"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $LASTLOGIN; ?>
								</FONT></TD>
						</TR>
						<TR> 
							<TD COLSPAN="2" BGCOLOR="#EEEEEE"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_563; ?>
								</FONT></TD>
						</TR>
						<TR> 
							<TD WIDTH="123" BGCOLOR="#EEEEEE"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_004; ?>
								</FONT></TD>
							<TD WIDTH="411" BGCOLOR="#EEEEEE">
								<INPUT TYPE="PASSWORD" NAME="password" SIZE="25">
							</TD>
						</TR>
						<TR> 
							<TD WIDTH="123" BGCOLOR="#EEEEEE"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_564; ?>
								</FONT></TD>
							<TD WIDTH="411" BGCOLOR="#EEEEEE">
								<INPUT TYPE="PASSWORD" NAME="repeatpassword" SIZE="25">
							</TD>
						</TR>
						<TR> 
							<TD WIDTH="123"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_565; ?>
								</FONT></TD>
							<TD WIDTH="411"> 
								<INPUT TYPE="radio" NAME="status" VALUE="1"
								<? if($USER[status] == 1) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_566; ?>
								</FONT> 
								<INPUT TYPE="radio" NAME="status" VALUE="2"
								<? if($USER[status] == 2) print " CHECKED";?>
								>
								<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<? print $MSG_567; ?>
								</FONT> </TD>
						</TR>
						<TR>
							<TD WIDTH=123>&nbsp;</TD>
							<TD WIDTH="411">&nbsp;</TD>
						</TR>
						<TR> 
							<TD WIDTH=123> 
								<INPUT TYPE="hidden" NAME="action" VALUE="update">
								<INPUT TYPE="hidden" NAME="id" VALUE="<?=$id?>">
							</TD>
							<TD WIDTH="411">
								<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
							</TD>
						</TR>
						<TR> 
							<TD WIDTH=123></TD>
							<TD WIDTH="411"> </TD>
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
</TD>
</TR>
</TABLE>

<? require("./footer.php"); ?>
</BODY>
</HTML>
