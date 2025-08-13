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

$buffer = file("./includes/mail_endauction_youwin.inc.php");

$i = 0;

$j = 0;

while($i < count($buffer)){

	if(!ereg("^#(.)*$",$buffer[$i])){

		$skipped_buffer[$j] = $buffer[$i];

		$j++;

	}

	$i++;

}

//--Reteve message

$message = implode($skipped_buffer,"");

//--Change TAGS with variables content
$message = ereg_replace("<#s_name#>",$Seller['name'],$message);
$message = ereg_replace("<#s_nick#>",$Seller['nick'],$message);
$message = ereg_replace("<#s_email#>",$Seller['email'],$message);
$message = ereg_replace("<#s_address#>",$Seller[''],$message);
$message = ereg_replace("<#s_city#>",$Seller[''],$message);
$message = ereg_replace("<#s_prov#>",$Seller['prov'],$message);
$message = ereg_replace("<#s_country#>",$Seller['country'],$message);
$message = ereg_replace("<#s_zip#>",$Seller['zip'],$message);
$message = ereg_replace("<#s_phone#>",$Seller['phone'],$message);

$message = ereg_replace("<#w_name#>",$Winner['name'],$message);
$message = ereg_replace("<#w_nick#>",$Winner['nick'],$message);

$message = ereg_replace("<#i_title#>",$Auction['title'],$message);
$message = ereg_replace("<#i_currentbid#>",print_money($Auction['current_bid']),$message);
$message = ereg_replace("<#i_description#>",$Auction['description'],$message);
$auction_url = "$SITE_URL"."item.php?id=".$Auction['id'];
$message = ereg_replace("<#i_url#>",$auction_url,$message);
$message = ereg_replace("<#i_ends#>",$ends_string,$message);

$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);                    	

mail($Winner["email"],"$MSG_909",stripslashes($message),"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]"); 


?>

