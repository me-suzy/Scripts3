<?
/*
# UBB.threads, Version 5
# Official Release Date for UBB.threads Version5: December 12, 2000.

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2001.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/


// Require the library
   require ("main.inc.php");
                                
// ---------------------
// Send the page to them
  $html = new html; 
  $html -> send_header("FAQ (Frequently Asked Questions)",$Cat,0,0,0,0,$user);
  $html -> table_header("FAQ (Frequently Asked Questions)");

  $phpurl = $config['phpurl'];

  $html -> open_table();
  echo " 
  <tr class=\"darktable\">
  <td>
  Hieronder vind je een aantal vaak gestelde vragen.  Je kunt op de vraag klikken om de antwoorden op deze vragen te lezen.  Mocht je andere problemen hebben die niet op deze pagina worden behandeld, email dan naar <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#register\">Wat is het voordeel van registreren?</a><br />
  <a href=\"#email\">Waarom worden er 2 email adressen gevraagd?</a><br />
  <a href=\"#cookies\">Moet ik cookies accepteren?</a><br />
  <a href=\"#login\">Hoe kan ik inloggen?</a><br />
  <a href=\"#help\">Help! Ik kan niet inloggen.</a><br />
  <a href=\"#titlestring\">Wat betekent die regel onder mijn gebruikersnaam in ieder bericht?</a><br />
  <a href=\"#titles\">Waar zijn de titels voor?</a><br />
  <a href=\"#password\">Ik ben mijn wachtwoord vergeten!</a><br />
  <a href=\"#changepassword\">Hoe kan ik mijn wachtwoord veranderen?</a><br />
  <a href=\"#image\">Hoe kan ik een afbeelding aan berichten toevoegen?</a><br />
  <a href=\"#limit\">Is de lengte van de handtekening beperkt?</a><br />
  <a href=\"#attach\">Kan ik een bestand aan mijn berichten toevoegen?</a><br />
  <a href=\"#rules\">Aan welke regels moet ik mij houden?</a><br />
  <a href=\"#editprof\">Hoe kan ik mijn profiel bewerken?</a><br />
  <a href=\"#editdisp\">Hoe kan ik mijn weergave instellingen veranderen?</a><br />
  <a href=\"#subject\">Waarom is het onderwerp van een bericht zo belangrijk?</a><br />
  <a href=\"#answer\">Hoe kan ik berichten vinden waar het antwoord op mijn vraag in staat?</a><br />
  <a href=\"#flash\">Wat betekent die knipperende enveloppe in het menu?</a><br />
  <a href=\"#letters\">Waar zijn die letters in het 'ontvangen privé-berichten' venster voor?</a><br />
  <a href=\"#url\">Ik krijg het niet voor elkaar een link in mijn bericht te plaatsen.</a><br />
  <a href=\"#whitespace\">Hoe kan ik in berichten tussen 2 paragrafen een stukje ruimte invoegen?</a><br />
  <a href=\"#spellchecker\">Kan ik spellingcontrole gebruiken?</a><br />
  <a href=\"#mistake\">Wat moet ik doen als ik een fout gemaakt heb in mijn bericht?</a><br />
  <a href=\"#wrongforum\">Wat moet ik doen als ik mijn bericht in een verkeerd forum geplaatst heb?</a><br />
  <a href=\"#html\">Mag ik html in mijn berichten gebruiken?</a><br />
  <a href=\"#polls\">Hoe kan ik een enquête in mijn bericht plaatsen?</a><br />
  <a href=\"#moreposts\">Ik wil minder (of meer) berichten per pagina zichtbaar maken.</a><br />
  <a href=\"#buttons\">Hoe zit het nou met al die knoppen?</a><br />
  <a href=\"#sortorder\">Waarom kan ik op 'Onderwerp', 'Auteur' en 'Geplaatst op' klikken?</a><br />
  <a href=\"#source\">Kan ik zelf zo'n board beheren?</a><br />
  <p>
  ";
  $html -> close_table();

  echo "
  <p>&nbsp;
  ";

  $html -> open_table();

  echo "
  <tr><td class=\"darktable\">
  <h3><a name=\"register\">Wat is het voordeel van registreren?</a></h3>
<p>
</td></tr><tr><td class=\"lighttable\">
Als je jezelf registreert kun je je eigen profiel inrichten en persoonlijke instellingen aanpassen. Door het board aan jouw wensen aan te passen wordt je bezoek al snel een stuk aangenamer. Er zijn een tal van instellingen mogelijk, dus neem rustig de tijd om na registratie op je gemak je eigen profiel in te richten. Een andere functie die alleen werkt na registratie is 'Nieuwe Berichten'. Deze functie laat je steeds zien welke berichten er sinds jouw laatste bezoek nieuw zijn bijgekomen. 
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"email\">Waarom worden er 2 email adressen gevraagd?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Het administratieve email adres wordt gebruikt voor email notificaties en om je jouw wachtwoord te sturen. Het publieke email adres staat in jouw profiel en is zichtbaar voor andere leden. Je kunt hier bijvoorbeeld een @hotmail adres invullen. Je kunt er ook gewoon je echte e-mail adres invullen met een extra teken. <i>Voorbeeld:</i> jouwnaam\@jouwprovider.nl. Iedereen kan zien dat het extra teken daar niet thuishoort, maar een spam robot kan dit adres nu niet gebruiken voor spam. 
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"cookies\">Moet ik cookies accepteren?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Ja.  Cookies worden gebruikt om jouw gebruikersnaam en wachtwoord te controleren.  Als jouw internet browser of firewall geen cookies accepteert, kun je niet inloggen.
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"login\">Hoe kan ik inloggen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Als je jezelf eenmaal geregistreerd hebt, kun je het beste inloggen om volledig gebruik te kunnen maken van alle mogelijkheden.  Je kunt inloggen via de '{$ubbt_lang['BUTT_LOGIN']}' link helemaal bovenaan het board.  Na op deze link geklikt te hebben verschijnt er een nieuwe pagina waar je jouw gebruikersnaam en wachtwoord in kunt vullen.  Let wel op de grote en kleine letters!  De boardsoftware maakt wel degelijk verschil tussen een A of een a.
<p>
Na je gebruikersnaam en wachtwoord ingevuld te hebben, kom je op de startpagina terecht. Als je een privé-bericht ontvangen hebt zie je een kleine enveloppe knipperen in het menu. Als je op de link '{$ubbt_lang['INDEX_ICON']}' in het menu bovenaan de pagina klikt, verschijnt de lijst met forums. 
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"help\">Help! Ik kan niet inloggen.</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Hier vind je een aantal handige tips:
<p>
 1) Zorg ervoor dat je jouw wachtwoord correct hebt ingevoerd. Let op de GROTE en kleine letters.<br />
2) Controleer of jouw internet browser cookies accepteert; Indien ja, controleer dan of je de beveiligingsinstellingen juist hebt ingesteld. Als je het beveiligingsniveau op 'Hoog' ingesteld hebt, worden cookies geweigerd en kun je niet inloggen.
<br />
3) Druk nogmaals op de '{$ubbt_lang['LOGOUT_TEXT']}' link bovenaan de pagina (indien aanwezig), en log opnieuw in.
<br />
4) Druk na inloggen eens op 'Verversen' of 'Refresh' in je internet browser om het inloggen te versnellen.
<br />
5) Als je nu nog steeds niet in kunt loggen ga je naar de inlogpagina en vul je onderaan bij 'Wachtwoord vergeten' je gebruikersnaam en email adres in. Daarna druk je op \"Wachtwoord vergeten\".  Je ontvangt nu een tijdelijk wachtwoord via e-mail. Probeer met deze gegevens opnieuw in te loggen.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"titlestring\">Wat betekent die regel onder mijn gebruikersnaam in ieder bericht?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Dit zijn titels die door de boardsoftware automatisch worden gegenereerd. De titel is afhankelijk van het aantal berichten dat je op dit board geplaatst hebt..
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"titles\">Waar zijn de titels voor?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Iedereen heeft zijn eigen titel.  Sommige titels worden door de boardsoftware automatisch gegenereerd en andere kunnen door de board medewerkers of VIP's uitgedeeld worden.  
  <p>
Dit is een lijst van standaard titels en het aantal berichten dat er voor benodigd is:
  <p>
  <pre>";
  readfile("{$config['path']}/filters/usertitles");
  echo "</pre>
  <br />
  
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"password\">Ik ben mijn wachtwoord vergeten!</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Geen zorgen! Ga je naar de inlogpagina en vul onderaan bij 'Wachtwoord vergeten' je gebruikersnaam en email adres in. Daarna druk je op 'Wachtwoord opvragen'. Je ontvangt nu een tijdelijk wachtwoord via e-mail.
<p>
Deze functie is veilig omdat het wachtwoord alleen naar het e-mail adres gestuurd wordt, dat bij deze gebruikersnaam hoort.  Je wachtwoord kan dus op deze manier niet 'gestolen' worden.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"changepassword\">Hoe kan ik mijn wachtwoord veranderen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Je kunt jouw wachtwoord altijd veranderen.  Om het wachtwoord te veranderen klik je op '{$ubbt_lang['CONTROL_PANEL']}'.  Daarna kies je achter persoonlijke informatie '{$ubbt_lang['EDIT_ICON']}'. Om het wachtwoord te wijzigen hoef je alleen de velden 'wachtwoord' en 'Wachtwoord controleren' te wijzigen (let op GROTE en kleine letters). Daarna druk je onderaan de pagina op '{$ubbt_lang['BUTT_SUBMIT']}'. 
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"image\">Hoe kan ik een afbeelding aan berichten toevoegen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Om een afbeelding in jouw bericht of handtekening toe te voegen, moet die afbeelding al ergens op een webserver aanwezig zijn.  Dit zou een afbeelding op jouw persoonlijke Homepage kunnen zijn bijvoorbeeld. Om een afbeelding aan een bericht toe te voegen kun je volgende '<font color=\"#FF0000\">tags</font>' gebruiken:
<p>
<font color=\"#FF0000\">[image]</font>http://www.jouwpagina.nl/afbeelding.gif<font color=\"#FF0000\">[/image]</font>
<p>
Stel voor, jouw afbeelding heet plaatje.gif en staat op jouw webserver http://www.mijnserver.nl/afbeeldingen. In dat geval is de juiste opmaak:
<p>
	<font color=\"#FF0000\">[image]</font>http://www.mijnserver.nl/afbeeldingen/plaatje.gif<font color=\"#FF0000\">[/image]</font>
<p>
Hetzelfde kun je ook in je handtekening doen.  Klik op {$ubbt_lang['CONTROL_PANEL']}.  Ga naar 'Persoonlijke informatie, email, wachtwoord, enz.' en klik op bewerken.  Zoek het veld 'Handtekening' en voer daar m.b.v. bovenstaande uitleg jouw afbeelding in. 
<p>
Noot: Maak afbeeldingen zo klein mogelijk en in ieder geval kleiner dan 35 kB. Grote afbeeldingen maken het board traag!
  <p>&nbsp;



  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"limit\">Is de lengte van de handtekening beperkt?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
We willen je verzoeken de handtekening zo compact mogelijk te houden. Gebruik dus geen grote afbeeldingen en onnodig lange zinnen.  Voor een afbeelding geld een maximum formaat van 125 x 600 pixels en 35 kB.  Dit om het board ook voor mensen met een trage verbinding redelijk snel te houden.
  <p>&nbsp;



  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"attach\">Kan ik een bestand aan mijn berichten toevoegen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
Als je over een Mozilla 4+ compatible internet browser beschikt is dat mogelijk. De meeste recente browsers ondersteunen deze functie dus.  Als je bij het plaatsen van een bericht, eerst een voorbeeld weergave kiest, kun je in dat venster een bestand aan jouw bericht toevoegen.";
  }
  else {
    echo "Nee helaas.  De mogelijkheid een bestand aan een bericht toe te voegen, is op dit board uitgeschakelt.";
  }
  echo "
  <p>&nbsp;


  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"rules\">Aan welke regels moet ik mij houden?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">";
  readfile("{$config['path']}/includes/boardrules.php");
  echo "<p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"editprof\">Hoe kan ik mijn profiel bewerken?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Klik op de '{$ubbt_lang['CONTROL_PANEL']}' link in het menu. Onderaan het volgende scherm vind je de opties voor het bewerken van je profiel.
  <p>&nbsp;



  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"editdisp\">Hoe kan ik mijn weergave instellingen veranderen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
UBB threads biedt vele mogelijkheden om instellingen aan je persoonlijke voorkeur aan te passen.  Je kunt je persoonlijke voorkeuren aanpassen via de '{$ubbt_lang['CONTROL_PANEL']}' link in het menu. Onderaan in jouw profiel vind je de 'Weergave instellingen'.
<p>
Je kunt dan kiezen welke taal je wilt gebruiken, welk stylesheet (deze bepalen het uiterlijk van het board), hoeveel berichten er per pagina moeten worden weergegeven, of je afbeeldingen van andere gebruikers in de berichtenweergave wilt weergeven, en nog veel meer. Eenmaal opgeslagen, worden dit je standaard instellingen.  Je kunt deze instellingen op ieder moment weer veranderen.

  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"subject\">Waarom is het onderwerp van een bericht zo belangrijk?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Het eerste bericht van een nieuw bericht bepaalt het onderwerp waaronder alle volgende antwoorden terug te vinden zijn. Als er eenmaal een antwoord gegeven is, kan het onderwerp van het eerste bericht niet meer worden veranderd.  Daarom is het belangrijk dat het onderwerp van een nieuw bericht vanaf het begin af aan duidelijk is. Maak het onderwerp zo beschrijvend en specifiek mogelijk. Bijvoorbeeld, 'waar vind ik de website van Infopop', is beter dan alleen 'infopop'.  De kans dat er op jouw bericht wordt gereageerd is niet alleen groter, maar het maakt het ook veel gemakkelijker om alle berichten in dit specifieke onderwerp (bijvoorbeeld middels de zoekpagina) terug te vinden.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"answer\">Hoe kan ik berichten vinden waar het antwoord op mijn vraag in staat?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Een van de grote voordelen van een discussie board is dat het mogelijk is om veel gestelde vragen eemaal te beantwoorden, zodat iedereen hier voordeel uit kan halen. Voordat je een vraag stelt, is het ten zeerste aan te raden om eerst eens goed te kijken of je vraag al eerder is gesteld, en mogelijk al beantwoord is!  Dit kun je doen door gebruik te maken van de 'Zoeken' link in het menu.  Verschillende opties zijn beschikbaar. B.v. hoe de zoekterm is gespecificeerd en je kunt instellen welk gedeelte van de database je wilt doorzoeken.  Dit is een van de redenen waarom het onderwerp van jouw bericht zo belangrijk is.  Hoe specifieker het onderwerp, des te gemakkelijker het zoeken naar vragen en antwoorden wordt.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"flash\">Wat betekent die knipperende enveloppe in het menu?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Dit betekent dat je ongelezen privé-berichten hebt. Klik op de enveloppe om de berichten te lezen. 
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"letters\">Waar zijn die letters in het 'ontvangen privé-berichten' venster voor?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
'N' geeft aan dat het bericht ongelezen is.<br />
'R' geeft aan dat dit bericht beantwoord is.<br />
Een spatie geeft aan dat het bericht gelezen, maar niet beantwoord is.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"url\">Ik krijg het niet voor elkaar een link in mijn bericht te plaatsen.</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Als er 'tags' in je bericht tekst verschijnen, of er een link naar 'http:///' verschijnt, zitten er waarschijnlijk onbedoelde spaties in jouw bericht. Vermijdt onderbrekende spaties, en alles zou daarna goed moeten werken.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"whitespace\">Hoe kan ik in berichten tussen 2 paragrafen een stukje ruimte invoegen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Door twee maal op de enter toets te drukken op de plaatsen waar je een lege regel wilt invoegen.  Voordat je het bericht plaatst kun je er voor kiezen eerst een voorbeeld te bekijken.  Mochten er dan nog fouten in jouw bericht staan, heb je de kans om het bericht aan te passen voordat je het uiteindelijk plaatst.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"spellchecker\">Kan ik spellingcontrole gebruiken?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Nee. Als je zeker wilt weten dat jouw bericht geen spellingsfouten bevat, kun je het bericht in een tekstverwerker controleren op fouten, en het dan door middel van 'knippen' en 'plakken' in het berichten venster plakken.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"mistake\">Wat moet ik doen als ik een fout gemaakt heb in mijn bericht?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Leden kunnen hun eigen berichten veranderen tot {$config['edittime']} uur nadat het geplaatst is.  Als het een grote of belangrijke verandering betreft, moet je het bericht markeren als 'bewerkt' , zodat andere lezers worden geattendeerd op de veranderde inhoud.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"wrongforum\">Wat moet ik doen als ik mijn bericht in een verkeerd forum geplaatst heb?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Laat het dan de board administrator(s) of de betreffende moderator(s) van het forum waar het bericht abusievelijk geplaatst is even weten.  Deze personen zijn in staat het bericht naar het juiste forum te verplaatsen.
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"html\">Mag ik html in mijn berichten gebruiken?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Dit is afhankelijk van de configuratie van dit board.  Als HTML is ingeschakeld zie je <b>HTML ingeschakeld</b> en kun je in jouw bericht normale 'HTML tags' gebruiken. Als UBBCode is ingeschakeld zie je <b>UBBCode ingeschakeld</b>. De volgende 'tags' zijn beschikbaar als UBBCode is ingeschakeld:
  <br /><br />
  <font class=\"standouttext\">
  [b]
  </font>
  tekst
  <font class=\"standouttext\">
  [/b]
  </font>
         = Maakt de tekst vet.<br />

  <font class=\"standouttext\">
  [email]
  </font>
  joe@email.com
  <font class=\"standouttext\">
  [/email] 
  </font>
  = Zorgt ervoor dat je op het email adres kunt klikken.<br />

  <font class=\"standouttext\">
  [i]
  </font>
  tekst
  <font class=\"standouttext\">
  [/i]
  </font>
         = Maakt de tekst cursief.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=\"standouttext\">";
    echo"[image]</font>";
    echo"url";
    echo "<font class=\"standouttext\">";
    echo "[/image]</font>  = Zet de opgegeven url in een img src tag.<br />";
  }

  echo "

  <font class=\"standouttext\">
  [code]
  </font>
  tekst
  <font class=\"standouttext\">
  [/code]
  </font>
   = Zet de tekst tussen pre tags.<br />

  <font class=\"standouttext\">
  [quote]
  </font>
  tekst
  <font class=\"standouttext\">
  [/quote] 
  </font>
  = Zet de tekst tussen blockquote en hr (horizontale lijn) tags.  Deze UBBCode tag wordt gebruikt om een citaat aan te geven. <br />

  <font class=\"standouttext\">
  [url]
  </font>
  link
  <font class=\"standouttext\">
  [/url]</font>    = Maakt van de opgegeven url een link.<br />

  <font class=\"standouttext\">
  [url=link]
  </font>
  tekst
  <font class=\"standouttext\">
  [/url]</font>    = Maakt van de opgegeven tekst een hyperlink verwijzend naar een link.<br />

  <font class=\"standouttext\">
  [list]<br />
  [*]Item 1<br />
  [*]Item 1<br />
  [/list]</font> =  Maakt een opsomming. [list=A] of [list=1] maakt een geordende / genummerde lijst.<br />
  <font class=\"standouttext\">
  [blush] of :o
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=\"standouttext\">
  [cool]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=\"standouttext\">
  [crazy]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=\"standouttext\">
  [frown] of :(
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=\"standouttext\">
  [laugh] of :D
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=\"standouttext\">
  [mad]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=\"standouttext\">
  [shocked]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=\"standouttext\">
  [smile] of :)
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=\"standouttext\">
  [tongue] of :p
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=\"standouttext\">
  [wink] of ;)
  </font> = <img src=\"{$config['images']}/graemlins/wink.gif\"><br />


  <font class=\"standouttext\">
  [color:red]
  </font> 
  tekst
  <font class=\"standouttext\">
  [/color]
  </font> 
  = Maakt de tekst rood.<br />

  <font class=\"standouttext\">
  [color:#00FF00]
  </font> 
  tekst
  <font class=\"standouttext\">
  [/color] 
  </font> 
  = Maakt de tekst groen.<br />

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\"> 
  <h3><a name=\"source\">Kan ik zelf zo'n board beheren?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Ja, een testversie van UBB threads is beschikbaar via <a href=\%22http:/www.infopop.com\%22>www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\">Hoe kan ik een enquête in mijn bericht plaatsen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Een enquête toevoegen in een bericht is eenvoudig, maar onthoudt wel het volgende:  Berichten met een enquête kunnen niet worden bewerkt maar kunnen wel worden verwijderd.<br />
  ";
  if (!$config['allowpolls']) {
    echo "<i>Alleen de administrator en de moderators kunnen deze functie gebruiken.</i><br />";
  }
  echo "
  Om een enquête toe te voegen aan je bericht, gebruik het volgende formaat:<p> 
  [pollstart]<br />
  [polltitle=Naam van je enquête]<br />
  [polloption=Eerste keuze]<br />
  [polloption=Tweede keuze]<br />
  [polloption=Zoveel keuzes als je maar wilt]<br />
  [pollstop]

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"moreposts\">Ik wil minder (of meer) berichten per pagina zichtbaar maken.</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Je kunt de hoeveelheid zichtbare onderwerpen per pagina veranderen in je profiel. Je kunt dit instellen tussen 1 en 99 onderwerpen per pagina. De standaard instelling voor nieuwe leden is {$theme['postsperpage']} onderwerpen per pagina.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <h3><a name=\"buttons\">Hoe zit het nou met al die knoppen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  De knoppen worden gebruikt voor het navigeren en weergeven van berichten.  Afhankelijk van het scherm waar je je bevindt kunnen de knoppen verschillende functies hebben.
  <p><b>Als alle onderwerpen worden weergegeven:</b>
  <br />- De <img src=\"{$config['images']}/previous.gif\"> en <img src=\"{$config['images']}/next.gif\" > knoppen brengen je op de vorige of volgende pagina met berichten. 
  <br />- De <img src=\"{$config['images']}/all.gif\" > knop brengt je naar de lijst met alle forums.
  <br />- De <img src=\"{$config['images']}/newpost.gif\" > knop brengt je naar het scherm waar je een nieuw onderwerp kunt starten.
  <br />- De <img src=\"{$config['images']}/expand.gif\" > en <img src=\"{$config['images']}/collapse.gif\" > knoppen schakelen tussen de uitgeklapte weergave en ingeklapte weergave. Uitgeklapte weergave laat het onderwerp van alle berichten zien samen met alle antwoorden in 'draad' formaat. Ingeklapte weergave laat het onderwerp zien van het eerste bericht samen met het aantal antwoorden op dat onderwerp.
  <p><b>Als individuele onderwerpen worden weergegeven:</b>
  <br />- De <img src=\"{$config['images']}/previous.gif\" > en <img src=\"{$config['images']}/next.gif\" > knoppen brengen je naar het vorige of het volgende onderwerp.
  <br />- De <img src=\"{$config['images']}/all.gif\" > knop brengt je naar de lijst met alle onderwerpen op die pagina.
  <br />- De <img src=\"{$config['images']}/flat.gif\" > knop laat je de lijst met antwoorden op een onderwerp zien in het geval van beantwoorden van het originele bericht.
  <br />- De <img src=\"{$config['images']}/threaded.gif\" > knop laat je het huidige bericht zien met daaronder alle antwoorden op dat bericht in 'draad' formaat.
  <br />- De <img src=\"{$config['images']}/reply.gif\" > knop brengt je naar het scherm waar je een antwoord kunt plaatsen op het bericht.
  <br />- De <img src=\"{$config['images']}/edit.gif\" > knop brengt je naar het scherm om het bericht te bewerken.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"sortorder\">Waarom kan ik op 'Onderwerp', 'Auteur' en 'Geplaatst op' klikken?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Je kunt op deze woorden klikken om de manier waarop de berichten zijn gesorteerd en worden weergegeven te veranderen.  Als je op 'Onderwerp' klikt, dan worden de berichten in omgekeerde alfabetische volgorde gesorteerd weergegeven. Klik je nogmaals op 'Onderwerp' dan worden de berichten in alfabetische volgorde gesorteerd weergegeven. 'Auteur' en 'Geplaatst op' werken op identieke wijze.

  <p>&nbsp;
  ";
  $html -> close_table();
// -------------
// Send a footer
  $html -> send_footer();
