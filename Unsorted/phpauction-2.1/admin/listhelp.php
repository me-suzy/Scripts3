<?
	include "loggedin.inc.php";

   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');
  
	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;
	
	include "./header.php";

        if ($act == "delete") {
		$query = "DELETE FROM PHPAUCTION_help WHERE topic = '" . $topic . "';";
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

<TR>
<TD>
	    <TABLE WIDTH=650 CELLPADDING=3 CELLSPACING=1 BORDER=0 ALIGN="CENTER">
			<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4">
		<B><? print $MSG_912; ?></B>
		</FONT><BR>
	 </TD>
	</TR>	
	<?
		$query = "select count(topic) as topics from PHPAUCTION_help";
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
		print "<TR><TD COLSPAN=3><FONT FACE=\"Verdana, Arial,Helvetica, sans-serif\" SIZE=2><B>
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
		$query = "select topic,helptext from PHPAUCTION_help order by topic limit $offset, $limit";
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
		print "<TR><TD COLSPAN=3><BR><CENTER><A HREF=\"addhelp.php\"><FONT FACE=Verdana,Tahoma SIZE=2>$MSG_917</FONT></A></CENTER><BR>";
		print "</TABLE></FONT>";



		//-- Build navigation line

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