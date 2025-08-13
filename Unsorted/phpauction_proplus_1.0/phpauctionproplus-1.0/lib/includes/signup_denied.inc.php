<?#//v.1.0.0
	$FROM = "From: $SETTINGS[sitename] <$SETTINGS[adminmail]>";
	$TO = $USER[email];
	$SUBJECT = "$SETTINGS[sitename] Payment Status";
	$MESSAGE = "Dear $USER[name],
 
Your registration at $SETTINGS[sitename] cannot be completed due 
to a problem during the payment processing at PayPal.

Please feel welcome to sign-up again.

$SETTINGS[sitename]
";
?>
