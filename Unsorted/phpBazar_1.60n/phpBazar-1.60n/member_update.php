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



require("library.php");



$login_check = $authlib->is_logged();



if ($homepage=="http://") {$homepage="";}

if (!memberfield("1","sex","","")) {$sex="n";}

$update = $authlib->edit($login_check[1], $sex, $newsletter, $firstname, $lastname, $address, $zip,

			$city, $state, $country, $phone, $cellphone, $icq, $homepage, $hobbys,

			$field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10);



if ($update != 2) {

        $errormessage=rawurlencode($update);

	header("Location: $url_to_start/members.php?choice=myprofile&status=6&errormessage=$errormessage");

	exit;

    } else {

	header("Location: $url_to_start/members.php?choice=myprofile&status=5");

	exit;

    }

?>