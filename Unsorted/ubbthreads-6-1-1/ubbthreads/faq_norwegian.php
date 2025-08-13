<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
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
  $html -> send_header("OSS (Ofte Stilte Spørsmål)",$Cat,0,$user);
  $html -> table_header("OSS (Ofte Stilte Spørsmål)");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=0 border=0 width=100% class=darktable>
  <tr><td>
  Her er en liste over spørsmål vi ofte får. Klikk på et av spørsmålene for å få fram svaret. Skulle det mangle noen viktige emner på denne siden, send en e-post til <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=lighttable>
  <a href=\"#attach\">Kan man legge med filer, når man sender innlegg?</a><br />
  <a href=\"#html\">Kan man bruke HTML, når man skriver innlegg?</a><br />
  <a href=\"#source\">Kan jeg lage mitt eget forum-nettsted?</a><br />
  <a href=\"#cookies\"><i>Må</i> min nettleser være innstilt til å akseptere cookies?</a><br />
  <a href=\"#polls\">Hvordan lager jeg en spørreundersøkelse i mine innlegg?</a><br />
  <a href=\"#moreposts\">Jeg vil gjerne se flere (eller færre) innlegg pr. side.</a><br />
  <a href=\"#buttons\">Hva er alle knappene til for?</a><br />
  <a href=\"#sortorder\">Hvorfor er Emne, Avsender og Dato linker?</a><br />
  <a href=\"#email\">Hvorfor skal man oppgi <i>to</i> e-postadresser?</a><br />
  <a href=\"#register\">Gi meg &eacute;n god grunn til, at jeg bør registrere et brukernavn!</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=0 border=0 width=100% class=darktable>
  <tr><td>
  <a name=\"attach\"><h3>Kan man legge ved filer, når man sender innlegg?</a></h3>
  <p>
  </td></tr><tr><td class=lighttable>
  ";
  if($config['files']) {
    echo "
      Du kan legge ved filer hvis din nettleser er kompatibel med Mozilla 4+ (f.eks. Netscape). Når du ser igjennom ditt innlegg før avsending, har du mulighet for å legge ved en fil.
    ";
  }
  else {
    echo "Nei, det er ikke mulig å legge ved filer.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>
  <a name=\"html\"><h3>Kan man bruke HTML, når man skriver innlegg?</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>
  Det varierer fra forum til forum. Hvis HTML er mulig, står det <b>HTML er mulig</b> på forumet. Hvis tekstformatering er mulig, står det <b>Tekstformatering er mulig</b>. Følgende tekstformateringskoder kan brukes:
  <br /><br />
  <font class=standouttext>
  [b]
  </font>
  tekst
  <font class=standouttext>
  [/b]
  </font>
         = gjør skriften fet.<br />

  <font class=standouttext>
  [e-postadresse]
  </font>
  e-post\@adresse.dk
  <font class=standouttext>
  [/e-postadresse] 
  </font>
  = gjør teksten til en klikkbar e-postadresse.<br />

  <font class=standouttext>
  [i]
  </font>
  tekst
  <font class=standouttext>
  [/i]
  </font>
         = gjør skriften kursiv.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=standouttext>";
    echo"[bilde]</font>";
    echo"url";
    echo "<font class=standouttext>";
    echo "[/bilde]</font>  = setter inn URL'en i en &lt;IMG SRC&gt;-kode.<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  tekst
  <font class=standouttext>
  [/pre]
  </font>
   = Setter &lt;PRE&gt;-koder rundt om teksten.<br />

  <font class=standouttext>
  [sitat]
  </font>
  text
  <font class=standouttext>
  [/sitat] 
  </font>
  = Setter &lt;BLOCKQUOTE&gt; og &lt;HR&gt; rundt om teksten. Dette brukes til å markere et sitat når du svarer på et innlegg.<br />

  <font class=standouttext>
  [url]
  </font>
  link
  <font class=standouttext>
  [/url]</font>    = gjør URL'en til en link.<br />


  <font class=standouttext>
  [rødmer]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [kul]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [gal]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [trist]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [ler]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [sint]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=standouttext>
  [sjokkert]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=standouttext>
  [smiler]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=standouttext>
  [tunge]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=standouttext>
  [blink]
  </font> = <img src=\"{$config['images']}/graemlins/wink.gif\"><br />

  <font class=\"standouttext\">
  [color:red]
  </font>
  text
  <font class=\"standouttext\">
  [/color]
  </font>
  = Makes the given text red.<br />

  <font class=\"standouttext\">
  [color:#00FF00]
  </font>
  text
  <font class=\"standouttext\">
  [/color]
  </font>
  = Makes the given text green.<br />


  <p>&nbsp;
  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>
  <a name=\"source\"><h3>Kan jeg lage mitt eget forum-nettsted?</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>
  Ja. Forumsystemet UBB.threads finner du på <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>
  <a name=\"cookies\"><h3><i>Må</i> min nettleser være innstilt til å akseptere cookies?</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>
  Ja. Cookies er nødvendig for å ta vare på ditt brukernavn og passord og for å holde orden på hvilke innlegg du har lest og ikke lest. Hvis din nettleser ikke er innstilt til å akseptere cookies, er det noen funksjoner i forumet, som ikke virker ordentlig.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\">Hvordan lager jeg en spørreundersøkelse i mine innlegg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Å lage en spørreundersøkelse i et innlegg er enkelt, men husk at slike innlegg ikke kan redigeres, kun slettes.<br />
  ";
  if (!$config['allowpolls']) {
    echo "<i>Kun administratorers og moderatorer kan lage spørreundersøkelser.</i><br />";
  }
  echo " 
  For å lage et spørsmål i innlegget ditt, bruk dette formatet:<p>
  [pollstart]<br />
  [polltitle=Navn på undersøkelsen]<br />
  [polloption=Første valg]<br />
  [polloption=Andre valg]<br />
  [polloption=Så mange valg du vil]<br />
  [pollstop]


                

  <p>&nbsp;
  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>
  <a name=\"moreposts\"><h3>Jeg vil gjerne se flere (eller færre) innlegg pr. side.</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>
  Antallet innlegg som vises pr. side, kan endres i profilen. Antallet kan settes til alt mellom 1 og 99 innlegg pr. side. Standard er satt til {$theme['postsperpage']} innlegg pr. side.


  <p>&nbsp;
  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>

  <a name=\"buttons\"><h3>Hva er alle knappene til for?</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>

  Knappene brukes til navigasjon og til å vise innlegg. Noen av knappene skifter funksjon alt etter hvilken side, man befinner seg på.
  <p>Når oversikten over alle temaer i et forum blir vist:
  <br />- Pilene til venstre og høyre fører til den forrige eller neste side med innlegg. 
  <br />- Pilen opp fører til oversikten over alle fora.
  <br />- \"Nytt innlegg\"-knappen brukes hvis man vil skrive et nytt innlegg til forumet.
  <br />- Pluss- og minusknappene skifter mellom å vise full temaoversikt eller komprimert.
  <p>Når ett enkelt tema blir vist:
  <br />- Pilene til venstre og høyre fører til henholdsvis forrige og neste emne.
  <br />- Pilen opp fører tilbake til oversikten over alle emner.
  <br />- \"Flat\"-knappen gjør, at man kan se alle innlegg i ett gitt tema på &eacute;n side.
  <br />- \"Tematisk\"-knappen gjør, at man ser et enkelt innlegg i et emne, og så ser alle svar på det innlegg som en \"tråd\" nedenunder.



  <p>&nbsp;
  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>
  <a name=\"sortorder\"><h3>Hvorfor er Emne, Avsender og Dato linker?</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>
  Klikk på en av dem for å endre rekkefølgen innleggene blir vist i. Klikker man på emne, sorteres innleggene etter emne i omvendt alfabetisk rekkefølge. Klikker man på emne en gang til, sorteres de i alfabetisk rekkefølge. Avsender og dato fungerer på samme måte.   


  <p>&nbsp;
  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>
  <a name=\"email\"><h3>Hvorfor skal man oppgi <i>to</i> e-postadresser?</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>
  Den ekte e-postadressen brukes når forumsystemet skal sende deg ditt passord, og når du skal ha melding om at det har kommet svar på dine innlegg eller private beskjeder. Den andre e-postadressen er den som er synlig for andre brukere når de ser på din profil. Det er mange som ikke vil offentliggjøre sine e-postadresser, men vi er nødt til å be folk om å oppgi sin ekte e-postadresse slik at de kan få sitt passord tilsendt. De som ikke ønsker å få sin e-postadresse offentliggjort kan oppgi den ekte til oss slik at de kan få passordet tilsendt, og en falsk som alminnelige brukere får se.  
  
  <p>&nbsp;
  </td></tr><tr><td class=alternatetable>
  </td></tr><tr><td class=darktable>
  <a name=\"register\"><h3>Gi meg &eacute;n god grunn til, at jeg bør registrere et brukernavn!</h3></a>
  <p>
  </td></tr><tr><td class=lighttable>
  Hvis man registrerer et brukernavn har man mulighet for å opprette en profil, slik at du ikke behøver å skrive inn opplysninger om deg selv hver gang du sender et innlegg. Dessuten har brukere mulighet for å sette opp forumet etter egen smak. Brukere kan også raskt se om det har kommet nye innlegg siden de sist besøkte forumet, noe anonyme brukere ikke kan.

  </td></tr></table  </td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
