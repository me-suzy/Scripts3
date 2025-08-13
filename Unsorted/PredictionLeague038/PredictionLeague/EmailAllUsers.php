<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th August 2002
 * File  : EmailAllUsers.php
 * Desc  : Email all the users. 
 *       : Create a form that mails to the users.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

// Make sure the user trying to send the email is 
// an admin or above.
if (!CheckAdmin($User->usertype)) {
  // Forward to somewhere
  LogMsg("Attempt to send email by User $User->userid with insufficient permissions from $REMOTE_ADDR:$REMOTE_PORT.");
  forward("PredictionIndex.php");
}
?>

<html>
<head>
<title>
Email Users
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">

<table width="800">
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
<tr>
<td class="LEFTCOL">
<?php require "menus.php"?>
</td>
<td class="CENTERCOL">
<font class="TBLROW">
<form method="POST" action="SendEmail.php">
  Subject: <input type="TEXT" name="SUBJECT" size="40"><br>
  <textarea name="BODY" cols="40" rows="10"></textarea><br>
  <input type="SUBMIT" name="Send" value="Send">
</form>
</font>
</td>
<td class="RIGHTCOL">
  <?php require "LoginPanel.php"?>

  <!-- Show the Prediction stats for the next match -->
  <?php ShowPredictionStatsForNextMatch(); ?>
  
  <!-- Competition Prize -->
  <?php require "Prize.html"?>
</td>
</tr>
</table>

</body>
</html>
