<?PHP
###########################################
#-----------Users login system------------#
###########################################
/*=========================================\
Author      :  Mohammed Ahmed(M@@king)    \\
Version     :  1.0                        \\
Date Created:  Aug 20  2005               \\
----------------------------              \\
Last Update:   August 22 2005             \\
----------------------------              \\
Country    :   Palestine                  \\
City       :   Gaza                       \\
E-mail     :   m@maaking.com              \\
MSN        :   m@maaking.com              \\
AOL-IM     :   maa2pal                    \\
WWW        :   http://www.maaking.com     \\
Mobile/SMS :   00972-599-622235           \\
                                          \\
===========================================\
------------------------------------------*/
if (eregi("footer.php", $_SERVER['SCRIPT_NAME'])) {
    Header("Location: index.php"); die();
}

echo "</td>
	</tr>
	<tr>
		<td class=\"footer\" height=20>
                <map name=\"mapFooter\">
		<area href=\"http://www.maaking.com/\" shape=\"rect\" coords=\"313, 6, 421, 19\">
		</map>

                <img src=\"images/footer.gif\" border=\"0\" usemap=\"#mapFooter\"></td>
	</tr>
</table>";
//Script by: <a href=\"http://www.maaking.com/\">maaking.com</a>
?>
