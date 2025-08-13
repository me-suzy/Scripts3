<?
include_once("admin/inc.php");
if (!$override)
{
	require("functions.php");
}
$aut = 1;
if ($validation == 1) { $val_string = " AND valid = 1"; }

// ## UPDATE AD COUNTER IN EACH DIR ##
$result_top = mysql_query ("select catid from $cat_tbl where catfatherid = 0 order by catfullname");
while ($row = mysql_fetch_array($result_top))
{
 $catid = $row["catid"];
 $top_catid = $catid;
 $result_ads_top = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid $val_string");
 $num_ads_top = mysql_num_rows($result_ads_top);
 $update_cat_top = mysql_query ("update $cat_tbl set total = $num_ads_top where catid = $catid");
 $ad = $num_ads_top;

 // Sub1
 $sql = "select catid,catname from $cat_tbl where catfatherid = $top_catid";
 $result = mysql_query ($sql);
 //$result1 =  mysql_num_rows($result);

 while ($row_sub = mysql_fetch_array($result))
 {
         $catid_sub1 = $row_sub["catid"];
         $catname_sub1 = $row_sub["catname"];
         $result_ads = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid_sub1 $val_string");
         $num_ads = mysql_num_rows($result_ads);
         $update_cat = mysql_query ("update $cat_tbl set total = $num_ads where catid = $catid_sub1");
         $ad = $num_ads + $ad;
         $res = mysql_query ("select total from $cat_tbl where catid = $top_catid");
         $res_row = mysql_fetch_array($res);
         $ads_in_top = $res_row["total"];
         $tot = $num_ads + $ads_in_top;
         $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $top_catid");

         // SUB2
         $sql2 = "select catid,catname from $cat_tbl where catfatherid = $catid_sub1";
         $result2 = mysql_query ($sql2);

         while ($row_sub2 = mysql_fetch_array($result2))
         {
                 $catid_sub2 = $row_sub2["catid"];
                 $catname_sub2 = $row_sub2["catname"];
                 $result_ads2 = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid_sub2 $val_string");
                 $num_ads = mysql_num_rows($result_ads2);
                 $update_cat = mysql_query ("update $cat_tbl set total = $num_ads where catid = $catid_sub2");
                 $ad = $num_ads + $ad;
                 // Update level above
                 $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub1");
                 $res_row = mysql_fetch_array($res);
                 $ads_in_sub1 = $res_row["total"];
                 $tot = $num_ads + $ads_in_sub1;
                 $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub1");
         // END SUB2

                 // SUB3
                 $sql3 = "select catid,catname from $cat_tbl where catfatherid = $catid_sub2";
                 $result3 = mysql_query ($sql3);
                 while ($row_sub3 = mysql_fetch_array($result3))
                 {
                         $catid_sub3 = $row_sub3["catid"];
                         $catname_sub3 = $row_sub3["catname"];
                         $result_ads3 = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid_sub3 $val_string");
                         $num_ads = mysql_num_rows($result_ads3);
                         $update_cat = mysql_query ("update $cat_tbl set total = $num_ads where catid = $catid_sub3");
                         $ad = $num_ads + $ad;
                         // Update level above
                         $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub2");
                         $res_row = mysql_fetch_array($res);
                         $ads_in_sub2 = $res_row["total"];
                         $tot = $num_ads + $ads_in_sub2;
                         $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub2");
                         // Update level above
                         $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub1");
                         $res_row = mysql_fetch_array($res);
                         $ads_in_sub1 = $res_row["total"];
                         $tot = $num_ads + $ads_in_sub1;
                         $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub1");
                  // END SUB3

                         // SUB4
                         $sql4 = "select catid,catname from $cat_tbl where catfatherid = $catid_sub3";
                         $result4 = mysql_query ($sql4);
                         while ($row_sub4 = mysql_fetch_array($result4))
                         {
                                 $catid_sub4 = $row_sub4["catid"];
                                 $catname_sub4 = $row_sub4["catname"];
                                 $result_ads4 = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid_sub4 $val_string");
                                 $num_ads = mysql_num_rows($result_ads4);
                                 $update_cat = mysql_query ("update $cat_tbl set total = $num_ads where catid = $catid_sub4");
                                 $ad = $num_ads + $ad;
                                 // Update level above
                                 $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub3");
                                 $res_row = mysql_fetch_array($res);
                                 $ads_in_sub3 = $res_row["total"];
                                 $tot = $num_ads + $ads_in_sub3;
                                 $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub3");
                                 // Update level above
                                 $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub2");
                                 $res_row = mysql_fetch_array($res);
                                 $ads_in_sub2 = $res_row["total"];
                                 $tot = $num_ads + $ads_in_sub2;
                                 $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub2");
                                 // Update level above
                                 $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub1");
                                 $res_row = mysql_fetch_array($res);
                                 $ads_in_sub1 = $res_row["total"];
                                 $tot = $num_ads + $ads_in_sub1;
                                 $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub1");
                         // END SUB4

                                 // SUB5
                                 $sql5 = "select catid,catname from $cat_tbl where catfatherid = $catid_sub4";
                                 $result5 = mysql_query ($sql5);
                                 //$result2 =  mysql_num_rows($result2);
                                 while ($row_sub5 = mysql_fetch_array($result5))
                                 {
                                         $catid_sub5 = $row_sub5["catid"];
                                         $catname_sub5 = $row_sub5["catname"];
                                         $result_ads5 = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid_sub5 $val_string");
                                         $num_ads = mysql_num_rows($result_ads5);
                                         $update_cat = mysql_query ("update $cat_tbl set total = $num_ads where catid = $catid_sub5");
                                         $ad = $num_ads + $ad;
                                         // Update level above
                                         $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub4");
                                         $res_row = mysql_fetch_array($res);
                                         $ads_in_sub4 = $res_row["total"];
                                         $tot = $num_ads + $ads_in_sub4;
                                         $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub4");
                                         // Update level above
                                         $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub3");
                                         $res_row = mysql_fetch_array($res);
                                         $ads_in_sub3 = $res_row["total"];
                                         $tot = $num_ads + $ads_in_sub3;
                                         $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub3");
                                         // Update level above
                                         $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub2");
                                         $res_row = mysql_fetch_array($res);
                                         $ads_in_sub2 = $res_row["total"];
                                         $tot = $num_ads + $ads_in_sub2;
                                         $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub2");
                                         // Update level above
                                         $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub1");
                                         $res_row = mysql_fetch_array($res);
                                         $ads_in_sub1 = $res_row["total"];
                                         $tot = $num_ads + $ads_in_sub1;
                                         $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub1");
                                 // END SUB5


                                         // SUB6
                                         //print "<br />";
                                         $sql6 = "select catid,catname from $cat_tbl where catfatherid = $catid_sub5";
                                         $result6 = mysql_query ($sql6);
                                         //$result2 =  mysql_num_rows($result2);
                                         while ($row_sub6 = mysql_fetch_array($result6))
                                         {
                                                 $catid_sub6 = $row_sub6["catid"];
                                                 $catname_sub6 = $row_sub6["catname"];
                                                 $result_ads6 = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid_sub6 $val_string");
                                                 $num_ads = mysql_num_rows($result_ads6);
                                                 $update_cat = mysql_query ("update $cat_tbl set total = $num_ads where catid = $catid_sub6");
                                                 $ad = $num_ads + $ad;
                                                 // Update level above
                                                 $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub5");
                                                 $res_row = mysql_fetch_array($res);
                                                 $ads_in_sub5 = $res_row["total"];
                                                 $tot = $num_ads + $ads_in_sub5;
                                                 $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub5");


                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub4");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub4 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub4;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub4");

                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub3");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub3 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub3;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub3");

                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub2");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub2 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub2;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub2");


                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub1");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub1 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub1;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub1");


                                                 // END SUB6


                                                 // SUB7

                                                 $sql7 = "select catid,catname from $cat_tbl where catfatherid = $catid_sub6";
                                                 $result7 = mysql_query ($sql7);

                                                 while ($row_sub7 = mysql_fetch_array($result7))
                                                 {
                                                         $catid_sub7 = $row_sub7["catid"];
                                                         $catname_sub7 = $row_sub7["catname"];
                                                         $result_ads7 = mysql_query ("select siteid from $ads_tbl where sitecatid = $catid_sub7 $val_string");
                                                         $num_ads = mysql_num_rows($result_ads7);
                                                         $update_cat = mysql_query ("update $cat_tbl set total = $num_ads where catid = $catid_sub7");

                                                        $ad = $num_ads + $ad;

                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub6");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub6 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub6;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub6");


                                                                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub5");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub5 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub5;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub5");


                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub4");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub4 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub4;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub4");

                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub3");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub3 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub3;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub3");

                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub2");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub2 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub2;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub2");


                                                // Update level above
                                                $res = mysql_query ("select total from $cat_tbl where catid = $catid_sub1");
                                                $res_row = mysql_fetch_array($res);
                                                $ads_in_sub1 = $res_row["total"];
                                                $tot = $num_ads + $ads_in_sub1;
                                                $res_update = mysql_query ("update $cat_tbl set total = $tot where catid = $catid_sub1");



                                                 // END SUB7


                                                                                                 } // END SUB7 tag

                                                                           } // END SUB6 tag

                                                   } // END SUB5 tag

                                                 } // END SUB4 tag

                                                } // END SUB3 tag

                                        } // END SUB2 TAG

                        } // END SUB1 TAG

                        $tot = $num_ads_top + $ads_in_sub1 + $ads_in_sub2 + $ads_in_sub3 + $ads_in_sub4 + $ads_in_sub5 + $ads_in_sub6 + $ads_in_sub7;
                        $res_update = mysql_query ("update $cat_tbl set total = $ad where catid = $top_catid");
                        $tot = 0;
                        $ad = 0;
                        $num_ads_top = 0;
                        $ads_in_sub1 = 0;
                        $ads_in_sub2 = 0;
                        $ads_in_sub3 = 0;
                        $ads_in_sub4 = 0;
                        $ads_in_sub5 = 0;
                        $ads_in_sub6 = 0;
                        $ads_in_sub7 = 0;
}

// ## THE END OF UPDATE AD COUNTER IN EACH DIR ##



// ## COUNT ADS FOR EACH USER ##
$sql_users = "select email,registered from $usr_tbl order by registered";
$sql_result = mysql_query($sql_users);
$num_users = mysql_num_rows($sql_result);


for ($i=0; $i<$num_users; $i++)
{
                 $row = mysql_fetch_array($sql_result);
                 $email = $row["email"];
                 $registered = $row["registered"];
                 $sql3 = mysql_query("select siteid from $ads_tbl where ad_username = '$email'");
                 $userads = mysql_num_rows($sql3);
                 $string = "UPDATE $usr_tbl set num_ads = '$userads', "
                 . "registered = '$registered' where email = '$email'";
                 $result123=MYSQL_QUERY($string);

}



// Selct all the ads in the database to see if we have some expires
$sql_links = "select * from $ads_tbl";

$sql_result = mysql_query ($sql_links);
$num_links =  mysql_num_rows($sql_result);


for ($i=0; $i<$num_links; $i++)
{
  $row = mysql_fetch_array($sql_result);
  $siteid = $row["siteid"];
  $sitedate = $row["sitedate"];
  $sitehits = $row["sitehits"];
  $sitevotes = $row["sitevotes"];
  $name = $row["name"];
  $phone = $row["phone"];
  $email = $row["email"];
  $sitevotes = $row["sitevotes"];
  $adressfield1 = $row["adressfield1"];
  $adressfield2 = $row["adressfield2"];
  $catname = $row["catname"];
  $datestamp = $row["datestamp"];
  $expiredate = $row["expiredate"];
  $dy =  date("d");
  $mn =  date("m");
  $yr = date("Y");
  $notify = $row["notify"];
  $expire_days = $row["expire_days"];

  $year=substr($row[datestamp],0,4);
  $month=substr($row[datestamp],4,2);
  $day=substr($row[datestamp],6,2);

	if ($advanced_delete_activated)
   {
		if (!$expire_days_option)
		{
           	$dagens_dato = mktime (date("H"),date("i"),date("s"),$mn,$dy,$yr);
            $dagens_dato_konv = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")) );
            $slettedato = mktime (date("H"),date("i"),date("s"),$month,$day+$delete_after_x_days,$year);
            $slettedato_konv = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),$month,$day+$delete_after_x_days,$year) );
		}
		elseif ($expire_days_option)
		{
			$dagens_dato = mktime (date("H"),date("i"),date("s"),$mn,$dy,$yr);
            $dagens_dato_konv = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")) );
            $slettedato = mktime (date("H"),date("i"),date("s"),$month,$day+$expire_days,$year);
            $slettedato_konv = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),$month,$day+$expire_days,$year) );
		}
            
            
		// Hvis slettedatoen - dagens dato er større enn delete_After_x_days, skal
        // annonsen slettes...
        
        $forskjell_1 = ($slettedato) - ($dagens_dato);
        $forskjell = $forskjell_1 /86400;
        if ($forskjell <= 0)
        {
					
                     $count  = $count + 1;
                     delete_ads("$siteid");

        }
        
        elseif ($forskjell <=7 AND $forskjell > 1 AND !$notify AND $expire_days_option)
        {
					$count  = $count + 1;
					warning("$siteid");

        }
        
        
        
		
	}
   		
}





// Counter

        $sql_links = "select siteid from $ads_tbl";
        $sql_result = mysql_query ($sql_links);
        $ads =  mysql_num_rows($sql_result);

        $sql_links = "select email from $usr_tbl";
        $sql_result = mysql_query ($sql_links);
        $users =  mysql_num_rows($sql_result);

        $sql_sites = "select sum(sitehits) from $ads_tbl";
        $result_sites = mysql_query ($sql_sites);
        $hits =  mysql_num_rows($result_sites);

        while ($row = mysql_fetch_array($result_sites))
        {
                 $detailed= $row["sum(sitehits)"];
        }

        $file_pointer = fopen("$full_path_to_public_program/admin/config/counter.inc.php", "w");
        $string = "<? \r
 \$ads=\"$ads\";\r;
 \$users=\"$users\";\r;
 \$detailed=\"$detailed\";";
        fwrite($file_pointer,$string);
        fclose($file_pointer);

?>
