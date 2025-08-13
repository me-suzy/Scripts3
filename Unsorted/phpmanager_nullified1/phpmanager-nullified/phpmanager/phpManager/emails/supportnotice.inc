<?php

mail("$supports_email", "New Support Request - Ticket number: $ticketno",

"======================================================

Ticket Number: $ticketno

Time of request: $date

Username: $username

URL: $url

Subject: $subject

Description: $description

======================================================",

"From: $email");

?>