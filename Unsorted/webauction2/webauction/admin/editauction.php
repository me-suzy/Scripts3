<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	include("../includes/config.inc.php");
	include("$include_path/messages.inc.php");
	include("$include_path/countries.inc.php");

	$username = $name;


	//-- Data check
	if(!$id){
		header("Location: listauctions.php");
		exit;
	}

	/*
	 *	If script is called to actually make modifications
	 *	(ie on first invocation this script just displays some HTML
	 *	 on the second it tries to modify the database).
	 */
	if($action){

		// Check that all the fields are not NULL
		if ($id && $title && $nick && $date && $duration && category &&
			$description && $current_bid && quantity &&
			$min_bid && $reserve_price && $country)
		{

			$DATE = explode("/",$date);
			$tmp_day 		= $DATE[0];
			$tmp_month 	= $DATE[1];
			$tmp_year 	= $DATE[2];

			/*
			 * 	Check the input values for validity.
			 */


			if(strlen($tmp_year) == 2){
				$tmp_year = "19".$tmp_year;
			}


			if (strlen($nick)<6)
			{
				$ERR = "$ERR_010";
			}
			else if (!ereg("^[0-9]{2}/[0-9]{2}/[0-9]{4}$",$date) &&
					 !ereg("^[0-9]{2}/[0-9]{2}/[0-9]{2}$",$date)) //date check
			{
				$ERR = "$ERR_700";
			}
			else if ($quantity < 1) // 1 or more items being sold
			{
				$ERR = "$ERR_701";
			}
			else if ($current_bid < $min_bid && $current_bid != 0) // bid > min_bid
			{
				$ERR = "$ERR_702";
			}
			else
			{

				$date = "$tmp_year"."$tmp_month"."$tmp_day";

				$sql="UPDATE ".$dbfix."_auctions SET title=\"".	AddSlashes($title)
								 ."\", user=\"".		AddSlashes($nick)
								 ."\", date=\"".		AddSlashes($date)
								 ."\", duration=\"".	AddSlashes($duration)
								 ."\", category=\"".	AddSlashes($category)
								 ."\", description=\"".	AddSlashes($description)
								 ."\", current_bid=\"".	AddSlashes($current_bid)
								 ."\", location=\"".		AddSlashes($country)
								 ."\", quantity=\"".	AddSlashes($quantity)
								 ."\", minimum_bid=\"".	AddSlashes($min_bid)
								 ."\", reserve_price=". AddSlashes($reserve_price)
								 ." WHERE id='".		AddSlashes($id)."'";
				$res=mysql_query ($sql);

				if (!$res)
				{
					 print "Database error on update: " . mysql_error();
					 exit;
				}
				else
				{
					$updated = 1;
				}


			}
		}
		else
		{
			$ERR = "$ERR_112";
		}

	}


	if(!$action || ($action && $updated)){

		/*
		 *	Make a large SQL query getting values from the "auctions"
		 *	table and corresponding values that are indexed in other tables
		 * 	and displaying them both (and allowing the admin to change
		 *	only the proper indexed values.
		 */
		$query = "select a.id, a.user as nick , u.nick as nick_description,
		a.title, a.date, a.description,
		a.category, c.cat_name, a.duration as duration, d.description as
		dur_description, a.suspended, a.current_bid,
		a.quantity, a.reserve_price, a.location, a.minimum_bid, l.country from ".$dbfix."_auctions
		a, ".$dbfix."_users u, ".$dbfix."_categories c, ".$dbfix."_durations d, ".$dbfix."_countries l where u.id = a.user and
		c.cat_id = a.category and d.days = a.duration and a.id=\"$id\"";		$result = mysql_query($query);
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
		$category = mysql_result($result, 0, "category");
		$cat_description = mysql_result($result,0,"cat_name");
		$description = mysql_result($result,0,"description");
		$suspended = mysql_result($result,0,"suspended");
		$current_bid = mysql_result($result,0,"current_bid");
		$min_bid = mysql_result($result,0,"minimum_bid");
		$quantity = mysql_result($result,0,"quantity");
		$reserve_price = mysql_result($result,0,"reserve_price");
		$country = mysql_result($result, 0, "location");


		/*
		 * 	For all list-like items we create drop-down
		 *	lists and select the index listed in the auction table.
		 *	for this auction.
		 */


		// NICKS (usernames)

		$nick_list = ""; // empty string to begin HTML list
		$nick_query = "select id, nick from ".$dbfix."_users";
		$res_q = mysql_query($nick_query);
		if(!$res_q){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}
		for ($i = 0; $i < mysql_num_rows($res_q); $i++)
		{
			$row = mysql_fetch_row($res_q);
			// 0 is id, 1 is nick
			// Append to the list
			$nick_list .= "<option value=\"$row[0]\"";
			// If this Nick's id coresponds to the owner of this
			// auction, select it
			if ($row[0] == $nick)
			{
				$nick_list .= " selected ";
			}
			$nick_list .= ">$row[1]</option>\n";
		};


		// DURATIONS

		$dur_list = ""; // empty string to begin HTML list
		$dur_query = "select days, description from ".$dbfix."_durations";
		$res_d = mysql_query($dur_query);
		if(!$res_d){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}
		for ($i = 0; $i < mysql_num_rows($res_d); $i++)
		{
			$row = mysql_fetch_row($res_d);
			// 0 is days, 1 is description
			// Append to the list
			$dur_list .= "<option value=\"$row[0]\"";
			// If this Durations # of days coresponds to the duration of this
			// auction, select it
			if ($row[0] == $duration)
			{
				$dur_list .= " selected ";
			}
			$dur_list .= ">$row[1]</option>\n";
		};



		// CATEGORIES

		/*
		 * Do Categories later -- see includes/categories.inc.php
		 */

		// COUNTRIES

		$country_list="";
		$country_query = "SELECT country_id, country
					FROM ".$dbfix."_countries
					ORDER BY country";
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

		$date = mysql_result($result,0,"date");
		$tmp_day = substr($date,6,2);
		$tmp_month = substr($date,4,2);
		$tmp_year = substr($date,0,4);
		$date = "$tmp_day/$tmp_month/$tmp_year";

	}

?>

<HTML>
<HEAD>

<?php include("$include_path/styles.inc.php"); ?>

<TITLE></TITLE>

</HEAD>

<?php include("./header.php"); ?>

<BODY bgcolor="#FFFFFF">
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
<TR>
<TD>
	<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<B><? print $tlt_font.$MSG_511; ?></B>
		<BR>
	 </TD>
	</TR>

	<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5">

	<?
	if($ERR || $updated){
				print "<TR>
						<TD>
						</TD>
						<TD WIDTH=486>
						<FONT FACE=\"Verdana,Arial, Helvetica\" SIZE=2 COLOR=red>";
						if($$ERR) print $$ERR;
						if($updated) print "Auction data updated";
						print "</TD>
						</TR>";
	}
	?>
<FORM NAME=details ACTION="editauction.php" METHOD="POST">


	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<?print $std_font; ?>
		<? print "$MSG_312 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=title SIZE=40 MAXLENGTH=255 VALUE="<? print $title; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_313 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<SELECT NAME=nick>
		<OPTION VALUE="">	</OPTION>

		<?  echo $nick_list; ?>

		</SELECT>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print " $MSG_314 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=date SIZE=20 MAXLENGTH=20 VALUE="<? echo $date; ?>">
		</FONT>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_315 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<SELECT NAME=duration>
		<OPTION VALUE="">	</OPTION>

		<?  echo $dur_list; ?>

		</SELECT>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204"  VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_316 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=category SIZE=10 MAXLENGTH=10 VALUE="<? echo $category; ?>">
	  <? print $cat_description ?></TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_317 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=description SIZE=40 MAXLENGTH=255 VALUE="<? echo $description; ?>">
	  </TD>
	</TR>


	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_014 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<SELECT NAME=country>
		<OPTION VALUE="">	</OPTION>

		<?  echo $country_list; ?>

		</SELECT>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_318 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME="current_bid" SIZE=15 MAXLENGTH=15 VALUE="<? echo $current_bid; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_327 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME="min_bid" SIZE=40 MAXLENGTH=40 VALUE="<? echo
		$min_bid; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_319 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME="quantity" SIZE=40 MAXLENGTH=40 VALUE="<? echo
		$quantity; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_320 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME="reserve_price" SIZE=40 MAXLENGTH=40 VALUE="<? echo
		$reserve_price; ?>">
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
		<BR><BR>
		 <INPUT TYPE=submit NAME=act VALUE="<? print $MSG_089; ?>">
		</TD>
	</TR>
	</TABLE>
		<INPUT type="hidden" NAME="id" VALUE="<? echo $id; ?>">
		<INPUT type="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
		<INPUT type="hidden" NAME="action" VALUE="update">
	</FORM>
	</center>
		 <BR>
		  <BR>
		  <CENTER>
		  <FONT face="Tahoma, Arial" size="2"><A HREF="admin.php" CLASS="navigation">Admin home</A> |
		  <FONT face="Tahoma, Arial" size="2"><A
		  HREF="listauctions.php?offset=<? print $offset; ?>
		  "CLASS="navigation">Auctions list</A></FONT>
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


  </BODY>
  </HTML>
