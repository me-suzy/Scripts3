<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

        if(!$SETTINGS[cron] == 1)
        {
                #// Include fils only if run as cronjob
                include("./includes/config.inc.php");
                include("./includes/messages.inc.php");
                include("./includes/auction_types.inc.php");
        }

        function openLogFile ()
        {
                global $logFileHandle,$logFileName;
                global $cronScriptHTMLOutput;

                $logFileHandle = @fopen ( $logFileName, "a" );
                if ( $cronScriptHTMLOutput==true )
                        print "<PRE>\n";
        }

        function closeLogFile ()
        {
                global $logFileHandle;
                global $cronScriptHTMLOutput;

                if ( $logFileHandle )
                        fclose ( $logFileHandle );

                if ( $cronScriptHTMLOutput )
                        print "</PRE>\n";
        }

        function printLog ($str)
        {
                global $logFileHandle;
                global $cronScriptHTMLOutput;

                if($logFileHandle)
                {
                        if ( substr($str,strlen($str)-1,1)!="\n" )
                                $str .= "\n";

                        fwrite ( $logFileHandle, $str );

                        if ( $cronScriptHTMLOutput )
                                print "".$str;
                }
        }

        function printLogL ( $str,$level )
        {
                for($i=1;$i<=$level;++$i)
                        $str = "\t".$str;
                printLog($str);
        }

        function errorLog ($str)
        {
                global $logFileHandle, $adminEmail;

                printLog ($str);
                /*
                mail (
                        $adminEmail,
                        "An cron script error has occured",
                        $str,
                        "From: $adminEmail\n".
                        "Content-type: text/plain\n"
                );
                */
                closeLogFile();
                exit;
        }

        function errorLogSQL ()
        {
                global $query;
                errorLog (
                        "SQL query error: $query\n".
                        "Error: ".mysql_error()
                );
        }

        // initialize cron script
        openLogFile();
        printLog("=============== STARTING CRON SCRIPT: ".date("d m Y H:i:s"));

        /* ------------------------------------------------------------
                1) "close" expired auctions

                closing auction means:
                        a) update database:
                                + "auctions" table
                                + "categories" table - for counters
                                + "counters" table
                        b) send email to winner (if any) - passing seller's data
                        c) send email to seller (reporting if there was a winner)
        */
        printLog("++++++ Closing expired auctions");

        $now = date ( "YmdHis" );
        $query = "SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE ends<='$now' AND closed='0'";
        printLog ($query);
        $result = mysql_query($query);
        if (!$result)
                errorLogSQL();
        else
        {
                $num = mysql_num_rows($result);
                printLog($num." auctions to close");

                $resultAUCTIONS = $result;
                while ($row=mysql_fetch_array($resultAUCTIONS))
                {
                        $Auction = $row;
                        printLog( "\nProcessing auction: ".$row["id"] );

		  #//======================================================
		  #  BEGINNING OF ITEM WATCH CODE Rida nr 247
		  #//======================================================
		   // Send notification if user added auction closes 

		$ended_auction_id = "$row[id]";
    		$resultAUC = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_auctions where id='$ended_auction_id'");
				$title = mysql_result ($resultAUC,0,"title");

    		$resultUSERS = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_users");
			$num_users = mysql_num_rows($resultUSERS);
			$i = 0;
			while($i < $num_users)
			{
			$nickname = mysql_result ($resultUSERS,$i,"nick");
			$e_mail = mysql_result ($resultUSERS,$i,"email");
			$keyword = mysql_result ($resultUSERS,$i,"item_watch");
			$key = split(" ",$keyword);
				for ($j=0; $j < count($key); $j++) 
				{
				$match = strstr($key[$j],$ended_auction_id);
				}


			// If keyword matches with opened auction title or/and desc send user a mail
			if ($match) 
				{ 
				$sitename = $SETTINGS[sitename];
				$auction_url = $SETTINGS[siteurl] . "item.php?mode=1&id=".$ended_auction_id;

			// Mail body and mail() functsion
			include ("./templates/template_item_watch_endedmail_php.html");

				}
				else { } 


			$i++;
			}

		  #//======================================================
		  #  END OF ITEM WATCH CODE
		  #//======================================================

                        /*        ***********************************
                                update database tables
                        ************************************* */

                                // update "auctions" table
                                $query = "UPDATE PHPAUCTIONPROPLUS_auctions SET closed='1',starts=$row[starts],ends=$row[ends] WHERE id=\"$row[id]\"";
                                if ( !mysql_query($query) )
                                        errorLogSQL();
                                printLogL($query,1);

                                // update "categories" table - for counters
                                $cat_id = $row["category"];
                                $root_cat = $cat_id;
                                do
                                {
                                        // update counter for this category
                                        $query = "SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=\"$cat_id\"";
                                        $result = mysql_query($query);
                                        if ( $result )
                                        {
                                                if ( mysql_num_rows($result)>0 )
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

                                                        $query = "UPDATE PHPAUCTIONPROPLUS_categories SET counter='$R_counter', sub_counter='$R_sub_counter' WHERE cat_id=\"$cat_id\"";
                                                        if ( !mysql_query($query) )
                                                                errorLogSQL();
                                                        printLogL($query,1);

                                                        $cat_id = $R_parent_id;
                                                }
                                        }
                                        else
                                                errorLogSQL();
                                }
                                while ($cat_id!=0);

                                // update "counters" table - decrease number of auctions
                                                $query = "UPDATE PHPAUCTIONPROPLUS_counters SET auctions=(auctions-1),closedauctions=(closedauctions+1)";
                                                printLogL($query,1);
                                                if ( !mysql_query($query) )
                                                        errorLogSQL();
                                /* retrieve seller info */
                                $query = "SELECT * FROM PHPAUCTIONPROPLUS_users WHERE id='".$Auction["user"]."'";
                                printLogL($query,1);
                                $result = mysql_query ($query);
                                if ($result)
                                {
                                        if ( mysql_num_rows($result)>0 )
                                        {
                                                mysql_data_seek ($result,0 );
                                                $Seller = mysql_fetch_array($result);
                                        }
                                        else
                                                $Seller = array();
                                }
                                else
                                        errorLogSQL();

                        /**************************************************
                                check if there is a winner - and get his info
                        ***************************************************/
                        $winner_present = false;
                        $query = "SELECT * FROM PHPAUCTIONPROPLUS_bids WHERE auction='".$row['id']."' ORDER BY bid DESC";
                        printLogL($query,1);
                        $result = mysql_query ( $query );
                        if ( $result )
                        {
                                if ( mysql_num_rows($result)>0 and ( $row['current_bid'] > $row['reserve_price'] ))
                                {
                                        $decrem=mysql_num_rows($result);
                                        mysql_data_seek($result,0);
                                        $WinnerBid = mysql_fetch_array($result);
                                        $winner_present = true;

                                        /* get winner info */
                                        $query = "SELECT * FROM PHPAUCTIONPROPLUS_users WHERE id='".$WinnerBid['bidder']."'";
                                        $result = mysql_query ($query);
                                        if ( $result )
                                        {
                                                if ( mysql_num_rows($result)>0 )
                                                {
                                                        mysql_data_seek ( $result,0 );
                                                        $Winner = mysql_fetch_array($result);
                                                }
                                                else
                                                        $Winner = array ();
                                        }
                                        else
                                                errorLogSQL();

                                }
                        }
                        else
                                errorLogSQL();

                        /******************************************
                                send email to seller - to notify him
                        *******************************************/

                                /* create a "report" to seller depending of what kind auction is */
                                $atype = intval($Auction["auction_type"]);
                                if ( $atype==1 )
                                {
                                        /* Standard auction */
                                        if ( $winner_present )
                                        {
                                                $report_text = $Winner["nick"]." (".$Winner["email"].")\n";

                                                #// Add winner's data to "winners" table
                                                $query = "INSERT INTO PHPAUCTIONPROPLUS_winners VALUES (NULL,'".$Auction['id']."','".$Seller['id']."','".$Winner['id']."',".$Auction['current_bid'].",NULL,0)";
                                                $res = @mysql_query($query);
                                                /* Update column bid in table PHPAUCTIONPROPLUS_counters */
                                                $counterbid = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET bids=(bids-$decrem),transactions=(transactions+1)");

                                        }
                                        else
                                        {
                                                $report_text = $MSG_429;
                                        }
                                }
                                else
                                {
                                        /* Dutch auction */
                                        $report_text = "";
                                                // find out if there is a winner in this auction
                                                $query = "SELECT * FROM PHPAUCTIONPROPLUS_bids WHERE auction='".$Auction['id']."' ORDER BY bid DESC";
                                                $res = mysql_query ($query);
                                                if ( $res )
                                                {
                                                        $numDbids = mysql_num_rows($res);
                                                        /* Update column bid in table PHPAUCTIONPROPLUS_counters */
                                                        $counterbid = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET bids=(bids-$numDbids)");
                                                        if ( $numDbids==0 )
                                                                {$report_text = "Nobody bidded";}
                                                        else
                                                        {

                                                                $report_text = "";
                                                                $WINNING_BID = $WinnerBid;

                                                                $items_count = $Auction["quantity"];
                                                                $row = mysql_fetch_array($res);
                                                                do
                                                                {
                                                                        if($row[bid] < $WINNING_BID)
                                                                        {
                                                                                $WINNING_BID = $row[bid];
                                                                        }

                                                                        $items_wanted = $row["quantity"];
                                                                        $items_got = 0;

                                                                        if($items_wanted <= $items_count)
                                                                        {
                                                                                $items_got = $items_wanted;
                                                                                $items_count -= $items_got;
                                                                        }
                                                                        else
                                                                        {
                                                                                $items_got = $items_count;
                                                                                $items_count -= $items_got;
                                                                        }

                                                                        #// Retrieve winner nick from the database
                                                                        #// Added by Gianluca Jan. 9, 2002
                                                                        $query = "SELECT nick,email FROM PHPAUCTIONPROPLUS_users WHERE id='$row[bidder]'";
                                                                        //print "$query<BR>";
                                                                        $res_n = @mysql_query($query);
                                                                        $NICK = @mysql_result($res_n,0,"nick");
                                                                        $EMAIL = @mysql_result($res_n,0,"email");

                                                                        $report_text .= " $MSG_159 ".$NICK." ($EMAIL) ".$items_got." items, ".print_money($row["bid"])." for each\n";
                                                                        $totalamount=$row[bid];
                                                                        #// Add winner's data to "winners" table
                                                                        $query = "INSERT INTO PHPAUCTIONPROPLUS_winners
                                                                                          VALUES
                                                                                          (NULL,
                                                                                          '$Auction[id]',
                                                                                          '$Seller[id]',
                                                                                          '$row[bidder]',
                                                                                          $row[bid],
                                                                                          NULL,
                                                                                          0)";                                                                        //print $query."<BR>";
                                                                        $res_ = @mysql_query($query);
                                                                        /* Update column transaction in table PHPAUCTIONPROPLUS_counters */
                                                                        $counterbid = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET transactions=(transactions+1)");
                                                                        if(!$row = mysql_fetch_array($res)){
                                                                                break;}
                                                                  }
                                                                while(($items_count>0) && $res);

                                                                $report_text .= $MSG_643." ".print_money($WINNING_BID);

                                                                printLog($report_text);
                                                        }
                                                }
                                                else
                                                        errorLogSQL();
                                }

                printLogL ( "mail to seller: ".$Seller["email"], 1 );
            	$i_title = $Auction["title"];

                $year    = substr($Auction['ends'],0,4);
                $month   = substr($Auction['ends'],4,2);
                $day     = substr($Auction['ends'],6,2);
                $hours   = substr($Auction['ends'],8,2);
                $minutes = substr($Auction['ends'],10,2);
                $ends_string   = $month . " " . $day . " " . $year . "  " . $hours . ":" . $minutes;
                //-- Send e-mail message
                if ($winner_present)
                   {
                                #//======================================================
                                #  Original PHPAUCTION (GPL) command
                                #//======================================================
                				//include('./includes/endauction_winner.inc.php');
                				#//======================================================

                                #//======================================================
                                #  PHPAUCTION PRO code
                                #//======================================================
                                $balance=true;
                                $finalfee=0;
                                if($SETTINGS[sellerfinalfee] == 1)
                                {
                           	   	$finalfee=1;
                                   	#// Calculate seller's FEE
                                   	if($SETTINGS[sellerfinaltype] == 1)
                                   	{
                                           	$PRICE = doubleval($WinnerBid);
                                           	$FEE = ($PRICE / 100) * doubleval($SETTINGS[sellerfinalvalue]);
                               	 	}
                                   	else
                                   	{
	                               		#// _____FIX FEE______else
                                                $FEE = doubleval($SETTINGS[sellerfinalvalue]);
                                   	}
						if($SETTINGS[feetype] == "prepay")
						{
                                        	#// Check if the seller has enough credit to be charged
                                        	if($Seller[balance] < $FEE)
                                        	{
                                                	
                                                	#// Set "closed" value to -1 (fee pending) for this auction
                                                	$query = "UPDATE PHPAUCTIONPROPLUS_auctions SET closed='-1' where id='$Auction[id]'";
                                                	$res = @mysql_query($query);
                                             #// Set the entry in the winners table as "suspended"
                                                          #// FEE field values
                                                          #// 1 - seller and winner still have to pay
                                                          #// 2 - seller payed, winner still have to pay
                                                          #// 3 - buyer payed, seller still have to pay
                                                          #// 0 - seller and winnre payed
                                                          $query = "UPDATE PHPAUCTIONPROPLUS_winners 
                                                                    SET
                                                                    fee=1
                                                                    WHERE
                                                                    auction='$Auction[id]'";
                                                            $res = @mysql_query($query);   
													#// Mail seller to notify there's a winner but more
                                                	#// credit is needed
                                                	include('./includes/endauction_winner_nobalance.inc.php');

											}
           			            
					}	
					elseif($SETTINGS[feetype] == "pay")
					{
                                                #// Set auction to "fee pending" status
						$query = "UPDATE PHPAUCTIONPROPLUS_auctions
                                                          SET
                                                          closed='-1'
                                                          WHERE id='$Auction[id]'";
                                                          $res = @mysql_query($query);
															$balance=false;
                                                          #// Set the entry in the winners table as "suspended"
                                                          #// FEE field values
                                                          #// 1 - seller and winner still have to pay
                                                          #// 2 - seller payed, winner still have to pay
                                                          #// 3 - buyer payed, seller still have to pay
                                                          #// 0 - seller and winnre payed
                                                          $query = "UPDATE PHPAUCTIONPROPLUS_winners 
                                                                    SET
                                                                    fee=1
                                                                    WHERE
                                                                    auction='$Auction[id]'";
                                                            $res = @mysql_query($query);
										
                                                            #// Send mail to the seller
                                                            include('./includes/endauction_winner_pay.inc.php');
                                        }		
		 
                                    $finalfee=1;
                                    #// Calculate buyer's FEE
                                    if($SETTINGS[buyerfinaltype] == 1)
                                    {
                                     	$PRICE = doubleval($WinnerBid);
                                    	$FEE_BUY = ($PRICE / 100) * doubleval($SETTINGS[buyerfinalvalue]);
                                    }
                                    else
                                    #// _____FIX FEE_BUY______else
                                    {	
                               			$FEE_BUY = doubleval($SETTINGS[buyerfinalvalue]);
                                    }
                                    if($SETTINGS[feetype] == "prepay")
                                    {
                                        // Check if the buyer has enough credit to be charged
                                        if($Winner[balance] < $FEE_BUY)
                                        {
                                                
                                                #// Set "closed" value to -1 (fee pending) for this auction
                                                $query = "UPDATE PHPAUCTIONPROPLUS_auctions SET closed='-1' where ID='$Auction[id]'";
                                                		$res = @mysql_query($query);
                                                		$balance=false;
										#// Set the entry in the winners table as "suspended"
										#// FEE field values
										#// 1 - seller and winner still have to pay
										#// 2 - seller payed, winner still have to pay
										#// 3 - buyer payed, seller still have to pay
										#// 0 - seller and winnre payed
										$query = "UPDATE PHPAUCTIONPROPLUS_winners 
											      SET
											      fee=1
											      WHERE
											      auction='$AUCTION[ID]'";
										$res = @mysql_query($query);
												#// Mail buyer to notify he win but more
                                                #// credit is needed
                                                include('./includes/endauction_buyer_nobalance.inc.php');

                                				}
                                				
									}
									elseif($SETTINGS[feetype] == "pay")
									{
										#// Set auction to "fee pending" status
										$query = "UPDATE PHPAUCTIONPROPLUS_auctions
									 		 	SET
									  			closed='-1'
									  			WHERE id='$Auction[id]'";
												$res = @mysql_query($query);
										$res = @mysql_query($query);

										#// Set the entry in the winners table as "suspended"
										#// FEE field values
										#// 1 - seller and winner still have to pay
										#// 2 - seller payed, winner still have to pay
										#// 3 - buyer payed, seller still have to pay
										#// 0 - seller and winnre payed
										$query = "UPDATE PHPAUCTIONPROPLUS_winners 
											      SET
											      fee=1
											      WHERE
											      auction='$AUCTION[ID]'";
										$res = @mysql_query($query);

										#// Send mail to the winner
										include('./includes/endauction_youwin_pay.inc.php');
									}

                                 if (($balance<>false) && ($finalfee==1) && $SETTINGS[feetype] == "prepay")
                                 {
                                                #// Update seller's - buyer's balance
                                                $res = @mysql_query("UPDATE PHPAUCTIONPROPLUS_users SET balance=balance-$FEE WHERE id='$Seller[id]'");
                                                $resbuy = @mysql_query("UPDATE PHPAUCTIONPROPLUS_users SET balance=balance-$FEE_BUY WHERE id='$Winner[id]'");

                                                #// Update seller's account
                                                $TODAY = date("Ymd");
                                                $DESCR = "<A HREF=item.php?id=$Auction[id] TARGET=_blank>$MSG_452</A>";
                                                $query = "INSERT INTO PHPAUCTIONPROPLUS_accounts
                                                                                         VALUES
                                                                                         (NULL,
                                                                                         '$Seller[id]',
                                                                                         '$DESCR',
                                                                                         '$TODAY',
                                                                                         1,
                                                                                         $FEE,
                                                                                         $Seller[balance]-$FEE,
                                                                                         '$Auction[id]')";
                                                $res = mysql_query($query);
                                                if(!$res) print $query."<BR>".mysql_error();
                                                $query_buy = "INSERT INTO PHPAUCTIONPROPLUS_accounts
                                                                                         VALUES
                                                                                         (NULL,
                                                                                         '$Winner[id]',
                                                                                         '$DESCR',
                                                                                         '$TODAY',
                                                                                         1,
                                                                                         $FEE_BUY,
                                                                                         $Winner[balance]-$FEE_BUY,
                                                                                         '$Auction[id]')";
                                                $res_buy = mysql_query($query_buy);
                                                if(!$res_buy) print $query."<BR>".mysql_error();
                                                /* Update column totalamount,fee in table PHPAUCTIONPROPLUS_counters */
                                                if (!$transaction)
												{
                                                        $transaction=$Auction['current_bid'];
                                                }
                                                $counterbid = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET totalamount=(totalamount+$transaction),fees=(fees+$FEE+$FEE_BUY)");
                                                 $close = mysql_query("UPDATE PHPAUCTIONPROPLUS_auctions SET closed='1' where ID='$Auction[id]'");

                                                #// Mail seller
                                                include('./includes/endauction_winner.inc.php');
                                                if ( $winner_present )
                                                {
                                                	printLogL ( "mail to winner: ".$Winner["email"], 1 );
                                                	include('./includes/endauction_youwin.inc.php');
                                                }
                                        }

								}

                                if ($finalfee<>1)
                                {
                                 include('./includes/endauction_winner.inc.php');
                                  if ( $winner_present )
                                  {
                                         printLogL ( "mail to winner: ".$Winner["email"], 1 );
                                         include('./includes/endauction_youwin.inc.php');
                                   }
                     }
            }
            else
            {
                include('./includes/endauction_nowinner.inc.php');
            }
                        
                }
        }

        /*************************************************************************
        "remove" old auctions (archive them)
        ********************************************************************* */
        printLog("\n");
        printLog("++++++ Archiving old auctions");

        $expiredTime = date ( "YmdHis", time()-$expireAuction );

        $query = "SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE ends<='$expiredTime'";
        printLog($query);
        $result = mysql_query($query);
        if ( $result )
        {
                $num = mysql_num_rows($result);
                printLog($num." auctions to archive");
                if ($num>0)
                {
                        $resultCLOSEDAUCTIONS = $result;
                        while ( $row = mysql_fetch_array($resultCLOSEDAUCTIONS,MYSQL_ASSOC) )
                        {
                                $AuctionInfo = $row;
                                printLogL("Processing auction: ".$AuctionInfo['id'],0);

                                /* delete this auction */
                                $query= "DELETE FROM PHPAUCTIONPROPLUS_auctions WHERE id='".$AuctionInfo['id']."'";
                                if ( !mysql_query($query) )
                                        errorLogSQL();

                                /* delete bids for this auction */
                                $query = "SELECT * FROM PHPAUCTIONPROPLUS_bids WHERE auction='".$AuctionInfo['id']."'";
                                $result = mysql_query($query);
                                if ( $result )
                                {
                                        $num = mysql_num_rows($result);
                                        if ( $num>0 )
                                        {
                                                printLogL ($num." bids for this auction to delete",1);
                                                $resultBIDS = $result;
                                                while ( $row = mysql_fetch_array($resultBIDS,MYSQL_ASSOC) )
                                                {
                                                        /* archive this bid */
                                                        $query = "delete from PHPAUCTIONPROPLUS_bids where auction='".$row['auction']."'";
                                                        $res = mysql_query($query);
                                                        if ( !$res )
                                                                errorLogSQL();
                                                }
                                        }
                                }
                                else
                                        errorLogSQL();

                        }
                }
        }
        else
        {
                errorLogSQL();
        }










        //***** control if there are some suspended auction and see if now user can pay fee ****///

		if($SETTINGS[feetype] == "prepay")
		{
	        $now = date ( "YmdHis" );
 	       $auctione = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE ends<='$now' AND closed='-1'");
  	      while ($auction_result=mysql_fetch_array($auctione))
   	      {
    	     	//echo  $auction_result["user"];
     	    /*********************************************** retrieve seller info *************************************/
      	                   $query = "SELECT * FROM PHPAUCTIONPROPLUS_users WHERE id='".$auction_result["user"]."'";
                                printLogL($query,1);
                                $result = mysql_query ($query);
                                if ($result)
                                {
                                        if ( mysql_num_rows($result)>0 )
                                        {
                                                mysql_data_seek ($result,0 );
                                                $Seller = mysql_fetch_array($result);
                                        }
                                        else
                                                $Seller = array();
                                }
                                else
                                        errorLogSQL();

         /*-***************************************** retrieve buyer info ****************************************/
                        $query = "SELECT * FROM PHPAUCTIONPROPLUS_bids WHERE auction='".$auction_result['id']."' ORDER BY bid DESC";
                        printLogL($query,1);
                        $result = mysql_query ( $query );
                        if ( $result )
                        {
                                if (mysql_num_rows($result)>0 and ($auction_result['current_bid'] > $auction_result['reserve_price']))
                                {
                                        $decrem=mysql_num_rows($result);
                                        mysql_data_seek($result,0);
                                        $WinnerBid = mysql_fetch_array($result);
                                        $winner_present = true;

                                        /* get winner info */
                                        $query = "SELECT * FROM PHPAUCTIONPROPLUS_users WHERE id='".$WinnerBid['bidder']."'";
                                        $result = mysql_query ($query);
                                        if ( $result )
                                        {
                                                if ( mysql_num_rows($result)>0 )
                                                {
                                                        mysql_data_seek ( $result,0 );
                                                        $Winner = mysql_fetch_array($result);
                                                }
                                                else
                                                        $Winner = array ();
                                        }
                                        else
                                                errorLogSQL();

                                }
                        }

                          $balance=true;
                          $finalfee=0;
               /**********************************get the fee********************************************/
                           if($SETTINGS[sellerfinalfee] == 1)
                                {
                                $finalfee=1;
                                        #// Calculate seller's FEE
                                        if($SETTINGS[sellerfinaltype] == 1)
                                        {
                                                $PRICE = doubleval($WinnerBid);
                                                $FEE = ($PRICE / 100) * doubleval($SETTINGS[sellerfinalvalue]);
                                        }
                                        else
                                        #// _____FIX FEE______else
                                        {
                                                $FEE = doubleval($SETTINGS[sellerfinalvalue]);
                                        }

                                        #// Check if the seller has enough credit to be charged
                                        if($Seller[balance] < $FEE)
                                        {
                                                $balance=false;
                                        }

                                  }
                               
                               if($SETTINGS[buyerfinalfee] == 1)
                                {
                                $finalfee=1;
                                        #// Calculate buyer's FEE
                                        if($SETTINGS[buyerfinaltype] == 1)
                                        {
                                                $PRICE = doubleval($WinnerBid);
                                                $FEE_BUY = ($PRICE / 100) * doubleval($SETTINGS[buyerfinalvalue]);
                                        }
                                        else
                                        #// _____FIX FEE_BUY______else
                                        {
                                                $FEE_BUY = doubleval($SETTINGS[buyerfinalvalue]);
                                        }

                                        #// Check if the buyer has enough credit to be charged
                                        if($Winner[balance] < $FEE_BUY)
                                        {
                                                $balance=false;
                                        }

                                }
                                
                                        if (($balance<>false) && ($finalfee==1))
                                        {
                                                #// Update seller's - buyer's balance
                                                $res = @mysql_query("UPDATE PHPAUCTIONPROPLUS_users SET balance=balance-$FEE WHERE id='$Seller[id]'");
                                                $resbuy = @mysql_query("UPDATE PHPAUCTIONPROPLUS_users SET balance=balance-$FEE_BUY WHERE id='$Winner[id]'");

                                                #// Update seller's account
                                                $TODAY = date("Ymd");
                                                $DESCR = "<A HREF=item.php?id=$Auction[id] TARGET=_blank>$MSG_452</A>";
                                                $query = "INSERT INTO PHPAUCTIONPROPLUS_accounts
                                                                                         VALUES
                                                                                         (NULL,
                                                                                         '$Seller[id]',
                                                                                         '$DESCR',
                                                                                         '$TODAY',
                                                                                         1,
                                                                                         $FEE,
                                                                                         $Seller[balance]-$FEE,
                                                                                         '$Auction[id]')";
                                                $res = mysql_query($query);
                                                if(!$res) print $query."<BR>".mysql_error();
                                                $query_buy = "INSERT INTO PHPAUCTIONPROPLUS_accounts
                                                                                         VALUES
                                                                                         (NULL,
                                                                                         '$Winner[id]',
                                                                                         '$DESCR',
                                                                                         '$TODAY',
                                                                                         1,
                                                                                         $FEE_BUY,
                                                                                         $Winner[balance]-$FEE_BUY,
                                                                                         '$Auction[id]')";
                                                $res_buy = mysql_query($query_buy);
                                                if(!$res_buy) print $query."<BR>".mysql_error();
                                                /* Update column totalamount,fee in table PHPAUCTIONPROPLUS_counters */
                                                if (!$transaction){
                                                        $transaction=$Auction['current_bid'];
                                                }
                                                $counterbid = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET totalamount=(totalamount+$transaction),fees=(fees+$FEE+$FEE_BUY)");
                                                $close = mysql_query("UPDATE PHPAUCTIONPROPLUS_auctions SET closed='1' where ID='$Auction[id]'");

                                                #// Mail seller
                                                include('./includes/endauction_winner.inc.php');
                                                if ( $winner_present )
                                                {
                                                printLogL ( "mail to winner: ".$Winner["email"], 1 );
                                                include('./includes/endauction_youwin.inc.php');
                                                }
                                        }

				        }
		}

        // finish cron script
        printLog ( "=========================== ENDING CRON");
        closeLogFile();

?>