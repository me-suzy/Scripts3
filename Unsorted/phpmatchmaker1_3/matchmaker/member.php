<?
require("php_inc.php"); 
session_start();
include("header_inc.php");

if ($username && $passwd)
// they have just tried logging in
{
    if (login($username, $passwd))
    {
      // if they are in the database register the user id
      $valid_user = $username;
      session_register("valid_user");
    }  
    else
    {
      // unsuccessful login
      do_html_heading("Problem:");
      echo "You could not be logged in. 
            You must be logged in to view this page.";


      exit;
    }      
}

do_html_heading("Members Page");
check_valid_user();


function check_valid_user()
// see if somebody is logged in and notify them if not
{
  global $valid_user;
  if (session_is_registered("valid_user"))
  {
      		
			db_connect();
			
				
 			$result_s = mysql_query("select * from user where username = '$valid_user'");
			$row = mysql_fetch_array($result_s);
			$hits = $row[hits];
			$ban = $row[ban];
			$lastlogin = $row[lastlogin];
 			$hits = $row[hits];
			$lastlogin = $row[lastlogin];
			$verify = $row[verify];
			
			if ($ban)
			{
				print "<p><b>Problem</b><br>Your username is banned from using these pages.<p>";	
				include "footer_inc.php";
				session_destroy();
				exit;
			}

						
			if ($verify AND $set_evalidation)
			{
				print "<p><b>Problem</b><br>You must verify your emailaddress by following the
				link given in email sent to your emailaddress before you can continue.<p>";	
				include "footer_inc.php";
				session_destroy();
				exit;
			}
			
			member_menu();
			db_connect();
			$string = "select id from matchprofiles where username_p1 = '$valid_user' or username_p2 = '$valid_user'";
			$match_check = mysql_query("$string");
			$num = mysql_num_rows($match_check);
			
			$string = "select mailid from mail where mail_to = '$valid_user' AND status = 'New!'";
			$mail_check = mysql_query("$string");
			$num_mail = mysql_num_rows($mail_check);
		
			$string = "select favid from favorites where owner = '$valid_user'";
			$fav_check = mysql_query("$string");
			$num_fav = mysql_num_rows($fav_check);
			
			$string = "select favid from favorites where fav_user = '$valid_user'";
			$fav = mysql_query("$string");
			$num_fav_remote = mysql_num_rows($fav);
			
			$string = "select visitid from visitors where owner = '$valid_user'";
			$member_visit = mysql_query("$string");
			$num_visitor = mysql_num_rows($member_visit);
	 		
			$d = date(Ymd);
			$string = "update user set lastlogin = '$d' where username = '$valid_user'";
			$upd = mysql_query("$string");
			
			
			echo "Logged in as $valid_user.";
      		echo "<p>";
			
			echo "<b>Statistics for $valid_user</b><br>";
			echo "Ad Views: $hits<br>";
			echo "New messages: <a href='mailbox.php'>$num_mail</a><br>";
			echo "Users matching your wish: <a href='match.php'>$num</a><br>";
			echo "Numbers of favorites: <a href='favorites.php'>$num_fav</a><br>";
			echo "People that have you as favorite: $num_fav_remote<br>";
			echo "Members visited you last 30 days: <a href='visitors.php'>$num_visitor</a><br>";
			echo "<p><p><b>Profile</b><br>";
			echo "<a href='option.php'>Options</a><br>";
			echo "<a href='profile.php'>Edit my own profile</a><br>";
			echo "<a href='profile_lo.php'>Edit whom I am searching for</a><br>";
			echo "<a href='upload_image.php'>Upload image to my profile</a><br>";

  }
  else
  {
     // they are not logged in 
     do_html_heading("Problem:");
     echo "You are not logged in.<br>";
     exit;
  }  
}

include("footer_inc.php");

?>
