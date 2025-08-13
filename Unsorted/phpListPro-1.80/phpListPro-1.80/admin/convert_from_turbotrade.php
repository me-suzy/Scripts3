<?

#################################################################################################

#

#  project           : phpListPro

#  filename          : convert_from_turbotrade.php

#  last modified by  : Erich Fuchs

#  e-mail            : office@smartisoft.com

#  purpose           : Convert from TurboTrade List (Filebased) to phpListPro (DBbased)

#

#################################################################################################



$data_path="/httpd/www/domain.com/listpro/admin/data/"; // !!! with trailing slash





#  Include Configs & Variables

#################################################################################################

$returnpath="../";

require ($returnpath."configtest.php");

?>



<html>

<head><title>TurboTrade CustomConvert</title></head>



<body><font face=Verdana>

<b>phpListPro-TurboTrade-CustomConvert</b><p>

<font size=1>

<?



#  The Menu-Section

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



mysql_db_query($database, "DELETE FROM sites WHERE 1=1") or die ("Database Query Error");



$id=time();

$count=0;

$null="";



$pl_spath=$data_path;

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





    $array = file($pl_spath.$val);

#    $array = explode("\n", $file[0]);



    $name=explode(".",$val);

    $name=addslashes($name[0]);



    $array[1]=addslashes(str_replace("\n", "", $array[1]));

    $array[2]=addslashes(str_replace("\n", "", $array[2]));

    $array[3]=addslashes(str_replace("\n", "", $array[3]));

    $array[4]=addslashes(str_replace("\n", "", $array[4]));

    $array[5]=addslashes(str_replace("\n", "", $array[5]));



    $id++;

    $pass=rand(0,10000000);

    $sql="INSERT INTO sites (id, votes, hits, site_name, site_addr,

			     site_desc, email, name, password, banner_addr,

			     banner_width, banner_height, rating, approved, emailapproved)

		   VALUES ('$id','$null','$null','$array[3]','$array[1]',

			    '$array[4]','$array[5]','$name','$array[2]','$null',

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
