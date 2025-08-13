<?

function check_valid_user()
// see if somebody is logged in and notify them if not
{
	
  global $valid_user;
  global $la_notlogged;
  global $la_s_bar;
  global $la_s_category;
  global $la_s_num_res;
  global $la_s_num_res2;
  global $catname;
  global $show_bar;
  global $cat_tbl;
  global $la_search;
  
  
  if (session_is_registered("valid_user"))
  {
  }
  else
  {
    // they are not logged in
?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?
    print " $la_notlogged <br />";

?>
    </td>
  </tr>
</table>

<?    
    
    include("admin/config/footer.inc.php");
	exit;
  }
}

function warning ($adid)
{
	global $from_adress_mail;
	global $warn_msg;
	global $warn_ttl;
	global $url;
	global $ads_tbl;
	global $name_of_site;
	require("admin/config/mail.inc.php");
	
	$sql_links = "select siteid,ad_username,sitetitle from $ads_tbl where siteid = $adid";
	$sql_result = mysql_query ($sql_links);

    $row = mysql_fetch_array($sql_result);
    $adid = $row["siteid"];
    $ad_username = $row["ad_username"];
	$sitetitle = $row["sitetitle"];
    
	$warn_ttl = ereg_replace ("\{EMAIL\}", $ad_username, $warn_ttl);
	$warn_msg = ereg_replace ("\{AD_ID\}", $adid, "$warn_msg");
	$warn_msg = ereg_replace ("\{URL\}", $url, "$warn_msg");
	$warn_msg = ereg_replace ("\{EMAIL\}", "$email", "$warn_msg");
	$warn_msg = ereg_replace ("\{ADTITLE\}", "$sitetitle", "$warn_msg");
	$warn_msg = ereg_replace ("\{SITENAME\}", "$name_of_site", "$warn_msg");
	
	$sendto = "$ad_username";
	$subject = "$warn_ttl";
	$message = "$warn_msg";
	
	$headers .= "From: $name_of_site<$from_adress_mail>\n";
	$headers .= "Reply-To: <$from_adress_mail>\n";
	$headers .= "X-Sender: <$from_adress_mail>\n";
	$headers .= "X-Mailer: PHP4\n"; //mailer
	$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
	$headers .= "Return-Path: <$from_adress_mail>\n";

	
	
	mail($sendto, $subject, $message, $headers);
	$sql = "UPDATE $ads_tbl set notify=1 WHERE siteid = $adid";
	$result = mysql_query($sql);
}

function find ($year, $month, $day, $numdays)
{
                global $expire_date;
                global $forskjell;

                $dagens_dato = mktime (date("H"),date("i"),date("s"),$mn,$dy,$yr);
                $dagens_dato_konv = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")) );
                $slettedato = mktime (date("H"),date("i"),date("s"),$month,$day+$numdays,$year);
                $slettedato_konv = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),$month,$day+$numdays,$year) );

                $forskjell_1 = ($slettedato) - ($dagens_dato);
                $forskjell = $forskjell_1 /86400;
                $expire_date = $slettedato_konv;

                //return $forskjell;
                return $expire_date;
}

function ads_latest_sevend_days()
{
                 // Make a mysql timestamp to compare
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                $mysql_timestamp_date = "$y$m$d";

}



function delete_user($email)
{

                        global $userid;
                        global $usr_tbl;
                        global $ads_tbl;
                        global $pic_tbl;
                        global $debug;
                        global $la_users_deleted;
                        global $fileimg_upload;
                        global $full_path_to_public_program;

                        $sql_links = "select * from $ads_tbl where ad_username = '$email'";
                        if ($debug) { print("$sql_links"); }
                        $sql_result = mysql_query ($sql_links);
						$ant = mysql_numrows($sql_result); 
                        

                        while ($row = mysql_fetch_array($sql_result))
                        {
								
								
                                $picture = $row["picture"];
                                $img_stored = $row["img_stored"];
                                $siteid = $row["siteid"];

								$query_pic = "select id,imageh,imagew,filetype,filename from $pic_tbl where pictures_siteid=$siteid";
								$sql_result_pic = mysql_query ($query_pic);
								$num_pic =  mysql_num_rows($sql_result);
								
								for ($i=0; $i<$num_pic; $i++)
								{
								      
									
								    $row = mysql_fetch_array($sql_result_pic);  
								
									$id = $row["id"];
									$filename_d = $row["filename"];
                        
                              
                                	if ($fileimg_upload AND $filename_d)
                                	{
                                		 
                                        $fil = $full_path_to_public_program . "/images/" . $filename_d;
                                		
                                        if (file_exists($fil))
                                        {
                                                $ext=substr($filename_d,-4);
												$file_without_ext=substr($filename_d,0,-4);
												
												
												$photo_name_large =  $full_path_to_public_program . "/images/" . $rand . $file_without_ext . "_large" . $ext;
												$photo_name_small =  $full_path_to_public_program . "/images/" . $rand . $file_without_ext . "_small" . $ext;
												

												
												
												if (file_exists($photo_name_large)) { 
													$delete_result = unlink($photo_name_large); 
												}
												
												if (file_exists($photo_name_small))  { 
													$delete_result = unlink($photo_name_small);
												}	
                                        	
                                        	
                                        		unlink ($fil);
												
                                                $query_pic = "delete from $pic_tbl where id='$id'";
												$sql_result_delpic = mysql_query ($query_pic);
                                                if ($debug) { print("<p>Image file $fil deleted!</p>"); }
                                                
                                        }
                                
                                	}
                                	else
                                	{
										$query_pic2 = "delete from $pic_tbl where pictures_siteid=$siteid";
										$sql_result_pic2 = mysql_query ($query_pic2);
										
                                		
                                		
                                	}
                                	
                                }
                                
                                $r = "delete from $ads_tbl where siteid=$siteid";
                                $sql_r = mysql_query($r);
                                if ($debug) { print("<p>Ads $siteid deleted with sql $r !</p>"); }
                                
                                
                                
                        }
						
                        $r = "delete from $usr_tbl where email='$email'";
						$sql_r = mysql_query($r);       	
						if ($debug) { print("<p>USer $email deleted with sql $r !</p>"); }
                                	
}

function delete_ads($adid)
{

                        global $userid;
                        global $usr_tbl;
                        global $ads_tbl;
                        global $aut;
                        global $pic_tbl;
                        global $debug;
                        global $la_ads_deleted;
                        global $full_path_to_public_program;
						global $la_delete;
						global $valid_user;
						global $fileimg_upload;
						$siteid = $adid;
						

                        $sql_links = "select * from $ads_tbl where siteid = $adid";
                        if ($debug) { print("$sql_links");  }
                        $sql_result = mysql_query ($sql_links);

                        while ($row = mysql_fetch_array($sql_result))
                        {

                         	//$siteid = $row["siteid"];
                         
							$query_pic = "select id,imageh,imagew,filetype,filename from $pic_tbl where pictures_siteid=$siteid";
						 	$sql_result_pic = mysql_query ($query_pic);
						 	$num_pic =  mysql_num_rows($sql_result_pic);
								
							for ($i=0; $i<$num_pic; $i++)
							{
								    //	print "<hr>Antall bilder #1: $num_pic<br />SQL #1: $query_pic  <hr>";
									$row_p = mysql_fetch_array($sql_result_pic);  
								
									$id = $row_p["id"];
									$filename_d = $row_p["filename"];
                        			
									
                              
                                	if ($fileimg_upload AND $filename_d)
                                	{
                                		 
                                        $fil = $full_path_to_public_program . "/images/" . $filename_d;
                                		
                                        if (file_exists($fil))
                                        {
		                                                
												$ext=substr($filename_d,-4);
												$file_without_ext=substr($filename_d,0,-4);
												
												
												$photo_name_large =  $full_path_to_public_program . "/images/" . $rand . $file_without_ext . "_large" . $ext;
												$photo_name_small =  $full_path_to_public_program . "/images/" . $rand . $file_without_ext . "_small" . $ext;
												
																						
												
												if (file_exists($photo_name_large)) { 
													$delete_result = unlink($photo_name_large); 
												}
												
												if (file_exists($photo_name_small))  { 
													$delete_result = unlink($photo_name_small);
												}	
                                        		
                                        	
                                        		unlink ($fil);
												
                                                $query_pic = "delete from $pic_tbl where id='$id'";
												$sql_result_delpic = mysql_query ($query_pic);
                                                if ($debug) { print("<p>Image file $fil deleted!</p>"); }
                                                
                                        }
                                
                                	}
                                	else
                                	{
										$query_pic2 = "delete from $pic_tbl where pictures_siteid=$siteid";
										$sql_result_pic2 = mysql_query ($query_pic2);
										
                                		
                                		
                                	}
                                	
                                }
                                
                                $r = "delete from $ads_tbl where siteid=$siteid";
                                $sql_r = mysql_query($r);
                                if ($debug) { print("<p>Ads $siteid deleted with sql $r !</p>"); }
                        }

}
?>                

<?


function setImageSize($image_file) { 
global $w;
global $h;
global $maxSize;
global $imgSizeArray;

//$maxSize = 100; // set this varible to max width or height 
if (!$maxSize)
{
	$maxSize = 200;
}
$width = $w; 
$height = $h; 

if($width > $maxSize || $height > $maxSize) { 

if($width > $height) { 
$i = $width - $maxSize; 
$imgSizeArray[0] = $maxSize; 
$imgSizeArray[1] = $height - ($height * ($i / $width)); 

} else { 

$i = $height - $maxSize; 
$imgSizeArray[0] = $width - ($width * ($i / $height)); 
$imgSizeArray[1] = $maxSize; 
} 

} else { 

$imgSizeArray[0] = $width; 
$imgSizeArray[1] = $height; 
} 

return $imgSizeArray; 
} 





?> 
