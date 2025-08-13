<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : SendPassword.php
 * Desc  : Send the password for the given user to the 
 *       : email address in the users profile. If the 
 *       : user cannot be found, display en error message.
 ********************************************************/
  require "SystemVars.php";
  require "dbaseFunctions.php";
  require "security.php";

  $username = $HTTP_POST_VARS["USERID"];
  $response = "Error";

  // Get the email address of the user
  $link = OpenConnection();
  if ($link == FALSE) {
    ErrorNotify("Unable to open connection for Sending Password to $username");
    exit;
  }

  $query = "SELECT * FROM $dbaseUserData where username=\"$username\"";
  $result = mysql_query($query, $link)
    or die("Query failed: $query");

  $array = mysql_fetch_assoc($result);
  $email = $array["email"];
  $password = $array["password"];

  if (true) {
    $response = "Password sent to <b>$email</b> for <b>$username</b>";
    // Send the email.
    $text = "You have requested your password for $PredictionLeagueTitle.\n Your password is $password\nThank you";
    mail($email, "$PredictionLeagueTitle",$text,"From: $adminEmailAddr");

  } else {
    $response = "Sorry we could not find the user <b>$username</b>, please make sure the name is spelt correctly.";
  }
    
  closeConnection($link);

?>

<html>
<head>
<title>Send Email</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>
<body class="MAIN">

  <table class="MAINTB">
    <tr>
      <td colspan="3" align="center">
        <!-- Header Row -->
        <?php echo $HeaderRow ?>
      </td>
    </tr>
    <tr>
      <td class="LEFTCOL">
        <?php include "menus.php"; ?>
      </td>
      <td class="CENTERCOL">
        <font class="TBLROW">
        <?php echo $response ?>
        </font>
      </td>
      <td class="RIGHTCOL">
        <?php require "LoginPanel.php"; ?>
      </td>
    </tr>
  </table>

</body>
</html>
