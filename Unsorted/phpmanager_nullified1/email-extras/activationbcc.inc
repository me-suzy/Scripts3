<?php

mail("$accounts_email", "Account Activation Record",

"A hosting account has been activated for $firstname $surname.

=====================================================
Account Information:
=====================================================
Client ID: $id
Package : $package
Username : $username
Password : $password
Website URL: http://www.$domainname$domain
IP Address: $ip
Home Root : /home/$username

=====================================================
Account Control Panel (phpManager) Instructions:
=====================================================

To connect to your account control panel please use
the following:

https://reenee.net/Management/clients/index.php

=====================================================
Cpanel Instructions:
=====================================================

In addition to being able to access CPanel directly from your Account
CPanel, you can connect to CPanel (your domain control panel) as follows:

http://www.$domainname$domain/cpanel/

or

http://$ip/cpanel/

Note:  Until your domain propagates, you can access CPanel at:

http://www.reenee.net/~$username

=====================================================
FTP Instructions:
=====================================================

There are several ways to upload files to your site:

You can begin uploading your files at:

ftp.$domainname$domain

or

$ip

User name and password are the same as the CPanel.

Files for public access (your website) should be uploaded to the
public_html directory. Files and directories outside the
public_html directory are not accessible to the public. The www
directory is just a link to the public_html directory.

Note:  If your account does not have a dedicated IP, you can still upload
files via ftp, by logging in your name-based FTP account.  (Remember to
format login name this way:  user@domain.com not just username.

Alternatively, you can log into your CPanel, click on File Manager and
upload files from there.

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
$town, $state $postcode
$country
Phone : $telephone
E-mail : $email
-----------------------------
Regards,

Admin
$company - Accounts Department",

"From: $accounts_email");

?>