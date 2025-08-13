<?php

mail("$email", "Account Cancellation",

"Dear $firstname $surname,

Your hosting account with $company has now been cancelled.

Thank you for using us as your host.

Regards,

Admin
$company - Accounts Department",

"From: $accounts_email");

?>