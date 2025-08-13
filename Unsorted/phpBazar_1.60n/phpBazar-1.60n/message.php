<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: message.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Show clear Text ErrorMessage in a popup-window
#
#################################################################################################

#  Include Configs & Variables

#################################################################################################

require ("library.php");



#  Main

#################################################################################################

echo "<html>\n";

echo " <head>\n";

echo "   <title>$msgheader</title>\n";

echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"$STYLE\">\n";

echo "$lang_metatag\n";

echo " </head>\n";



echo "<body bgcolor=\"$bgcolor\">\n";

echo "<div class=\"mainheader\">".strtoupper($msgheader)."</div>\n";

echo "<div class=\"maintext\"><br><center>\n";

echo "$msgheader : ".stripslashes($msg)."<br>\n";

echo "<br><form action=javascript:window.close() METHOD=POST>\n";

echo "<input type=submit value=$close></form></center>\n";

echo "</div></body></html>\n";

?>