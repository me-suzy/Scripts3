<?

/*

DELETE_HIDDEN
This script should be run from each 1 to 10 days. Best is once a day.

*/


require("config.php");


// Make a mysql timestamp to compare
$y = date("Y");
$m = date("m");
$d = date("d");
$mysql_timestamp_date = "$y$m$d";



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
    
		// Some old stuff..
		$dates= "$sitedate";
		$today = date("d.m.Y");
    $dy =  date("d",time($dates));
    $mn =  date("m",time($dates));
    $yr = date("Y",time($dates));
																

		// From version 5.0 Alpha, this Advanced delete should be used.														
		if ($advanced_delete_activated)
		{
		 	    /* 
					Delete day descied here...If ad in database has been registered
					on DATESTAMP, the ad should be deleted after x days.
					*/
		 	    $date_new = $datestamp - $delete_after_x_days;
																				
					if ($debug)
					{
					 	  //print("$mysql_timestamp_date + 1<br />");																				
							print("$mysql_timestamp_date - $date_new<br />");
					}

					if (($mysql_timestamp_date) < ($date_new))
					{
							 $count  = $count + 1;	
							 $result=MYSQL_QUERY("delete from $ads_tbl where siteid=$siteid");
         	  	 $result2=MYSQL_QUERY("delete from $pic_tbl where pictures_siteid=$siteid");
							 
							 						 
      		 			if ($debug)
		    				{	 
									 print("Deleted: $siteid - Timeregistered: $datestamp - Today: $mysql_timestamp_date");
									 
									 
								}														 
					 }
																		 
			}

			else
			{
			 		// This ensure backwards compability (should not really be used any more)

       		if ($today == $expiredate)
       		{
                	 $result=MYSQL_QUERY("delete from $ads_tbl where siteid=$siteid");
                 	 $result2=MYSQL_QUERY("delete from $pic_tbl where pictures_siteid=$siteid");
      		 }
	  	}




}



// Delete old users that have registered x days ago, and today have no ads.

if ($delete_members_activated)
{	 
	 
	 // First we find the latest registered user
	
	$sql_latest = "select * from $usr_tbl order by registered desc limit 1";

	$sql_latest_result = mysql_query ($sql_latest);
  $latest =  mysql_num_rows($sql_latest_result);


  for ($i=0; $i<$latest; $i++)
  {
   		$row = mysql_fetch_array($sql_latest_result);
			$latest_userdate = $row["registered"];
	}
	
	// Then we find out if we can delete a user...
	
	$sql_siste = "select * from $usr_tbl order by registered";


	$sql_siste_result = mysql_query ($sql_siste);
  $num_links =  mysql_num_rows($sql_siste_result);


  for ($i=0; $i<$num_links; $i++)
  {
   		$row = mysql_fetch_array($sql_siste_result);
			$userid = $row["userid"];
			$name = $row["name"];
			$registered = $row["registered"];
			//substr($sitetitle2, 0, 10);
			//	$aar = substr($registered, 0, 4); 
      //  $maned = substr($registered, 4, 2); 
      //  $dag = substr($registered, 6, 2); 

			//print("$dag - $maned - $aar<br />");
			//print("$name - $registered<br />");
			$date_new = $latest_userdate-90;
			if ($num_ads == 0 AND (($registered) < ($date_new)))
			{
			 	 			print("Deleted : $userid - $registered - ($date_new)<br />");
							$count  = $count + 1;
	
			}
			
	}
}

?>


