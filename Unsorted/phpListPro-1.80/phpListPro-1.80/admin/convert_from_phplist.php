<?

#################################################################################################

#

#  project           : phpListPro

#  filename          : convert_from_phpList.php

#  last modified by  : Erich Fuchs

#  e-mail            : office@smartisoft.com

#  purpose           : Convert from phpList (Filebased) to phpListPro (DBbased)

#

#################################################################################################



#  Enter the FULL Path to your Site_Log

#################################################################################################

$site_log_file = "/httpd/www/yourdomain.com/admin/site_log.txt"; // Enter your PATH here





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

	$line = explode("||", $file[$i]);

	if (strlen($line[0]) == 1) { $count = "000000" . $line[0]; }

	if (strlen($line[0]) == 2) { $count = "00000" . $line[0]; }

	if (strlen($line[0]) == 3) { $count = "0000" . $line[0]; }

	if (strlen($line[0]) == 4) { $count = "000" . $line[0]; }

	if (strlen($line[0]) == 5) { $count = "00" . $line[0]; }

	if (strlen($line[0]) == 6) { $count = "0" . $line[0]; }

	$array[] .= "$count||$line[1]||$line[2]||$line[3]||$line[4]||$line[5]||$line[6]||$line[7]||$line[8]||$line[9]||$line[10]||$line[11]||$line[12]||\n";

    }

    rsort($array);

}



$count = 0;

for($i=0;$total > $i; $i++){

    $split = explode("||", $array[$i]);



    $split[3]=addslashes($split[3]);

    $split[8]=addslashes($split[8]);



    mysql_db_query($database, "INSERT INTO sites (id, votes, hits, site_name, site_addr, site_desc, email, name,

						  password, banner_addr, banner_width, banner_height, rating,approved,emailapproved)

					  VALUES ('$split[2]','$split[0]','$split[1]','$split[3]','$split[4]',

						  '$split[8]','$split[6]','$split[7]','$split[5]','$split[9]',

						  '$split[10]','$split[11]','$split[12]','1','1')")

					  or die ("Database Insert Error");

    echo "<b>ID:</b> $split[2] <b>Site Name:</b> ".stripslashes($split[3])." converted successfull ...<br>\n";

    $count++;



    /* KEY

    $votes = $split[0]

    $hits = $split[1]

    $date = $split[2]

    $site_name = $split[3]

    $site_address = $split[4]

    $password = $split[5]

    $email_address = $split[6]

    $webmaster_name = $split[7]

    $site_description = $split[8]

    $banner_address = $split[9]

    $banner_width = $split[10]

    $banner_height = $split[11]

    $rating = $split[12]

    */

}

echo "<br><b>$count Entry's converted successfull !!!</b>";

mysql_close();

?>
