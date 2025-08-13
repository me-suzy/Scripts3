<?php

  // get all the settings...
  require("admin/settings.inc.php");

  // decide what to do...
  if ($action == "add_advertiser_do") { add_advertiser(); }
  else                                { show_html();      }




  function show_html() {

  // show the adding page HTML
echo <<<PAGE


<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>PPC Banner System - Add New Advertiser</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<form method="POST">
  <input type="hidden" name="action" value="add_advertiser_do">
  <p align="center"><font face="Arial" size="2">Please complete the form below,
  and then submit to add a new advertiser. <br>
  Once added, you will then need to add credit by logging into the
  administration page.</font></p>
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
      </table>
    </center>
  </div>
  <p align="center"><input type="submit" value="Add" name="B1"></p>

</form>

</body>

</html>

PAGE;

  } // end the HTML showing part...


function add_advertiser() {

global $name, $email, $new_password, $banner1, $banner2, $banner3, $url, $last_top_up, $credit_left, $hits_received, $exposures;
global $url, $host, $database, $password, $username, $debug, $webmaster_email, $PHP_SELF, $HTTP_HOST;


 // check to see if all required stuff was done correctly....
 if (!$name)         { normal_error("You didn't enter a name! Please try again"); }
 if (!$email)        { normal_error("You didn't enter an email. This will be their login username, so please go back and submit one."); }
 if (!$new_password) { normal_error("You didn't specify a password!"); }
 if (!$banner1)      { normal_error("You didn't specify their banner URL (jpg/gif)"); }
 if (!$url)          { normal_error("You didn't specify a URL to send clicks too!"); }

 // check format of email address...
 if(!eregi("[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,}$", $email)) {

   normal_error("Bad email address format! Please go back and try again...");

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

  $todays_date = date('d/m/Y'); // get date, so we can set when account was setup.

// find the password, or username if needed.
$query = "INSERT INTO `advertiser_info` (`Name`, `Email`, `BannerURL1`, `BannerURL2`, `BannerURL3`, `URL`, `LastTopUp`, `CreditLeft`, `HitsReceived`, `Exposures`, `Password`) VALUES ('$name', '$email', '$banner1', '$banner2', '$banner3', '$url', '$todays_date', '0', '0', '0', '$new_password')";

   $do = mysql_query($query);
   $error = mysql_error();

   if ($error) { echo $error; exit; }

   if ($debug) { echo "query executed....<BR>"; }

// close mysql, otherwise we clog it up...
mysql_close($connection);

   $advertiser = "http://" . $HTTP_HOST . $PHP_SELF . "/admin/advertiser.php";

echo <<<PAGE



<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Ace PPC Banner System Admin Panel - Add an Advertiser &gt; Advertiser Added!</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page</b></font></p>
<form method="POST">
  <input type="hidden" name="action" value="add_advertiser_do">
  <p align="center"><font face="Arial" size="2">Your account has now been added.
  No please goto $advertiser and login with your email
  address, and the password you defined back at the last stage.</font></p>
  <p align="center">&nbsp;</p>

</form>

</body>

</html>

PAGE;


}

?>