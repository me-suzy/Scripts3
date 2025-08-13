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

        if ($act == "delete") {
		$query = "DELETE FROM PHPAUCTIONPROPLUS_help WHERE topic = '" . $topic . "';";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			include "./footer.php";
			exit;
		}
	}
	
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
					<? print $MSG_912; ?>
					</B></FONT></TD>
			</TR>
			<TR> 
				<TD> 
					<TABLE WIDTH=650 CELLPADDING=3 CELLSPACING=1 BORDER=0 ALIGN="CENTER">
						<?
		$query = "select count(topic) as topics from PHPAUCTIONPROPLUS_help";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			exit;
		}
		if (mysql_num_rows($result)) {
			$num_usrs = mysql_result($result,0,"topics");
		} else {
			$num_usrs = 0;
		}
		print "<TR BGCOLOR=#FFFFFF><TD COLSPAN=3><FONT FACE=\"Verdana, Arial,Helvetica, sans-serif\" SIZE=2><B>
				$num_usrs $MSG_913</B></TD></TR>";
	?>
						<TR BGCOLOR="#999999"> 
							<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
								<B> 
								<? print $MSG_914; ?>
								</B> </FONT> </TD>
							<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
								<B> 
								<? print $MSG_915; ?>
								</B> </FONT> </TD>
							<TD ALIGN=LEFT> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
								<B> 
								<? print $MSG_297; ?>
								</B> </FONT> </TD>
						<TR> 
							<?
		$query = "select topic,helptext from PHPAUCTIONPROPLUS_help order by topic limit $offset, $limit";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_topics = mysql_num_rows($result);
		$i = 0;
		$bgcolor = "#FFFFFF";
		while($i < $num_topics){

			if($bgcolor == "#FFFFFF"){
				$bgcolor = "#EEEEEE";
			}else{
				$bgcolor = "#FFFFFF";
			}

			$topic = mysql_result($result,$i,"topic");
			$helptext = mysql_result($result,$i,"helptext");

			print "<TR BGCOLOR=$bgcolor>
					<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						$topic
						</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						$helptext
						</FONT>
						</TD>
						<TD ALIGN=LEFT>
						<A HREF=\"addhelp.php?act=edit&topic=$topic&offset=$offset\" class=\"nounderlined\">$MSG_298</A><BR>
						<A HREF=\"listhelp.php?act=delete&topic=$topic&offset=$offset\" class=\"nounderlined\">$MSG_299</A><BR>
						<TR>";

			$i++;
		}
		print "<TR BGCOLOR=#FFFFFF><TD COLSPAN=3><BR><CENTER><A HREF=\"addhelp.php\"><FONT FACE=Verdana,Tahoma SIZE=2>$MSG_917</FONT></A></CENTER><BR>";
		print "</TABLE></FONT>
			   </TD></TR></TABLE>";



		//-- Build navigation line
		print "<TABLE WIDTH=600 BORDER=0 CELLPADDING=4 CELLSPACING=0 ALIGN=CENTER>
			   <TR ALIGN=CENTER BGCOLOR=#FFFFFF>
			   <TD COLSPAN=2>";		

		print "<SPAN CLASS=\"navigation\">";
		$num_pages = ceil($num_topics / $limit);
		$i = 0;
		while($i < $num_pages ){

			$of = ($i * $limit);

			if($of != $offset){
				print "<A HREF=\"listhelp.php?offset=$of\" CLASS=\"navigation\">".($i + 1)."</A>";
				if($i != $num_pages) print " | ";
			}else{
				print $i + 1;
				if($i != $num_pages) print " | ";
			}

			$i++;
		}
		print "</SPAN></TR></TABLE>";



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
		</TABLE></TABLE>	
<? include "./footer.php"; ?>
