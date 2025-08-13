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


$buffer = file("./includes/auctionmail.inc.php");

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

$message = ereg_replace("<#c_name#>","$user_name",$message);


$message = ereg_replace("<#c_nick#>","$nick",$message);

$message = ereg_replace("<#c_address#>","$user_address",$message);

$message = ereg_replace("<#c_city#>","$user_country",$message);

$message = ereg_replace("<#c_country#>","$user_country",$message);

$message = ereg_replace("<#c_zip#>","$user_zip",$message);

$message = ereg_replace("<#c_email#>","$user_email",$message);


if($sessionVars["SELL_atype"] == 1)
{
	$message = ereg_replace("<#a_type#>",$MSG_642,$message);
}
else
{
	$message = ereg_replace("<#a_type#>",$MSG_641,$message);
}


$message = ereg_replace("<#a_qty#>",$sessionVars["SELL_iquantity"],$message);
$message = ereg_replace("<#a_title#>",$title,$message);
$message = ereg_replace("<#a_id#>","$auction_id",$message);
$message = ereg_replace("<#a_description#>","$description",$message);
$message = ereg_replace("<#a_picturl#>","$pict_url",$message);
$message = ereg_replace("<#a_minbid#>",print_money($minimum_bid),$message);
$message = ereg_replace("<#a_resprice#>",print_money($reserve_price),$message);
$message = ereg_replace("<#a_duration#>","$duration",$message);
$message = ereg_replace("<#a_location#>","$location",$message);
$message = ereg_replace("<#a_zip#>","$location_zip",$message);
$message = ereg_replace("<#a_url#>","$auction_url",$message);
$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);                    	


if($customincrement > 0)
{
	$message = ereg_replace("<#a_customincrement#>",print_money($customincrement),$message);
}
else
{
	$message = ereg_replace("<#a_customincrement#>",$MSG_614,$message);
}



if($shipping == '1'){

	$shipping_string = $MSG_038;

}else{

	$shipping_string = $MSG_032;

}

$message = ereg_replace("<#a_shipping#>","$shipping_string",$message);



if($international){

	$int_string = $MSG_033;

}else{

	$int_string = $MSG_043;

}

$message = ereg_replace("<#a_intern#>","$int_string",$message);

$pay_names_list = ereg_replace("@","\n",$payment_names);

$message = ereg_replace("<#a_payment#>","$pay_names_list",$message);

$message = ereg_replace("<#a_category#>","$sessionVars[categoriesList]",$message);

$message = ereg_replace("<#a_subcategory#>","$sub_name",$message);

$message = ereg_replace("<#a_ends#>","$ends",$message);
$message = ereg_replace("&nbsp;"," ",$message);
mail($user_email,"$MSG_099",stripslashes($message),"From:$SETTINGS[sitename] <$SETTNGS[adminmail]>\nReplyTo:$SETTNGS[adminmail]"); 


?>
