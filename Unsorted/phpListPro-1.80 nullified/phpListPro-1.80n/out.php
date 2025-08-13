<?
################################################################################################
#
#  project           	: phpListPro
#  filename          	: out.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Count out Links
#
#################################################################################################


#  Include Configs & Variables


#################################################################################################


require ("config.php");


srand((double) microtime() * 1000000);





mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");





if ($site=="random") {





    $sql = "SELECT id,site_addr FROM sites";


    if ($cat) {$sql .= " WHERE cat='$cat'";}


    $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


    $count = mysql_num_rows($query);


    if ($count>0) {


	if ($count>1) {$shift = rand(1,$count)-1;}


	if ($shift>0) {mysql_data_seek($query,$shift);}


        $result = mysql_fetch_row($query);


	$result[site_addr]=$result[1];


        $sql = "UPDATE sites SET hits=hits+1 WHERE id='$result[0]' ";


        mysql_db_query($database, $sql) or die(geterrdesc($sql));


    } else {


	$result[site_addr]=$url_to_start;


    }


} else {





    $sql = "SELECT id,site_addr FROM sites WHERE id='$site'";


    $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


    $result = mysql_fetch_array($query);





    $sql = "UPDATE sites SET hits=hits+1 WHERE id='$site' ";


    mysql_db_query($database, $sql) or die(geterrdesc($sql));


}





mysql_close();





if ($outexiturl) {


    if ($outexiturl[0]=="SELFLINK") {	// set to SELFLINK


	$doexiturl="$list_url/in.php?site=$result[id]";


    } else {  				// generate random link from outexiturl-array


	$count=count($outexiturl);


	if ($count>0) {


	    $shift = rand(0,$count);


	    $doexiturl=$outexiturl[$shift];


	}


    }


    if ($doexiturl) {


        echo "<script language=\"JavaScript\">{


	open(\"$doexiturl\", \"_blank\");


	location.href=\"$result[site_addr]\";


	self.focus();


	}</script></body>";


    } else {


        header("location: $result[site_addr]");


    }


} else {


    header("location: $result[site_addr]");


}





#  End


#################################################################################################


?>
