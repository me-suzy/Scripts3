<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_ads.inc.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: IncludeModule show classified ad (overview)
#
#################################################################################################



	    if (!$db[location]) {$db[location]=$ad_noloc;}



	    $iconstring="";

	    for ($i=10; $i > 0; $i--) {

		$iconfield="icon".$i;

		$iconalt="icon".$i."alt";

		if ($db[$iconfield] && adfield($db[catid],"$iconfield")) {

    		    $iconstring.="<img src=\"$dbc[$iconfield]\" alt=\"$dbc[$iconalt]\" align=\"right\" vspace=\"2\"

	    		onmouseover=\"window.status='$dbc[$iconalt]'; return true;\"

	    		onmouseout=\"window.status=''; return true;\">\n";

		}

	    }



	    if ($db[attachment1] || $db[attachment2] || $db[attachment3]) {

		$iconstring.="<img src=\"$image_dir/icons/diskette.gif\" alt=\"$ad_att\" align=\"right\" vspace=\"2\"

	    	    onmouseover=\"window.status='$ad_att'; return true;\"

	    	    onmouseout=\"window.status=''; return true;\">\n";

	    }





	    echo " <tr>\n";

	    echo "   <td class=\"classcat3\">\n";

	    if ($db[picture] || $db[picture2] || $db[picture3]) {



		if (!$db[picture] && $db[picture2]) {

		    $db[picture]=$db[picture2];

		    $db[_picture]=$db[_picture2];

		} elseif (!$db[picture] && !$db[picture2] && $db[picture3]) {

		    $db[picture]=$db[picture3];

		    $db[_picture]=$db[_picture3];

		}



    		if (!$pic_database && $db[picture] && is_file("$pic_path/$db[picture]") && $pic_prelowres) {

		    // claculate thumbnail-size

		    if (is_file("$pic_path/$db[_picture]")) {

			$previewpic="$pic_path/$db[_picture]";

		    } else {

			$previewpic="$pic_path/$db[picture]";

		    }

		    $picinfo=GetImageSize($previewpic);

		    $picsize=explode("x",$pic_prelowres);

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

    		    echo "   <a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\" onmouseover=\"window.status='$ad_nr $db[id]';

			    return true;\" onmouseout=\"window.status=''; return true;\"><img src=\"$previewpic\" $sizestr border=\"0\"></a>\n";



		} elseif ($db[picture] && $pic_prelowres) {

		    // claculate thumbnail-size

		    $result4 = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$db[_picture]'") or died("Can NOT find the Picture");

		    $dbp = mysql_fetch_array($result4);

		    if (!$dbp) {

			$result4 = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$db[picture]'") or died("Can NOT find the Picture");

			$dbp = mysql_fetch_array($result4);

		    }

		    $picsize=explode("x",$pic_prelowres);

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

    		    echo "   <a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\" onmouseover=\"window.status='$ad_nr $db[id]';

			    return true;\" onmouseout=\"window.status=''; return true;\"><img src=\"picturedisplay.php?id=$dbp[picture_name]\" $sizestr border=\"0\"></a>\n";

		} else {

		    echo "   <a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\" onmouseover=\"window.status='$ad_nr $db[id]';

    			    return true;\" onmouseout=\"window.status=''; return true;\"><img src=\"$image_dir/$pic_icon\" border=\"0\"></a>\n";

		}





	    } else {

		if ($pic_noicon) {

		    echo "  <img src=\"$image_dir/$pic_noicon\" border=\"0\">\n";

		} else {

		    echo "  &nbsp;\n";

		}

	    }

	    echo "   </td>\n";

	    echo "   <td class=\"classcat4\">\n";

	    echo "  <table width=\"100%\" border=0 cellspacing=0 cellpadding=0><tr><td>\n";



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

		echo "    <img src=\"$image_dir/icons/new.gif\" align=\"right\" vspace=\"2\" alt=\"$ad_new\"

    			    onmouseover=\"window.status='$ad_new'; return true;\"

    			    onmouseout=\"window.status=''; return true;\">";

	    }



	    echo "   <a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\" onmouseover=\"window.status='$ad_nr $db[id]';

			return true;\" onmouseout=\"window.status=''; return true;\">".badwords($db[header],$mod)."</a>\n";



	    echo "  </td><td width=\"1%\" valign=\"top\">\n";

	    echo "   <div class=\"smallleft\">\n";

	    echo "  <img src=\"$image_dir/icons/chart.gif\" alt=\"$ad_stat\" align=\"left\" hspace=\"2\"

    			    onmouseover=\"window.status='$ad_stat'; return true;\"

    			    onmouseout=\"window.status=''; return true;\">:$db[viewed]/$db[answered]</div>\n";



	    echo "  </td></tr></table>\n";



	    echo "   <div class=\"smallleft\">\n";

	    echo "   $ad_from $dbu[username] $ad_date ".dateToStr($db[addate])."<br>\n";

	    if ($dbc[sfield]) {

	        echo "   $ad_location$db[location]<br>\n";

		echo "   $iconstring\n";

		echo "    $dbc[sfield]: ".badwords($db[sfield],$mod)."\n";

	    } else {

	        echo "   $iconstring\n";

    		echo "   $ad_location$db[location]\n";

	    }

	    echo "   </div>";

	    echo "   </td>\n";

	    echo " </tr>\n";



?>