<?

#################################################################################################

#

#  project           : phpListPro

#  filename          : convert_from_autorankpro.php

#  last modified by  : Erich Fuchs

#  e-mail            : office@smartisoft.com

#  purpose           : Convert from AutoRankPro (Filebased) to phpListPro (DBbased)

#

#################################################################################################



#  Enter the FULL Path to your Site_Log

#################################################################################################

$site_log_file = "/httpd/www/smartisoft.com/products/listpro/admin/data/members.txt"; // Enter your PATH here





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

	$array[] .= "$line[0]|$line[1]|$line[2]|$line[3]|$line[4]|$line[5]|$line[6]|$line[7]|$line[8]|$line[9]|$line[10]|$line[11]|$line[12]|\n";

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

					  VALUES ('$id','','','$split[6]','$split[9]','$split[7]','$split[11]','$split[0]',

						    '$pass','$split[10]','468','60','5','1','1')")

					  or die ("Database Insert Error");

    echo "<b>ID:</b> $id <b>Site Name:</b> ".stripslashes($split[6])." converted successfull ...<br>\n";

    $count++;



}

echo "<br><b>$count Entry's converted successfull !!!</b>";

mysql_close();

?>
