<?
require_once("php_inc.php");

session_start();

if (!$email || !$username || !$passwd)
{
 do_html_heading("Error:");
 echo "You haven´t filled out all the fields. Go back to" . 
 			" try again";
 			include "footer_inc.php";
 exit;
}

if (!valid_email($email))
{
	do_html_heading("Error:");
	echo "The email address entered is not legal. Go back to" . 
 			 " try again";
 			 include "footer_inc.php";
 exit;
}

$banlist = split(",", $set_banlist);

foreach($banlist as $banned) 
{ 
	
	$email_banned = explode("@", $banned); 
	
	if ($email_banned[0] == "*") 
	{ 
		
		$email_check = explode("@", $email); 
        
		
		
		if (trim(strtolower($email_check[1])) == trim(strtolower($email_banned[1]))) 
        {
               do_html_heading("Error:");
               print "<font color='red'><b>Error:</b></font><br />Your kind of emailaddress is not allowed here.<br />";

			   session_destroy();
			   exit;
        }
	} 
   	else 
	{ 
		
       	
		if (trim(strtolower($email)) == trim(strtolower($banned))) 
        {
        	do_html_heading("Error:");
        	print "<font color='red'><b>Error:</b></font><br />Your kind of emailaddress is not allowed here.<br />";
        	
			session_destroy();
			exit;
     	} 
	}	 
} 

if ($passwd <> $passwd2)
{
	do_html_heading("Error:");
	echo "The two passwords doesn´t match each other. Go back to" . 
 			 " try again";
 			 include "footer_inc.php";
 exit;
}


$reg_result = register ($username, $email, $passwd);
if ($reg_result == 'true')
{
 include("header_inc.php");
 //include("configdata.php");
 $valid_user = $username;
 session_register("valid_user");
 do_html_heading("Registration Successful");
 echo "You are now a registered user. ";
 
 if ($set_evalidation)
 {
 	echo "To continue, " . "check your emailaddress for validation. You will need to click the link given there " .
 	"There you will also recieve your username and password.";	
 }
 else 
 {
 	echo "To continue, " . "click <a href='member.php'>here to proceed</a>. " . 
 	"You will also recieve a mail with your password and other login info.";	
 }
 
 if ($set_evalidation)
 {
 	$sendto = "$email";
 	$from = "$from_adress";
 	$subject = "Registered user / validation";
 	$message = "Hi $username, \nIn order to login at our site, you will need
 	to verify your emailaddress. To do this, click the link given below:
 	
 	http://$url_of_site/verify.php?code=$code
 	
 	
 	Afterwards, you can log in with this:\n" .
 	"Username: $username\nPassword: $passwd\nLog in at $url_of_site";
 }
 else 
 {
 	$sendto = "$email";
 	$from = "$from_adress";
 	$subject = "Registered user";
 	$message = "Hi $username, \nYou are now a registered user at the $phpmmname site.\n" .
 	"Username: $username\nPassword: $passwd\nLog in at $url_of_site";
 }
 
 $headers = "From: $from\r\n";
 // send e-mail
 mail($sendto, $subject, $message, $headers);		
 
 
 include("footer_inc.php");			
}
else
{
 do_html_heading("Problem");
 echo $reg_result;
 exit;
}



?>