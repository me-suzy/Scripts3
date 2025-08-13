<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	#//include "lib/check-key.php";

	#// Update users table
	$res = @mysql_query("UPDATE PHPAUCTIONPROPLUS_users SET balance=balance-$FEE WHERE id='$user_id'");
	
	#// Update counters table
	$query = "UPDATE PHPAUCTIONPROPLUS_counters 
		SET fees=(fees+$FEE), transactions=(transactions+1)";
	$re_ = @mysql_query($query);
	//print $query."<BR>";
	if(!$re_)
	{
		print "ERROR $query<BR>".mysql_error();
		mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
		exit;
	}

	#// Update auctions table
	$query = "UPDATE PHPAUCTIONPROPLUS_auctions SET suspended=0 WHERE id='$TPL_auction_id' ";
	$res = @mysql_query($query);
	if(!$res)
	{
		print "ERROR $query<BR>".mysql_error();
		exit;
	}

	#// Update user's account
	$TODAY = date("Ymd");
	$DESCR = "<A HREF=item.php?id=$TPL_auction_id TARGET=_blank>$MSG_450</A>";
	$query = "INSERT INTO PHPAUCTIONPROPLUS_accounts 
						 VALUES
						 (NULL,
						 '$user_id',
						 '$DESCR',
						 '$TODAY',
						 1,
						 $FEE,
						 $user_balance-$FEE,
						 '$TPL_auction_id ')";
	$res = mysql_query($query);
	if(!$res)
	{
		print "Error: $query<BR>".mysql_error();
	}
?>
<TABLE WIDTH="100%" BGCOLOR="#FFFFFF" BORDER=0>
	<TR> 
		<TD> 
			<CENTER>
				<? print $tlt_font.$MSG_028; ?>
				<P><BR>
					<BR>
					<? print $std_font.$MSG_100.$MSG_101; ?>
					<A HREF="item.php?id=<? print $TPL_auction_id; ?>&mode=1"> 
					<? print $SETTINGS[siteurl]; ?>
					item.php?id= 
					<? print $TPL_auction_id; ?>
					</A> <BR>
					<?=$MSG_451?>
					<?=print_money($FEE)?>
				</P>
				<BR>
				<BR>
			</CENTER>
		</TD>
	</TR>
</TABLE>
