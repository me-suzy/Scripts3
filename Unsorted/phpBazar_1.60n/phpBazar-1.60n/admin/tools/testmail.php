<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

<html lang="iw">

<head>

<title>PHP Email testing</title>

</head>

<body>

<?



$mailto = "webmaster@mydomain.com"; #YOUR EMAIL !!!!

$from = "webmaster@mydomain.com";



$result = mail($mailto, "email testing",

"If you get this email, this function works at your server.",

"From: $from");



echo " Email was sent to $mailto, result is: $result <br>

check your mail-box now. <br>

If the result is 1, everything should work ok.";

?>

</body>

</html>