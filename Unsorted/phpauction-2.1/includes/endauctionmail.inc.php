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

$END_AUCTION_NO_WINNER = "Dear $name,
The auction you created ect. ect..... ended.

Auction data
------------------------------------
Product: $title
Auction ID: $id  
Auction started: $starting_date
Auction ended: $ending_date
Auction URL: $auction_url

We inform you that there's not a winner for this auction.";

$END_AUCTION_WINNER = "Dear $name,
The auction you created ect. ect..... ended.

Auction data
------------------------------------
Product: $title
Auction ID: $id  
Auction started: $starting_date
Auction ended: $ending_date
Auction URL: $auction_url

The winner of the auction is: $bidder_name. 
Contact the winner at this e-mail address: $bidder_email";


$END_AUCTION_WINNER_CONFIRMATION = "Dear $bidder_name,
Congratulations!!

You are the winner of the auction ect ect ect.....

Auction data
------------------------------------
Product: $title
Auction ID: $id  
Auction started: $starting_date
Auction ended: $ending_date
Auction URL: $auction_url

The seller will contact you etc etc etc.
Anyway this is his/her e-mail address :$email.

Thanks etc etc.";

?>