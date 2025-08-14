<HTML><HEAD><META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1"><META NAME="Generator" CONTENT="NetObjects Fusion 4.0 for Windows"><TITLE>Fussnote</TITLE><META HTTP-EQUIV="refresh" CONTENT="240; URL=cron.php"><BASE TARGET="_parent"></HEAD><BODY>
<P><br><P>

<?php



/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/



        include("includes/config.inc.php");

        include("includes/messages.inc.php");

        include("includes/auction_types.inc.php");



        function openLogFile ()

        {

                global $logFileHandle,$logFileName;

                global $cronScriptHTMLOutput;


                $logFileHandle = @fopen ( $logFileName, "w" );

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

        $query = "SELECT * FROM ".$dbfix."_auctions WHERE ends<='$now' AND closed='0'";

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



                        /*        ***********************************

                                update database tables

                        ************************************* */



                                // update "auctions" table

                                $query = "UPDATE ".$dbfix."_auctions SET closed='1',date=$row[date],ends=$row[ends] WHERE id=\"$row[id]\"";

                                if ( !mysql_query($query) )

                                        errorLogSQL();

                                printLogL($query,1);



                                // update "categories" table - for counters

                                $cat_id = $row["category"];

                                $root_cat = $cat_id;

                                do

                                {

                                        // update counter for this category

                                        $query = "SELECT * FROM ".$dbfix."_categories WHERE cat_id=\"$cat_id\"";

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



                                                        $query = "UPDATE ".$dbfix."_categories SET counter='$R_counter', sub_counter='$R_sub_counter' WHERE cat_id=\"$cat_id\"";

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

                                $query = "SELECT * FROM ".$dbfix."_counters";

                                $result = mysql_query($query);

                                if ( $result )

                                {

                                        if ( mysql_num_rows($result)>0 )

                                        {

                                                $auctions = mysql_result($result,0,"auctions");



                                                if($auctions > 0) --$auctions;



                                                $query = "UPDATE ".$dbfix."_counters SET auctions=$auctions";

                                                printLogL($query,1);

                                                if ( !mysql_query($query) )

                                                        errorLogSQL();

                                                /*

                                                if ( !mysql_query($query) )

                                                        die (mysql_error());

                                                */

                                        }

                                        else

                                        {

                                                $query = "INSERT INTO ".$dbfix."_counters VALUES (0,0)";

                                                printLogL($query,1);

                                                if ( !mysql_query($query) )

                                                        errorLogSQL();

                                        }

                                }

                                else

                                        errorLogSQL();



                                /* retrieve seller info */

                                $query = "SELECT * FROM ".$dbfix."_users WHERE id='".$Auction["user"]."'";

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



                        /*        ***********************************

                                check if there is a winner - and get his info

                        ************************************* */

                        $winner_present = false;

                        $query = "SELECT * FROM ".$dbfix."_bids WHERE auction='".$row["id"]."' ORDER BY bid DESC";

                        printLogL($query,1);

                        $result = mysql_query ( $query );

                        if ( $result )

                        {

                                if ( mysql_num_rows($result)>0 and ( $row["current_bid"] > $row["reserve_price"] ))

                                {

                                        mysql_data_seek($result,0);

                                        $WinnerBid = mysql_fetch_array($result);

                                        $winner_present = true;



                                        /* get winner info */

                                        $query = "SELECT * FROM ".$dbfix."_users WHERE id='".$WinnerBid["bidder"]."'";

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



                        /*        ****************************************

                                send email to seller - to notify him

                        ****************************************** */



                                

                        printLogL ( "mail to seller: ".$Seller["email"], 1 );

            $i_title = $Auction["title"];

                $year    = substr($Auction['ends'],0,4);
                $month   = substr($Auction['ends'],4,2);
                $day     = substr($Auction['ends'],6,2);
                $hours   = substr($Auction['ends'],8,2);
                $minutes = substr($Auction['ends'],10,2);
                $ends_string   = $month . " " . $day . " " . $year . "  " . $hours . ":" . $minutes;

                //-- Send e-mail message

                   if ($winner_present) {
                include('includes/endauction_winner.inc.php');
            } else {
                include('includes/endauction_nowinner.inc.php');
            }

                        /*        ****************************************

                                send email to winner (if any)

                        ****************************************** */

                        if ( $winner_present )

                        {

                                printLogL ( "mail to winner: ".$Winner["email"], 1 );

                include('includes/endauction_youwin.inc.php');

                        }

                }

        }



        /*        ************************************************************************

                "remove" old auctions (archive them)

                ********************************************************************* */

        printLog("\n");

        printLog("++++++ Archiving old auctions");



        $expiredTime = date ( "YmdHis", time()-$expireAuction );

        $query = "SELECT * FROM ".$dbfix."_auctions WHERE ends<='$expiredTime'";

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

                                printLogL("Processing auction: ".$AuctionInfo["id"],0);



                                /* delete this auction */

                                $query= "DELETE FROM ".$dbfix."_auctions WHERE id='".$AuctionInfo["id"]."'";

                                if ( !mysql_query($query) )

                                        errorLogSQL();



                                /* delete bids for this auction */

                                $query = "SELECT * FROM ".$dbfix."_bids WHERE auction='".$AuctionInfo["id"]."'";

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

                                                        $query = "delete from ".$dbfix."_bids where auction='".$row["auction"]."'";

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

                errorLogSQL();



        /*        ************************************************************************

                "remove" old user sessions (archive them)

                ********************************************************************* */



        $sessionTime = date ( "YmdHis", time()-$sessionLifeTime );

        $query = "SELECT * FROM ".$dbfix."_sessions WHERE last_visit<='$sessionTime'";

        printLog($query);

        $result = mysql_query ($query);

        if ( $result )

        {

                $num = mysql_num_rows ($result);

                printLog($num." sessions to archive");

                if ( $num>0 )

                {

                        $resultSESSIONS = $result;

                        while ( $row=mysql_fetch_array($resultSESSIONS) )

                        {

                                $Session = $row;



                                /* delete this session */

                                $query = "DELETE FROM ".$dbfix."_sessions WHERE id='".$Session["id"]."'";

                                if ( !mysql_query($query) )

                                        errorLogSQL();

                        }

                }

        }

        else

                errorLogSQL();



        // finish cron script

        printLog ( "=========================== ENDING CRON");

        closeLogFile();

?>
