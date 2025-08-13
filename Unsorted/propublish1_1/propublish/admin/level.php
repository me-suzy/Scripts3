<? 
$req_level = 1;
include "inc_t.php";
?>

<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5">
  <tr>
    <td valign="top">
    <?
     if ($submit)
     {



          if (!$levelname_f OR !$level_f)
          {
           $error = 1;
           print "<font color=red>$la_mand_error1</font><br>";
          }


          if ($error <> 1)
          {

              if (!$levelid)
              {
               $sql = "INSERT INTO level_news (levelname,level) values ('$levelname_f', '$level_f')";
              }
              else
              {
               $sql = "UPDATE level_news SET levelname = '$levelname_f', level = '$level_f' WHERE levelid = '$levelid'";
              }

              $result = mysql_query($sql);




               if ($result)
               {
                print "<b>$la_level_saved</b><br>";
               }
          }
     }

     if ($levelid)
     {
        $sql = "SELECT * FROM level_news WHERE levelid = '$levelid'";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $levelname  = $myrow["levelname"];
              $level_db = $myrow["level"];
        }

     print "<b>$la_editing $level_db $levelname</b><br>";
     }

     if ($delete)
     {
        $sql = "DELETE FROM level_news WHERE levelid = $delete";
        $result = mysql_query($sql);
        if ($result)
        {
         print "<b>$la_deleted_level</b><br>";
        }

     }


    ?>
    </td>
  </tr>
</table>

<br>
<form method="post" action="level.php">
<input type="hidden" name="levelid" value="<? echo $levelid; ?>">
<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5">
  <tr>
    <td valign="top" bgcolor="#C0C0C0" colspan="2"><b><? echo $la_newedit; ?></b></td>
  </tr>
  <tr>
    <td valign="top"><font face="Arial" size="2"><? echo $la_levelname ?></font></td>
    <td align="right" valign="top"><input type="text" name="levelname_f" size="25" value="<? echo $levelname; ?>"></td>
  </tr>
  <tr>
    <td valign="top"><font face="Arial" size="2"><? echo $la_level ?></font></td>
    <td align="right" valign="top"><input type="text" name="level_f" size="25" value="<? echo $level_db; ?>"></td>
  </tr>


  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right" valign="top"><input type="submit" value="<? echo $la_save ?>" name="submit"></td>
  </tr>
</table>
</form>

<table border="1" cellpadding="2" width="400" bgcolor="#E5E5E5" bordercolorlight="#FFFFFF" bordercolordark="#E5E5E5" class="articlebody">
  <tr>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_levelname ?></b></td>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_level ?></b></td>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_change ?></b></td>
  </tr>

          <?
        $sql = "SELECT * FROM level_news";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $levelid = $myrow["levelid"];
              $levelname  = $myrow["levelname"];
              $level_db = $myrow["level"];

              print "<tr>";
              print "<td valign=\"top\"><font face=\"Arial\" size=\"2\"><a href=\"level.php?levelid=\">$levelname</a></font></td>";
              print "<td valign=\"top\"><font face=\"Arial\" size=\"2\">$level_db</font></td> ";
              print "<td valign=\"top\"><font face=\"Arial\" size=\"2\"><a href=\"level.php?delete=$levelid\">$la_del</a> | ";
              print "<a href=\"level.php?levelid=$levelid\">$la_change</a>";


              print "</tr>";
        }

        ?>

</table>

</body>

</html>

<? include "inc_b.php"; ?>