<?php
  require "SystemVars.php";
  require "SortFunctions.php";
  require "dbaseFunctions.php";
  require "security.php";
//  require "error.php";

  /*******************************************************
  * Check the user id and password
  *******************************************************/
  session_set_cookie_params(60*60*24*7,"/$baseDirName/");
  session_start();
?>
<html>
<head>
<title>
Profile Icon Selection
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">
  <table class="MAINTB" border="0">
    <!-- Top banner, will include news -->
    <tr>
      <td colspan="3" align="center">
        <?php echo $HeaderRow ?>
      </td>
    </tr>
    <!-- Diplplay the next game -->
    <tr>
      <td colspan="3" align="center" class="TBLROW">
        <font class="TBLROW">
          <?php echo getNextGame() ?>
        </font>
      </td>
    </tr>
    <tr>
      <!-- Left column -->
      <td class="LEFTCOL">
        <!-- Menu -->
        <?php require "menus.php"?>
        <!-- End Menu -->
      </td>
      <!-- Central Column -->
      <td class="CENTERCOL">
        <table class="CENTER">
          <tr>
            <td align="CENTER" class="TBLHEAD" colspan="3">
              <font class="TBLHEAD">
                Click on the Icon you want to use
              </font>
            </td>
          </tr>
<?php
  $dirname = "icons";
  $dir = @opendir($dirname);
  if ($dir == FALSE) {
    // Oh no, no files.
    $error = "Installation problem, unable to open directory $dirname";
    echo($error);
  }

  $count = 0;
  while (($file = readdir($dir)) != FALSE) {
    if (TRUE == is_dir($file)) {
      continue;
    }

    if (($count%3) == 0) {
      echo "<tr>\n";
    }
    $count++;
?>
            <td align="CENTER" class="TBLROW">
              <font class="TBLROW">
<?php
                $fullname = $dirname."/".$file;
                echo "<a href=\"SelectIcon.php?icon=$file\">";
                echo "<img border=\"0\" src=\"$fullname\">";
                echo "</a>";
?>
              </font>
            </td>
<?php
    if (($count%3) == 0) {
      echo "</tr>\n";
    }
  }
  closedir($dir);
?>
        </table>

      </td>
      <!-- Right Column -->
      <td class="RIGHTCOL" align="RIGHT">
        <!-- Show the Prediction stats for the next match -->
        <?php ShowPredictionStatsForNextMatch(); ?>
        
        <!-- Competition Prize -->
        <?php require "Prize.html" ?>
      </td>
    </tr>
  </table>

</body>
</html>
