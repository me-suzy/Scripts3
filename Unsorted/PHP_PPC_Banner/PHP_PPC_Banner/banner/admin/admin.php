<?php


/*

WHAT IS THIS FILE?

This file contains all the stuff that is needed to administer the PPC banner stuff...

*/

//have to do the requires seperatly...otherwise we get screwed around by cookie stuff...require_once("settings.inc.php");

if     ($action == "log_in")             { require_once("settings.inc.php"); do_login();            }
elseif ($action == "show_admin")         { show_logged_in("1");                                    }
elseif ($action == "add_advertiser")     { require_once("settings.inc.php");add_advertiser_html(); }
elseif ($action == "add_advertiser_do")  { require_once("settings.inc.php");add_advertiser_do();   }
elseif ($action == "edit_advertiser")    { require_once("settings.inc.php");edit_advertiser();     }
elseif ($action == "edit_advertiser2")   { require_once("settings.inc.php");edit_advertiser_2();   }
elseif ($action == "edit_advertiser_do") { require_once("settings.inc.php");edit_advertiser_do();  }
elseif ($action == "delete_advertiser")  { require_once("settings.inc.php");delete_advertiser();   }
elseif ($action == "delete_advertiser2") { require_once("settings.inc.php");delete_advertiser_2(); }
elseif ($action == "add_credit_to_advertiser")  { require_once("settings.inc.php");add_credit();   }
elseif ($action == "add_credit_to_advertiser2") { require_once("settings.inc.php");add_credit_2(); }
elseif ($action == "add_credit_to_advertiser3") { require_once("settings.inc.php");add_credit_3(); }
elseif ($action == "view_credit_update_stats")  { require_once("settings.inc.php");view_stats();   }
elseif ($action == "edit_settings")      { require_once("settings.inc.php");edit_settings();       }
elseif ($action == "edit_settings2")     { require_once("settings.inc.php");update_settings();     }
else                                     { require_once("settings.inc.php");show_login_page();     }


/* ################################################## */

function show_login_page() {

echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" conte4nt="text/html; charset=windows-1252">
<title>Ace PPC Banner System Admin Panel - Login</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<form method="POST" action="admin.php">
  <input type="hidden" name="action" value="log_in">
  <p align="center"><font face="Arial" size="2">Please enter the admin username
  and password you had set up when installing this script. This will enable you
  to log in and administer the banners, and do other tasks, such as mass mailing
  advertisers and editing settings.</font></p>
  <div align="center">
    <center>
    <table border="0" cellpadding="2" cellspacing="1" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber1" width="233">
      <tr>
        <td width="148"><font face="Arial" size="2">Username :</font></td>
        <td width="149"><input type="text" name="login_username" size="20"></td>
      </tr>
      <tr>
        <td width="148"><font face="Arial" size="2">Password :</font></td>
        <td width="149"><input type="password" name="login_password" size="20"></td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center"><input type="submit" value="Login" name="B1"></p>
  <p align="center">&nbsp;</p>
  <p align="center">&nbsp;</p>
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>

PAGE;

}

/* ################################################## */

function do_login() {

   // rerquire here...otherwise we get hassle from the cookie stuff :(
   require_once("settings.inc.php");
   global $login_username, $login_password, $admin_username, $admin_password;


   if ($login_username == $admin_username && $login_password == $admin_password) {

   setcookie(SETUSERNAME, $login_username);
   setcookie(SETPASSWORD, $login_password);

   // rerquire here...otherwise we get hassle from the cookie stuff :(

   show_logged_in("0");

 } else {

   normal_error("The username/password combination you entered does not seem to be valid. Please check and try again.");

 }

}


/* ######################################### */

function show_logged_in($check) {


// if they are trying to get back to admin, check everything still exists cookie wise...
if ($check) {

/* set the cookie globals..so we can check they are logged in... */
global $SETUSERNAME, $SETPASSWORD;

// now verify they are logged in...if not, exit...
if (!$SETUSERNAME || !$SETPASSWORD) {

      normal_error("The admin username/password cookie does not appear to be on your system...so you are not logged in....please re-login and try again..."); }

   }


echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Banner System Admin Panel</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<p align="center"><font face="Arial" size="2">Please select something to do from
the menu below;</font></p>
<p align="center"><font face="Arial" size="2">
<a href="admin.php?action=add_advertiser">Add Advertiser</a><br>
<a href="admin.php?action=edit_advertiser">Edit Advertiser</a><br>
<a href="admin.php?action=delete_advertiser">Delete Advertiser</a><br>
<a href="admin.php?action=add_credit_to_advertiser">Add Credit to Advertiser</a><br>
<a href="admin.php?action=view_credit_update_stats">View credit Update
Statistics</a></font></p>
<p align="center"><font face="Arial" size="2">
<a href="admin.php?action=edit_settings">Edit Settings</a></font></p>
<p align="center">&nbsp;</p>

<p align="center">&nbsp;</p>

<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>


PAGE;

} // end 'show_logged_in' function...

/* ################################################################ */

function add_advertiser_html() {

/* set the cookie globals..so we can check they are logged in... */
global $SETUSERNAME, $SETPASSWORD;

// now verify they are logged in...if not, exit...
 if (!$SETUSERNAME || !$SETPASSWORD) {

      normal_error("The admin username/password cooklie does not appear to be on your system...so you are not logged in./...please re-login and try again...");

      }

$todays_date = date('d/m/Y'); // get todays date..cos that can automatically be shown for the last top up date...

echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Banner System Admin Panel - Add an Advertiser</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<form method="POST">
  <input type="hidden" name="action" value="add_advertiser_do">
  <p align="center"><font face="Arial" size="2">Please complete the form below,
  and then submit to add a new advertiser.</font></p>
  <p align="center"><font face="Arial" size="2"><font color="#FF0000">*</font>
  denotes required field.</font></p>
  <div align="center">
    <center>
    <table border="0" cellpadding="2" cellspacing="1" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber1" width="274">
      <tr>
        <td width="157"><font face="Arial" size="2">Name : <font color="#FF0000">
        *</font></font></td>
        <td width="181"><input type="text" name="name" size="20"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Email :
        <font color="#FF0000">*</font></font></td>
        <td width="181"><input type="text" name="email" size="20"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Password :
        <font color="#FF0000">*</font></font></td>
        <td width="181"><input type="text" name="new_password" size="20"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Banner 1 :
        <font color="#FF0000">*</font></font></td>
        <td width="181"><input type="text" name="banner1" size="20"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Banner 2 :</font></td>
        <td width="181"><input type="text" name="banner2" size="20"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Banner 3 :</font></td>
        <td width="181"><input type="text" name="banner3" size="20"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">URL : <font color="#FF0000">
        *</font></font></td>
        <td width="181"><input type="text" name="url" size="20"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Last Top Up :
        <font color="#FF0000">*</font></font></td>
        <td width="181">
        <input type="text" name="last_top_up" size="20" value="$todays_date"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Credit (in ¢):
        <font color="#FF0000">*</font></font></td>
        <td width="181">
        <input type="text" name="credit_left" size="20" value="0"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Hits Received :</font></td>
        <td width="181">
        <input type="text" name="hits_received" size="20" value="0"></td>
      </tr>
      <tr>
        <td width="157"><font face="Arial" size="2">Exposures :</font></td>
        <td width="181">
        <input type="text" name="exposures" size="20" value="0"></td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center"><input type="submit" value="Add" name="B1"></p>
    <p align="center">&nbsp;</p>

</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>

PAGE;

}

/* ################################################################ */

function add_advertiser_do() {

global $SETUSERNAME, $SETPASSWORD;
global $name, $email, $new_password, $banner1, $banner2, $banner3, $url, $last_top_up, $credit_left, $hits_received, $exposures;
global $url, $host, $database, $password, $username, $debug, $webmaster_email;

 // check if they are logged in..
 if (!$SETPASSWORD || !$SETUSERNAME) { normal_error("No login cookie was set!"); }

 // check to see if all required stuff was done correctly....
 if (!$name)         { normal_error("You didn't enter a name! Please try again"); }
 if (!$email)        { normal_error("You didn't enter an email. This will be their login username, so please go back and submit one."); }
 if (!$new_password) { normal_error("You didn't specify a password!"); }
 if (!$banner1)      { normal_error("You didn't specify their banner URL (jpg/gif)"); }
 if (!$url)          { normal_error("You didn't specify a URL to send clicks too!"); }
 if (!$last_top_up)  { normal_error("You didn't specify a late top up date...usually the current date is ok to leave..."); }
 if ($credit_left == " "){ normal_error("You didn't specify a credit remaining amount. Please go back and correct it."); }

 // check format of email address...
 if(!eregi("[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,}$", $email)) {

   normal_error("Bad email address format! Please go back and try again...");

 }

 // check for a valied format of money...
 if (!preg_match("/^[0-9]{1,25}$/",$credit_left)) {

   normal_error("The credit left you entered to be added is in an invalied format..please click bank and try again...");

 }

 // check for the format of the last top up...
 $goodDate = isValidDate($last_top_up);
 if (!$goodDate) {

   normal_error("The last update value you set is in the incorrect format, it needs to be like: day/month/year, i.e. 21/3/2002");

 }

 // check for a valied format of the banner 1 URL....
 if(!preg_match("/^\w+:+/i", $banner1))
    {
    normal_error("The image URL you provided for banner 1 does not appear to be valied. Please try again...");
    }


 // check for a valied format of the banner 2 URL....
 if ($banner2) {
   if(!preg_match("/^\w+:+/i", $banner2))
      {
      normal_error("The image URL you provided for banner 2 does not appear to be valied. Please try again...");
      }
 }

 // check for a valied format of the banner 3 URL....
 if ($banner3) {
   if(!preg_match("/^\w+:+/i", $banner3))
      {
      normal_error("The image URL you provided for banner 3 does not appear to be valied. Please try again...");
      }
 }

 // check for a valied format of the redirect URL....
 if(!preg_match("/^\w+:+/i", $url))
    {
    normal_error("The URL you provided does not appear to be valied. Please try again...");
    }

 // check for the format of the hits received....
   if(!preg_match("/^[0-9]$/i", $hits_received))   {

   normal_error("The hits received you entered was not valied. Only whole numbers please.");

 }

 // check for the format of the exposures...
   if(!preg_match("/^[0-9]$/i", $exposures))  {

   normal_error("The exposures you entered was not valied. Only whole numbers please.");

 }

/* NOW EVERYTHING HAS BEEN CHECKED WE CAN FINALLY ADD IT TO THE DATABASE...*/

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...


// find the password, or username if needed.
$query = "INSERT INTO `advertiser_info` (`Name`, `Email`, `BannerURL1`, `BannerURL2`, `BannerURL3`, `URL`, `LastTopUp`, `CreditLeft`, `HitsReceived`, `Exposures`, `Password`) VALUES ('$name', '$email', '$banner1', '$banner2', '$banner3', '$url', '$last_top_up', '$credit_left', '$hits_received', '$exposures', '$new_password')";

   $do = mysql_query($query);
   $error = mysql_error();

   if ($error) { echo $error; exit; }

   if ($debug) { echo "query executed....<BR>"; }

echo "Advertiser added...<a href=admin.php?action=show_admin>click here to go back to the main menu...</a>";

// close mysql, otherwise we clog it up...
mysql_close($connection);

}

/* ################################################################ */

// let them choose who they want to edit first...create a list on the page, then they select it..
function edit_advertiser() {

// show the top half of the page...
echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Admin - Edit Advertiser</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<form method="post">
  <input type="hidden" value="add_advertiser_do" name="action">
  <p align="center"><font face="Arial" size="2">Please click 'edit' next to an advertiser to edit that advertisers settings...</font></p>
  <div align="center">
    <center>
    <table id="AutoNumber1" style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing="1" cellPadding="2" width="300" border="0">

PAGE;

//now get SQL stuff, and return the <tr> cells etc...

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...


// find the password, or username if needed.
$query = "SELECT * FROM advertiser_info";

   $do = mysql_query($query);
   $error = mysql_error();

   if ($debug) { echo "query executed....<BR>"; }

//get info we need...to see if they have enuf credit
while ($cat = mysql_fetch_array($do)) {

// set up the variables from MySQL
$email  = $cat['Email'];

echo <<<TABLE

      <tr>
        <td width="250">$email</td>
        <td width="50">[
        <a href="admin.php?action=edit_advertiser2&email=$email">edit</a> ]</td>
      </tr>

TABLE;


} // end the while...


// now show the rest of the page...to close table and footer etc...

echo <<<PAGE

      <tr>
        <td width="157" height="22">&nbsp;</td>
        <td width="181" height="22">&nbsp;</td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center">&nbsp;</p>
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>


PAGE;

// close mysql, otherwise we clog it up...
mysql_close($connection);

}

/* ################################################################ */

// now show them the old listing, and show submit button so they can re-submit the edited data...
// don't let them change the email address!
function edit_advertiser_2() {

// get globals for SQL login stuff..
global $url, $host, $database, $password, $username, $debug, $email;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
 if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
 if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...

// find the password, or username if needed.
$query = "SELECT * FROM advertiser_info WHERE Email = '" . $email . "'";

   $do = mysql_query($query);
   $error = mysql_error();

   if ($debug) { echo "query executed....<BR>"; }

//get info we need...to see if they have enuf credit
while ($cat = mysql_fetch_array($do)) {

// set up the variables from MySQL
$name          = $cat['Name'];
$email         = $cat['Email'];
$new_password  = $cat['Password'];
$banner1       = $cat['BannerURL1'];
$banner2       = $cat['BannerURL2'];
$banner3       = $cat['BannerURL3'];
$url           = $cat['URL'];
$last_top_up   = $cat['LastTopUp'];
$credit        = $cat['CreditLeft'];
$hits_received = $cat['HitsReceived'];
$exposures     = $cat['Exposures'];

echo <<<TABLE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Edit user &gt; $email</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<form method="post">
  <input type="hidden" value="edit_advertiser_do" name="action">
  <input type="hidden" value="$email" name="email">
  <p align="center"><font face="Arial" size="2">Please complete the form below,
  and then submit to modify an advertiser.</font></p>
  <p align="center"><font face="Arial" size="2"><font color="#ff0000">*</font>
  denotes required field.</font></p>
  <div align="center">
    <center>
    <table id="AutoNumber1" style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing="1" cellPadding="2" width="274" border="0" height="286">
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">Name :
        <font color="#ff0000">*</font></font></td>
        <td width="181" height="22"><input name="name" size="20" value="$name"></td>
      </tr>
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">Email :
        <font color="#ff0000">*</font></font></td>
        <td width="181" height="22">
        <input name="new_email" size="20" value="$email"></td>
      </tr>
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">Password :
        <font color="#ff0000">*</font></font></td>
        <td width="181" height="22">
        <input name="new_password" size="20" value="$new_password"></td>
      </tr>
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">Banner 1 :
        <font color="#ff0000">*</font></font></td>
        <td width="181" height="22">
        <input name="banner1" size="20" value="$banner1"></td>
      </tr>
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">Banner 2 :</font></td>
        <td width="181" height="22">
        <input name="banner2" size="20" value="$banner2"></td>
      </tr>
      <tr>
        <td width="157" height="17"><font face="Arial" size="2">Banner 3 :</font></td>
        <td width="181" height="17">
        <input name="banner3" size="20" value="$banner3"></td>
      </tr>
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">URL :
        <font color="#ff0000">*</font></font></td>
        <td width="181" height="22"><input name="url" size="20" value="$url"></td>
      </tr>
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">Last Top Up :
        <font color="#ff0000">*</font></font></td>
        <td width="181" height="22">
        <input name="last_top_up" size="20" value="$last_top_up"></td>
      </tr>
      <tr>
        <td width="157" height="22"><font face="Arial" size="2">Credit :
        <font color="#ff0000">*</font></font></td>
        <td width="181" height="22">
        <input value="$credit" name="credit_left" size="20"></td>
      </tr>
      <tr>
        <td width="157" height="19"><font face="Arial" size="2">Hits Received :</font></td>
        <td width="181" height="19">
        <input type="text" name="hits_received" size="20" value="$hits_received"></td>
      </tr>
      <tr>
        <td width="157" height="19"><font face="Arial" size="2">Exposures :</font></td>
        <td width="181" height="19">
        <input type="text" name="exposures" size="20" value="$exposures"></td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center"><input type="submit" value="Update" name="B1"></p>
  <p align="center">&nbsp;</p>
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>


TABLE;


} // end the while...


// close mysql, otherwise we clog it up...
mysql_close($connection);


}

/* ################################################################ */

function edit_advertiser_do() {

global $SETUSERNAME, $SETPASSWORD;
global $name, $email, $new_email, $new_password, $banner1, $banner2, $banner3, $url, $last_top_up, $credit_left, $hits_received, $exposures;
global $url, $host, $database, $password, $username, $debug, $webmaster_email;

 // check if they are logged in..
 if (!$SETPASSWORD || !$SETUSERNAME) { normal_error("No login cookie was set!"); }

 // check to see if all required stuff was done correctly....
 if (!$name)         { normal_error("You didn't enter a name! Please try again"); }
 if (!$email)        { normal_error("You didn't enter an email. This will be their login username, so please go back and submit one."); }
 if (!$new_password) { normal_error("You didn't specify a password!"); }
 if (!$banner1)      { normal_error("You didn't specify their banner URL (jpg/gif)"); }
 if (!$url)          { normal_error("You didn't specify a URL to send clicks too!"); }
 if (!$last_top_up)  { normal_error("You didn't specify a late top up date...usually the current date is ok to leave..."); }
 if ($credit_left == " "){ normal_error("You didn't specify a credit remaining amount. Please go back and correct it."); }

 // check format of email address...
 if(!eregi("[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,}$", $email)) {

   normal_error("Bad email address format! Please go back and try again...");

 }

 // check format of email address...
 if(!eregi("[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,}$", $new_email)) {

   normal_error("Bad email address format! Please go back and try again...");

 }

 // check for a valied format of money...
 if (!preg_match("/^[0-9]{1,25}$/",$credit_left)) {

   normal_error("The credit left you entered to be added is in an invalied format..please click bank and try again...");

 }

 // check for the format of the last top up...
 $goodDate = isValidDate($last_top_up);
 if (!$goodDate) {

   normal_error("The last update value you set is in the incorrect format, it needs to be like: day/month/year, i.e. 21/3/2002");

 }

 // check for a valied format of the banner 1 URL....
 if(!preg_match("/^\w+:+/i", $banner1))
    {
    normal_error("The image URL you provided for banner 1 does not appear to be valied. Please try again...");
    }


 // check for a valied format of the banner 2 URL....
 if ($banner2) {
   if(!preg_match("/^\w+:+/i", $banner2))
      {
      normal_error("The image URL you provided for banner 2 does not appear to be valied. Please try again...");
      }
 }

 // check for a valied format of the banner 3 URL....
 if ($banner3) {
   if(!preg_match("/^\w+:+/i", $banner3))
      {
      normal_error("The image URL you provided for banner 3 does not appear to be valied. Please try again...");
      }
 }

 // check for a valied format of the redirect URL....
 if(!preg_match("/^\w+:+/i", $url))
    {
    normal_error("The URL you provided does not appear to be valied. Please try again...");
    }

 // check for the format of the hits received....
   if(!preg_match("/^[0-9]$/i", $hits_received))   {

   normal_error("The hits received you entered was not valied. Only whole numbers please.");

 }

 // check for the format of the exposures...
   if(!preg_match("/^[0-9]{1,25}$/i", $exposures))  {

   normal_error("The exposures you entered was not valied. Only whole numbers please.");

 }

/* NOW EVERYTHING HAS BEEN CHECKED WE CAN EDIT THE DATABASE...*/

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...


 // update the stuff...
 $query = "UPDATE `advertiser_info` SET `Name` = '$name' , `Email` = '$new_email' ,  `BannerURL1` = '$banner1' , `BannerURL2` = '$banner2' , `BannerURL3` = '$banner3' , `URL` = '$url' , `LastTopUp` = '$last_top_up' , `CreditLeft` = '$credit_left' , `HitsReceived` = '$hits_received' , `Exposures` = '$exposures' , `Password` = '$new_password' WHERE `Email` = '$email'";


   $do = mysql_query($query);
   $error = mysql_error();

   if ($error) { echo $error; exit; }

   if ($debug) { echo "query executed....<BR>"; }

echo "Advertiser modified...<a href=admin.php?action=show_admin>click here to go back to the main menu...</a>";

// close mysql, otherwise we clog it up...
mysql_close($connection);

}

/* ################################################################ */

function delete_advertiser() {


// show the top half of the page...
echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Admin - Edit Advertiser</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
  <p align="center"><font face="Arial" size="2">Please click 'delete' next to an advertiser to delete that advertiser...</font></p>
  <div align="center">
    <center>
    <table id="AutoNumber1" style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing="1" cellPadding="2" width="300" border="0">

PAGE;

//now get SQL stuff, and return the <tr> cells etc...

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...


// find the password, or username if needed.
$query = "SELECT * FROM advertiser_info";

   $do = mysql_query($query);
   $error = mysql_error();

   if ($debug) { echo "query executed....<BR>"; }

//get info we need...to see if they have enuf credit
while ($cat = mysql_fetch_array($do)) {

// set up the variables from MySQL
$email  = $cat['Email'];

echo <<<TABLE

      <tr>
        <td width="250">$email</td>
        <td width="50">[
        <a href="admin.php?action=delete_advertiser2&email=$email">delete</a> ]</td>
      </tr>

TABLE;


} // end the while...


// now show the rest of the page...to close table and footer etc...

echo <<<PAGE

      <tr>
        <td width="157" height="22">&nbsp;</td>
        <td width="181" height="22">&nbsp;</td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center">&nbsp;</p>
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>


PAGE;

// close mysql, otherwise we clog it up...
mysql_close($connection);


}

/* ################################################################ */

// check the validity of the date...
function isValidDate($dt) {
 $arr = explode("/",$dt);
 if(count($arr) != 3) {
  return(false);
 } //end if

// day/mm/yyyy
 return(checkdate($arr[1],$arr[0],$arr[2]));
} //end fxn


/* ################################################################ */

function delete_advertiser_2() {

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug, $email;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...


 // update the stuff...
 $query = "DELETE FROM `advertiser_info` WHERE Email = '$email'";


   $do = mysql_query($query);
   $count_rows = mysql_affected_rows();

   if (!$count_rows) { error("Email address was not found in records...so could not be deleted!", $connection); }

   $error = mysql_error();

   if ($error) { echo $error; exit; }

   if ($debug) { echo "query executed....<BR>"; }

echo "Advertiser deleted...<a href=admin.php?action=show_admin>click here to go back to the main menu...</a>";


// close mysql, otherwise we clog it up...
mysql_close($connection);


}

/* ################################################################ */

function add_credit() {

// show the top half of the page...
echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Admin - Update Advertiser Credit</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
  <p align="center"><font face="Arial" size="2">Please click 'update' next to an advertiser to edit their current limit of credit...</font></p>
  <div align="center">
    <center>
    <table id="AutoNumber1" style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing="1" cellPadding="2" width="300" border="0">

PAGE;

//now get SQL stuff, and return the <tr> cells etc...

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...


// find the password, or username if needed.
$query = "SELECT * FROM advertiser_info";

   $do = mysql_query($query);
   $error = mysql_error();

   if ($debug) { echo "query executed....<BR>"; }

//get info we need...to see if they have enuf credit
while ($cat = mysql_fetch_array($do)) {

// set up the variables from MySQL
$email  = $cat['Email'];

echo <<<TABLE

      <tr>
        <td width="250">$email</td>
        <td width="50">[
        <a href="admin.php?action=add_credit_to_advertiser2&email=$email">update</a> ]</td>
      </tr>

TABLE;


} // end the while...


// now show the rest of the page...to close table and footer etc...

echo <<<PAGE

      <tr>
        <td width="157" height="22">&nbsp;</td>
        <td width="181" height="22">&nbsp;</td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center">&nbsp;</p>
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>


PAGE;


// close mysql, otherwise we clog it up...
mysql_close($connection);

}

/* ################################################################ */

function add_credit_2() {

global $email;

echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Banner Rotation Admin &gt; Add credit to advertiser...($email) </title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<p align="center"><font face="Arial" size="2">Please enter the amount of credit
you want to add to this use. Credit needs to be in the form of ¢ (i.e. 500
credits is $5).</font></p>
<form method="POST">
  <div align="center">
    <center>
    <table id="AutoNumber1" style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing="1" cellPadding="2" width="312" border="0">
      <tr>
        <td width="222"><font face="Verdana" size="2">How much do you want to
        add?</font></td>
        <td width="90">&nbsp; <input type="text" size="9" name="credit_to_add"></td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center"><input type="submit" value="Submit" name="B1"></p>
  <input type="hidden" name="email" value="$email">
  <input type="hidden" name="action" value="add_credit_to_advertiser3">
</form>
<p align="center">&nbsp;</p>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>


PAGE;


}

/* ################################################################ */

function add_credit_3() {

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug, $email, $credit_to_add;


 // check for a valied format of money...
 if (!preg_match("/^[0-9]{1,25}$/",$credit_to_add)) {

   normal_error("The credit left you entered to be added is in an invalied format..please click bank and try again...");

 }

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...

// get date...so we can update when the last credit update occured...
$todays_date = date('d/m/Y');

 // update the stuff...
 $query = "UPDATE `advertiser_info` SET `CreditLeft` = CreditLeft + $credit_to_add, LastTopUp = '$todays_date' WHERE Email = '$email'";


   $do = mysql_query($query);
   $count_rows = mysql_affected_rows();

   if (!$count_rows) { error("Email address was not found in records...so could not be deleted!", $connection); }

   $error = mysql_error();

   if ($error) { echo $error; exit; }

   if ($debug) { echo "query executed....<BR>"; }

echo "Advertiser's credit updated...<a href=admin.php?action=show_admin>click here to go back to the main menu...</a>";


// close mysql, otherwise we clog it up...
mysql_close($connection);



}

/* ################################################################ */

// show a table page with all the info on each advertiser...such as last updates
function view_stats() {

// show the top half of the page...
echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Admin - Delete Advertiser</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
  <p align="center"><font face="Arial" size="2">Here is the statistics for all the advertiser accounts you have listed. Click EDIT or DELETE to modify/delete these listings...<BR><BR><b>ALL CHANGES ARE NOT REVERSABLE!</b></font></p>
  <div align="center">
    <center>
  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="986" id="AutoNumber1" height="37">
      <tr>
        <td width="246" height="18">
        <p align="center"><b><font face="Verdana" size="2">Email Address</font></b></td>
        <td width="246" height="18">
        <p align="center"><b><font face="Verdana" size="2">Last Update</font></b></td>
        <td width="247" height="18">
        <p align="center"><b><font face="Verdana" size="2">Total Credit
        Remaining </font></b></td>
        <td width="247" height="18">
        <p align="center"><b><font face="Verdana" size="2">Other Options</font></b></td>
      </tr>

PAGE;

//now get SQL stuff, and return the <tr> cells etc...

// get globals fdor SQL login stuff..
global $url, $host, $database, $password, $username, $debug;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...


// find the password, or username if needed.
$query = "SELECT * FROM advertiser_info";

   $do = mysql_query($query);
   $error = mysql_error();

   if ($debug) { echo "query executed....<BR>"; }

//get info we need...to see if they have enuf credit
while ($cat = mysql_fetch_array($do)) {

// set up the variables from MySQL
$email  =      $cat['Email'];
$last_update = $cat['LastTopUp'];
$credit =      $cat['CreditLeft'];

echo <<<TABLE

 <tr>
        <td width="246" height="19">
        <p align="center"><font face="Verdana" size="2">$email</font></td>
        <td width="246" height="19">
        <p align="center"><font face="Verdana" size="2">$last_update</font></td>
        <td width="247" height="19">
        <p align="center"><font face="Verdana" size="2">$credit</font></td>
        <td width="247" height="19">
        <p align="center"><font face="Verdana" size="2">[
        <a href="admin.php?action=edit_advertiser2&email=$email">edit</a> ] [
        <a href="admin.php?action=delete_advertiser2&email=$email">delete</a> ]</font></td>
      </tr>


TABLE;


} // end the while...


// now show the rest of the page...to close table and footer etc...

echo <<<PAGE

      <tr>
        <td width="157" height="22">&nbsp;</td>
        <td width="181" height="22">&nbsp;</td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center">&nbsp;</p>
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>


PAGE;


// close mysql, otherwise we clog it up...
mysql_close($connection);

}

/* ################################################################ */

function edit_settings() {

global $username, $password, $host, $database, $debug, $cost_per_click, $default_banner_img, $default_banner_url, $webmaster_email, $url_to_paypal_php, $admin_username, $admin_password;

echo <<<PAGE



<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Banner System Admin Panel &gt; Update Settings...</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<p align="center"><font face="Arial" size="2">Simply edit the settings below to
directly affect the script. Please note, that editing the SQL settings
incorrectly<br>
will cause the script not to work!</font></p>
<form method="POST">
  <div align="center">
    <center>
  <table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="54%" id="AutoNumber1" height="221">
    <tr>
      <td width="50%" height="13"><font size="2" face="Verdana">SQL Username [
      <a href="#sql_username">help</a> ]</font></td>
      <td width="50%" height="13"><font face="Verdana">
      <input type="text" size="35" name="new_username" value="$username"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">SQL Password [
      <a href="#sql_password">help</a> ]</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_password" value="$password"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Host [
      <a href="#sql_host">help</a> ]</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_host" value="$host"></font></td>
    </tr>
    <tr>
      <td width="50%" height="18"><font size="2" face="Verdana">Database [
      <a href="#sql_database">help</a>
      ]</font></td>
      <td width="50%" height="18"><font face="Verdana">
      <input type="text" size="35" name="new_database" value="$database"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Debug on? [
      <a href="#debug">help</a>
      ] (1 = yes, 0 = no)</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_debug" value="$debug"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Cost Per Click [
      <a href="#cost_per_click">help</a> ]</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_cost_per_click" value="$cost_per_click"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Default Banner
      Image [ <a href="#default_banner_img">help</a> ]</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_default_banner_img" value="$default_banner_img"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Default Banner
      URL [ <a href="#default_banner_url">help</a> ]</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_default_banner_url" value="$default_banner_url"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Webmaster Email
      [ <a href="#webmaster_email">help</a> ]</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_webmaster_email" value="$webmaster_email"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">URL To PayPal
      PHP Script [ <a href="#url_to_php">help</a> ]</font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_url_to_paypal_php" value="$url_to_paypal_php"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Admin Username [
      <a href="#admin_username">help</a> ] </font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_admin_username" value="$admin_username"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19"><font size="2" face="Verdana">Admin Password [
      <a href="#admin_password">help</a> ] </font></td>
      <td width="50%" height="19"><font face="Verdana">
      <input type="text" size="35" name="new_admin_password" value="$admin_password"></font></td>
    </tr>
    <tr>
      <td width="50%" height="19">
      <p align="right"><BR><input type="submit" value="Submit">&nbsp;&nbsp;</td>
      <td width="50%" height="19">
      <p align="left"><BR>&nbsp;&nbsp;<input type="reset" value="Reset"></td>
    </tr>
  </table>
    </center>
  </div>
  <input type="hidden" name="action" value="edit_settings2">
</form>
<p align="center">&nbsp;</p>
<form method="POST">
  <input type="hidden" name="action" value="viewuser">
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>

PAGE;

}

/* ############################################################################### */

function update_settings() {

global $new_username, $new_password, $new_host, $new_database, $new_debug, $new_cost_per_click, $new_default_banner_img, $new_default_banner_url, $new_webmaster_email, $new_url_to_paypal_php, $new_admin_username, $new_admin_password;

// do some verifications first, to seeif most of it is ok to do...

 // first see if the cost per click is in a good format...
 if (!preg_match("/^[0-9]{1,25}/", $new_cost_per_click)) {

      normal_error("The value you entered for the cost per click does not seem to be valied. This is in cents, so MUST be a round number!");

 }

 // check format of email address...
 if(!eregi("[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,}$", $new_webmaster_email)) {

   normal_error("Bad email address format! Please go back and try again...");

 }

 // check for a valied format of the redirect URL....
 if(!preg_match("/^\w+:+/i", $new_default_banner_url))
    {
    normal_error("The Default Banner URL you provided does not appear to be valied. Please try again...");
    }

 // check for a valied format of the  default image URL....
 if(!preg_match("/^\w+:+/i", $new_default_banner_img))
    {
    normal_error("The Default Banner Image you provided does not appear to be valied. Please try again...");
    }

 // check for a valied format of the  default image URL....
 if(!preg_match("/^\d{1,1}$/", $new_debug))
    {
    normal_error("You did not enter a valied format for debug level. Enter 1 or 0. Please try again...");
    }

$put_in = <<<PUT_IN
<?php

\$username = "$new_username";
\$password = "$new_password";
\$host =     "$new_host";
\$database = "$new_database";
\$debug = $new_debug;

\$cost_per_click = $new_cost_per_click;

\$default_banner_img = "$new_default_banner_img";
\$default_banner_url = "$new_default_banner_url";

\$webmaster_email = '$new_webmaster_email';

\$url_to_paypal_php = "$new_url_to_paypal_php";

\$admin_username = "$new_admin_username";
\$admin_password = "$new_admin_password";

?>

PUT_IN;

$put = @fopen("settings.inc.php", "w+");
fputs ($put, $put_in);
fclose($put);

if ($phperror_msg) { error("Error: $phperror_msg"); }

echo "Updated settings file....Please click <a href=admin.php?action=show_admin>here</a> to go back to the main menu.";


}

/* ################################################################################ */


// if we get any errors we need to be able to refer them somewhere...so this is the place....
// only if we got SQL stuff to get rid of tho!
function error($error, $connection) {

echo $error;
mysql_close($connection);
exit;


}

/* ################################################################ */

// if we get any errors we need to be able to refer them somewhere...so this is the place...
function normal_error($error) {

echo $error;
exit;


}

/* ################################################################ */

?>