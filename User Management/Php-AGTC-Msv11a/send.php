<?php
// *************************************************************************************************
// Title: 		PHP AGTC-Membership system v1.1a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
//
include "config.php";

// DO NOT EDIT BELOW THIS LINE, UNLESS YOU KNOW WHAT YOU ARE DOING
if ($username == "" or $userpass == "" or $useremail == ""){$msg3=true;}

$email = $useremail; 

if (!isset($useremail)) 
echo "Error, Please re-send $username" ; 

$todayis = date("l, F j, Y, g:i a") ;

$subject = $emailSubject;

$message = " $todayis [EST] \n

From: $sendersName ($sendersEmail)\n
Comment: $sendersComment \n
You are now registered as a member you can now login ($serverFolder$loginPage)\n
This email was sent by an auto responder, you cannot reply to this email.

";

$from = "From: $sendersEmail";

if ($email != "") 
mail($email, $subject, $message, $from);

?>