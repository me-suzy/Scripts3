<?


#################################################################################################


#


#  project           	: phpListPro


#  filename          	: menu.inc


#  last modified by  	: Erich Fuchs


#  e-mail            	: office@smartisoft.com


#  purpose           	: Show the menu ....


#


#################################################################################################








#  The Main-Section


#################################################################################################


echo" <table class=\"menu\" align=\"center\">\n";


echo"   <tr>\n";


echo"    <td>\n";


echo"     <a href=\"addsite.php$catlink\" onclick=\"exit=false\" onmouseover=\"window.status='$lang[addsitedesc]'; return true;\" onmouseout=\"window.status=''; return true;\">$lang[addsite]</a>\n";


echo"     $menu_separator<a href=\"editsite.php$catlink\" onclick=\"exit=false\" onmouseover=\"window.status='$lang[editsitedesc]'; return true;\" onmouseout=\"window.status=''; return true;\">$lang[editsite]</a>\n";


echo"     $menu_separator<a href=\"help.php$catlink\" onclick=\"exit=false\" onmouseover=\"window.status='$lang[helpsitedesc]'; return true;\" onmouseout=\"window.status=''; return true;\">$lang[helpsite]</a>\n";


echo"    </td>\n";


echo"   </tr>\n";


echo" </table>\n";





// Spacer Table


echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"10\" height=6>\n";


echo"   <tr><td>\n";


echo"   </td></tr>\n";


echo" </table>\n";





?>
