/*=================================================================
#	Copyright (c) 2002 by [ x-dev.de/x-gfx.de ]
#	Newsletter-Script by Robert Klikics [rob@x-dev.de]
#==================================================================
#	Website: 	http://www.x-gfx.de [english/german]      
#	Requires:	PHP 4.1.x ++
#	License: 	GPL/Free
#	[ Comments/Additions are welcome! ]
#==================================================================
#	Help: Please read the doc-files for further informations!
#==================================================================
#	File: readme.txt [info]
#===================================================================*/ 

### settings/setup ###

- edit conf.inc.php with your settings
- edit language.inc.php as you like
- edit header- and footer.inc.php [folder style] to your own style (or use my default-style)

### installing ###

- copy all folders/files to a new dir on your server
- chmod 666/777 the dir 'csv' and all files included
- open xNewsletter.php via browser and login to the admin-area with your pw

### usage ###

- to call the included subscribe/unsubscribe form open xNewsletter.php?act=email
- you can, of course, also use your own forms (take a look at sample_form.html)
- call xNewsletter.php and logon to send your newsletters
- you'll always get a bcc of any newsletter to your $cfg['mail'] - address so you don't need to subscribe the newsletter

### upgrade from xNewsletter v1.0 to v2.0 ###

- simple copy your old email_list.txt into the 'csv' dir

### security-information ###

- it's recommended to protect the dir 'csv' with a .htaccess file 
- sample of a .htaccess to disallow viewing the csv-files:

		AuthUserFile /dev/null
		AuthGroupFile /dev/null
		AuthName DenyViaWeb
		AuthType Basic
		
		
		<Limit GET>
		order allow,deny
		deny from all
		</Limit>

### hint ###

- visit our forums @ www.x-gfx.de in case of problems/bugs

###

Regards,
Robert