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

   require("./header.php"); 
   require('../includes/styles.inc.php'); 


	#//
	$ERR = "&nbsp;";
	
	if(is_array($HTTP_POST_VARS[delete]))
	{
		while(list($k,$v) = each($HTTP_POST_VARS[delete]))
		{
			@mysql_query("delete from PHPAUCTION_adminusers where id=$k");
		}
	}
	
	#//
	$query = "SELECT * FROM PHPAUCTION_adminusers order by username";
	$res = @mysql_query($query);
	if(!$res)
	{
		print "Error: $query<BR>".mysql_error();
		exit;
	}

?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_525; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
			<FORM NAME=conf ACTION=<?=basename($PHP_SELF)?> METHOD=POST>
				    <TABLE WIDTH=650 CELLPADDING=2 ALIGN="CENTER">
						<TR> 
							<TD COLSPAN="2"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A></FONT> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR BGCOLOR="#EEEEEE"> 
										<TD COLSPAN="5" ALIGN=CENTER><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF=newadminuser.php> 
											<?=$MSG_568?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#999999"> 
										<TD WIDTH="30%"> 
											<CENTER>
												<B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
												<? print $MSG_557; ?>
												</FONT></B> 
											</CENTER>
										</TD>
										<TD WIDTH="16%"> 
											<CENTER>
												<B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
												<? print $MSG_558; ?>
												</FONT><FONT SIZE="2"><FONT FACE="Verdana, Arial, Helvetica, sans-serif"></FONT></FONT></B> 
											</CENTER>
										</TD>
										<TD WIDTH="19%"> 
											<CENTER>
												<B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
												<? print $MSG_559; ?>
												</FONT><FONT SIZE="2"><FONT FACE="Verdana, Arial, Helvetica, sans-serif"></FONT></FONT></B> 
											</CENTER>
										</TD>
										<TD WIDTH="12%"> 
											<CENTER>
												<B><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
												<? print $MSG_560; ?>
												</FONT><FONT SIZE="2"><FONT FACE="Verdana, Arial, Helvetica, sans-serif"></FONT></FONT></B> 
											</CENTER>
										</TD>
										<TD WIDTH="23%"> 
											<CENTER>
												<B><FONT SIZE="2"><FONT FACE="Verdana, Arial, Helvetica, sans-serif"> 
												<INPUT TYPE="submit" NAME="Submit" VALUE="<?=$MSG_561?>">
												</FONT></FONT></B> 
											</CENTER>
										</TD>
									</TR>
									<?
										while($USER = mysql_fetch_array($res))
										{
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
													   
									?>
									<TR BGCOLOR="#EEEEEE"> 
										<TD WIDTH="30%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<A HREF=editadminuser.php?id=<?=$USER[id]?>> 
											<?=$USER[username]?>
											</A> </FONT> </TD>
										<TD WIDTH="16%" ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$CREATED?>
											</FONT> </TD>
										<TD WIDTH="19%" ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$LASTLOGIN?>
											</FONT> </TD>
										<TD WIDTH="12%" ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$STATUS[$USER[status]]?>
											</FONT> </TD>
										<TD WIDTH="23%"> 
											<CENTER>
												<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
												<INPUT TYPE="checkbox" NAME="delete[<?=$USER[id]?>]" VALUE="<?=$USER[id]?>">
												</FONT> 
											</CENTER>
										</TD>
									</TR>
									<?
									}
								?>
								</TABLE>
								<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A> </FONT> </TD>
						</TR>
						<TR> 
							<TD WIDTH=169> 
								<INPUT TYPE="hidden" NAME="action" VALUE="update">
							</TD>
							<TD WIDTH="365">&nbsp; </TD>
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
