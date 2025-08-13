<?php

mail("$supports_email", "Amend Support Request - Ticket number: $ticketno",

"======================================================

Ticket Number: $ticketno

Username: $username

Subject: $subject

Description: 

$addnewdescription

======================================================",

"From: $email");

?>