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
#	File: language.inc.php [language-data]
#===================================================================*/ 

/* - here you can set-up the printed textes for mail & script [please do _NOT_ use HTML]
 * - can can add a line-break with \n
**/

// misc. stuff
define(SUBSCRIBE_OK				,"Thanks, eMail added to the list ...");
define(DELETE_OK				,"eMail deleted from list");

// form stuff (xNewsletter.php?act=email)
define(SUBSCRIBE				,"Subscribe");
define(UNSUBSCRIBE				,"Unsubscribe");
define(FORM_SUBMIT				,"Submit");
define(FORM_VALUE				,"you@domain.com");	
define(INFO					,"Please enter your eMail and choose an option:");
define(BACK					,"back");

// messages
define(THANKS_SAVED				,"Thanks, your eMail was added to the list ...");
define(THANKS_DELETED				,"Thanks, your eMail was deleted from the list ...");				

// error-stuff
define(ALREADY_IN_LIST				,"Sorry, eMail already found in list ...");
define(NOT_IN_LIST				,"Sorry, eMail not found in list ...");
define(INVALID_EMAIL				,"Sorry, this eMail is invalid ...");
?>