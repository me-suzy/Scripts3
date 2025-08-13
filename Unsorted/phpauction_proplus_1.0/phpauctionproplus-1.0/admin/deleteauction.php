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
	
	if($action)
	{
		if(!$ERR)
		{
			//-- Get category
			$query = "select category from PHPAUCTIONPROPLUS_auctions where id='$id'";
			$res__ = mysql_query($query);
			if(!$res__)
			{
				print $ERR_001." $query<BR>".mysql_error();
				exit;
			}
			else
			{
				$cat_id = mysql_result($res__,0,"category");
			}
			
			//-- delete auction
			$sql="delete from PHPAUCTIONPROPLUS_auctions WHERE id='$id'";
			$res=mysql_query ($sql);
			
			//-- Update counters
			$query = mysql_query("update PHPAUCTIONPROPLUS_counters set auctions=(auctions-1)");
			
			//-- delete bids
			$query = "SELECT count(auction) as BIDS from PHPAUCTIONPROPLUS_bids WHERE auction='$id'";
			$res = mysql_query($query);
			if(!$res)
			{
				print "ERROR: $query<BR>".mysql_error();
				exit;
			}
			if(mysql_num_rows($res) > 0)
			{
				$BIDS = mysql_result($res,0,"BIDS");
				$sql="delete from PHPAUCTIONPROPLUS_bids WHERE aution='$id'";
				$res=mysql_query ($sql);
			}
			else
			{
				$BIDS = 0;
			}
			
			//-- Update counters
			$query = mysql_query("update PHPAUCTIONPRO_bids set bids=(bids-$BIDS)");
			
			// update "categories" table - for counters
			$root_cat = $cat_id;
			do 
			{
				// update counter for this category
				$query = "SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=\"$cat_id\"";
				$result = mysql_query($query);
			
				if($result)
				{
					if (mysql_num_rows($result)>0)
					{
						$R_parent_id = mysql_result($result,0,"parent_id");
						$R_cat_id = mysql_result($result,0,"cat_id");
						$R_counter = intval(mysql_result($result,0,"counter"));
						$R_sub_counter = intval(mysql_result($result,0,"sub_counter"));

						$R_sub_counter--;
						if ( $cat_id == $root_cat )
							--$R_counter;

						if($R_counter < 0) $R_counter = 0;
						if($R_sub_counter < 0) $R_sub_counter = 0;

						$query = "UPDATE PHPAUCTIONPROPLUS_categories SET counter='$R_counter', sub_counter='$R_sub_counter' WHERE cat_id=\"$cat_id\"";
						@mysql_query($query);
						
						$cat_id = $R_parent_id;
					}
				}
			} 
			while ($cat_id!=0);	
			
			
			Header("location: listauctions.php?offset=$offset");
		}

	}
	

	if(!$action){

		$query = "select a.id, u.nick, a.title, a.starts, a.description,
		c.cat_name, d.description as duration, a.suspended, a.current_bid,
		a.quantity, a.reserve_price, a.location from PHPAUCTIONPROPLUS_auctions
		a, PHPAUCTIONPROPLUS_users u, PHPAUCTIONPROPLUS_categories c, PHPAUCTIONPROPLUS_durations d where u.id = a.user and
		c.cat_id = a.category and d.days = a.duration and a.id=\"$id\"";		
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}

		$id = mysql_result($result,0,"id");
		$title = mysql_result($result,0,"title");
		$nick = mysql_result($result,0,"nick");
		$tmp_date = mysql_result($result,0,"starts");
		$duration = mysql_result($result,0,"duration");
		$category = mysql_result($result,0,"cat_name");
		$description = mysql_result($result,0,"description");
		$suspended = mysql_result($result,0,"suspended");
		$current_bid = mysql_result($result,0,"current_bid");
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

	include "./header.php";
	require('../includes/styles.inc.php'); 
?> <BR> 
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
	<TR> 
		<TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
			<? print $MSG_325; ?>
			</B></FONT></TD>
	</TR>
	<TR> 
		<TD> 
			<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
				<TR> 
					<TD> 
						<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
							<TR> 
								<TD ALIGN=CENTER COLSPAN=5> <BR>
									<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"> 
									<B> </B> </FONT> <BR>
								</TD>
							</TR>
							<TABLE WIDTH="700" BORDER="0" CELLPADDING="5">
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
		print $std_font; 
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
										<? print $err_font.$MSG_326; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204">&nbsp;</TD>
									<TD WIDTH="486"> 
										<FORM NAME=details ACTION="deleteauction.php" METHOD="POST">
											<INPUT TYPE="hidden" NAME="id" VALUE="<? echo $id; ?>">
											<INPUT TYPE="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
											<INPUT TYPE="hidden" NAME="action" VALUE="Delete">
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_008; ?>">
										</FORM>
									</TD>
							</TABLE>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
			
		</TD>
	</TR>
	<TR>
		<TD BGCOLOR="#FFFFFF">
		</TD>
	</TR>
</TABLE>
<!-- Closing external table (header.php) -->
<? include "./footer.php"; ?>
