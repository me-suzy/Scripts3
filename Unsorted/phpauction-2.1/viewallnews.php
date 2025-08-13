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


	// Include messages file	
   require('./includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('./includes/config.inc.php');
   

	
?>

<HTML>
<HEAD>
<TITLE></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" TEXT="#08428C" LINK="#08428C" VLINK="#08428C" ALINK="#08428C">

<?

require("header.php");

$query = "SELECT * from PHPAUCTION_news where suspended=0 order by new_date";
$res = mysql_query($query);
if(!$res)
{
	print $ERR_001;
	exit;
}

while($new = mysql_fetch_array($res))
{
	$TPL_all_news .= "<strong><big>·</big></strong>
							$std_font<A HREF=\"viewnew.php?id=$new[id]\">$new[title]</A>
							<BR>";
}

include "templates/template_view_allnews_php.html";

?>

<? require("./footer.php"); ?>
</BODY>
</HTML>
