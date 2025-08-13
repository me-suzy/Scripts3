<?php



// see if they want ot view HTML or do the isntall...
if ($action == "install") { do_install(); } else { show_setup(); }

/*                 DIVIDER                  */

function show_setup() {

echo <<<TEMPLATE

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Install</title>
</head>

<body>

<p align="center"><font face="Arial" size="4"><b>Ace PPC Banner Admin Page - Install</b></font></p>
<p align="center"><font face="Arial" size="2">Just edit the appropriate things below 
to reflect your server and setup. Then press &#39;install&#39; and all the table structure 
etc for the MySQL database will be created. Please note, you need permission to 
the database and the database must already exist. Also be sure that settings.inc.php 
is CHMODed to 666 (UNIX).</font></p>
<form method="POST">
  <div align="center">
    <center>
    <table id="AutoNumber1" style="BORDER-COLLAPSE: collapse" borderColor="#111111" height="221" cellSpacing="0" width="54%" border="0">
      <tr>
        <td width="50%" height="13"><font face="Verdana" size="2">SQL Username</font></td>
        <td width="50%" height="13"><font face="Verdana">
        <input size="35" name="username"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">SQL Password</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="password"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Host</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="host"></font></td>
      </tr>
      <tr>
        <td width="50%" height="18"><font face="Verdana" size="2">Database</font></td>
        <td width="50%" height="18"><font face="Verdana">
        <input size="35" name="database"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Debug on? (1 = 
        yes, 0 = no)</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="debug"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Cost Per Click</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="cost_per_click"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Default Banner 
        Image</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="default_banner_img"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Default Banner 
        URL</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="default_banner_url"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Webmaster Email</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="webmaster_email"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">URL To PayPal 
        PHP Script</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="url_to_paypal_php"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Admin Username</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="admin_username"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19"><font face="Verdana" size="2">Admin Password</font></td>
        <td width="50%" height="19"><font face="Verdana">
        <input size="35" name="admin_password"></font></td>
      </tr>
      <tr>
        <td width="50%" height="19">
        <p align="right"><br>
        <input type="submit" value="Submit">&nbsp; </p>
        </td>
        <td width="50%" height="19">
        <p align="left"><br>
&nbsp; <input type="reset" value="Reset"></p>
        </td>
      </tr>
    </table>
    </center>
  </div>
  <input type="hidden" value="install" name="action">
</form>
<p align="center">&nbsp;</p>
<form method="post">
  <input type="hidden" value="viewuser" name="action">
</form>
<p align="center"><font face="Arial"><font size="1">
<a href="http://www.ace-installer.com">Powered by Ace PPC Banner System</a><br>
</font><a href="http://www.ace-installer.com/support.php"><font size="1">Support</font></a></font></p>

</body>

</html>

TEMPLATE;



}





/* ######################################## */
/*                 DIVIDER                  */
/* ######################################## */


function do_install() {

global $username, $password, $host, $database, $debug, $cost_per_click, $default_banner_img, $default_banner_url, $webmaster_email, $url_to_paypal_php, $admin_username, $admin_password;

// connect to mySQL and if not pass back an error
  $connection = @mysql_connect($host, $username, $password);  $error = mysql_error();
       if (!$connection) { sql_error_report("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
       if ($debug) { echo "Host connection established....<BR>"; } //a little something for debugging


// now we need to connect the database

  $db = @mysql_select_db($database, $connection); $error = mysql_error();
      if (!$db) { sql_error_report("Unable to connect to database. Reason: $error", $connection); }
      if ($debug) { echo "Database connection established....<BR>"; } // a little debugging info if needed...

// run the query to update the 'sales' column...

 $query = "CREATE TABLE advertiser_info (
  PrimaryID tinyint(4) NOT NULL auto_increment,
  Name text NOT NULL,
  Email text NOT NULL,
  BannerURL1 text NOT NULL,
  BannerURL2 text NOT NULL,
  BannerURL3 text NOT NULL,
  URL text NOT NULL,
  LastTopUp text NOT NULL,
  CreditLeft int(11) NOT NULL default '0',
  HitsReceived int(11) NOT NULL default '0',
  Exposures int(11) NOT NULL default '0',
  Password text NOT NULL,
  UNIQUE KEY PrimaryID (PrimaryID)
) TYPE=MyISAM;";



 $query = mysql_query($query);

if (!$query) { $error = mysql_error(); sql_error_report("Unable to run query 1! Reason: $error", $connection); }

 $query = mysql_query("INSERT INTO advertiser_info VALUES (1,'Andy','webmaster@ace-host.com','http://www.ace-clipart.com/banner2.gif','http://www.ace-installer.com/bigbanner1.gif','','http://www.ace-installer.com','31/3/2002',345539,3,8,'password');");

 if (!$query) { $error = mysql_error(); sql_error_report("Unable to run query 2! Reason: $error", $connection); }

 $query = mysql_query("INSERT INTO advertiser_info VALUES (3,'andy','andy@ace-installer.com','http://www.ace-installer.com/bigbanner1.gif','','','http://www.ace-installer.com/perlscripts.php','11/04/2002',1795,1,1,'Newby2k');");



 if (!$query) { $error = mysql_error(); sql_error_report("Unable to run query 3! Reason: $error", $connection); }

mysql_close($connection); // exit the connection so we dont clog up MySQL

$put_in = <<<PUT_IN

<?php

\$username = "$username";
\$password = "$password";
\$host =     "$host";
\$database = "$database";
\$debug = $debug;

\$cost_per_click = $cost_per_click;

\$default_banner_img = "$default_banner_img";
\$default_banner_url = "$default_banner_url";

\$webmaster_email = '$webmaster_email';

\$url_to_paypal_php = "$url_to_paypal_php";

\$admin_username = "$admin_username";
\$admin_password = "$admin_password";

?>



PUT_IN;



$put = @fopen("settings.inc.php", "w+");

fputs ($put, $put_in);

fclose($put);



if ($phperror_msg) { error("Error: $phperror_msg"); }



echo "MySQL Data seems to have been installed successfully...<BR>";

echo "Settings in setting.inc.php have also been updated ok...<BR><BR>";

echo "Now goto admin.php to start administering your PPC banner rotation...<BR><BR>";





} // end function for redirect page.



/* ######################################## */

/*                 DIVIDER                  */

/* ######################################## */

// hopefully catch those nasty errors!
function error($error) {

echo $error;
exit;

}

/* ######################################## */

// error sub in case we get problems with SQL queries etc.

function sql_error_report($error, $connection) {

  echo $error;
  mysql_close($connection); // exit the connection so we dont clog up MySQL
  exit; // exit from the script...we can't do anything now...

 }

?>