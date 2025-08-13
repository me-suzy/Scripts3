<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: lostpass.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Send lost pwd to Member
#
#################################################################################################



require ("library.php");



$lostpass = $authlib->lostpwd($email);



if ($lostpass == 2) {

    header("Location: $url_to_start/main.php?status=4");

    exit;

    } else {

    header("Location: $url_to_start/main.php?status=2");

    exit;

    }

?>