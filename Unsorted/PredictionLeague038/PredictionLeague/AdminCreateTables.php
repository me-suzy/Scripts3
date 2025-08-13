<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : AdminCreateTables.php
 * Desc  : Form used to create the required database 
 *       : tables. The names given in this form need to
 *       : be reflected in the file SystemVars.php.
 *       : The list of current databases is displayed
 *       : underneath the form.
 ********************************************************/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Create the Tables for the Prediction League
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">

    <form method="POST" action="CreateDBase.php">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="3" align="CENTER">
          <font class="TBLHEAD">
            Database and Table Administration
          </font>
        </td>
      </tr>
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Database Host (URL)
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="TEXT" size="20" name="URL" value="localhost">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
          The FQDN for the database.
          </font>
        </td>
      </tr> 
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Username
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="TEXT" size="20" name="USERNAME">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            This entry is required to connect to the MySQL database if the database has authentication configured.
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
            This entry is required to connect to the MySQL database if the database has authentication configured.
          </font>
        </td>
      </tr> 
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Create Database
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="CHECKBOX" name="CREATEDB" value="TRUE">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            Uncheck this box if the database already exists. This allows creation of tables in an existing database.
          </font>
        </td>
      </tr> 
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Database Name
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="TEXT" size="20" name="DBASENAME">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            This is the name of the database to be created. If the database already exists, then the database will not be created.
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
  </body>
</html>

