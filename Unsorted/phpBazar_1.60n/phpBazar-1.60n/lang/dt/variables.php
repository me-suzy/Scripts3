<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: variables.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose	     	: German Language File
#  version           	: 1.59
#
#################################################################################################



$lang	= "Deutsch";

$menusep= " || ";

$lang_metatag   ="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">"; // Added in V1.55



$gender[m]      ="Mann"; // Added in V1.55

$gender[f]      ="Frau"; // Added in V1.55

$gender[a]      ="Paar M/F"; // Added in V1.55

$gender[b]      ="Paar F/F"; // Added in V1.55

$gender[c]      ="Paar M/M"; // Added in V1.55



$userlevel[0]	="Junior Mitglied";

$userlevel[1]	="Mitglied";

$userlevel[2]	="Senior Mitglied";

$userlevel[3]   ="Zahlendes Mitglied";

$userlevel[4]   ="Zahlendes Mitglied";

$userlevel[5]   ="Zahlendes Mitglied";

$userlevel[6]   ="Zahlendes Mitglied";

$userlevel[7]   ="Zahlendes Mitglied";

$userlevel[8]	="Senior Moderator";

$userlevel[9]	="Administrator";



$mail_msg[0] 	="$bazar_name Registration";

$mail_msg[1] 	="Willkommen, ";

$mail_msg[2] 	="Danke für die Registrierung bei $bazar_name!\nFolgende Informationen haben wir erhalten :\n\nUsername  : ";

$mail_msg[3] 	="Passwort  : ";

$mail_msg[4] 	="E-Mail    : ";

$mail_msg[5] 	="Geschlecht: ";

$mail_msg[6] 	="Du musst Deine Mitgliedschaft bestätigen, klick auf folgenden Link: ";

$mail_msg[7] 	="Dein Webmaster\n$url_to_start\n\nPS: Solltest Du dich nicht bei uns angemeldet haben, bitte ignoriere diese Nachricht.";

$mail_msg[8] 	="Hallo Webmaster, eine neue Registrierung :-) bei Bazar :\n\nUsername : ";

$mail_msg[9] 	="Bazar Registration Bestätigung";

$mail_msg[10]	="Hallo, ";

$mail_msg[11]	="DANKE für die Bestätigung Deiner Mitgliedschaft bei $bazar_name!\n\nDu kannst dich nun mit Deinem Usernamen und Deinem Passwort einloggen.\n\nDein Webmaster\n$url_to_start";

$mail_msg[12]	="Mitglieder Information";

$mail_msg[13]	="Hallo, ";

$mail_msg[14]	="Du hast Dein Passwort vergessen ?!?! hier sind die Informationen: \n\nUsername : ";

$mail_msg[15] 	="Dein Webmaster\n$url_to_start";

$mail_msg[16]	="E-Mail Änderung";

$mail_msg[17]	="Hallo, \n\nDu hast eine Änderung deiner E-Mail-Adresse angefordert.\nUm sicherzu gehen dass Deine neue E-Mail-Adresse stimmt, klick bitte auf den folgenden Link:";

$mail_msg[18]	="Danke\n\nDein Webmaster\n$url_to_start";

$mail_msg[19]   ="Benachrichtigung - Inserat Laufzeit abgelaufen";

$mail_msg[20]   ="Hallo, \n\ndas folgende Inserat ist in $timeoutnotify Tagen abgelaufen:\n";

$mail_msg[21]   ="Um die Laufzeit um $timeoutconfirm Tage zu verlängern, click:\n\n";

$mail_msg[22]   ="Danke\n\nDein Webmaster\n$url_to_start";

$mail_msg[23]   ="Benachrichtigung über neue WebMail"; // Added in V1.50

$mail_msg[24]   ="Hallo $toname,\n\ndu hast neue Mail in deinem WebMail-Eingang bei $bazar_name !\n\nLog dich bei $url_to_start ein und prüfe deine Mail\n\nDein Webmaster\n$url_to_start"; // Added in V1.50



$status_msg[0]	="Logout OK";

$status_msg[1]	="Login OK";

$status_msg[2]	="Name falsch";

$status_msg[3]	="Leeres Feld";

$status_msg[4]	="Gesendet";

$status_msg[5]	="Änderung OK";

$status_msg[6]	="Fehler";

$status_msg[7]	="Gelöscht";

$status_msg[8]	="Leere Abstimmung";

$status_msg[9]	="Fehler Abstimmung";

$status_msg[10]	="Abstimmung OK";

$status_msg[11]	="GB Fehler";

$status_msg[12]	="GB Speichern OK";

$status_msg[13]	="Gespeichert OK";



$error[0] 	="Passwords do not match each other";

$error[1] 	="Username is too short. Minimum is 3 valid characters.";

$error[2] 	="Username is too long. Maximum is 11 valid characters.";

$error[3] 	="Username contains invalid characters.";

$error[4] 	="Email address format is invalid.";

$error[5] 	="Password is too short. Minimum is 3 valid characters.";

$error[6] 	="Password is too long. Maximum is 20 valid characters.";

$error[7] 	="Password contains invalid characters.";

$error[8] 	="First- or LastName contains invalid characters.";

$error[9] 	="Field contains invalid characters.";

$error[10] 	="ICQ contains invalid characters.";

$error[11] 	="Gender can not be empty."; // Changed in V1.55

$error[12] 	="Username already exists.";

$error[13] 	="A user with that email already exists.";

$error[14] 	="Some fields were left empty.";

$error[15] 	="Temporary ID and Username combination incorrect, or account purged.";

$error[16] 	="Unknown database failure, please try later.";

$error[17] 	="Your login information was entered but due to an unknown error other details could not be filled in, so please login to your account and remove it immediately and then re-register.";

$error[18] 	="The flushing process was unsuccessful.";

$error[19] 	="No username corresponding to that email.";

$error[20] 	="Your reg. details couldnot be updated due to a database fault.";

$error[21] 	="Your password couldnot be updated due to a database fault.";

$error[22] 	="Your emails donot match.";

$error[23] 	="I think your current email and the email you entered for modification are same hence I can't change anything.";

$error[24] 	="Your vote couldnot be updated duw to a database fault";

$error[25] 	="You already voted, your vote couldnot be counted !!!";

$error[26] 	="ID and Username combination incorrect, or account purged.";

$error[27]	="I am sorry, you banned - please contact the Webmaster !!!";

$error[28]	="Sorry, Only Members can vote !!!";

$error[29]	="Kein Inserat gefunden !!!<br> Gib die gesuchte Inserat-Nummer ein !!!";

$error[30]	="Kein Inserat gefunden !!!<br> Versuch andere Suchbegriffe !!!";



$text_msg[0]    ="Danke für dein Inserat !";

$text_msg[1]	="Danke für dein Inserat, es wird in Kürze freigeschaltet";

$text_msg[2]    ="Eine E-Mail Bestätigung wurde an deine NEUE E-Mail-Adresse gesandt";

$text_msg[3]    ="Eine E-Mail Bestätigung wurde an deine E-Mail-Adresse gesandt";



#########################################################

# WARNING!! The $menu_link_desc variables below provide javascript messages for the

# browser status bar. DO NOT use any apostrophes in these text messages

# or they may cause an error in the browser !!!. Tip: Instead of trying to write

# "Reply to this member's ad" why not try "Reply to the ad of this member"

#  or even just "Reply to ad".

##########################################################



$menu_link1	="Home";

$menu_link1desc	="Zurück";

$menu_link1url	="main.php";

$menu_link2	="Bazar";

$menu_link2desc	="zu den Inseraten und Anzeigen";

$menu_link2url	="classified.php";

$menu_link3	="Bilder";

$menu_link3desc	="zu den Bildern";

$menu_link3url	="picturelib.php"; // "picturelib.php" for PicLib Option else use "pictures.php"

$menu_link4	="Links";

$menu_link4desc	="zu den ausgesuchten erotischen Link`s";

$menu_link4url	="links.php";

$menu_link5	="Forum";

$menu_link5desc	="zum Forum";

$menu_link5url	="forum.php";

$menu_link6	="Chat";

$menu_link6desc	="chatte mit anderen Mitgliedern";

$menu_link6url	="chat.php";

$menu_link7     ="WebMail"; // Changed in V1.50

$menu_link7desc ="Deine WebMail"; // Changed in V1.50

$menu_link7url  ="webmail.php"; // Changed in V1.50

$menu_link8	="Mitglieder"; // Changed in V1.55

$menu_link8desc	="Mitglieder (Suche, Ansicht, Details) & mein Profil (Einstellungen)"; // Changed in V1.55

$menu_link8url	="members.php";

$menu_link9	="Gästebuch";

$menu_link9desc	="zu unserem Gästebuch";

$menu_link9url	="guestbook.php";

$menu_link10	="Kontakt";

$menu_link10desc="treten in Kontakt mit uns ...";

$menu_link10url	="contact.php";



$main_head	="Home";

$classified_head="Bazar";

$classadd_head	="Hinzufügen";

$classedit_head	="Bearbeiten";

$classseek_head	="Suchen";

$classmy_head	="Eigene";

$classfav_head  ="Favoriten";

$classnot_head  ="Notify";

$forum_head	="Forum";

$stories_head	="Geschichten";

$pictures_head	="Bilder";

$links_head	="Links";

$members_head	="Mitglieder"; // Changed in V1.55

$guestbook_head	="Gästebuch";

$contact_head	="Kontakt";

$status_header	="Status";

$useronl_head   ="Users Online"; // Added in V1.50

$newmemb_head   ="Anmeldung"; // Added in V1.50

$webmail_head   ="WebMail"; // Added in V1.50

$classtop_head  ="Top"; // Added in V1.55



$lostpw_header	="Passwort verloren";

$lostpw_email 	="E-Mail";

$lostpw_button	="Schicken";



$login_header	="Login";

$login_username	="Username";

$login_password	="Passwort";

$login_member	="Mitglied#";



$user_online    ="User online"; // Added in V1.50

$users_online   ="Users online"; // Added in V1.50

$useronl_uname  ="Username"; // Added in V1.50

$useronl_ip     ="IP-Adresse"; // Added in V1.50

$useronl_time   ="Zeit"; // Added in V1.50

$useronl_page   ="Seite"; // Added in V1.50

$useronl_guest  ="Gast"; // Added in V1.50



$nav_prev       ="Gehe zur vorigen Seite"; // Added in V1.50

$nav_next       ="Gehe zur nächsten Seite"; // Added in V1.50

$nav_gopage     ="Gehe zu dieser Seite"; // Added in V1.50

$nav_actpage    ="Diese Seite"; // Added in V1.50



$memf_username  ="Username (Nick)"; // Added in V1.50

$memf_email     ="E-Mail"; // Added in V1.50

$memf_level     ="Level"; // Added in V1.50

$memf_votes     ="Stimmen"; // Added in V1.50

$memf_lastvote  ="Datum letzte Abstimmung"; // Added in V1.50

$memf_ads       ="Inserate"; // Added in V1.50

$memf_lastad    ="Datum letztes Inserat"; // Added in V1.50

$memf_password  ="Passwort"; // Added in V1.50

$memf_password2 ="Bestätige Password"; // Added in V1.50

$memf_sex       ="Geschlecht"; // Added in V1.50

$memf_newsletter="Newsletter"; // Added in V1.50

$memf_firstname ="Vorname"; // Added in V1.50

$memf_lastname  ="Nachname"; // Added in V1.50

$memf_address   ="Adresse"; // Added in V1.50

$memf_zip       ="PLZ"; // Added in V1.50

$memf_city      ="Stadt"; // Added in V1.50

$memf_state     ="Staat"; // Added in V1.50

$memf_country   ="Land"; // Added in V1.50

$memf_phone     ="Telefon"; // Added in V1.50

$memf_cellphone ="Mobil"; // Added in V1.50

$memf_icq       ="ICQ"; // Added in V1.50

$memf_homepage  ="Homepage"; // Added in V1.50

$memf_hobbys    ="Hobbys"; // Added in V1.50

$memf_field1    ="Field1"; // Added in V1.50

$memf_field2    ="Field2"; // Added in V1.50

$memf_field3    ="Field3"; // Added in V1.50

$memf_field4    ="Field4"; // Added in V1.50

$memf_field5    ="Field5"; // Added in V1.50

$memf_field6    ="Field6"; // Added in V1.50

$memf_field7    ="Field7"; // Added in V1.50

$memf_field8    ="Field8"; // Added in V1.50

$memf_field9    ="Field9"; // Added in V1.50

$memf_field10   ="Field10"; // Added in V1.50



$webmail_inbox  ="Eingang"; // Added in V1.50

$webmail_sent   ="Gesendet"; // Added in V1.50

$webmail_trash  ="Gelöscht"; // Added in V1.50

$webmail_from   ="Von"; // Added in V1.50

$webmail_to     ="An"; // Added in V1.50

$webmail_date   ="Datum"; // Added in V1.50

$webmail_subject="Betrifft"; // Added in V1.50

$webmail_message="Nachricht"; // Added in V1.50

$webmail_attach ="Anhang"; // Added in V1.50

$webmail_reply  ="Beantworte diese Mail"; // Added in V1.50

$webmail_del    ="Lösche diese Mail (Ordner [Gelöscht])"; // Added in V1.50

$webmail_tdel   ="Lösche diese Mail vom Ordner [Gelöscht]"; // Added in V1.50

$webmail_tundel ="Diese Mail wiederherstellen"; // Added in V1.50

$webmail_sdel   ="Lösche diese Mail vom Ordner [Gesendet]"; // Added in V1.55



#########################################################

# WARNING!! The $xxxx_link_desc variables below provide javascript messages for the

# browser status bar. DO NOT use any apostrophes in these text messages

# or they may cause an error in the browser !!!. Tip: Instead of trying to write

# "Reply to this member's ad" why not try "Reply to the ad of this member"

#  or even just "Reply to ad".

##########################################################



$logi_link1	="Passwort verloren";

$logi_link1desc	="Passwort vergessen ??? Wir senden Dir dein Passwort";

$logi_link2	="Anmeldung Gratis";

$logi_link2desc	="Melden Dich GRATIS als neues Mitglied an !!!";



$gb_link1	="Hinzufügen";

$gb_link1desc	="Füge einen neuen Eintrag hinzu";

$gb_link1head	="Gästebuch Eintrag hinzufügen";

$gb_pages	="Seiten:";

$gb_name	="Name";

$gb_comments	="Kommentar";

$gb_location	="Lokation: ";

$gb_posted	="eingetragen: ";



$gbadd_name	="Name :";

$gbadd_location	="Lokation :";

$gbadd_email	="E-Mail :";

$gbadd_url	="URL :";

$gbadd_icq	="ICQ :";

$gbadd_msg	="Nachricht :";



$vote_vote	="Stim.";

$vote_answer	="Antwort";

$vote_button	="Stimmen";



$memb_link1	="E-Mail ändern";

$memb_link1desc	="E-Mail Adresse ändern";

$memb_link2	="Passwort ändern";

$memb_link2desc	="Passwort ändern";

$memb_link3	="Löschen";

$memb_link3desc	="Mitgliedschaft löschen";



$memb_newvalid  ="(muss gültig sein)"; // Added in V1.50

$memb_newterms  ="Ich habe die Nutzungsbedingungen gelesen und akzepiert !"; // Added in V1.50

$memb_newsubmit ="Registieren"; // Added in V1.50

$memb_newpublic ="(öffentlich sichtbar)"; // Added in V1.55



$memb_detdeleted="gelöscht"; // Added in V1.55

$memb_detdetails="Details"; // Added in V1.55

$memb_detuser	="Username"; // Added in V1.55

$memb_detonl	="Username"; // Added in V1.55

$memb_detads	="Ins"; // Added in V1.55

$memb_detvot	="Abs"; // Added in V1.55

$memb_detmail	="Mail"; // Added in V1.55

$memb_deticq	="ICQ"; // Added in V1.55

$memb_deturl	="URL"; // Added in V1.55



$members_link   ="Mitglieder"; // Added in V1.55

$members_link_desc="Zurück zum Mitglieder Menü"; // Added in V1.55

$members_link1  ="Suche"; // Added in V1.55

$members_link1desc="Suche nach Mitgliedern"; // Added in V1.55

$members_link2  ="Mein Profil"; // Added in V1.55

$members_link2desc="mein Profil (Einstellungen)"; // Added in V1.55



$myprofile_head ="Mein Profil"; // Added in V1.55

$memberseek_head="Mitglieder Suche"; // Added in V1.55

$memberdet_head ="Mitglieder Details"; // Added in V1.55

$memberads_head ="Mitglieder Inserate"; // Added in V1.55



$class_link1	="Hinzufügen";

$class_link1desc="Fügen Deine Inserate und Anzeigen hinzu";

$class_link2	="Eigene";

$class_link2desc="Anzeigen, Ändern und Löschen Deiner eigenen Inserate";

$class_link3	="Suchen";

$class_link3desc="Suche Inserate und Anzeigen in der Kategorie";

$class_link4    ="Favoriten";

$class_link4desc="Deine Favoriten";

$class_link5    ="Notify";

$class_link5desc="Deine Benachrichtigungen";

$class_link	="Bazar";

$class_link_desc="Zurück zum Bazar Hauptmenü";



$ad_pages	="Seiten:";

$ad_from	="von Mitglied";

$ad_date	="um";

$ad_home	="Bazar";

$ad_sendemail	="Sende eine Antwort an den Inserenten per E-Mail";

$ad_sendlink	="Sende dieses Inserat (als URL-Link) einem Freund per E-Mail";

$ad_icq		="Sende eine ICQ-Nachricht an den Inserenten";

$ad_location	="Lokation: ";

$ad_noloc	="keine";

$ad_text	="Text";

$ad_picwin	="Grosses Bild";

$ad_enlarge	="Bild vergrössern";

$ad_print	="Dieses Inserat drucken";

$ad_favorits    ="Dieses Inserat zu den Favoriten hinzüfugen";

$ad_nr		="Inserat#: ";

$ad_gotourl     ="Homepage";

$ad_stat        ="Statistik: gesehen/beantwortet";

$ad_att         ="Dokument(e)"; // Added in V1.50

$ad_new         ="Neues Inserat innerhalb der letzten $show_newicon Tage"; // Added in V1.50

$ad_rating      ="Inserat Bewertung"; // Added in V1.50

$ad_yes         ="Ja"; // Added in V1.50

$ad_no          ="Nein"; // Added in V1.50

$ad_member      ="Details des Inserenten"; // Added in V1.55



$adadd_submit	="Eingabe";

$adadd_user	="Username :";

$adadd_ip  	="IP-Adresse :";

$adadd_cat 	="Kategorie :";

$adadd_subcat	="Subkategorie :";

$adadd_dur	="Laufzeit :";

$adadd_durweeks	="Wochen";

$adadd_durdays  ="Tage";

$adadd_loc	="* Lokation :";

$adadd_head	="* Beschreibung :";

$adadd_text	="* Inserat-Text :";

$adadd_selicon	="Wähle :";

$adadd_fieldend =" :";

$adadd_pic	="Bild :";

$adadd_picnos	="keine Soderzeichen";

$adadd_submitone="[Nur einmal klicken]";

$adadd_att      ="Dokument :"; // Added in V1.50

$adadd_delatt   ="(löschen)"; // Added in V1.50

$adadd_attnos   ="nur .pdf .doc .txt erlaubt"; // Added in V1.50

$adadd_forceadd ="<center><br><br>Danke für deine Anmeldung.<br><br>

                    Plaziere nun dein Inserat.<br>

                    Wähle nun deine Kategorie und bestätige mit 'Eingabe'.</center>"; // Added in V1.57

$adadd_pretext 	="<center><br><br>Plaziere nun dein Inserat.<br>

                    Wähle nun deine Kategorie und bestätige mit 'Eingabe'.</center>"; // Added in V1.59



$adseek_adnr	="Inserat# :";

$adseek_submit	="Eingabe";

$adseek_cat 	="Kategorie :";

$adseek_subcat	="Subkategorie :";

$adseek_all	="Alle";

$adseek_loc	="Lokation :";

$adseek_text	="Textsuche :";

$adseek_submitone="[Nur einmal klicken]";

$adseek_simple	="Einfache Suche";

$adseek_adv	="Erweiterte Suche";

$adseek_result	="Suchergebnisse";

$adseek_sort	="sortieren nach :";

$adseek_pic     ="nur mit Bildern :"; // Added in V1.50

$adseek_att     ="nur mit Dokumente :"; // Added in V1.50



$admy_edit	="Änder diese Anzeige";

$admy_delete	="Lösche diese Anzeige";

$admy_member	="Mitglied: ";



$admydel_head	="Inserat löschen";

$admydel_msg	="Willst Du dieses Inserat wirklich löschen ???";

$admydel_submit ="Löschen";

$admydel_done	="Dein Inserat ist nun gelöscht !!!";



$adfav_delete   ="Lösche diese Anzeige von den Favoriten";

$adnot_delete   ="Lösche diese Kategorie von den Notifys";



$notifydel_head	="Kategorie löschen";

$notifydel_msg	="Willst Du diese Kategorie wirklich von den Notifys löschen ???";

$notifydel_done	="Diese Kategorie ist nun von den Notifys gelöscht !!!";

$notify_head	="Notify";

$notify_exist 	="Die Kategorie ist schon als Notify gespeichert.";

$notify_done    ="Die Kategorie wurde in den Notifys gespeichert.";

$notify_add     ="Füge diese Kategorie den Notifys hinzu";



$sm_mailhead	="E-Mail an Inserent senden";

$sm_linkhead	="E-Mail an einen Freund";

$sm_friendhead  ="Unsere URL-Adresse einem Freund senden";

$sm_friendrefx  ="sendet dir die folgende URL-Adresse: ";

$sm_fromname	="von Name :";

$sm_fromemail	="von E-Mail :";

$sm_toname	="an Name :";

$sm_toemail	="an E-Mail :";

$sm_text	="Nachricht :";

$sm_subject	="Betreff :";

$sm_submit	="Senden";

$sm_cc		="Kopie";

$sm_afriend     ="Ein Freund";

$sm_anonym      ="Anonym";

$sm_friendref	="- Freund benachrichtigen";

$sm_answer	="- beantworte Inserat#";

$sm_systext	="sendet Dir den folgenden Link: ";

$sm_emailheader	="";

$sm_emailfooter	="\n\n-------------------------------\ngesendet von $bazar_name @ $HTTP_HOST";



$ar_adid        ="Inserat Nr.:"; // Added in V1.50

$ar_rating      ="Bewertung :"; // Added in V1.50

$ar_ratingcount ="Stimmen :"; // Added in V1.50

$ar_submit      ="Bewerten"; // Added in V1.50

$ar_already     ="Du hast schon für dieses Inserat gestimmt !"; // Added in V1.50



$msghead_error  ="Fehler";

$msghead_message="Nachricht";



$favorits_header="Favoriten";

$favorits_done  ="Das Inserat wurde erfolgreich zu den Favoriten hinzugefügt.";

$favorits_exist ="Das Inserat ist schon als Favorit gespeichert.";

$favorits_del   ="Das Inserat wurde von den Favoriten gelöscht.";



$location_sel	="----- Bitte wählen -----";

$back		="Zurück";

$done		="Fertig";

$close		="Schliessen";

$submit		="Eingabe";

$update         ="Speichern"; //Added in V1.50

$smiliehelp	="SmilieHilfe";



$footer_fav     ="Zu Favoriten hinzufügen";

$footer_terms   ="Nutzungsbestimmungen";



$cat_new        ="Neue Inserate inerhalb der letzten $show_newicon Tage"; // Added in V1.55

$cat_pass       ="Zugang nur mit gültigem Passwort"; // Added in V1.55

$mess_noentry   ="<br><br><br><br><br><center>Keine Datenbank-Einträge in dieser Ansicht !</center>"; // Added in V1.55

$pass_text      ="<br><br><br><br><br><center>Diese Ansicht ist nur mit einem gültigen Passwort möglich !</center>"; // Added in V1

$memb_notenabled="<br><br><br><br><br><center>Diese Ansicht ist nicht möglich !</center>"; // Added in V1.55

$pass_head      ="Passwort"; // Added in V1.55



#################################################################################################

#

# End

#

#################################################################################################

?>