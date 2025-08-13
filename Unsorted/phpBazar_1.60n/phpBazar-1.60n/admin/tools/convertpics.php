<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: convertpics.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: If ImageMagick's convert is installed later, this tool convert your current pics
#
#################################################################################################



require "config.php";



    ####################################################################################

    ### !!! COPY THIS FILE TO YOUR BAZAR-DIR - AFTER SUCCESSFULL RUN - DELETE IT !!! ###

    ####################################################################################



    ### AFTER SUCCESSFULL CONVERSION - BACKUP CURRENT PICTURES AND COPY OVER FROM TARGET-PATH ###



$spath=$bazar_dir."/images/userpics/";	   // SOURCE-PATH here are the pic's to convert :-)

$tpath=$bazar_dir."/images/userpics/tmp/"; // TARGET-PATH MUST BE DIFFERENT AND WITH WRITE PERMISSION (777)



$extmask= ".+\.jpg$|.+\.gif$|.+\.png$";    // all .gif and .jpg and .png





#  Get Entrys for current page

#################################################################################################



$handle=opendir($spath);

while ($file = readdir($handle)) {

#    if (eregi($extmask,$file) && !eregi("_+|-+",$file) ) {

	$retVal[count($retVal)] = $file;

#    }

}

closedir($handle);

$counter=0;

if (is_array($retVal)) {

 sort($retVal);

 while (list($key, $val) = each($retVal)) {

    if ($val != "." && $val != "..") {

	$path = str_replace("//","/",$spath.$val);

	$t_path = str_replace("//","/",$tpath."".$val);

	$ts_path = str_replace("//","/",$tpath."_".$val);

	if (is_file($path)) {

	    if (!is_file($t_path) && $convertpath && $convertpath!="AUTO") {  // create medium size pic

		exec($convertpath." -geometry ".$pic_res." -quality ".$pic_quality." ".$path." ".$t_path);

	    }

	    if (!is_file($ts_path) && $convertpath && $convertpath!="AUTO") {  // create small size pic

        	exec($convertpath." -geometry ".$pic_lowres." -quality ".$pic_quality." ".$path." ".$ts_path);

	    }

    	    $counter++;

	    echo "Picture $counter processed ($path)<br>\n";

	    flush();

	}

    }

 } //End while

 echo "<hr>$counter Pictures processed - check your Target-dir ($tpath) now ...\n";

}



# End of Page reached

#################################################################################################

?>