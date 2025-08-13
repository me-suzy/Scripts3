<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


$buffer = file("./includes/mail_endauction_buyers_nofee.inc.php");

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

$message = ereg_replace("<#w_report#>",$report_text,$message);
$message = ereg_replace("<#w_name#>",$Winner['name'],$message);
$message = ereg_replace("<#w_nick#>",$Winner['nick'],$message);

$message = ereg_replace("<#i_title#>",$Auction['title'],$message);
$message = ereg_replace("<#i_currentbid#>",print_money($Auction['current_bid']),$message);
$message = ereg_replace("<#i_description#>",$Auction['description'],$message);
$message = ereg_replace("<#i_qty#>",$Auction['quantity'],$message);
$auction_url = "$SITE_URL"."item.php?id=".$Auction['id'];
$message = ereg_replace("<#i_url#>",$auction_url,$message);
$message = ereg_replace("<#i_ends#>",$ends_string,$message);

$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);

mail($Winner["email"],"$MSG_909",stripslashes($message),"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]");



?>
