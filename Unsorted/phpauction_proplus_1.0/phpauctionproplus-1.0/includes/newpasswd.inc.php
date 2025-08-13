<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	$to 		= $EMAIL;
	$from 	= "From: $SETTINGS[sitename] <$SETTINGS[adminmail]>\n";
	$subject	= "Your new password";
	$message = "Hi $TPL_username,
As you requested, we have created a new password for your account.
It is: $NEWPASSWD
Use it to login to $SITE_NAME and remember to change it to the one you prefer.
";
?>