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
		header("Location: listusers.php");
		exit;
	}
	
	if($action)
	{
		$ERR_CODE = 1;
	
		//-- Check if the users has some auction 
		
		$query = "select * from PHPAUCTIONPROPLUS_auctions where user='$id'";
	
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}
		
		$num_auctions = mysql_num_rows($result);
		if($num_auctions > 0)
		{
			
			$ERR = "The user is the SELLER in the following auctions:<BR>";
			$i =  0;
			while($i < $num_auctions){
				$ERR_CODE=2;
				$ERR .= mysql_result($result,$i,"id")."<BR>";
				$i++;
			}
		}

		//-- Check if the user is BIDDER in some auction
		
		$query = "select * from PHPAUCTIONPROPLUS_bids where bidder='$id'";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}
		
		$num_auctions = mysql_num_rows($result);
		if($num_auctions > 0){
			$ERR_CODE=1;
			$ERR = "The user placed a bid in the following auctions:<BR>";
			$i =  0;
			while($i < $num_auctions){
				$ERR .= mysql_result($result,$i,"bidder")."<BR>";
				$i++;
			}
		}

		//-- check if user is suspended or not
		$suspend = mysql_query("select suspended from PHPAUCTIONPROPLUS_users where id=\"$id\"");
		if(!$suspend){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}
		$myrow=mysql_fetch_array($suspend);
		$suspended = $myrow['suspended'];

		if($ERR_CODE==1)
		{

			//-- delete user
			$sql="delete from PHPAUCTIONPROPLUS_users WHERE id='$id'";
			$res=mysql_query ($sql);

			//-- delete user bids
			$decremsql = mysql_query("select * FROM PHPAUCTIONPROPLUS_bids WHERE bidder='$id'");
			$bid_decrem = mysql_num_rows($decremsql);
			$sql="delete from PHPAUCTIONPROPLUS_bids WHERE bidder='$id'";
			$res=mysql_query ($sql);

			//-- delete user's auctions
			$decremsql = mysql_query("select * FROM PHPAUCTIONPROPLUS_auctions WHERE user='$id'");
			$row=mysql_fetch_array($decremsql);

                                // update "categories" table - for counters
                                $cat_id = $row["category"];
                                $root_cat = $cat_id;
                                do
                                {       $query = "SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=\"$cat_id\"";
                                        $result = mysql_query($query);
                                        if ( $result )
                                        {
                                                if ( mysql_num_rows($result)>0 )
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
                                                        if ( !mysql_query($query) )
                                                                errorLogSQL();

                                                        $cat_id = $R_parent_id;
                                                }
                                        }
                                        else { }
                                }
                                while ($cat_id!=0);


			$auction_decrem = mysql_num_rows($decremsql);
			$sql = "delete from PHPAUCTIONPROPLUS_auctions WHERE user='$id'";
			$res=mysql_query ($sql);
			
			//-- Update counters
			if ($suspended == 0)
			{
				$query = mysql_query("update PHPAUCTIONPROPLUS_counters set users=(users-1), bids=(bids-$bid_decrem), auctions=(auctions-$auction_decrem)");
			}
			else if ($suspended == 1)
			{
				$query = mysql_query("update PHPAUCTIONPROPLUS_counters set users=(users-1), inactiveusers=(inactiveusers-1), bids=(bids-$bid_decrem), auctions=(auctions-$auction_decrem)");			
			}
			Header("location: listusers.php?offset=$offset");
		}
		if($ERR_CODE==2){

			//-- delete user
			$sql="delete from PHPAUCTIONPROPLUS_users WHERE id='$id'";
			$res=mysql_query ($sql);
			$suspended = mysql_result ( $res, 0, "suspended" );


			//-- delete user auctions
			$sql="delete from PHPAUCTIONPROPLUS_auctions WHERE user='$id'";
			$res=mysql_query ($sql);
			
			//-- Update counters
			if ($suspended==0){
			$query = mysql_query("update PHPAUCTIONPROPLUS_counters set users=(users-1)");			
			}
			else if ($suspended==1)
			{
			$query = mysql_query("update PHPAUCTIONPROPLUS_counters set inactiveusers=(inactiveusers-1)");			
			}

			Header("location: listusers.php?offset=$offset");
		}	
	}
	

	if(!$action || ($action && $ERR)){

		$query = "select * from PHPAUCTIONPROPLUS_users where id=\"$id\"";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}

		$username = mysql_result($result,0,"name");

		$nick = mysql_result($result,0,"nick");
		$password = mysql_result($result,0,"password");
		$email = mysql_result($result,0,"email");
		$address = mysql_result($result,0,"address");
		
		$country = mysql_result($result,0,"country");
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
		
		$prov = mysql_result($result,0,"country");
		$zip = mysql_result($result,0,"zip");

		$birthdate = mysql_result($result,0,"birthdate");
		$birth_day = substr($birthdate,6,2);
		$birth_month = substr($birthdate,4,2);
		$birth_year = substr($birthdate,0,4);
		$birthdate = "$birth_day/$birth_month/$birth_year";

		$phone = mysql_result($result,0,"phone");
		$suspended = mysql_result($result,0,"suspended");

		$rate_num = mysql_result($result,0,"rate_num");
		$rate_sum = mysql_result($result,0,"rate_sum");
		if ($rate_num) 
		{
			$rate = round($rate_sum / $rate_num);
		}
		else 
		{
			$rate=0;
		}

	}


	include "./header.php";
	require('../includes/styles.inc.php');
?> <BR> 
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
	<TR> 
		<TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B> 
			<? print $MSG_304; ?>
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
									<B> </B> </FONT><BR>
								</TD>
							</TR>
							<TABLE WIDTH="700" BORDER="0" CELLPADDING="5">
								<?
	if($ERR)
	{
?>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
									</TD>
									<TD WIDTH="486"> 
										<? print $err_font.$ERR; ?>
									</TD>
								</TR>
								<? 
	}
?>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_302"; ?>
									</TD>
									<TD WIDTH="486"> 
										<?print $username; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_003"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $nick; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_004"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $password; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204"  VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_303"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $email; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204"  VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_252"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $birthdate; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_009"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $address; ?>
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
										
										<? print "$MSG_012"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $zip; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_013"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $phone; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_222"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? if(!$rate) $rate=0; ?>
										<IMG SRC="../images/estrella_<? echo $rate; ?>.gif"> 
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
										<? print "$err_font $MSG_307"; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204">&nbsp;</TD>
									<TD WIDTH="486"> 
										<FORM NAME=details ACTION="deleteuser.php" METHOD="POST">
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
			<CENTER>
				<FONT FACE="Tahoma, Arial" SIZE="2"><A HREF="admin.php">Admin 
				home</A> | <FONT FACE="Tahoma, Arial" SIZE="2"><A HREF="listusers.php?offset=<? print $offset; ?>">Users 
				list</A></FONT> </FONT> 
			</CENTER>
		</TD>
	</TR>
	<TR> 
		<TD BGCOLOR="#FFFFFF"> </TD>
	</TR>
</TABLE>
<!-- Closing external table (header.php) -->
<? include "./footer.php"; ?>
