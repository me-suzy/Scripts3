<?
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


	
<TABLE WIDTH=650 CELPADDING=0 CELLSPACING=1 BORDER=0 ALIGN="CENTER" CELLPADDING="3">
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4">
		<B><? print $MSG_516; ?></B>
		</font>
		<BR>
	 </TD>
	</TR>	
	<TR>
	 <TD ALIGN=center COLSPAN=5 BGCOLOR=#EEEEEE>
		<BR>
		<B><A HREF="addnew.php"><? print $std_font.$MSG_518; ?></A></B>
		<BR>
	 </TD>
	</TR>	
	<?
		$query = "select count(id) as news from PHPAUCTION_news";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_news = mysql_result($result,0,"news");
		print "<TR><TD COLSPAN=5><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2><B>
				$num_news $MSG_517</B></TD></TR>";
	?>
	<TR BGCOLOR="#999999"> 
		<TD ALIGN=CENTER width=20%> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
			<B> 
			<? print $MSG_314; ?>
			</B> </FONT> </TD>
		<TD ALIGN=left width=60%> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
			<B> 
			<? print $MSG_312; ?>
			</B> </FONT> </TD>
		<TD ALIGN=LEFT> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"> 
			<B> 
			<? print $MSG_297; ?>
			</B> </FONT> </TD>
	  <TR>

	  <?
		$query = "select * from PHPAUCTION_news order by new_date limit $offset, $limit";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_auctions = mysql_num_rows($result);
		$i = 0;
		$bgcolor = "#FFFFFF";
		while($i < $num_auctions){

			if($bgcolor == "#FFFFFF"){
				$bgcolor = "#EEEEEE";
			}else{
				$bgcolor = "#FFFFFF";
			}

			$id = mysql_result($result,$i,"id");
			$title = mysql_result($result,$i,"title");
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

		print "</TABLE></FONT><BR>";



		//-- Build navigation line

		print "<SPAN CLASS=\"navigation\">";
		$num_pages = ceil($num_usrs / $limit);
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
		print "</SPAN>";



	  ?>
	  <BR>
	  <BR>
	  <CENTER>
		<A HREF="admin.php" CLASS="links">Admin Home</A>
		</CENTER>
	  <BR>

</TABLE>

  
  
  <? include "./footer.php"; ?>
