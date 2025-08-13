<?php

mail("$email", "Account Activated",

"Dear $firstname $surname, thank you for choosing us to host your web site.

=====================================================
Your account information is below:
=====================================================
Client ID: $id
Package : $package
Username : $username
Password : $password
Website URL: http://www.$domainname$domain
IP Address: $ip
Home Root : /home/$username

=====================================================
Cpanel Instructions:
=====================================================

To connect to your account control panel please use
the following:

http://www.$domainname$domain/cpanel/

or

http://$ip/cpanel/

=====================================================
FTP Instructions:
=====================================================

You can begin uploading your files at:

ftp.$domainname$domain

or

$ip

User name and password are the same as the CPanel.

Files for public access (your website) should be 
uploaded to the public_html directory. Files and 
directories outside the public_html directory are
not accessible to the public. The www directory
is just a link to the public_html directory.

=====================================================
DNS Instructions:
=====================================================

If you have a domain name, you will need to process the DNS changes. 
Please change the domains nameservers to the following.

ENTER YOUR NAMESERVERS HERE

=====================================================
For Additional Support:
=====================================================

If you are in need of additional support regarding 
your account you have several options. We encourage
you to view our FAQ for answers to the most common 
questions.
If you can't find what you need in our FAQ or 
need other technical assistance, please email support 
at: $support_email

=====================================================
Other Account Information
=====================================================

Please let us know if any of the following information
is incorrect as SOON as possible.

Payment method: $payment
Billing cycle : $schedule

Personal Information:
Name : $firstname $surname
Address: $house, $street
$town
$country
$postcode
Phone : $telephone
E-mail : $email
-----------------------------
Regards,

Admin
$company - Accounts Department",

"From: $accounts_email");

?>