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
   require('../includes/fonts.inc.php');
   require('../includes/fontsize.inc.php');
   require('../includes/fontcolor.inc.php');

   require("./header.php"); 
   require('../includes/styles.inc.php'); 


	#//
	$ERR = "&nbsp;";
	
	#// 
	if($HTTP_POST_VARS[action] == "update")
	{
		#// Build fonts description strings
		$STD_FONT = $HTTP_POST_VARS[std_face].
					$HTTP_POST_VARS[std_size].
					$HTTP_POST_VARS[std_color].
					$HTTP_POST_VARS[std_bold].
					$HTTP_POST_VARS[std_italic];
		$ERR_FONT = $HTTP_POST_VARS[err_face].
					$HTTP_POST_VARS[err_size].
					$HTTP_POST_VARS[err_color].
					$HTTP_POST_VARS[err_bold].
					$HTTP_POST_VARS[err_italic];
		$SML_FONT = $HTTP_POST_VARS[sml_face].
					$HTTP_POST_VARS[sml_size].
					$HTTP_POST_VARS[sml_color].
					$HTTP_POST_VARS[sml_bold].
					$HTTP_POST_VARS[sml_italic];
		$NAV_FONT = $HTTP_POST_VARS[nav_face].
					$HTTP_POST_VARS[nav_size].
					$HTTP_POST_VARS[nav_color].
					$HTTP_POST_VARS[nav_bold].
					$HTTP_POST_VARS[nav_italic];
		$TLT_FONT = $HTTP_POST_VARS[tlt_face].
					$HTTP_POST_VARS[tlt_size].
					$HTTP_POST_VARS[tlt_color].
					$HTTP_POST_VARS[tlt_bold].
					$HTTP_POST_VARS[tlt_italic];
		$FOOTER_FONT = $HTTP_POST_VARS[footer_face].
					$HTTP_POST_VARS[footer_size].
					$HTTP_POST_VARS[footer_color].
					$HTTP_POST_VARS[footer_bold].
					$HTTP_POST_VARS[footer_italic];
					
		$query = "UPDATE PHPAUCTION_settings set std_font='$STD_FONT',
									  err_font='$ERR_FONT',
									  nav_font='$NAV_FONT',
									  tlt_font='$TLT_FONT',
									  footer_font='$FOOTER_FONT',
									  sml_font='$SML_FONT',
									  bordercolor='$HTTP_POST_VARS[bordercolor]',
									  headercolor='$HTTP_POST_VARS[headercolor]',
									  tableheadercolor='$HTTP_POST_VARS[tableheadercolor]',
									  linkscolor='$HTTP_POST_VARS[linkscolor]',
									  vlinkscolor='$HTTP_POST_VARS[vlinkscolor]'";
		$res_ = @mysql_query($query);
		if(!$res_)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		else
		{
			$ERR = $MSG_593;
		}
	}

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

?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_555; ?>
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
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_571; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A></FONT> 
								<!-- ** STANDARD FONT ** -->
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_577; ?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_578; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=std_face>
												<?
											while(list($k,$v) = each($FONTS))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[std_font],0,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</select>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_579; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=std_size>
												<?
											while(list($k,$v) = each($FONTSIZE))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[std_font],1,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</select>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=std_color VALUE=<?=$k?>
														<? if(substr($SETTINGS[std_font],2,1) == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_581; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="std_bold" VALUE="1"
											<? if(substr($SETTINGS[std_font],3,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="std_bold" VALUE="2"
											
											<? if(substr($SETTINGS[std_font],3,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_582; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="std_italic" VALUE="1"
											<? if(substr($SETTINGS[std_font],4,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="std_italic" VALUE="2"
											<? if(substr($SETTINGS[std_font],4,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_572; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A></FONT> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_576; ?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_578; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=err_face>
												<?
											reset($FONTS);
											while(list($k,$v) = each($FONTS))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[err_font],0,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_579; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=err_size>
												<?
											reset($FONTSIZE);
											while(list($k,$v) = each($FONTSIZE))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[err_font],1,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=err_color VALUE=<?=$k?>
														<? if(substr($SETTINGS[err_font],2,1) == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_581; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="err_bold" VALUE="1"
											<? if(substr($SETTINGS[err_font],3,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="err_bold" VALUE="2"
											<? if(substr($SETTINGS[err_font],3,1) == 2) print " CHECKED";?>>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_582; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="err_italic" VALUE="1"
											<? if(substr($SETTINGS[err_font],4,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="err_italic" VALUE="2"
											<? if(substr($SETTINGS[err_font],4,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
								</TABLE>
								<BR>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_588; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A></FONT> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_589; ?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_578; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=nav_face>
												<?
											reset($FONTS);
											while(list($k,$v) = each($FONTS))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[nav_font],0,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_579; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=nav_size>
												<?
											reset($FONTSIZE);
											while(list($k,$v) = each($FONTSIZE))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[nav_font],1,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=nav_color VALUE=<?=$k?>
														<? if(substr($SETTINGS[nav_font],2,1) == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_581; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="nav_bold" VALUE="1"
											<? if(substr($SETTINGS[nav_font],3,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="nav_bold" VALUE="2"
											<? if(substr($SETTINGS[nav_font],3,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_582; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="nav_italic" VALUE="1"
											<? if(substr($SETTINGS[nav_font],4,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="nav_italic" VALUE="2"
											<? if(substr($SETTINGS[nav_font],4,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
								</TABLE>
								<BR>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_573; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A></FONT> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_583;?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_578; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=sml_face>
												<?
											reset($FONTS);
											while(list($k,$v) = each($FONTS))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[sml_font],0,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_579; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=sml_size>
												<?
											reset($FONTSIZE);
											while(list($k,$v) = each($FONTSIZE))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[sml_font],1,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=sml_color VALUE=<?=$k?>
														<?if(substr($SETTINGS[sml_font],2,1) == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_581; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="sml_bold" VALUE="1"
											<?if(substr($SETTINGS[sml_font],3,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="sml_bold" VALUE="2"
											<?if(substr($SETTINGS[sml_font],3,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_582; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="sml_italic" VALUE="1"
											<?if(substr($SETTINGS[sml_font],4,1) == 1) print " CHECKED";?>>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="sml_italic" VALUE="2"
											<?if(substr($SETTINGS[sml_font],4,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
								</TABLE>
								<BR>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_574; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2" HEIGHT="21"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_584;?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_578; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=footer_face>
												<?
											reset($FONTS);
											while(list($k,$v) = each($FONTS))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[footer_font],0,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_579; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=footer_size>
												<?
											reset($FONTSIZE);
											while(list($k,$v) = each($FONTSIZE))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[footer_font],1,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=footer_color VALUE=<?=$k?>
														<? if(substr($SETTINGS[footer_font],2,1) == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_581; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="footer_bold" VALUE="1"
											<?if(substr($SETTINGS[footer_font],3,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="footer_bold" VALUE="2"
											<?if(substr($SETTINGS[footer_font],3,1) == 2) print " CHECKED";?>>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_582; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="footer_italic" VALUE="1"
											<?if(substr($SETTINGS[footer_font],4,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="footer_italic" VALUE="2"
											<?if(substr($SETTINGS[footer_font],4,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_575; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A></FONT> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2" HEIGHT="21"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_585;?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_578; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=tlt_face>
												<?
											reset($FONTS);
											while(list($k,$v) = each($FONTS))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[tlt_font],0,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_579; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<SELECT NAME=tlt_size>
												<?
											reset($FONTSIZE);
											while(list($k,$v) = each($FONTSIZE))
											{
												print "<OPTION VALUE=$k";
												if(substr($SETTINGS[tlt_font],1,1) == $k) print " SELECTED";
												print ">$v</OPTION>\n";
												
											}
										?>
											</SELECT>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=tlt_color VALUE=<?=$k?>
														<?if(substr($SETTINGS[tlt_font],2,1) == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_581; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="tlt_bold" VALUE="1"
											<?if(substr($SETTINGS[tlt_font],3,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="tlt_bold" VALUE="2"
											<?if(substr($SETTINGS[tlt_font],3,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_582; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<INPUT TYPE="radio" NAME="tlt_italic" VALUE="1"
											<?if(substr($SETTINGS[tlt_font],4,1) == 1) print " CHECKED";?>
											>
											<?=$MSG_030?>
											<INPUT TYPE="radio" NAME="tlt_italic" VALUE="2"
											<?if(substr($SETTINGS[tlt_font],4,1) == 2) print " CHECKED";?>
											>
											<?=$MSG_029?>
											</FONT></TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_586; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								</A></FONT> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2" HEIGHT="21"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_587;?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=bordercolor VALUE=<?=$k?>
														<?if($SETTINGS[bordercolor] == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2" HEIGHT="7"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_590; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2" HEIGHT="21"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_634;?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=headercolor VALUE=<?=$k?>
														<?if($SETTINGS[headercolor] == $k) print " CHECKED";?>
														>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
								<? print $MSG_591; ?>
								</A></FONT></TD>
							<TD WIDTH="393"> 
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR> 
										<TD COLSPAN="2" HEIGHT="21"><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_633;?>
											</A></FONT></TD>
									</TR>
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=tableheadercolor  VALUE=<?=$k?>
														<?if($SETTINGS[tableheadercolor] == $k) print " CHECKED";?>>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links">
								<? print $MSG_595; ?>
								</A></FONT></TD>
							<TD WIDTH="393">
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=linkscolor VALUE=<?=$k?>
														<?if($SETTINGS[linkscolor] == $k) print " CHECKED";?>>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD COLSPAN="2" HEIGHT="7"><IMG SRC="../images/transparent.gif" WIDTH="1" HEIGHT="5"></TD>
						</TR>
						<TR VALIGN="TOP"> 
							<TD WIDTH=141><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links">
								<? print $MSG_596; ?>
								</A></FONT></TD>
							<TD WIDTH="393">
								<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
									<TR BGCOLOR="#EEEEEE" VALIGN="TOP"> 
										<TD WIDTH="20%"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"><A HREF="./increments.php" CLASS="links"> 
											<? print $MSG_580; ?>
											</A></FONT></TD>
										<TD WIDTH="80%"> 
											<TABLE WIDTH="150" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#FFFFFF">
												<?
												reset($FONTCOLOR);
												while(list($k,$v) = each($FONTCOLOR))
												{
											?>
												<TR> 
													<TD WIDTH="18" BGCOLOR=<?=$v?>>&nbsp;</TD>
													<TD WIDTH="129" BGCOLOR="#EEEEEE"> 
														<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
														<INPUT TYPE=radio NAME=vlinkscolor VALUE=<?=$k?>
														<?if($SETTINGS[vlinkscolor] == $k) print " CHECKED";?>>
														<?=$v?>
														</FONT></TD>
												</TR>
												<?
												}
											?>
											</TABLE>
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR> 
							<TD WIDTH=141> 
								<INPUT TYPE="hidden" NAME="action" VALUE="update">
							</TD>
							<TD WIDTH="393"> 
								<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_530; ?>">
							</TD>
						</TR>
						<TR> 
							<TD WIDTH=141></TD>
							<TD WIDTH="393"> </TD>
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
