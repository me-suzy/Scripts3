<?
#################################################################################################
#
#  project           : phpBazar
#  filename          : vote_submit.php
#  last modified by  : Erich Fuchs
#  supplied by       : Reseting
#  nullified by	     : CyKuH [WTN]
#  purpose           : Submit a Vote
#
#################################################################################################



#  Include Configs & Variables

#################################################################################################

require ("library.php");



$login_check = $authlib->is_logged();



if ($login_check || $votefree) {



    if (!$vote) {

	header("Location: ".$source."?status=8");

	exit;

    } else {

	mysql_connect($server,$db_user,$db_pass) or died("Database Connect Error");

	if ($login_check) { $tmp=$login_check[1];}

	if (isbanned($tmp)) {

    	    // IP banned, Do nothing !!!

	    $errormessage=rawurlencode($error[27]);

	    header("Location: ".$source."?status=9&errormessage=$errormessage");

	    exit;

	} elseif ($vote && $phpBazar_voted && $vote_cookie_time) {

    	    // Cookie is set - Already voted, Do nothing !!!

	    $errormessage=rawurlencode($error[25]);

	    header("Location: ".$source."?status=9&errormessage=$errormessage");

	    exit;

        } elseif ($vote) {

    	    $query = mysql_db_query($database,"update votes set votes=votes+1 where id='$vote'") or died("Database Query Error");

	    if ($login_check) {

    		$query2 = mysql_db_query($database,"update userdata set votes=votes+1,lastvotedate=now() where id='$login_check[1]'") or died("Database Query Error");

	    } else {

		$query2 = "1";

	    }

    	    if(!$query || !$query2) {

		$errormessage=rawurlencode($error[24]);

		header("Location: ".$source."?status=9&errormessage=$errormessage");

		exit;

    	    }

	    if($vote && !$phpBazar_voted) {

		$cookietime=$timestamp+(3600*$vote_cookie_time);

		setcookie("phpBazar_voted","1", $cookietime, "$cookiepath");

    	    }



	    logging("0","$login_check[1]","$login_check[0]","VOTE: voted","");



	    header("Location: ".$source."?status=10");

	    exit;

	}

    }



} else {

    $errormessage=rawurlencode($error[28]);

    header("Location: ".$source."?status=9&errormessage=$errormessage");

    exit;

}

?>