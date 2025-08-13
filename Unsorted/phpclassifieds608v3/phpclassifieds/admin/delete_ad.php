<?
require("admheader.php");
require("../functions.php");

        /*
        $sql_delete = "delete from $ads_tbl where siteid = '$siteid'";
        if ($debug)
        {
                  print("$sql_delete");
        }
        $result = mysql_query ($sql_delete);
        */
        print("$menu<p>");
        //print("$deleted");

        delete_ads("$siteid");

          require("admfooter.php");
?>