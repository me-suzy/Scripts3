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
   require ("./main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// ---------------------
// Send the page to them
   $html -> send_header("Hjälp/Frågor",$Cat,0,$user);
   $html -> table_header("Hjälp/Frågor");

   $phpurl = $config['phpurl'];

   $html -> open_table();
   echo " 
   <tr class=\"darktable\">
   <td>
  Nedan ser du en lista över de vanligaste frågorna som vi får. Du kan klicka på dem för att få svar på någonting du kanske har problem med eller undrar över. Skicka förslag till <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a> om det är något du tycker saknas bland frågorna.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#register\">Varför skall jag registrera ett användarnamn?</a><br />
  <a href=\"#email\">Varför frågas det efter två e-postadresser?</a><br />
  <a href=\"#cookies\">Måste jag acceptera cookies?</a><br />
  <a href=\"#login\">Hur loggar jag in?</a><br />
  <a href=\"#help\">Hjälp! Jag har problem med inloggningen!</a><br />
  <a href=\"#titlestring\">Vad betyder texten som står nedanför mitt användarnamn i inläggen?</a><br />
  <a href=\"#titles\">Vad är titlarna för något?</a><br />
  <a href=\"#password\">Jag har glömt mitt lösenord!</a><br />
  <a href=\"#changepassword\">Hur ändrar jag mitt lösenord?</a><br />
  <a href=\"#image\">Hur lägger jag till en bild i mitt inlägg?</a><br />
  <a href=\"#limit\">Finns det någon begränsning på storleken på bilder i signaturen?</a><br />
  <a href=\"#attach\">Kan jag bifoga en fil i mitt inlägg?</a><br />
  <a href=\"#rules\">Vad är reglerna för forumet?</a><br />
  <a href=\"#editprof\">Hur ändrar jag min profil?</a><br />
  <a href=\"#editdisp\">Hur ändrar jag mina visningsinställningar?</a><br />
  <a href=\"#subject\">Varför är det så viktigt med ett ärende till inläggen</a><br />
  <a href=\"#answer\">Hur hittar jag inlägg som redan har svar på min fråga?</a><br />
  <a href=\"#flash\">Vad betyder det blinkande kuvertet i menyn?</a><br />
  <a href=\"#letters\">Vad betyder bokstäverna i Mottagna privata meddelanden?</a><br />
  <a href=\"#url\">Jag får inte URL-taggarna att fungera!</a><br />
  <a href=\"#whitespace\">Hur får jag mellanrum mellan stycken i mina inlägg?</a><br />
  <a href=\"#spellchecker\">Finns det stavningskontroll?</a><br />
  <a href=\"#mistake\">Om jag gör ett misstag i mitt inlägg?</a><br />
  <a href=\"#wrongforum\">Vad gör jag om jag skrev i fel forum?</a><br />
  <a href=\"#html\">Kan jag använda html i mitt inlägg?</a><br />
  <a href=\"#polls\">Hur lägger jag in en röstningsfunktion i mitt inlägg?</a><br />
  <a href=\"#moreposts\">Jag vill se fler (eller färre) inlägg per sida.</a><br />
  <a href=\"#buttons\">Hur fungerar alla knappar?</a><br />
  <a href=\"#sortorder\">Varför är Ämne, Skribent, Senaste inlägg klickbara?</a><br />
  <a href=\"#source\">Kan jag köra mitt eget forum?</a>
  <p>
  ";
  $html -> close_table();

  echo "
  <p>&nbsp;
  ";

  $html -> open_table();

  echo "
  <tr class=\"darktable\"><td>
  <h3><a name=\"register\">Varför skall jag registrera ett användarnamn?</a></h3>
<p>
</td></tr><tr><td class=\"lighttable\">
Genom att registrera ett användarnamn, får du möjlighet att ha en egen profil med egna forumsinställningar. Du kommer få mer ut av din vistelse här genom att ändra din profil och personliga inställningar efter smak det blir lättare för andra att se vem du är och lättare att smälta in i gemenskapen, så ta dig gärna tid för att fylla i de olika inställningarna. Dessutom är det endast registrerade användare som kan se hur många nya inlägg som gjorts sedan förra besöket.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"email\">Varför frågas det efter två e-postadresser?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Den riktiga e-postadressen är till för upplysning, utskick av lösenord och forumsutskick. Den andra är den som användare ser när dom tittar på din profil. Vi inser att man kanske inte vill att alla skall se den normala e-postadressen, men vi måste ha den för att ha möjlighet att ge dig information och vidarebefordring via e-post när någon svarat på ditt inlägg m.m. Med anledning av detta kan du ge oss din riktiga e-postadress som bara vi ser och du kan visa en annan för allmänheten. En del skriver sin e-post som scream\@no.spam.domain.com. På det sättet kan folk lista ut din riktiga e-postadress, men spamming-agenter kan inte bara scanna sidan för att spamma dig.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"cookies\">Måste jag acceptera cookies?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Ja. Cookies är till för att spåra ditt användarnamn/lösenord och för att visa dig vilka inlägg som är nya sen du var inne senast. Om man inte tillåter cookies kommer alla funktioner inte funka som det skall.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"login\">Hur loggar jag in?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Efter att du har skapat ett konto (användarnamn) på dessa forum så måste du logga in för att kunna ta del av de personliga inställningarna. Uppe till höger i webbläsaren finns en Logga in-länk som du klickar på för att logga in. Länken tar dig till en sida där du fyller i ditt Användarnamn och Lösenord. Kom ihåg att det är skillnad på små och stora bokstäver i lösenordet.
<p>
Efter att du har fyllt i ditt Användarnamn och Lösenord så kommer du föras till en sida som kallas Startsida. Om du har Privata meddelanden så kommer ett blinkande kuvert att synas till vänster om menyraden. Genom att klicka på länken Huvudsida i menyn högt upp på sidan så kommer du in till själva forumen.

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"help\">Hjälp! Jag har problem med inloggningen!</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Här är en checklista som kan hjälpa dig att logga in:
<p>
 1) Se till att du skriver in ditt lösenord korrekt. Det är skillnad på små och stora bokstäver.<br />
 2) Din webbläsare måste tillåta cookies, undersök vilken säkerhetsnivå du använder i din webbläsare. Höga säkerhetsnivåer i vissa webbläsare gör automatiskt att cookies inte tillåts. För att kunna utnyttja viktiga funktioner på dessa forum måste du tillåta cookies.
 3) Logga ut helt och hållet genom att klicka på Logga ut-länken på sidan och försök sedan logga in igen.
 4) Efter att du har loggat in kan du behöva klicka på Uppdatera i din webbläsare för att autentiseringen ska genomföras.
 5) Om du fortfarande har problem så gå till inloggningssidan. Där fyller du in ditt användarnamn i fältet för Användarnamn och klickar på knappen \"Jag har glömt mitt lösenord\". Ett tillfälligt lösenord kommer att genereras och skickas till den e-postadress du uppgav när du registrerade dig. Du MÅSTE fylla i ditt användarnamn i Användarnamns-fältet innan denna funktion kan utnyttjas.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"titlestring\">Vad betyder texten som står nedanför mitt användarnamn i inläggen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Det är en Användartitel som genereras av systemet - stranger, newbie, journeyman, member etc. Du avancerar genom de olika nivåerna baserat på det antal inlägg som du har gjort på forumet.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"titles\">Vad är titlarna för något?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Alla på forumet har en titel, dessa visas nedanför användarnamnet i inläggen. Vissa titlar har tillsatts automatiskt beroende på hur många inlägg de har gjort, medan andra titlar har satts av forumet ägare för att ge officiell status för företagsrepresentativa eller andra VIP på forumen.
  <p>
  Här är en lista över standardtitlarna och antalet inlägg som måste göras för att nå upp till dem:
  <p>
  <pre>";
  readfile("{$config['path']}/filters/usertitles");

  echo "
  </pre>
  <br />

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"password\">Jag har glömt mitt lösenord!</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Oroa dig inte om du har glömt ditt lösenord! Du kan få ett tillfälligt lösenord skickat till dig väldigt enkelt. Det enda du behöver göra är att gå till inloggingssidan för forumen och fylla i ditt användarnamn i fältet för Användarnamn. Klicka sedan på knappen \"Jag har glömt mitt lösenord\" så kommer ett tillfälligt lösenord att skickas till den e-postadress du angav när du registrerade dig.
<p>
Denna process är säker eftersom lösenordet bara skickas till den som registrerade kontot. Det går inte att stjäla andras lösenord med denna funktion.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"changepassword\">Hur ändrar jag mitt lösenord?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Du kan ändra ditt lösenord när som helst, det enda du behöver göra är att klicka på {$ubbt_lang['CONTROL_PANEL']}-länken som finns på varje sida. Därefter klickar du på \"Personlig information, e-postadress, lösenord, osv.\" under Huvudinställningar längst ner på sidan. Fyll i fälten för lösenord och verifiering av lösenordet på denna sida för att ändra ditt lösenord. När du fyllt i det nya lösenordet så klickar du på Skicka-knappen längst ner på sidan för att spara informationen. (Kom ihåg att det är skillnad på små och stora bokstäver i lösenordet.)


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"image\">Hur lägger jag till en bild i mitt inlägg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
För att kunna lägga in en bild i ett inlägg eller i din signatur så måste den redan finnas tillgänglig på en webbserver. Detta kan vara en bild på din egna hemsida till exempel. För att lägga bilden i ett meddelande så använder du denna Märknings-kod:
<p>
[image]http://www.url_to_image.com/image_name.gif[/image]
<p>
Om du till exempel vill ha en bild som heter cateye.gif och den finns på din webbplats som har adressen http://www.mywebsite.com/pics så skulle du använda följande märkning:
<p>
	[image]http://www.mywebsite.com/pics/cateye.gif[/image]
<p>
Du kan göra likadant i din signatur. Klicka på {$ubbt_lang['CONTROL_PANEL']}-länken som finns på varje sida och därefter på \"Personlig information, e-postadress, lösenord, osv.\" under rubriken Huvudinställningar. Leta efter signatur-rutan och fyll i den information du vill ska visas efter alla dina inlägg, inklusive bild-märkningar enligt ovan.
<p>
OBS! För att forumen ska kunna ladda snabbt för alla besökare så rekommenderar vi att du inte går över 35k för någon av dina bilder.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"limit\">Finns det någon begränsning på storleken på bilder i signaturen?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Vi rekommenderar att du håller dina bilder relativt små. I regel bör du inte gå över 125 x 600 pixlar och/eller 35k för en signatur-bild. Detta är för att forumen ska ladda så snabbt som möjligt för alla användare.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"attach\">Kan jag bifoga en fil i mitt inlägg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Om du har en browser som är kompatibel med Mozilla 4+ så är svaret ja.  När du förhandsgranskar ditt inlägg kommer du ha möjlighet att bifoga en fil.
    ";
  }
  else {
    echo "Nej.  Fil tillägg är borttaget i denna versionen.";
  }

  echo "
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"rules\">Vad är reglerna för forumet?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
När man registrerar sig som användare på forumet så går man med på följande regler:
- Besökare ska inte skriva inlägg som kan skapa anstöt, skyddad av upphovsrätt, varumärkesskyddad eller lagligt skyddad på annat vis - utan att få tillåtelse av ägaren av upphovsrätten - eller som innehåller personliga telefonnummer eller adresser.<br />
- Besökare får inte använda forumen för att skriva eller vidarebefodra reklam för kommersiella aktörer av någon typ.<br />
- Moderatorerna i varje forum har rätten att ändra, censurera, ta bort eller på annat sätt modifiera alla skrivna inlägg.<br />
- Denna webbplats garanterar inte kvaliteten på inlagda texter på forumet och bär inget ansvar för eventuell förlorad information, skada eller annat orsakat av något inlägg på forumen.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"editprof\">Hur ändrar jag min profil?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Klicka på {$ubbt_lang['CONTROL_PANEL']}-länken i menyn högt upp på sidan. Längst ner på sidan, under rubriken Huvudinställningar, finns länkar till att ändra dina olika inställningar. Klicka på \"Personlig information, e-postadress, lösenord, osv.\" för att ändra din personliga information.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"editdisp\">Hur ändrar jag mina visningsinställningar?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Om du inte tar del av de många olika inställningsmöjligheter som finns i UBB.threads så missar du en stor del av kraften i forumsystemet. Många delar av hur forumet visas kan anpassas efter ditt eget tycke. Att ändra detta är ungefär som att ändra din användarprofil, men i detta fall så klickar du på \"Visningsinställningar...\" under Huvudinställningar efter att du har klickat på {$ubbt_lang['CONTROL_PANEL']}-länken i menyn.
<p>
Du kan välja vilket språk som du vill använda på forumen, vilken stylesheet som ska användas för känsla och utseende, hur många inlägg som ska visas per sida, om du vill se andra användares bilder med deras inlägg och mycket, mycket mer. När du har sparat informationen så kommer detta bli dina standardinställningar. Du kan ändra dessa inställningar igen när som helst.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"subject\">Varför är det så viktigt med ett ärende till inläggen</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Det första inlägget i en tråd sätter det ärende som alla efterföljande svar kommer att visas med. Efter att någon har svarat på huvudinlägget så kan inte trådens ärende ändras. Därför är det viktigt att ärendet blir rätt redan från början, ärendet bör vara så beskrivande och specifikt som möjligt. Till exempel, 'Frågor om LCD-inbränning' är mycket bättre än något helt generellt, som till exempel 'Nybörjare behöver hjälp!'. Det är inte bara större chans att man får ett svar utan det gör att det blir mycket enklare att hitta svaren till tråden.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"answer\">Hur hittar jag inlägg som redan har svar på min fråga?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
En stor fördel med diskussionsforum är att vanligt förekommande frågor kan besvaras en gång så kan alla läsa svaret. Innan du ställer en fråga så är det alltid bra om du ser om den ställts tidigare - och kanske redan besvarats. Du kan göra detta via Sök-länken i menyn. Det finns flera olika sökinställningar, både hur sök-texten är angiven och hur stor del av forumen du vill söka i. Det är här som det märks att det är viktigt att ha ett välspecifierat ärende till inlägg, då det blir mycket enklare att hitta vad man söker efter i sökresultatet.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"flash\">Vad betyder det blinkande kuvertet i menyn?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Det betyder att du har olästa Privata meddelanden.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"letters\">Vad betyder bokstäverna i Mottagna privata meddelanden?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
'N' betyder att meddelandet är oläst.<br />
'R' betyder att du har besvarat meddelandet.<br />
Ett mellanslag betyder att du har läst meddelandet men inte besvarat det.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"url\">Jag får inte URL-taggarna att fungera!</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Om taggarna visas i texten, eller om du får en länk som pekar till http:/// så har du några extra mellanslag i syntaxen. Ta bort alla extra mellanslag så kommer allt att fungera bra.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"whitespace\">Hur får jag mellanrum mellan stycken i mina inlägg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Genom att trycka på retur-tangenten två gånger där du vill ha en tom rad. Om du väljer att förhandsgranska dina inlägg så har du möjligheten att göra extra förändringar innan du skickar iväg inlägget.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"spellchecker\">Finns det stavningskontroll?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Nej. Om du vill vara säker på att du har stavat allting korrekt så kan du göra en stavningskontroll i en textredigerare och sedan klippa och klistra texten till forumets inläggsruta.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"mistake\">Om jag gör ett misstag i mitt inlägg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Användare kan ändra sina inlägg upp till {$config['edittime']} timmar efter att de skrevs. Om det är stora förändringar så bör du markera att inlägget har ändrats så att andra blir uppmärksammade på det ändrade innehållet. För kosmetiska förändringar är det bättre att inte markera det som ändrat.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"wrongforum\">Vad gör jag om jag skrev i fel forum?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Meddela forumets Administratör, han/hon kan flytta det åt dig.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"html\">Kan jag använda html i mitt inlägg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Det finns två tillvägagångssätt att använda html i ditt inlägg. Om html är aktiverat då kommer du se <b>Html är aktiverat</b> och då kan du använda vanlig html i ditt inlägg. Om UBBCode är aktiverat kommer du se <b>UBBCode är aktiverat</b>. Följande taggar finns tillgängliga om UBBCode är aktiverat:
  <br /><br />
  <font class=\"standouttext\">
  [b]
  </font>
  text
  <font class=\"standouttext\">
  [/b]
  </font>
         = Gör den valda texten till fetstil.<br />

  <font class=\"standouttext\">
  [e-post]
  </font>
  joe@blow.com
  <font class=\"standouttext\">
  [/e-post] 
  </font>
  = Gör den valda e-postadressen klickbar.<br />

  <font class=\"standouttext\">
  [i]
  </font>
  text
  <font class=\"standouttext\">
  [/i]
  </font>
         = Gör den valda texten kursiv.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=\"standouttext\">";
    echo"[bild]</font>";
    echo"url";
    echo "<font class=\"standouttext\">";
    echo "[/bild]</font>  = Infoga en vald url i en img src-tagg.<br />";
  }

  echo "

  <font class=\"standouttext\">
  [pre]
  </font>
  text
  <font class=\"standouttext\">
  [/pre]
  </font>
   = Omge den valda texten med pre-taggar.<br />

  <font class=\"standouttext\">
  [citat]
  </font>
  text
  <font class=\"standouttext\">
  [/citat] 
  </font>
  = Markerar vald text som citat från föregående inlägg.<br />

  <font class=\"standouttext\">
  [url]
  </font>
  länk
  <font class=\"standouttext\">
  [/url]</font>    = Gör en vald url till en länk.<br />

  <font class=\"standouttext\">
  [url=link]
  </font>
  title
  <font class=\"standouttext\">
  [/url]</font>    = Gör en text till hyperlänk pekande mot en länk.<br />


  <font class=\"standouttext\">
  [list]<br />
  [*]Element 1<br />
  [*]Element 2<br />
  [/list]</font> =  Gör en punktlista. [list=A] eller [list=1] gör sorterad respektive numrerad lista.<br />

  <font class=\"standouttext\">
  [rodna]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=\"standouttext\">
  [cool]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=\"standouttext\">
  [galen]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=\"standouttext\">
  [besviken]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=\"standouttext\">
  [skratt]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=\"standouttext\">
  [arg]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=\"standouttext\">
  [chockad]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=\"standouttext\">
  [ler]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=\"standouttext\">
  [tunga]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=\"standouttext\">
  [blink]
  </font> = <img src=\"{$config['images']}/graemlins/wink.gif\"><br />


  <font class=\"standouttext\">
  [color:red]
  </font>
  text
  <font class=\"standouttext\">
  [/color]
  </font>
  = Gör den givna texten röd.<br />

  <font class=\"standouttext\">
  [color:#00FF00]
  </font>
  text
  <font class=\"standouttext\">
  [/color]
  </font>
  = Gör den givna texten grön.<br />


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\">Hur lägger jag in en röstningsfunktion i mitt inlägg?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Att skapa en omröstning i ditt inlägg är enkelt, men kom ihåg: ett inlägg med en omröstning kan inte redigeras men det går att radera inlägget.<br />
  ";
  if (!$config['allowpolls']) {
    echo "<i>Bara administratörer och moderatorer kan använda sig av detta.</i><br />";
  }
  echo " 
  För att bifoga röstfunktion i ditt inlägg, använd detta format:<p>
  [pollstart]<br />
  [polltitle=Namnet på omröstningen]<br />
  [polloption=första valet]<br />
  [polloption=andra valet]<br />
  [polloption=Så många val du önskar]<br />
  [pollstop]



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"moreposts\">Jag vill se fler (eller färre) inlägg per sida.</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Du kan ändra antalet inlägg som skall visas per sida genom att ändra inställningarna i din profil. Du kan välja från 1 till 99 inlägg per sida. Detta är förinställt till {$config['postsperpage']} inlägg per sida.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <h3><a name=\"buttons\">Hur fungerar alla knappar?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  Knapparna är till för att navigera och göra inlägg. Dessa kan ha olika funktion beroende på var du är i forumet, se nedan:
  <p><b>Vid visning av trådlista:</b>
  <br />- Denna <img src=\"{$config['images']}/previous.gif\"> och denna <img src=\"{$config['images']}/next.gif\" > knapp tar dig till föregående respektive nästa sida med inlägg. 
  <br />- Denna <img src=\"{$config['images']}/all.gif\" > knapp kommer ta dig till listan över alla tillgängliga forum.
  <br />- Denna <img src=\"{$config['images']}/newpost.gif\" > knapp låter dig göra ett nytt inlägg i forumet.
  <br />- Denna <img src=\"{$config['images']}/expand.gif\" > och denna <img src=\"{$config['images']}/collapse.gif\" > knapp låter dig växla mellan expanderad och minimerad trådvisning. Expanderad tråd visar alla huvudinlägg med svar i trådat format. Minimerad tråd visar huvudinlägget samt en siffra på antal svar som skrivits till inlägget.
  <p><b>Visning av en tråd:</b>
  <br />- Denna <img src=\"{$config['images']}/previous.gif\" > och denna <img src=\"{$config['images']}/next.gif\" > knapp tar dig till föregående respektive nästa inlägg.
  <br />- Denna <img src=\"{$config['images']}/all.gif\" > knapp kommer ta dig tillbaka till listan över alla inlägg på den sidan.
  <br />- Denna <img src=\"{$config['images']}/flat.gif\" > knapp låter dig se hela tråden med tillhörande svar på en sida.
  <br />- Denna <img src=\"{$config['images']}/threaded.gif\" > knapp visar det aktuella inlägget och rubrikerna på alla svarsinlägg i trådat format under inlägget.
  <br />- Denna <img src=\"{$config['images']}/reply.gif\" > knapp låter dig svara på ett inlägg.
  <br />- Denna <img src=\"{$config['images']}/edit.gif\" > knapp låter dig redigera ett inlägg.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"sortorder\">Varför är Ämne, Skribent, Senaste inlägg klickbara?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Du kan klicka på dessa för att sortera inläggen. Om du klickar på Ämnen en gång, kommer dessa sorteras i motsatt alfabetisk ordning. Om du klickar igen sorteras ämnen i alfabetisk ordning. Skribent och senaste inlägg fungerar liknande.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\"> 
  <h3><a name=\"source\">Kan jag köra mitt eget forum?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Ja, UBB.threads finns tillgängligt på <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
";

  $html -> close_table();
// -------------
// Send a footer
  $html -> send_footer();

?>
