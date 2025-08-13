<?#//v.1.0.0
	$FROM = "From: $SETTINGS[sitename] <$SETTINGS[adminmail]>";
	$TO = $USER[email];
	$SUBJECT = "$SETTINGS[sitename] Credits Purchase Confirmation";
	$MESSAGE = "Dear $USER[name],
 
This message is to confirm that  ".print_money($HTTP_POST_VARS[payment_gross])." 
has been paid and your account at $SETTINGS[sitename] has been activated.

You can login and enjoy all the features of our site.

Sincerely,
$SETTINGS[sitename]
";
?>
