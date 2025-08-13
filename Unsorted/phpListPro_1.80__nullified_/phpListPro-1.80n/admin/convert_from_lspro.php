<?
#################################################################################################
#
#  project           : phpListPro
#  filename          : convert_from_lspro.php
#  last modified by  : Erich Fuchs
#  supplied by       : CyKuH [WTN]
#  purpose           : Convert from ListSitePro (Filebased) to phpListPro (DBbased)
#
#################################################################################################

#  Enter the FULL Path to your Site_Log

#################################################################################################

$site_log_file = "./data.file"; // Enter your PATH here





#  Include Configs & Variables

#################################################################################################

$returnpath="../";

require ($returnpath."config.php");





#  The Menu-Section

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



mysql_db_query($database, "DELETE FROM sites WHERE 1=1") or die ("Database Query Error");



$file = file($site_log_file);

$total = count($file);



if($total != 0){

    for($i = 0; $i < $total; $i++) {

	$line = explode("|", $file[$i]);

	$array[] .= "$line[0]|$line[1]|$line[2]|$line[3]|$line[4]|$line[5]|$line[6]|$line[7]|$line[8]|$line[9]|$line[10]|$line[11]|$line[12]|$line[13]|$line[14]|$line[15]|$line[16]|$line[17]|$line[18]\n";

    }

    rsort($array);

}



$id=time();



$count = 0;

for($i=0;$total > $i; $i++){

    $split = explode("|", $array[$i]);



    $split[6]=addslashes($split[6]);

    $split[7]=addslashes($split[7]);

    $id++;

    $pass=rand(0,10000000);



    mysql_db_query($database, "INSERT INTO sites (id, votes, hits, site_name, site_addr, site_desc, email, name,

						  password, banner_addr, banner_width, banner_height, rating,approved,emailapproved)

					  VALUES ('$split[12]','$split[0]','$split[1]','$split[6]','$split[8]','$split[7]','$split[5]','$split[4]',

						    '$split[11]','$split[9]','$split[14]','$split[13]','10','1','1')")

					  or die ("Database Insert Error");

    echo "<b>ID:</b> $split[4] <b>Site Name:</b> ".stripslashes($split[6])." converted successfull ...<br>\n";

    $count++;



}

echo "<br><b>$count Entry's converted successfull !!!</b>";

mysql_close();

?>
