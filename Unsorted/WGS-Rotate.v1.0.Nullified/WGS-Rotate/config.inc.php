<?


// ******************************************************************
// Configuration
// Please change the configuration to your needs.
// ******************************************************************

// MySQL database connection:
  $dbname = "dbname";        // Database name
  $dbusr  = "login";        // Your username when connecting to MySQL database
  $dbpass = "pass";            // Your password when connecting to MySQL database
  $dbhost = "localhost";   // host, where your database is located (usually localhost)

// admin page options:
  $admin_pass = "demo"; // password to access admin area
  $from_mail = "you@you.com"; // e-mail that will appear when you will send mass e-mails
  $from_mail_name = "you@you.com"; // name that will appear when you will send mass e-mails
  $admin_email = "you@you.com"; // your e-mail, where you're gona receive
                                         // the notifications about new urls
  
  $members_per_page = 30;  // as you're gona have huge member base, for faster navigation through
                           // admin area you can set a limit on how many members per page you want
                           // to view

// Color Scheme:
  $bcolor = "#C8C8C8";     // Table Border Color
  $tcolor = "#DFDFDF";     // Table Header Row Color
  $lrcolor = "#FFFFFF";    // Table Content Light Row Color
  $drcolor = "#EAEAEA";    // Table Content Dark Row Color

// admin area top design .. :)
$admin_page_title =  '
  <html>
    <head>
      <title>WGS-Rotate Script Admin Area</title>
      <style type="text/css">
        BODY {
          font-family:verdana;
          font-size:10pt
          }
        TD {
          font-size:10pt;
          }
        TH {
          font-size:10pt;
          }
      </style>
    </head>
    <body>
    <a href="admin.php">Admin area home</a><br />
      <center>
      ';
// admin area bottom design
$admin_page_bottom = '
      </center>
     </body>
    </html>
';

// member login form that is displayed when members try to access the member.php file
$member_login_box = '
  <form action="member.php" method="POST">
    <table cellspacing="0" cellpadding="2" width="300" border="1" bordercolor="'.$bcolor.'">
      <tr bgcolor="'.$tcolor.'">
        <th colspan="2">Member Login</th>
      </tr>
      <tr>
        <td>Username:</td>
        <td><input name="login" /></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input name="pass" type="password"></td>
      </tr>
      <tr>
        <td colspan="2"><center><input type="submit" value="Log-in"></center></td>
      </tr>
      <tr>
        <td colspan="2"><center>Not a member yet? <a href="signup.php">Register Now!</a></center></td>
      </tr>
    </table>
  </form>
';


// top part of the design, this one is applied to all pages, except the admin area .. :)
$design_top = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="lv" lang="lv">

<head>
<title>WGS-Rotate</title>
<style type="text/css">
    body{
       margin:0px;
       font-family:verdana;
       font-size:10pt
       }
    TD {
       font-size:10pt;
       }
    INPUT {
       border: 1px solid gray;
       background-color: #E8E8E8;
       }
</style>
</head>

<body>
<table cellspacing=0 cellpadding=0 width=100%>
  <tr>
    <td colspan="2" width=100% background="images/Untitled-1_03.gif" style="background-repeat:repeat-x;"><!--CyKuH [WTN]--><img src="images/Untitled-1_01.gif" width="338" height="66" border="0" /><img src="images/Untitled-1_02.gif" width="183" height="66" /></td>
  </tr>
  <tr>
    <td width="100" valign="top" style="padding-left:20px;">
    <img src="images/spacer.gif" width="100" height="1" /><br /><br />
      <a href="index.php">Home</a><br />
      <a href="signup.php">Sign Up</a><br />
      <a href="faq.php">FAQ</a><br />
      <a href="support.php">Support</a><br />
      <a href="member.php">Log-In</a><br /> <br /><br />
    </td>
    <td style="padding-left:50px; text-align:left;" width="100%"><br /><br />
';


// bottom part of the page design .. this one is applied to all pages except admin area
$design_bottom = '  <br /><br />
    </td>
  </tr>
  <tr>
    <td colspan="2" background="images/Untitled-1_07.gif"><img src="images/Untitled-1_06.gif" width="224" height="28" /></td>
  </tr>
</table>
</body>
</html>
';

// message that will appear when a member starts to rotate url, however he
// hasn't added any liks in his control panel html and design there is wellcome .. ;)
$no_urls_to_rotate = 'Sorry, but the WGS-Rotate owner has not added any links to rotate. Please return to our homepage: <a href="http://www.yourrotator.com">yourrotator.com</a>';

// ******************************************************************
// Script Part
// Normally nothing should be changed below
// ******************************************************************

// Connecting to a database
    $link = mysql_connect($dbhost, $dbusr, $dbpass)
          or die("Error Connecting $sitename database!");
    mysql_select_db($dbname) or die("Error Selecting $sitename database");
    
$today = getdate();
$datetime = $today['year']."-".$today['mon']."-".$today['mday']." ".$today['hours'].":".$today['minutes'].":".$today['seconds'];
$datetoday = $today['year']."-".$today['mon']."-".$today['mday'];
$yesterday  = date("Y-m-d", mktime (0,0,0,date("m")  ,date("d")-1,date("Y")));


?>
