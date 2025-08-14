This is a Basic Authentication System for use with PHP and MySQL. 

Once your database is setup and you have edited inc/config.inc.php with your
database variables you only need to include the following at the top of your
code: 

<? include_once("inc/auth.inc.php"); $user = _check_auth($_COOKIE); ?>

If the user is not authenticated it will display the login box. 

This has been tested on:

PHP Version 5.0.3
MySQL Version 4.0.21
Apache/2.0.52

Its basic and I cant see any flaws within it however others may be able to 
spot them, so do not use this unless its for basic authentication. I did 
this to test using sessions and cookies so it may come in handy to someone else. 

Please let me know if you find anything wrong with it. 

Greets

x0x
