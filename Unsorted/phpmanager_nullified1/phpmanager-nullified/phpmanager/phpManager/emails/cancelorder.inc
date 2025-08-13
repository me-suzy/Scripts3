<?php

mail("$email", "Order Cancellation",

"Dear $firstname $surname,

Your request for a $company hosting account has now been cancelled as we have not yet received your payment.

If you still wish to order one of our hosting accounts, please fill in the order form again.

Regards,

Admin
$company - Accounts Department",

"From: $accounts_email");

?>