<select name="sitecatid">
<?
require ("admin/config/general.inc.php");

$sql1 = "select * from $cat_tbl where catid = $catid  order by catfullname";
$result = mysql_query ($sql1);
$antall = mysql_num_rows($result);

if ($antall == 0)
{
$sql_top = "select * from $cat_tbl where catfatherid = $catid  order by catfullname";
$result = mysql_query ($sql_top);
$r = $result;
}


while ($row1 = mysql_fetch_array($result))
{
                         $catid1 = $row1["catid"];
      $catfullname1 = $row1["catfullname"];
                        $allowads = $row1["allowads"];
                        if ($allowads == 'on')
                        print "<option value='$catid1'>$catfullname1</option>";
                        $sql2 = "select * from $cat_tbl where catfatherid = $catid1  order by catfullname";
                        $result2 = mysql_query ($sql2);
                        while ($row2 = mysql_fetch_array($result2))
                        {
                                                   $catid2 = $row2["catid"];
                                                 $catfullname2 = $row2["catfullname"];
                                                $allowads = $row2["allowads"];
                                                if ($allowads == 'on')
                                                print "<option value='$catid2'>$catfullname2</option>";

                                                $sql3 = "select * from $cat_tbl where catfatherid = $catid2  order by catfullname";
                                                $result3 = mysql_query ($sql3);
                                                while ($row3 = mysql_fetch_array($result3))
                                                {
                                                                          $catid3 = $row3["catid"];
                                                                        $catfullname3 = $row3["catfullname"];
                                                                        $allowads = $row3["allowads"];
                                                                        if ($allowads == 'on')
                                                                        print "<option value='$catid3'>$catfullname3</option>";


                                                                        $sql4 = "select * from $cat_tbl where catfatherid = $catid3  order by catfullname";
                                                                        $result4 = mysql_query ($sql4);
                                                                        while ($row4 = mysql_fetch_array($result4))
                                                                        {
                                                                                          $catid4 = $row4["catid"];
                                                                                        $catfullname4 = $row4["catfullname"];
                                                                                        $allowads = $row4["allowads"];
                                                                                        if ($allowads == 'on')
                                                                                        print "<option value='$catid4'>$catfullname4</option>";


                                                                                        $sql5 = "select * from $cat_tbl where catfatherid = $catid4  order by catfullname";
                                                                                        $result5 = mysql_query ($sql5);
                                                                                        while ($row5 = mysql_fetch_array($result5))
                                                                                        {
                                                                                                                           $catid5 = $row5["catid"];
                                                                                                                        $catfullname5 = $row5["catfullname"];
                                                                                                                        $allowads = $row5["allowads"];
                                                                                                                        if ($allowads == 'on')
                                                                                                                        print "<option value='$catid5'>$catfullname5</option>";

                                                                                                                        $sql6 = "select * from $cat_tbl where catfatherid = $catid5  order by catfullname";
                                                                                                                        $result6 = mysql_query ($sql6);
                                                                                                                        while ($row6 = mysql_fetch_array($result6))
                                                                                                                        {
                                                                                                                                                    $catid6 = $row6["catid"];
                                                                                                                                                $catfullname6 = $row6["catfullname"];
                                                                                                                                                $allowads = $row6["allowads"];
                                                                                                                                                if ($allowads == 'on')
                                                                                                                                                print "<option value='$catid6'>$catfullname6</option>";

                                                                                                                                                $sql7 = "select * from $cat_tbl where catfatherid = $catid6  order by catfullname";
                                                                                                                                                $result7 = mysql_query ($sql7);

                                                                                                                                                while ($row7 = mysql_fetch_array($result7))
                                                                                                                                                {
                                                                                                                                                                             $catid7 = $row7["catid"];
                                                                                                                                                                        $catfullname7= $row7["catfullname"];
                                                                                                                                                                        $allowads = $row7["allowads"];
                                                                                                                                                                        if ($allowads == 'on')
                                                                                                                                                                        print "<option value='$catid7'>$catfullname7</option>";

                                                                                                                                                $sql8 = "select * from $cat_tbl where catfatherid = $catid7  order by catfullname";
                                                                                                                                                $result8 = mysql_query ($sql8);

                                                                                                                                                while ($row8 = mysql_fetch_array($result8))
                                                                                                                                                {
                                                                                                                                                                             $catid8 = $row8["catid"];
                                                                                                                                                                        $catfullname8= $row8["catfullname"];
                                                                                                                                                                        $allowads = $row18["allowads"];
                                                                                                                                                                        if ($allowads == 'on')
                                                                                                                                                                        print "<option value='$catid8'>$catfullname8</option>";


                                                                                                                                                        }                // 8
                                                                                                                                                } // 7


                                                                                                                        } // 6


                                                                                        } // 5

                                                                         } // 4


                                                        } // 3


                        } // 2



}

?>
</select>
