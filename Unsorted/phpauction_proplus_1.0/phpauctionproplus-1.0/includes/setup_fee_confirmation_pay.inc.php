<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////



$buffer = file("./includes/setup_confirmation_pay_mail.inc.php");

$i = 0;

$j = 0;

while($i < count($buffer)){

	if(!ereg("^#(.)*$",$buffer[$i])){

		$skipped_buffer[$j] = $buffer[$i];

		$j++;

	}

	$i++;

}

$auction_url = $SETTINGS[siteurl] . "item.php?mode=1&id=".$HTTP_POST_VARS[item_number];

//--Retrieve message

$message = implode($skipped_buffer,"");


//--Change TAGS with variables content

$message = ereg_replace("<#c_name#>","$USER[name]",$message);
$message = ereg_replace("<#a_title#>",$TITLE,$message);
$message = ereg_replace("<#a_url#>",$auction_url,$message);
$message = ereg_replace("<#a_id#>","$HTTP_POST_VARS[item_number]",$message);
$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);                    	
$message = ereg_replace("&nbsp;"," ",$message);
mail($EMAIL,"$MSG_488",stripslashes($message),"From:$SETTINGS[sitename] <$SETTNGS[adminmail]>\nReplyTo:$SETTNGS[adminmail]"); 


?>
