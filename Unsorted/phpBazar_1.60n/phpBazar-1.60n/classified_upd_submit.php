<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_upd_submit.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Submit Ad-Data
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



if (function_exists("ini_get")) {

    $upl_tmp_dir=ini_get('upload_tmp_dir');

} else {

    $upl_tmp_dir=get_cfg_var('upload_tmp_dir');

}



if ($fix_tmp_dir) { // only for fixing on some servers, normally NOT used - set in config.php

    $tmp_dir = $fix_tmp_dir;

} elseif ($upl_tmp_dir) {

    $tmp_dir = $upl_tmp_dir;

} else {

    $tmp_dir = dirname(tempnam('', ''));

}



if ($logging_enable && $floodprotect && $floodprotect_ad && $login_check && !$login_check[2] && !$editad) { // check floodprotect

    $checktimestamp = $timestamp-(3600*$floodprotect);

    $result = mysql_db_query($database, "SELECT timestamp FROM logging WHERE event='AD: new' AND username='$login_check[0]' AND timestamp>'$checktimestamp'") or died("Database Query Error".mysql_error());

    $count=mysql_num_rows($result);

    if ($floodprotect_ad<=$count) {

	died ("Floodprotect active !!! $count events logged last $floodprotect hour(s)");

    }

}



#  Picture Handling

#################################################################################################



if (($pic1!="none" || $pic2!="none" || $pic3!="none") && $convertpath && $pic_enable) {



  if ($_FILES["pic1"]["error"]=="1") { died ($_FILES["pic1"]["name"]." is larger than PHP upload_max_filesize"); }

  if ($_FILES["pic1"]["error"]=="2") { died ($_FILES["pic1"]["name"]." is too large!<br>(max. $pic_maxsize Byte)"); }



  if ($_FILES["pic2"]["error"]=="1") { died ($_FILES["pic2"]["name"]." is larger than PHP upload_max_filesize"); }

  if ($_FILES["pic2"]["error"]=="2") { died ($_FILES["pic2"]["name"]." is too large!<br>(max. $pic_maxsize Byte)"); }



  if ($_FILES["pic3"]["error"]=="1") { died ($_FILES["pic3"]["name"]." is larger than PHP upload_max_filesize"); }

  if ($_FILES["pic3"]["error"]=="2") { died ($_FILES["pic3"]["name"]." is too large!<br>(max. $pic_maxsize Byte)"); }



  mysql_connect($server, $db_user, $db_pass) or died("Database NOT connected");



  if ($pic1!="none" && $pic1) {

    $picinfo=GetImageSize("$pic1");

    if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {

    	switch ($picinfo[2]) {

    	    case 1 : $ext=".gif"; $type="image/gif"; break;

    	    case 2 : $ext=".jpg"; $type="image/jpeg"; break;

	    case 3 : $ext=".png"; $type="image/png"; break;

	}

    } else {

	died ("Wrong Filetype! Only .gif, .jpg, .png Files supported");

    }



    $copyresult=copy("$pic1", "$tmp_dir/tmp_picture$ext");

    if ($copyresult) {

	$picturename = $timestamp.$ext;

	    if (strtoupper($convertpath) == "AUTO") {	// simple file handling without convert

		$in[picture] = $picturename;

		if (!$pic_database) {

		    copy("$tmp_dir/tmp_picture$ext","$bazar_dir/$pic_path/$picturename");

		    if (!is_file("$bazar_dir/$pic_path/$picturename")) {

			died ("$bazar_dir/$pic_path is not a dir or not writeable!");

		    }

		} else {

                    $picture_size = filesize("$tmp_dir/tmp_picture$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());

		}

		suppr("$tmp_dir/tmp_picture$ext");



	    } else {					// advanced file handling with convert

		if (!is_file($convertpath)) {

		    died ("Convertpath is wrong! Check configuration");

		}

		$_picturename = "_".$picturename;

		$in[picture] = $picturename;

		$in[_picture] = $_picturename;

		$convertstr=" -scale $pic_res -quality $pic_quality $tmp_dir/tmp_picture$ext $tmp_dir/tmp_picture1$ext";

		$_convertstr=" -scale $pic_lowres -quality $pic_quality $tmp_dir/tmp_picture$ext $tmp_dir/tmp_picture2$ext ";

		exec($convertpath.$convertstr);

		exec($convertpath.$_convertstr);

		if (!$pic_database) {

    		    copy("$tmp_dir/tmp_picture1$ext","$bazar_dir/$pic_path/$picturename");

		    copy("$tmp_dir/tmp_picture2$ext","$bazar_dir/$pic_path/$_picturename");

		    if (!is_file("$bazar_dir/$pic_path/$picturename")) {

			died ("$bazar_dir/$pic_path is not a dir or not writeable!");

		    }

		} else {

                    $picture_size = filesize("$tmp_dir/tmp_picture1$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture1$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture1$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());



                    $picture_size = filesize("$tmp_dir/tmp_picture2$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture2$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture2$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$_picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());

		}

		suppr("$tmp_dir/tmp_picture$ext");

		suppr("$tmp_dir/tmp_picture1$ext");

		suppr("$tmp_dir/tmp_picture2$ext");

	    }

    } else {

	died ("Could NOT copy the file! Check permissions");

    }

  }





  if ($pic2!="none" && $pic2) {

    $picinfo=GetImageSize("$pic2");

    if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {

    	switch ($picinfo[2]) {

    	    case 1 : $ext=".gif"; $type="image/gif"; break;

    	    case 2 : $ext=".jpg"; $type="image/jpeg"; break;

	    case 3 : $ext=".png"; $type="image/png"; break;

	}

    } else {

	died ("Wrong Filetype! Only .gif, .jpg, .png Files supported");

    }



    $copyresult=copy("$pic2", "$tmp_dir/tmp_picture$ext");

    if ($copyresult) {

	$picturename = ($timestamp+1).$ext;

	    if (strtoupper($convertpath) == "AUTO") {	// simple file handling without convert

		$in[picture2] = $picturename;

		if (!$pic_database) {

		    copy("$tmp_dir/tmp_picture$ext","$bazar_dir/$pic_path/$picturename");

		    if (!is_file("$bazar_dir/$pic_path/$picturename")) {

			died ("$bazar_dir/$pic_path is not a dir or not writeable!");

		    }

		} else {

                    $picture_size = filesize("$tmp_dir/tmp_picture$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());

		}

		suppr("$tmp_dir/tmp_picture$ext");



	    } else {					// advanced file handling with convert

		if (!is_file($convertpath)) {

		    died ("Convertpath is wrong! Check configuration");

		}

		$_picturename = "_".$picturename;

		$in[picture2] = $picturename;

		$in[_picture2] = $_picturename;

		$convertstr=" -scale $pic_res -quality $pic_quality $tmp_dir/tmp_picture$ext $tmp_dir/tmp_picture1$ext";

		$_convertstr=" -scale $pic_lowres -quality $pic_quality $tmp_dir/tmp_picture$ext $tmp_dir/tmp_picture2$ext ";

		exec($convertpath.$convertstr);

		exec($convertpath.$_convertstr);

		if (!$pic_database) {

		    copy("$tmp_dir/tmp_picture1$ext","$bazar_dir/$pic_path/$picturename");

		    copy("$tmp_dir/tmp_picture2$ext","$bazar_dir/$pic_path/$_picturename");

		    if (!is_file("$bazar_dir/$pic_path/$picturename")) {

			died ("$bazar_dir/$pic_path is not a dir or not writeable!");

		    }

		} else {

                    $picture_size = filesize("$tmp_dir/tmp_picture1$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture1$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture1$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());



                    $picture_size = filesize("$tmp_dir/tmp_picture2$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture2$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture2$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$_picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());

		}

		suppr("$tmp_dir/tmp_picture$ext");

		suppr("$tmp_dir/tmp_picture1$ext");

		suppr("$tmp_dir/tmp_picture2$ext");

	    }

    } else {

	died ("Could NOT copy the file!");

    }

  }





  if ($pic3!="none" && $pic3) {

    $picinfo=GetImageSize("$pic3");

    if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {

    	switch ($picinfo[2]) {

    	    case 1 : $ext=".gif"; $type="image/gif"; break;

    	    case 2 : $ext=".jpg"; $type="image/jpeg"; break;

	    case 3 : $ext=".png"; $type="image/png"; break;

	}

    } else {

	died ("Wrong Filetype! Only .gif, .jpg, .png Files supported");

    }

    $copyresult=copy("$pic3", "$tmp_dir/tmp_picture$ext");

    if ($copyresult) {

	$picturename = ($timestamp+2).$ext;

	    if (strtoupper($convertpath) == "AUTO") {	// simple file handling without convert

		$in[picture3] = $picturename;

		if (!$pic_database) {

		    copy("$tmp_dir/tmp_picture$ext","$bazar_dir/$pic_path/$picturename");

		    if (!is_file("$bazar_dir/$pic_path/$picturename")) {

			died ("$bazar_dir/$pic_path is not a dir or not writeable!");

		    }

		} else {

                    $picture_size = filesize("$tmp_dir/tmp_picture$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());

		}

		suppr("$tmp_dir/tmp_picture$ext");



	    } else {					// advanced file handling with convert

		if (!is_file($convertpath)) {

		    died ("Convertpath is wrong! Check configuration");

		}

		$_picturename = "_".$picturename;

		$in[picture3] = $picturename;

		$in[_picture3] = $_picturename;

		$convertstr=" -scale $pic_res -quality $pic_quality $tmp_dir/tmp_picture$ext $tmp_dir/tmp_picture1$ext";

		$_convertstr=" -scale $pic_lowres -quality $pic_quality $tmp_dir/tmp_picture$ext $tmp_dir/tmp_picture2$ext ";

		exec($convertpath.$convertstr);

		exec($convertpath.$_convertstr);

		if (!$pic_database) {

		    copy("$tmp_dir/tmp_picture1$ext","$bazar_dir/$pic_path/$picturename");

		    copy("$tmp_dir/tmp_picture2$ext","$bazar_dir/$pic_path/$_picturename");

		    if (!is_file("$bazar_dir/$pic_path/$picturename")) {

			died ("$bazar_dir/$pic_path is not a dir or not writeable!");

		    }

		} else {

                    $picture_size = filesize("$tmp_dir/tmp_picture1$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture1$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture1$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());



                    $picture_size = filesize("$tmp_dir/tmp_picture2$ext");

                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture2$ext", "r"), $picture_size));

                    $picinfo=GetImageSize("$tmp_dir/tmp_picture2$ext");

                    mysql_db_query($database, "INSERT INTO pictures VALUES ('$_picturename','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')")

			or die("DB Update Error $db[picture] ".mysql_error());

		}

		suppr("$tmp_dir/tmp_picture$ext");

		suppr("$tmp_dir/tmp_picture1$ext");

		suppr("$tmp_dir/tmp_picture2$ext");

	    }

    } else {

	died ("Could NOT copy the file!");

    }

  }



  mysql_close();



}



#  Attachment Handling

#################################################################################################



if (($att1!="none" || $att2!="none" || $att3!="none") && $att_enable) {



  if ($_FILES["att1"]["error"]=="1") { died ($_FILES["att1"]["name"]." is larger than PHP upload_max_filesize"); }

  if ($_FILES["att1"]["error"]=="2") { died ($_FILES["att1"]["name"]." is too large!<br>(max. $att_maxsize Byte)"); }



  if ($_FILES["att2"]["error"]=="1") { died ($_FILES["att2"]["name"]." is larger than PHP upload_max_filesize"); }

  if ($_FILES["att2"]["error"]=="2") { died ($_FILES["att2"]["name"]." is too large!<br>(max. $att_maxsize Byte)"); }



  if ($_FILES["att3"]["error"]=="1") { died ($_FILES["att3"]["name"]." is larger than PHP upload_max_filesize"); }

  if ($_FILES["att3"]["error"]=="2") { died ($_FILES["att3"]["name"]." is too large!<br>(max. $att_maxsize Byte)"); }



  if ($att1!="none" && $att1) {

    $temp=explode(".","$att1_name");

    $attinfo=$temp[(count($temp)-1)];

    if ($attinfo == "pdf" || $attinfo == "doc" || $attinfo == "txt") {

    	switch ($attinfo) {

    	    case "pdf" : $ext=".pdf"; $type="application/pdf"; break;

    	    case "doc" : $ext=".doc"; $type="application/doc"; break;

	    case "txt" : $ext=".txt"; $type="text/txt"; break;

	}

    } else {

	died ("Wrong Filetype! Only .pdf, .doc, .txt Files supported");

    }



    $attname=$timestamp.$ext;

    $copyresult=copy("$att1", "$bazar_dir/$att_path/$attname");

    if ($copyresult) {

	$in[att1]=$attname;

    } else {

	died ("Could NOT copy the file! Check permissions");

    }

  }





  if ($att2!="none" && $att2) {

    $temp=explode(".","$att2_name");

    $attinfo=$temp[(count($temp)-1)];



    if ($attinfo == "pdf" || $attinfo == "doc" || $attinfo == "txt") {

    	switch ($attinfo) {

    	    case pdf : $ext=".pdf"; $type="application/pdf"; break;

    	    case doc : $ext=".doc"; $type="application/doc"; break;

	    case txt : $ext=".txt"; $type="text/txt"; break;

	}

    } else {

	died ("Wrong Filetype! Only .pdf, .doc, .txt Files supported");

    }



    $attname=($timestamp+1).$ext;

    $copyresult=copy("$att2", "$bazar_dir/$att_path/$attname");

    if ($copyresult) {

	$in[att2]=$attname;

    } else {

	died ("Could NOT copy the file! Check permissions");

    }

  }





  if ($att3!="none" && $att3) {

    $temp=explode(".","$att3_name");

    $attinfo=$temp[(count($temp)-1)];



    if ($attinfo == "pdf" || $attinfo == "doc" || $attinfo == "txt") {

    	switch ($attinfo) {

    	    case pdf : $ext=".pdf"; $type="application/pdf"; break;

    	    case doc : $ext=".doc"; $type="application/doc"; break;

	    case txt : $ext=".txt"; $type="text/txt"; break;

	}

    } else {

	died ("Wrong Filetype! Only .pdf, .doc, .txt Files supported");

    }



    $attname=($timestamp+2).$ext;

    $copyresult=copy("$att3", "$bazar_dir/$att_path/$attname");

    if ($copyresult) {

	$in[att3]=$attname;

    } else {

	died ("Could NOT copy the file! Check permissions");

    }

  }





}



#  Text Handling

#################################################################################################



if (!$in[location] || !$in[header] || !$in[text]) {

    $error=$error[14];

    died($error);

} else {



    mysql_connect($server, $db_user, $db_pass) or died("Database NOT connected");



    $login_check = $authlib->is_logged();

    if (isbanned($login_check[1])) {

	$error=rawurlencode($error[27]);

        header("Location: $url_to_start/classified.php?status=6&errormessage=$error");

        exit;

        }



    if (strlen($in['text']) < $limit["0"] || strlen($in['text']) > $limit["1"]) {

	died("Sorry, your text has to be between $limit[0] and $limit[1] characters.");

    }



    $in = strip_array($in);

    $in[text]=encode_msg($in[text]);



    if ($in[icon1]=="on")  {$in[icon1]=1;}  else {$in[icon1]=0;}

    if ($in[icon2]=="on")  {$in[icon2]=1;}  else {$in[icon2]=0;}

    if ($in[icon3]=="on")  {$in[icon3]=1;}  else {$in[icon3]=0;}

    if ($in[icon4]=="on")  {$in[icon4]=1;}  else {$in[icon4]=0;}

    if ($in[icon5]=="on")  {$in[icon5]=1;}  else {$in[icon5]=0;}

    if ($in[icon6]=="on")  {$in[icon6]=1;}  else {$in[icon6]=0;}

    if ($in[icon7]=="on")  {$in[icon7]=1;}  else {$in[icon7]=0;}

    if ($in[icon8]=="on")  {$in[icon8]=1;}  else {$in[icon8]=0;}

    if ($in[icon9]=="on")  {$in[icon9]=1;}  else {$in[icon9]=0;}

    if ($in[icon10]=="on") {$in[icon10]=1;} else {$in[icon10]=0;}



    if ($editadid) {



	if ($adeditapproval) {$publicview=-1; $textmessage=$text_msg[1]; } else {$publicview=1;}



	if ($pic1del) {$in[picture]=""; $in[_picture]="";}

	if ($pic2del) {$in[picture2]=""; $in[_picture2]="";}

	if ($pic3del) {$in[picture3]=""; $in[_picture3]="";}

	if ($att1del) {$in[att1]="";}

	if ($att2del) {$in[att2]="";}

	if ($att3del) {$in[att3]="";}



        mysql_db_query($database, "UPDATE ads SET userid='$in[userid]',

						    catid='$in[catid]',

						    subcatid='$in[subcatid]',

						    adeditdate=now(),

						    ip='$ip',

						    durationdays='$in[duration]',

						    location='$in[location]',

						    header='$in[header]',

						    text='$in[text]',

						    _picture='$in[_picture]',

						    picture='$in[picture]',

						    _picture2='$in[_picture2]',

						    picture2='$in[picture2]',

						    _picture3='$in[_picture3]',

						    picture3='$in[picture3]',

						    attachment1='$in[att1]',

						    attachment2='$in[att2]',

						    attachment3='$in[att3]',

						    sfield='$in[sfield]',

						    field1='$in[field1]',

						    field2='$in[field2]',

						    field3='$in[field3]',

						    field4='$in[field4]',

						    field5='$in[field5]',

						    field6='$in[field6]',

						    field7='$in[field7]',

						    field8='$in[field8]',

						    field9='$in[field9]',

						    field10='$in[field10]',

						    field11='$in[field11]',

						    field12='$in[field12]',

						    field13='$in[field13]',

						    field14='$in[field14]',

						    field15='$in[field15]',

						    field16='$in[field16]',

						    field17='$in[field17]',

						    field18='$in[field18]',

						    field19='$in[field19]',

						    field20='$in[field20]',

						    icon1='$in[icon1]',

						    icon2='$in[icon2]',

						    icon3='$in[icon3]',

						    icon4='$in[icon4]',

						    icon5='$in[icon5]',

						    icon6='$in[icon6]',

						    icon7='$in[icon7]',

						    icon8='$in[icon8]',

						    icon9='$in[icon9]',

						    icon10='$in[icon10]',

						    publicview='$publicview'

						    WHERE id=$editadid") or died("Database Query Error");



        logging("0","$login_check[1]","$login_check[0]","AD: changed","Cat: $in[catid] ($in[cat]) - Subcat: $in[subcatid] ($in[subcat]) - Ad: $editadid, Header: $in[header]");



        if ($aded_notify) {

	    $mailto = "$aded_notify";

            $from = "From: $admin_email";

	    $subject = "NOTIFY edited AD from $in[uname]";

            $message = "User: $in[userid] ($in[uname])\nCat: $in[catid] ($in[cat])\nSubcat: $in[subcatid] ($in[subcat])\nLocation: $in[location]\nHeader: $in[header]\n\nText: $in[text]";

	    @mail($mailto, $subject, $message, $from);

	}





    } else {

	if ($adapproval) {$publicview=0;} else {$publicview=1;}

        mysql_db_query($database, "INSERT INTO ads (userid, catid, subcatid, addate, adeditdate, ip, durationdays, location,

        header, text, _picture, picture, _picture2, picture2, _picture3, picture3,attachment1,attachment2,attachment3,

	sfield, field1, field2, field3, field4, field5, field6, field7, field8, field9,

        field10, field11, field12, field13, field14, field15, field16, field17, field18, field19, field20,

	icon1, icon2, icon3, icon4, icon5, icon6, icon7, icon8, icon9, icon10, publicview)

        VALUES('$in[userid]', '$in[catid]','$in[subcatid]',now(),now(),'$ip','$in[duration]','$in[location]',

        '$in[header]','$in[text]','$in[_picture]','$in[picture]','$in[_picture2]','$in[picture2]','$in[_picture3]','$in[picture3]','$in[att1]','$in[att2]','$in[att3]',

	'$in[sfield]','$in[field1]','$in[field2]','$in[field3]',

        '$in[field4]','$in[field5]','$in[field6]','$in[field7]','$in[field8]','$in[field9]','$in[field10]',

	'$in[field11]','$in[field12]','$in[field13]',

        '$in[field14]','$in[field15]','$in[field16]','$in[field17]','$in[field18]','$in[field19]','$in[field20]',

        '$in[icon1]','$in[icon2]','$in[icon3]','$in[icon4]','$in[icon5]','$in[icon6]','$in[icon7]','$in[icon8]',

        '$in[icon9]','$in[icon10]','$publicview')") or died("Database Query Error");



	$newadid=mysql_insert_id();

	$newcatid=$in[catid];

	$newsubcatid=$in[subcatid];



        if (!$adapproval) {

	 $textmessage="";

	 mysql_db_query($database,"update adcat set ads=ads+1 where id='$in[catid]'") or died("Database Query Error");

         mysql_db_query($database,"update adsubcat set ads=ads+1,notify='1' where id='$in[subcatid]'") or died("Database Query Error");

	 mysql_db_query($database,"update userdata set ads=ads+1,lastaddate=now() where id='$in[userid]'") or died("Database Query Error");

	} else {

	 $textmessage=$text_msg[1];

	}



        if ($ad_notify) {

	    $mailto = "$ad_notify";

            $from = "From: $admin_email";

	    $subject = "NOTIFY new AD from $in[uname]";

            $message = "User: $in[userid] ($in[uname])\nCat: $in[catid] ($in[cat])\nSubcat: $in[subcatid] ($in[subcat])\nLocation: $in[location]\nHeader: $in[header]\n\nText: $in[text]";

    	    @mail($mailto, $subject, $message, $from);

	}



	if ($sales_option) {

            sales_countevent(2,$in[userid],$in[catid]);

        }



        logging("0","$login_check[1]","$login_check[0]","AD: new","Cat: $in[catid] ($in[cat]) - Subcat: $in[subcatid] ($in[subcat]) - Header: $in[header]");



    }





    #  some functions at update time

    #################################################################################################



    if ($timeoutnotify>0) {

	$sql = "select * FROM ads WHERE (TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays-'$timeoutnotify')) AND timeoutdays<'$timeoutmax' AND deleted!='1' AND publicview='1' AND timeoutnotify=''";

	$result = mysql_db_query($database, $sql) or died("Database Query Error");

	while ($db = mysql_fetch_array($result)) {

	    $result2 = mysql_db_query($database, "SELECT * FROM userdata WHERE id=$db[userid]") or died("Record NOT Found");

	    $dbu = mysql_fetch_array($result2);

	    $mdhash=md5($timestamp.$db[header].$db[id].$secret);

	    $mailto = "$dbu[email]";

            $from = "From: $admin_email";

	    $subject = "$mail_msg[19]";

            $message = "$mail_msg[20]\nID: $db[id] ($db[header])\n\n$mail_msg[21]$url_to_start/confirm_ad.php?id=$db[id]&hash=$mdhash\n\n$mail_msg[22]";

    	    @mail($mailto, $subject, $message, $from);

	    mysql_db_query($database,"UPDATE ads SET timeoutnotify='$mdhash' WHERE id='$db[id]'")

	    or died("Database Query Error - userdata");

	}

    }



    if ($adautoflush) {

	if ($really_del_memb) {

	    $result = mysql_db_query($database, "select * FROM ads WHERE (TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays)) OR deleted='1'")

	    		or died("Database Query Error");

	} else {

	    $result = mysql_db_query($database, "select * FROM ads WHERE TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays)")

	    		or died("Database Query Error");

	}

	while ($db = mysql_fetch_array($result)) {



	    // Subtract Counter in userdata-DB

	    mysql_db_query($database,"update userdata set ads=ads-1,lastaddate=now() where id='$db[userid]'")

	    or died("Database Query Error - userdata");



	    // Subtract Counter in adcat-DB

	    mysql_db_query($database,"update adcat set ads=ads-1 where id='$db[catid]'")

	    or died("Database Query Error - adcat");



	    // Subtract Counter in adsubcat-DB

	    mysql_db_query($database,"update adsubcat set ads=ads-1 where id='$db[subcatid]'")

	    or died("Database Query Error - adsubcat");



	    // Delete Pictures if any ...

    	    if (!$pic_database && $db[picture] && is_file("$bazar_dir/$pic_path/$db[picture]")) {

        	suppr("$bazar_dir/$pic_path/$db[picture]");

    	    } elseif ($db[picture]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[picture]'") or died("Database Query Error");

	    }

    	    if (!$pic_database && $db[_picture] && is_file("$bazar_dir/$pic_path/$db[_picture]")) {

        	suppr("$bazar_dir/$pic_path/$db[_picture]");

    	    } elseif ($db[_picture]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture]'") or died("Database Query Error");

    	    }



    	    if (!$pic_database && $db[picture2] && is_file("$bazar_dir/$pic_path/$db[picture2]")) {

        	suppr("$bazar_dir/$pic_path/$db[picture2]");

    	    } elseif ($db[picture2]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[picture2]'") or died("Database Query Error");

	    }

    	    if (!$pic_database && $db[_picture2] && is_file("$bazar_dir/$pic_path/$db[_picture2]")) {

        	suppr("$bazar_dir/$pic_path/$db[_picture2]");

    	    } elseif ($db[_picture2]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture2]'") or died("Database Query Error");

    	    }



    	    if (!$pic_database && $db[picture3] && is_file("$bazar_dir/$pic_path/$db[picture3]")) {

        	suppr("$bazar_dir/$pic_path/$db[picture3]");

    	    } elseif ($db[picture3]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[picture3]'") or died("Database Query Error");

	    }

    	    if (!$pic_database && $db[_picture3] && is_file("$bazar_dir/$pic_path/$db[_picture3]")) {

        	suppr("$bazar_dir/$pic_path/$db[_picture3]");

    	    } elseif ($db[_picture3]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture3]'") or died("Database Query Error");

    	    }



	    // Delete Attachments if any ...

    	    if ($db[attachment1] && is_file("$bazar_dir/$att_path/$db[attachment1]")) {

        	suppr("$bazar_dir/$att_path/$db[attachment1]");

	    }

    	    if ($db[attachment2] && is_file("$bazar_dir/$att_path/$db[attachment2]")) {

        	suppr("$bazar_dir/$att_path/$db[attachment2]");

	    }

    	    if ($db[attachment3] && is_file("$bazar_dir/$att_path/$db[attachment3]")) {

        	suppr("$bazar_dir/$att_path/$db[attachment3]");

	    }



    	    // Delete Entry from favorits-DB

	    mysql_db_query($database,"delete from favorits where adid = '$db[id]'")

	    or died("Database Query Error");



	    // Delete Entry from ads-DB

	    mysql_db_query($database,"delete from ads where id = '$db[id]'")

	    or died("Database Query Error - ads");



	}

    }



    mysql_close();



    if ($editadid && !$login_check[2]) {

	if ($adeditapproval) {

    	    $locvar="choice=my&status=13&textmessage=".rawurlencode($text_msg[1]);

	} else {

#    	    $locvar="choice=my&status=13&textmessage=".rawurlencode($text_msg[0]);

    	    $locvar="choice=my&status=13";

	}

    } else {

	if ($adapproval) {

    	    $locvar="choice=my&status=13&textmessage=".rawurlencode($text_msg[1]);

	} else {

#    	    $locvar="catid=$newcatid&subcatid=$newsubcatid&adid=$newadid&status=13&textmessage=".rawurlencode($text_msg[0]);

    	    $locvar="catid=$newcatid&subcatid=$newsubcatid&adid=$newadid&status=13";

#    	    $locvar="catid=$newcatid&subcatid=$newsubcatid&status=13";

	}

	if ($force_addad && $HTTP_COOKIE_VARS["ForceAddAd"]==1){

	    setcookie("ForceAddAd", "", 0, "$cookiepath"); // delete cookie

	}

    }

    header("Location: $url_to_start/classified.php?$locvar");

}



?>