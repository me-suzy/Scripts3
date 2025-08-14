\<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

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

//--Reteve message

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

$message = ereg_replace("<#c_sitename#>",$SITE_NAME,$message);
$message = ereg_replace("<#c_siteurl#>",$SITE_URL,$message);
$message = ereg_replace("<#c_adminemail#>",$adminEmail,$message);                    	

mail($friend_email,"$MSG_905",$message,"From:$SITE_NAME <$adminEmail>\nReplyTo:$adminEmail"); 



?>

