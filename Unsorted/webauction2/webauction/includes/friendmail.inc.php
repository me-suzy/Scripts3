<?
  
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

$message = "To: $friend_name <$friend_email>
From: $sender_name <$sender_email>
Subject: Schau Dir diese Auktion an $SITE_NAME

".stripslashes($sender_comment)."

Auktion:
------------------------------------------------------------------
$item_description

Hier ist die Adresse:
$SITE_URL"."item.php?id=$id";

?>
