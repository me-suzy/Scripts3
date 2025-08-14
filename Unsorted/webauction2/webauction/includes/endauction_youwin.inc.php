<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
// $buffer = file("./includes/endauctionmail.inc.php");
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
$message = ereg_replace("<#s_address#>",$Seller['address'],$message);
$message = ereg_replace("<#s_city#>",$Seller['city'],$message);
$message = ereg_replace("<#s_prov#>",$Seller['prov'],$message);
$message = ereg_replace("<#s_country#>",$Seller['country'],$message);
$message = ereg_replace("<#s_zip#>",$Seller['zip'],$message);
$message = ereg_replace("<#s_phone#>",$Seller['phone'],$message);
$message = ereg_replace("<#w_name#>",$Winner['name'],$message);
$message = ereg_replace("<#w_nick#>",$Winner['nick'],$message);
$message = ereg_replace("<#i_title#>",$Auction['title'],$message);
$message = ereg_replace("<#i_description#>",$Auction['description'],$message);
$auction_url = "$SITE_URL"."item.php?id=".$Auction['id'];
$message = ereg_replace("<#i_url#>",$auction_url,$message);
$message = ereg_replace("<#i_ends#>",$ends_string,$message);
$message = ereg_replace("<#c_sitename#>",$SITE_NAME,$message);
$message = ereg_replace("<#c_siteurl#>",$SITE_URL,$message);
$message = ereg_replace("<#c_adminemail#>",$adminEmail,$message);
$message = ereg_replace("<#o_bid#>",number_format($Auction['current_bid'],2,'.',','),$message);

$s_feed_url = "$SITE_URL"."feedback.php?id=".$Seller['id']; // Bewertungs URL 

$message = ereg_replace("<#s_feedurl#>",$s_feed_url,$message); // Bewertungs URL 

$w_feed_url = "$SITE_URL"."feedback.php?id=".$Winner['id']; // Bewertungs URL 

$message = ereg_replace("<#w_feedurl#>",$w_feed_url,$message); // Bewertungs URL

mail($Winner["email"],"$MSG_909",$message,"From: $SITE_NAME_MANUELL <$adminEmail>\nReplyTo:$adminEmail");


?>