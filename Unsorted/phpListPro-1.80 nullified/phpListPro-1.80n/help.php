<?

################################################################################################
#
#  project           	: phpListPro
#  filename          	: help.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Display HELP
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require("config.php");

include($helpheader);



echo "<table class=\"standard\"><tr><td>";

echo "<center><b>$lang[help]</b><hr></center>";

echo "</td></tr><td>\n";

echo "$lang[helpreplace1]<br>";

echo "$lang[helpreplace2]<p>";

echo "$lang[addplease]<p>";

help("XXXXXXXX");

echo "

</td></tr></table>

<p>

<FORM action=\"$list_url/list.php$catlink\" onclick=\"exit=false\" method=\"POST\">

<INPUT TYPE=\"submit\" name=\"Done_submit\" VALUE=\"$lang[done]\">

<p>

";



include($helpfooter);

?>
