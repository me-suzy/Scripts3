<?php

mail("$email", "RE: Support Request - Ticket number: $ticketno",

"You may view the status of your request at anytime by logging into your Account Manager and entering your ticket number in the support section.

======================================================

Ticket Number: $ticketno

Time of request: $date

Username: $username

URL: $url

Subject: $subject

Description: 

$addnewdescription

======================================================

Regards,

Support Team
$company - Support",

"From: $supports_email");

?>