<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: ./admin/restore.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Restore latest Backup
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("../config.php");



$file_path              = "$bazar_dir/$backup_dir";     // backup dir under admin-path with write-permission

$unpacker               = "/usr/bin/gunzip";      	// path to unpacker

$unpackeroptions        = "-f";              		// set the packer options

$mysql			= "/usr/bin/mysql";  		// path to mysql-client

$mysqloptions		= "-u".$db_user." -p".$db_pass." $database";

#$setrestoredate	= "01-01-2002";			// set if you wish to set the DB Date, if not latest will be used (default)



#################################################################################################



if ($setrestoredate) {

    $restoredate=$setrestoredate;

} else {

    // find last backup date

    for($i=0; $i<14; $i++) {

	$datestr=date("d-m-Y",mktime (0,0,0,date("m")  ,date("d")-$i,date("Y")));

        $testfile=$file_path."/ads_".$datestr.".sql";

        if (is_file($testfile) || is_file($testfile.".gz")) {$restoredate=$datestr;}

    }

}



$handle=opendir($file_path);

while (false!==($file = readdir($handle))) {

    $ext=substr($file,-17,17);

    $ext2=substr($file,-14,14);

    if ($ext==$restoredate.".sql.gz") {

	exec("$unpacker $unpackeroptions $file_path/$file");

	$output=exec("$mysql $mysqloptions < ".$file_path."/".substr($file,0,-3));

	if ($outout) {echo $output;}

    } elseif ($ext2==$restoredate.".sql") {

	$output=exec("$mysql $mysqloptions < ".$file_path."/".$file);

	if ($outout) {echo $output;}

    }

}

closedir($handle);



echo "         Database ($restoredate) restore finished\n";

?>