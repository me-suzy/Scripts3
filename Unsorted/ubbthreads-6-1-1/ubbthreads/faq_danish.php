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
  $html -> send_header("OSS (Ofte Stillede Spørgsmål)",$Cat,0,$user);
  $html -> table_header("OSS (Ofte Stillede Spørgsmål)");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  Her er en liste over spørgsmål, vi ofte får. Klik på et af spørgsmålene for at se det tilhørende svar. Skulle der mangle nogle vigtige emner på denne side, så send venligst et e-brev til <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">Kan man vedhæfte filer, når man sender indlæg?</a><br />
  <a href=\"#html\">Kan man bruge HTML, når man skriver indlæg?</a><br />
  <a href=\"#source\">Kan jeg lave mit eget forum-netsted?</a><br />
  <a href=\"#cookies\"><i>Skal</i> ens browser være indstillet til at acceptere cookies?</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
  <a href=\"#moreposts\">Jeg vil gerne se flere (eller færre) indlæg pr. side.</a><br />
  <a href=\"#buttons\">Hvad er alle de knapper til for?</a><br />
  <a href=\"#sortorder\">Hvorfor er Emne, Afsender og Dato links?</a><br />
  <a href=\"#email\">Hvorfor skal man indtaste <i>to</i> e-postadresser?</a><br />
  <a href=\"#register\">Giv mig bare &eacute;n god grund til, at jeg skulle registrere et brugernavn!</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  <a name=\"attach\"><h3>Kan man vedhæfte filer, når man sender indlæg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Hvis ens browser er kompatibel med Mozilla 4+, så kan man. Når man gennemser sit indlæg før afsendelse, har man mulighed for at vedhæfte en fil.
    ";
  }
  else {
    echo "Nej, det er ikke muligt at vedhæfte filer.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"html\"><h3>Kan man bruge HTML, når man skriver indlæg?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Det varierer fra forum til forum. Hvis HTML er muligt, står der <b>HTML er muligt</b> på forumet. Hvis tekstformatering er muligt, står der <b>Tekstformatering er muligt</b>. Følgende tekstformateringskoder kan bruges:
  <br /><br />
  <font class=standouttext>
  [b]
  </font>
  tekst
  <font class=standouttext>
  [/b]
  </font>
         = gør skriften fed.<br />

  <font class=standouttext>
  [e-postadresse]
  </font>
  e-post\@adresse.dk
  <font class=standouttext>
  [/e-postadresse] 
  </font>
  = gør teksten til en klikbar e-postadresse.<br />

  <font class=standouttext>
  [i]
  </font>
  tekst
  <font class=standouttext>
  [/i]
  </font>
         = gør skriften kursiv.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=standouttext>";
    echo"[billede]</font>";
    echo"url";
    echo "<font class=standouttext>";
    echo "[/billede]</font>  = indsætter URL'en i en &lt;IMG SRC&gt;-kode.<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  tekst
  <font class=standouttext>
  [/pre]
  </font>
   = Sætter &lt;PRE&gt;-koder rundt om teksten.<br />

  <font class=standouttext>
  [citat]
  </font>
  text
  <font class=standouttext>
  [/citat] 
  </font>
  = Sætter &lt;BLOCKQUOTE&gt; og &lt;HR&gt; rundt om teksten. Dette bruges til at markere et citat fra det indlæg, der svares på.<br />

  <font class=standouttext>
  [url]
  </font>
  link
  <font class=standouttext>
  [/url]</font>    = Gør URL'en til et link.<br />


  <font class=standouttext>
  [rødmer]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [sej]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [skør]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [trist]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [ler]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [vred]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=standouttext>
  [chokeret]
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
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"source\"><h3>Kan jeg lave mit eget forum-netsted?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Ja. Forumsystemet UBB.threads kan findes på <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"cookies\"><h3><i>Skal</i> ens browser være indstillet til at acceptere cookies?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Ja. Cookies er nødvendige for at gemme dit brugernavn og adgangskode og for at udregne, hvilke indlæg du har læst, og hvilke, du ikke har læst. Hvis din browser ikke er indstillet til at acceptere cookies, er der nogle funktioner i forumet, der ikke virker ordentligt.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\">How do i put a poll in my post?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Putting a pull in your post is simple, but remember: Posts with polls  in them
 cannot be edited but they may be deleted.<br />
  ";
  if (!$config['allowpolls']) {
    echo "<i>Only admins and moderators may use this feature.</i><br />";
  }
  echo " 
  To add a poll to your post, use this format:<p>
  [pollstart]<br />
  [polltitle=Name of your poll]<br />
  [polloption=First Choice]<br />
  [polloption=Second Choice]<br />
  [polloption=As many choices as you would like]<br />
  [pollstop]


                

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"moreposts\"><h3>Jeg vil gerne se flere (eller færre) indlæg pr. side.</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Antallet af indlæg, der skal vises pr. side, kan ændres i profilen. Antallet kan sættes til alt mellem 1 og 99 indlæg pr. side. Det er som standard sat til {$theme['postsperpage']} indlæg pr. side.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <a name=\"buttons\"><h3>Hvad er alle de knapper til for?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  Knapperne bruges til navigation og til at vise indlæg. Nogle af knapperne skifter funktion alt efter hvilken side, man befinder sig på.
  <p>Når oversigten over alle tråde i et forum bliver vist:
  <br />- Pilene til venstre og højre fører til den forrige eller næste side med indlæg. 
  <br />- Pilen opad fører til oversigten over alle fora.
  <br />- \"Nyt indlæg\"-knappen bruges, hvis man vil skrive et nyt indlæg til forumet.
  <br />- Plus- og minusknapperne skifter mellem at vise trådene udtrukket og sammentrukket.
  <p>Når en enkelt tråd bliver vist:
  <br />- Pilene til venstre og højre fører til henholdsvis forrige og næste tråd.
  <br />- Pilen opad fører tilbage til oversigten over alle indlæg på den pågældende side.
  <br />- \"Fladt\"-knappen gør, at man kan se alle indlæg i en given tråd på &eacute;n side.
  <br />- \"I tråde\"-knappen gør, at man ser et enkelt indlæg i en tråd, og så ser alle svar på det indlæg som en \"tråd\" nedenunder.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"sortorder\"><h3>Hvorfor er Emne, Afsender og Dato links?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Klik på en af dem for at ændre rækkefølgen, indlæggene bliver vist i. Klikker man på Emne en enkelt gang, sorteres indlæggene efter emne i omvendt alfabetisk rækkefølge. Klikker man på Emne endnu engang, sorteres indlæggene efter Emne i alfabetisk rækkefølge. Afsender og Dato fungerer på samme måde.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"email\"><h3>Hvorfor skal man indtaste <i>to</i> e-postadresser?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Den ægte e-postadresse bruges, når forumsystemet skal sender en ens adgangskode, og når man skal have besked om, at der er kommet svar på ens indlæg, eller at der er kommet private beskeder. Den anden e-postadresse er den, som er synlig for andre brugere, når de ser ens profil. Der er mange mennesker, der ikke ønsker at offentliggøre deres e-postadresse, men vi er nødt til at bede folk om at skrive deres rigtige e-postadresse, således at de kan få deres adgangskode tilsendt. Dem, der ikke ønsker deres e-postadresse offentliggjort, kan så skrive en ægte e-postadresse, som kun vi får at se, samt en falsk e-postadresse, som almindelige brugere kan se.


  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"register\"><h3>Giv mig bare &eacute;n god grund til, at jeg skulle registrere et brugernavn!</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Hvis man registrerer et brugernavn, har man mulighed for at oprette en profil, således at man ikke behøver at indtaste oplysninger om sig selv hver eneste gang, man skriver et indlæg. Desuden har brugere også mulighed for at indrette udseendet af forumet efter deres egen smag. Brugere kan også nemt og hurtigt se, om der er kommet nye indlæg siden de sidst besøgte forumet - denne fordel har anonyme brugere ikke.

  </td></tr></table>  </td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
