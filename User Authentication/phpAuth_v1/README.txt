///////////////////////
//Setting up phpAuth.//
///////////////////////

1. Create database text file on your webserver. e.g. members.txt

2. Edit phpAuth_config.php to suit your needs.

	2.1 Change the database name to the database text file you've created.	
	2.1 Edit the redirection for login and logout

3. Add phpAuth into your pages.

	3.1 Where you want the login to appear add <? include "phpAuth_inc.php"; ?>

4. Add header controllers to your pages.

	4.1 On any page that you have included the phpAuth login page you must add <? ob_start(); 	?> at the very top, before any html.
	4.2 Also at the very bottom after all the html you need to add <? ob_end_flush(); ?>. 

5. Add code to pages which are to be restricted to non-members.

	5.1 On any page you want to restrict, add the following code before any html code.
	
	<?
	include "phpAuth_functions.php";
	phpAuth_chk_login();
	?>

	5.2 Example

	<?
	include "phpAuth_functions.php";
	phpAuth_chk_login();
	?>
	<html>
	<head>
	<title>Members Only</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
		
	<body>
	
	Welcome to the members area!

	</body>
	</html>

6. Any log out buttons or links must point to phpAuth_logout.php for your log out redirection to work. A basic log out link is below.

	5.1. <a href="phpAuth_logout.php">Log out</a>

7. Upload all files to your web server.

8. CHMOD your database text file to 666.

///////////////////////
//Optional Config.   //
///////////////////////

1. Include user registration page into your own pages.

	1.1 Where you want the user registration page to appear add <? include 	"phpAuth_adduser.php"; ?>
	1.2 Change the link "Register Here" on phpAuth_inc.php to your own registration page.