<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 9th December
 * File  : menus.php
 ********************************************************/

  /*******************************************************
  * Check the user id and password from the cookie.
  *******************************************************/
  $isAdmin =  CheckAdmin($User->usertype);
  echo "<!-- Logged in $User->loggedIn $User->userid -->\n";
?>
<!-- Menu -->
<table border="0" width="150">
<?php
  if (($homePage != "" and $homePageTitle != "") or $chatRoomURL != "") { 
?>
<tr>
<td align="center" class="LOGINHD">
<font class="LOGINHD">
Home
</font>
</td>
</tr>
<?php
}
?>
<tr>
<td class="TBLROW">
<!-- Home Page -->
<?php 
  if ($homePage != "" and $homePageTitle != "") {
    echo "<a href=\"$homePage\">$homePageTitle</a><br>";
  }
?>
<?php 
  if ($chatRoomURL != "") { 
?>
<a href="<?php echo $chatRoomURL ?>" target="_NewChatEnglFC">Chat Room</a><br>
<?php 
  } 
?>
</td>
</tr>
</table>

<?php
  if ($User != 0 && $User->loggedIn == TRUE) {
?>
    <table border="0" width="150">
    <tr>
    <td align="center" class="LOGINHD">
    <font class="LOGINHD">
    [<?php echo " $User->userid"; ?>]
    </font>
    </td>
    </tr>
    <tr>
    <td align="center" valign="middle" class="TBLROW">
      <a href="UserSelectIcon.php">
      <img border="0" alt="<?php echo $User->icon?>" src="<?php echo $User->icon?>">
      </a>
    </td>
    </tr>
    </table>
<?php
  }
?>
<table width="150">
<tr>
<td align="center" class="LOGINHD">
<font class="LOGINHD">
Menu
</font>
</td>
</tr>
<tr>
<td class="TBLROW">
<a href="PredictionIndex.php">Prediction Table</a><br>
<a href="ShowMatchResults.php">Fixtures/Results</a><br>

<?php 
  // Only show these if the user is loged on
  if ($User->loggedIn) {
?>
<a href="ShowMyPredictions.php">My Predictions</a><br>
<a href="ShowMyProfile.php">My Profile</a><br>
<a href="logout.php">Logout</a>
<?php
  }
?>
</td>
</tr>
<tr>
<td class="TBLROW">
<a href="HelpIndex.php">Help</a><br>
<a href="mailto:<?php echo $adminEmailAddr?>?subject=PredictionLeague">Email Us</a>
</td>
</tr>
</table>

<?php 
  // If the user is an administrator, show the admin index.
  if($isAdmin) {
    require "AdminMenus.php";
  }
?>
<table width="150">
<tr>
<td class="TBLROW">
<font class="VERSION">
<a class="VERSION" href="<?php echo $PLHome?>">
Prediction League Version <?php echo VERSION ?>
</a>
</font>
</td>
</tr>
</table>
<!-- End Menu -->
