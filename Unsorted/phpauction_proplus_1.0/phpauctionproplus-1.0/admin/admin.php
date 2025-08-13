<?#//v.1.0.0
	include "loggedin.inc.php";
	
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	Function ImagePrefix($tab)
	{
		GLOBAL $S;
		
		if($tab == $S)
		{
			return "A";
		}
	}

   	require('../includes/messages.inc.php');
    include "../includes/config.inc.php";


	#// Reset counters
	if($HTTP_POST_VARS[process] == "RESET")
	{
		$TODAY = date("Ymd");
		$query = "UPDATE PHPAUCTIONPROPLUS_counters 
				  SET
				  transactions=0,
				  totalamount=0,
				  fees=0,
				  resetdate='$TODAY',
				  suspendedauction=0";
		$res = @mysql_query($query);
		if(!$res)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
	}
	
	#// Switch fee type (pay <-> prepay
	if(!empty($HTTP_POST_VARS[switchfeetype]))
	{
		$query = "UPDATE PHPAUCTIONPROPLUS_settings
				  SET
				  feetype='$HTTP_POST_VARS[SWITCH]'";
		$res = @mysql_query($query);
		if(!$res)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		else
		{
			$SETTINGS["feetype"] = $HTTP_POST_VARS["SWITCH"];
		}
		
	}
	

	require("./header.php");
   	require('../includes/styles.inc.php'); 
   	
   	
   	#// Handle TABS
   	if(!isset($HTTP_SESSION_VARS[S]) && !isset($HTTP_GET_VARS[S]))
   	{
   		$S = "settings";
   		session_name("PHPAUCTIONADMIN");
   		session_register("S");
   	}
   	if(isset($HTTP_GET_VARS[S]))
   	{
   		$S = $HTTP_GET_VARS[S];
   		session_name("PHPAUCTIONADMIN");
   		session_register("S");
	}  
	
	#// Retrieve counters
	$query = "SELECT * FROM PHPAUCTIONPROPLUS_counters";
	$res = mysql_query($query);
	if(!$res)
	{
		print "Error: $query<BR>".mysql_error();
		exit;
	}
	else
	{
		$COUNTERS = mysql_fetch_array($res);
	}
	
	
?>  


<SCRIPT LANGUAGE=javascript>
<!--
function	ConfirmReset()
{
	var x = window.confirm("<?=$MSG_408?>")
	if(x)
	{
		document.resetcounters.process.value = "RESET";
		document.resetcounters.submit();
	}

}	
//-->
</SCRIPT>
<TABLE WIDTH=650 BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF" ALIGN="CENTER">
	<TR>
		<TD> 
			<TABLE WIDTH=100% CELLPADDING=4 ALIGN="CENTER" CELLSPACING="0">
				<TR BGCOLOR="#FFFFFF"> 
					<TD COLSPAN="2"  VALIGN=top>&nbsp;</TD>
				</TR>
				<?
				if($NOTCONNECTED)
				{
			?>
				<TR BGCOLOR="#FF9900"> 
					<TD COLSPAN="2"  VALIGN=top> <FONT FACE=Verdana SIZE=2 COLOR=#FFFFFF> 
						<?=$ERR_049?>
						</FONT></TD>
				</TR>
				<?
					}
				?>
				<TR> 
					<TD WIDTH=43%  VALIGN=top> 
						<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#99CCFF">
							<TR> 
								<TD> 
									<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING="0" BORDER="0">
										<TR> 
											<TD BGCOLOR="#99CCFF"> <FONT FACE="Tahoma, Verdana" SIZE="2"> 
												<B> 
												<? print $MSG_349; ?>
												</B> </FONT></TD>
										</TR>
										<TR> 
											<TD BGCOLOR="#FFFFFF"> 
												<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
													<TR BGCOLOR="#CCCCCC"> 
														<TD COLSPAN="2"><B><FONT FACE="Tahoma, Verdana" SIZE="2" COLOR="#333333"> 
															<? print $MSG_361; ?>
															</FONT></B></TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="73%"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_351; ?>
															</FONT></TD>
														<TD WIDTH="27%"> 
															<DIV ALIGN="RIGHT"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
																<? print intval($COUNTERS[users]); ?>
																</FONT></DIV>
														</TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="73%"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_352 ?>
															</FONT></TD>
														<TD WIDTH="27%"> 
															<DIV ALIGN="RIGHT"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
																<? print $COUNTERS[inactiveusers]; ?>
																</FONT></DIV>
														</TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="73%"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<B> 
															<? print $MSG_350; ?>
															</B> </FONT></TD>
														<TD WIDTH="27%"> 
															<DIV ALIGN="RIGHT"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
																<? print $COUNTERS[users]; ?>
																</FONT></B></DIV>
														</TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="73%"><IMG SRC="images/transparent.gif" WIDTH="1" HEIGHT="1"></TD>
														<TD WIDTH="27%"><IMG SRC="images/transparent.gif" WIDTH="1" HEIGHT="1"></TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="73%"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_353; ?>
															</FONT></B></TD>
														<TD WIDTH="27%"> 
															<DIV ALIGN="RIGHT"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
																<? print $COUNTERS[auctions]; ?>
																</FONT></B></DIV>
														</TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="73%"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_354; ?>
															</FONT></B></TD>
														<TD WIDTH="27%"> 
															<DIV ALIGN="RIGHT"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
																<? print $COUNTERS[closedauctions]; ?>
																</FONT></B></DIV>
														</TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="73%"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_355; ?>
															</FONT></B></TD>
														<TD WIDTH="27%"> 
															<DIV ALIGN="RIGHT"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
																<? print $COUNTERS[bids]; ?>
																</FONT></B></DIV>
														</TD>
													</TR>
												</TABLE>
												<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="2">
													<TR BGCOLOR="#99CCFF"> 
														<TD COLSPAN="2"><IMG SRC="images/transparent.gif" WIDTH="1" HEIGHT="1"></TD>
													</TR>
													<TR BGCOLOR="#CCCCCC"> 
														<TD COLSPAN="2"><B><FONT FACE="Tahoma, Verdana" SIZE="2" COLOR="#333333"> 
															<? print $MSG_360; ?></FONT></B>
															&nbsp;&nbsp;
															<FONT FACE="Tahoma, Verdana" SIZE="1">
															(<? print $MSG_362; ?>
															<? print $RESET_DATE; ?>)
															</FONT></TD>
													</TR>
													<TR BGCOLOR="#EEEEEE"> 
														<TD COLSPAN="2"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															</FONT></TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="60%"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_356; ?>
															</FONT></TD>
														<TD WIDTH="40%" ALIGN=RIGHT>
															<FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<?=$COUNTERS[transactions]?></FONT>
														</TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="60%"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_357; ?>
															</FONT></TD>
														<TD WIDTH="40%" ALIGN=RIGHT> 
															<FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<?=print_money($COUNTERS[totalamount])?>
															</FONT>
														</TD>
													</TR>
													<TR BGCOLOR="#E1E8F2"> 
														<TD WIDTH="60%"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<? print $MSG_364; ?>
															</FONT></TD>
														<TD WIDTH="40%" ALIGN=RIGHT>
															<FONT FACE="Tahoma, Verdana" SIZE="2"> 
															<?=print_money($COUNTERS[fees])?>
															</FONT>
														</TD>
													</TR>
													<FORM NAME=resetcounters ACTION=<?=basename($PHP_SELF)?> METHOD=post>
													<INPUT TYPE=hidden NAME=process VALUE="">
													    <TR BGCOLOR="#FFCC00"> 
															<TD COLSPAN="2" ALIGN=CENTER><FONT FACE="Tahoma, Verdana" SIZE="2"> 
																<INPUT TYPE="submit" NAME="Submit" VALUE="<?=$MSG_359; ?>" onClick="ConfirmReset()">
																</FONT></TD>
													</TR>
													</FORM>
												</TABLE>
											</TD>
										</TR>
									</TABLE>
								</TD>
							</TR>
						</TABLE>
					</TD>
					<!-- Installation -->
					<!-- Configuration -->
					<TD WIDTH=57%  VALIGN=top> 
						<TABLE WIDTH=100% CELLPADDING=0 CELLSPACING="0" BORDER="0" ALIGN="CENTER">
							<TR> 
								<TD BGCOLOR="#FFFFFF" WIDTH="21%"> <A HREF="admin.php?S=settings"><IMG SRC="images/<?=ImagePrefix("settings")?>settings.gif" BORDER=0 WIDTH="66" HEIGHT="25"></A></TD>
								<TD BGCOLOR="#FFFFFF" WIDTH="21%"><A HREF="admin.php?S=aux"><IMG SRC="images/<?=ImagePrefix("aux")?>auxiliary.gif" WIDTH="66" HEIGHT="25" BORDER="0"></A></TD>
								<TD BGCOLOR="#FFFFFF" WIDTH="21%"><A HREF="admin.php?S=users"><IMG SRC="images/<?=ImagePrefix("users")?>users.gif" WIDTH="66" HEIGHT="25" BORDER="0"></A></TD>
								<TD BGCOLOR="#FFFFFF" WIDTH="21%"><A HREF="admin.php?S=auctions"><IMG SRC="images/<?=ImagePrefix("auctions")?>auctions.gif" WIDTH="66" HEIGHT="25" BORDER="0"></A></TD>
								<TD BGCOLOR="#FFFFFF" WIDTH="16%"><A HREF="admin.php?S=contents"><IMG SRC="images/<?=ImagePrefix("contents")?>contents.gif" WIDTH="66" HEIGHT="25" BORDER="0"></A><A HREF="admin.php?S=fees"><IMG SRC="images/<?=ImagePrefix("fees")?>fees.gif" BORDER="0"></A></TD>
							</TR>
							<TR BGCOLOR="#296FAB"> 
								<TD COLSPAN="5">&nbsp;</TD>
							</TR>
						</TABLE>    
						<TABLE WIDTH=100% CELLPADDING=1 CELLSPACING="0" BORDER="0" ALIGN="CENTER" BGCOLOR="#336699">
							<TR BGCOLOR="#FFFFFF"> 
								<TD COLSPAN="5" BGCOLOR="#336699"> 
									<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="2">
										<TR> 
											<TD BGCOLOR="#FFFFFF"> 
												<?
													switch($S)
													{
														case "settings":
															include "settings.menu.php";
															break;
														case "aux":
															include "auxiliary.menu.php";
															break;
														case "users":
															include "users.menu.php";
															break;
														case "auctions":
															include "auctions.menu.php";
															break;
														case "contents":
															include "contents.menu.php";
															break;
														case "fees":
															include "fees.menu.php";
															break;
													}
												?>
											</TD>
										</TR>
									</TABLE>
								</TD>
							</TR>
						</TABLE>
					    <TABLE WIDTH=100% CELLPADDING=1 CELLSPACING="0" BORDER="0" ALIGN="CENTER" BGCOLOR="#336699">
							<TR BGCOLOR="#FFFFFF"> 
								<TD COLSPAN="5" BGCOLOR="#FFCC00"> 
									<CENTER>
										<A HREF="<?=$SETTINGS[siteurl]?>"><FONT FACE="Tahoma, Verdana" SIZE="2">Site 
										Home</FONT></A> 
										&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF="patches.php"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
										<?=$MSG_655?></FONT></A> 
									</CENTER>
								</TD>
							</TR>
						</TABLE>
					</TD>
					<!-- Administration -->
				</TR>
			</TABLE>
			<BR>

</TD>
</TR>
</TABLE>


<? include "footer.php"; ?>
<P>&nbsp;</P>
</BODY></HTML>
