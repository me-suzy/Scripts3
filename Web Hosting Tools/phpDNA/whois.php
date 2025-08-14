<html>
<head>
<title>phpGlobalWhois</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<form name="form1" method="post" action="example.php">
  Domain: <input type="text" name="dom" size="20">
  <input type="submit" name="Submit" value="Submit">
</form>
<?
if ($dom){
	include "./whois.inc";
	$whoisresult = lookup($dom);
	#The PRE tags are there to display the results in web browsers without replacing \n with <br>..its just cleaner and quicker that way. ;)
	print "<pre>".$whoisresult."</pre>";
}
?>
</body>
</html>