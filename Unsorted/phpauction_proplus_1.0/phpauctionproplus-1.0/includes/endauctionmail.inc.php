<?#//v.1.0.0
  
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


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