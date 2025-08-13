<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
$bad_words_list = split(",", $bad_words);

if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h2>$name_of_site</h2>");

include_once("member_header.php");	



if ($sess == 1 AND session_is_registered("admin"))
{
	session_unregister("valid_user");
	$valid_user = $usr;
	session_register("valid_user");
}
if (!session_is_registered(admin))
{	
	if ($siteid)
	{
	$result = mysql_query ("select ad_username from $ads_tbl where siteid = $siteid");
	$row = mysql_fetch_array($result);
    $ad_username = $row["ad_username"];	
    
    if ($ad_username <> $valid_user)
    {
    	print "$la_mustbeowner";
    	include_once("admin/config/footer.inc.php");
    	exit;	
    }
	}
}

?>
<table border="0" cellspacing="1" width="100%">
<tr>
    <td width="100%" valign="top">
<?
// ## Some default code that can be useful
$dagens_dato = date("d.m.Y");
$dy =  date("d");
$mn =  date("m");
$yr = date("Y");

if (!$expire_days_option)
{
	$expiredate = strftime("%d.%m.%Y", mktime(0,0,0,$mn,$dy+$delete_after_x_days,$yr));
}
else
{
	$expiredate = strftime("%d.%m.%Y", mktime(0,0,0,$mn,$dy+$expire_days,$yr));
}



if ($submit)
{
     $sitetitle = strip_tags ("$sitetitle");
     $sitedescription = strip_tags ("$sitedescription");
     
     $sitetitle = ereg_replace('"', ' ', $sitetitle);
     $sitetitle = ereg_replace("'", " ", $sitetitle);
     
     
     $sitedescription = ereg_replace('"', ' ', $sitedescription);
     $sitedescription = ereg_replace("'", " ", $sitedescription);
     
     $custom_field_1 = strip_tags ("$custom_field_1");
     $custom_field_2 = strip_tags ("$custom_field_2");
     $custom_field_3 = strip_tags ("$custom_field_3");
     $custom_field_4 = strip_tags ("$custom_field_4");
     $custom_field_5 = strip_tags ("$custom_field_5");
     $custom_field_6 = strip_tags ("$custom_field_6");
     $custom_field_7 = strip_tags ("$custom_field_7");
     $custom_field_8 = strip_tags ("$custom_field_8");

     // A field is not filled out, wich one ?
     print("<ol class=\"red\">");
     if (!$sitetitle)
     {
      print("<li>$la_error_msg1</li>");
      $error = 1;
     }
     if (!$sitedescription)
     {
      print("<li>$la_error_msg2</li>");
      $error = 1;
     }
     if ($f1_mandatory == 'on' AND $f1 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f2_mandatory == 'on' AND $f2 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f3_mandatory == 'on' AND $f3 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f4_mandatory == 'on' AND $f4 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f5_mandatory == 'on' AND $f5 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f6_mandatory == 'on' AND $f6 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f7_mandatory == 'on' AND $f7 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f8_mandatory == 'on' AND $f8 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f9_mandatory == 'on' AND $f9 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f10_mandatory == 'on' AND $f10 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f11_mandatory == 'on' AND $f11 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f12_mandatory == 'on' AND $f12 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f13_mandatory == 'on' AND $f13 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f14_mandatory == 'on' AND $f14 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if ($f15_mandatory == 'on' AND $f15 == '')
     {
      print("<li>$err_add</li>");
      $error = 1;
     }
     if (!$sitecatid)
     {
      print("<li>$la_error_msg3</li>");
      $error = 1;
     }
     

    
    
     foreach($bad_words_list as $bad) 
     {
     	if ($bad)
     	{
     		$result1 = ereg($bad,$sitetitle);
     		$result2 = ereg($bad,$sitedescription);
     	}

     	if ($result1 OR $result2)
     	{
     		print("<li>$la_bad_words <b>$bad</b><br />");
     		$error = 1;
     		print "</li>";
     	}    	
     	 
     }	

     print("</ol>");
	
     
     if (!$error)
     {

		// Insert or Update, that is the question ...
        
			if (is_array($f1))
            {
            	$count = count($f1);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f1_inn = $f1_inn . "$f1[$i], ";
        			}
        			else
        			{
        				$f1_inn = $f1_inn . $f1[$i];
        			}
        		}
            } 
            else
            {
            	$f1_inn = $f1;	
            }
            
			if (is_array($f2))
            {
            	$count = count($f2);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f2_inn = $f2_inn . "$f2[$i], ";
        			}
        			else
        			{
        				$f2_inn = $f2_inn . $f2[$i];
        			}
        		}
            } 
            else
            {
            	$f2_inn = $f2;	
            }
		
		
			if (is_array($f3))
            {
            	$count = count($f3);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f3_inn = $f3_inn . "$f3[$i], ";
        			}
        			else
        			{
        				$f3_inn = $f3_inn . $f3[$i];
        			}
        		}
            } 
            else
            {
            	$f3_inn = $f3;	
            }

            
            if (is_array($f4))
            {
            	$count = count($f4);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f4_inn = $f4_inn . "$f4[$i], ";
        			}
        			else
        			{
        				$f4_inn = $f4_inn . $f4[$i];
        			}
        		}
            } 
            else
            {
            	$f4_inn = $f4;	
            }

            
			if (is_array($f5))
            {
            	$count = count($f5);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f5_inn = $f5_inn . "$f5[$i], ";
        			}
        			else
        			{
        				$f5_inn = $f5_inn . $f5[$i];
        			}
        		}
            } 
            else
            {
            	$f5_inn = $f5;	
            }

            
  			if (is_array($f6))
            {
            	$count = count($f6);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f6_inn = $f6_inn . "$f6[$i], ";
        			}
        			else
        			{
        				$f6_inn = $f6_inn . $f6[$i];
        			}
        		}
            } 
            else
            {
            	$f6_inn = $f6;	
            }

            
			if (is_array($f7))
            {
            	$count = count($f7);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f7_inn = $f7_inn . "$f7[$i], ";
        			}
        			else
        			{
        				$f7_inn = $f7_inn . $f7[$i];
        			}
        		}
            } 
            else
            {
            	$f7_inn = $f7;	
            }

            
			if (is_array($f8))
            {
            	$count = count($f8);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f8_inn = $f8_inn . "$f8[$i], ";
        			}
        			else
        			{
        				$f8_inn = $f8_inn . $f8[$i];
        			}
        		}
            } 
            else
            {
            	$f8_inn = $f8;	
            }

            
			if (is_array($f9))
            {
            	$count = count($f9);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f9_inn = $f9_inn . "$f9[$i], ";
        			}
        			else
        			{
        				$f9_inn = $f9_inn . $f9[$i];
        			}
        		}
            } 
            else
            {
            	$f9_inn = $f9;	
            }

            
			if (is_array($f10))
            {
            	$count = count($f1);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f10_inn = $f10_inn . "$f10[$i], ";
        			}
        			else
        			{
        				$f10_inn = $f10_inn . $f10[$i];
        			}
        		}
            } 
            else
            {
            	$f10_inn = $f10;	
            }

            
			if (is_array($f11))
            {
            	$count = count($f11);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f11_inn = $f11_inn . "$f11[$i], ";
        			}
        			else
        			{
        				$f11_inn = $f11_inn . $f11[$i];
        			}
        		}
            } 
            else
            {
            	$f11_inn = $f11;	
            }

            
			if (is_array($f12))
            {
            	$count = count($f12);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f12_inn = $f12_inn . "$f12[$i], ";
        			}
        			else
        			{
        				$f12_inn = $f12_inn . $f12[$i];
        			}
        		}
            } 
            else
            {
            	$f12_inn = $f12;	
            }

            
			if (is_array($f13))
            {
            	$count = count($f1);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f13_inn = $f13_inn . "$f13[$i], ";
        			}
        			else
        			{
        				$f13_inn = $f13_inn . $f13[$i];
        			}
        		}
            } 
            else
            {
            	$f13_inn = $f13;	
            }

            
			if (is_array($f14))
            {
            	$count = count($f14);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f14_inn = $f14_inn . "$f14[$i], ";
        			}
        			else
        			{
        				$f14_inn = $f14_inn . $f14[$i];
        			}
        		}
            } 
            else
            {
            	$f14_inn = $f14;	
            }

            
			if (is_array($f15))
            {
            	$count = count($f15);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$f15_inn = $f15_inn . "$f15[$i], ";
        			}
        			else
        			{
        				$f15_inn = $f15_inn . $f15[$i];
        			}
        		}
            } 
            else
            {
            	$f15_inn = $f15;	
            }

            
		if ($siteid)
        {
        	
        	if (!$no_webmastermail)
        	{
        		$admin_new_ad = ereg_replace("\{id\}", "$siteid", $admin_new_ad);
				$admin_new_ad = ereg_replace("\{url\}", "$url", $admin_new_ad);
        		$mode = "update";
            	$adid = $siteid;
            	$sendto = "$from_adress_mail";
            	$from = "$from_adress_mail";
            	$subject = "$admin_new_ad_subject";
            	$message = "$admin_new_ad";
				$headers = "From: $from\r\n";
				// send e-mail
            	mail($sendto, $subject, $message, $headers);
        	}	
            
			
             
			$sql_update = "update $ads_tbl set sitetitle='$sitetitle',sitedate='$dagens_dato', expiredate='$expiredate', sitedescription='$sitedescription',siteurl='$siteur',sitecatid=$sitecatid, custom_field_1='$custom_field_1', custom_field_2='$custom_field_2', custom_field_3='$custom_field_3', custom_field_4='$custom_field_4', custom_field_5='$custom_field_5', custom_field_6='$custom_field_6', custom_field_7='$custom_field_7', custom_field_8='$custom_field_8', f1='$f1_inn',f2='$f2_inn',f3='$f3_inn',f4='$f4_inn',f5='$f5_inn',f6='$f6_inn',f7='$f7_inn',f8='$f8_inn',f9='$f9_inn',f10='$f10_inn',f11='$f11_inn',f12='$f12_inn',f13='$f13_inn',f14='$f14_inn',f15='$f15_inn', expire_days='$expire_days', valid='0', notify='0' where siteid = '$siteid' AND ad_username='$valid_user'";
			$result = mysql_query ($sql_update);
			
			
			
		}
        else
        {
        	if ($auto)
            {
            	$override = 1;
            	include("update.php");
			}
            
			if ($credits_option AND !session_is_registered(admin))
			{
				$result = mysql_query ("update $usr_tbl set credits = credits - 1 where email = '$valid_user'");
			}
			$mode = "insert";
            $sql_insert = "insert into $ads_tbl (
							ad_username,
                            sitetitle,
							sitedescription,
							siteurl,
                 			sitedate,
			                expiredate,
			                sitecatid,
			                sites_userid,
			                sites_pass,
			                custom_field_1,
			                custom_field_2,
			                custom_field_3,
			                custom_field_4,
			                custom_field_5,
			                custom_field_6,
			                custom_field_7,
			                custom_field_8,
			                f1,
	                        f2,
	                        f3,
	                        f4,
	                        f5,
	                        f6,
	                        f7,
	                        f8,
	                        f9,
	                        f10,
	                        f11,
	                        f12,
	                        f13,
	                        f14,
	                        f15,
            				expire_days
						)
                 		values
						(
							'$valid_user',
			                '$sitetitle',
			                '$sitedescription',
			                '$siteurl',
			                '$dagens_dato',
			                '$expiredate',
			                '$sitecatid',
			                '$userid',
			                '$pass',
			                '$custom_field_1',
			                '$custom_field_2',
			                '$custom_field_3',
			                '$custom_field_4',
			                '$custom_field_5',
			                '$custom_field_6',
			                '$custom_field_7',
			                '$custom_field_8',
                            '$f1_inn',
                            '$f2_inn',
                            '$f3_inn',
                            '$f4_inn',
                            '$f5_inn',
                            '$f6_inn',
                            '$f7_inn',
                            '$f8_inn',
                            '$f9_inn',
                            '$f10_inn',
                            '$f11_inn',
                            '$f12_inn',
                            '$f13_inn',
                            '$f14_inn',
                            '$f15_inn',
            				'$expire_days')";

			$result = mysql_query ($sql_insert);
			$adid = mysql_insert_id();
			}// Insert or Update resolved

			if ($picture_upload_enable)
        	{
				if (!$adid)
                {
                	$adid = $siteid;
                }
                
                if ($fileimg_upload == 1)
				{
                	$url_forward = "upload_file.php?pictures_siteid=$adid";
                }
				else
            	{
                    $url_forward = "upload_new.php?pictures_siteid=$adid";
            	}

                 print "$la_pic_upl<br />";
                 print "<br /><b />$la_choose</b /><br /><a href='$url_forward'><img src=\"images/pointer.gif\" border=\"0\" />$la_i_want</a><br />";
                 print "<a href='index.php'><img src=\"images/pointer.gif\" border=\"0\" />$la_ret</a><br />";
                 print "<a href='member.php'><img src=\"images/pointer.gif\" border=\"0\" />$la_add_an</a><br />";
        	} // End picture upload

		
        require("admin/config/general.inc.php");
        
		if (!$no_webmastermail)
		{
        
        $admin_new_ad = ereg_replace("\{id\}", "$adid", $admin_new_ad);
		$admin_new_ad = ereg_replace("\{url\}", "$url", $admin_new_ad);

        $sendto = "$from_adress_mail";
        $from = "$from_adress_mail";
        $subject = "$admin_new_ad_subject";
        $message = "$admin_new_ad";
        $headers = "From: $from\r\n";
        // send e-mail
        mail($sendto, $subject, $message, $headers);
		}
} // End of Valid data, but still submitted info
 // End of submit information
}
if (!$submit OR ($submit AND $error))
{


 if ($siteid)
 {
                 $result = mysql_query ("select * from $ads_tbl,$cat_tbl where siteid = $siteid AND sitecatid=catid");
                 $row = mysql_fetch_array($result);
                 $siteid = $row["siteid"];
                 $sitetitle = $row["sitetitle"];
                 $sitedescription = $row["sitedescription"];
                 $siteurl = $row["siteurl"];
                 $sitedate = $row["sitedate"];
                 $sitecatid = $row["sitecatid"];
                 $catid = $sitecatid;
                 $sitehits = $row["sitehits"];
                 $sitevotes = $row["sitevotes"];
                 $custom_field_1 = $row["custom_field_1"];
                 $custom_field_2 = $row["custom_field_2"];
                 $custom_field_3 = $row["custom_field_3"];
                 $custom_field_4 = $row["custom_field_4"];
                 $custom_field_5 = $row["custom_field_5"];
                 $custom_field_6 = $row["custom_field_6"];
                 $custom_field_7 = $row["custom_field_7"];
                 $custom_field_8 = $row["custom_field_8"];
                 $cattpl = $row["cattpl"];
                 $expire_days = $row["expire_days"];


                 while ($counter < 15)
                 {
                         $counter = $counter + 1;
                         $fieldnumber = "f" . $counter;
                         $fieldid[$counter] = $row["$fieldnumber"];
                 }

 }

?>
<b><? echo $la_edit ?></b>




      <script LANGUAGE="JavaScript">
        function openWin2(URL) { aWindow=window.open(URL,"Large","toolbar=no,width=400,height=350,status=no,scrollbars=no,resize=no,menubars=no");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      }
        </script>

<?
print("<a href=\"javascript:openWin2('suggestcategory.php?id=$siteid&adtitle=$sitetitle')\">$suggestcategory</a>");
?>


<?

if ($credits_option AND !session_is_registered("admin"))
{
	$result = mysql_query ("select credits from $usr_tbl where email = '$valid_user'");
	$row_c = mysql_fetch_array($result);
	$credits = $row_c["credits"];

	if ($credits < 1)
	{
		print "<hr />$la_credits_warning<hr />";
	}
	else 
	{
		print "<br />$la_credits_remaining $credits";
		print "<form method='post' action='add_ad.php'>";
	} 
}
else 
{
	print "<form method='post' action='add_ad.php'>";	
}

?>

<?
$sql_top = "select catname from $cat_tbl where catid = $catid";
$result_1 = mysql_query ($sql_top);
$row_1 = mysql_fetch_array($result_1);
$catname = $row_1["catname"];
print "<h2>$catname</h2>";
?>   



<input type="hidden" name="siteid" value="<? echo $siteid ?>" />
<input type="hidden" name="catid" value="<? echo $catid ?>" />
<input type="hidden" name="sitecatid" value="<? echo $catid ?>" />
<table border="0" cellpadding="1" cellspacing="1" width="100%">


<tr>
    <td width="50%" valign="top"><? echo $title ?></td>
    <td width="50%" valign="top">
<input type="text" name="sitetitle" size="39" maxlength="29" class="txt" value="<?php echo $sitetitle ?>" />




    </td>
  </tr>




    <tr>
    <td width="50%" valign="top"><? echo $description ?></td>
    <td width="50%" valign="top"><textarea rows="7" name="sitedescription" cols="45"><?php echo $sitedescription ?></textarea>


      

    </td>
  </tr>


<?

if ($expire_days_option)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$la_rundays</td>");
  print("<td width=\"50%\" valign=\"top\"><select name='expire_days'>");
  while ($counter_days < 90)
  {
  		$counter_days++;
  		print "<option";
  		if ($counter_days == $expire_days)
  		{
  			print " selected='selected'";
  		}
  		print ">$counter_days</option>";
  }
  print("</select>");
  print("</td>");
  print("</tr>");
}
?>



<?
if ($custom_field_1_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_1_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_1\" size=\"29\" class='txt' value=\"$custom_field_1\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_2_text)
{

  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_2_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_2\" size=\"29\" class='txt' value=\"$custom_field_2\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_3_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_3_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_3\" size=\"29\" class='txt' value=\"$custom_field_3\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_4_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_4_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_4\" size=\"29\" class='txt' value=\"$custom_field_4\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_5_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_5_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_5\" size=\"29\" class='txt' value=\"$custom_field_5\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_6_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_6_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_6\" size=\"29\" class='txt' value=\"$custom_field_6\" />");

  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_7_text)
{

  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_7_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_7\" size=\"29\" class='txt' value=\"$custom_field_7\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_8_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_8_text</td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_8\" size=\"29\" class='txt' value=\"$custom_field_8\" />");
  print("</td>");
  print("</tr>");
}

$result_1 = mysql_query ("select cattpl from $cat_tbl where catid = '$catid'");
$row_cat = mysql_fetch_array($result_1);
$cattpl_cat = $row_cat["cattpl"];

$string = "select * from template where name = '$cattpl_cat'";
$result = mysql_query ($string);
$row = mysql_fetch_array($result);


while ($field < 15)
{
print "<tr><td></td><td>";
 $field = $field + 1;
 $fieldname = "f" . $field;
 $tmpfield1 = "f" . $field . "_caption";
 $tmpfield2 = "f" . $field . "_type";
 $tmpfield3 = "f" . $field . "_mandatory";
 $tmpfield4 = "f" . $field . "_length";
 $tmpfield5 = "f" . $field . "_filename";

 $caption = $row["$tmpfield1"];
 $type = $row["$tmpfield2"];
 $mandatory = $row["$tmpfield3"];
 $length = $row["$tmpfield4"];
 $filen = $row["$tmpfield5"];
 print "<input type='hidden' name='$tmpfield3' value='$mandatory' />";
 print "</td></tr>";
 
 if ($caption <> '')
 {
 print("<tr>");
 print("<td width=\"50%\" valign=\"top\">$caption</td>");
 print("<td width=\"50%\" valign=\"top\">");
 if ($type == 'Option')
 {
        $options = file("options/$filen");
        $num_options =  count($options);
        for ($i=0; $i<$num_options; $i++)
        {

                print "<input type='radio' value='$options[$i]' name='$fieldname'";
                if (trim($fieldid[$field]) == trim($options[$i])) { print " checked";  }
                print ">$options[$i]";
                print "&nbsp;&nbsp;&nbsp;";
        }
 }
 if ($type == 'Text')
 {
         print("<input type=\"text\" name=\"$fieldname\" size=\"$length\" maxlength=\"$length\" class='txt' value=\"$fieldid[$field]\" />");
 }
 if ($type == 'URL')
 {
         print("<input type=\"text\" name=\"$fieldname\" size=\"$length\" maxlength=\"$length\" class='txt' value=\"$fieldid[$field]\" />");
 }

 if ($type == 'Textarea')
 {
         $cols = $length - 1;
         print("<textarea rows=\"4\" cols=\"$cols\" name=\"$fieldname\" maxlength=\"$length\" class='txt'\">$fieldid[$field]</textarea>");
 }


 if ($type == 'Checkbox')
 {
        $options = file("options/$filen");
        $num_options =  count($options);
        for ($i=0; $i<$num_options; $i++)
        {
				$c++;
                print "<input type='checkbox' value='$options[$i]' name='$fieldname" . "[]'";
                //print "<input type='checkbox' value='$options[$i]' name='$fieldname'";
                if (preg_match("/$options[$i]/", "$fieldid[$field]"))
                { print " checked";  } 
                print ">$options[$i]";
                //if (trim($fieldid[$field]) == trim($options[$i])) { print " checked";  } print ">$options[$i]";
                print "&nbsp;&nbsp;&nbsp;";
                //print "<hr>if (trim($fieldid[$field]) == trim($options[$i])<hr>";
        }
 }



 if ($type == 'Dropdown')
 {
  		$options = file("options/$filen");
        $num_options =  count($options);

        print "<select size='1' name='$fieldname'>";
        print "<option selected>$options[$i]";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($fieldid[$field] == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "</select>&nbsp;&nbsp;&nbsp;";

 }




 print("</td>");
 print("</tr>");
 $caption1 = '';
 }
}


?>
<tr><td></td>
</tr>

<tr>
<td>
</td>
<td>
<?
print("<input type='submit' name='submit' class='txt' value='$submit_button' />");

?>
</td>
</tr>
</table>
<?
print("</form>");

}
?>
</td></tr></table>
<?
include_once("member_footer.php");
include_once("admin/config/footer.inc.php"); 
?>
