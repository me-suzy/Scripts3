<?
//session start ( i left this in as e-post originally used sessions, you can remove the 2 lines below if not needed.
ob_start();
session_start();
//fix to help internet explorer remember form variables
header("Cache-control: private"); //IE 6 fix
//use the following if loop if you wish to make use of the page variables
if(!isset($_REQUEST['page'])||($_REQUEST['page']==NULL))
{
	$page="home";
	}
	else{$page=$_REQUEST['page'];}
//needs to be completed
$db_conn = mysql_connect("database host (often localhost)", "database username", "database password") or die("unable to connect to the database");
  mysql_select_db("database name", $db_conn) or die("unable to select the database");
//forgot pass from address
$from = 'enter the email address you want to be shown in the from area of the email that a users receives in their forgot password email';

