<?
/*

   Copyright (c), 1999, 2000 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/



	/* Include messages file & Connect to sql server & inizialize configuration variables */
	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
	require('./includes/auction_types.inc.php');

	require("header.php");
 

	

   
	   			 mysql_query ("INSERT INTO PHPAUCTION_request (req_auction, req_user, req_text) values ('$reqauction', '$requser', '$reqtext')");
	
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