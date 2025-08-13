<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////



	/* Include messages file & Connect to sql server & inizialize configuration variables */
	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
	require('./includes/auction_types.inc.php');

	require("header.php");
 

	

   
	   			 mysql_query ("INSERT INTO PHPAUCTIONPROPLUS_request (req_auction, req_user, req_text) values ('$reqauction', '$requser', '$reqtext')");
	
				 $num = mysql_affected_rows();
				 
				 if ($num > 0)
				 	{


 print
	   
				   "<TABLE bgcolor=\"#FFFFFF\"BORDER=0 height=\"140\" WIDTH=\"100%\">".
				   "<TR>".
				    "<TD  ALIGN=Center>".
"<A HREF=\"item.php?SESSION_ID=".urlencode($sessionID)."&id=$id \">
$MSG_138</a>
				 <br> <br>".
				   
				 
				   "$std_font
				<B>Message posted</B></FONT></TD>".
				   "</TR>".
				   
				   "</TABLE>".
				   "<br>";
				   
				   
	    

          
   		 			}
				 else
				    print "verification error";
	
	
	
	

	require("footer.php");
?>