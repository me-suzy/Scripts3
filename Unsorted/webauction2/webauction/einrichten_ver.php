<?php

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
    require('./includes/countries.inc.php');
	require('./includes/relations.inc.php');
    require('./includes/auction_types.inc.php');
	require('./includes/durations.inc.php');
	require('./includes/payments.inc.php');
	require('./includes/datacheck.inc.php');
/*----------------------------------------------------------*/
     $T=	"<SELECT NAME=\"atype\">\n";
			reset($auction_types); while(list($key,$val)=each($auction_types)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$atype)?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_auction_type = $T;
  	   		/*----------------------------------------------------------*/ 
			
            //--
			$query = "select * from ".$dbfix."_durations order by days";
			$res_durations = mysql_query($query);
			if(!$res_durations)
			{
				print $ERR_001." - ".mysql_error();
			}
			$num_durations = mysql_num_rows($res_durations);
			$i = 0;
			$T=	"<SELECT NAME=\"duration\">\n";
			while($i < $num_durations){
				$days 				= mysql_result($res_durations,$i,"days");
				$duration_descr 	= mysql_result($res_durations,$i,"description");
				$T.= "	<OPTION VALUE=\"$days\"";
				if($days == $duration)
				{
					$T .= " SELECTED";
				}
				$T .= ">$duration_descr</OPTION>";
				$i++;
			}
			$T.="</SELECT>\n";
			$TPL_durations_list = $T;
// -------------------------------------- country
			$T=	"<SELECT NAME=\"country\">\n";
			reset($countries); while(list($key,$val)=each($countries)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$country)?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_countries_list = $T;
          /*----------------------------------------------------------*/
   // -------------------------------------- payment
			//--
			$qurey = "select * from ".$dbfix."_payments";
			$res_payment = mysql_query($qurey);
			if(!$res_payment)
			{
				print $ERR_001." - ".mysql_error();
				exit;
			}
			$num_payments = mysql_num_rows($res_payment);
			$T=	"";
			$i = 0;
			while($i < $num_payments)
			{
				$payment_descr = mysql_result($res_payment,$i,"description");
				$T.="<INPUT TYPE=CHECKBOX NAME=\"payment[]\" VALUE=\"$payment_descr\"";
				if($payment_descr == $payment[$i])
				{
					$T .= " CHECKED";
				}
				$T .= "> $std_font $payment_descr</FONT><BR>";
				$i++;
			}
			$TPL_payments_list = $T;
   
   // -------------------------------------- shipping
			if ( intval($shipping)==1 )
				$TPL_shipping1_value = "CHECKED";
			if ( intval($shipping)==2 )
				$TPL_shipping2_value = "CHECKED";
			if ( !empty($international) )
				$TPL_international_value = "CHECKED";
    
              // -------------------------------------- reserved price
			if ( $with_reserve=="yes" )
				$TPL_with_reserve_selected = "CHECKED";
			else
				$TPL_without_reserve_selected = "CHECKED";
            // -------------------------------------- photo source
			if ( intval($imgtype)==1 )
				$TPL_imgtype2_SELECTED = "CHECKED";
			else
				$TPL_imgtype1_SELECTED = "CHECKED";
			$TPL_error_value = $$ERR;
          /*----------------------------------------------------------*/
	$id = (int)$id;
	if ($id==0)
	{
		/*
			display full list of categories of level 0
		*/
		$result = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE parent_id='0' ORDER BY cat_name" );
		if (!$result)
		{
			/* output error message and exit */
			print "database error";
			exit;
		}
		else
		{
				$need_to_continue = 1;
				$cycle = 1;
			$TPL_main_value = "";
			$TPL_categories_string = "Alle Hauptkategorien";
				while ($row=mysql_fetch_array($result))
				{
					if ($cycle==1 ) { $TPL_main_value.="<TR WIDTH=516 ALIGN=LEFT>\n"; }
					$sub_counter = (int)$row[sub_counter];
					$cat_counter = (int)$row[counter];
					if ($sub_counter!=0)
						$count_string = "(".$sub_counter.")";
					else
					{
						$count_string = "";
				
					}
					$TPL_main_value .= "<TD WIDTH=\"33%\"><A HREF=\"einrichten_ver.php?id=".$row[cat_id]."&user=".$user."&password=".$password."&zip=".$zip."&country=".$country."&name=".$name."&menge=1\"><font size=\"2\" face=\"arial\" color=\"#000000\">".$row[cat_name]."</font></A><font size=\"1\" face=\"arial\" color=\"#ff0000\"> ".$count_string."</FONT></TD>\n";
					
					++$cycle;
					if ($cycle==4) { $cycle=1; $TPL_main_value.="</TR>\n"; }
				}
			if ( $cycle>=2 && $cycle<=3 && $login=="einrichten" && !$LOGIN_ERR)
			{
				while ( $cycle<4 )
				{
					$TPL_main_value .= "	<TD WIDTH=\"33%\">&nbsp;</TD>\n";
					++$cycle;
				}
				$TPL_main_value .= "</TR>\n";
			}
			include "header.php";
			include "templates/kategorie_header_einrichten_php3.html";
			include "templates/kategorie_php3.html";
			include "footer.php";
			exit;
		}
	}
	else
	{
		/*
			specified category number
			look into table - and if we don't have such category - redirect to full list
		*/
		$result = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE cat_id='$id'" );
		if ($result)
			$category = mysql_fetch_array($result);
		else
			$category = false;
		if (!$category)
		{
			/* redirect to global categories list */
			header ( "Location: einrichten_ver.php?id=2000&user=".$user."&password=".$password."&zip=".$zip."&country=".$country."&name=".$name."&menge=1" );
			exit;
		}
		else
		{
			/* 
				such category exists
				
				retrieve it's subcategories and its auctions
			*/
			/* recursively get "path" to this category */
			$TPL_categories_string = "".$category["cat_name"];
			$TPL_category_new = "".$category["cat_name"];
/*			$TPL_cat_id_new = (int)$category[parent_id];  */
			$TPL_cat_id_new = (int)$category[cat_id];
			$par_id = (int)$category[parent_id];
			while ( $par_id!=0 )
			{
				// get next parent
				$res = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE cat_id='$par_id'");
				if ($res)
				{
					$rw = mysql_fetch_array($res);
					if ($rw)
						$par_id = (int)$rw[parent_id];
					else
						$par_id = 0;
				}
				else
					$par_id = 0;
$TPL_categories_string = "<font size=\"2\" face=\"arial\" color=\"#000000\"><A  HREF=\"einrichten_ver.php?id=".$row[cat_id]."&user=".$user."&password=".$password."&zip=".$zip."&country=".$country."&name=".$name."&menge=1\"><font size=\"2\" face=\"arial\" color=\"#000000\">".$rw["cat_name"]."</A> &gt; ".$TPL_categories_string;
			}
			/* get list of subcategories of this category */
					$subcat_count = 0;
					$result = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE parent_id='$id' ORDER BY cat_name" );
					if (!$result)
					{
						/* output error message and exit */
					}
					else
					{
						/* query succeeded - display list of categories */
							$need_to_continue = 1;
							$cycle = 1;
						$TPL_main_value = "";
							while ($row=mysql_fetch_array($result))
							{
								++$subcat_count;
								if ($cycle==1 ) { $TPL_main_value.="<TR ALIGN=LEFT>\n"; }
									$sub_counter = (int)$row[sub_counter];
									$cat_counter = (int)$row[counter];
									if ($sub_counter!=0)
										$count_string = "(".$sub_counter.")";
									else
									{
										if ($cat_counter!=0)
										{
											$count_string = "(".$cat_counter.")";
										}
										else
											$count_string = "";
									}
$TPL_main_value .= "<TD WIDTH=\"33%\"><A  HREF=\"einrichten_ver.php?id=".$row[cat_id]."&user=".$user."&password=".$password."&zip=".$zip."&country=".$country."&name=".$name."&menge=1\"><font size=\"2\" face=\"arial\" color=\"#000000\">".$row[cat_name]."</A></font><font size=\"1\" face=\"arial\" color=\"#ff0000\"> ".$count_string."</FONT></TD>\n";
								++$cycle;
								if ($cycle==4) { $cycle=1; $TPL_main_value.="</TR>\n"; }
				}
			if ( $cycle>=2 && $cycle<=3 )
			{
				while ( $cycle<4 )
				{
					$TPL_main_value .= "	<TD WIDTH=\"33%\">&nbsp;</TD>\n";
					++$cycle;
				}
				$TPL_main_value .= "</TR>\n";
						}
					}
					
					
				
			include "header.php";
			include "templates/kategorie_header_einrichten_php3.html";
			if ( $subcat_count>0 )
			{
				include "templates/kategorie_php3.html";
			}
			if ($subcat_count==0) 
			{
				include "templates/sell_php3.html";
			}
			include "footer.php";
			exit;
		
		
		}
	}
?>
