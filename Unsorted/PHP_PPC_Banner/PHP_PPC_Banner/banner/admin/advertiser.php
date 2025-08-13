<?php





/*

WHAT IS THIS FILE?



This is the file that will display the banner...



*/





require("settings.inc.php");



if ($action == "login")               { do_login();        } // verify the login info they sent.

elseif ($action == "add_credit")      { add_credit();      } // show the page where they can decide how much they wanna top up...

elseif ($action == "send_add_credit") { send_credit();     } // check how much they asked for, and if ok then save price as cookie, and redirect appropriately to PayPal

else                                  { show_login_page(); } // if nothing was sent, or it was invalid, then send em here



/* ######################################### */



// page to show when they need to log into an account...

function show_login_page() {



echo <<<PAGE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Advertiser Login Page...</title>
</head>

<body>

<p align="center"><font face="Verdana" size="4"><b>Advertiser Login Page</b></font></p>
<p align="center"><font face="Verdana" size="2">Complete the form below to log 
in. From here you can add credit and view statistics on your banners.</font></p>
<form method="POST">
  <div align="center">
    <center>
    <table border="0" cellpadding="2" cellspacing="0" id="AutoNumber1" fpstyle="7,011111100" style="border-collapse: collapse; border-left: .75pt solid black; border-right: .75pt solid black; border-top: 1.5pt solid black; border-bottom: 1.5pt solid black" bordercolor="#111111" height="49" width="402">
      <tr>
        <td width="209" style="font-weight: bold; color: white; border-left-style: none; border-right-style: none; border-top-style: none; border-bottom: .75pt solid black; background-color: #B4B4CA" height="23">
        <font face="Verdana" size="2">Email Address (username) : </font></td>
        <td width="183" style="font-weight: bold; color: white; border-left-style: none; border-right-style: none; border-top-style: none; border-bottom: .75pt solid black; background-color: #B4B4CA" height="23">
        <font face="Verdana">
        <input type="text" size="23" name="login_username" style="color: #008000; border-style: double; border-width: 3; background-color: #D0D0DD"></font></td>
      </tr>
      <tr>
        <td width="209" style="font-weight: bold; color: black; border-style: none; background-color: white" height="18">
        <font face="Verdana" size="2">Password :</font></td>
        <td width="183" style="color: black; border-style: none; background-color: white" height="18">
        <font face="Verdana">
        <input type="password" size="23" name="login_password" style="color: #008000; border-style: double; border-width: 3; background-color: #D0D0DD"></font></td>
      </tr>
    </table>
    </center>
  </div>
  <p align="center">
  <input type="submit" value="Login" name="B1" style="color: #008000; border-style: double; border-width: 3; background-color: #B4B4CA"></p>
  <input type="hidden" name="action" value="login">
</form>

</body>

</html>



PAGE;





}



/* ################################################################ */



// do login stuff, and add cooklies if ok, then redirect to main();

function do_login() {



global $login_username, $login_password;

global $url, $host, $database, $password, $username, $debug, $webmaster_email;



// connect to mySQL and if not pass back an error

$connection = mysql_connect($host, $username, $password);  $error = mysql_error();

if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }

if ($debug) { echo "host connection established...."; } //a little something for debugging



// now we need to connect the database

$db = mysql_select_db($database, $connection); $error = mysql_error();

if (!$db) { error("Unable to execute command. Reason: $error", $connection); }

if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...





// find the password, or username if needed.

$query = "SELECT * FROM advertiser_info WHERE Email = '$login_username'";

   if ($debug) { echo $query . "<BR>"; }



   $login = mysql_query($query);

   $error = mysql_error();



   if ($debug) { echo "query executed....<BR>"; }



//   if (!mysql_fetch_array($login)) { echo "no results"; }



//get info we need...to see if they have enuf credit

while ($cat = mysql_fetch_array($login)) {



$is = 1; // set this to true, so we know a result was found...



$password_from_db = $cat['Password'];



    if ($debug) { echo "Got password <BR>"; }

    if ($login_password == $password_from_db) {



// set up the variables from MySQL

$CreditLeft   = $cat['CreditLeft'];

$Impressions  = $cat['Impressions'];

$HitsReceived = $cat['HitsReceived'];

$Exposures    = $cat['Exposures'];

$BannerURL1   = $cat['BannerURL1'];

$BannerURL2   = $cat['BannerURL2'];

$BannerURL3   = $cat['BannerURL3'];





// show the page...

echo <<<PAGE



<html>



<head>

<meta http-equiv="Content-Language" content="en-gb">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title>Advertiser Banner Stats Page...</title>

</head>



<body>



<p align="center"><font face="Verdana" size="4"><b>Advertiser Login Page</b></font></p>



<p align="center"><font face="Verdana" size="2">

<a href="advertiser.php?action=add_credit&do_email=$login_username">Add Additional Credit</a></font></p>

<p align="center"><font face="Verdana" size="2">Please enter the email address

you have registered with your account below, <br>

along with the password that was set up with it. BOTH are case sensitive. </font>

</p>

  <div align="center">

    <center>

    <table border="0" cellpadding="2" cellspacing="0" id="AutoNumber1" fpstyle="7,011111100" style="border-collapse: collapse; border-left: .75pt solid black; border-right: .75pt solid black; border-top: 1.5pt solid black; border-bottom: 1.5pt solid black" bordercolor="#111111" height="49" width="627">

      <tr>

        <td width="209" style="font-weight: bold; color: white; border-left-style: none; border-right-style: none; border-top-style: none; border-bottom: .75pt solid black; background-color: #B4B4CA" height="23">

        <p align="center"><font face="Verdana" size="2">Credit Left (¢)</font></td>

        <td width="183" style="font-weight: bold; color: white; border-left-style: none; border-right-style: none; border-top-style: none; border-bottom: .75pt solid black; background-color: #B4B4CA" height="23">

        <p align="center">Clicks</td>

        <td width="183" style="font-weight: bold; color: white; border-left-style: none; border-right-style: none; border-top-style: none; border-bottom: .75pt solid black; background-color: #B4B4CA" height="23">

        <p align="center">Impressions</td>

        <td width="408" style="font-weight: bold; color: white; border-left-style: none; border-right-style: none; border-top-style: none; border-bottom: .75pt solid black; background-color: #B4B4CA" height="23">

        <p align="center">Banners</td>

      </tr>

      <tr>

        <td width="209" style="font-weight: bold; color: black; border-style: none; background-color: white" height="18">

        <p align="center">$CreditLeft</td>

        <td width="183" style="color: black; border-style: none; background-color: white" height="18">

        <p align="center">$HitsReceived</td>

        <td width="183" style="color: black; border-style: none; background-color: white" height="18">

        <p align="center">$Exposures</td>

        <td width="408" style="color: black; border-style: none; background-color: white" height="18">

        <p align="center">$BannerURL1<br>

        $BannerURL2<br>

        $BannerURL3</td>

      </tr>

    </table>

    </center>

  </div>



<p align="center"><font face="Verdana" size="2">The banners you have listed with this account are;</font></p>



PAGE;



if ($BannerURL1) {

echo "<p align=\"center\"><img src=$BannerURL1></p>";

}

if ($BannerURL2) {

echo "<p align=\"center\"><img src=$BannerURL2></p>";

}

if ($BannerURL3) {

echo "<p align=\"center\"><img src=$BannerURL3></p>";

}





echo <<<PAGE



<p align="center"><font face="Verdana" size="2">To change these banners, please

contact the <a href='mailto:$webmaster_email'>webmaster</a> with the changes you would like to make.</font></p>



</body>



</html>



PAGE;



} else { normal_error("Bad username/password combination....please click bank and try again."); }



} // end the while



if (!$is) { normal_error("The username you provided was not found! Please double check it is correct and try again..."); }



}



/* ################################################################ */



function add_credit() {



global $do_email;



echo <<<PAGE



<html>



<head>

<meta http-equiv="Content-Language" content="en-gb">

<title>Purchase more banner credit...</title>

<body topmargin="0" leftmargin="0">



<div align="center">

  <center>

  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber1">

    <tr>

      <td width="100%" bgcolor="#0080C0">

      <p align="center"><br>

      <font face="Arial" color="#FFFFFF">Purchase more credit for $email banner

      account...</font><br>

&nbsp;</td>

    </tr>

    <tr>

      <td width="100%">&nbsp;<p align="center"><font face="Verdana" size="2">To add credit please click on the

      'next' button. This will take you to thePayPal site, where you can

      enter the amount of money you want to top up with.</font></p>

      <form method="POST">

        <p align="center">

        <font face="Verdana"><font size="2">How much do you want to purchase?

        </font>

        <input type="text" name="price_update" size="8" style="border: 1px ridge #000000"><font size="2">

        (In USD without the $ sign, in format of 12.33 etc)</font></font></p>

        <p align="center">

        <input type="submit" value="Next &gt;&gt;&gt;" name="B1" style="color: #008000; border-style: double; border-width: 3; background-color: #C0C0C0"></p>

        <input type="hidden" name="email" value="$do_email">

        <input type="hidden" name="action" value="send_add_credit">

      </form>

      <p>&nbsp;</td>

    </tr>

  </table>

  </center>

</div>

<b></b>

<body>

</head>

</body>



</html>



PAGE;



}



/* ################################################################ */



// verify if they sent everything ok, and if so redirect to PAypal..also sert cookie so they dont try and trick the sytstem with fake prices...

function send_credit() {



global $email, $price_update, $webmaster_email, $url_to_paypal_php;



if (!preg_match("/[0-9]{1,6}\.[0-9]{1,2}$/",$price_update)) {



   echo "The price you entered to be added is in an invalied format..please click bank and try again...(include the .00 at the end if you are using a rounded sum of money)";

   exit;



}



if(!eregi("[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,}$", $email)) {



   echo "Bad email address format! Please go back and try again...";

   exit;



}



setcookie(MAINEMAIL, $email); // set their email as cookie, so we can use it later...



header("Location: https://www.paypal.com/xclick/business=$webmaster_email&item_name=Credit Update&item_number=1&amount=$price_update&return=$url_to_paypal_php");



}



/* ################################################################ */



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