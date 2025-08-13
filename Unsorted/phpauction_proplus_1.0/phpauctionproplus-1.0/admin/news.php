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
		<TD ALIGN=CENTER><FONT COLOR=#FFFFFF FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
			<? print $MSG_516; ?>
			</B></FONT></TD>
	</TR>
	<TR> 
		<TD> 
			<TABLE WIDTH=650 CELPADDING=0 CELLSPACING=1 BORDER=0 ALIGN="CENTER" CELLPADDING="3">
				<TR> 
					<TD ALIGN=center COLSPAN=5 BGCOLOR=#EEEEEE>
						<B><A HREF="addnew.php"> 
						<? print $std_font.$MSG_518; ?>
						</A></B>
					</TD>
				</TR>
				<?
		$query = "select count(id) as news from PHPAUCTIONPROPLUS_news";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_news = mysql_result($result,0,"news");
		print "<TR BGCOLOR=#FFFFFF><TD COLSPAN=5><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2><B>
				$num_news $MSG_517</B></TD></TR>";
	?>
				<TR BGCOLOR="#999999"> 
					<TD ALIGN=CENTER WIDTH=20%> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
						<B> 
						<? print $MSG_314; ?>
						</B> </FONT> </TD>
					<TD ALIGN=left WIDTH=60%> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
						<B> 
						<? print $MSG_312; ?>
						</B> </FONT> </TD>
					<TD ALIGN=LEFT> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
						<B> 
						<? print $MSG_297; ?>
						</B> </FONT> </TD>
				<TR> 
					<?
		$query = "select * from PHPAUCTIONPROPLUS_news order by new_date limit $offset, $limit";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_news2 = mysql_num_rows($result);
		$i = 0;
		$bgcolor = "#FFFFFF";
		while($i < $num_news2){

			if($bgcolor == "#FFFFFF"){
				$bgcolor = "#EEEEEE";
			}else{
				$bgcolor = "#FFFFFF";
			}

			$id = mysql_result($result,$i,"id");
			$title = 	stripslashes(mysql_result($result,$i,"title"));
			$tmp_date = mysql_result($result,$i,"new_date");
			$suspended = mysql_result($result,$i,"suspended");
			$day = substr($tmp_date,6,2);
			$month = substr($tmp_date,4,2);
			$year = substr($tmp_date,0,4);
			$date = "$day/$month/$year";
			
			print "<TR BGCOLOR=$bgcolor>
					<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						$month/$day/$year
						</FONT>
						</TD>
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
	
						<TD ALIGN=LEFT>
						<A HREF=\"editnew.php?id=$id&offset=$offset\" class=\"nounderlined\">$MSG_298</A><BR>
						<A HREF=\"deletenew.php?id=$id&offset=$offset\" class=\"nounderlined\">$MSG_299</A>
						<BR>
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
		$num_pages = ceil($num_news / $limit);
		$i = 0;
		while($i < $num_pages ){

			$of = ($i * $limit);

			if($of != $offset){
				print "<A HREF=\"news.php?offset=$of\" CLASS=\"navigation\">".($i + 1)."</A>";
				if($i != $num_pages) print " | ";
			}else{
				print $i + 1;
				if($i != $num_pages) print " | ";
			}

			$i++;
		}
		print "</SPAN></TD></TR>";



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
</TABLE>

<? include "./footer.php"; ?>
