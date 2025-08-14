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
if (eregi("header.php", $_SERVER['SCRIPT_NAME'])) {
    Header("Location: index.php"); die();
}

echo "<html>"
     ."<head>"
     ."<meta http-equiv=\"content-language\" content=\"en-us\">"
     ."<meta name=\"generator\" content=\"microsoft frontpage 6.0\">"
     ."<meta name=\"progid\" content=\"frontpage.editor.document\">"
     ."<meta name=\"author\" content=\"mohammed ahmed\">"
     ."<meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1256\">"
     ."<title>users login system bye maaking.com</title>"
     ."<link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\">"
     ."</head>"
     ."<script src=\"javascript.js\" type=\"text/javascript\">"
     ."</script>"
     ."<body topmargin=\"0\" leftmargin=\"10\" bgcolor=\"#cfcfcf\">";

echo "<table height=50% align=\"center\" border=\"0\" width=\"750\" id=\"table1\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fbfbfb\" bordercolor=\"#006ab0\">
        <tr>
		<td height=60><img src=\"images/logo.gif\"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
        <tr>
		<td valign=\"top\">";
?>
