<!-- LINK_TITLE.PHP -->
<?
if ($kid)

{



        print ("<table border='0' width='100%' cellspacing='0' cellpadding='10'><tr><td width='100%'><a href='index.php'><img src='images/pointer.gif' border='0' alt='$home' />$home</a>");
        $sql_1 = "select * from $cat_tbl where catid = $kid";
        $resultat1 = mysql_query ($sql_1);
        $row = mysql_fetch_array($resultat1);
        $catid_1 = $row["catid"];
        $catfatherid_1 = $row["catfatherid"];
        $catname_1 = $row["catname"];
        $t1 = $catname_1;
        $catid1 = $catid_1;

        if ($catfatherid_1 <> 0)
        {
             $sql_underkategori = "select * from $cat_tbl where catid = $catfatherid_1";
             $resultat = mysql_query ($sql_underkategori);
             $row = mysql_fetch_array($resultat);
             $catid_2 = $row["catid"];
             $catfatherid_2 = $row["catfatherid"];
             $catname_2 = $row["catname"];
             $t2 = $catname_2;
             $catid2 = $catid_2;
        }

        if ($catfatherid_2 <> 0)
        {
             $sql_underkategori = "select * from $cat_tbl where catid = $catfatherid_2";
             $resultat = mysql_query ($sql_underkategori);
             $row = mysql_fetch_array($resultat);
             $catid_3 = $row["catid"];
             $catfatherid_3 = $row["catfatherid"];
             $catname_3 = $row["catname"];
             $t3 = $catname_3;
             $catid3 = $catid_3;
        }

        if ($catfatherid_3 <> 0)
        {
             $sql_underkategori = "select * from $cat_tbl where catid = $catfatherid_3";
             $resultat = mysql_query ($sql_underkategori);
             $row = mysql_fetch_array($resultat);
             $catid_4 = $row["catid"];
             $catfatherid_4 = $row["catfatherid"];
             $catname_4 = $row["catname"];
             $t4 = $catname_4;
             $catid4 = $catid_4;
        }

        if ($catfatherid_4 <> 0)
        {
             $sql_underkategori = "select * from $cat_tbl where catid = $catfatherid_4";
             $resultat = mysql_query ($sql_underkategori);
             $row = mysql_fetch_array($resultat);
             $catid_5 = $row["catid"];
             $catfatherid_5 = $row["catfatherid"];
             $catname_5 = $row["catname"];
             $t5 = $catname_5;
             $catid5 = $catid_5;
        }

        if ($catfatherid_5 <> 0)
        {
             $sql_underkategori = "select * from $cat_tbl where catid = $catfatherid_5";
             $resultat = mysql_query ($sql_underkategori);
             $row = mysql_fetch_array($resultat);
             $catid_6 = $row["catid"];
             $catfatherid_6 = $row["catfatherid"];
             $catname_6 = $row["catname"];
             $t6 = $catname_6;
             $catid6 = $catid_6;
        }

        if ($catfatherid_6 <> 0)
        {
             $sql_underkategori = "select * from $cat_tbl where catid = $catfatherid_6";
             $resultat = mysql_query ($sql_underkategori);
             $row = mysql_fetch_array($resultat);
             $catid_7 = $row["catid"];
             $catfatherid_7 = $row["catfatherid"];
             $catname_7 = $row["catname"];
             $t7 = $catname_7;
             $catid7 = $catid_7;
        }

if ($t7)
print " / <a href='index.php?kid=$catid7&amp;catname=" . urlencode($t7) . "'>$t7</a>";
if ($t6)
print " / <a href='index.php?kid=$catid6&amp;catname=" . urlencode($t6) . "'>$t6</a>";
if ($t5)
print " / <a href='index.php?kid=$catid5&amp;catname=" . urlencode($t5) . "'>$t5</a>";
if ($t4)
print " / <a href='index.php?kid=$catid4&amp;catname=" . urlencode($t4) . "'>$t4</a>";
if ($t3)
print " / <a href='index.php?kid=$catid3&amp;catname=" . urlencode($t3) . "'>$t3</a>";
if ($t2)
print " / <a href='index.php?kid=$catid2&amp;catname=" . urlencode($t2) . "'>$t2</a>";
if ($t1)
print " / <a href='index.php?kid=$catid1&amp;catname=" . urlencode($t1) . "'>$t1</a>";
print "</td></tr></table>";
}

?>
<!-- LINK_TITLE.PHP END -->
