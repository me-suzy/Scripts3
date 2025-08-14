<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

$buffer = file("./includes/mail_endauction_winner.inc.php");

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
$message = ereg_replace("<#b_name#>",$Winner['name'],$message);
$message = ereg_replace("<#b_nick#>",$Winner['nick'],$message);
$message = ereg_replace("<#b_email#>",$Winner['email'],$message);
$message = ereg_replace("<#b_address#>",$Winner['address'],$message);
$message = ereg_replace("<#b_city#>",$Winner['city'],$message);
$message = ereg_replace("<#b_prov#>",$Winner['prov'],$message);
$message = ereg_replace("<#b_country#>",$Winner['country'],$message);
$message = ereg_replace("<#b_zip#>",$Winner['zip'],$message);
$message = ereg_replace("<#b_phone#>",$Winner['phone'],$message);
$message = ereg_replace("<#o_bid#>",number_format($Auction['current_bid'],2,'.',','),$message);
$message = ereg_replace("<#i_title#>",$Auction['title'],$message);
$message = ereg_replace("<#i_description#>",$Auction['description'],$message);
$auction_url = "$SITE_URL"."item.php?id=".$Auction['id'];
$message = ereg_replace("<#i_url#>",$Auction_url,$message);
$message = ereg_replace("<#i_ends#>",$ends_string,$message);

$message = ereg_replace("<#c_sitename#>",$SITE_NAME,$message);
$message = ereg_replace("<#c_siteurl#>",$SITE_URL,$message);
$message = ereg_replace("<#c_adminemail#>",$adminEmail,$message);

$s_feed_url = "$SITE_URL"."feedback.php?id=".$Seller['id']; // Bewertungs URL 

$message = ereg_replace("<#s_feedurl#>",$s_feed_url,$message); // Bewertungs URL 

$w_feed_url = "$SITE_URL"."feedback.php?id=".$Winner['id']; // Bewertungs URL 

$message = ereg_replace("<#w_feedurl#>",$w_feed_url,$message); // Bewertungs URL

mail($Seller["email"],"$MSG_907",$message,"From: $SITE_NAME_MANUELL <$adminEmail>\nReplyTo:$adminEmail");



?>