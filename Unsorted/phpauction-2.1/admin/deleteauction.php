<?
	include "loggedin.inc.php";

	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	include "../includes/countries.inc.php";
	
	$username = $name;


	//-- Data check
	if(!$id){
		header("Location: listauctions.php");
		exit;
	}
	
	if($action)
	{
		if(!$ERR)
		{
			//-- Get category
			$query = "select category from PHPAUCTION_auctions where id='$id'";
			$res__ = mysql_query($query);
			if(!$res__)
			{
				print $ERR_001." $query<BR>".mysql_error();
				exit;
			}
			else
			{
				$cat_id = mysql_result($res__,0,"category");
			}
			
			//-- delete auction
			$sql="delete from PHPAUCTION_auctions WHERE id='$id'";
			$res=mysql_query ($sql);
			
			//-- Update counters
			$query = "select auctions from PHPAUCTION_counters";
			$result = mysql_query($query);
			
			$auctions = mysql_result($result,0,"auctions");
			$auctions = $auctions - 1;
			
			$query = "update PHPAUCTION_counters set auctions=$auctions";
			$result = mysql_query($query);
			
			// update "categories" table - for counters
			$root_cat = $cat_id;
			do 
			{
				// update counter for this category
				$query = "SELECT * FROM PHPAUCTION_categories WHERE cat_id=\"$cat_id\"";
				$result = mysql_query($query);
			
				if($result)
				{
					if (mysql_num_rows($result)>0)
					{
						$R_parent_id = mysql_result($result,0,"parent_id");
						$R_cat_id = mysql_result($result,0,"cat_id");
						$R_counter = intval(mysql_result($result,0,"counter"));
						$R_sub_counter = intval(mysql_result($result,0,"sub_counter"));

						$R_sub_counter--;
						if ( $cat_id == $root_cat )
							--$R_counter;

						if($R_counter < 0) $R_counter = 0;
						if($R_sub_counter < 0) $R_sub_counter = 0;

						$query = "UPDATE PHPAUCTION_categories SET counter='$R_counter', sub_counter='$R_sub_counter' WHERE cat_id=\"$cat_id\"";
						@mysql_query($query);
						
						$cat_id = $R_parent_id;
					}
				}
			} 
			while ($cat_id!=0);	
			
			
			Header("location: listauctions.php?offset=$offset");
		}

	}
	

	if(!$action){

		$query = "select a.id, u.nick, a.title, a.starts, a.description,
		c.cat_name, d.description as duration, a.suspended, a.current_bid,
		a.quantity, a.reserve_price, a.location from PHPAUCTION_auctions
		a, PHPAUCTION_users u, PHPAUCTION_categories c, PHPAUCTION_durations d where u.id = a.user and
		c.cat_id = a.category and d.days = a.duration and a.id=\"$id\"";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}

		$id = mysql_result($result,0,"id");
		$title = mysql_result($result,0,"title");
		$nick = mysql_result($result,0,"nick");
		$tmp_date = mysql_result($result,0,"starts");
		$duration = mysql_result($result,0,"duration");
		$category = mysql_result($result,0,"cat_name");
		$description = mysql_result($result,0,"description");
		$suspended = mysql_result($result,0,"suspended");
		$current_bid = mysql_result($result,0,"current_bid");
		$quantity = mysql_result($result,0,"quantity");
		$reserve_price = mysql_result($result,0,"reserve_price");
		$country = mysql_result($result, 0, "location");

		$country_list="";
		while (list ($code, $descr) = each ($countries))
		{
			$country_list .= "<option value=\"$code\"";
			if ($code == $country)
			{
				$country_list .= " selected";
			}
			$country_list .= ">$descr</option>\n";
		};
		
		$day = substr($tmp_date,6,2);
		$month = substr($tmp_date,4,2);
		$year = substr($tmp_date,0,4);
		$date = "$day/$month/$year";
	}

	include "./header.php";
	require('../includes/styles.inc.php'); 
?>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
<TR>
<TD>
	<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4">
		<B><? print $MSG_325; ?></B>
		</FONT>
		<BR>
	 </TD>
	</TR>	
<TABLE WIDTH="700" BORDER="0" CELLPADDING="5">

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
    <? print $err_font.$MSG_326; ?>
    
   </TD>
</TR>
<TR>
  <TD WIDTH="204">&nbsp;</TD>
  <TD WIDTH="486">
	</FONT>
	 <FORM NAME=details ACTION="deleteauction.php" METHOD="POST">
	 <INPUT type="hidden" NAME="id" VALUE="<? echo $id; ?>">
	 <INPUT type="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
	 <INPUT type="hidden" NAME="action" VALUE="Delete">	 
	 <INPUT TYPE=submit NAME=act VALUE="<? print $MSG_008; ?>">
	 </FORM>
	 </td>
	 
   </TD>
</TR>
</TABLE>

</center>
 <BR>
  <BR>
  <CENTER>
  <FONT face="Tahoma, Arial" size="2"><A HREF="admin.php">Admin home</A> | 
  <FONT face="Tahoma, Arial" size="2"><A HREF="listauctions.php?offset=<? print
  $offset; ?>">Auctions list</A></FONT>
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
  
  
  <? include "./footer.php"; ?>