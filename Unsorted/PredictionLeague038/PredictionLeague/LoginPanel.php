<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : LoginPanel.php
 * Desc  : Display the login panel if an user is not 
 *       : logged in.
 ********************************************************/
if ($User == 0 || $User->loggedIn == FALSE) {
?>
  <!-- Login panel -->
  <form method="POST" action="login.php">
    <table width="150" border="0">
      <tr>
        <td align="center" colspan="2" class="LOGINHD">
          <font class="LOGINHD">
            Login
          </font>
        </td>
      </tr>
      <tr>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            Username:
          </font>
        </td>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            <input type="text" size="10" name="LOGIN">
          </font>
        </td>
      </tr>
      <tr>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            Password
          </font>
        </td>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            <input type="password" size="10" name="PWD">
          </font>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="2" class="LOGINRW">
          <font class="LOGINRW">
            <input type="submit" name="logon" value="logon">
          </font>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="LOGINRW">
          <font class="LOGINRW">
            <a href="CreateNewUser.php">New User</a>
          </font>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="LOGINRW">
          <font class="LOGINRW">
            <a href="ForgotPassword.php">Forgot password</a><br>
          </font>
        </td>
      </tr>
    </table>
  </form>
<?php 
} 
?>
