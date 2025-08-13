<?#//v.1.0.0


#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "loggedin.inc.php";


   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');
	
	
	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;
	
	
	include "./header.php";
?>
<STYLE type="text/css">
<!--
.unnamed1 {  font: 10pt Tahoma, Arial; color: #000066; text-decoration: none}
-->
</STYLE>

<?    require('../includes/styles.inc.php'); ?>

	
<BR>
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
	<TR> 
		<TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
			<? print $MSG_067; ?>
			</B></FONT></TD>
	</TR>
	<TR> 
		<TD> 
			<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=1 BORDER=0 ALIGN="CENTER" CELLPADDING="3">
				<?
		$query = "select count(id) as auctions from PHPAUCTIONPROPLUS_auctions";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_auctions = mysql_result($result,0,"auctions");
		print "<TR BGCOLOR=#FFFFFF><TD COLSPAN=7><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2><B>
				$num_auctions $MSG_311</B></TD></TR>";
	?>
				<TR BGCOLOR="#999999"> 
					<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
						<B> 
						<? print $MSG_312; ?>
						</B> </FONT> </TD>
					<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
						<B> 
						<? print $MSG_313; ?>
						</B> </FONT> </TD>
					<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
						<B> 
						<? print $MSG_314; ?>
						</B> </FONT> </TD>
					<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
						<B> 
						<? print $MSG_315; ?>
						</B> </FONT> </TD>
					<TD ALIGN=LEFT> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
						<B> 
						<? print $MSG_316; ?>
						</B> </FONT> </TD>
					<TD ALIGN=LEFT> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
						<B> 
						<? print $MSG_317; ?>
						</B> </FONT> </TD>
					<TD ALIGN=LEFT> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
						<B> 
						<? print $MSG_297; ?>
						</B> </FONT> </TD>
				<TR> 
					<?
		$query = "select a.id, u.nick, a.title, a.starts, a.description, c.cat_name, d.description as duration, a.suspended from PHPAUCTIONPROPLUS_auctions a, PHPAUCTIONPROPLUS_users u, PHPAUCTIONPROPLUS_categories c, PHPAUCTIONPROPLUS_durations d where u.id = a.user and c.cat_id = a.category and d.days = a.duration order by nick limit $offset, $limit";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_auction = mysql_num_rows($result);
		$i = 0;
		$bgcolor = "#FFFFFF";
		while($i < $num_auction){

			if($bgcolor == "#FFFFFF"){
				$bgcolor = "#EEEEEE";
			}else{
				$bgcolor = "#FFFFFF";
			}

			$id = mysql_result($result,$i,"id");
			$title = stripslashes(mysql_result($result,$i,"title"));
			$nick = mysql_result($result,$i,"nick");
			$tmp_date = mysql_result($result,$i,"starts");
			$duration = mysql_result($result,$i,"duration");
			$category = mysql_result($result,$i,"cat_name");
			$description = stripslashes(mysql_result($result,$i,"description"));
			$suspended = mysql_result($result,$i,"suspended");
			$day = substr($tmp_date,6,2);
			$month = substr($tmp_date,4,2);
			$year = substr($tmp_date,0,4);
			$date = "$day/$month/$year";
			
			print "<TR BGCOLOR=$bgcolor>
					<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>";
						if($suspended == 1)
						{
							print "<FONT COLOR=red><B>$title</B></FONT>";
						}
						else
						{
							print $title;
						}
						print "</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						".$nick."
						</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						".$date."
						</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						$duration	
						</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						$category	
						</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						$description	
						</FONT>
						</TD>
						<TD ALIGN=LEFT>
						<A HREF=\"editauction.php?id=$id&offset=$offset\" class=\"nounderlined\">$MSG_298</A><BR>
						<A HREF=\"deleteauction.php?id=$id&offset=$offset\" class=\"nounderlined\">$MSG_299</A><BR>
						<A HREF=\"excludeauction.php?id=$id&offset=$offset\" class=\"nounderlined\">";
						if($suspended == 0)
						{
							print $MSG_300;
						}
						else
						{
							print $MSG_310;
						}
						print "</A><BR>
						</TD>
						<TR>";

			$i++;
		}

		print "</TABLE></FONT><BR>
			   </TD></TR></TABLE>";



		//-- Build navigation line
		print "<TABLE WIDTH=600 BORDER=0 CELLPADDING=4 CELLSPACING=0 ALIGN=CENTER>
			   <TR ALIGN=CENTER BGCOLOR=#FFFFFF>
			   <TD COLSPAN=2>";		
		print "<SPAN CLASS=\"navigation\">";
		$num_pages = ceil($num_auctions / $limit);
		$i = 0;
		while($i < $num_pages ){

			$of = ($i * $limit);

			if($of != $offset){
				print "<A HREF=\"listauctions.php?offset=$of\" CLASS=\"navigation\">".($i + 1)."</A>";
				if($i != $num_pages) print " | ";
			}else{
				print $i + 1;
				if($i != $num_pages) print " | ";
			}

			$i++;
		}
		print "</SPAN></TR></TD>";



	  ?>
	  	<TR BGCOLOR=#FFFFFF ALIGN=CENTER>
	  	<TD>
		<BR>
		<BR>
		<CENTER>
			<A HREF="admin.php" CLASS="links">Admin Home</A> 
		</CENTER>
		<BR>
		</TD>
		</TR>
		</TABLE>


<? include "./footer.php"; ?>
