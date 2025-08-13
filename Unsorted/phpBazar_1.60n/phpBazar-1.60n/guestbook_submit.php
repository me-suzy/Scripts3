<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: guestbook_submit.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Submit Guestbook Data
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



$login_check = $authlib->is_logged();



if (!$in && !$delid) {

    header("Location: $url_to_start/guestbook.php?status=3");

    exit;

} elseif ($delid && $login_check[2]) {



    mysql_connect($server, $db_user, $db_pass) or died("Database Connect Error");

    mysql_db_query($database, "DELETE FROM guestbook WHERE id='$delid'") or died("Database Query Error");

    mysql_close();



    header("Location: $url_to_start/guestbook.php?status=12");

    exit;



} else {



    mysql_connect($server, $db_user, $db_pass) or died("Database Connect Error");



    if (isbanned($login_check[1])) {

	header("Location: $url_to_start/guestbook.php?status=11&errormessage=$error[27]");

        exit;

	}



    $add_date = $timestamp;



    $in = strip_array($in);



    $in[message] = encode_msg($in[message]);    // Add SQL compatibilty & Smilie Convert



    $in['http']    = str_replace("http://", "", $in['http']);   // Remove http:// from URLs



    if ($in['icq'] != "" && ($in['icq'] < 1000 || $in['icq'] > 999999999)) { died("Non-valid ICQ entry, if you do not have an icq account please leave blank."); }

    if (!eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$",$in['email']) && $in['email'] != "") { died("Non-valid Email entry, please enter your correct e-mail address or if you don't have one leave it blank."); }

    if (strlen($in['message']) < $limit["0"] || strlen($in['message']) > $limit["1"]) { died("Sorry, your message has to be between $limit[0] and $limit[1] characters."); }



    if ($in['email'] == "") { $in['email'] = "none"; }

    if ($in['icq'] == "") { $in['icq'] = 0; }

    if ($in['http'] == "") { $in['http'] = "none"; }

    if ($in['location'] == "0") { $in['location'] = "none"; }

    $in[browser] = $HTTP_USER_AGENT;



    mysql_db_query($database, "INSERT INTO guestbook (name, email, http, icq, message, timestamp, ip, location, browser)

    VALUES('$in[name]', '$in[email]','$in[http]','$in[icq]','$in[message]','$add_date', '$ip','$in[location]','$in[browser]')")

    or died("Database Query Error");



    if ($gb_notify) {

        @mail("$gb_notify","NOTIFY new Guestbook Entry","Name: $in[name]\nLocation: $in[location]\nE-Mail: $in[email]\nICQ: $in[icq]\nWWW: $in[http]\n\n$in[message]","From: $gb_notify");

    }



    logging("0","$login_check[1]","$login_check[0]","GB: Entry added","Name: $in[name] - Msg: $in[message]");



    mysql_close();



    header("Location: $url_to_start/guestbook.php?status=12");

    exit;

}



?>