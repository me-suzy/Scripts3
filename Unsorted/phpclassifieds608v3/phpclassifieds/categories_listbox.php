<select size="1" name="sitecatid">

<?



if (!$simple_structure)
{
           print("<option value='0' selected>$choose_cat</option>");
           $sql_select = "select * from $cat_tbl where catid = $category_wanted order by catname";
           $result = mysql_query ($sql_select);

          while ($row = mysql_fetch_array($result))
          {
                                        $catid = $row["catid"];
                                        $catfatherid = $row["catfatherid"];
                                        $catname = $row["catname"];
                                        $catdescription = $row["catdescription"];

                                        // HIGEST SUBLEVEL CATEGORIES
                                        // Sub-category
                                        $sql_underkategori = "select * from $cat_tbl where catfatherid = $category_wanted order by catname";

                                        $resultat = mysql_query ($sql_underkategori);

                                        while ($row = mysql_fetch_array($resultat))
                                        {
                                                               $catid_sub = $row["catid"];
                                                               $catfatherid_sub = $row["catfatherid"];
                                                               $catname_sub = $row["catname"];
                                                               print("<option value='$catid_sub'");

                                                                 if ($catid == $catid_sub)
                                                                 {
                                                                           print("selected");
                                                                 }

                                                                 print(">");
                                                                 print("--> $catname_sub");
                                                                 print("</option>");

                                  }




        }


}
// If we have simple cat strucutre, do this:
else
{
         $sql_select = "select * from $cat_tbl where catfatherid = 0 order by catname";
         $result = mysql_query ($sql_select);

         while ($row = mysql_fetch_array($result))
         {

                $catid_nr = $row["catid"];
                $catfatherid = $row["catfatherid"];
                $catname = $row["catname"];
                $catdescription = $row["catdescription"];

                print("<option value='$catid'");

                print("<option value='$catid_nr'");
                if ($catid_nr == $catid) { print("selected");  }
                print(">");
                print("$catname");
                print("</option>");
        }
}
?>
</select>