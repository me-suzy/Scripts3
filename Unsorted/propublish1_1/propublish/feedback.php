<html
<head>

</head>

<body>
<h3><b><? echo $la_feedback ?></b></h3>
<?
include ("admin/set_inc.php");
include ("admin/language/$lang");

if ($submit)
{
 	 print("<p>$la_thanks</p>");
	 
	 if ($kar)
	 {
	 		require("admin/db.php");
	 		
			
  			$sql_up = "update article_news set votes = votes + 1, grade = grade + $kar where id=$artid";
			$result = mysql_query($sql_up); 
	 
	    	if ($result)
	 		{
			
			 	 print("<p>");
			}
			else
			{
			 		print("Error!");
			}
			
	 }
	 		
$melding = strip_tags($kommentar);
$sendto = $set_email;
$from = strip_tags($epost);
$subject = $la_email_admin_subj;
$message = "$la_voted: \n
$la_article $set_url/art.php?artid=$artid\r
$la_grade $kar\r
$la_comment $kommentar\r
$la_email $epost";

$headers = "From: $from\r\n";
// send e-mail
mail($sendto, $subject, $message, $headers);

	 


}
else
{
	print "<p><i>$la_article: $title ($artid)</i>";
	print "<br>$la_feedback_info</p>";
	print "<form method=\"post\" action=\"feedback.php\">";
	print "<input type=\"hidden\" name=\"artid\" value=\"$artid\">";
	
	print "<p><u>$la_grade</u><br>";
	print "$la_bad";
	print "<input type='radio' value='1' name='kar'> <input type='radio' value='2' name='kar'>";
	print "<input type='radio' value='3' name='kar'> <input type='radio' value='4' name='kar'>";
	print "<input type='radio' value='5' name='kar'> <input type='radio' value='6' name='kar'>";
	print "<input type='radio' value='7' name='kar'> <input type='radio' value='8' name='kar'>";
	print "<input type='radio' value='9' name='kar'> <input type='radio' value='10' name='kar'>";
	print "$la_perfect !</p>";
	
	print "<p><u>$la_comment</u><br>";
	print "<textarea rows='3' name='kommentar' cols='40'></textarea></p>";
	
	print "<p><u>$la_email</u><br>";
	print "<input type='text' name='epost' size='20'><br>";
	print "<input type='submit' value='Send' name='submit'></form></p>";
}
?>
</body>
</html>
