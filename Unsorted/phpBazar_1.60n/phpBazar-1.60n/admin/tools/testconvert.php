<?

################################################################################################
#
#  project              : phpBazar
#  filename             : picturedisplay.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose              : Test ImageMagick convert-tool installation
#
#################################################################################################



    ####################################################################################

    ### !!! COPY THIS FILE TO YOUR BAZAR-DIR - AFTER SUCCESSFULL RUN - DELETE IT !!! ###

    ####################################################################################



require ("config.php");



if (!is_file($convertpath)) {

    echo "ERROR: Convertpath is wrong! Check configuration<br>";

    echo "try to search filesystem for convert ...<hr>";

    exec("locate convert",$a);

} else {

    echo "Convert is located at $convertpath<hr>";

    exec($convertpath." -v",$a);

}



foreach ($a as $v) {

   print "$v<br>";

}





?>