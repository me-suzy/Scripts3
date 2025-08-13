<?php

mail("$accounts_email", "Final Notice Record",

"A final notice has been sent to $firstname $surname, as follows:

Dear $firstname $surname:

Merchant : $company
Domain: www.$domainname$domain
Payment Method : $payment
Billing cycle : $schedule
Amount Past Due: \$$amount

This is to advise you that to date we have not received the payment due on your hosting account.  Your account has been suspended pending receipt of the overdue payment.  Your web site is no longer online with us.  As a courtesy, we will extend you a 3-day grace period within which to pay the overdue \$$amount.  If the amount overdue remains unpaid at conclusion of the 3-day grace period, your account will be automatically cancelled.

Should you decide after your account has been cancelled that you wish to have your account reinstated, you will have to pay an additional \$25.00 fee before we allow you to host again with us.  You will also be required to go through the order process as a new client, subject to the current posted hosting fees and payment methods of $company.  This means you may have to pay a higher hosting fee ongoing than the fee you currently pay, since our prices are subject to change from time to time.  Our accepted payment methods may from time to time be revised as well.

We are sorry it is necessary to take these measures.  We hope you will send us your overdue hosting fee and that $company can continue to be of service to you.

If you have any questions, please contact us.

-----------------------------
Regards,

Admin
$company - Accounts Department",

"From: $accounts_email");

?>