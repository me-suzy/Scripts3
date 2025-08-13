<?#//v.1.0.0
	$FROM = "From: $SETTINGS[sitename] <$SETTINGS[adminmail]>";
	$TO = $USER[email];
	$SUBJECT = "$SETTINGS[sitename] Payment Status";
	$MESSAGE = "Dear $USER[name],
 
Your registration at $SETTINGS[sitename] has been completed.
Your payment has been received and processed successfully.

$SETTINGS[sitename]
";
?>
