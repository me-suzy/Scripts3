<?php

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

	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
	require('./includes/auction_types.inc.php');
	require('./includes/countries.inc.php');
	require('./includes/datacheck.inc.php');



	#// If user is not logged in redirect to login page
	if(!isset($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"]))
	{
		Header("Location: user_login.php");
		exit;
	}

	if($HTTP_POST_VARS[action] == "relist")
	{

		#// perform data check here
		$ERR = "ERR_".CheckSellData();
		
		#//$ERR = "ERR_".CheckMoney($minimum_bid);


		#// Check duration/start date congruence
		#// If the user changed the duration:
		#// END_DATE cannot be <= START_DATE
		$STARTS = mktime(substr($HTTP_POST_VARS[auctionstarts],8,2),
					     substr($HTTP_POST_VARS[auctionstarts],10,2),
					     substr($HTTP_POST_VARS[auctionstarts],12,2),
					     substr($HTTP_POST_VARS[auctionstarts],4,2),
					     substr($HTTP_POST_VARS[auctionstarts],6,2),
					     substr($HTTP_POST_VARS[auctionstarts],0,4));
					   
		$ENDS = $STARTS + ($duration * 86400);
		$FORMATTED_ENDS = date("YmdHis",$ENDS);
		
		if($ENDS <= $STARTS)
		{
			$er = true;
			$ERR = "ERR_060";
		}

		
		if(!isset($$ERR))
		{
			if($userfile!="none")
			{
				$inf = GetImageSize($userfile);
				$er = false;
				// make a check
				if($inf)
				{
					$inf[2] = intval($inf[2]); // check for uploaded file type
					if ( ($inf[2]!=1) && ($inf[2]!=2) )
					{
						$er = true;
						$ERR = "ERR_602";
					}
					else
					{
						// check for file size
						if ( intval($userfile_size)>$MAX_UPLOAD_SIZE )
						{
							$er = true;
							$ERR = "ERR_603";
						}
					}
				}
				else
				{
					$ERR = "ERR_060";
					$er = true;
				}
				
				if (!$er)
				{
					// really save this file
					$ext = ($inf[2]==1)?".gif":".jpg";
					$fname = $image_upload_path.$id.$ext;

					if (file_exists($fname))
						unlink ($fname);
					copy($userfile, $fname);

					$uploaded_filename = $id.$ext;
					$file_uploaded = true;
				}
				else
				{
					// there is an error
					unset($file_uploaded);
				}

			}
			elseif(!empty($HTTP_POST_VARS[pict_url]))
			{
				unset($file_uploaded);
				$uploaded_filename = $HTTP_POST_VARS[pict_url];
			}
			
			
			#// Update database
			if(is_array($HTTP_POST_VARS[payment]))
			{	
				$PAYMENT = "";
				while(list($k,$v) = each($HTTP_POST_VARS[payment]))
				{
					$PAYMENT .= $v."\n";
				}
			}
			$query = "UPDATE PHPAUCTION_auctions set
					  title='".addslashes($HTTP_POST_VARS[title])."', 
					  description='".addslashes($HTTP_POST_VARS[description])."',";
			if(!empty($uploaded_filename))
			{
				$query .= "pict_url='".addslashes($uploaded_filename)."',";
			}
			$query .= "category=$HTTP_POST_VARS[category],
					  starts=starts,
					  ends='$FORMATTED_ENDS',
					  minimum_bid=".input_money($HTTP_POST_VARS[minimum_bid]).", 
					  reserve_price=".doubleval((($HTTP_POST_VARS[with_reserve])?$HTTP_POST_VARS[reserve_price]:"0")).",
					  auction_type='".$HTTP_POST_VARS[atype]."', 
					  duration='".$HTTP_POST_VARS[duration]."', 
					  increment=".doubleval($HTTP_POST_VARS[customincrement]).",
					  location='".$HTTP_POST_VARS[country]."', 
					  location_zip='".$HTTP_POST_VARS[location_zip]."', 
					  shipping='".$HTTP_POST_VARS[shipping]."', 
					  payment='".$PAYMENT."', 
					  international='".(($HTTP_POST_VARS[international])?"1":"0")."', 
					  photo_uploaded='".(($file_uploaded)?"1":"0")."', 
					  quantity=$HTTP_POST_VARS[iquantity]
					  WHERE id='$HTTP_POST_VARS[id]'";
			$res = @mysql_query($query);
			if(!$res)
			{
				print $ERR_001."<BR>$query<BR>".mysql_error();
				exit;
			}
			else
			{
				#// Redirect
				Header("Location: yourauctions.php");
				exit;
			}
		}
		else
		{
			$TPL_error_value = $$ERR;
		}
	}
	
	if ($HTTP_POST_VARS[action] != "relist" ||
	    ($HTTP_POST_VARS[action] == "relist" && isset($$ERR)))
	{
		#// Retrieve auction's data
		$query = "SELECT * from PHPAUCTION_auctions where id='$id'";
		$res = @mysql_query($query);
		if(!$res)
		{
			print $ERR_001."<BR>$query<BR>".mysql_error();
			exit;
		}
		elseif(mysql_num_rows($res) == 0)
		{
			print $ERR_606;
			exit;
		}
		else
		{
			$AUCTION = mysql_fetch_array($res);
			
			$T=	"<SELECT NAME=\"atype\">\n";
			reset($auction_types); while(list($key,$val)=each($auction_types)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$AUCTION[auction_type])?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_auction_type = $T;

			// ------------------------------------- duration
			
			//-- 
			$query = "select * from PHPAUCTION_durations order by days";
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
				
				if($days == $AUCTION[duration])
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
					(($key==$AUCTION[location])?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_countries_list = $T;

			// -------------------------------------- payment
			
			//--
			$qurey = "select * from PHPAUCTION_payments";
			$res_payment = mysql_query($qurey);
			if(!$res_payment)
			{
				print $ERR_001." - ".mysql_error();
				exit;
			}
			$num_payments = mysql_num_rows($res_payment);
			$T=	"";
			
			$PAYMENT_ARRAY = explode("\n",$AUCTION[payment]);
			while(list($k,$v) = each($PAYMENT_ARRAY))
			{
				$PAYMENT_ARRAY[$k] = chop($v);
			}
				
			
			reset($PAYMENT_ARRAY);
			$i = 0;
			while($i < $num_payments)
			{
				$payment_descr = mysql_result($res_payment,$i,"description");
				
				$T.="<INPUT TYPE=CHECKBOX NAME=\"payment[]\" VALUE=\"$payment_descr\"";

				if(in_array(chop($payment_descr),$PAYMENT_ARRAY))
				{
					$T .= " CHECKED";
				}
				
				$T .= "> $std_font $payment_descr</FONT><BR>";
				
				$i++;
			}
			$TPL_payments_list = $T;

			// -------------------------------------- category
			$T=	"<SELECT NAME=\"category\">\n";
			$result = mysql_query("SELECT * FROM PHPAUCTION_categories_plain");
			if($result):
				while($row=mysql_fetch_array($result)){
					$T.=
						"	<OPTION VALUE=\"".
						$row[cat_id].
						"\" ".
						(($row[cat_id]==$category)?"SELECTED":"")
						.">".$row[cat_name]."</OPTION>\n";
				}
			endif;
			$T.="</SELECT>\n";
			$TPL_categories_list = $T;

			// -------------------------------------- shipping
			if ($AUCTION[shipping] == 1)
				$TPL_shipping1_value = " CHECKED";

			if ($AUCTION[shipping] == 2)
				$TPL_shipping2_value = " CHECKED";

			if (!empty($AUCTION[international]))
				$TPL_international_value = " CHECKED";

			// -------------------------------------- reserved price
			if ($AUCTION[reserve_price] > 0)
			{
				$TPL_with_reserve_selected = "CHECKED";
			}
			else
			{
				$TPL_without_reserve_selected = "CHECKED";
			}
				
			include "header.php";
			include "templates/template_edit_auction_php.html";
			include "footer.php";
		}
	}

	exit;
?>