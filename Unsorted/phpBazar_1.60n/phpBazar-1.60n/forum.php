<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: forum.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Inteface to Forum
#
#################################################################################################



if (strstr (getenv('HTTP_USER_AGENT'), 'MSIE')) { 	// Internet Explorer Detection

    $frameheight="107";

} else {

    $frameheight="119";

}



#  Include Configs & Variables

#################################################################################################

require ("library.php");



$login_check = $authlib->is_logged();

if ((!$login_check && !$forumfreeread) || !$forum_enable) {



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



#  The Main-Section

#################################################################################################

echo"<td valign=\"top\" align=\"left\">\n";

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";



if (!$forum_enable && ($login_check || $forumfreeread)) {

    echo "<br><br><center><b>Forum is NOT enabled !!!</b></center>";

} else {

	include ("$language_dir/nologin.inc");

}



echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";

echo"</td>\n";





#  The Right-Side-Section

#################################################################################################

echo"<td valign=\"top\" align=\"left\">\n";

include ("right.inc");

echo"</td>\n";

echo"</tr>\n";

echo"</table>\n";



#  The Foot-Section

#################################################################################################

include ($FOOTER);





} else {



echo"

<html>

 <head>

 <title>$bazar_name - Forum</title>

 <link rel=\"stylesheet\" type=\"text/css\" href=\"$STYLE\">

 <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">

 <meta name=\"generator\" content=\"Manual\">

 <meta name=\"robots\" content=\"index, follow\">

 <meta name=\"revisit-after\" content=\"20 days\">

 </head>





<frameset rows=\"$frameheight,*\" frameborder=\"0\" border=\"0\" framespacing=\"0\">

<frame name=\"topFrame\" scrolling=\"NO\" noresize src=\"frametop.php\" target=\"mainFrame\" >

<frameset cols=\"*,$table_width_menu,*\" frameborder=\"0\" border=\"0\" framespacing=\"0\">

<frame name=\"mainFrameLeft\" src=\"frameblank.php\" scrolling=\"NO\" target=\"mainFrame\">

<frame name=\"mainFrame\" src=\"forum/index.php\" scrolling=\"auto\" target=\"mainFrame\">

<frame name=\"mainFrameRight\" src=\"frameblank.php\" scrolling=\"NO\" target=\"mainFrame\">

<noframes>

<body bgcolor=\"#000000\" text=\"#FFFFFF\" link=\"#FFFFFF\" vlink=\"#C0C0C0\" alink=\"#FF0000\">

<p align=\"center\"><font face=\"Verdana\" size=\"2\"><b>This Site's uses Frames, update your Browser !!!</b></font></p>

<p align=\"center\">&nbsp;</p>

</body>

</noframes>

</frameset>

</frameset>

";

}



?>