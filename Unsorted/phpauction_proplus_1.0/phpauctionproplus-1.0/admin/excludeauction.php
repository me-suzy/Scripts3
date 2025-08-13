<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "loggedin.inc.php";


	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	include "../includes/countries.inc.php";
	
	$username = $name;


	//-- Data check
	if(!$id){
		header("Location: listauctions.php");
		exit;
	}
	
	if($action){
	$closql=mysql_query ("select closed from PHPAUCTIONPROPLUS_auctions WHERE id='$id'");
	$close=mysql_fetch_array($closql);
	$closed=$close['closed'];
		if($mode == "activate")
		{
			$sql="update PHPAUCTIONPROPLUS_auctions set suspended=0 WHERE id='$id'";
/* Update column suspendedauction,auctions,closedauctions   in table PHPAUCTIONPROPLUS_counters */
			if ($closed==1){
			$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET suspendedauction=(suspendedauction-1),closedauctions=(closedauctions+1)");
			}
			else if ($closed==0){
			$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET suspendedauction=(suspendedauction-1),auctions=(auctions+1)");
			}
		}
		else
		{
			$sql="update PHPAUCTIONPROPLUS_auctions set suspended=1 WHERE id='$id'";
/* Update column suspendedauction,auctions,closedauctions   in table PHPAUCTIONPROPLUS_counters */
			if ($closed==1){
			$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET suspendedauction=(suspendedauction+1),closedauctions=(closedauctions-1)");
			}
			else if ($closed==0){
			$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET suspendedauction=(suspendedauction+1),auctions=(auctions-1)");
			}
		}
		$res=mysql_query ($sql);

		Header("location: listauctions.php");
		exit;
	}
	

	if(!$action || ($action && $updated)){

		$query = "select a.id, u.nick, a.title, a.starts, a.description,
		c.cat_name, d.description as duration, a.suspended, a.current_bid,
		a.quantity, a.reserve_price, a.location, a.minimum_bid from PHPAUCTIONPROPLUS_auctions
		a, PHPAUCTIONPROPLUS_users u, PHPAUCTIONPROPLUS_categories c, PHPAUCTIONPROPLUS_durations d where u.id = a.user and
		c.cat_id = a.category and d.days = a.duration and a.id=\"$id\"";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}


		$id = mysql_result($result,0,"id");
		$title = stripslashes(mysql_result($result,0,"title"));
		$nick = mysql_result($result,0,"nick");
		$tmp_date = mysql_result($result,0,"starts");
		$duration = mysql_result($result,0,"duration");
		$category = mysql_result($result,0,"cat_name");
		$description = stripslashes(mysql_result($result,0,"description"));
		$suspended = mysql_result($result,0,"suspended");
		$current_bid = mysql_result($result,0,"current_bid");
		$min_bid = mysql_result($result,0,"minimum_bid");
		$quantity = mysql_result($result,0,"quantity");
		$reserve_price = mysql_result($result,0,"reserve_price");
		$country = mysql_result($result, 0, "location");

		$country_list="";
		while (list ($code, $descr) = each ($countries))
		{
			$country_list .= "<option value=\"$code\"";
			if ($code == $country)
			{
				$country_list .= " selected";
			}
			$country_list .= ">$descr</option>\n";
		};
		
		$day = substr($tmp_date,6,2);
		$month = substr($tmp_date,4,2);
		$year = substr($tmp_date,0,4);
		$date = "$day/$month/$year";

	}

	require('./header.php');
	require('../includes/styles.inc.php'); 
?> <BR> 
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
	<TR> 
		<TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
			<?
			if($suspended > 0)
			{
				print $MSG_322;
			}
			else
			{
				print $MSG_321;
			}
		?>
			</B></FONT></TD>
	</TR>
	<TR> 
		<TD BGCOLOR="#FFFFFF">
			<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF" CELLSPACING="0">
				<TR> 
					<TD> 
						<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
							<TR> 
								<TD ALIGN=CENTER COLSPAN=5> <BR>
									<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"> 
									<B> </B> </FONT> <BR>
								</TD>
							</TR>
							<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5">
								<?
if($updated){
			print "<TR>
  					<TD>
  					</TD>
  					<TD WIDTH=486>
					<FONT FACE=\"Verdana,Arial, Helvetica\" SIZE=2 COLOR=red>";
					if($updated) print "Auctions data updated";					
					print "</TD>
					</TR>";
}
?>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										<? print "$MSG_312"; ?>
									</TD>
									<TD WIDTH="486"> 
										<?print $title; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_313"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $nick; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_314"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $date; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204"  VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_315"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $duration; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204"  VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_316"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $category; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_317"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $description; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_014"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $countries[$country]; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_318"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $current_bid; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_327"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $min_bid; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_319"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $quantity; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_320"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $reserve_price; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_300"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? 
											if($suspended == 0)
												print "$MSG_029";
											else
												print "$MSG_030";

										?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204">&nbsp;</TD>
									<TD WIDTH="486"> 
										<? print $err_font; ?>
										<?
											if($suspended > 0)
											{
												print $MSG_324;
												$mode = "activate";
											}
											else
											{
												print $MSG_323;
												$mode = "suspend";
											}
										?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204">&nbsp;</TD>
									<TD WIDTH="486"> 
										<FORM NAME=details ACTION="excludeauction.php" METHOD="POST">
											<INPUT TYPE="hidden" NAME="id" VALUE="<? echo $id; ?>">
											<INPUT TYPE="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
											<INPUT TYPE="hidden" NAME="action" VALUE="Delete">
											<INPUT TYPE="hidden" NAME="mode" VALUE="<? print $mode; ?>">
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_030; ?>">
										</FORM>
									</TD>
								</TR>
							</TABLE>
							<BR>
							<BR>
							<CENTER>
								<FONT FACE="Tahoma, Arial" SIZE="2"><A HREF="admin.php">Admin 
								home</A> | <FONT FACE="Tahoma, Arial" SIZE="2"><A HREF="listauctions.php?offset=<? print $offset; ?>">Auctions 
								list</A></FONT> </FONT>
							</CENTER>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
<!-- Closing external table (header.php) -->
<? include "footer.php"; ?>
