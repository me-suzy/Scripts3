<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: member_chpass.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Change Member's Password
#
#################################################################################################



 require("library.php");



 $login_check = $authlib->is_logged();



 if (!$login_check) {

     header("Location: $url_to_start/main.php?status=2");

     exit;

 }



 if (!$password || !$password2) {

     header("Location: $url_to_start/members.php?status=3");

     exit;

 } else {

    $password=trim($password);

    $password2=trim($password2);

    $chpass = $authlib->chpass($login_check[1], $password, $password2);

    if ($chpass != 2) {

        $errormessage=rawurlencode($chpass);

	header("Location: $url_to_start/members.php?status=6&errormessage=$errormessage");

        exit;

     } else {

	$authlib->logout();

        header("Location: $url_to_start/main.php?status=5");

        exit;

     }

 }



?>