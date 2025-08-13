<?php

mail("$email", "Customer Receipt",

"Dear $firstname $surname,

This email is to acknowledge that your payment for the amount of \$$amount has been received.

=====================================================
General Information
=====================================================

Merchant : $company
Date : $date

=====================================================
Order Information
=====================================================

Description : $package
Amount : \$$amount
Payment Method : $payment

=====================================================
Billing Information
=====================================================

Name : $firstname $surname
Address: $house, $street
$town
$country
$postcode
Phone : $telephone
E-mail : $email

Thank you for your business.

Regards,

Admin
$company - Accounts Department",

"From: $accounts_email");

?>