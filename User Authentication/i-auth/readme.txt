######################################################################
#								     #
# i-auth was written by Ryan Marshall of irealms.co.uk             # 
#		if you have any problems			     #
#		mail ryanmarshall@irealms.co.uk                      #
######################################################################

i-auth contains all the files needed for setting up with a basic template format. Just upload the files and point your browser to index.php. If you wish to include i-auth into your own page just follow the instructions below.

This script contains various files which are listed below:

index.php = main template page
register.php = registration page
home.php = default content page
i-auth.php = contains the main bulk of the login script
register.php = registration file
forgot.php = forgotten password form
changepass.php = changepassword form
config.php = contains database connection script and session info
users.sql = table creation script
readme.txt

setting up the script (for those not using the index.php template included:

The script is commented where appropriate and a basic knowledge of php is beneficial here. 

You will need to create a database and then insert the users.sql file to create the tables needed for i-auth to work.

If you already have a config file just copy the code from config.php into your existing file. It is best to include your config file into each page that uses i-auth or it's variables, this can be done by inserting some script at the top of each page:-

<?
include 'config.php';
?>

The files contained were originally set up to include files into an index page if you wish to do the same without changing the script place the following where you want your login scripts to appear:

<?
if ($log=="") include "i-auth.php";
if ($log=="logout") include "logout.php";
if ($log=="change") include "changepass.php";
if ($log=="1") include "changepass.php";
if ($log=="forgot") include "forgot.php";
?>

The section below is for the navigation this should be included where you want your content to appear as shown in the index.php template.

<?
if ($page=='reg') include 'register.php';
if ($page=='') include 'home.php';
?>

Finally in the file forgot.php look for the following lines :-

 $additional_headers = 'From: youremail@yourdomain.com'."\n"
	  .'Reply-To: youremail@yourdomain.com';

replace the email in the above lines with one you wish your users to see in the from and reply to fields of their forgotten password mail.


To restrict page access of any page to those who are logged in just add the following code to each page:-

<?
if (!isset($_SESSION['valid_user']))
{
	header("Location: http://www.irealms.co.uk");
}
?>

NOTE: This will only work if the session is present on that page (ie. by including config.php as shown above).

