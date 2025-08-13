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
?>
  


<TABLE WIDTH=650 BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF" ALIGN="CENTER">
	<TR>
		<TD> <BR>
			<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR>
					<TD BGCOLOR="#333333"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="1"></TD>
				</TR>
				<TR>
					<TD BGCOLOR="#E9ECF3">
						<CENTER>
							<B><FONT FACE="Tahoma, Verdana" SIZE="2">ADMINISTRATION 
							OPTIONS</FONT></B> 
						</CENTER>
					</TD>
				</TR>
				<TR>
					<TD BGCOLOR="#000000"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="1"></TD>
				</TR>
			</TABLE>
			<BR>
			<TABLE WIDTH=100% CELLPADDING=4 ALIGN="CENTER" CELLSPACING="0">
				<?
				if($NOTCONNECTED)
				{
			?>
				<TR BGCOLOR="#FF9900"> 
					<TD COLSPAN="3"  VALIGN=top> <FONT FACE=Verdana SIZE=2 COLOR=#FFFFFF> 
						<?=$ERR_049?>
						</FONT></TD>
				</TR>
				<?
					}
				?>
				<TR> 
					<TD WIDTH=33%  VALIGN=top> 
						<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING="0" BORDER="0">
							<TR> 
								<TD BGCOLOR="#eeeeee"> <FONT FACE="Tahoma, Verdana" SIZE="2"> 
									<B> 
									<? print $MSG_524; ?>
									</B> </FONT></TD>
							</TR>
							<TR> 
								<TD><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./settings.php" CLASS="links"> 
									<? print $MSG_526; ?>
									</A></FONT></TD>
							</TR>
							<TR> 
								<TD><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./currency.php"  CLASS="links"> 
									<? print $MSG_076; ?>
									</A></FONT></TD>
							</TR>
							<TR> 
								<TD><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./fontsandcolors.php" CLASS="links"> 
									<? print $MSG_555; ?>
									</A> </FONT></TD>
							</TR>
							<TR> 
								<TD><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./adminusers.php" CLASS="links"> 
									<? print $MSG_525; ?>
									</A></FONT></TD>
							</TR>
							<TR> 
								<TD><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./banners.php" CLASS="links"> 
									<? print $MSG_599; ?>
									</A></FONT></TD>
							</TR>
						</TABLE>
					</TD>
					<!-- Installation -->
					<!-- Configuration -->
					<TD WIDTH=33%  VALIGN=top> 
						<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING="0">
							<TR> 
								<TD BGCOLOR="#EEEEEE"> <FONT FACE="Tahoma, Verdana" SIZE="2"> 
									<B> 
									<? print $MSG_063; ?>
									</B> </FONT></TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"> <IMG SRC="../images/ball.gif"> 
									<A HREF="./categories.php"  CLASS="links"> 
									<? print $MSG_078; ?>
									</A> </FONT> </TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"> <IMG SRC="../images/ball.gif"> 
									<A HREF="./countries.php" CLASS="links"> 
									<? print $MSG_083; ?>
									</A> </FONT> </TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"> <IMG SRC="../images/ball.gif"> 
									<A HREF="./payments.php" CLASS="links"> 
									<? print $MSG_075; ?>
									</A> </FONT> </TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"> <IMG SRC="../images/ball.gif"> 
									<A HREF="./durations.php" CLASS="links"> 
									<? print $MSG_069; ?>
									</A> </FONT> </TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"> <IMG SRC="../images/ball.gif"> 
									<A HREF="./increments.php" CLASS="links"> 
									<? print $MSG_128; ?>
									</A> </FONT> </TD>
							</TR>
						</TABLE>
					</TD>
					<!-- Administration -->
					<TD WIDTH=34% VALIGN=top> 
						<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING="0" BORDER="0">
							<TR> 
								<TD BGCOLOR="#EEEEEE"> <FONT FACE="Tahoma, Verdana" SIZE="2"> 
									<B> 
									<? print $MSG_062; ?>
									</B> </FONT></TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./listusers.php" CLASS="links"> 
									<? print $MSG_045; ?>
									</A> </FONT></TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./listauctions.php" CLASS="links"> 
									<? print $MSG_067;?>
									</A> </FONT></TD>
							</TR>
							<TR> 
								<TD><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./listhelp.php" CLASS="links"> 
									<? print $MSG_916;?>
									</A></FONT></TD>
							</TR>
							<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./news.php" CLASS="links"> 
									<? print $MSG_516; ?>
									</A> </FONT>
								</TD>
							</TR>
<TR> 
								<TD> <FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="../images/ball.gif"> 
									<A HREF="./updatecountersgpl.php" CLASS="links"> 
									<? print "Re-Sync Counters"; ?>
									</A> </FONT>
								</TD>
							</TR>
							<? 
											if($SETTINGS[newsletter] == 1){
								?>
									   <TR> 
										 <TD> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"font color="navy"> 
											<IMG SRC="../images/ball.gif">
											<A HREF="./newsletter.php" CLASS="links">
											<?=$MSG_607?></A> </FONT>
											</TD>
									  </TR>
							<?
								}
								else{
								"";
								}
							 ?>  							
						</TABLE>
					</TD>
				</TR>
			</TABLE>
			<BR>

</TD>
</TR>
</TABLE>


<? include "footer.php"; ?>
<P>&nbsp;</P>
</BODY></HTML>
