<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	$to 		= $EMAIL;
	$from 	= "From: <$TPL_sender_mail>\n";
	$subject	= "Ihr Gebot";
	$message = "Guten Tag $TPL_username,
	
Ihr Gebot in Höhe von 
für $item_title ( $item_description )

Es lautet: $NEWPASSWD

Bitte melden Sie sich auf <#c_siteurl>
mit Ihrem aktuellen Benutzernamen und Ihrem neuen Passwort an und ändern Sie Ihr neues
Passwort umgehend in ein von Ihnen ausgewähltes.

Weiterhin viel Spass und Erfolg beim Bieten auf <#c_sitename#>";
?>