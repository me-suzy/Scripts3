<?php
/*********************************************************
 * Author: John Astill
 * Date  : 18th December
 * File  : ForgotPassword.html
 * Desc  : Send the password to the user.
 ********************************************************/
require "SystemVars.php";
require "dbaseFunctions.php";
require "security.php";

// Access the session data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>
    Forgot Password
  </title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">

<table width="800">
  <!-- Row for header display -->
  <tr>
    <td colspan="3" align="center">
      <!-- Header Row -->
      <?php echo $HeaderRow ?>
    </td>
  </tr>
  <!-- Main body row -->
  <tr>
    <td valign="top">
      <?php include "menus.php"?>
    </td>
    <td valign="top">
      <form method="POST" action="SendPassword.php">
        <!-- Show the Users info -->
        <table width="500">
          <tr>
            <td colspan="2" align="CENTER" class="TBLHEAD">
              <font class="TBLHEAD">
                Password Reminder
              </font>
            </td>
          </tr>
          <tr>
            <td align="CENTER" class="TBLHEAD">
              <font class="TBLHEAD">
                User ID
              </font>
            </td>
            <td class="TBLROW">
              <font class="TBLROW">
                <input type="text" size="20" name="USERID" value="<?php echo $User->userid?>">
              </font>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="LEFT" class="TBLROW">
              <font class="TBLROW">
                Enter your User ID and press the send button. Your password will be emailed to the email address in your profile.
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLROW" align="CENTER" colspan="2">
              <input type="SUBMIT" name="Send" value="Send">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>

</body>
</html>



