<?

#################################################################################################
#
#  project              : phpBazar
#  filename             : ./admin/backup.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose              : Database-Backup
#
#################################################################################################





		    #########################################################

		    # !!! HINT: require mySQL Version 3.23.21 and above !!! #

		    #########################################################





#  Include Configs & Variables

#################################################################################################

require_once ("../config.php");



$file_path		= "$bazar_dir/$backup_dir";	// backup dir under admin-path with write-permission

$rotation 		= 7; 				// how many backups should stored (in days)

$packer			= "/usr/bin/gzip";		// path to packer, "" -> disabled

$packeroptions		= "-f -9";			// set the packer options



#################################################################################################



chdir($file_path);



$mysql_link = mysql_connect($server,$db_user,$db_pass) or die(mysql_error());

mysql_select_db($database,$mysql_link) or die(mysql_error());



$result = mysql_list_tables($database) or die(mysql_error());

$i = 0;

while ($i < mysql_num_rows($result)) {

    $tb_names[$i] = mysql_tablename($result, $i);

    $tablename= $tb_names[$i];



    $backupfile = $file_path."/".$tablename."_".date("d-m-Y").".sql";

    $oldbackupfile=$tablename."_".date("d-m-Y",mktime (0,0,0,date("m")  ,date("d")-$rotation,date("Y"))).".sql.gz";



    if(file_exists($file_path."/".$oldbackupfile)) {

	unlink($file_path."/".$oldbackupfile);

    }



    $fd = fopen ($backupfile, "w");



    mysql_query("SET SQL_QUOTE_SHOW_CREATE = 0");

    $resultcr = mysql_query("SHOW CREATE TABLE $tablename") or die(mysql_error());

    $tmp= mysql_fetch_array($resultcr);

    mysql_free_result($resultcr);



    $entry ="# ----------------------------------------------\n";

    $entry.="# created by phpBazar $bazar_version\n";

    $entry.="# on ".date("M.j, Y H:i:s",time())."\n";

    $entry.="# Table: $tablename\n";

    $entry.="# PHP Version: ".phpversion()."\n";

    $entry.="# ----------------------------------------------\n\n";

    $entry.="# - Table Dump ---------------------------------\n\n";

    $entry.="DROP TABLE IF EXISTS $tablename;\n";

    $entry.=$tmp[1].";\n\n";

    $entry.="# - Data Dump ----------------------------------\n\n";



    fwrite($fd,$entry);



    $query = "select * from $tablename";

    $resultado=mysql_query($query,$mysql_link);

    while($row=mysql_fetch_row($resultado)) {

    	$cuantas=count($row);

    	$entry="INSERT into $tablename (";

    	for($x=0;$x<$cuantas;$x++)

    	{

			$entry.=mysql_field_name($resultado, $x);

			if ($x!=($cuantas-1))

			{

				$entry.=",";

			}

		}

    	$entry.=") VALUES (";



    	for($x=0;$x<$cuantas;$x++)

    	{

		$entry.="'".addslashes($row[$x])."'";

		if ($x!=($cuantas-1)) $entry.=",";

		}



    	$entry.=");";



    	fwrite($fd,$entry."\n");



    }



    $entry ="\n# - End Dump -----------------------------------\n\n";

    fwrite($fd,$entry);



    fclose($fd);



    if (is_file("$packer")) {exec("$packer $packeroptions $backupfile");}



    $i++;

}



echo "         Database backup finished\n";

?>

