<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	include "../includes/countries.inc.php";
	
	$username = $name;


	//-- Data check
	if(!$id){
		header("Location: listauctions.php");
		exit;
	}
	
	if($action){
	
		if($mode == "activate")
		{
			$sql="update ".$dbfix."_auctions set suspended=0 WHERE id='$id'";
		}
		else
		{
			$sql="update ".$dbfix."_auctions set suspended=1 WHERE id='$id'";
		}
		$res=mysql_query ($sql);

		$updated=1;
		
	}
	

	if(!$action || ($action && $updated)){

		$query = "select a.id, u.nick, a.title, a.date, a.description,
		c.cat_name, d.description as duration, a.suspended, a.current_bid,
		a.quantity, a.reserve_price, a.location, a.minimum_bid from ".$dbfix."_auctions
		a, ".$dbfix."_users u, ".$dbfix."_categories c, ".$dbfix."_durations d where u.id = a.user and
		c.cat_id = a.category and d.days = a.duration and a.id=\"$id\"";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}


		$id = mysql_result($result,0,"id");
		$title = mysql_result($result,0,"title");
		$nick = mysql_result($result,0,"nick");
		$tmp_date = mysql_result($result,0,"date");
		$duration = mysql_result($result,0,"duration");
		$category = mysql_result($result,0,"cat_name");
		$description = mysql_result($result,0,"description");
		$suspended = mysql_result($result,0,"suspended");
		$current_bid = mysql_result($result,0,"current_bid");
		$min_bid = mysql_result($result,0,"minimum_bid");
		$quantity = mysql_result($result,0,"quantity");
		$reserve_price = mysql_result($result,0,"reserve_price");
		$country = mysql_result($result, 0, "location");

  $country_list="";
		$country_query = "SELECT country_id, country FROM ".$dbfix."_countries ORDER BY country";
                $res_c = mysql_query($country_query);
                if(!$res_c){
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }
                for ($i = 0; $i < mysql_num_rows($res_c); $i++)
                {
                        $row = mysql_fetch_row($res_c);
                        // Append to the list
                        $country_list .= "<option value=\"$row[0]\"";
                        if ($row[0] == $country)
                        {
                                $country_list .= " selected ";
                        }
                        $country_list .= ">$row[1]</option>\n";
                };
		
		$day = substr($tmp_date,6,2);
		$month = substr($tmp_date,4,2);
		$year = substr($tmp_date,0,4);
		$date = "$day/$month/$year";

	}

?>

<HTML>
<HEAD>

<?    require('../includes/styles.inc.php'); ?>

<TITLE></TITLE>

</HEAD>

<?    require('./header.php'); ?>  

<BODY bgcolor="#FFFFFF">
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
<TR>
<TD>
	<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<B><? print $tlt_font; ?>
		<?
			if($suspended > 0)
			{
				print $MSG_321;
			}
			else
			{
				print $MSG_322;
			}
		?></B>
		<BR>
	 </TD>
	</TR>	

<TABLE WIDTH="700" BORDER="0" CELLPADDING="5">

<?
if($updated){
			print "<TR>
  					<TD>
  					</TD>
  					<TD WIDTH=486>
					<FONT FACE=\"Verdana,Arial, Helvetica\" SIZE=2 COLOR=red>";
					if($updated) print "Auctions data updated";					
					print "</TD>
					</TR>";
}
?>


<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
  	<?print $std_font; ?>
  	<? print "$MSG_312"; ?>
  	</FONT>
  </TD>
  <TD WIDTH="486">
  	<?print $std_font.$title; ?>
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_313"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
  	<? print $std_font.$nick; ?>
  	</FONT>
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_314"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
   <? print $std_font.$date; ?>
  	</FONT>
  </TD>
</TR>

<TR>
  <TD WIDTH="204"  VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_315"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
   <? print $std_font.$duration; ?>
  </TD>
</TR>

<TR>
  <TD WIDTH="204"  VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_316"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
  	<? print $std_font.$category; ?>
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
  	<? print $std_font; ?>
  	<? print "$MSG_317"; ?>
  	</FONT>
  </TD>
  <TD WIDTH="486">
  	<? print $std_font.$description; ?>
  </TD>
</TR>


<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_014"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
	<? print $std_font.$countries[$country]; ?>
	
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_318"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
  	<? print $std_font.$current_bid; ?>
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_327"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
  	<? print $std_font.$min_bid; ?>
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_319"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
  	<? print $std_font.$quantity; ?>
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_320"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
	<? print $std_font.$reserve_price; ?>
  </TD>
</TR>

<TR>
  <TD WIDTH="204" VALIGN="top" ALIGN="right">
   <? print $std_font; ?>
   <? print "$MSG_300"; ?>
   </FONT>
  </TD>
  <TD WIDTH="486">
	<? 
		print $std_font; 
		if($suspended == 0)
			print "$MSG_029";
		else
			print "$MSG_030";
		
	?>
	
  </TD>
</TR>

<TR>
  <TD WIDTH="204">&nbsp;</TD>
  <TD WIDTH="486">
	</FONT>
    <? print $err_font; ?>
    <?
    		if($suspended > 0)
    		{
    			print $MSG_324;
    			$mode = "activate";
    		}
    		else
    		{
    			print $MSG_323;
    			$mode = "suspend";
    		}
    	?>
   </TD>
</TR>
<TR>
  <TD WIDTH="204">&nbsp;</TD>
  <TD WIDTH="486">
	</FONT>
		 <FORM NAME=details ACTION="excludeauction.php" METHOD="POST">
		 <INPUT type="hidden" NAME="id" VALUE="<? echo $id; ?>">
		 <INPUT type="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
		 <INPUT type="hidden" NAME="action" VALUE="Delete">	 
		 <INPUT type="hidden" NAME="mode" VALUE="<? print $mode; ?>">	 
		 <INPUT TYPE=submit NAME=act VALUE="<? print $MSG_030; ?>">   
		 </FORM>
	 </TD>
	 
</TR>
</TABLE>

</center>

 <BR>
  <BR>
  <CENTER>
  <FONT face="Tahoma, Arial" size="2"><A HREF="admin.php">Admin home</A> | 
  <FONT face="Tahoma, Arial" size="2"><A HREF="listauctions.php?offset=<? print$ offset; ?>">Auctions list</A></FONT>
  </CENTER>
  
</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

  
  <!-- Closing external table (header.php) -->
  </TD>
  </TR>
</TABLE>
    
  
  </BODY>
  </HTML>
