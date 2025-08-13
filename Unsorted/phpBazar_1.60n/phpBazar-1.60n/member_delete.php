<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: member_update.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Update Member's Data
#
#################################################################################################



require ("library.php");



$login_check = $authlib->is_logged();



$delete = $authlib->delete($login_check[1]);



if ($delete =="2") {

    $authlib->logout();

    header("Location: $url_to_start/main.php?status=7");

    } else {

    header("Location: $url_to_start/main.php?status=6");

    }

exit;



?>