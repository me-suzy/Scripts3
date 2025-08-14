<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');
  
   if (!$authenticated) {
	Header("Location: login.php");
   }	
	
	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;
	
	include "./header.php";

        if ($act == "delete") {
		$query = "DELETE FROM ".$dbfix."_help WHERE topic = '" . $topic . "';";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			include "./footer.php";
			exit;
		}
	}
	
?>

<HTML>
<HEAD>

<TITLE></TITLE>


<STYLE type="text/css">
<!--
.unnamed1 {  font: 10pt Tahoma, Arial; color: #32972d; text-decoration: none}
-->
</STYLE>

<?    require('../includes/styles.inc.php'); ?>

</HEAD>

  
<BODY bgcolor="#FFFFFF">
<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
<TR>
<TD>
	<TABLE WIDTH=100% CELLPADDING=0 CELLSPACING=5 BORDER=0>
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<B><? print $tlt_font.$MSG_912; ?></B>
		<BR>
	 </TD>
	</TR>	
	<?
		$query = "select count(topic) as topics from ".$dbfix."_help";
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
	<TR BGCOLOR="#32972d">
		<TD ALIGN=CENTER>
			<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF">
			<B><? print $MSG_914; ?></B>
			</FONT>
		</TD>
		<TD ALIGN=CENTER>
			<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF">
			<B><? print $MSG_915; ?></B>
			</FONT>
		</TD>
		<TD ALIGN=LEFT>
			<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF">
			<B><? print $MSG_297; ?></B>
			</FONT>
		</TD>
	  <TR>

	  <?
		$query = "select topic,helptext from ".$dbfix."_help order by topic limit $offset, $limit";
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
		print "<TR><TD COLSPAN=3><BR><CENTER><A HREF=\"addhelp.php\">$MSG_917</A></CENTER><BR>";
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
</TD>
</TR>
</TABLE>
  
  <!-- Closing external table (header.php) -->
  </TD>
  </TR>
</TABLE>
  
  
  <? include "./footer.php"; ?>
  
  
</BODY>
</HTML>
