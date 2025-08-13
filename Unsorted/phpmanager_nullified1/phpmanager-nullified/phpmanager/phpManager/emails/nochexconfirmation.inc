<?php

mail("$email", "Order Confirmation",

"Dear $firstname $surname,

We have received your hosting order. Your new account will be setup with the following details that you provided:

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

Please click on the link below to transfer your payment of $price to our NOCHEX account:

$link

If payment has not been received within 7 days of this email, your order will be deleted from our database.

If you wish to change any of the above details, you may do so via our helpdesk once your account is setup.

Thank you for choosing $company as your host.

Regards,

Admin
$company- Accounts Department",

"From: $accounts_email");

?>