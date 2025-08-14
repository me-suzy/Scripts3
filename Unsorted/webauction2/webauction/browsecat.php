<SCRIPT Language=PHP>

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

  
  if($cat == "" || $cat == "all"){
  	Header("Location: ./allcats.php");
  }

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
	
	$query = "select name from ".$dbfix."_categories where id=\"$cat\"";
	$result = mysql_query($query);
	if(!$result){
		print $ERR_001;
		exit;
	}
	$cat_name = mysql_result($result,0,"name");
		
	$query = "select id, name from ".$dbfix."_subcategories where category=\"$cat\" order by name";
	$result = mysql_query($query);
	if(!$result){
		print $ERR_001;
		exit;
	}
	
	$num_subs = mysql_num_rows($result);
	print "<CENTER>";
	print "<TABLE WIDTH=\"600\" BORDER=\"0\" CELLPADDING=\"4\" CELLSPACING=\"0\">";
	print "<TR>";
	print "<TD COLSPAN=5>";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">";
	print "$MSG_165 $cat_name";
	print "<BR><IMG SRC=\"images/linea.gif\" WIDTH=\"750\" HEIGHT=\"6\">";
	print "</TD>";
	print "</TR>";
	$i = 0;
	
	while($i < $num_subs){
		print "<TR VALIGN=\"top\">";

//-- Column #1
		print "<TD WIDTH=\"33%\">";

		$sub_id = mysql_result($result,$i,"id");
		$sub_name = mysql_result($result,$i,"name");
		
		$query = "select id from ".$dbfix."_auctions where subcategory=\"$sub_id\"  AND  closed=\"0\"";
		$result_count = mysql_query($query);
		if(!$result_count){
			print $ERR_001;
			exit;
		}
		$num_auctions = mysql_num_rows($result_count);
		
		$sub_url = "./browsesub.php?cat=$cat&sub=$sub_id";
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
		print "<A HREF=\"$sub_url\">$sub_name</A>";
		
		if($num_auctions){
			print "<FONT FACE=\"Helvetica,Arial\" SIZE=\"2\">";
			print " ($num_auctions)";
			print "</FONT>";
		}

		print "<BR>";
		if($i < ($num_subs -1)){
			$i++;
			$INCREMENTED = 1;
		}else{
			$INCREMENTED = 0;
		}
		print "</TD>";

//-- Column #2
		print "<TD WIDTH=\"33%\">";
		
		if($INCREMENTED){
			$sub_id = mysql_result($result,$i,"id");
			$sub_name = mysql_result($result,$i,"name");
		
			$query = "select id from ".$dbfix."_auctions where subcategory=\"$sub_id\"  AND  closed=\"0\"";
			$result_count = mysql_query($query);
			if(!$result_count){
				print $ERR_001;
				exit;
			}
			$num_auctions = mysql_num_rows($result_count);
		
			$sub_url = "./browsesub.php?cat=$cat&sub=$sub_id";
			print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>";
			print "<A HREF=\"$sub_url\">$sub_name</A>";
		
			if($num_auctions){
				print "<FONT FACE=\"Helvetica,Arial\" SIZE=\"2\">";
				print " ($num_auctions)";
				print "</FONT>";
			}
		}	
		print "<BR>";
		if($i < ($num_subs -1)){
			$i++;
			$INCREMETED = 1;
		}else{
			$INCREMENTED = 0;
		}
		print "</TD>";

//-- Column #3
		print "<TD WIDTH=\"33%\">";

		if($INCREMENTED){
			$sub_id = mysql_result($result,$i,"id");
			$sub_name = mysql_result($result,$i,"name");
			
			$query = "select id from ".$dbfix."_auctions where subcategory=\"$sub_id\"  AND  closed=\"0\"";
			$result_count = mysql_query($query);
			if(!$result_count){
				print $ERR_001;
				exit;
			}
			$num_auctions = mysql_num_rows($result_count);
			
			$sub_url = "./browsesub.php?cat=$cat&sub=$sub_id";
			print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>";
			print "<A HREF=\"$sub_url\">$sub_name</A>";
		
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
</CENTER>
</BODY>
</HTML>
