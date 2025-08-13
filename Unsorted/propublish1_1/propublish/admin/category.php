<? 
$req_level = 1;
include "inc_t.php";

$catname_f = htmlspecialchars($catname_f);
$delete = htmlspecialchars($delete);
$cid = htmlspecialchars($cid);

?>

<h3><? echo $la_cat; ?></h3>


<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5"  class='articlebody'>
  <tr>
    <td valign="top">
    <?
     if ($submit)
     {
          if (!$catname_f)
          {
           $error = 1;
           print "<font color=red>$la_mand_error1</font><br>";
          }


          if ($error <> 1)
          {
 
              if (!$id)
              {
               $sql = "INSERT INTO cat_news (catname) values ('$catname_f')";
              }
              else
              {
               $sql = "UPDATE cat_news SET catname = '$catname_f' WHERE id = '$cid'";
              }

              $result = mysql_query($sql);




               if ($result)
               {
                print "<b>$la_cat_saved</b><br>";
               }
          }
     }

     if ($cid)
     {
        $sql = "SELECT * FROM cat_news WHERE id = '$cid'";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $catname  = $myrow["catname"];
        }

     print "<b>$la_editing $catname</b><br>";
     }

     if ($delete)
     {
        $sql = "DELETE FROM cat_news WHERE id = $delete";
        $result = mysql_query($sql);
        if ($result)
        {
         print "<b>$la_deleted_cat</b><br>";
        }

     }


    ?>
    </td>
  </tr>
</table>

<br>
<form method="post" action="category.php">
<input type="hidden" name="cid" value="<? echo $cid; ?>">
<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5"  class='articlebody'>
  <tr>
    <td valign="top" bgcolor="#C0C0C0" colspan="2"><b><? echo $la_newedit; ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><? echo $la_catname ?></font></td>
    <td align="right" valign="top"><input type="text" name="catname_f" size="25" value="<? echo $catname; ?>"></td>
  </tr>

  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right" valign="top"><input type="submit" value="<? echo $la_save ?>" name="submit"></td>
  </tr>
</table>
</form>

<table border="1" cellpadding="2" width="400" bgcolor="#E5E5E5" bordercolorlight="#FFFFFF" bordercolordark="#E5E5E5" class='articlebody'>
  <tr>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_cat ?></b></td>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_change ?></b></td>
  </tr>

        <?
        $sql = "SELECT * FROM cat_news";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $cid = $myrow["id"];
              $catname  = $myrow["catname"];


              print "<tr>";
              print "<td valign=\"top\"><a href=\"category.php?cid=$cid\">$catname</a></font></td>";
              print "<td valign=\"top\"><a href=\"category.php?delete=$cid\">$la_del</a> | ";
              print "<a href=\"category.php?cid=$cid\">$la_change</a>";
              print "</tr>";
        }

        ?>

</table>

<? include("inc_b.php"); ?>
