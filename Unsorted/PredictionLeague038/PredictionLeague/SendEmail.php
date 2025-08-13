<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th August 2002
 * File  : SendEmail.php
 * Desc  : Send the email to all the users.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

  // Users are stored in a string
  $users = "";

  $subject = $HTTP_POST_VARS["SUBJECT"];
  $body = $HTTP_POST_VARS["BODY"];

  // Get all the users.
  $link = OpenConnection();

  $query = "select username, email from $dbaseUserData";
  $result = mysql_query($query)
    or die("Unable to perform query: $query");

  // Only versions of PHP over 4.3 support BCC.
  if (version_compare(phpversion(),'4.3') != -1) {
    while ($line = mysql_fetch_array($result)) {
      $users = $line["email"].", $users";
    }

    // Send the email
    if (@mail($adminEmailAddr,$subject,$body,
          "From: $adminEmailAddr\r\n"
          ."Reply-To: $adminEmailAddr\r\n"
          ."Bcc: $users") == FALSE) {
      LogMsg("Unable to send email $subject<br>$body");
    }
  } else {
    // Send as individual emails.
    while ($line = mysql_fetch_array($result)) {
      $user = $line["email"];
      if (FALSE == @mail($user,$subject,$body,
          "From: $adminEmailAddr\r\n"
          ."Reply-To: $adminEmailAddr\r\n")) {
        LogMsg("Unable to send email $subject<br>$body");
      }
    }
  }

  CloseConnection($link);
  forward("PredictionIndex.php");
?>
