<?php

mail("$email", "Customer Invoice",

"Dear $firstname $surname,

Please find below your invoice for this billing period. You have till $duedate to pay the amount of \$$amount
otherwise we may, at our sole discretion, suspend your account until all monies owed are paid. If you are
paying by Credit Card or PayPal, you need not take any further action.

=====================================================
General Information
=====================================================

Merchant : $company
Domain: www.$domainname$domain
Date : $date

=====================================================
Invoice Information
=====================================================
 
Payment Due Date: $duedate
Payment Method : $payment
Billing cycle : $schedule
Amount Due: \$$amount

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

If you have any queries, or are unable to pay this invoice, please contact us at: $accounts_email

Regards, 
Admin 
$company - Accounts",

"From: $accounts_email");

?>