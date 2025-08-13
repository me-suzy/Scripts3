<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : ShowMyProfile.php
 * Desc  : Display the predictions for the currently logged
 *       : in user. If the cookie does not exist for the
 *       : user,show them a login page.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
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
      My Profile for <?php echo $User->userid?>
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
    <SCRIPT LANGUAGE="JavaScript">
    <!--

    /***********************************************
     * Check the passwords are equal
     ***********************************************/
    function checkPasswords(form) {

      // Ensure that the passwords is not empty
      if (form.PWD1.value == "") {
        alert("Password is required");
        return (false);
      }

      // Ensure that the passwords are equal
      if (form.PWD1.value != form.PWD2.value) {
        alert("Passwords do not match");
        return (false);
      }

      if (form.PWD1.value.length >= 32) {
        alert("Password too long. Must be less than 32 characters.");
        return (false);
      }

      return true;
    }

    /***********************************************
     * Check the form is complete. 
     * Check the email address 
     ***********************************************/
    function checkForm(form) {

      // Ensure that an email address is entered
      if (form.EMAIL.value == "") {
        alert("Email address is required");
        return (false);
      }

      if (form.EMAIL.value.length >= 60) {
        alert("Email address too long. Must be less than 60 characters.");
        return (false);
      }

      // Ensure that an email address is valid
      if (form.EMAIL.value.indexOf('@') < 0) {
        alert("Email address is not valid");
        return (false);
      }

      return true;
    }

    // -->
    </SCRIPT>
  </head>

<body class="MAIN">
<!-- Main table -->
<table class="MAINTB">
  <tr>
    <td colspan="3" align="center">
      <!-- Header Row -->
      <?php echo $HeaderRow ?>
    </td>
  </tr>
  <!-- Display the next game -->
  <tr>
    <td colspan="3" align="center" class="TBLROW">
      <font class="TBLROW">
        <?php echo getNextGame() ?>
      </font>
    </td>
  </tr>

  <?php if ($ErrorCode != "") { ?>
  <tr>
  <td colspan="3" bgcolor="red" align="center">
  <!-- Error Message Row -->
  <font class="TBLHEAD">
  <?php echo $ErrorCode ?>
  </font>
  </td>
  </tr>
  <?php 
    // Empty the error code
    $ErrorCode = "";
  }
  ?>

  <tr>
    <!-- Left Column -->
    <td valign="top" class="LEFTCOL">
      <?php include "menus.php"?>
    </td>
    <!-- Central Column -->
    <td valign="top" align="CENTER" class="CENTERCOL">
      <!-- Show the Users info -->
      <form method="POST" action="UpdateProfile.php">
        <table border="0" width="500">
          <tr>
            <td align="CENTER" class="TBLHEAD">
              <font class="TBLHEAD">
                User ID
              </font>
            </td>
            <td align="CENTER" class="TBLHEAD">
              <font class="TBLHEAD">
                Email Address
              </font>
            </td>
            <td align="CENTER" class="TBLHEAD">
              <font class="TBLHEAD">
                Icon <!-- <a href="ChangeIcon.php">[change]</a>-->
              </font>
            </td>
            <td align="CENTER" class="TBLHEAD">
              <font class="TBLHEAD">
                Join Date
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLROW">
              <font class="TBLROW">
                <?php echo $User->userid ?>
                <input type="HIDDEN" name="USER" value="<?php echo $User->userid ?>">
              </font>
            </td>
            <td class="TBLROW">
              <font class="TBLROW">
                <input type="text" size="32" name="EMAIL" value="<?php echo $User->emailaddr ?>">
              </font>
            </td>
            <td class="TBLROW">
              <font class="TBLROW">
                <a href="UserSelectIcon.php"><?php echo $User->icon ?></a>
              </font>
            </td>
            <td class="TBLROW">
              <font class="TBLROW">
                <?php echo $User->createdate ?>
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLROW" align="CENTER" colspan="4">
              <input type="submit" onClick="return checkForm(this.form);" value="Change Profile" name="Change">
            </td>
          </tr>
        </table>
      </form>

      <!-- Form for changing the password -->
      <form method="POST" action="ChangePassword.php">
        <table border="0">
          <tr>
            <td class="TBLHEAD" align="CENTER" colspan="2">
              <font class="TBLHEAD">
                Change Password
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLHEAD" align="LEFT">
              <font class="TBLHEAD">
                Password
              </font>
            </td>
            <td class="TBLHEAD" align="LEFT">
              <font class="TBLHEAD">
                Again
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLROW" align="LEFT">
              <font class="TBLROW">
                <input type="hidden" name="USERID" value="<?php echo $User->userid; ?>">
                <input type="text" size="20" name="PWD1">
              </font>
            </td>
            <td class="TBLROW" align="LEFT">
              <font class="TBLROW">
                <input type="text" size="20" name="PWD2">
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLROW" align="CENTER" colspan="2">
              <font class="TBLROW">
                <input type="submit" onClick="return checkPasswords(this.form);" Value="Change Password" Name="ChangePwd">
              </font>
            </td>
          </tr>
        </table>
      </form>
    </td>
    <!-- Left Column -->
    <td valign="top" class="RIGHTCOL">
      <!-- Show the Prediction stats for the next match -->
      <?php ShowPredictionStatsForNextMatch(); ?>
      
      <!-- Competition Prize -->
      <?php require "Prize.html" ?>
    </td>
  </tr>
</table>

</body>
</html>


