<?#//v.1.0.1
	include "loggedin.inc.php";

	#///////////////////////////////////////////////////////
	#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
	#//  For Source code for the GPL version go to        //  
	#//  http://phpauction.org and download               //
	#///////////////////////////////////////////////////////

   	require('../includes/messages.inc.php');
   	require('../includes/config.inc.php');
	
	#// Retrieve current counters
	$query = "SELECT * FROM PHPAUCTION_counters";
	$res = @mysql_query($query);
	if(!$res)
	{
		print "Error: $query<BR>".mysql_error();
		exit;
	}
	elseif(mysql_num_rows($res) > 0)
	{
		$COUNTERS = mysql_fetch_array($res);
	}
			
	require("./header.php");
	require('../includes/styles.inc.php'); 


?>
  
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>
<CENTER>
<BR>
<FORM NAME=conf ACTION=checkupdates.php METHOD=POST>
	<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB">
		<TR> 
			<TD ALIGN=CENTER><FONT color=#ffffff FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
				<? print $MSG_1030; ?>
				</B></FONT><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4" COLOR=#FFFFFF><B> 
				</B></FONT>
			</TD>
		</TR>
		<TR> 
			<TD>
				<TABLE WIDTH=100% CELLPADDING=2 ALIGN="CENTER" BGCOLOR="#FFFFFF">
					<TR VALIGN="TOP"> 
						<TD COLSPAN=3><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
							<?=$MSG_1031;?>
							</FONT></TD>
					</TR>
					<TR VALIGN="TOP"> 
						<TD COLSPAN=3>
							<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
							<BR><BR>
							USERS<BR>
							-----<BR>
							<?
								$query = "SELECT count(id) as COUNTER from PHPAUCTION_users WHERE suspended=0";
								$res = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								$USERS = mysql_result($res,0,"COUNTER");
								
								#// Update counters table
								$query = "UPDATE PHPAUCTION_counters set users=$USERS";
								$res_ = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								else
								{
									print "<I>active users</I> counter updated. New value is: $USERS<BR>";
								}
							?>

							<?
								$query = "SELECT count(id) as COUNTER from PHPAUCTION_users WHERE suspended<>0";
								$res = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								$USERS = mysql_result($res,0,"COUNTER");
								
								#// Update counters table
								$query = "UPDATE PHPAUCTION_counters set inactiveusers=$USERS";
								$res_ = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								else
								{
									print "<I>inactive users</I> counter updated. New value is: $USERS<BR>";
								}
							?>
							<BR>
							AUCTIONS<BR>
							--------<BR>
							<?
								$query = "SELECT count(id) as COUNTER from PHPAUCTION_auctions WHERE closed=0 and suspended=0";
								$res = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								$AUCTIONS = mysql_result($res,0,"COUNTER");
								
								#// Update counters table
								$query = "UPDATE PHPAUCTION_counters set auctions=$AUCTIONS";
								$res_ = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								else
								{
									print "<I>active auctions</I> counter updated. New value is: $AUCTIONS<BR>";
								}
							?>

							<?
								$query = "SELECT count(id) as COUNTER from PHPAUCTION_auctions WHERE closed<>0";
								$res = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								$AUCTIONS = mysql_result($res,0,"COUNTER");
								
								#// Update counters table
								$query = "UPDATE PHPAUCTION_counters set closedauctions=$AUCTIONS";
								$res_ = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								else
								{
									print "<I>closed auctions</I> counter updated. New value is: $AUCTIONS<BR>";
								}
							?>

							<?
								$query = "SELECT count(id) as COUNTER from PHPAUCTION_auctions WHERE closed=0 and suspended<>0";
								$res = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								$AUCTIONS = mysql_result($res,0,"COUNTER");
								
								#// Update counters table
								$query = "UPDATE PHPAUCTION_counters set suspendedauctions=$AUCTIONS";
								$res_ = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								else
								{
									print "<I>suspended auctions</I> counter updated. New value is: $AUCTIONS<BR>";
								}
							?>
							
							<BR>
							BIDS<BR>
							----<BR>
							<?
								$query = "SELECT
										  a.bid,
										  a.auction,
										  b.id
										  FROM
										  PHPAUCTION_bids a, PHPAUCTION_auctions b
										  WHERE
										  a.auction=b.id AND
										  b.closed=0 AND b.suspended=0";
								$res = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								$BIDS = mysql_num_rows($res);
								
								#// Update counters table
								$query = "UPDATE PHPAUCTION_counters set bids=$BIDS";
								$res_ = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								else
								{
									print "<I>bids</I> counter updated. New value is: $BIDS<BR>";
								}
							?>
							<BR>
							CATEGORIES<BR>
							----------<BR>
							<?
								@mysql_query("UPDATE PHPAUCTION_categories set counter=0, sub_counter=0");
								print "Reset all categories counters<BR>";
								$query = "SELECT 
										  a.cat_id, a.cat_name, a.parent_id, a.counter,a.sub_counter,
										  b.id, b.category
										  FROM 
										  PHPAUCTION_categories a, PHPAUCTION_auctions b
										  WHERE 
										  a.cat_id=b.category
										  AND
										  b.closed=0 AND b.suspended=0";
								$res = @mysql_query($query);
								if(!$res)
								{
									print "Error: $query<BR>".mysql_error();
									exit;
								}
								while($row = mysql_fetch_array($res))
								{
									

									$cat_id = $row[cat_id];
									
			do 
			{
				// update counter for this category
				$query = "SELECT * FROM PHPAUCTION_categories WHERE cat_id=\"$cat_id\"";
				$result = mysql_query($query);
			   $CAT = mysql_fetch_array($result);
				if($result)
				{
					if (mysql_num_rows($result) > 0)
					{
						$R_parent_id = $CAT[parent_id];
						$R_cat_id = $CAT[cat_id];
						$R_sub_name = $CAT[cat_name];
						$R_counter = intval(mysql_result($result,0,"counter"));
						$R_sub_counter = intval(mysql_result($result,0,"sub_counter"));

						$R_sub_counter++;
						if ( $cat_id == $root_cat )
							++$R_counter;

						if($R_counter < 0) $R_counter = 0;
						if($R_sub_counter < 0) $R_sub_counter = 0;

						$query = "UPDATE PHPAUCTION_categories SET counter='$R_counter', sub_counter='$R_sub_counter' WHERE cat_id=\"$cat_id\"";
						@mysql_query($query);
						
						$cat_id = $R_parent_id;
						print "Counter updated for category <I>$R_sub_name</I>. New value is: $R_sub_counter<BR>\n";
                					}
				}
			} 
			while ($cat_id!=0);	
		}
								
	?>
							</FONT></TD>
					</TR>
				</TABLE>
			</TD>
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
