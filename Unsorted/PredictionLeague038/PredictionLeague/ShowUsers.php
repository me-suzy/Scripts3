<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 23rd January 2002
 * File  : ShowUsers.php
 * Desc  : Display a table with all the user details 
 *       : except the password.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

// If the user is not an admin user then they can't see this page.
if ($User->usertype < 4) {
  // Go back to the prediction index page.
  forward();
}


/////////////////////////////////////////////////////////
// Show a list of the users.
/////////////////////////////////////////////////////////
function ShowUsers($user) {
  global $dbaseUserData;

  $link = OpenConnection();
  if ($link == FALSE) {
    echo "Unable to connect to the dbase";
    return;
  }
  
  $userquery = "SELECT * FROM $dbaseUserData";
  $userresult = mysql_query($userquery)
      or die("Query failed: $userquery");

  // Display the username as a header.
  echo "<table width=\"500\">";
  echo "<tr>";
  echo "<td class=\"TBLHEAD\" colspan=\"5\" align=\"center\"><font class=\"TBLHEAD\">Users</font></td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Username</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Email</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Icon</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Since</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">&nbsp;</font></td>";
  echo "</tr>";
  // First loop. Used to get all the users.
  while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
    // For each user display all their predictions.
    // against the actual result.
    $username = $userline["username"];
    $icon = $userline["icon"];
    $email = $userline["email"];
    $since = $userline["since"];

    echo "<tr>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$username</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"mailto:$email\">$email</a></font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$icon</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$since</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"DeleteUser.php?user=$username\" onclick=\"return confirm('Are you sure you want to delete $username and all their predictions');\">delete user</a></font></td>";
    echo "</tr>";
  }
  echo "</table>";

  CloseConnection($link);
}
?>

<html>
<head>
<title>
<?php echo "Users\n"?>
</title>
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
<?php
  /*******************************************************
  * Check the user id and password from the cookie.
  *******************************************************/
  $user = $HTTP_GET_VARS["user"];
  ShowUsers($user);
?>
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
