<?


#################################################################################################
#
#  project           	: phpListPro
#  filename          	: lang.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Change the Language
#
#################################################################################################

#  Include Configs & Variables


#################################################################################################





$cookietime=time()+(3600*24*356);


setcookie("Language", "$language", "$cookietime", "$cookiepath"); // 1 Year


header("Location: $url");





?>
