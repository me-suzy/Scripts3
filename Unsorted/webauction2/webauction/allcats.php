<SCRIPT Language=PHP>

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	// Include messages file	
   require('includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('includes/config.inc.php');
	
</SCRIPT>

<HTML>
<HEAD>
<TITLE></TITLE>
</HEAD>

<BODY BGCOLOR="#FFFFFF" LINK="#000000" VLINK="#000000" ALINK="#000000">

<? require('header.php'); ?>
<BR>



<?
	//--

	$query = "select id, name from ".$dbfix."_categories order by name";
	$result = mysql_query($query);
	if(!$result){
		print $ERR_001;
		exit;
	}
	
	$num_cats = mysql_num_rows($result);
	print "<CENTER>";
	print "<TABLE WIDTH=\"600\" BORDER=\"0\" CELLPADDING=\"4\" CELLSPACING=\"0\">";
	print "<TR>";
	print "<TD COLSPAN=5>";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">";
	print "$MSG_177";
	print "<BR><IMG SRC=\"images/linea.gif\" WIDTH=\"770\" HEIGHT=\"6\"><BR>";
	print "</TD>";
	print "</TR>";
	
	$i = 0;
	
	while($i < $num_cats){
		print "<TR VALIGN=\"top\">";

//-- Column #1
		print "<TD WIDTH=\"33%\">";

		$cat_id = mysql_result($result,$i,"id");
		$cat_name = mysql_result($result,$i,"name");
		
		$query = "select id from ".$dbfix."_auctions where category=\"$cat_id\"  AND  closed=\"0\"";
		$result_count = mysql_query($query);
		if(!$result_count){
			print $ERR_001;
			exit;
		}
		$num_auctions = mysql_num_rows($result_count);
		
		$sub_url = "./browsecat.php?cat=$cat_id";
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
		print "<A HREF=\"$sub_url\">$cat_name</A>";
		
		if($num_auctions){
			print "<FONT FACE=\"Helvetica,Arial\" SIZE=\"2\">";
			print " ($num_auctions)";
			print "</FONT>";
		}

		print "<BR>";
		if($i < ($num_cats -1)){
			$i++;
			$INCREMENTED = 1;
		}else{
			$INCREMENTED = 0;
		}
		print "</TD>";

//-- Column #2
		print "<TD WIDTH=\"33%\">";
		
		if($INCREMENTED){
			$cat_id = mysql_result($result,$i,"id");
			$cat_name = mysql_result($result,$i,"name");
		
			$query = "select id from ".$dbfix."_auctions where category=\"$cat_id\"  AND  closed=\"0\"";
			$result_count = mysql_query($query);
			if(!$result_count){
				print $ERR_001;
				exit;
			}
			$num_auctions = mysql_num_rows($result_count);
		
			$sub_url = "./browsecat.php?cat=$cat_id";
			print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>";
			print "<A HREF=\"$sub_url\">$cat_name</A>";
		
			if($num_auctions){
				print "<FONT FACE=\"Helvetica,Arial\" SIZE=\"2\">";
				print " ($num_auctions)";
				print "</FONT>";
			}
		}	
		print "<BR>";
		if($i < ($num_cats -1)){
			$i++;
			$INCREMETED = 1;
		}else{
			$INCREMENTED = 0;
		}
		print "</TD>";

//-- Column #3
		print "<TD WIDTH=\"33%\">";

		if($INCREMENTED){
			$cat_id = mysql_result($result,$i,"id");
			$cat_name = mysql_result($result,$i,"name");
			
			$query = "select id from ".$dbfix."_auctions where category=\"$cat_id\"  AND  closed=\"0\"";
			$result_count = mysql_query($query);
			if(!$result_count){
				print $ERR_001;
				exit;
			}
			$num_auctions = mysql_num_rows($result_count);
			
			$sub_url = "./browsecat.php?cat=$cat_id";
			print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>";
			print "<A HREF=\"$sub_url\">$cat_name</A>";
		
			if($num_auctions){
				print "<FONT FACE=\"Helvetica,Arial\" SIZE=\"2\">";
				print " ($num_auctions)";
				print "</FONT>";
			}
		}
		print "<BR>";
		$i++;
		print "</TD>";


 }	
?>
</TABLE>

<BR>
<SCRIPT Language=PHP> require("footer.php"); </SCRIPT>	
</BODY>
</HTML>
