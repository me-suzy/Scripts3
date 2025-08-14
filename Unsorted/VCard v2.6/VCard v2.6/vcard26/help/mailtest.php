<html>
	<head>
	<title>PHP Mail Tester</title>
	</head>
<body>
<?php

if (empty($HTTP_POST_VARS['address']))
{

	echo "
	<form action=\"mailtest.php\" method=post>
	<p>Your e-mail address: <input type=\"text\" name=\"address\"></p>
	<p><input type=\"submit\" value=\"Send email\"></p>";

} else {
	mail("$HTTP_POST_VARS[address]","Testing mail() function","This is a test email");
	echo "<p>Mail sent. Were any errors shown? If there were, mail is probably not set up correctly. And you need contact your web hosting provider about this problem.</p>";
}
?>
</body>
</html>