<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : EnterPrediction.php
 * Desc  : Enter a prediction given the selected game.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

 $user = $HTTP_GET_VARS["user"];
 $date = $HTTP_GET_VARS["date"];
 $home = $HTTP_GET_VARS["home"];
 $away = $HTTP_GET_VARS["away"];
 $homescore = $HTTP_GET_VARS["hs"];
 $awayscore = $HTTP_GET_VARS["as"];

  /*******************************************************
  * Check the user id and password
  *******************************************************/
  session_set_cookie_params(60*60*24*7,"/$baseDirName/");
  session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Enter Prediction
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">

    <SCRIPT LANGUAGE="JavaScript">
    <!--

    // Check that all the values required for the
    // prediction is complete.
    //   USER
    //   DATE
    //   HOMETEAM
    //   HOMESCORE
    //   AWAYSCORE
    //   AWAYTEAM
    function checkPredictionContent(form) {

      /*
      alert("User "+form.USER.value+
            "\nDATE "+form.DATE.value+
            "\nHOMETEAM "+form.HOMETEAM.value+
            "\nHOMESCORE "+form.HOMESCORE.value+
            "\nAWAYSCORE "+form.AWAYSCORE.value+
            "\nAWAYTEAM "+form.AWAYTEAM.value);
            */

      // Ensure a USER ID is entered
      if (form.USER.value == "") {
        alert("User Required");
        return false;
      }

      // Ensure that the passwords is not empty
      if (form.HOMETEAM.value == "") {
        alert("Hometeam is required");
        return false;
      }

      // Ensure that the passwords is not empty
      if (form.HOMESCORE.value == "" || form.HOMESCORE.value < 0) {
        alert("Homescore is not valid "+homescore);
        return false;
      }

      // Ensure that the passwords is not empty
      if (form.AWAYTEAM.value == "") {
        alert("AWAYTEAM is not valid");
        return false;
      }

      // Ensure that the passwords is not empty
      if (form.AWAYSCORE.value == "" || form.AWAYSCORE.value < 0) {
        alert("Awayscore is not valid "+awayscore);
        return false;
      }

      return true;
    }

    // -->
    </SCRIPT>

</head>

<body class="MAIN">
  <table class="MAINTB" border="0">
    <!-- Top banner, will include news -->
    <tr>
      <td colspan="3" align="center">
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
      <td valign="top">
        <?php include "menus.php"; ?>
      </td>
      <td valign="top">
        <table width="500">
          <tr>
            <td>
              <form method="POST" action="SubmitPrediction.php">
              <table width="500">
              <tr>
              <td align="center" colspan="8" class="TBLHEAD">
              <font class="TBLHEAD">
              Prediction
              </font>
              </td>
              </tr>
              <tr>
              <td class="TBLROW">
              <font class="TBLROW">
              <?php echo $user; ?>
              <input type="HIDDEN" name="USER" value="<?php echo $user; ?>">
              </font>
              </td>
              <td class="TBLROW">
              <font class="TBLROW">
              <?php echo $date; ?>
              <input type="HIDDEN" name="DATE" value="<?php echo $date; ?>">
              </font>
              </td>
              <td class="TBLROW">
              <font class="TBLROW">
              <?php echo $home; ?>
              <input type="HIDDEN" name="HOMETEAM" value="<?php echo $home; ?>">
              </font>
              </td>
              <td class="TBLROW">
              <font class="TBLROW">
              <input type="TEXT" size="2" name="HOMESCORE" value="<?php echo $homescore?>">
              </font>
              </td>
              <td class="TBLROW">
              <font class="TBLROW">
              v
              </font>
              </td>
              <td class="TBLROW">
              <font class="TBLROW">
              <input type="TEXT" size="2" name="AWAYSCORE" value="<?php echo $awayscore?>">
              </font>
              </td>
              <td class="TBLROW">
              <font class="TBLROW">
              <?php echo $away; ?>
              <input type="HIDDEN" name="AWAYTEAM" value="<?php echo $away; ?>">
              </font>
              </td>
              <td class="TBLROW">
              <font class="TBLROW">
              <input type="SUBMIT" onClick="return checkPredictionContent(this.form);" name="Submit" value="Submit">
              </font>
              </td>
              </tr>
              </table>
              </form>
            </td>
          </tr>
        </table>
      </td>
      <!-- Right Column -->
      <td class="RIGHTCOL" align="RIGHT">
        <!-- Show the login panel if required -->
        <?php require "LoginPanel.php" ?>

        <!-- Show the Prediction stats for the next match -->
        <?php ShowPredictionStatsForNextMatch(); ?>
    
        <!-- Competition Prize -->
        <?php require "Prize.html"?>
      </td>
    </tr>
  </table>
</body>
</html>


