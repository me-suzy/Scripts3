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
	$query = "select * from PHPAUCTIONPROPLUS_winners WHERE seller='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]'";
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
        	$FEE = $row[fee];
   			$query = "SELECT title FROM PHPAUCTIONPROPLUS_auctions WHERE id='$row[auction]'";
   			$r = @mysql_query($query);
   			if(!$r)
   			{
   				MySQLError($query);
   				exit;
   			}


   			$AUCTIONS[$row[auction]] = stripslashes(mysql_result($r,0,"title"));
   			
   			#// Build winners array
   			$query = "SELECT * FROM PHPAUCTIONPROPLUS_winners WHERE auction='$row[auction]'";
   			$rr = @mysql_query($query);
   			if(!$rr)
   			{
   				MySQLError($query);
   				exit;
   			}

   			else
   			{


   				while($winner = mysql_fetch_array($rr))
   				{
   					#// Get user's details
   					$query = "SELECT nick,email FROM PHPAUCTIONPROPLUS_users WHERE id='$winner[winner]'";
   					$re_ = @mysql_query($query);
   					if(!$re_)
   					{
						MySQLError($query);
						exit;
					}
					
   					$WINNERS[$row[auction]] .= "|".$winner[winner];
   					$WINNERS_BID[$row[auction]] .= "|".$winner[bid];
   					$WINNERS_QTY[$row[auction]] .= "|".$winner[quantity];
   					$WINNERS_NICK[$row[auction]] .= "|".mysql_result($re_,0,"nick");
   					$WINNERS_EMAIL[$row[auction]] .= "|".mysql_result($re_,0,"email");
   			    	
   				}
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
include "templates/template_selling_php.html";
include "./footer.php"; 

?>
</BODY>
</HTML>
