<?

include ("admin/set_inc.php");
include ("admin/language/$lang");
?>
<html
<head>

</head>

<body>
<h3><b><? echo $la_tellafriend ?></b></h3>
<?
if ($submit)
{
 	 
	 
	if ($avsender_epost AND $mottaker_epost)
	{	 		
				print("<p>$la_tellafriend_sent $mottaker_epost !</p>");
				$melding = strip_tags($kommentar);
				$sendto = $mottaker_epost;
				$from = $avsender_epost;
				$subject = $la_tellafriend_subj;
				$message = "$avsender_epost $la_tellafriend_msg: \n
http://www.webber.no/index.php?artid=$artid\n\n$la_tellafiend_comment\n
------------\n$melding\n\n\n$la_sent http://www.webber.no";

				$headers = "From: $from\r\n";
				// send e-mail
				mail($sendto, $subject, $message, $headers);
	}
	
	else
	{
	 		  print("<b>$la_tellafriend_error1</b>");
	}	
				
			
}
else
{
	print "<p><i>$la_article $title [$artid]</i><br>";
  	print "$la_tellafriend_intro</p>";

  	print "<form method='post' action='$PHP_SELF?'>"; 
	print "<input type='hidden' name='artid' value='$artid'>";
  	print "<p><u>$la_reciever_mail</u><br>";
    print "<input type='text' name='mottaker_epost' size='20'></p>";
  	print "<p><u>$la_sender_mail</u><br>";
    print "<input type='text' name='avsender_epost' size='20'></p>";
  	print "<p><u>$la_comment</u><br>";
    print "<textarea rows='2' name='kommentar' cols='40'></textarea><br>";
    print "<input type='submit' value='Send' name='submit'></p></form></p>";
}
print "<a href='javascript:window.close();'>$la_close</a>";
?>
 </body>
</html>
