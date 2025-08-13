<?
#################################################################################################
#
#  project           : phpListPro
#  filename          : convert_from_custom.php
#  last modified by  : Erich Fuchs
#  supplied by       : CyKuH [WTN]
#  purpose           : Convert from custom List (Filebased) to phpListPro (DBbased)
#
#################################################################################################

#  Include Configs & Variables

#################################################################################################

$returnpath="../";

require ($returnpath."config.php");

?>

<html>

<head><title>CustomConvert</title></head>



<body><font face=Verdana>

<b>phpListPro-CustomConvert</b><p>

<font size=1>

<?



#  The Menu-Section

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



mysql_db_query($database, "DELETE FROM sites WHERE 1=1") or die ("Database Query Error");



$id=time();

$count=0;

$null="";



$pl_spath="./data/";

$handle=opendir($pl_spath);

while ($file = readdir($handle)) {

    if ($file!="." && $file!="..") {

            $retVal[count($retVal)] = $file;

    }

}

closedir($handle);



if (is_array($retVal)) {

  sort($retVal);

    while (list($key, $val) = each($retVal)) {





    $file = file($pl_spath.$val);

    $array = explode("|", $file[0]);



    $array[0]=addslashes($array[0]);

    $array[3]=addslashes($array[3]);

    $array[4]=addslashes($array[4]);



    $id++;

    $pass=rand(0,10000000);

    $sql="INSERT INTO sites (id, votes, hits, site_name, site_addr, site_desc, email, name,

						  password, banner_addr, banner_width, banner_height, rating, approved, emailapproved)

					  VALUES ('$id','$null','$null','$array[3]','$array[2]',

						  '$array[4]','$array[1]','$array[0]','$pass','$null',

						  '$null','$null','5','1','1')";

    mysql_db_query($database, $sql) or die ("Database Insert Error: $sql");



    echo "ID:$id SiteName:".stripslashes($array[3])." converted successfull ...<br>\n";

    $count++;



    }



}





echo "<br><b>$count Entry's converted successfull !!!</b>";

mysql_close();

?>



</font>

</body>

</html>
