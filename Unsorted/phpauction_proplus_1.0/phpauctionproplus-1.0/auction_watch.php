<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


  $err_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\" COLOR=red>";
  $std_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\">";

	// Include messages file	
   require('./includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('./includes/config.inc.php');
   
	#// If user is not logged in redirect to login page
	if(!isset($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"]))
	{
		Header("Location: user_login.php");
		exit;
	}
   

	
?>

<HTML>
<HEAD>
<TITLE><? print $SETTINGS[sitename]?></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" >

<?

require("header.php");

		// Auction id is present, now update table
	if ($insert=="true") 
	{ 
		// Check if this keyword is not already added
		echo "<input type=hidden name=add value=$add>";
		if ($add=="") 
			{ 
			$add="disabled"; 
			}
		$query = "SELECT auc_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]'";
		$result = @mysql_query($query); 
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
		        }
		$auctions = trim(mysql_result ($result,0,"auc_watch"));
		if ($auctions!="disabled") 
			{
			$match = strstr($auctions, $add); 
			$auctions = $auctions;
			$TPL_active="<a href='auction_watch.php?active=no'>deactivate ?</a>"; 
			}
			 else 	{ 
			 	$auctions = ""; 
				$TPL_active="<a href='auction_watch.php?active=no'>deactivate ?</a>"; 
				}

		if (!$match)
				{
				$auction_watch = trim("$auctions $add");
				$auction_watch_new = trim($auction_watch);
				$TPL_active="<a href='auction_watch.php?active=no'>deactivate ?</a>"; 
				$query = "UPDATE PHPAUCTIONPROPLUS_users set auc_watch='$auction_watch_new' where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
				$result = @mysql_query("$query"); 
					if(!$result)
		        		{
		                        MySQLError($query);
		                        exit;
				        }
				}  
		else { }


		// Show results
		$query = "SELECT auc_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
			MySQLError($query);
			exit;
			}
		$auctions = trim(mysql_result ($result,0,"auc_watch"));

		if (($auctions=="disabled") || ($auctions=="") || ($auctions=="NULL") ) { } else {
		$auction = split(" ",$auctions);
		for ($j=0; $j < count($auction); $j++) 
		{
		$TPL_auction_watch.="<TR><TD>$std_font $auction[$j]</TD><TD ALIGN=RIGHT>
			  <A HREF='auction_watch.php?delete=$auction[$j]'><IMG SRC='./images/delete.gif' BORDER='0'></A>
			  </TD></TR>";
		
		}	}

	} 


		// Delete auction form auction watch

	if ($delete)
	{
		$query = "SELECT auc_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
			MySQLError($query);
			exit;
			}
		$auctions = trim(mysql_result ($result,0,"auc_watch"));

		$auc_id = split(" ",$auctions);
		for ($j=0; $j < count($auc_id); $j++) 
			{
			$match = strstr($auc_id[$j],$delete);
			if ($match) 
				{ 
				$auction_watch = $auction_watch; 
				}
				else 
				{
				$auction_watch = "$auc_id[$j] $auction_watch";
				}
		 	}
			$auction_watch_new = trim($auction_watch);
			if (($auction_watch_new=="") || ($auction_watch_new==" ")) 
				{ $auction_watch_new="disabled"; 
				$TPL_active="not active"; 
				}
				else
				{
				$TPL_active="<a href='auction_watch.php?active=no'>deactivate ?</a>"; 
				}
			$query = "UPDATE PHPAUCTIONPROPLUS_users set auc_watch='$auction_watch_new' where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
			$result = @mysql_query("$query"); 
				if(!$result)
		        	{
				MySQLError($query);
				exit;
				}

		// Show results

		$query = "SELECT auc_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
			MySQLError($query);
			exit;
			}
		$auctions = trim(mysql_result ($result,0,"auc_watch"));

		if (($auctions=="disabled") || ($auctions=="") || ($auctions=="NULL") ) { } else {
		$auction = split(" ",$auctions);
		for ($j=0; $j < count($auction); $j++) 
		{
		$TPL_auction_watch.="<TR><TD>$std_font $auction[$j]</A></TD><TD ALIGN=RIGHT>
			  <A HREF='auction_watch.php?delete=$auction[$j]'><IMG SRC='./images/delete.gif' BORDER='0'></A>
			  </TD></TR>";
		}	}

	}

		// Disable auction watch
	if ($active=="no")
	{
	$query = "UPDATE PHPAUCTIONPROPLUS_users set auc_watch='disabled' where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]'";
	$result = @mysql_query($query); 
		if(!$result)
	        {
		MySQLError($query);
		exit;
		}   	
	}


		// Show results if nothing changed

	if ((!$add) && (!$delete)) 
	{ 
		$query = "SELECT auc_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
	        	{
			MySQLError($query);
			exit;
			}
		$auctions = trim(mysql_result ($result,0,"auc_watch"));

		if (($auctions=="disabled") || ($auctions=="") || ($auctions=="NULL") ) 
			{ $TPL_active="not active"; 
			} 
			else 
			{
			$TPL_active="<a href='auction_watch.php?active=no'>deactivate ?</a>"; 
			$auction = split(" ",$auctions);
			for ($j=0; $j < count($auction); $j++) 
			{
		$TPL_auction_watch.="<TR><TD>$std_font $auction[$j]</A></TD><TD ALIGN=RIGHT>
			  <A HREF='auction_watch.php?delete=$auction[$j]'><IMG SRC='./images/delete.gif' BORDER='0'></A>
			  </TD></TR>";

		}	}
	}
		


include "templates/template_auction_watch_php.html";
include "./footer.php"; 

?>
</BODY>
</HTML>



