<?#//v.1.0.0

	$FROM = "From: $SETTINGS[sitename] <$SETTINGS[adminmail]>";
	$TO = $USER[email];
	$SUBJECT = "$SETTINGS[sitename] Credits Purchase Confirmation";
	$MESSAGE = "Dear $USER[name],
 
This message is to confirm that ".print_money($HTTP_POST_VARS[payment_gross])." 
has been added to your credits account at $SETTINGS[sitename].

Sincerely,
$SETTINGS[sitename]
";
?>
