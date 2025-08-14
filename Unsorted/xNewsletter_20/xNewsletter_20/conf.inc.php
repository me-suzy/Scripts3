<?php
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
#	File: conf.inc.php [config]
#===================================================================*/ 

/* Please set-up these var's: */

// your admin-password
$cfg['pw']	= "test";

// path to the csv-file to store data
$cfg['file']	= "csv/email_list.txt";

// path to the csv-file with the mail-footer included
$cfg['ffile']	= "csv/mail_footer.txt";

// sender email [i.e. noreply@domain.com] */
$cfg['mail']	= "news@x-gfx.de";

// return-mail [for 'Return-Path']
$cfg['return']	= "noreply@x-gfx.de";

// URL of your page
$cfg['hp']	= "http://www.x-gfx.de";

// name of your page
$cfg['name']	= "x-gfx.de";

// full URL to xNewsletter.php
$cfg['url']	= "http://www.x-gfx.de/dev/xNL_20/xNewsletter.php";

?>