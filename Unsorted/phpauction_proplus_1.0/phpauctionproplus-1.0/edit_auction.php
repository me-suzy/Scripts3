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
					$ERR = "ERR_602";
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
			$query = "UPDATE PHPAUCTIONPROPLUS_auctions set
					  title='".addslashes($HTTP_POST_VARS[title])."', 
					  description='".addslashes($HTTP_POST_VARS[description])."',";
			if(!empty($uploaded_filename))
			{
				$query .= "pict_url='".addslashes($uploaded_filename)."',
				           photo_uploaded='".(($file_uploaded)?"1":"0")."',";
			}
			$query .= "category=$HTTP_POST_VARS[category],
					  minimum_bid=".input_money($HTTP_POST_VARS[minimum_bid]).", 
					  reserve_price=".doubleval((($HTTP_POST_VARS[with_reserve])?$HTTP_POST_VARS[reserve_price]:"0")).",
					 buy_now=".doubleval((($HTTP_POST_VARS[buy_now])?$HTTP_POST_VARS[buy_now_price]:"0")).",
					  auction_type='".$HTTP_POST_VARS[atype]."', 
					  duration='".$HTTP_POST_VARS[duration]."',";
			if($HTTP_POST_VARS[increments] == 2)
			{ 
					  $query .= "increment=".doubleval($HTTP_POST_VARS[customincrement]).",";
			}
			else
			{ 
					 $query .=  "increment=0,";
			}
			
			$query .= "location='".$HTTP_POST_VARS[country]."', 
					  location_zip='".$HTTP_POST_VARS[location_zip]."', 
					  shipping='".$HTTP_POST_VARS[shipping]."', 
					  payment='".$PAYMENT."', 
					  international='".(($HTTP_POST_VARS[international])?"1":"0")."',
					  quantity=$HTTP_POST_VARS[iquantity]
					  WHERE id='$HTTP_POST_VARS[id]'";
			$res = @mysql_query($query);
			//print $query;
			if(!$res)
			{
				MySQLError($query);
				exit;
			}
			else
			{

           #// @@@@@@@@@@@@@@@@@@ GIAN - added 03/08/2002 @@@@@@@@@@@@@@@@@
          #// Create pictures gallery if any
          if($SETTINGS[picturesgallery] == 1 && @count($EDIT_UPLOADED_PICTURES)> 0 && $HTTP_SESSION_VARS[EDIT_GALLERY_UPDATED])
          {
	  					#// Delete existing files
  						reset($EDIT_UPLOADED_PICTURES);
		  				$handle=opendir($uploaded_path.$HTTP_POST_VARS[id]);
              while ($file = readdir($handle))
			    		{
                @unlink($uploaded_path.$HTTP_POST_VARS[id]."/".$file);
              }


              #// Create dirctory
              //mkdir($uploaded_path.$sessionVars["SELL_auction_id"],0777);

              #// Copy files
  	  				reset($EDIT_UPLOADED_PICTURES);
              while(list($k,$v) = each($EDIT_UPLOADED_PICTURES))
              {
                 copy($uploaded_path.session_id()."/$v",$uploaded_path.$HTTP_POST_VARS[id]."/".$v);
              }

              #// Delete files +++++++++++++++++++++++++++++++
              reset($EDIT_UPLOADED_PICTURES);
              while(list($k,$v) = each($EDIT_UPLOADED_PICTURES))
              {
                  unlink($uploaded_path.session_id()."/$v");
              }
              #// Delete temp directory
              rmdir($uploaded_path.session_id());

        }
        session_name($SESSION_NAME);
        session_unregister(EDIT_GALLERY_UPDATED);
        session_unregister(EDIT_UPLOADED_PICTURES);
        session_unregister(EDIT_UPLOADED_PICTURES_SIZE);

        #// ++++++++++++++++++++++++++++++++++++++++

				#// Save auction ID in EDITED_AUCTIONS array
				$EDITED_AUCTIONS[$id] = $id;
				session_name($SESSION_NAME);
				session_register("EDITED_AUCTIONS");
				
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
		$query = "SELECT * from PHPAUCTIONPROPLUS_auctions where id='$id'";
		$res = @mysql_query($query);
		if(!$res)
		{
			MySQLError($query);
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


            #// Pictures Gellery ++++++++++++++++++++++++++++++++++++++++++++++++++
            unset($EDIT_UPLOADED_PICTURES);
            unset($EDIT_UPLOADED_PICTURES_SIZE);
            unset($EDIT_GALLERY_UPDATES);
            session_name($SESSION_NAME);
            session_unregister(EDIT_UPLOADED_PICTURES);
            session_unregister(EDIT_UPLOADED_PICTURES_SIZE);
            session_unregister(EDIT_GALLERY_UPDATES);

            if(file_exists($uploaded_path.$id))
            {
               $dir = @opendir($uploaded_path.$id);
               while($file = readdir($dir))
               {
                   if($file != "." && $file != "..")
                   {
                       $EDIT_UPLOADED_PICTURES[] = $file;
                       $EDIT_UPLOADED_PICTURES_SIZE[] = filesize($uploaded_path.$id."/".$file);
                   }
               }
               closedir($dir);
               $EDIT_GALLERY_DIR = $id;
							 $EXISTING_PICTURES = count($EDIT_UPLOADED_PICTURES);

               session_name($SESSION_NAME);
               session_register(EXISTING_PICTURES,EDIT_UPLOADED_PICTURES,EDIT_UPLOADED_PICTURES_SIZE,EDIT_GALLERY_DIR);

               if(count($EDIT_UPLOADED_PICTURES) > 0)
               {
                   $TPL_gallery = "[<A HREF=\"Javascript:window_open('edit_gallery.php?id=$id','gallery',400,500,10,01)\">$MSG_692</A>]";
               }
               else
               {
                   $TPL_gallery = "";
               }
            }


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
			$qurey = "select * from PHPAUCTIONPROPLUS_payments";
			$res_payment = mysql_query($qurey);
			if(!$res_payment)
			{
				MySQLError($query);
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
			$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories_plain");
			if($result):
				while($row=mysql_fetch_array($result)){
					$T.=
						"	<OPTION VALUE=\"".
						$row[cat_id].
						"\" ".
						(($row[cat_id]==$AUCTION[category])?"SELECTED":"")
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
			// -------------------------------------- buy now price
			if ($AUCTION[buy_now] > 0 || $buy_now_price)
			{
				$TPL_with_buy_now_selected = "CHECKED";
			}
			else
			{
				$TPL_without_buy_now_selected = "CHECKED";
			}
			
			include "header.php";
			include "templates/template_edit_auction_php.html";
			include "footer.php";
		}
	}

	exit;
?>