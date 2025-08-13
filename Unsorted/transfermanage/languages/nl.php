<?
/***************************************************************************
 *                                   nl.php
 *                            -------------------
 *   begin                : Tuesday', Aug 15', 2002
 *   copyright            : ('C) 2002 Bugada Andrea
 *   email                : phpATM@free.fr
 *
 *   $Id: nl.php, v1.03 2002/08/28 23:53:50 perry Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
 ***************************************************************************/

$headerpage="include/header.htm";    // The optional header file (can absent)
$footerpage="include/footer.htm";    // The footer file (must present!)
$infopage="include/info.htm";        // The optional info file (can absent)

$charsetencoding="";                 // The encoding for national symbols (i.e. for cyryllic  must be "windows-1251")
$uploadcentercaption="PHP Advanced Transfer Manager";
$uploadcentermessage="phpATM";

$mess=array(
"0" => "",
"1" => "Januari",
"2" => "Februari",
"3" => "Maart",
"4" => "April",
"5" => "Mei",
"6" => "Juni",
"7" => "Juli",
"8" => "Augustus",
"9" => "September",
"10" => "Oktober",
"11" => "November",
"12" => "December",
"13" => "Vandaag",
"14" => "Gisteren",
"15" => "Bestandsnaam",
"16" => "Waardering",
"17" => "Grootte",
"18" => "Uploaded",
"19" => "Eigenaar",
"20" => "Upload Bestand",
"21" => "Lokaal Bestand",
"22" => "Bestands Beschrijving",
"23" => "Download",
"24" => "Volgorde",
"25" => "Home",
"26" => "Bestand",
"27" => "Print",
"28" => "Sluiten",
"29" => "Ga terug",
"30" => "Dit bestand is verwijderd",
"31" => "Kan bestand niet openen",
"32" => "Ga terug",
"33" => "Error uploading bestand!",
"34" => "Je moet een bestand selecteren",
"35" => "Terug",
"36" => "Het bestand",
"37" => "Is succesvol uploaded",
"38" => "Bstand met naam",
"39" => "Bestaat reeds",
"40" => "Het bestand is succesvol uploaded",
"41" => "Taal is succesvol gekozen",
"42" => "Welkom bij PHP Advanced Transfer Manager",
"43" => "Totale Ruimte Gebruikt",
"44" => "Toon bestanden voor alle dagen",
"45" => "Invalide ZIP archief!",
"46" => "Archief inhoud:",
"47" => "Datum/Tijd",
"48" => "Directory",
"49" => "Het is toegestaan om het bestand te uploaden met naam %s!",
"50" => "Overschrijding maximum toegestane grootte",
"51" => "Informatie",
"52" => "Selecteer Skin",
"53" => "Skin",
"54" => "Welkom",
"55" => "Huidige tijd",
"56" => "Gebruiker",
"57" => "Gebruikersnaam",
"58" => "Registreren",
"59" => "Registratie",
"60" => "Zondag",
"61" => "Maandag",
"62" => "Dinsdag",
"63" => "Woensdag",
"64" => "Donderdag",
"65" => "Vrijdag",
"66" => "Zaterdag",
"67" => "Zend",
"68" => "Mail bestand",
"69" => "Bestand is gemailed naar %s adres.",
"70" => "Bestand uploaded door gebruiker: %s",
"71" => "Login",
"72" => "Logout",
"73" => "Enter",
"74" => "Anonymous",
"75" => "Normale Gebruiker",
"76" => "Power Gebruiker",
"77" => "Administrator",
"78" => "Prive zone",
"79" => "Publieke zone",
"80" => "Je hebt een onjuiste account naam of paswoord ingegeven.",
"81" => "Mijn profiel",
"82" => "Toon/edit mijn profiel",
"83" => "Paswoord",
"84" => "Selecteer taal",
"85" => "Selecteer tijdzone",
"86" => "Je huidige tijd",
"88" => "AUB, geef een juist e-mail adres.",
"89" => "Verzeker jezelf ervan dat je e-mail adres actief is, want je persoonlijke activatie code wordt naar je e-mail adres gezonden.",
"90" => "Bestand uploaded: ",
"91" => "AUB, geef je e-mail adres, dat je hebt opgegeven bij registratie.",
"92" => "Bestands grootte: ",
"93" => "AUB, schrijf je naam en paswoord op",
"94" => "Registratie is nodig. AUB, registreer.",
"95" => "Registratie is niet nodig. Je kan registreren als je je naam bij je uploaded bestanden wil zien. Niemand anders kan je  naam gebruiken om bestanden te uploaden.",
"96" => "Skin geselecteerd.",
"97" => "Vernieuwen",
"98" => "AUB, geef je login naam en paswoord",
"99" => "Nog steeds niet geregistreerd? - Registreer hier!",
"100" => "Je paswoord vergeten?",
"101" => "AUB, ga %s terug %s en probeer opnieuw.",
"102" => "Je bent succesvol uitgelogd.",
"103" => "Gebruikersnaam is onjuist. De naam mag niet langer dan 12 symbolen zijn en kan bestaan uit letters en cijfers. Naam kan ook '-', '_', en spaties bevatten.",
"104" => "De '%s' die je hebt gekozen is al in gebruik.",
"105" => "Bevestig paswoord",
"106" => "Paswoorden zijn niet gelijk.",
"107" => "Het formaat van het ingegeven e-mail adres is onjuist.",
"108" => "Dank je voor het registreren. AUB vergeet je paswoord niet daar het encrypted in de database wordt weggeschreven en ook wij het niet kunnen achterhalen.",
"109" => "Je kan %s enter het Upload Center hier. %s",
"110" => "Je activatie code is per e-mail naar je verzonden. Je moet je account binnen 2 dagen activeren of het account wordt automatisch opgeheven.",
"111" => "Je hebt geen rechten om dit bestand te downloaden",
"112" => "Activeer account",
"113" => "AUB, activeer je account",
"114" => "Activatie code",
"115" => "Je account is nu actief.",
"116" => "AUB %s enter hier %s.",
"117" => "De ingegeven account naam of activatie code is onjuist.",
"118" => "Account reeds actief.",
"119" => "Ik wil elke dag gemaild worden over de uploaded bestanden.",
"120" => "Wijzig je paswoord.",
"121" => "Je oude paswoord",
"122" => "De ingegeven account naam bestaat niet.",
"123" => "Het ingegeven e-mail adres is onjuist.",
"124" => "Je nieuwe paswoord is verzonden naar je e-mail adres.",
"125" => "kan niet uitvoeren, object niet gevonden",
"126" => "Pas je account instellingen aan",
"127" => "Toepassen",
"128" => "Je profiel is opgeslagen.",
"129" => "Je paswoord is gewijzigd.",
"130" => "Je hebt een onjuist vorig paswoord ingegeven.",
"131" => "Je moet je nieuwe paswoord opgeven.",
"132" => "Configuratie",
"133" => "Upload",
"134" => "Taal & tijdzone",
"135" => "Account statistieken",
"136" => "Je account is aangemaakt:",
"137" => "Gebruikers beheer",
"138" => "Viewer (View only)",
"139" => "Uploader (upload only)",
"140" => "Account '%s' successvol gewijzigd",
"141" => "Laatste",
"142" => "Allemaal",
"143" => "Nieuw e-mail adres wordt geldig na bevestiging. Bevestigings code is gemailed naar je nieuwe mail adres. Zie de instructies in de email.",
"144" => "",
"145" => "AUB, bevestig je nieuwe e-mail adres.",
"146" => "Bevestigings code",
"147" => "Bevestig",
"148" => "Niets te bevestigen.",
"149" => "De ingegeven account naam of bevestigings code is onjuist.",
"150" => "Je nieuwe e-mail adres '%s' is bevestigd.",
"151" => "Bestanden uploaded",
"152" => "Bestanden downloaded",
"153" => "Bestanden e-mailed",
"154" => "Account aangemaakt",
"155" => "Laatstelijk bezocht tijd",
"156" => "Status",
"157" => "Actieve status",
"158" => "Onvangst nieuwsbrief",
"159" => "e-mail",
"160" => "Totaal:",
"161" => "Account(s)",
"162" => "Verwijder account",
"163" => "Toon %s account(s) van %s",
"164" => "Configureer Upload Center",
"165" => "Edit bestanden",
"166" => "Edit bestanden",
"167" => "Bestand %s is succesvol gewijzigd",
"168" => "Opslaan",
"169" => "Verwijder",
"170" => "Verwijder bestand",
"171" => "Mirror",
"172" => "Ja",
"173" => "Nee",
"174" => "Actief",
"175" => "Inactief",
"176" => "Ongerechtigd",
"177" => "Sorry, de server het mail programma niet uitvoeren.",
"178" => "Je registratie is mislukt. AUB, probeer het later opnieuw.",
"179" => "AUB, probeer het later opnieuw.",
"180" => "Bestand succesvol verwijderd",
"181" => "Je hebt geen rechten om dit bestand te verwijderen",
"182" => "Directory succesvol verwijderd",
"183" => "Je hebt geen rechten om deze directory te verwijderen",
"184" => "Directory succesvol aangemaakt",
"185" => "Je hebt geen rechten om een directory aan te maken",
"186" => "Maak een nieuwe directory",
"187" => "Directory naam",
"188" => "Maak dir",
"189" => "Directory bestaat reeds, aub een andere naam",
"190" => "Je moet een directory naam opgeven",
"191" => "Verander",
"192" => "Bestandsnaam",
"193" => "Verander bestand / directory details",
"194" => "Object succesvol renamed.",
"195" => "Je hebt geen rechten om dat object te hernoemen",
"196" => "Het root path is niet correct. Controleer de instellingen",
"197" => "Volgorde bij",
"198" => "Hernoemen mislukt, bestand bestaat reeds",
"199" => "Laatste uploads",
"200" => "Top downloads",
"201" => "Hernoemen mislukt, naam niet toegestaan",
"202" => "The url die je opgaf is ongeldig",
"203" => "File http adres",
"204" => "Upload file by http adres",

//
// New strings introduced in version 1.10
//
"205" => "Always stay logged",
"206" => "Can't execute: name not allowed",
"207" => "ip blocked",
"208" => "Your ip has been blocked by Administration!",
"209" => "To obtain more infos contact the Administrator"
);

//
// Send file e-mail configuration
//
$sendfile_email_subject = 'Aangevraagd bestand';
$sendfile_email_body = '
Hier is het door jou aangevraagde bestand per mail

';
$sendfile_email_end = 'Groeten,';

//
// Digest e-mail configuration
//
$digest_email_subject = "Iedere dag nieuwsbrief";

//
// Confirm new e-mail configuration
//
$confirm_email_subject = 'Bevestig nieuw e-mail adres';
$confirm_email_body = 'Beste %s,

Omdat jouw veiligheid belangrijk voor ons is, Zal je nieuwe e-mail adres bevestigd moeten worden na ontvangst.

Je persoonlijke bevestigings code is: %s

Activering van je e-mail adres is eenvoudig:
1. Bezoek ons op $installurl/confirm.php en we zullen je door de procedure loodsen.
2. Geef je account naam en bevestigings code.
3. Klik op de \'Bevestig\' knop.

';
$confirm_email_end = 'Groeten,';

//
// Send password e-mail configuration
//
$chpass_email_subject = 'Nieuw paswoord';
$chpass_email_body = 'Beste gebruiker,

Je nieuwe paswoord is %s

';
$chpass_email_end = 'Groeten,';

//
// Confirm registration e-mail configuration
//
$register_email_subject = 'Bevestig registratie';
$register_email_body = 'Beste %s,

Dank voor je registratie.

Omdat jouw veiligheid belangrijk voor ons is, Zal je account geactiveerd moeten worden na ontvangst.

Je persoonlijke activerings code is: %s
(let op: dit is niet je paswoord)

Activering van je account is eenvoudig:
1. Bezoek ons op %s en we zullen je door de procedure loodsen.
2. Geef je account naam en activerings code.
3. Klik op de \'Activeer account\' knop.

';
$register_email_end = 'Groeten,';
?>
