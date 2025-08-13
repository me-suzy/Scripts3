<?php

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


$buffer = file("./includes/no_longer_winnermail.inc.php");

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

$message = ereg_replace("<#o_name#>","$OldWinner_name",$message);
$message = ereg_replace("<#o_nick#>","$OldWinner_nick",$message);
$message = ereg_replace("<#o_email#>","$OldWinner_email",$message);
$message = ereg_replace("<#o_bid#>","$OldWinner_bid",$message);

$message = ereg_replace("<#n_bid#>",$new_bid,$message);

$message = ereg_replace("<#i_title#>","$item_title",$message);
$message = ereg_replace("<#i_description#>","$item_description",$message);
$auction_url = "$SITE_URL"."item.php?id=$id";
$message = ereg_replace("<#i_url#>","$auction_url",$message);
$message = ereg_replace("<#i_ends#>","$ends_string",$message);

$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);                    	

// Remove any &nbsp; that might have beem included in the email. //
// These are added by the print_money() function, but emails are sent  //
// as plain text //

$message = ereg_replace("&nbsp;"," ",$message);


mail($OldWinner_email,"$MSG_906",stripslashes($message),"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]"); 

?>
