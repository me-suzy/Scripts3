<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

   require('./includes/messages.inc.php');
   require('./includes/config.inc.php');
 
	#// If user is not logged in redirect to login page
	if(!isset($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"]))
	{
		Header("Location: user_login.php");
		exit;
	}

	#// Get closed auctions with winners
	$query = "select * from PHPAUCTIONPROPLUS_winners WHERE winner='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]'";
	$res = @mysql_query($query);
	if(!$res)
	{
		MySQLError($query);
		exit;
	}
	else
	{
   		while($row = mysql_fetch_array($res))
   		{
			$FEE=$row[fee];
   			$query = "SELECT title FROM PHPAUCTIONPROPLUS_auctions WHERE id='$row[auction]'";
   			$r = @mysql_query($query);
   			if(!$r)
   			{
   				MySQLError($query);
   				exit;
   			}
   			$AUCTIONS[$row[auction]] = stripslashes(mysql_result($r,0,"title"));
   			
			#// Get seller's details
			$query = "SELECT nick,email FROM PHPAUCTIONPROPLUS_users WHERE id='$row[seller]'";
			$re_ = @mysql_query($query);
			if(!$re_)
			{
				MySQLError($query);
				exit;
			}

			$SELLER[$row[auction]] = $row[seller];
			$BID[$row[auction]] = $row[bid];
			$QTY[$row[auction]] = $row[quantity];
			$SELLER_NICK[$row[auction]] = mysql_result($re_,0,"nick");
			$SELLER_EMAIL[$row[auction]] = mysql_result($re_,0,"email");
			
			
           	#// Calculate buyer's FEE
            if($SETTINGS[buyerfinaltype] == 1)
            {
            	$PRICE = doubleval($row[bid]);
                $FEE_AMOUNT[$row[auction]] = ($PRICE / 100) * doubleval($SETTINGS[buyerfinalvalue]);
            }
            else
            {
	         	#// _____FIX FEE______else
                $FEE_AMOUNT[$row[auction]]  = doubleval($SETTINGS[buyerfinalvalue]);
            }
			
   		}
   	}
?>

<HTML>
<HEAD>
<TITLE><? print $SETTINGS[sitename]?></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" >

<?

require("header.php");
include "templates/template_buying_php.html";
include "./footer.php"; 

?>
</BODY>
</HTML>
