<?
#################################################################################################
#
#  project           	: phpListPro
#  filename          	: library.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Functions-Library File
#
#################################################################################################


#  Functions


#################################################################################################





if ($cat) {


    $catlink="?cat=$cat";


    $catlink2="&cat=$cat";


}





if ($search) {


    $searchlink="?search=$search";


    $searchlink2="&search=$search";


}





function geterrdesc($sql) {


    $error = mysql_error() . "<BR>\n Error caused by statement: $sql";


    return $error;


}





if (!$gateway) {$show_rating = false;}





function encrypt ($number,$password) {


    $random=rand(0,1629892229);


    $crypt=15278902349-strlen($password);


    $encrypted=$number+$crypt-$random."A".$random;


    return $encrypted;


}





function decrypt ($number,$password) {


    $array=explode("A",$number);


    $crypt=15278902349-strlen($password);


    $decrypted=$array[0]-$crypt+$array[1];


    return $decrypted;


}





function check_bad_words($string) {


    global $bad_words;





    for ($i=0; $i<count($bad_words); $i++) {


	if (strstr(strtoupper($string), strtoupper($bad_words[$i]))) {


            return true;


	}


    }


    return false;


}








function check_bad_ips($string) {


    global $bad_ips;





    for ($i=0; $i<count($bad_ips); $i++) {


	if (strstr($string,$bad_ips[$i])) {


            return true;


	}


    }


    return false;


}








function addslashesnew($string) {


    if (get_magic_quotes_gpc()==1) {


	return $string;


    } else {


	return addslashes($string);


    }


}





function getbannersize($url) {


    $fp = @fopen ($url, "r");


    if ($fp) {


        while (!feof($fp)) {


	    $contents .= fread($fp, 1000);


        }


	fclose($fp);





        $fsize=strlen($contents);


        if ($fsize>0) {


#	    print "filesize: $fsize";


            return $fsize;


	}


    }





    return false;





}





?>
