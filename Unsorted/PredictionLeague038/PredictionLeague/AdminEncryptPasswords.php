<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th October 2002
 * File  : AdminEncryptPasswords.php
 * Desc  : Form used to update the current user settings
 *       : by encrypting the passwords.
 ********************************************************/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Encrypt the user passwords.
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">

    <form method="POST" action="EncryptPasswords.php">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="3" align="CENTER">
          <font class="TBLHEAD">
            Encrypt Passwords
          </font>
        </td>
      </tr>
      <tr>
      <tr>
        <td class="TBLROW" colspan="3" align="CENTER">
          <font class="TBLROW">
            Warning: This should be performed only once. Running this multiple times will corrupt the passwords.
          </font>
        </td>
      </tr>
      <tr>
        <td colspan="3" class="TBLROW" align="CENTER">
          <input type="SUBMIT" NAME="CREATE" VALUE="Encrypt Passwords">
        </td>
      </tr>
    </table>
  </form>
  </body>
</html>

