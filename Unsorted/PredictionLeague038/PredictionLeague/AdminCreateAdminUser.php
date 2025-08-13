<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : AdminCreateAdminUser.php
 * Desc  : Form used to create the admin user for the 
 *       : prediction league.
 ********************************************************/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Create the Admin User for the Prediction League
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">

<?php
  require "SystemVars.php";
  require "dbaseFunctions.php";
  require "security.php";
  // Test to see if the maximum number of admin users has already
  // been created.
  
  $link = OpenConnection();
  if ($link == FALSE) {
    ErrorNotify("Unable to open connection");
    exit;
  }

  $query = "select * from $dbaseUserData where usertype=\"4\"";
  $result = mysql_query($query) 
    or die("Unable to peform query: $query");

  if ($result == FALSE) {
    ErrorNotify("Query Failed : $query");
    CloseConnection($link);
    return TRUE;
  }
  
  // Count the number of admin users.
  $numrows = mysql_num_rows($result);
  if ($numrows >= $maxAdminUsers) {
?>
The Maximum number of admin users has been created. If you want to allow more, you need to change the configuration variable maxAdminUsers.
<?php
    if ($numrows >= $maxAdminUsers) {
?>
<p>
WARNING: you have more admin users [<?php echo $numrows?>] than are configured [<?php echo $maxAdminUsers?>]. 
<?php
    }
  } else {  
?>
    <form method="POST" action="CreateAdminUser.php">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="3" align="CENTER">
          <font class="TBLHEAD">
            Admin User Administration
          </font>
        </td>
      </tr>
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Admin User Name
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="TEXT" size="20" name="USER" value="">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            The name for the admin user.
          </font>
        </td>
      </tr> 
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Password
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="TEXT" size="20" name="PASSWORD">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            The password for the admin user.
          </font>
        </td>
      </tr> 
      <tr>
        <td colspan="3" class="TBLROW" align="CENTER">
          <input type="SUBMIT" NAME="CREATE" VALUE="CREATE">
        </td>
      </tr>
    </table>
  </form>
<?php
  }
?>

  </body>
</html>

