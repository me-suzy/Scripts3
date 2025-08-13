<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
	require('./includes/auction_types.inc.php');
	require('./includes/countries.inc.php');
	require('./includes/datacheck.inc.php');

	
	//-- Genetares the UCTION's unique ID
	
	function generate_id()
	{
		global $title, $description;
		$continue = true;
		//$auction_id = uniqid(""); 

		$auction_id = md5(uniqid(rand()));
		//$auction_id = eregi_replace("[a-f]","",$auction_id);
		
		return $auction_id;
	}

	if (empty($action))
	{
		// initialize some variables here

		if ($mode=="recall")
		{
			// recall the variables from current session
#			if (isset($sessionVars["SELL_DATA_CORRECT"]))
#			{

				// delete uploaded image
				if (isset($sessionVars["SELL_file_uploaded"]))
				{
					if (file_exists($image_upload_path.$sessionVars["SELL_pict_url"]))
						unlink($image_upload_path.$sessionVars["SELL_pict_url"]);
					unset($sessionVars["SELL_file_uploaded"]);
					$sessionVars["SELL_pict_url"] = $sessionVars["SELL_pict_url_original"];
					session_name($SESSION_NAME);
					session_register("sessionVars");
					//putSessionVars();
				}

				$title = $sessionVars["SELL_title"];
				$description = $sessionVars["SELL_description"];
				$pict_url = $sessionVars["SELL_pict_url_original"];
				$atype = $sessionVars["SELL_atype"];
				$iquantity = $sessionVars["SELL_iquantity"];
				$minimum_bid = $sessionVars["SELL_minimum_bid"];
				$with_reserve = ($sessionVars["SELL_with_reserve"])?"yes":"no";
				$reserve_price = $sessionVars["SELL_reserve_price"];
				$buy_now = $sessionVars["SELL_with_buy_now"];
				$buy_now_price = $sessionVars["SELL_buy_now_price"];
				$duration = $sessionVars["SELL_duration"];
				$increments = $sessionVars["SELL_increments"];
				$customincrement = $sessionVars["SELL_customincrement"];
				$country = $sessionVars["SELL_country"];
				$location_zip = $sessionVars["SELL_location_zip"];
				$shipping = $sessionVars["SELL_shipping"];
				$payment = $sessionVars["SELL_payment"];
				$international = ($sessionVars["SELL_international"])?"on":"";
				$category = $sessionVars["SELL_category"];
				$imgtype = $sessionVars["SELL_imgtype"];
#			}
		}
		else
		{
			// auction type
			reset($auction_types);
			list($atype,) = each($auction_types);

			// quantity of items
			$iquantity = 1;


			// country
			reset($countries);
			list($country,) = each($countries);

			// shipping
			$shipping = 1;

			// image type
			$imgtype = 1;

			$with_reserve = "no";
		}
	}
	elseif ($action=='first')
	{
		unset($auction_id);

		// perform data check here
		$ERR = "ERR_".CheckSellData();

		//$ERR = "ERR_".CheckMoney($minimum_bid);
		
		// if no other errors - handle upload here
		if (!$$ERR)
		{
			unset($file_uploaded);

			/* generate a auction ID on this step */
			$auction_id = generate_id();

			if ( $userfile!="none" )
			{
				$inf = GetImageSize ( $userfile );
				$er = false;
				// make a check
				if ($inf)
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
					$ERR = "ERR_602";
					$er = true;
				}

				if (!$er)
				{
					// really save this file
					$ext = ($inf[2]==1)?".gif":".jpg";
					$fname = $image_upload_path.$auction_id.$ext;

					if ( file_exists($fname) )
						unlink ($fname);
					copy ( $userfile, $fname );

					$uploaded_filename = $auction_id.$ext;
					$file_uploaded = true;
				}
				else
				{
					// there is an error
					unset($file_uploaded);
				}

			}
			else
			{
				unset($file_uploaded);
			}
		}
	}

	/*	if script called the first time OR
		an error occured THEN
		display form
	*/
	if ( empty($action) || (($action=='first')&&($$ERR)) )
	{
		// display form here
		include "header.php";


		#// Delete Pictures Gallery session variables
		if(mode != "recall")
		{
			unset($UPLOADED_PICTURES);
			unset($UPLOADED_PICTURES_SIZE);
			session_name($SESSION_NAME);
			session_unregister(UPLOADED_PICTURES_SIZE);
			session_unregister(UPLOADED_PICTURES);
    }

		$auc_id =$sessionVars["SELL_auction_id"];
		$filename = "counter/".auction_id.".txt";
		$newfile = fopen($filename, "w+") or die("Couldn't create file."); fclose($newfile);
		$tfail = fopen("$filename", "w");
		$faili = "1";
		fwrite($tfail, "$faili", 500000);
		fclose($tfail);

		// prepare variables for templates/template

			// simple fields
			$titleH =  htmlspecialchars($title);
			$descriptionH = htmlspecialchars($description);
			$pict_urlH = htmlspecialchars($pict_url);

			// ------------------------------------- auction type
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

			// ------------------------------------- duration
			
			//-- 
			$query = "select * from PHPAUCTIONPROPLUS_durations order by days";
			$res_durations = mysql_query($query);
			if(!$res_durations)
			{
				MySQLError($query);
				exit;
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
					(($key==$country)?" SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_countries_list = $T;

			// -------------------------------------- payment
			
			//--
			$qurey = "select * from PHPAUCTIONPROPLUS_payments";
			$res_payment = mysql_query($qurey);
			if(!$res_payment)
			{
				MySQLError($query);
				exit;
			}
			$num_payments = mysql_num_rows($res_payment);
			$T=	"";
			
			$i = 0;
			while($i < $num_payments)
			{
				$payment_descr = mysql_result($res_payment,$i,"description");
				
				$T.="<INPUT TYPE=CHECKBOX NAME=\"payment[]\" VALUE=\"$payment_descr\"";
				if(is_array($payment))
				{
					if(in_array($payment_descr,$payment))
					{
						$T .= " CHECKED";
					}
				}
				$T .= "> $std_font $payment_descr</FONT><BR>";
				
				$i++;
			}
			$TPL_payments_list = $T;

			// -------------------------------------- category
			$T=	"<SELECT NAME=\"category\">\n";
			$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories_plain");
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

			// -------------------------------------- buy now
			if ( $buy_now=="yes" )
				$TPL_with_buy_now_selected = "CHECKED";
			else
				$TPL_without_buy_now_selected = "CHECKED";

			// -------------------------------------- photo source
			if ( intval($imgtype)==1 )
				$TPL_imgtype2_SELECTED = "CHECKED";
			else
				$TPL_imgtype1_SELECTED = "CHECKED";

			$TPL_error_value = $$ERR;

			// update current session
			if ( isset($sessionVars["SELL_DATA_CORRECT"]) )
				unset($sessionVars["SELL_DATA_CORRECT"]);
			session_name($SESSION_NAME);
			session_register("sessionVars");
			
			//putSessionVars();

		// include corresponding templates/template and exit
		include "templates/template_sell_php.html";
		include "footer.php";
		exit;
	}

	/*	all data is ok.
		TODO: update current session variables and proceed further
	*/
	if ($action=="first" && !$$ERR)
	{	
		$auction_id = $sessionVars["SELL_auction_id"];
			// auction title
		$sessionVars["SELL_title"] = strip_tags($title);
			// auction description
		$sessionVars["SELL_description"] = $description;
		
			// image URL
		if (!isset($file_uploaded))
		{
			$sessionVars["SELL_pict_url"] = $pict_url;
			unset($sessionVars["SELL_original_filename"]);
		}
		else
		{
			// the URL is uploaded image
			$sessionVars["SELL_pict_url"] = $uploaded_filename;
			$sessionVars["SELL_original_filename"] = $userfile_name;
		}

			// data from "picture URL" input field
		$sessionVars["SELL_pict_url_original"] = $pict_url;

       #// Calculate gappery  fee if necessary
       if($SETTINGS[picturesgallery] == 1 && $SETTINGS[picturesgalleryfee] == 1 && @count($UPLOADED_PICTURES)> 0)
       {
           $GALLERY_FEE = $SETTINGS[picturesgalleryvalue] * count($UPLOADED_PICTURES);
					 $TPL_gallery_fee = "<BR><TABLE WIDTH=100% CELLPADDING=2 CELLSPACING=0 BORDER=0 BGCOLOR=#FFEB6B>
					                     <TR><TD>$std_font $MSG_696".print_money($GALLERY_FEE)."</TD></TR>
															 </TABLE>";
       }



			// flag if file is uploaded
		if (!isset($file_uploaded))
			unset($sessionVars["SELL_file_uploaded"]);
		else
			$sessionVars["SELL_file_uploaded"] = true;

		// auction type
		$sessionVars["SELL_atype"] = $atype;

		// quantity of items for sale
		$sessionVars["SELL_iquantity"] = $iquantity;

		// minimum bid
		$sessionVars["SELL_minimum_bid"] = $minimum_bid;

		// increments information
		$sessionVars["SELL_increments"] = $increments;
		$sessionVars["SELL_customincrement"] = $customincrement;

		// reserved price flag
		$sessionVars["SELL_with_reserve"] = ($with_reserve=="yes")?true:false;

		// reserved price value
		$sessionVars["SELL_reserve_price"] = $reserve_price;

		// buy now
		$sessionVars["SELL_with_buy_now"] = ($buy_now=="yes")?true:false;

		// buy now price value
		$sessionVars["SELL_buy_now_price"] = $buy_now_price;

		// auction duration
		$sessionVars["SELL_duration"] = $duration;

		// country
		$sessionVars["SELL_country"] = $country;

		// zip code
		$sessionVars["SELL_location_zip"] = $location_zip;

		// shipping method
		$sessionVars["SELL_shipping"] = $shipping;

		// international shipping
		$sessionVars["SELL_international"] = (strlen($international)==0)?false:true;

		// payment methods: text and index
		reset($payment); 
		while(list($key,$val) = each($payment))
		{
			$SELL_payment[$key] = $payment[$key];
			//$sessionVars["SELL_payment".$key] = $payment[$key];
		}
		$sessionVars[SELL_payment] = $SELL_payment;
			// category ID
		$sessionVars["SELL_category"] = $category;

			// auction id
		if (isset($auction_id))
			$sessionVars["SELL_auction_id"] = $auction_id;
		else
			$sessionVars["SELL_auction_id"] = generate_id();

			// image type
		$sessionVars["SELL_imgtype"] = $imgtype;

			// set that first step is passed
		$sessionVars["SELL_DATA_CORRECT"] = true;
		session_name($SESSION_NAME);
		session_register("sessionVars");
		//putSessionVars();
#		print "Sessions vars are put";
	}

	// check second data - login and password
	if ( $action=="second")
	{	
		$nickH = htmlspecialchars($nick);

		$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_users WHERE nick='".AddSlashes($nick)."'");
		if ($result)
			$num = mysql_num_rows($result);
		else
			$num = 0;
		
		if ($num==0)
			$ERR = "ERR_025";

		if ($num>0)
		{
			$pass = mysql_result ($result,0,"password");
			$user_id = mysql_result ($result,0,"id");
			$user_balance = mysql_result ($result,0,"balance");
			if (md5($MD5_PREFIX.$password) != $pass)
			{
				$ERR = "ERR_026";
			}
			else
			{
				if(mysql_result($result,0,"suspended") > 0)
				{
					$ERR = "ERR_618";
				}
				else
				{
					if($SETTINGS[sellersetupfee] == 1)
						{
						#// Calculate the fee to apply (if any) and check user's
						#// account to see if there's enough credit to pay the fee
						##// ==============================================================
						#// _____PERCENTAGE OF THE STARTING PRICE______
						if($SETTINGS[sellersetuptype] == 1) 
						{
							$PRICE = doubleval($sessionVars["SELL_minimum_bid"]);
							$FEE = ($PRICE / 100) * doubleval($SETTINGS[sellersetupvalue]);
						}
						else
						#// _____FIX FEE______else
						{
							$FEE = doubleval($SETTINGS[sellersetupvalue]);
						}
                        
            $SETUP_FEE = $FEE;

            #// Add pictures gallery fee if necessary
            if($SETTINGS[picturesgallery] == 1 && $SETTINGS[picturesgalleryfee] == 1 && @count($UPLOADED_PICTURES)> 0)
            {
                $GALLERY_FEE = $SETTINGS[picturesgalleryvalue] * count($UPLOADED_PICTURES);
            }
            $FEE = $FEE + $GALLERY_FEE;
                        

						session_name($SESSION_NAME);
						session_register("FEE");

						$query = "SELECT balance FROM PHPAUCTIONPROPLUS_users WHERE id='$user_id'";
						$res = mysql_query($query);
						if(!$res)
						{
							MySQLError($query);
							exit;
						}
						$balance = mysql_result($res,0,"balance");
	if($balance > $FEE && $SETTINGS[feetype] == "prepay")
{
						$query = "update PHPAUCTIONPROPLUS_counters set auctions = auctions+1";
			    $result = mysql_query($query);

          		}		 

				elseif(mysql_result($res,0,"balance") < $FEE && $SETTINGS[feetype] == "prepay")
						{
							$ERR = "ERR_121";
						}
					}
				}
			}
		}
	}

	if ( ($action=="first" && !$$ERR) || ($action=="second" && $$ERR) )
	{
		// display preview form

			// error text
		$TPL_error = $$ERR;

			// title text
		$TPL_title_value = strip_tags($sessionVars["SELL_title"]);

			// description text
		$TPL_description_shown_value = stripslashes(nl2br($sessionVars["SELL_description"]));

			// picture URL
			if( intval($sessionVars["SELL_imgtype"])==0 )
			{
//				print "URL";
				// URL specified
				if ( strlen($sessionVars["SELL_pict_url_original"])==0 )
					$TPL_pict_URL_value = $MSG_114;
				else
					$TPL_pict_URL_value = "<IMG SRC=\"".$sessionVars["SELL_pict_url_original"]."\">";
			}
			else
			{
				// a file uploaded
				if ( empty($sessionVars["SELL_file_uploaded"]) )
					$TPL_pict_URL_value = $MSG_114;
				else
					$TPL_pict_URL_value = "<IMG SRC=\"".$uploaded_path.$sessionVars["SELL_pict_url"]."\">";
			}


/*
		$TPL_pict_URL_value = (strlen($sessionVars["SELL_pict_url"])>0)
			? "<IMG SRC=\"".$uploaded_path.$sessionVars["SELL_pict_url"]."\">"
			: "no image";
*/

			// minimum bid
		$TPL_minimum_bid_value = print_money($sessionVars["SELL_minimum_bid"]);

			// reserved price
		if ($sessionVars["SELL_with_reserve"])
			$TPL_reserve_price_displayed = "$std_font ".print_money($sessionVars["SELL_reserve_price"])."</FONT>";
		else
			$TPL_reserve_price_displayed = "$std_font no </FONT>";

			// buy now
		if ($sessionVars["SELL_with_buy_now"])
			$TPL_buy_now_price_displayed = "$std_font ".print_money($sessionVars["SELL_buy_now_price"])."</FONT>";
		else
			$TPL_buy_now_price_displayed = "$std_font no </FONT>";

			// auction duration
		
		//--
		$query = "select description from PHPAUCTIONPROPLUS_durations where days=".$sessionVars["SELL_duration"];
		$res_duration_descr = mysql_query($query);
		$duration_descr = mysql_result($res_duration_descr,0,"description");
		$TPL_durations_list = $duration_descr;

		#// Bids increment
		if($sessionVars["SELL_increments"] == 1)
		{
			$TPL_increments = $MSG_614;
		}
		else
		{
			$TPL_increments = print_money($sessionVars["SELL_customincrement"]);
		}
		
		
		
		
			// auction type
		$TPL_auction_type = $auction_types[$sessionVars["SELL_atype"]];
		if ( intval($sessionVars["SELL_atype"])==2 )
			$TPL_auction_type .= "</TD></TR><TR><TD ALIGN=RIGHT> $std_font <B>Quantity:</B> </FONT></TD><TD>$std_font".$sessionVars["SELL_iquantity"]."</TD></TR>";

			// country
		$TPL_countries_list = $countries[$sessionVars["SELL_country"]];

			// zip code
		$TPL_location_zip = $sessionVars["SELL_location_zip"];

			// shipping
		if ( intval($sessionVars["SELL_shipping"]) == 1 )
		{
			$TPL_shipping_value = $MSG_038;
		}
		else
		{
			$TPL_shipping_value = $MSG_032;
		}
		if ( $sessionVars["SELL_international"] )
		{
			$TPL_international_value  = "<BR>";
			$TPL_international_value .= $MSG_033;
		}
		else
		{
			$TPL_international_value  = "<BR>";
			$TPL_international_value .= $MSG_043;
		}

		// payment methods
		
		//--
		$query = "select * from PHPAUCTIONPROPLUS_payments";
		$res_payments = mysql_query($query);
		if(!$res_payments)
		{
			MySQLError($query);
			exit;
		}
	
		while($pay = mysql_fetch_array($res_payments))
		{
			if(in_array($pay[description],$sessionVars[SELL_payment]))
			{
				$TPL_payment_methods .= "$std_font".$pay[description]."</FONT><BR>";
			}
			$i++;
		}

			// category name
		$cat_id = intval($sessionVars["SELL_category"]);
		$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=$cat_id");
		$parent_id = mysql_result($result,0,"parent_id");
		$category_name = mysql_result($result,0,"cat_name");

		$T = "";
		while($parent_id!=0)
		{
			// get info about this parent
			$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=$parent_id");
			$pparent_id = intval(mysql_result($result,0,"parent_id"));
			$pcat_id = mysql_result($result,0,"cat_id");
			$pcat_name = mysql_result($result,0,"cat_name");

			$T = "$pcat_name &gt; ".$T;

			// get parent of this parent
			if ($pparent_id!=0)
				$parent_id = mysql_result( mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=$pparent_id"),0,"parent_id" );
			else
				$parent_id = 0;
		}
		$T .= $category_name;
		$TPL_categories_list = $T;
		
		$sessionVars[categoriesList] = $TPL_categories_list;
		session_name($SESSION_NAME);
		session_register("sessionVars");
		
		include "header.php";
		include "templates/template_sell_preview_php.html";
		include "footer.php";
		exit;
	}

	if ($action=='second' && !$$ERR)
	{
	
		//-- If a suggested category is present send an e-mail
		//-- to the site administrator
		if($suggested_category)
		{
			$to 		= $SETTINGS[adminmail];
			$subject	= $MSG_254;
			$message	= $suggested_category."\n".
						  $MSG_255.  
						  $sessionVars["SELL_auction_id"];
			
			mail($to,$subject,$message);
			
		}


		// really add item to database and display confirmation message
		if ( !$sessionVars["SELL_DATA_CORRECT"] )
			header ( "Location: sell.php" );

		// prepare some things
			// payments list
			
			$payment_text = "";
		//--
		$query = "select * from PHPAUCTIONPROPLUS_payments";
		$res_payments = mysql_query($query);
		if(!$res_payments)
		{
			MySQLError($query);
			exit;
		}
		
		$num_payments = mysql_num_rows($res_payments);
		$i = 0;
		while($i < $num_payments)
		{
			$idx = mysql_result($res_payments,$i,"id");
			$val = mysql_result($res_payments,$i,"description");
			if (in_array($val,$sessionVars[SELL_payment]))
			//if ( isset($sessionVars["SELL_payment".$i]) )
				$payment_text .= $val." \n";
				
			$i++;
		}
			// auction starts
				$time = time();
				$a_starts = date("Y-m-d H:i:s",$time);

			// auction ends
				$a_ends = $time+$sessionVars["SELL_duration"]*24*60*60;
				$a_ends = date("Y-m-d H:i:s", $a_ends);

			// picture URL
				$pcURL = "";
				if ( ($sessionVars["SELL_file_uploaded"]) && (strlen($sessionVars["SELL_original_filename"])>0) )
					$pcURL = $sessionVars["SELL_pict_url"];
				else
					$pcURL = $sessionVars["SELL_pict_url_original"];

		$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE id=".$sessionVars["SELL_auction_id"]);
		if ($result)
			$nr = mysql_num_rows($result);
		else
			$nr = 0;

		if ($nr>0)
		{
			header ( "Location: item.php?mode=1&id=$sessionVars[SELL_auction_id]");
			exit;
		}

		include "header.php";


		#//======================================================
		#  PHPAUCTION PRO code
		#//======================================================
		if($SETTINGS[sellersetupfee] == 1)
		{
			$SUSPENDED = 1;
		}
		else
		{
			$SUSPENDED = 0;
		}
		#//======================================================

		if ($sessionVars["SELL_suspended"] <> 8)
			{

		$query = 
			"INSERT INTO PHPAUCTIONPROPLUS_auctions VALUES ('".$sessionVars["SELL_auction_id"]."', '". // auction id
			$user_id."', '".
			addslashes($sessionVars["SELL_title"])."', '". // auction title
			$a_starts."', '". // auction starts
			addslashes($sessionVars["SELL_description"])."', '". // auction description
			addslashes($pcURL)."', ". // picture URL
			$sessionVars["SELL_category"].", '". // category
			$sessionVars["SELL_minimum_bid"]."', '".// minimum bid
			(($sessionVars["SELL_with_reserve"])?$sessionVars["SELL_reserve_price"]:"0")."', '".// reserve price
			(($sessionVars["SELL_with_buy_now"])?$sessionVars["SELL_buy_now_price"]:"0")."', '".// buy now price
			$sessionVars["SELL_atype"]."', '".// auction type
			$sessionVars["SELL_duration"]."', ".
			doubleval($sessionVars["SELL_customincrement"]).", '".
			$sessionVars["SELL_country"]."', '".// country
			$sessionVars["SELL_location_zip"]."', '".// zip code
			$sessionVars["SELL_shipping"]."', '".// shipping method
			$payment_text."', '".// payment method
			(($sessionVars["SELL_international"])?"1":"0")."', '".// international shipping
			$a_ends."', '".// ends
			"0', '".// current bid
			"0', ".// closed
			(($sessionVars["SELL_file_uploaded"])?"1":"0").", ".
			$sessionVars["SELL_iquantity"].", ".// quantity
            "'$SUSPENDED' ".//suspended
			")";// photo uploaded
			}
		else
			{
		$query = 
			"UPDATE PHPAUCTIONPROPLUS_auctions set 
title = '".addslashes($sessionVars["SELL_title"])."', 
starts = '".$a_starts."', 
description = '".addslashes($sessionVars["SELL_description"])."', 
pict_url = '".addslashes($pcURL)."', 
category = ".$sessionVars["SELL_category"].", 
minimum_bid = '".$sessionVars["SELL_minimum_bid"]."', 
reserve_price = '".(($sessionVars["SELL_with_reserve"])?$sessionVars["SELL_reserve_price"]:"0")."', 
buy_now = '".(($sessionVars["SELL_with_buy_now"])?$sessionVars["SELL_buy_now_price"]:"0")."', 
auction_type = '".$sessionVars["SELL_atype"]."', 
duration = '".$sessionVars["SELL_duration"]."', 
increment = ".doubleval($sessionVars["SELL_customincrement"]).", 
location = '".$sessionVars["SELL_country"]."', 
location_zip = '".$sessionVars["SELL_location_zip"]."', 
shipping = '".$sessionVars["SELL_shipping"]."', 
payment = '".$payment_text."', 
international = '".(($sessionVars["SELL_international"])?"1":"0")."', 
ends = '".$a_ends."', 
photo_uploaded = ".(($sessionVars["SELL_file_uploaded"])?"1":"0").", 
quantity = ".$sessionVars["SELL_iquantity"].", 
closed = '0',
suspended = '0' WHERE id = '".$sessionVars["SELL_auction_id"]."' ";
			}


		if (!mysql_query($query))
		{
			MySQLError($query);
			exit;
		}
		else
		{
            #// @@@@@@@@@@@@@@@@@@ GIAN - added 03/08/2002 @@@@@@@@@@@@@@@@@
            #// Create pictures gallery if any
            if($SETTINGS[picturesgallery] == 1 && @count($UPLOADED_PICTURES)> 0 && $HTTP_SESSION_VARS[GALLERY_UPDATED])
            {
                #// Create dirctory
                mkdir($uploaded_path.$sessionVars["SELL_auction_id"],0777);
                
                #// Copy files
                while(list($k,$v) = each($UPLOADED_PICTURES))
                {
                    copy($uploaded_path.session_id()."/$v",$uploaded_path.$sessionVars["SELL_auction_id"]."/".$v);
                }
                
                #// Delete files
                reset($UPLOADED_PICTURES);
                while(list($k,$v) = each($UPLOADED_PICTURES))
                {
                    unlink($uploaded_path.session_id()."/$v");
                }
                #// Delete temp directory
                @rmdir($uploaded_path.session_id());
                
            }
            #// @@@@@@@@@@@@@@@@@@  @@@@@@@@@@@@@@@@@
            
            
			//-- Update COUNTERS table
			
			if($SETTINGS[sellersetupfee] != 1)
			{
			    $query = "update PHPAUCTIONPROPLUS_counters set auctions = (auctions+1)";
			    $result = mysql_query($query);
            }

			$TPL_auction_id = $sessionVars["SELL_auction_id"];
			
            
            #// Unset session variables
            session_name($SESSION_NAME);
            session_unregister(UPLOADED_PICTURES);
            session_unregister(UPLOADED_PICTURES_SIZE);
            
			#//======================================================
			#  PHPAUCTION PRO code
			#//======================================================
			if($SETTINGS[sellersetupfee] == 1)
			{
				#// Distinguish between PAY nad PREPAY
				if($SETTINGS[feetype] == "prepay")
				{
					include "lib/auctionsetup_payment.php";
				}
				else
				{
					include "templates/template_sell_result_pay_php.html";
				}
			}
			else
			{
				include "templates/template_sell_result_php.html";
			}
		}

		include "footer.php";

		// and increase category counters
		$ct = intval($sessionVars["SELL_category"]);
		$row = mysql_fetch_array(mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=$ct"));
		$counter = $row[counter]+1;
		$subcoun = $row[sub_counter]+1;
		$parent_id = $row[parent_id];
		mysql_query("UPDATE PHPAUCTIONPROPLUS_categories SET counter=$counter, sub_counter=$subcoun WHERE cat_id=$ct");

			// update recursive categories
		while ( $parent_id!=0 )
		{
			// update this parent's subcounter
				$rw = mysql_fetch_array(mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=$parent_id"));
				$subcoun = $rw[sub_counter]+1;
				mysql_query("UPDATE PHPAUCTIONPROPLUS_categories SET sub_counter=$subcoun WHERE cat_id=$parent_id");
			// get next parent
				$parent_id = intval($rw[parent_id]);
		}

			#//======================================================
			#  Added by Simokas
			#//======================================================
			// Send notification if users keyword matches (Auction Watch)

    		$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_users");
			$num_users = mysql_num_rows($result);
			$i = 0;
			while($i < $num_users){
				$nickname = mysql_result ($result,$i,"nick");
				$e_mail = mysql_result ($result,$i,"email");
				$keyword = strtolower(mysql_result ($result,$i,"auc_watch"));
				$title = $sessionVars["SELL_title"];
				$seller = ".AddSlashes($nick).";
				$description = $sessionVars["SELL_description"];
				$new_auction_text = strtolower("$title, $description, $seller");

				$key = split(" ",$keyword);
			for ($j=0; $j < count($key); $j++) {
				$match = strstr($new_auction_text,$key[$j]);

			}


			// If keyword matches with opened auction title or/and desc send user a mail
			if ($match) 
				{ 
				$sitename = $SETTINGS[sitename];
				$auction_url = $SETTINGS[siteurl] . "item.php?mode=1&id=".$sessionVars["SELL_auction_id"];

			// Mail body and mail() functsion
			include ("./templates/template_auction_watchmail_php.html");

				}
				else { } 


			$i++;
			}

			// Send confirmation email
    		$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_users WHERE nick='".AddSlashes($nick)."'");
        	$user_name = mysql_result ($result,0,"name");
        	$user_email = mysql_result ($result,0,"email");
        	$user_address = mysql_result ($result,0,"address");
        	$user_city = mysql_result ($result,0,"city");
        	$user_country = mysql_result ($result,0,"country");
        	$user_zip = mysql_result ($result,0,"zip");                        
			$title = $sessionVars["SELL_title"];
			$auction_id = $sessionVars["SELL_auction_id"];
			$description = $sessionVars["SELL_description"];
			$pict_url = $pcURL;
			$minimum_bid = $sessionVars["SELL_minimum_bid"];
			$reserve_price = $sessionVars["SELL_reserve_price"];
			$buy_now_price = $sessionVars["SELL_buy_now_price"];
			$duration = $sessionVars["SELL_duration"];
			$customincrement = $sessionVars["SELL_customincrement"];
			//$cat_name = $sessionVars["SELL_category"];
			$cat_name = $category_name;
			$ends = $a_ends;
         	$auction_url = $SETTINGS[siteurl] . "item.php?mode=1&id=".$sessionVars["SELL_auction_id"];
        	
        	
        	include('./includes/auction_confirmation.inc.php');
                        
	}

	// clear this session from SELL_ variables
	reset($sessionVars); while(list($key,$val)=each($sessionVars)){
		if ( strpos($key,"SELL_")==0 )
			unset($sessionVars[$key]);
	}
	session_name($SESSION_NAME);
	session_register("session_Vars");
	//putSessionVars();

	// to be continued
	exit;
?>