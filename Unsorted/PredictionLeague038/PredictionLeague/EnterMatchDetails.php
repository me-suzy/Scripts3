<?php
require "SystemVars.php";
require "DBaseFunctions.php";
?>
<html>
  <head>
    <title>
      EnglandFC Enter Match Details
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>
  <body>
    <table>
    <tr>
    <!-- Left Column -->
    <td valign="TOP">
    <?php
      include "menu.php";
    ?>
    </td>
    <!-- Middle Column -->
    <td>

    
    <form method="POST" action="InsertMatchData.php">
      <table>
        <!-- Match Date -->
        <tr>
          <td class="TBLROW">
            Match Date
          </td>
          <td class="TBLROW">
            <input type="TEXT" name="DATE">
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- Opposition -->
        <tr>
          <td class="TBLROW">
            Opposition
          </td>
          <td class="TBLROW">
            <input type="TEXT" name="OPP">
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- Home or Away -->
        <tr>
          <td class="TBLROW">
            Home or Away
          </td>
          <td class="TBLROW">
            <select name="HA">
              <option value="H">Home</option>
              <option value="A">Away</option>
            </select>
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- For -->
        <tr>
          <td class="TBLROW">
            For
          </td>
          <td class="TBLROW">
            <input type="TEXT" size="2" name="FOR">
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- Against -->
        <tr>
          <td class="TBLROW">
            Against
          </td>
          <td class="TBLROW">
            <input type="TEXT" size="2" name="AGAINST">
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- Attendance -->
        <tr>
          <td class="TBLROW">
            Attendance
          </td>
          <td class="TBLROW">
            <input type="TEXT" size="6" name="ATT">
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- Referee -->
        <tr>
          <td class="TBLROW">
            Referee ID
          </td>
          <td class="TBLROW">
            <input type="TEXT" size="4" name="REFEREE">
          </td>
          <td class="TBLROW">
            <a target="_REF" href="DisplayReferees.php">Referee List</a>
          </td>
        </tr>
        <!-- Venue -->
        <tr>
          <td class="TBLROW">
            Venue ID
          </td>
          <td class="TBLROW">
<?php
  GetVenuesAsSelectOption();
?>
          </td>
          <td class="TBLROW">
            <a target="_VENUE" href="DisplayVenues.php">Stadium List</a>
          </td>
        </tr>
        <!-- Level -->
        <tr>
          <td class="TBLROW">
            Level
          </td>
          <td class="TBLROW">
            <select name="LEVEL">
              <option value="FULL">Full</option>
              <option value="B">B</option>
              <option value="U21">U21</option>
              <option value="U18">U18</option>
            </select>
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- Gender -->
        <tr>
          <td class="TBLROW">
            Gender
          </td>
          <td class="TBLROW">
            <select name="GENDER">
              <option value="M">Male</option>
              <option value="F">Female</option>
            </select>
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <!-- Competition -->
        <tr>
          <td class="TBLROW">
            Competition
          </td>
          <td class="TBLROW">
            <input type="TEXT" name="COMP">
          </td>
          <td class="TBLROW">
          </td>
        </tr>
        <tr>
          <td class="TBLROW">
            <input type="SUBMIT" value="Enter" name="Enter">
          </td>
          <td class="TBLROW">
            <input type="RESET" value="Clear" name="Clear">
          </td>
        </tr>
      </table>
    </form>
    </td>
    </tr>
    </table>
  </body>
</html>
