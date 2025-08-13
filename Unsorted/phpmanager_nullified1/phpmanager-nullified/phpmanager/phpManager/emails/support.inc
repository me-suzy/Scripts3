<?php

mail("$email", "Support Request - Ticket number: $ticketno",

"Thank you for submitting your support request.

Your support request as detailed below has been received.

Although our dedicated support team will endeavour to resolve your support request as soon as possible, please allow upto 12 hours for a reply.

You may view the status of your request at anytime by logging into your Account Manager and entering your ticket number in the support section.

======================================================

Ticket Number: $ticketno

Time of request: $date

Username: $clientname

URL: $url

Subject: $subject

Description: $description

======================================================

Regards,

Support Team
$company - Support",

"From: $supports_email");

?>