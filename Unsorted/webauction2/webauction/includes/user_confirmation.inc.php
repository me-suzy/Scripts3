<?

 /*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/




//require("./config.inc.php");

require("./includes/messages.inc.php");



$buffer = file("./includes/usermail.inc.php");



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


		$message = ereg_replace("<#c_id#>",AddSlashes($TPL_id_hidden),$message);
		$message = ereg_replace("<#c_name#>",AddSlashes($TPL_name_hidden),$message);
		$message = ereg_replace("<#c_nick#>",AddSlashes($TPL_nick_hidden),$message);
		$message = ereg_replace("<#c_address#>",AddSlashes($TPL_address),$message);
		$message = ereg_replace("<#c_city#>",AddSlashes($TPL_city),$message);
		$message = ereg_replace("<#c_prov#>",AddSlashes($TPL_prov),$message);
		$message = ereg_replace("<#c_zip#>",AddSlashes($TPL_zip),$message);
		$message = ereg_replace("<#c_country#>",AddSlashes($countries[$TPL_country]),$message);
		$message = ereg_replace("<#c_phone#>",AddSlashes($TPL_phone),$message);
		$message = ereg_replace("<#c_email#>",AddSlashes($TPL_email_hidden),$message);
		$message = ereg_replace("<#c_password#>",AddSlashes($TPL_password_hidden),$message);
		$message = ereg_replace("<#c_sitename#>",$SITE_NAME,$message);
		$message = ereg_replace("<#c_siteurl#>",$SITE_URL,$message);
		$message = ereg_replace("<#c_adminemail#>",$adminEmail,$message);                    	

		mail($TPL_email_hidden,"$MSG_098",$message,"From:$SITE_NAME <$adminEmail>\nReplyTo:$adminEmail");

?>

