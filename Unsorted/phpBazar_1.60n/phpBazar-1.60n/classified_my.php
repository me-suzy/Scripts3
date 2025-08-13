<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_my.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Member's can update & delete there ads entry's
#
#################################################################################################



#  Connect to the DB

#################################################################################################



mysql_connect($server, $db_user, $db_pass);



#  Get Entrys for current page

#################################################################################################

if ($login_check) {$mod=$login_check[2];}

if ($login_check) {$uid=$login_check[1];}

if ($uid) {



 $result = mysql_db_query($database, "SELECT * FROM userdata WHERE id='$uid'") or died("Record NOT Found");

 $db = mysql_fetch_array($result);



 # Calculate Page-Numbers

 #################################################################################################

 if (empty($perpage)) $perpage = 5;

 if (empty($pperpage)) $pperpage = 5;    // !!! ONLY 3,5,7,9,11,13 !!!

 if (empty($offset)) $offset = 0;

 if (empty($poffset)) $poffset = 0;



 if ($choice=="fav") {

    $result = mysql_db_query($database, "SELECT * FROM favorits WHERE userid='$uid' LIMIT $offset, $perpage") or died(mysql_error());

    $amount = mysql_db_query($database, "SELECT count(userid) FROM favorits WHERE userid='$uid'") or died("Favorits - Record NOT Found");

 } else {

    $result = mysql_db_query($database, "SELECT * FROM ads WHERE userid='$uid' ORDER by addate DESC LIMIT $offset, $perpage") or died(mysql_error());

    $amount = mysql_db_query($database, "SELECT count(id) FROM ads WHERE userid='$uid' ORDER by addate DESC") or died("Ads - Record NOT Found");

 }



 $amount_array = mysql_fetch_array($amount);

 $pages = ceil($amount_array[0] / $perpage);

 $actpage = ($offset+$perpage)/$perpage;

 $maxpoffset = $pages-$pperpage;

 $middlepage=($pperpage-1)/2;

 if ($maxpoffset<0) {$maxpoffset=0;}



 if ($amount_array[0]) {



  echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

  echo "<tr><td><div class=\"smallleft\">\n";

  echo "$admy_member$db[username]\n";

  echo "</div></td>\n";

  echo "<td><div class=\"mainpages\">\n";



  if ($pages) {                                       // print only when pages > 0

    echo "$ad_pages\n";



    if ($offset) {

	$noffset=$offset-$perpage;

	$npoffset = $noffset/$perpage-$middlepage;

	if ($npoffset<0) {$npoffset = 0;}

	if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

        echo "[<a href=\"classified.php?choice=$choice&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_prev'; return true;\"

	     onmouseout=\"window.status=''; return true;\"><</a>]\n";

    }

    for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {

        $noffset = $i * $perpage;

	$npoffset = $noffset/$perpage-$middlepage;

	if ($npoffset<0) {$npoffset = 0;}

	if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	$actual = $i + 1;

        if ($actual==$actpage) {

	    echo "(<a href=\"classified.php?choice=$choice&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_actpage'; return true;\"

	     onmouseout=\"window.status=''; return true;\">$actual</a>)\n";

	    } else {

	    echo "[<a href=\"classified.php?choice=$choice&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_gopage'; return true;\"

	     onmouseout=\"window.status=''; return true;\">$actual</a>]\n";

	    }

	}



    if ($offset+$perpage<$amount_array["0"]) {

        $noffset=$offset+$perpage;

	$npoffset = $noffset/$perpage-$middlepage;

	if ($npoffset<0) {$npoffset = 0;}

	if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

        echo "[<a href=\"classified.php?choice=$choice&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_next'; return true;\"

	     onmouseout=\"window.status=''; return true;\">></a>]\n";

	}

  }



  echo "</div></td></tr>\n";

  echo "</table>\n";





echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

while ($tempdb = mysql_fetch_array($result)) {

  if ($choice=="fav") {

	$query = mysql_db_query($database, "SELECT * FROM ads WHERE id='$tempdb[adid]'") or died("Ads - Record NOT Found");

	$db = mysql_fetch_array($query);

  } else {

	$db = $tempdb;

  }



  if ($db) {

    $result2 = mysql_db_query($database, "SELECT * FROM userdata WHERE id='$db[userid]'") or died("Userdata - Record NOT Found".mysql_error());

    $dbu = mysql_fetch_array($result2);

    $result3 = mysql_db_query($database, "SELECT * FROM adcat WHERE id='$db[catid]'") or died("Adcat - Record NOT Found");

    $dbc = mysql_fetch_array($result3);



    if (!$db[location]) {$db[location]=$ad_noloc;}



    $iconstring="";

    for ($i=10; $i > 0; $i--) {

	$iconfield="icon".$i;

	$iconalt="icon".$i."alt";

	if ($db[$iconfield] && adfield($db[catid],$iconfield)) {

    	    $iconstring.="<img src=\"$dbc[$iconfield]\" alt=\"$dbc[$iconalt]\" align=\"right\" vspace=\"2\"

	     onmouseover=\"window.status='$dbc[$iconalt]'; return true;\"

	     onmouseout=\"window.status=''; return true;\">\n";

	    }

	}





    echo " <tr>\n";

    echo "   <td class=\"classcat5\">\n";



    if ($db[_picture] || $db[_picture2]  || $db[_picture3]) { 	// advanced picture handling



      if (!$pic_database && $db[picture] && is_file("$pic_path/$db[picture]") && is_file("$pic_path/$db[_picture]")) {

	echo "   <a href=\"$pic_path/$db[picture]\" onClick='enterWindow=window.open(\"$pic_path/$db[picture]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		<img src=\"$pic_path/$db[_picture]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";

      } elseif ($db[picture]) {

	echo "   <a href=\"picturedisplay.php?id=$db[picture]\" onClick='enterWindow=window.open(\"picturedisplay.php?id=$db[picture]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

	        <img src=\"picturedisplay.php?id=$db[_picture]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";

      }



      if (!$pic_database && $db[picture2] && is_file("$pic_path/$db[picture2]") && is_file("$pic_path/$db[_picture2]")) {

	echo "   <a href=\"$pic_path/$db[picture2]\" onClick='enterWindow=window.open(\"$pic_path/$db[picture2]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		<img src=\"$pic_path/$db[_picture2]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";

      } elseif ($db[picture2]) {

	echo "   <a href=\"picturedisplay.php?id=$db[picture2]\" onClick='enterWindow=window.open(\"picturedisplay.php?id=$db[picture2]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

	        <img src=\"picturedisplay.php?id=$db[_picture2]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";

      }



      if (!$pic_database && $db[picture3] && is_file("$pic_path/$db[picture3]") && is_file("$pic_path/$db[_picture3]")) {

	echo "   <a href=\"$pic_path/$db[picture3]\" onClick='enterWindow=window.open(\"$pic_path/$db[picture3]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		<img src=\"$pic_path/$db[_picture3]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";

      } elseif ($db[picture3]) {

	echo "   <a href=\"picturedisplay.php?id=$db[picture3]\" onClick='enterWindow=window.open(\"picturedisplay.php?id=$db[picture3]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

	        <img src=\"picturedisplay.php?id=$db[_picture3]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";

      }





    } elseif ($db[picture] || $db[picture2] || $db[picture3]) { 	// simple picture handling



        if (!$pic_database && $db[picture] && is_file("$pic_path/$db[picture]")) {

	    // claculate thumbnail-size

	    $picinfo=GetImageSize("$pic_path/$db[picture]");

	    $picsize=explode("x",$pic_lowres);

	    if ($picinfo[0]>intval($picsize[0]) || $picinfo[1]>intval($picsize[1])) {

		$div[0]=$picinfo[0]/$picsize[0];

		$div[1]=$picinfo[1]/$picsize[1];

		if ($div[0]>$div[1]) {

		    $sizestr="width=".intval($picinfo[0]/$div[0])." height=".intval($picinfo[1]/$div[0]);

		} else {

		    $sizestr="width=".intval($picinfo[0]/$div[1])." height=".intval($picinfo[1]/$div[1]);

		}

	    } else {

		$sizestr=$picinfo[3];

	    }

    	    echo "   <a href=\"$pic_path/$db[picture]\" onClick='enterWindow=window.open(\"$pic_path/$db[picture]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		    <img src=\"$pic_path/$db[picture]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";

	} elseif ($db[picture]) {

	    // claculate thumbnail-size

	    $result4 = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$db[picture]'") or died("Can NOT find the Picture");

	    $dbp = mysql_fetch_array($result4);

	    $picsize=explode("x",$pic_lowres);

	    if ($dbp[picture_width]>intval($picsize[0]) || $dbp[picture_height]>intval($picsize[1])) {

		$div[0]=$dbp[picture_width]/$picsize[0];

		$div[1]=$dbp[picture_height]/$picsize[1];

		if ($div[0]>$div[1]) {

		    $sizestr="width=".intval($dbp[picture_width]/$div[0])." height=".intval($dbp[picture_height]/$div[0]);

		} else {

		    $sizestr="width=".intval($dbp[picture_width]/$div[1])." height=".intval($dbp[picture_height]/$div[1]);

		}

	    } else {

		$sizestr="width=$dbp[picture_width] height=$dbp[picture_height]";

	    }

    	    echo "   <a href=\"picturedisplay.php?id=$db[picture]\" onClick='enterWindow=window.open(\"picturedisplay.php?id=$db[picture]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		    <img src=\"picturedisplay.php?id=$db[picture]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";

	}







        if (!$pic_database && $db[picture2] && is_file("$pic_path/$db[picture2]")) {

	    // claculate thumbnail-size

	    $picinfo=GetImageSize("$pic_path/$db[picture2]");

	    $picsize=explode("x",$pic_lowres);

	    if ($picinfo[0]>intval($picsize[0]) || $picinfo[1]>intval($picsize[1])) {

		$div[0]=$picinfo[0]/$picsize[0];

		$div[1]=$picinfo[1]/$picsize[1];

		if ($div[0]>$div[1]) {

		    $sizestr="width=".intval($picinfo[0]/$div[0])." height=".intval($picinfo[1]/$div[0]);

		} else {

		    $sizestr="width=".intval($picinfo[0]/$div[1])." height=".intval($picinfo[1]/$div[1]);

		}

	    } else {

		$sizestr=$picinfo[3];

	    }

    	    echo "   <a href=\"$pic_path/$db[picture2]\" onClick='enterWindow=window.open(\"$pic_path/$db[picture2]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		    <img src=\"$pic_path/$db[picture2]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";

	} elseif ($db[picture2]) {

	    // claculate thumbnail-size

	    $result4 = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$db[picture2]'") or died("Can NOT find the Picture");

	    $dbp = mysql_fetch_array($result4);

	    $picsize=explode("x",$pic_lowres);

	    if ($dbp[picture_width]>intval($picsize[0]) || $dbp[picture_height]>intval($picsize[1])) {

		$div[0]=$dbp[picture_width]/$picsize[0];

		$div[1]=$dbp[picture_height]/$picsize[1];

		if ($div[0]>$div[1]) {

		    $sizestr="width=".intval($dbp[picture_width]/$div[0])." height=".intval($dbp[picture_height]/$div[0]);

		} else {

		    $sizestr="width=".intval($dbp[picture_width]/$div[1])." height=".intval($dbp[picture_height]/$div[1]);

		}

	    } else {

		$sizestr="width=$dbp[picture_width] height=$dbp[picture_height]";

	    }

    	    echo "   <a href=\"picturedisplay.php?id=$db[picture2]\" onClick='enterWindow=window.open(\"picturedisplay.php?id=$db[picture2]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		    <img src=\"picturedisplay.php?id=$db[picture2]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";

	}







        if (!$pic_database && $db[picture3] && is_file("$pic_path/$db[picture3]")) {

	    // claculate thumbnail-size

	    $picinfo=GetImageSize("$pic_path/$db[picture3]");

	    $picsize=explode("x",$pic_lowres);

	    if ($picinfo[0]>intval($picsize[0]) || $picinfo[1]>intval($picsize[1])) {

		$div[0]=$picinfo[0]/$picsize[0];

		$div[1]=$picinfo[1]/$picsize[1];

		if ($div[0]>$div[1]) {

		    $sizestr="width=".intval($picinfo[0]/$div[0])." height=".intval($picinfo[1]/$div[0]);

		} else {

		    $sizestr="width=".intval($picinfo[0]/$div[1])." height=".intval($picinfo[1]/$div[1]);

		}

	    } else {

		$sizestr=$picinfo[3];

	    }

    	    echo "   <a href=\"$pic_path/$db[picture3]\" onClick='enterWindow=window.open(\"$pic_path/$db[picture3]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		    <img src=\"$pic_path/$db[picture3]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";

	} elseif ($db[picture3]) {

	    // claculate thumbnail-size

	    $result4 = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$db[picture3]'") or died("Can NOT find the Picture");

	    $dbp = mysql_fetch_array($result4);

	    $picsize=explode("x",$pic_lowres);

	    if ($dbp[picture_width]>intval($picsize[0]) || $dbp[picture_height]>intval($picsize[1])) {

		$div[0]=$dbp[picture_width]/$picsize[0];

		$div[1]=$dbp[picture_height]/$picsize[1];

		if ($div[0]>$div[1]) {

		    $sizestr="width=".intval($dbp[picture_width]/$div[0])." height=".intval($dbp[picture_height]/$div[0]);

		} else {

		    $sizestr="width=".intval($dbp[picture_width]/$div[1])." height=".intval($dbp[picture_height]/$div[1]);

		}

	    } else {

		$sizestr="width=$dbp[picture_width] height=$dbp[picture_height]";

	    }

    	    echo "   <a href=\"picturedisplay.php?id=$db[picture3]\" onClick='enterWindow=window.open(\"picturedisplay.php?id=$db[picture3]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>

		    <img src=\"picturedisplay.php?id=$db[picture3]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";

	}



    } else {                    // no picture handling



    }

    echo "   &nbsp;\n";

    echo "   </td>\n";

    echo "   <td class=\"classcat6\">\n";

    echo "   <table width=\"100%\" border=0 cellspacing=0 cellpadding=0><tr><td>\n";



    if($show_adrating) {

	$per = $db[rating]*10/2;

	echo "<table align=right border=0 cellspacing=4 cellpadding=1 width=\"58\"

	    	    onmouseover=\"window.status='$ad_rating $db[rating]'; return true;\"

		    onmouseout=\"window.status=''; return true;\">\n";

	echo " <tr>\n";

	echo "  <td class=\"ratebarout\">\n";

	echo "   <img src=\"$image_dir/$adrating_icon\" align=\"left\" border=\"0\" width=\"$per\" height=\"6\"

		    alt=\"$ad_rating $db[rating]\" hspace=\"0\"

		    onmouseover=\"window.status='$ad_rating $db[rating]'; return true;\"

		    onmouseout=\"window.status=''; return true;\">\n";

	echo "  </td>\n";

	echo " </tr>\n";

	echo "</table>\n";

    }





    if ($show_newicon && dateToTime($db[addate])>$timestamp-86400*$show_newicon) {

	echo "    <img src=\"$image_dir/icons/new.gif\" align=\"right\" vspace=\"2\">";

    }

    echo "   <div class=\"whiteleft\">".badwords($db[header],$mod)."<br></div>\n";

    echo "   </td><td width=\"1%\" valign=\"top\">\n";

    echo "   <div class=\"smallleft\">\n";

    echo "   <img src=\"$image_dir/icons/chart.gif\" alt=\"$ad_stat\" align=\"left\" hspace=\"2\"

             onmouseover=\"window.status='$ad_stat'; return true;\"

             onmouseout=\"window.status=''; return true;\">:$db[viewed]/$db[answered]</div>\n";

    echo "  </td></tr></table>\n";

    echo "   <div class=\"smallleft\">\n";

    echo "   $ad_from $dbu[username] $ad_date ".dateToStr($db[addate])."<br>\n";

    echo "   $iconstring\n";

    echo "   $ad_location$db[location]\n";

    echo "   <br><div class=\"spaceleft\">&nbsp</div><hr>\n";



    if ($db[attachment1] || $db[attachment2] || $db[attachment3] && $att_enable) {



        if ($db[attachment3]) {

            echo "<a href=\"$att_path/$db[attachment3]\" onClick='enterWindow=window.open(\"$att_path/$db[attachment3]\",\"Picture\",\"top=10,left=10,scrollbars=yes\"); return false'>

              <img src=\"$image_dir/$att_icon\" alt=\"$db[attachment3]\" align=\"right\" vspace=\"2\" border=\"0\"

              onmouseover=\"window.status='$db[attachment3]'; return true;\"

              onmouseout=\"window.status=''; return true;\"></a>\n";

        }



        if ($db[attachment2]) {

            echo "<a href=\"$att_path/$db[attachment2]\" onClick='enterWindow=window.open(\"$att_path/$db[attachment2]\",\"Picture\",\"top=10,left=10,scrollbars=yes\"); return false'>

              <img src=\"$image_dir/$att_icon\" alt=\"$db[attachment2]\" align=\"right\" vspace=\"2\" border=\"0\"

              onmouseover=\"window.status='$db[attachment2]'; return true;\"

              onmouseout=\"window.status=''; return true;\"></a>\n";

        }



        if ($db[attachment1]) {

            echo "<a href=\"$att_path/$db[attachment1]\" onClick='enterWindow=window.open(\"$att_path/$db[attachment1]\",\"Picture\",\"top=10,left=10,scrollbars=yes\"); return false'>

              <img src=\"$image_dir/$att_icon\" alt=\"$db[attachment1]\" align=\"right\" vspace=\"2\" border=\"0\"

              onmouseover=\"window.status='$db[attachment1]'; return true;\"

              onmouseout=\"window.status=''; return true;\"></a>\n";

        }



        echo " $ad_att:";

        echo "   <br><div class=\"spaceleft\">&nbsp</div><br><div class=\"spaceleft\">&nbsp</div><hr>\n";



    }





    echo "<table cellspacing=\"0\" cellpading=\"0\">";



    if ($dbc[sfield] && adfield($db[catid],"sfield")) {

        echo "<tr valign=\"top\">

                <td><div class=smallleft>$dbc[sfield]</div></td>

                <td><div class=smallleft>:</div></td>

                <td><div class=smallleft>".badwords($db[sfield],$mod)."</div></td>

              </tr>";

    }



    for ($i=1;$i<=20;$i++) {

        $fieldi=("field".$i);

        if ($dbc[$fieldi] && adfield($db[catid],"$fieldi")) {

            echo "<tr valign=\"top\">

                    <td><div class=smallleft>$dbc[$fieldi]</div></td>

                    <td><div class=smallleft>:</div></td>";

    	    if (ereg("checkbox",adfield($db[catid],"$fieldi")) && $db[$fieldi]=="on") {

        	echo "<td><img src=\"$image_dir/icons/checked2.gif\" border=\"0\" alt=\"$ad_yes\"

                	onmouseover=\"window.status='$ad_yes'; return true;\"

                	onmouseout=\"window.status=''; return true;\"></td>\n";

    	    } elseif (ereg("checkbox",adfield($db[catid],"$fieldi")) && $db[$fieldi]=="") {

        	echo "<td><img src=\"$image_dir/icons/signno.gif\" border=\"0\" alt=\"$ad_no\"

                	onmouseover=\"window.status='$ad_no'; return true;\"

                	onmouseout=\"window.status=''; return true;\"></td>\n";

    	    } else {

    		echo "<td><div class=smallleft>".badwords($db[$fieldi],$mod)." ".adfieldunit($db[catid],"$fieldi")."</div></td>";

	    }

        echo "</tr>";

        }

    }



    echo "<tr valign=\"top\">

            <td><div class=smallleft>$ad_text</div></td>

            <td><div class=smallleft>:</div></td>

            <td><div class=smallleft>".badwords($db[text],$mod)."</div></td>

	  </tr>\n";



    echo "</table>";

    echo "<hr>\n";



    if ($choice=="my") {

	echo "   <div class=\"smallright\">$ad_nr$db[id]</div>";

	echo "   <a href=\"classified_my_del.php?adid=$db[id]\"

	      onClick='enterWindow=window.open(\"classified_my_del.php?adid=$db[id]\",\"Delete\",\"width=400,height=200,top=100,left=100\"); return false'

	      onmouseover=\"window.status='$admy_delete'; return true;\"

	      onmouseout=\"window.status=''; return true;\">

	     <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"$admy_delete\" align=\"right\" vspace=\"2\"></a>\n";

        echo "   <a href=\"classified.php?editadid=$db[id]\"

	      onmouseover=\"window.status='$admy_edit'; return true;\"

	      onmouseout=\"window.status=''; return true;\">

	     <img src=\"$image_dir/icons/reply.gif\" border=\"0\" alt=\"$admy_edit\" align=\"right\" vspace=\"2\"></a>\n";



    } elseif ($choice=="fav") {

        if ($sales_option && !sales_checkaccess(3,$uid,$db[catid])) { // check access for user and cat

	    ico_email("","left");

        } else {

	    ico_email("adid=$db[id]","left");

        }



	if ($dbu[icq]) {

            if ($sales_option && !sales_checkaccess(3,$uid,$db[catid])) { // check access for user and cat

    		ico_icq("","left");

            } else {

    		ico_icq("$dbu[icq]","left");

	    }

	}



	ico_friend("catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]"
,"left");



	if ($show_url && $dbu[homepage]) {

    	    if ($sales_option && !sales_checkaccess(3,$uid,$catid)) { // check access for user and cat

    		ico_url("","left");

    	    } else {

    		ico_url("$dbu[homepage]","left");

	    }

	}



	ico_print("","left");



	if ($show_adrating && $login_check) {

           ico_adrating("adid=$db[id]","left");

	}



        if ($show_members_details && $login_check) {

            ico_info("choice=details&uid=$dbu[id]&uname=$dbu[username]","left");

        }



	echo "   <div class=\"smallright\">$ad_nr$db[id]</div>";



	echo " <a href=\"favorits.php?deladid=$db[id]\"

	      onClick='enterWindow=window.open(\"favorits.php?deladid=$db[id]\",\"Delete\",\"width=400,height=200,top=100,left=100\"); return false'

	      onmouseover=\"window.status='$adfav_delete'; return true;\"

	      onmouseout=\"window.status=''; return true;\">

	     <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"$adfav_delete\" align=\"right\" vspace=\"2\"></a>\n";



    }





    echo "   </div>\n";

    echo "   </td>\n";

    echo " </tr>\n";

    } //End while

  }

 echo "</table>\n";

 } else {

    echo $mess_noentry;

 }

} else { // NO Login



}



# End of Page reached

#################################################################################################





#  Disconnect DB

#################################################################################################

mysql_close();

?>