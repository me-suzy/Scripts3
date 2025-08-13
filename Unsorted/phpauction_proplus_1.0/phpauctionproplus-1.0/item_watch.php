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

	if (($add) && (!$add=="")) 
	{ 
		// Check if this item is not already added

		$query = "SELECT item_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
		        }
		$items = trim(mysql_result ($result,0,"item_watch"));
		if ($items!="disabled") 
			{
			$match = strstr($items, $add); 
			$items = $items;
			}
			else { $items = ""; }

		if (!$match)
				{
				$item_watch = trim("$items $add");
				$item_watch_new = trim($item_watch);
				$query = "UPDATE PHPAUCTIONPROPLUS_users set item_watch='$item_watch_new' where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
				$result = @mysql_query("$query"); 
					if(!$result)
		        		{
                        		MySQLError($query);
                        		exit;
		        		}
				}  
		else { }


		// Show results

		$query = "SELECT item_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
			}
		$items = trim(mysql_result ($result,0,"item_watch"));

		if (($items=="disabled") || ($items=="") || ($items=="NULL") ) { } else {
		$item = split(" ",$items);
		for ($j=0; $j < count($item); $j++) 
		{
		$query = "SELECT title from PHPAUCTIONPROPLUS_auctions where id='$item[$j]' ";
		$result = @mysql_query("$query");
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
			}
		$title = mysql_result ($result,0,"title");
		$TPL_item_watch.="<TR><TD><A HREF='item.php?id=$item[$j]'>$std_font $title</A></TD><TD ALIGN=RIGHT>
			  <A HREF='item_watch.php?delete=$item[$j]'><IMG SRC='./images/delete.gif' BORDER='0'></A>
			  </TD></TR>";
		}	}

	} 


		// Delete item form item watch

	if ($delete)
	{
		$query = "SELECT item_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
			}
		$items = trim(mysql_result ($result,0,"item_watch"));

		$auc_id = split(" ",$items);
		for ($j=0; $j < count($auc_id); $j++) 
			{
			$match = strstr($auc_id[$j],$delete);
			if ($match) { $item_watch = $item_watch; }
			else {
			$item_watch = "$auc_id[$j] $item_watch";
			} }
			$item_watch_new = trim($item_watch);
			if (($item_watch_new=="") || ($item_watch_new==" ")) { $item_watch_new="disabled"; }
			$query = "UPDATE PHPAUCTIONPROPLUS_users set item_watch='$item_watch_new' where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
			$result = @mysql_query("$query"); 
				if(!$result)
		        	{
                        	MySQLError($query);
                        	exit;
				}

		// Show results

		$query = "SELECT item_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
			}
		$items = trim(mysql_result ($result,0,"item_watch"));

		if (($items=="disabled") || ($items=="") || ($items=="NULL") ) { } else {
		$item = split(" ",$items);
		for ($j=0; $j < count($item); $j++) 
		{
		$query = "SELECT title from PHPAUCTIONPROPLUS_auctions where id='$item[$j]' ";
		$result = @mysql_query("$query");
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
			}
		$title = mysql_result ($result,0,"title");
		$TPL_item_watch.="<TR><TD><A HREF='item.php?id=$item[$j]'>$std_font $title</A></TD><TD ALIGN=RIGHT>
			  <A HREF='item_watch.php?delete=$item[$j]'><IMG SRC='./images/delete.gif' BORDER='0'></A>
			  </TD></TR>";
		}	}

	}

		// Show results if nothing changed

	if ((!$add) && (!$delete)) 
	{ 
		$query = "SELECT item_watch from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
			}
		$items = trim(mysql_result ($result,0,"item_watch"));

		if (($items=="disabled") || ($items=="") || ($items=="NULL") ) { } else {
		$item = split(" ",$items);
		for ($j=0; $j < count($item); $j++) 
		{
		$query = "SELECT title from PHPAUCTIONPROPLUS_auctions where id='$item[$j]' ";
		$result = @mysql_query("$query");
			if(!$result)
		        {
                        MySQLError($query);
                        exit;
			}
		$title = mysql_result ($result,0,"title");
		$TPL_item_watch.="<TR><TD><A HREF='item.php?id=$item[$j]'>$std_font $title</A></TD><TD ALIGN=RIGHT>
			  <A HREF='item_watch.php?delete=$item[$j]'><IMG SRC='./images/delete.gif' BORDER='0'></A>
			  </TD></TR>";
		}	}
	}
		


include "templates/template_item_watch_php.html";
include "./footer.php"; 

?>
</BODY>
</HTML>



