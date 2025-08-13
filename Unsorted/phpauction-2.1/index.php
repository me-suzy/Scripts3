<?

/*



   Copyright (c), 1999, 2000 - phpauction.org                  

   

   This program is free software; you can redistribute it and/or modify 

   it under the terms of the GNU General Public License as published by 

   the Free Software Foundation (version 2 or later).                                  

                                                                        

   This program is distributed in the hope that it will be useful,      

   but WITHOUT ANY WARRANTY; without even the implied warranty of       

   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        

   GNU General Public License for more details.                         

                                                                        

   You should have received a copy of the GNU General Public License    

   along with this program; if not, write to the Free Software          

   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

   

*/







	require('./includes/config.inc.php');

	require('./includes/messages.inc.php');

	require("./header.php"); 

	

	

	/*

	If subscription to information service requested

	update INFO table

	*/

	

	$TPL_info_err = "";

	if($user_email){

		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$user_email)){

			$TPL_info_err = $ERR_028;

		}else{

			$query = "insert into PHPAUCTION_info values(\"$user_email\")";

			$result = mysql_query($query);

			if(!$result){

				$TPL_info_err = $ERR_001;

			}else{

				$TPL_info_err = $MSG_512;

			}

		}

	}



	/*

		prepare data for templates/template

	*/



	/* prepare categories list for templates/template */

		$TPL_categories_value = "";

		$query = "select * from PHPAUCTION_categories WHERE parent_id=0 order by sub_counter desc";

		$result = mysql_query($query);

			if(!$result)

			{

				$TPL_categories_value .= $ERR_001;

			}

			else

			{

				$num_cat = mysql_num_rows($result);

				$i = 0;

				$TPL_categories_value .= "<TABLE BORDER=0>\n";

				while($i < $num_cat && $i < 23)

				{



					$cat_id = mysql_result($result,$i,"cat_id");

					$cat_name = mysql_result($result,$i,"cat_name");

					$sub_count = intval(mysql_result($result, $i, "sub_counter"));
					
					$cat_colour = mysql_result($result, $i, "cat_colour");
					
					$cat_image = mysql_result($result, $i, "cat_image");

#					print $sub_count."<BR>";

					$cat_counter = (int)mysql_result($result, $i, "counter" );

					if ($sub_count!=0)

						$cat_counter = "(".$sub_count.")";

					else

					{

						$cat_counter = "";

						/*

						if ($cat_counter!=0)

						{

							$count_string = "(".$cat_counter.")";

						}

						else

							$count_string = "";

						*/

					}

					$cat_url = "./browse.php?id=$cat_id";

					$TPL_categories_value .= "<TR>\n<TD>";
					if ( $cat_image != "")
					{
						$TPL_categories_value .= "<IMG SRC=\"$cat_image\">";
					}
					$TPL_categories_value .= "</TD>\n";

					if ( $cat_colour != "")
					{
						$TPL_categories_value .= "<TD BGCOLOR=\"$cat_colour\">";
					}
					else
					{
						$TPL_categories_value .= "<TD>";
					}
					

					$TPL_categories_value .= "$std_font";

					//$cat_name = ereg_replace ( " ", "&nbsp;", $cat_name );

					$TPL_categories_value .= " <A HREF=\"$cat_url\">$cat_name</A>"." $cat_counter"."</FONT></TD></TR>\n";

					$i++;

				}

				$TPL_categories_value .= "</TABLE>\n";

				$TPL_categories_value .= "<A HREF=\"browse.php?id=0\">$std_font$MSG_277 </FONT></A>";

			}



	/* get last created auctions */

			$query = "select id,title,starts from PHPAUCTION_auctions where closed='0' order by starts desc";

			$result = mysql_query($query);

			if ( $result )

				$num_auction = mysql_num_rows($result);

			else

				$num_auction = 0;



			$i = 0;

			$bgcolor = "#FFFFFF";

			$TPL_last_auctions_value = "";

			while($i < $num_auction && $i < 6){

				if($bgcolor == "#FFFFFF")

				{

					$bgcolor = $FONTCOLOR[$SETTINGS[headercolor]];

				}

				else

				{

					$bgcolor = "#FFFFFF";

				}			

				$title = mysql_result($result,$i,"title");

				$id 	 = mysql_result($result,$i,"id");

				$date	 = mysql_result($result,$i,"starts");



				$year = substr($date,0,4);

				$month = substr($date,4,2);

				$day = substr($date,6,2);

				$hours = substr($date,8,2);

				$minutes = substr($date,10,2);

				$seconds = substr($date,12,2);



				

				$TPL_last_auctions_value .= 

					"<TR BGCOLOR=\"$bgcolor\">".

					"<TD WIDTH=\"150\"  VALIGN=top ALIGN=LEFT>".

					"$std_font".

					ArrangeDate($day,$month,$year,$hours,$minutes).

					"</FONT></TD>".

					"<TD WIDTH=\"245\" VALIGN=top ALIGN=LEFT>".

					"$std_font".

					"<A HREF=\"./item.php?id=$id\">".stripslashes($title)."</A>".

					"</FONT></TD>".

					"</TR>";



				$i++;

			}

			

			if($num_auction > 6)

			{

				$TPL_last_auctions_value .= "<TR ALIGN=RIGHT>".

											"<TD></TD><TD>".

											"<A HREF=\"./view_more_news.php?\">$sml_font$MSG_233</FONT></A>...".

											"</TD>".

											"</TR>";			

			}

			

	/* get ending soon auctions */

			$TPL_ending_soon_value = "";

			$now = date("YmdHis",time());

			

			$query = "select ends,id,title from PHPAUCTION_auctions where closed=\"0\" and
						suspended=\"0\" order by ends";
			$result = mysql_query($query);

			

			$num_auction = mysql_num_rows($result);

			

			$i = 0;

			$bgcolor = "#FFFFFF";

			while($i < $num_auction && $i < 6){

				if($bgcolor == "#FFFFFF")

				{

					$bgcolor = $FONTCOLOR[$SETTINGS[headercolor]];

				}

				else

				{

					$bgcolor = "#FFFFFF";

				}					

				$title 	= mysql_result($result,$i,"title");

				$id 	= mysql_result($result,$i,"id");

				$ends 	= mysql_result($result,$i,"ends");



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

                if ($difference > 0) {

    				$ends_string = intval($difference / 86400).$MSG_126;

    				$difference = $difference - (intval($difference / 86400) 
												 * 86400);


    				$hours_difference = intval($difference / 3600);

    				if(strlen($hours_difference) == 1){

    					$hours_difference = "0".$hours_difference;
    
	    			}

    				$ends_string .= $hours_difference.":";



    				$difference = $difference - ($hours_difference * 3600);

    				$minutes_difference = intval($difference / 60);

    				if(strlen($minutes_difference) == 1){

    					$minutes_difference = "0".$minutes_difference;

    				}

    				$ends_string .= $minutes_difference.":";



    				$difference = $difference - ($minutes_difference * 60);

	    			$seconds_difference = $difference;
    
    				if(strlen($seconds_difference) == 1){

    					$seconds_difference = "0".$seconds_difference;

    				}

				

    				$ends_string .= $seconds_difference;

                } else {
                
                    $ends_string = "$err_font$MSG_911</FONT>";
                }

				$TPL_ending_soon_value .=

				"<TR BGCOLOR=\"$bgcolor\">".

					"<TD WIDTH=\"140\"  VALIGN=top ALIGN=LEFT>".

						"$std_font".$ends_string."</FONT>".

					"</TD>".



					"<TD WIDTH=\"250\" VALIGN=top ALIGN=LEFT>".

						"$std_font<A HREF=\"./item.php?id=$id&\">".stripslashes($title)."</A></FONT>".

					"</TD>".

				"</TR>";

				

				$i++;

			}

			

			if($num_auction > 6)

			{

				$TPL_ending_soon_value .= "<TR ALIGN=RIGHT>".

											"<TD></TD><TD>".

											"<A HREF=\"./view_more_ending.php\">$sml_font$MSG_233</FONT></A>...".

											"</TD>".

											"</TR>";				

			}



	/* get higher bids */

	

			$TPL_maximum_bids = "";

			$query = "select auction,max(bid) as max_bid from PHPAUCTION_bids group by auction order by max_bid desc";

			$result = mysql_query($query);



			if ($result)

				$num_auction = mysql_num_rows($result);

			else

				$num_auction = 0;



			$i = 0;
			$j = 0;

			$bgcolor = "#FFFFFF";

			while($i < $num_auction && $j < 6){

				$max_bid  	 = mysql_result($result,$i,"max_bid");

				$auction  = mysql_result($result,$i,"auction");



				//-- Get auction data

				

				$query = "select title,closed,id from PHPAUCTION_auctions where id=\"$auction\"";

				$result_bid = mysql_query($query);

				if(mysql_num_rows($result_bid) > 0)
				{
					$title = mysql_result($result_bid,0,"title");

					$closed = mysql_result($result_bid,0,"closed");

					$auc_id = mysql_result($result_bid,0,"id");
				}

				

				if($closed == "0"){

					$TPL_maximum_bids .=

					"<TR BGCOLOR=\"$bgcolor\">".

					"<TD WIDTH=\"140\" VALIGN=top ALIGN=LEFT>".

					"$std_font".

					print_money ($max_bid).
					
					"</FONT></TD>".

					"<TD ALIGN=LEFT WIDTH=\"240\">".

					"$std_font".

					"<A HREF=\"./item.php?id=$auc_id\">".stripslashes($title)."</A>".

					"</FONT></TD>".

					"</TR>";
					$j++;
					if($bgcolor == "#FFFFFF")
					
					{
					
						$bgcolor = $FONTCOLOR[$SETTINGS[headercolor]];
	
					}
				
					else
					
					{
					
						$bgcolor = "#FFFFFF";
					
					}					


				}

				$i++;

				

			}



			if($num_auction > 6)

			{

				$TPL_maximum_bids .= "<TR ALIGN=RIGHT>".

										"<TD></TD><TD>".

										"<A HREF=\"./view_more_higher.php\">$sml_font$MSG_233</FONT></A>...".

										"</TD>".

										"</TR>";					

			}

		
	// Build list of help topics
        $query = "select topic from PHPAUCTION_help order by topic;";
	$result = mysql_query($query);
	if (!$result){
		print "$err_font $ERR_001 </font> <br>";
		require("./footer.php");
		exit;
	}
        if (mysql_num_rows($result)) {
		$TPL_helptopics = "<a href=\"help.php\">" . $MSG_919 .  "</a><br>";
                $num_topics = mysql_num_rows($result);
                $i = 0;
                while(($i < $num_topics) && ($i < 5)){
                        $this_topic = mysql_result($result, $i, "topic");
			if ($this_topic != "General") {
				$TPL_helptopics .= "<a href=\"help.php?topic=";
        	                $TPL_helptopics .= $this_topic;
                	        $TPL_helptopics .= "\">";
                        	$TPL_helptopics .= $this_topic;
                        	$TPL_helptopics .= "<br>";
			}
			$i++;
		}
	} else { 	
		$TPL_helptopics = "&nbsp;";
	}
	

	//-- Build news list
	if($SETTINGS[newsbox] == 1)
	{
		$query = "SELECT title,id from PHPAUCTION_news where suspended=0 order by new_date limit $SETTINGS[newstoshow]";
		$res = mysql_query($query);
		if(!$res)
		{
			$TPL_news_list = $ERR_001;
			exit;
		}
		
		while($new = mysql_fetch_array($res))
		{
			$TPL_news_list .= "<strong><big>·</big></strong> 
									 $sml_font<A HREF=\"viewnew.php?id=$new[id]\">$new[title]</A><BR>";
		}
	}
	else
	{
		$TPL_news_list = "&nbsp;";
	}


	require("./templates/template_index_php.html");
	require('./footer.php');

?>
