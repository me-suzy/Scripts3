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
			$query = "update PHPAUCTION_settings set currency='".addslashes($HTTP_POST_VARS[currency])."',
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
	
			
	require("./header.php");
	require('../includes/styles.inc.php'); 


?>
  
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_076; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
			<FORM NAME=conf ACTION=currency.php METHOD=POST>
				    <TABLE WIDTH=500 CELLPADDING=2 ALIGN="CENTER">
						<TR> 
							<TD COLSPAN="2" ALIGN=CENTER><B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FF0000"></FONT></B> 
								<B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FF0000"> 
								<?=$ERR?>
								</FONT> </B></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=116 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<?=$MSG_552;?>
								</FONT></TD>
							<TD HEIGHT="31" WIDTH="368"> 
								<INPUT TYPE=text NAME=currency VALUE="<?=$SETTINGS[currency]?>" SIZE=5 MAXLENGTH="10">
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2" HEIGHT="7"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=116 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<?=$MSG_544;?>
								</FONT></TD>
							<TD HEIGHT="31" WIDTH="368"> 
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
							<TD WIDTH=116 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<?=$MSG_548;?>
								</FONT></TD>
							<TD HEIGHT="31" WIDTH="368"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<?=$MSG_547;?>
								</FONT><BR>
								<INPUT TYPE=text NAME=moneydecimals VALUE="<?=$SETTINGS[moneydecimals]?>" SIZE=5 MAXLENGTH="10">
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2" HEIGHT="6"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=116 HEIGHT="31"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<?=$MSG_549;?>
								</FONT></TD>
							<TD HEIGHT="31" WIDTH="368"> 
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
							<TD WIDTH=116> 
								<INPUT TYPE="hidden" NAME="action" VALUE="update">
								<INPUT TYPE="hidden" NAME="id" VALUE="<?=$id?>">
							</TD>
							<TD WIDTH="368"> 
								<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
							</TD>
						</TR>
						<TR> 
							<TD WIDTH=116></TD>
							<TD WIDTH="368"> </TD>
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
