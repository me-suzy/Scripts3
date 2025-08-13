<? require("admheader.php"); ?>

<h3>Email users</h3>


<?
if ($submit_mail)
{
	$sql = "select * from user";
	$res_all = mysql_query($sql);
	
	while($row = mysql_fetch_array($res_all))
	{
		$username = $row["username"];
		$email = $row["email"];
		$prefs = $row["prefs"];
		
		
		
		if ($pref == 'local' AND !$o_local AND !$o_remote)
		{
			$sql_insert = "insert into mail (mail_to, mail_from, title, message) values ('$username','$from_adress','$subject','$message')";
			$res = mysql_query($sql_insert)	;
			
		}
		elseif ($pref = 'email' AND !$o_local AND !$o_remote)
		{
			
			$headers .= "From: $phpmmname<$from_adress>\n";
			$headers .= "Reply-To: <$from_adress>\n";
			$headers .= "X-Sender: <$from_adress>\n";
			$headers .= "X-Mailer: PHP4\n"; //mailer
			$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
			$headers .= "Return-Path: <$from_adress>\n";
			mail($email,$title,wordwrap(stripslashes($message)),$headers);
			print "Sent mail to $email<br>";
		}
		elseif ($o_local OR $o_remote)
		{
			if ($o_local)
			{
					$sql_insert = "insert into mail (mail_to, mail_from, title, message) values ('$username','$from_adress','$subject','$message')";
					$res = mysql_query($sql_insert)	;
					print "inserted $username<br>";
				
			}
			elseif ($o_remote)
			{
					$headers .= "From: $phpmmname<$from_adress>\n";
					$headers .= "Reply-To: <$from_adress>\n";
					$headers .= "X-Sender: <$from_adress>\n";
					$headers .= "X-Mailer: PHP4\n"; //mailer
					$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
					$headers .= "Return-Path: <$from_adress>\n";
					mail($email,$title,wordwrap(stripslashes($message)),$headers);	
					print "Sent mail to $email<br>";
			}
			
			
			
		}
		
		
		


	}

}
else
{
	?>
	
	
	
	From here, you can send a mail to all of your users, and it will
	go into their local mailbox here at your site, or to their home emailaddress, depending on what
	they have choosen as default delivery method.<p />

	
	
	
	<p /> 
	<form method="pos" action="mail_users.php">
		
	
	<b>Subject</b><br />
	<input type='text' name= 'subject'/><p />
	
	<b>Message</b><br />
	<textarea name='message' cols='50' rows='20'></textarea><p />
	
	<b>Override</b><br />
	By selecting a option below, users preference will be ignored, and sent the way you select:<br />
	<input type='radio' name='o_local' />Override user, and use local delivery to username<br />
	<input type='radio' name='o_remote' />Override user, and use remote (normal email to given emailaddress<br />
	<p />
	<input type='submit' name='submit_mail' value='Send mail'>

<?
}
?>
<? require("admfooter.php"); ?>