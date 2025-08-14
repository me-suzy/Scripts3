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
<CENTER>
<SCRIPT Language=PHP> require("header.php"); </SCRIPT>
<BR>

<?
//--
	print "\n<TABLE WIDTH=\"745\" CELLSPACING=\"1\" CELLPADDING=\"3\" BORDER=\"0\">\n";

	$query = "select name from ".$dbfix."_categories where id=\"$cat\"";
	$result = mysql_query($query);
	if(!$result){
		print $ERR_001;
		exit;
	}
	$cat_name = mysql_result($result,0,"name");
	
	$query = "select name from ".$dbfix."_subcategories where id=\"$sub\"";
	$result = mysql_query($query);
	if(!$result){
		print $ERR_001;
		exit;
	}
	$sub_name = mysql_result($result,0,"name");
	print "<TR>";
	print "<TD COLSPAN=5>";	
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">";
	print "$MSG_165 <A HREF=\"browsecat.php?cat=$cat\">$cat_name</A> > $sub_name<BR>";
	print "</FONT>";
	print "<IMG SRC=\"images/linea.gif\" WIDTH=\"100%\" HEIGHT=\"6\"><BR>";
	PRINT "</TD>";
	PRINT "</TR>";	
	print "<TR BGCOLOR=\"#F6AF17\">\n";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">\n";
	print "<TD WIDTH=\"10%\" ALIGN=\"center\">";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">\n";
	print "$MSG_167</TD>\n";
	print "<TD WIDTH=\"45%\" ALIGN=\"center\">";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">\n";
	print "$MSG_168</TD>\n";
	print "<TD WIDTH=\"15%\" ALIGN=\"center\">";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">\n";
	print "$MSG_169</TD>\n";
	print "<TD WIDTH=\"15%\" ALIGN=\"center\">";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">\n";
	print "$MSG_170</TD>\n";
	print "<TD WIDTH=\"15%\" ALIGN=\"center\">";
	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"#000000\">\n";
	print "$MSG_171</TD>\n";
	print "</TR>\n";
	

	
	$query = "select * from ".$dbfix."_auctions where category=\"$cat\" and subcategory=\"$sub\" and closed=\"0\" order by title";
	$result = mysql_query($query);
	if(!$result){
		print $ERR_001;
		exit;
	}
	
	$num_auctions = mysql_num_rows($result);
	
	if($num_auctions == 0){
		print "</TABLE>";
		print "<BR><BR><CENTER>";
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"3\">";
		print "$MSG_172";
		print "</CENTER><BR><BR>";
	}else{
		$i = 0;
		$BgColor = "#EBEBEB";

		while($i < $num_auctions){
			
			if($BgColor == "#EBEBEB"){
				$BgColor = "#FFFFFF";
			}else{
				$BgColor = "#EBEBEB";
			}
			print "<TR VALIGN=\"top\" BGCOLOR=\"$BgColor\">";

	//-- Column #1 - Picture
			print "<TD WIDTH=\"10%\" ALIGN=\"center\">";
			$pict_url = mysql_result($result,$i,"pict_url");
			
			if($pict_url){
				print "<IMG SRC=\"./images/picture.gif\" BORDER=\"0\">";
			}else{
				print "--";
			}
			
			print "</TD>";
	
	
	//-- Column #2 - Auction title
			print "<TD WIDTH=\"45%\">";
			$title = mysql_result($result,$i,"title");
			$date = mysql_result($result,$i,"date");
			$ends = mysql_result($result,$i,"ends");
			$reserve_price = mysql_result($result,$i,"reserve_price");
			$auction_id = mysql_result($result,$i,"id");
			print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
			print "<A HREF=\"./item.php?id=$auction_id\">$title</A> ";
	
			$year 			= intval(date("Y"));
   	 	$month 			= intval(date("m"));
   	 	$day 				= intval(date("d"));
   	 	$hours 			= intval(date("H"));
   	 	$minutes 		= intval(date("i"));
   	 	$seconds 		= intval(date("s"));
   	 	$start_year		= substr($date,0,4);
   	 	$start_month 	= substr($date,4,2);
   	 	$start_day 		= substr($date,6,2);
   	 	$start_hours	= substr($date,8,2);
   	 	$start_minutes = substr($date,10,2);
   	 	$start_seconds = substr($date,12,2);
	
   	 	$difference = intval(intval(mktime($hours,$minutes,$seconds,$month,$day,$year)) - mktime($start_hours,$start_minutes,$start_seconds,$start_month,$start_day,$start_year));
   	 	if(intval($difference / 3600) < 12){			
				print "<IMG SRC=\"./images/nueva.gif\">";
			}
			print "</TD>";
	

	//-- Column #3 - Current Bid
			print "<TD WIDTH=\"15%\">";
			print "<TABLE WIDTH=95%>";
			print "<TR>";
			print "<TD>";
				if($reserve_price > 0){
					print "<IMG SRC=\"images/r.gif\"> ";
				}
			print "</TD>";
			print "<TD ALIGN=RIGHT>";
				$current_bid = mysql_result($result,$i,"current_bid");
				print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
				print number_format($current_bid,0,",",".");
			print "</TD>";
			print "<TR>";
			print "</TABLE>";
			print "</TD>";


	//-- Column #4 - Number of bids
			print "<TD WIDTH=\"15%\" ALIGN=\"center\">";
			$query = "select bid from ".$dbfix."_bids where auction=\"$auction_id\"";
			$result_count = mysql_query($query);
			if(!$result_count){
				print $ERR_001;
				exit;
			}
		
			print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
			print mysql_num_rows($result_count);
			print "</TD>";


	//-- Column #5 - Time remaining
			print "<TD ALIGN=\"center\">";
			$ends = mysql_result($result,$i,"ends");
		
		    	
			$year 			= intval(date("Y"));
			$month 			= intval(date("m"));
			$day 				= intval(date("d"));
			$hours 			= intval(date("H"));
			$minutes 		= intval(date("i"));
			$seconds 		= intval(date("s"));
			$ends_year 		= substr($ends,0,4);
			$ends_month 	= substr($ends,4,2);
			$ends_day 		= substr($ends,6,2);
			$ends_hours		= substr($ends,8,2);
			$ends_minutes 	= substr($ends,10,2);
			$ends_seconds 	= substr($ends,12,2);
		
			$difference = intval(mktime($ends_hours,$ends_minutes,$ends_seconds,$ends_month,$ends_day,$ends_year)) - intval(mktime($hours,$minutes,$seconds,$month,$day,$year));
			$days_difference = intval($difference / 86400);
			$difference = $difference - ($days_difference * 86400);
		
			$hours_difference = intval($difference / 3600);
			if(strlen($hours_difference) == 1){
				$hours_difference = "0".$hours_difference;
			}
		
			$difference = $difference - ($hours_difference * 3600);
			$minutes_difference = intval($difference / 60);
			if(strlen($minutes_difference) == 1){
				$minutes_difference = "0".$minutes_difference;
			}
		
			$difference = $difference - ($minutes_difference * 60);
			$seconds_difference = $difference;
			if(strlen($seconds_difference) == 1){
				$seconds_difference = "0".$seconds_difference;
			}

			print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
			print "$days_difference $MSG_126 <BR>$hours_difference:$hours_difference:$seconds_difference";
			print "</TD>";
	
			$i++;
			print "</TR>";
	 	}
	 	print "</TABLE>";
	 }

?>
<BR>
<SCRIPT Language=PHP> require("footer.php"); </SCRIPT>	
</CENTER>
</BODY>
</HTML>
