<?php
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

$buffer = file("./includes/friendmail.inc.php");

$i = 0;

$j = 0;

while($i < count($buffer)){

	if(!ereg("^#(.)*$",$buffer[$i])){

		$skipped_buffer[$j] = $buffer[$i];

		$j++;

	}

	$i++;

}

//--Retrieve message

$message = implode($skipped_buffer,"");

//--Change TAGS with variables content

$message = ereg_replace("<#s_name#>","$sender_name",$message);
$message = ereg_replace("<#s_email#>","$sender_email",$message);
$message = ereg_replace("<#s_comment#>","$sender_comment",$message);

$message = ereg_replace("<#f_name#>","$friend_name",$message);
$message = ereg_replace("<#f_email#>","$friend_email",$message);

$message = ereg_replace("<#i_title#>","$item_title",$message);
$message = ereg_replace("<#i_description#>","$item_description",$message);
$auction_url = "$SITE_URL"."item.php?id=$auction_id";
$message = ereg_replace("<#i_url#>","$auction_url",$message);

$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);                    	

mail($friend_email,"$MSG_905",stripslashes($message),"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]"); 


?>
