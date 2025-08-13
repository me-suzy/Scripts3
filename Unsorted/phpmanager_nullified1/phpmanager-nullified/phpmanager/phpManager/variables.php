<?php

////////////////////////////////////////////////////////////////////////
//                                                                    //
//                - phpManager, Copyright 2002 Taysoft -              //
//                                                                    //
// All scripts contained within are protected by international        //
// copyright law. Any unauthorised copying or distribution of         //
// this software will be dealt with accordingly.                      //
//                                                                    //
// Subsequently, the copyright message must not be                    //
// removed from the software. A fee of US$200 is payable to           //
// remove the copyright message. Additionally, phpManager is          //
// distributed as a single domain license (Single IP). You will       //
// be required to purchase another license if you wish to install     //
// on another domain.                                                 //
// Please note: Removal of the copyright message does NOT give        //
// you distribution rights to the software. All you are paying        //
// for is to remove the copyright message to give a more professional //
// look for your clients.                                             //
//                                                                    //
////////////////////////////////////////////////////////////////////////

$rootdirectory = "";

include ("$rootdirectory/connect.php");

$sql = "SELECT * FROM variables";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$language = $myrow["language"];
$template = $myrow["template"];
$clientmanagerlogin_title = $myrow["clientmanagerlogin_title"];
$clientmanager_title = $myrow["clientmanager_title"];
$accountmanagerlogin_title = $myrow["accountmanagerlogin_title"];
$accountmanager_title = $myrow["accountmanager_title"];
$admin_email = $myrow["admin_email"];
$accounts_email = $myrow["accounts_email"];
$supports_email = $myrow["supports_email"];
$checkout_email = $myrow["checkout_email"];
$revecom_email = $myrow["revecom_email"];
$paypal_email = $myrow["paypal_email"];
$worldpay_email = $myrow["worldpay_email"];
$company = $myrow["company"];
$processor = $myrow["processor"];
$dateformat = $myrow["dateformat"];
$instId = $myrow["instId"];
$currency = $myrow["currency"];
$worldpayPW = $myrow["worldpayPW"];
$cp = $myrow["cp"];
$currencytype = $myrow["currencytype"];

if ($dateformat=="US"){

$dateformat = "%m/%d/%y";

} else {

$dateformat = "%d/%m/%y";

}

?>