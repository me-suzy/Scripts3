<?#//v.1.0.0
  
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////




//require("./config.inc.php");
require("./includes/messages.inc.php");

if($SETTINGS[signupfee] == 2)
{
	$buffer = file("./includes/usermail.inc.php");
}
else
{
	if($SETTINGS[feetype] == "pay")
	{
		$buffer = file("./includes/usermail_pay.inc.php");
	}
	else
	{
		$buffer = file("./includes/usermail_prepay.inc.php");
	}
}	

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

$CONFIRMATIONPAGE = $SETTINGS[siteurl]."confirm.php?id=$TPL_id_hidden";

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
		$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
		$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
		$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);  
		$message = ereg_replace("<#c_confirmation_page#>",$CONFIRMATIONPAGE,$message);                  	
		mail($TPL_email_hidden,"$MSG_098",$message,"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]");
?>
