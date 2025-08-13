<?php

mail("$email", "Support Request Resolved - Ticket number: $ticketno",

"======================================================

Ticket Number: $ticketno

Time of request: $date

Username: $username

URL: $url

Subject: $subject

Description: 

$addnewdescription

======================================================",

"From: $supports_email");

?>