<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: member_chemail.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Change Member's E-Mail
#
#################################################################################################





require("library.php");



$login_check = $authlib->is_logged();



if (!$login_check) {

    header("Location: $url_to_start/main.php?status=2");

    exit;

}



if (!$email || !$email2) {

    header("Location: $url_to_start/members.php?status=3");

    exit;

} else {

    $email=trim($email);

    $email2=trim($email2);

    $chemail = $authlib->chemail($login_check[1], $email, $email2);

    if ($chemail != 2) {

        $errormessage=rawurlencode($chemail);

	header("Location: $url_to_start/members.php?status=6&errormessage=$errormessage");

	exit;

    } else {

        $textmessage=rawurlencode($text_msg[2]);

        header("Location: $url_to_start/members.php?status=4&textmessage=$textmessage");

	exit;

    }



}



?>