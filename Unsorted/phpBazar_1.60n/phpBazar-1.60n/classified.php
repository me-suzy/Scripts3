<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Classified Area
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");





#  The Head-Section

#################################################################################################

include ($HEADER);





#  The Menu-Section

#################################################################################################

include ("menu.inc");



#  The Left-Side-Section

#################################################################################################

$tmp_width = ($table_width+(2*$table_width_side)+10);

echo"<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$tmp_width\">\n";

echo"<tr>\n";

echo"<td valign=\"top\" align=\"right\">\n";

include ("left.inc");

echo"</td>\n";

$login_check = $authlib->is_logged();

#if ($login_check || $bazarfreeread) {$table_height="";}



#  The Main-Section

#################################################################################################

echo"<td valign=\"top\" align=\"left\">\n";

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";//

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";





if ((!$login_check && !$bazarfreeread) || (!$login_check && $choice=="notify" || !$login_check && $editadid || !$login_check && $choice=="add" || !$login_check && $choice=="my" || !$login_check && $choice=="fav")) {

	include ("$language_dir/nologin.inc");

} else {

	if ($force_addad && $HTTP_COOKIE_VARS["ForceAddAd"]==1){$choice="add";$editadid="";}



	if ($choice=="add" || $editadid) {

	    echo"           <table align=\"center\" width=\"100%\">\n";

	    echo"            <tr>\n";

	    echo"             <td>\n";

	    echo"              <div class=\"mainmenu\">\n";

	    echo"          	   <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';

				    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";

	    echo" 		   <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';

				    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";

	    echo"              </div>\n";

	    echo"             </td>\n";

	    echo"             <td width=\"30%\">\n";

	    if ($editadid) {

		echo "		<div class=\"mainheader\">$classedit_head</div>\n";

	    } else {

		echo "		<div class=\"mainheader\">$classadd_head</div>\n";

	    }

	    echo"             </td>\n";

	    echo"            </tr>\n";

	    echo"           </table>\n";

	    if ($force_addad && $HTTP_COOKIE_VARS["ForceAddAd"]==1) {echo $adadd_forceadd;} else {echo $adadd_pretext;}

	    include ("classified_upd.php");

	} elseif ($choice=="my") {

	    echo"           <table align=\"center\" width=\"100%\">\n";

	    echo"            <tr>\n";

	    echo"             <td>\n";

	    echo"              <div class=\"mainmenu\">\n";

	    echo"          	   <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';

				    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";

	    echo" 		   <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';

				    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";

	    echo"              </div>\n";

	    echo"             </td>\n";

	    echo"             <td width=\"30%\">\n";

	    echo "<div class=\"mainheader\">$classmy_head</div>\n";

	    echo"             </td>\n";

	    echo"            </tr>\n";

	    echo"           </table>\n";

	    include ("classified_my.php");

	} elseif ($choice=="fav") {

	    echo"           <table align=\"center\" width=\"100%\">\n";

	    echo"            <tr>\n";

	    echo"             <td>\n";

	    echo"              <div class=\"mainmenu\">\n";

	    echo"          	   <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';

				    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";

	    echo" 		   <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';

				    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";

	    echo"              </div>\n";

	    echo"             </td>\n";

	    echo"             <td width=\"30%\">\n";

	    echo "<div class=\"mainheader\">$classfav_head</div>\n";

	    echo"             </td>\n";

	    echo"            </tr>\n";

	    echo"           </table>\n";

	    include ("classified_my.php");

	} elseif ($choice=="notify") {

	    echo"           <table align=\"center\" width=\"100%\">\n";

	    echo"            <tr>\n";

	    echo"             <td>\n";

	    echo"              <div class=\"mainmenu\">\n";

	    echo"          	   <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';

				    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";

	    echo" 		   <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';

				    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";

	    echo"              </div>\n";

	    echo"             </td>\n";

	    echo"             <td width=\"30%\">\n";

	    echo "<div class=\"mainheader\">$classnot_head</div>\n";

	    echo"             </td>\n";

	    echo"            </tr>\n";

	    echo"           </table>\n";

	    include ("classified_notify.php");

	} elseif ($choice=="search") {

	    echo"           <table align=\"center\" width=\"100%\">\n";

	    echo"            <tr>\n";

	    echo"             <td>\n";

	    echo"              <div class=\"mainmenu\">\n";

	    echo"          	   <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';

				    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";

	    echo" 		   <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';

				    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";

	    echo"              </div>\n";

	    echo"             </td>\n";

	    echo"             <td width=\"30%\">\n";

	    echo "<div class=\"mainheader\">$classseek_head</div>\n";

	    echo"             </td>\n";

	    echo"            </tr>\n";

	    echo"           </table>\n";

	    include ("classified_search.php");

	} elseif ($choice=="top") {

	    echo"           <table align=\"center\" width=\"100%\">\n";

	    echo"            <tr>\n";

	    echo"             <td>\n";

	    echo"              <div class=\"mainmenu\">\n";

	    echo"          	   <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';

				    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>\n";

#	    echo" 		   $menusep<a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';

#				    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";

	    echo"              </div>\n";

	    echo"             </td>\n";

	    echo"             <td width=\"30%\">\n";

	    echo "<div class=\"mainheader\">$classtop_head $top_maximum</div>\n";

	    echo"             </td>\n";

	    echo"            </tr>\n";

	    echo"           </table>\n";

	    include ("classified_top.php");

	} else {



	    // Classified Main

	    echo"           <table align=\"center\" width=\"100%\">\n";

	    echo"            <tr>\n";

	    echo"             <td>\n";

	    echo"              <div class=\"mainmenu\">\n";

	    echo" 		   <a href=\"classified.php?choice=add&catid=$catid&subcatid=$subcatid\" onmouseover=\"window.status='$class_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link1</a>$menusep\n";

	    echo" 		   <a href=\"classified.php?choice=search&catid=$catid&subcatid=$subcatid\" onmouseover=\"window.status='$class_link3desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link3</a>$menusep\n";

	    echo" 		   <a href=\"classified.php?choice=my\" onmouseover=\"window.status='$class_link2desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link2</a>$menusep\n";

	    echo" 		   <a href=\"classified.php?choice=fav\" onmouseover=\"window.status='$class_link4desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link4</a>\n";

	    if ($catnotify) {

		echo" 		   $menusep<a href=\"classified.php?choice=notify\" onmouseover=\"window.status='$class_link5desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link5</a>\n";

	    }

	    echo"              </div>\n";

	    echo"             </td>\n";

	    echo"             <td width=\"20%\">\n";

	    echo"              <div class=\"mainheader\">$classified_head</div>\n";

	    echo"             </td>\n";

	    echo"            </tr>\n";

	    echo"           </table>\n";



	    if ($catid && $subcatid) {

		include ("classified_ads_show.php");

	    } elseif ($sqlquery) {

		include ("classified_results.php");

	    } else {

		include ("classified_cat_show.php");

	    }



	}

}

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";



#  PLEASE DO NOT REMOVE OR EDIT THIS COPYRIGHT-NOTICE !!! THANKS !!! ################################################

echo" <p><div class=\"footer\">phpBazar Ver. $bazar_version &copy 2001-".date("Y")." by SmartISoft<!--CyKuH [WTN]--></div>\n";

#####################################################################################################################



echo"</td>\n";





#  The Right-Side-Section

#################################################################################################

echo"<td valign=\"top\" align=\"left\">\n";

include ("classified_right.php");

echo"</td>\n";

echo"</tr>\n";

echo"</table>\n";





#  The Foot-Section

#################################################################################################

include ($FOOTER);

?>