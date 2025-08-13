<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: menu.inc
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Show the menu ....
#
#################################################################################################





#  The Main-Section

#################################################################################################



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_menu\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

echo"          <center>\n";

echo"          <a href=\"$menu_link1url\" onmouseover=\"window.status='$menu_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link1</a>\n";

if ($menu_link2url) {

echo"          $menusep<a href=\"$menu_link2url\" onmouseover=\"window.status='$menu_link2desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link2</a>\n";}

if ($menu_link3url) {

echo"          $menusep<a href=\"$menu_link3url\" onmouseover=\"window.status='$menu_link3desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link3</a>\n";}

if ($menu_link4url) {

echo"          $menusep<a href=\"$menu_link4url\" onmouseover=\"window.status='$menu_link4desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link4</a>\n";}

if ($menu_link5url) {

echo"          $menusep<a href=\"$menu_link5url\" onmouseover=\"window.status='$menu_link5desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link5</a>\n";}

if ($menu_link6url) {

echo"          $menusep<a href=\"$menu_link6url\" onmouseover=\"window.status='$menu_link6desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link6</a>\n";}

if ($menu_link7url) {

echo"          $menusep<a href=\"$menu_link7url\" onmouseover=\"window.status='$menu_link7desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link7</a>\n";}

if ($menu_link8url) {

echo"          $menusep<a href=\"$menu_link8url\" onmouseover=\"window.status='$menu_link8desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link8</a>\n";}

if ($menu_link9url) {

echo"          $menusep<a href=\"$menu_link9url\" onmouseover=\"window.status='$menu_link9desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link9</a>\n";}

if ($menu_link10url) {

echo"          $menusep<a href=\"$menu_link10url\" onmouseover=\"window.status='$menu_link10desc'; return true;\" onmouseout=\"window.status=''; return true;\">$menu_link10</a>\n";}

echo"          </center>\n";

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";

include ("spacer.inc");

?>