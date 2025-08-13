<?php

mail("$accounts_email", "Customer Invoice Record",

"$firstname $surname has been invoiced as follows:

Please find below your invoice for this billing period.  Payment is due upon receipt.  If we have not received your payment by $duedate in the amount of \$$amount, our system will automatically suspend your account until all monies owed are paid.

If you are set up to pay automatic recurring payments by Credit Card or PayPal, you need not take any further action.

=====================================================
General Information
=====================================================

Merchant : $company
Domain: www.$domainname$domain
Date : $date

=====================================================
Invoice Information
=====================================================
 
Payment Due Upon Receipt
Payment Method : $payment
Billing cycle : $schedule
Amount Due: \$$amount

=====================================================
Billing Information
=====================================================

Name : $firstname $surname
Address: $house, $street
$town, $state $postcode
$country
Phone : $telephone
E-mail : $email

If you have any queries, or are unable to pay this invoice, please contact us at: $accounts_email

Regards, 
Admin 
$company - Accounts",

"From: $accounts_email");

?>