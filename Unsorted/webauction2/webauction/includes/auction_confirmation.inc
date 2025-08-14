<?
  
 /*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

$buffer = file("./includes/auctionmail.inc");

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

$message = ereg_replace("<#c_name#>","$user_name",$message);

$message = ereg_replace("<#c_nick#>","$nick",$message);
$message = ereg_replace("<#c_address#>","$user_address",$message);
$message = ereg_replace("<#c_country#>","$user_country",$message);
$message = ereg_replace("<#c_zip#>","$user_zip",$message);
$message = ereg_replace("<#c_email#>","$user_email",$message);

$message = ereg_replace("<#a_title#>",$title,$message);
//$message = ereg_replace("<#a_title#>",$title,$message);
$message = ereg_replace("<#a_id#>","$auction_id",$message);
$message = ereg_replace("<#a_description#>","$description",$message);
$message = ereg_replace("<#a_picturl#>","$pict_url",$message);
$message = ereg_replace("<#a_minbid#>",number_format($minimum_bid,2,'.',','),$message);
$message = ereg_replace("<#a_resprice#>",number_format($reserve_price,2,'.',','),$message);
$message = ereg_replace("<#a_duration#>","$duration",$message);
$message = ereg_replace("<#a_location#>","$location",$message);
$message = ereg_replace("<#a_zip#>","$location_zip",$message);
$message = ereg_replace("<#a_url#>","$auction_url",$message);

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
$message = ereg_replace("<#a_category#>","$cat_name",$message);
$message = ereg_replace("<#a_subcategory#>","$sub_name",$message);

$year = substr($ends,0,4);
$month = substr($ends,4,2);
$day = substr($ends,6,2);
$hour = substr($ends,8,2);
$minutes = substr($ends,10,2);
$ends_string = "$month/$day/$year $hour:$minutes";
$message = ereg_replace("<#a_ends#>","$ends_string",$message);


mail($user_email,"$MSG_099",$message,"From:$SITE_NAME <$adminEmail>\nReplyTo:$adminEmail"); 

?>
