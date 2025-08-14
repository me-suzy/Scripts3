<?

require("config.php");

if($_POST["action"]=="submit")
	{
		$added=$_POST['added'];
		$password=$_POST['password'];
		$name=$_POST['name'];
		$email=$_POST['email'];
		$site_name=$_POST['site_name'];
		$url=$_POST['url'];
		$description=$_POST['description'];

		$sql = "INSERT INTO $db_table (added, password, name, email, site_name, url, description) VALUES ('$added', '$password', '$name', '$email', '$site_name', '$url', '$description')";
		$result = mysql_query($sql);

		$sql = "SELECT * FROM $db_table ORDER BY id DESC LIMIT 1";
		$result = mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
			{
				$id = $row['id'];
				$password = $_POST['password'];
				$to = $_POST['email'];
				$from = "From: $admin_email";
				$subject = "$ring_name Submission";
				$message = "Thanks for joining the $ring_name webring!  Here is your login info, keep this in a safe place!\n\nSite ID: $id\nPassword: $password\n\nDon't forget to put up the ring code on your site as soon as possible!  You will not be added to the ring until I find the ring code on your site.\n\nHere's the code:\n\n<a href='$ring_url/webring.php?id=$id&action=prev'><</a> <a href='$ring_url/webring.php?action=rand'>?</a> <a href='$ring_url/webring.php?action=home'>$ring_name</a> <a href='$ring_url/webring.php?action=list'>#</a> <a href='$ring_url/webring.php?id=$id&action=next'>></a>\n\nThanks!\n$admin_name\n$ring_url";
				$name = $_POST['name'];
				$url = $_POST['url'];
				$admin_message = "$name has joined $ring_name!  To check this site for your ring code, go to:\n$url\n\nTo approve this new site, go to:\n$ring_url/process.php?id=$id&action=approve\n\nAlternatively, to remove this site, go to:\n$ring_url/process.php?id=$id&action=remove";
				if(mail($to,$subject,$message,$from))
					{
						mail($admin_email,$subject,$admin_message,"From: $to");
						print"<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"3; URL=$index_php\">";
						echo "Thanks for your submission!  We're taking you back to the main page!";
					}
				else
					{
						echo "There was a problem with your submission. Please check that you filled in the form correctly.";
					}
			}
	}

elseif($_POST["action"]=="modify")
	{
		$sql = "SELECT * FROM $db_table WHERE id=$id";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result))
			{
				$password1 = $row['password'];
				$password2 = $_POST['password'];

				if($password1 == $password2)
					{
						mysql_query("UPDATE $db_table SET name='$name', email='$email', site_name='$site_name', url='$url', description='$description' WHERE id='$id'");
						print"<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"3; URL=$index_php\">";
						echo "Thanks for your modification!  We're taking you back to the main page!";
					}

				else
					{
						echo "There was a problem with your submission. Please check that you filled in the form correctly.";
					}
			}
	}

elseif($_POST["action"]=="remove")
	{
		$sql = "SELECT * FROM $db_table WHERE id=$id";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result))
			{
				$password1 = $row['password'];
				$password2 = $_POST['password'];

				$to = $row['email'];
				$url = $row['url'];

				if($password1 == $password2)
					{
						mysql_query("DELETE FROM $db_table WHERE id=$id");

						$from = "From: $admin_email";
						$subject = "Site Deleted From $ring_name";
						$message = "Your site has been deleted from $ring_name upon your request.  Sorry you decided to go!\n\n$admin_name\n$index_php";
						$admin_message = "The site located at $url has been deleted upon the owner's request.";
						if(mail($to,$subject,$message,$from))
							{
								mail($admin_email,$subject,$admin_message,"From: $to");
								print"<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"3; URL=$index_php\">";
								echo "You've been deleted from $ring_name.  Sorry to see you go!";
							}
						else
							{
								echo "There was a problem with your submission. Please check that you filled in the form correctly.";
							}
					}

				else
					{
						echo "There was a problem with your submission. Please check that you filled in the form correctly.";
					}
			}
	}

elseif($_POST["action"]=="approve")
	{
		$id = $_POST['id'];
		$sql = "SELECT * FROM $db_table WHERE id=$id";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result))
			{
				$to = $row['email'];
				$subject = "$ring_name Approval";
				$message = "Your site has been approved in the $ring_name webring!  Thanks for joining!\n\n$admin_name\n$index_php";

				if($_POST['password'] == $admin_password)
					{
						mysql_query("UPDATE $db_table SET queue='1' WHERE id=$id");
						print "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"3; URL=$index_php\">";
						echo "Thanks for your approval!  We're taking you back to the main page!";
						mail($to,$subject,$message,"From: $admin_email");
					}
				else
					{
						echo "Wrong password.";
					}
			}
	}

elseif($_POST["action"]=="delete")
	{
		$id = $_POST['id'];
		$sql = "SELECT * FROM $db_table WHERE id=$id";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result))
			{
				$to = $row['email'];
				$site = $row['site_name'];
				$subject = "$ring_name Deletion";
				$message = "Your site has been deleted from the $ring_name webring!  If you have any questions about why your site was deleted, respond to this email.\n\n$admin_name\n$index_php";

				if($_POST['password'] == $admin_password)
					{
						mysql_query("DELETE FROM $db_table WHERE id=$id");
						print "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"3; URL=$index_php\">";
						echo "$site has been deleted from the webring!";
						mail($to,$subject,$message,"From: $admin_email");
					}
				else
					{
						echo "Wrong password.";
					}
			}
	}

elseif($_GET["action"]=="approve")
	{
		echo "Please enter your admin password:<br><br><form method=\"post\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"password\" name=\"password\"><input type=\"submit\" name=\"action\" value=\"approve\"></form>";
	}

elseif($_GET["action"]=="remove")
	{
		echo "Please enter your admin password:<br><br><form method=\"post\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"password\" name=\"password\"><input type=\"submit\" name=\"action\" value=\"delete\"></form>";
	}

else
	{
		echo "No variable passed.";
	}

?>
