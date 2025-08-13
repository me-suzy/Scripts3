<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	include "includes/config.inc.php";
	include "includes/messages.inc.php";
	//include "includes/check-key.php";

	

	#// Receive a POST from Paypal and update the database

	if(isset($HTTP_POST_VARS))

	{

		if($HTTP_POST_VARS[item_name] == "BuyCredits")

		{

			#//User's data

			$query = "SELECT * from PHPAUCTIONPROPLUS_users WHERE id='$HTTP_POST_VARS[item_number]'";

			//mail("webmaster@phpelance.com","",$query);

			$rr = @mysql_query($query);

			if(!$rr)

			{

				mail($SETTINGS[adminmail],"ERROR processing PayPal response",$query."\n".mysql_error(),$SETTINGS[adminmail]);
			}

			else

			{

				$USER = @mysql_fetch_array($rr);
			}



			if($HTTP_POST_VARS[payment_status] == "Completed")

			{

				#// Update users table

				$query = "UPDATE PHPAUCTIONPROPLUS_users 
						  SET 
						  balance=balance+$HTTP_POST_VARS[payment_gross]
						  WHERE id='$HTTP_POST_VARS[item_number]'";
				$res = @mysql_query($query);

				if(!$res)

				{

					//mail("webmaster@phpelance.com","ERROR",mysql_error());

					exit;

				}

				else

				{

					$BALANCE = $USER[balance] + $HTTP_POST_VARS[payment_gross];

					$TODAY = date("Ymd");
					
						#// Add amount to counters table under the total
						$query = "UPDATE PHPAUCTIONPROPLUS_counters set transactions=(transactions+1), totalamount=(totalamount+$HTTP_POST_VARS[payment_gross])";

						$res = @mysql_query($query);
						if(!$res)
						{
							mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
							exit;
						}

					#// Add record to user's account

					$query = "INSERT INTO PHPAUCTIONPROPLUS_accounts 

							  VALUES

							  (NULL,

							  '$HTTP_POST_VARS[item_number]',

							  '$MSG_446',

							  '$TODAY', 
							  2,
							  ".doubleval($HTTP_POST_VARS[payment_gross]).",

							  ".doubleval($BALANCE).",

							  '')";

					$res = @mysql_query($query);

					if(!$res)

					{

						// mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());

						exit;

					}

					
					#// If this is the first credits purchase and the user just 
					#// registered (suspended=9) -> activate
					#// charge the user the sign up fee
					if($USER[suspended] == 9)
					{
						$BALANCE = $BALANCE - $SETTINGS[signupvalue];						
						$query = "UPDATE PHPAUCTIONPROPLUS_users 
								  SET
								  balance=$BALANCE,
								  suspended=0
								  WHERE id='$HTTP_POST_VARS[item_number]'";
						$rr_ = @mysql_query($query);
						if(!$rr_)
						{
							//mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
							exit;
						}

						#// Add amount to counters table under the fees
						$query = "UPDATE PHPAUCTIONPROPLUS_counters set transactions=(transactions+1), fees=(fees+$SETTINGS[signupvalue])";

						$res = @mysql_query($query);
						if(!$res)
						{
							mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
							exit;
						}

							#// Update counters table
							$query = "UPDATE PHPAUCTIONPROPLUS_counters 
									  SET
									  users=(users+1),inactiveusers=inactiveusers-1";
							$re_ = @mysql_query($query);
							//print $query."<BR>";
							if(!$re_)
							{
								print "ERROR $query<BR>".mysql_error();
								mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
								exit;
							}

						#// Add record to user's account
						$query = "INSERT INTO PHPAUCTIONPROPLUS_accounts 
								  VALUES
								  (NULL,
								  '$HTTP_POST_VARS[item_number]',
								  '$MSG_417',
								  '$TODAY', 
								  1,
							 	 ".doubleval($SETTINGS[signupvalue]).",
								  ".doubleval($BALANCE).",
								  '')";
						$res = @mysql_query($query);
						if(!$res)
						{
							mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
							exit;
						}

					}						
					
					#// Send notification e-mail to the user

					include "lib/includes/credits_confirmation.inc.php";

					mail($TO,$SUBJECT,$MESSAGE,$FROM);


					print "Paypal Payment simulation completed.<BR>
						   	<A HREF=credits_account.php>Click here</A> to return to Phpauction Pro demo";
					
				}

			}

		}
		elseif($HTTP_POST_VARS[item_name] == "PaySignUpFee")
		{
			#//User's data
			$query = "SELECT * from PHPAUCTIONPROPLUS_users WHERE id='".$HTTP_POST_VARS[item_number]."'";
			//mail("webmaster@phpelance.com","",$query);
			$rr = mysql_query($query);
			if(!$rr)
			{
				print "ERROR $query<BR>".mysql_error();
				mail($SETTINGS[adminmail],"ERROR processing PayPal response",$query."\n".mysql_error(),$SETTINGS[adminmail]);
			}
			else
			{
				$USER = @mysql_fetch_array($rr);
			}

			if($HTTP_POST_VARS[payment_status] == "Completed")
			{
				#// Update users table
				$query = "UPDATE PHPAUCTIONPROPLUS_users SET balance=balance+$HTTP_POST_VARS[payment_gross],suspended=0 WHERE id='$HTTP_POST_VARS[item_number]'";
				$res = mysql_query($query);
				if(!$res)
				{
					print "ERROR $query<BR>".mysql_error();
					//mail("webmaster@phpelance.com","ERROR",mysql_error());
					exit;
				}
				else
				{
					$BALANCE = $USER[balance] + $HTTP_POST_VARS[payment_gross];
					$TODAY = date("Ymd");
					
						#// Add amount to counters table under the fees
						$query = "UPDATE PHPAUCTIONPROPLUS_counters set transactions=(transactions+1), users=(users+1), fees=(fees+$HTTP_POST_VARS[payment_gross])";

						$res = @mysql_query($query);
						if(!$res)
						{
							mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
							exit;
						}

					#// Add record to user's account
					$query = "INSERT INTO PHPAUCTIONPROPLUS_accounts 
							  VALUES
							  (NULL,
							  '$HTTP_POST_VARS[item_number]',
							  '$MSG_417',
							  '$TODAY',
							  1,
							  ".doubleval($HTTP_POST_VARS[payment_gross]).",
							  ".doubleval($BALANCE).",
							  '')";
					$res = @mysql_query($query);
					//print $query."<BR>";
					if(!$res)
					{
						print "ERROR $query<BR>".mysql_error();
						//mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
						exit;
					}
					
					#// Update counters table
					$query = "UPDATE PHPAUCTIONPROPLUS_counters 
							  SET
							  inactiveusers=inactiveusers-1";
					$re_ = @mysql_query($query);
					//print $query."<BR>";
					if(!$re_)
					{
						print "ERROR $query<BR>".mysql_error();
						mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
						exit;
					}

					#// Send notification e-mail to the user
					include "lib/includes/signup_fee_confirmation_pay.inc.php";
					mail($TO,$SUBJECT,$MESSAGE,$FROM);
					
					print "Paypal Payment simulation completed.<BR>
						   	<A HREF=credits_account.php>Click here</A> to return to Phpauction Pro demo";
				}
			}
		}
		elseif($HTTP_POST_VARS[item_name] == "PayAuctionSetupFee")
		{


			#// Activate auction
			$query = "UPDATE PHPAUCTIONPROPLUS_auctions
					  SET
					  suspended=0
					  WHERE id='$HTTP_POST_VARS[item_number]'";
			$res = @mysql_query($query);
			if(!$res)
			{
				print "ERROR $query<BR>".mysql_error();
				mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
				#// Add amount to counters table under the fees
				$query = "UPDATE PHPAUCTIONPROPLUS_counters set transactions=(transactions+1), auctions=(auctions+1), fees=(fees+$HTTP_POST_VARS[payment_gross])";

				$res = @mysql_query($query);
				if(!$res)
				{
					mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
					exit;
				}

			#// Retrieve user's ID
			$query = "SELECT user,title FROM PHPAUCTIONPROPLUS_auctions WHERE id='$HTTP_POST_VARS[item_number]'";
			$res = @mysql_query($query);
			if(!$res)
			{
				print "ERROR $query<BR>".mysql_error();
				mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
			$USER = mysql_result($res,0,"user");
			$TITLE = mysql_result($res,0,"title");
						
			#// Get auction's data
			$query = "SELECT email FROM PHPAUCTIONPROPLUS_users WHERE id='$USER'";
			$res = @mysql_query($query);
			if(!$res)
			{
				print "ERROR $query<BR>".mysql_error();
				mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
			$EMAIL = mysql_result($res,0,"email");
			
			
			$TODAY = date("Ymd");

			
			$query = "INSERT INTO PHPAUCTIONPROPLUS_accounts 
					  VALUES
					  (NULL,
					  '$USER',
					  '".$MSG_486.$HTTP_POST_VARS[item_number]."',
					  '$TODAY',
					  1,
					  ".doubleval($HTTP_POST_VARS[payment_gross]).",
					  ".doubleval($BALANCE).",
					  '$HTTP_POST_VARS[item_number]')";
			$res = @mysql_query($query);
			//print $query."<BR>";
			if(!$res)
			{
				print "ERROR $query<BR>".mysql_error();
				//mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
			#// Send notification e-mail to the user
			include "includes/setup_fee_confirmation_pay.inc.php";
			mail($TO,$SUBJECT,$MESSAGE,$FROM);
			
			print "Paypal Payment simulation completed.<BR>
				   	<A HREF=credits_account.php>Click here</A> to return to Phpauction Pro demo";
			
		}					
		elseif($HTTP_POST_VARS[item_name] == "PayBuyerFinalUpFee")
		{
			#// Retrieve auction id and winner id
			$TMP = explode("+",urlencode($HTTP_POST_VARS[item_number]));
			$BUYER = $TMP[0];
			$AUCTION = $TMP[1];

			$query = "SELECT fee,bid FROM PHPAUCTIONPROPLUS_winners WHERE auction='$AUCTION' AND winner='$BUYER'";
			$res = @mysql_query($query);
			//print "$query<BR>";
			if(!$res)
			{
				print "ERROR $query<BR>".mysql_error();
				//mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
			else
			{
				$FEE = mysql_result($res,0,"fee");
				$BID = mysql_result($res,0,"bid");
			}

			if($FEE == 0 || $FEE == 3) exit; //Buyer already payed
			
			#// Update PHPAUCTIONPROPLUS_winners table
			if($FEE == 1)
			{
				$NEWVALUE = 3;
			}
			elseif($FEE == 2)
			{
				$NEWVALUE = 0;
				
				#// Also update auction's record to set the closed flag to 1
				$query = "UPDATE PHPAUCTIONPROPLUS_auctions SET closed=1 WHERE id='$AUCTION'";
				$res = @mysql_query($query);
				if(!$res)
				{
					print "ERROR $query<BR>".mysql_error();
					//mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
					exit;
				}
			}


           	#// Calculate buyer's FEE
            if($SETTINGS[buyerfinaltype] == 1)
            {
            	$PRICE = doubleval($BID);
                $AMOUNT = ($PRICE / 100) * doubleval($SETTINGS[buyerfinalvalue]);
            }
            else
            {
	         	#// _____FIX FEE______else
                $AMOUNT  = doubleval($SETTINGS[buyerfinalvalue]);
            }
            			
			$query = "UPDATE PHPAUCTIONPROPLUS_winners
				      SET			
					  fee=$NEWVALUE
					  WHERE
					  auction='$AUCTION' 
					  AND
					  winner='$BUYER'";
			//print "$query<BR>";
			$res = @mysql_query($query);
			if(!$res)
			{
				print "ERROR $query<BR>".mysql_error();
				//mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
            
            $TODAY = date("Ymd");
            
            #// Get buyer's balance
            $query = "SELECT balance FROM PHPAUCTIONPROPLUS_users WHERE id='$BUYER'";
            $res = @mysql_query($query);
			if(!$res)
			{
				mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
            else
            {
            	$BALANCE = mysql_result($res,0,"balance");
            }
            			

			#// Add amount to counters table under the fees
			$query = "UPDATE PHPAUCTIONPROPLUS_counters set transactions=(transactions+1), fees=(fees+$HTTP_POST_VARS[payment_gross])";

			$res = @mysql_query($query);
			if(!$res)
			{
				mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}

			#// Insert record in the winner's account
			$query = "INSERT INTO PHPAUCTIONPROPLUS_accounts 
					  VALUES
					  (NULL,
					  '$BUYER',
					  '".$MSG_495.$AUCTION."',
					  '$TODAY', 
					  1,
					  ".doubleval($HTTP_POST_VARS[payment_gross]).",
					  ".doubleval($BALANCE).",
					  '$AUCTION')";
			$res = @mysql_query($query);
			print "$query<BR>";
			if(!$res)
			{
				mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
			
			print "Paypal Payment simulation completed.<BR>
				   	<A HREF=credits_account.php>Click here</A> to return to Phpauction Pro demo";



		}					
		elseif($HTTP_POST_VARS[item_name] == "PaySellerFinalFee")
		{
			#// Retrieve auction id and seller id
			$TMP = explode("+",urlencode($HTTP_POST_VARS[item_number]));
			$SELLER = $TMP[0];
			$AUCTION = $TMP[1];

			$query = "SELECT * FROM PHPAUCTIONPROPLUS_winners
					  WHERE
					  auction='$AUCTION'
					  AND
					  seller='$SELLER'
					  AND
					  (fee=1 OR fee=3)";
			$res = @mysql_query($query);
			print "$query<BR>";
			if(!$res)
			{
				print "ERROR $query<BR>".mysql_error();
				//mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
				exit;
			}
			elseif(mysql_num_rows($res) == 0)
			{
				exit;
			}
			else
			{
				$BID = 0;
				#// Loop through winners
				while($row = mysql_fetch_array($res))
				{
					if($row[bid] > $BID) $BID = $row[bid];
					#// Update record
					if($row[fee] == 1)
					{
						$NEWVALUE = 2;
					}
					elseif($row[fee] == 3)
					{
						$NEWVALUE = 0;					
					}
					
					$query = "UPDATE PHPAUCTIONPROPLUS_winners
							  SET
							  fee=$NEWVALUE
							  WHERE id=$row[id]";
					$r = @mysql_query($query);
					print "$query<BR>";		
				}
				

	           	#// Calculate seller's FEE
 	            if($SETTINGS[sellerfinaltype] == 1)
   	            {
   	 	        	$PRICE = doubleval($BID);
    	            $AMOUNT = ($PRICE / 100) * doubleval($SETTINGS[sellerinalvalue]);
      	        }
       	   	    else
 	       	    {
					#// _____FIX FEE______else
          	      	$AMOUNT  = doubleval($SETTINGS[sellerfinalvalue]);
          	    }
				
				#// Create seller's account entry
	            $TODAY = date("Ymd");
 	           
				#// Get seller's balance
	            $query = "SELECT balance FROM PHPAUCTIONPROPLUS_users WHERE id='$SELLER'";
	            $res = @mysql_query($query);
				if(!$res)
				{
					mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
					exit;
				}
 	           	else
  	          	{
   	 	        	$BALANCE = mysql_result($res,0,"balance");
				}
            			
				#// Add amount to counters table under the fees
				$query = "UPDATE PHPAUCTIONPROPLUS_counters set transactions=(transactions+1), fees=(fees+$HTTP_POST_VARS[payment_gross])";

				$res = @mysql_query($query);
				if(!$res)
				{
					mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
					exit;
				}

				#// Insert record in the winner's account
				$query = "INSERT INTO PHPAUCTIONPROPLUS_accounts 
						  VALUES
						  (NULL,
						  '$SELLER',
						  '".$MSG_495.$AUCTION."',
						  '$TODAY', 
						  1,
						  ".doubleval($HTTP_POST_VARS[payment_gross]).",
						  ".doubleval($BALANCE).",
						  '$AUCTION')";
				$res = @mysql_query($query);
				print "$query<BR>";
				if(!$res)
				{
					mail("webmaster@phpelance.com","ERROR",$query."\n".mysql_error());
					exit;
				}
			
				
			}
			
			print "Paypal Payment simulation completed.<BR>
				   	<A HREF=credits_account.php>Click here</A> to return to Phpauction Pro demo";



		}					


	}

?>