<?php

mail("$accounts_email", "Order Hosting",

"New Hosting Order:

Fullname: $firstname $surname
Address:
$house $street
$town
$country
$postcode
Telephone Number: $telephone
Email Address: $email
Domain: www.$domainname$domain
Hosting Package: $package
Schedule: $schedule
Payment Method: $payment
Client IP Number: $REMOTE_ADDR",

"From: $email");

?>