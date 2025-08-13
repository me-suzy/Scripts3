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

	include "includes/config.inc.php";
	include "includes/messages.inc.php";

	$result = mysql_query ( "SELECT * FROM PHPAUCTION_categories WHERE parent_id='0' ORDER BY cat_name" );
	$output = "<SELECT NAME=\"id\">\n";
	$output.= "	<OPTION VALUE=\"0\"></OPTION>\n";

		if ($result)
			$num_rows = mysql_num_rows($result);
		else
			$num_rows = 0;

		$i = 0;
		while($i < $num_rows){
			$category_id = mysql_result($result,$i,"cat_id");
			$cat_name = mysql_result($result,$i,"cat_name");
			$output .= "	<OPTION VALUE=\"$category_id\">$cat_name</OPTION>\n";
			$i++;
		}

	$output.= "	<OPTION VALUE=\"\"></OPTION>\n";
	$output.= "	<OPTION VALUE=\"0\">$MSG_292</OPTION>\n";
	$output.= "</SELECT>\n";

	$handle = fopen ( "categories_select_box.inc.php" , "w" );
	fputs ( $handle, $output );
	fclose ($handle);
	
?>

<H2>PHPauction - Populate Categories Script</H2>
<BR>
<BR>
categories_select_box.inc.php created.
<BR><BR>
You now need to run:
<UL>
<LI><A HREF="./util_cc2.php">util_cc2.php</A>
</UL>

